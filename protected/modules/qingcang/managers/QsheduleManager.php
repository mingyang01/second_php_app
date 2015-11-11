<?php

/**
 * 排序商品和店铺管理
 * @author linglingqi
 * @version 2015-07-31
 */
class QsheduleManager extends Manager{

	/**
	 * 审核通过添加商品数据
	 * @param  $event_id
	 * @param  $shop_id
	 * @param  $start_time
	 * @param  $end_time
	 * @param  $banner  店铺banner
	 * @param  $sort_last
	 * @param  $sort_create
	 * @param  $sort_prevue
	 * @param  $title
	 */
	public static function addInfo($event_id, $shop_id, $start_time, $end_time, $title="超低清仓", $banner=array(), $sort_last=999, $sort_create=1, $sort_prevue=1) {

		if (!$event_id || !$shop_id) {
			return false;
		}
		//从报名表中的数据判断，是否改店铺下面有审核通过的商品，如果有才允许审核通过，如果没有，不能审核通过
//     	$infos = self::getScheduleShopTids($shop_id, QcommManager::EVENT_ID, $start_time, $end_time);
//     	if (!$infos) {
//     		return false;
//     	}

    	return QdonlineManager::addInfo($event_id, $shop_id, $start_time, $end_time, QensureScheduleManager::formateBanner($banner), $sort_last, $sort_create, $sort_prevue, $title);
	}

	/**
	 * 获取最近参加活动的店铺下的商品
	 * @param $shop_id string 店铺ID
	 */
	public static function getLastTime($shop_id) {

		if (!$shop_id) {
			return false;
		}

		$shops = QdonlineManager::getLastTime($shop_id);

		$ret = array();
		foreach ($shops as $key=>$val) {
			$start_time = $val['start_time'];
			$end_time = $val['end_time'];
			if (self::getScheduleShopTids($shop_id, QcommManager::EVENT_ID, $start_time, $end_time)) {
				$ret = $val;
			}
		}
		return $ret;
	}


	/**
	 * 获取最近一段时间店铺参加清仓活动的次数
	 * @param shop_id 店铺ID
	 * @param time 希望获取店铺参加活动的时间条件
	 * @param event_id 可以为空，活动ID
	 */
	public static function getShopJoinEvent($shop_id, $time, $event_id='') {

		return QdonlineManager::getShopJoinEvent($shop_id, $time, $event_id);
	}

	/**
	 * 获取店铺下的商品的推ID
	 * @param  $shop_id
	 * @param  $event_id
	 * @param  $start_time
	 * @param  $end_time
	 */
	public function getScheduleShopTids($shop_id, $event_id, $start_time, $end_time) {

// 		$params = array(
// 				'shop'		=>	$shop_id,
// 				'event'		=>	$event_id,
// 				'realStatus'=>	QcheckManager::STATUS_SCHEDULE_PASS,
// 				'from'	=>	$start_time,
// 				'to'	=>	$end_time,
// 				'catagory'	=> 0,
// 				'level'	=> '#'
// 		);

		$start_time = strtotime($start_time) - 600;
		$end_time = strtotime($end_time) + 600;

		$params = array_merge($params, QshopManager::defaultParams());


		$sql = "select distinct a.id from shop_groupon_info a  where
				a.id in (select groupon_id from tuan_events_item_detail where event_id=$event_id) and
				a.audit_status = ".QcheckManager::STATUS_SCHEDULE_PASS ." and a.start_time >= '$start_time'
				and a.end_time <= '$end_time' and a.goods_type = 2 and a.shop_id = '$shop_id'";


		$db  = Yii::app()->sdb_brd_shop;
		$infos = $db->createCommand($sql)->queryColumn();

// 		$search =  new SearchManager();
// 		$infos = $search->getTwitterList($params, 0, 1, 1);
		return $infos;
	}

}