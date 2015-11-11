<?php
/**
 * 清仓店铺排期管理
 * @author linglingqi@
 * @version 2015-07-30
 */
class QensureScheduleManager extends Manager {

	const NEW_SORT_TYPE = 3;//今日上新
	const LAST_SORT_TYPE = 1; //最后疯抢
	const PRE_SORT_TYPE = 2; //明日预告
	const POPULAR_SORT_TYPE = 4; //畅销榜单
	const ALL_SORT_TYPE = 0; //全部榜单

	public static function getShopType() {
		return array(
				self::ALL_SORT_TYPE		=>	'全部',
				self::NEW_SORT_TYPE 	=> '今日上新',
				self::LAST_SORT_TYPE	=> '最后疯抢',
				self::PRE_SORT_TYPE		=>	'明日预告',
				self::POPULAR_SORT_TYPE	=>	'往日热销',
		);
	}

	public static function getEventShopType() {
		return array(
				self::ALL_SORT_TYPE		=>	'全部',
		);
	}

	/**
	 * 根据条件获取店铺列表
	 * @param  $event_id	活动ID
	 * @param  $shop		店铺ID，可以为空
	 * @param  $type		类型，默认为全部
	 */
	public static function getShopList($event_id, $shop_id='', $type=self::ALL_SORT_TYPE) {

		$shops = QdonlineManager::getShopList($event_id, $shop_id, $type);
		//如果是全部店铺，全部返回
		if ($type == self::ALL_SORT_TYPE) {
			return $shops;
		}

		//榜单店铺，获取店铺下面的商品数展现
		foreach ($shops as $key=>$val) {
			$start_time = $val['start_time'];
			$end_time = $val['end_time'];
			$shops[$key]['total_tid'] = count(QsheduleManager::getScheduleShopTids($val['shop_id'], $event_id, $start_time, $end_time));
		}
		if($type == QensureScheduleManager::POPULAR_SORT_TYPE) {
			//按照流行度排序的商品需要按照销量排序
			$shops = QensureScheduleManager::sortBySale($shops);
		}

		return $shops;
	}

	/**
	 * 店铺根据30天的销售量排序
	 * @param  $shops 数组；店铺信息
	 */
	public static function sortBySale($shops) {

		$shop_ids = array_keys(ArrFomate::hashmap($shops, 'shop_id'));

		$shop = new ShopManager();
		$shop_infos = $shop->getShopDetailInfo($shop_ids);

		$shop_list = array();
		foreach ($shops as $key=>$val) {
			$sid = $val['shop_id'];
			$shop_list[$key] = $val;
			$shop_list[$key]['sale_num'] = $shop_infos[$sid]['paid_goods_num_30'];
		}

		return ArrFomate::sortByCol($shop_list, 'sale_num', 'SORT_DESC');
	}

	/**
	 * 店铺排序
	 * @param unknown $sort
	 * @param unknown $type
	 */
	public static function sortShopList($sort, $type){

    	if (empty($sort) || !$type) return false;
    	$sort = rtrim($sort,',');
    	$index = explode(',',$sort);

    	if ($type == self::NEW_SORT_TYPE) {
    		$key = 'sort_create';
    	} elseif ($type == self::LAST_SORT_TYPE) {
    		$key = 'sort_last';
    	} elseif ($type == self::PRE_SORT_TYPE) {
    		$key = 'sort_prevue';
    	} else {
    		return false;
    	}

    	$result = false;
    	$now = time();
    	foreach ($index as $skey => $val){
    		$date = date('Y-m-d H:i:s',$now-($skey*10));
    		$skey = $skey + 1;
    		$up = " $key=$skey ";
    		$where = " where id = {$val}";
    		$result = QdonlineManager::updateByWhere($up, $where);
    	}
    	return $result;
    }

// 	/**
// 	 * 获取店铺下的商品的推ID
// 	 * @param  $shop_id
// 	 * @param  $event_id
// 	 * @param  $start_time
// 	 * @param  $end_time
// 	 */
// 	private static function getScheduleShopTids($shop_id, $event_id, $start_time, $end_time) {

// 		$params = array(
// 				'shop'		=>	$shop_id,
// 				'event'		=>	$event_id,
// 				'realStatus'=>	QcheckManager::STATUS_SCHEDULE_PASS,
// 				'from'	=>	$start_time,
// 				'to'	=>	$end_time
// 		);

// 		$params = array_merge($params, QshopManager::defaultParams());

// 		$search =  new SearchManager();
// 		$infos = $search->getTwitterList($params, 0, 1, 1);
// 		return $infos;
// 	}

	public static function isHadScheduleShop($shop_id, $event_id) {

		$list = QdonlineManager::getShopList($event_id, $shop_id);
		$time = time();
		$has = array();
		foreach ($list as $val) {
			$start = strtotime($val['start_time']);
			$end = strtotime($val['end_time']);
			//未开始
			if($time<=$start && $time<$end) {
				$has[] = $val;
			}elseif ($time>$start && $time<$end) {
				$has[] = $val;
			}
		}
		return $has;
	}

	public static function formateBanner($banner) {
		if (!$banner) {
			$banner = array('icon'=>'');
		}

		if (!is_array($banner)) {
			$banner = array('icon'=>$banner);
		}
		return json_encode($banner);
	}
}
?>