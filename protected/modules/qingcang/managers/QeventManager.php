<?php
/**
 * 清仓店活动管理
 * @author linglingqi@meilishuo.com
 * @version 2015-07-30
 */
class QeventManager extends Manager {

	/**
	 * 获取商品下的图片
	 * @param int $eventId
	 * @param int $areaId 区块id
	 * @param int $areaSub  区块下子id
	 * @return array
	 */
	static public function getEventGoodsList($eventId, $areaId='', $areaSub='') {

		if (!$eventId) return array();

		$sql = "select event.id,event.twitter_id,event.event_id,event.category,event.area,event.rank,event.shop_id,event.groupon_id";
		$sql .= ",groupon.start_time, groupon.end_time,groupon.goods_name,groupon.goods_image,groupon.off_num,groupon.off_price,groupon.audit_status";
		$sql .= " from tuan_events_item_detail as event join shop_groupon_info groupon on
		 event.groupon_id=groupon.id where event_id={$eventId} AND category>0 and groupon.audit_status=50";

		$where = "";
		if (is_numeric($areaId)) {
			$where .= " AND event.area={$areaId}";
		}
		if (is_numeric($areaSub)) {
			$where .= " AND event.area_sub={$areaSub}";
		}
		if ($where) {
			$sql .= $where;
		}

		$order = " ORDER BY event.rank DESC";

		$sql .= $order;

		$sdb_brd_shop   = Yii::app()->sdb_brd_shop;
		$eventGoodsList = $sdb_brd_shop->createCommand($sql)->queryAll();

		$info = ArrFomate::hashmap($eventGoodsList, 'twitter_id');
		//获取商品的gmv
		$g_man = new GoodsManager();
		$tmps = $g_man->getGoodsMoreInfoByTids(array_keys($info));
		$good_gmvs = array();
		foreach ($tmps as $val) {
			$good_gmvs[$val['twitter_id']] = $val['gmv'];
		}
		//得到店铺ID列表，以店铺gmv排序；获取店铺下排序的商品
		$shop = array();
		foreach($eventGoodsList as $val) {
			$shop[$val['shop_id']]['good'][$val['twitter_id']] = $val;
		}
		$sinfo =QshopManager::getShopInfo(array_keys($shop));

		$list_info = QdonlineManager::getInfoByshopidBat(array_keys($shop), $eventId);
		$list_info = ArrFomate::hashmap($list_info, 'shop_id');
		$data = array();
		foreach($shop as $id=>$shopInfo) {
			$good_info = $shopInfo['good'];
			if (!$good_info) {
				unset($shop[$id]);
			}
			$tmp = current($shopInfo['good']);
			//获取店铺信息；对店铺下的商品排序处理
			$shop[$id]['shop_id'] = $id;
			$shop[$id]['shop_nick'] = $sinfo[$id]['shop_nick'];
			$shop[$id]['gmv'] = $sinfo[$id]['gmv_30'];
			$shop[$id]['rank'] = $tmp['rank'] ? $tmp['rank'] : 0;
			$shop[$id]['id']= $list_info[$id]['id'];
			//店铺下的商品排序处理
			if(isset($list_info[$id]['twitters'])) {
				$twitters = unserialize($list_info[$id]['twitters']);
			}
			foreach ($good_info as $gk=>$ginfo) {
				if($twitters) {
					$good_info[$gk]['sort'] = isset($twitters[$ginfo['twitter_id']]) ? $twitters[$ginfo['twitter_id']] : 100;
				} else {
					$good_info[$gk]['sort'] = 100;
				}
				$good_info[$gk]['gmv'] = isset($good_gmvs[$gk]['gmv']) ? $good_gmvs[$gk]['gmv'] :0;
			}
			$good_info = ArrFomate::sortByCol($good_info, 'gmv', 'SORT_DESC');
			$good_info = ArrFomate::sortByCol($good_info, 'sort', 'SORT_ASC');
			$shop[$id]['good'] = array_slice($good_info, 0, 3);
		}
		$shop = ArrFomate::sortByMultiCols($shop, array('rank'=>'SORT_ASC','gmv'=>'SORT_DESC'));


		if ($shop) {
			return $shop;
			//$sql =
		} else {
			return array();
		}
	}

}
