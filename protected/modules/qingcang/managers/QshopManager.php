<?php
/**
 * 清仓店铺管理
 * @author linglingqi@
 * @version 2015-07-30
 */
class QshopManager extends Manager {


	//清仓后台获取商家的数据
	public static function getQingcangShopInfos($shop_ids, $event_id, $realStatus=QcheckManager::STATUS_APPLY) {

		if (!$shop_ids) {
			return array();
		}

		!is_array($shop_ids) && $shop_ids = array($shop_ids);

		$shops = array();
		$shop_info = self::getShopInfo($shop_ids);
		$search = new SearchManager();
		foreach($shop_info as $shop_id=>$info) {

			$shops[$shop_id] = $info;
			//本自然月参加次数
			$time =  date("Y-m-d", strtotime("-1 month"));
			$a_month_join = QsheduleManager::getShopJoinEvent($shop_id, $time);
			$shops[$shop_id]['had_join_count'] = count($a_month_join);
			$history = QsheduleManager::getLastTime($shop_id);
			$shops[$shop_id]['history']['event_id'] = isset($history['event_id']) ? $history['event_id'] : '';
			$shops[$shop_id]['history']['time'] = (isset($history['start_time']) || isset($history['end_time'])) ? $history['start_time'] .'-'. $history['end_time'] : '';

			//待审核商品
			$param = array(
					'shop'		=>	$shop_id,
					'realStatus'=>	$realStatus,
					'type'		=>	1,
					'event'		=>	$event_id,
			);
			$param = array_merge($param, self::defaultParams());
			$uncheck = $search->getTwitterList($param);
			$shops[$shop_id]['uncheck'] = count($uncheck);
			$shops[$shop_id]['level'] = ShopManager::$shopLevel[$info['level']];
			$shops[$shop_id]['area'] = ShopManager::$shopAreaArr[$info['area']];
		}
		return $shops;

	}



	/**
	 * 根据店铺ID获取店铺详情
	 * @param  $shop_id
	 */
	public static function getShopInfo($shop_ids) {

		if (!$shop_ids) {
			return array();
		}

		!is_array($shop_ids) && $shop_ids = array($shop_ids);
		$where = implode("','", $shop_ids);

		$sql = "select * from `brd_shop_groupon_shops_relation` where shop_id in ('" .$where ."')";
		$db = yii::app()->sdb_brd_shop;

		$result = $db->createCommand($sql)->queryAll();

		$shop = new ShopManager();
		foreach ($result as $key=>$val) {
			$result[$key]['category'] = $shop->getShopCategory($val['major']);
		}

		return ArrFomate::hashmap($result, 'shop_id');
	}

	/**
	 * 查询时的默认参数
	 */
	public static function defaultParams() {
		return array(
    			'catagory' =>	0,
    			'type'		=>	1,
    			'level'		=>	'#',
    			'isshow_tag'=> '#',
    	);
	}


}
?>