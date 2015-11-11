<?php
/**
 * 清仓线上店铺列表
 * @author linglingqi@
 * @version 2015-08-10
 */
class QdonlineManager extends Manager {

	private static $file=array(
     		'event_id'	=>	 1,
    		'shop_id'	=>	 1,
			'banner'=>	  0,
       		'title'		=>	  0,
 			'mark'=>	  0,
   			'sort_last'	=>		0,
			'sort_create'=>		0,
			'sort_prevue'	=>	0,
			'start_time'=>	  0,
			'end_time'	=>		0,
			'create_time'=>		0,
			'updator'	=>		0,
			'ext' =>0,
			'twitters'	=>0,
	);

	/**
	 * 添加店铺信息
	 * @param  $shop_id
	 * @param  $event_id
	 * @param  $start_time
	 * @param  $end_time
	 */
	public static function addInfo($event_id, $shop_id, $start_time, $end_time, $banner=array(), $sort_last=999, $sort_create=1, $sort_prevue=1, $title="超低清仓") {

		$db_groupon = Yii::app()->db_groupon;
		$data = array(
				'event_id'	=>	 $event_id,
				'shop_id'	=>	 $shop_id,
				'banner'=>	  $banner ? $banner : array('title'=>'','icon'=>''),
				'title'		=>	  $title,
				'mark'=>	  QcommManager::QINGCANG_ICON,
				'sort_last'	=>		$sort_last ? $sort_last : 1,
				'sort_create'=>		$sort_create ? $sort_create : 1,
				'sort_prevue'	=>	$sort_prevue ? $sort_prevue : 1,
				'start_time'=>	  $start_time,
				'end_time'	=>		$end_time,
				'create_time'=>	date('y-m-d H:i:s'),
				'ext'=>'{"test":""}',
				'updator'	=>	yii::app()->user->name,
		);

		$sql_group = "insert into `t_groupon_online_shop`" . QdbcommManager::formatedit($data, self::$file, 'insert');
		return $db_groupon->createCommand($sql_group)->execute();
	}

	/**
	 * 修改店铺信息
	 */
	public static function updateInfo($id, $start_time, $end_time, $banner, $mark=QcommManager::QINGCANG_ICON, $title="超低清仓") {

		if (!$id) {
			return false;
		}

		//获取该店铺已有的排期信息；2、获取改排期下的商品信息；3、修改商品的时间；4修改店铺的时间

		$info = self::getShopInfoById($id);
		$good_list = QsheduleManager::getScheduleShopTids($info['shop_id'], $info['event_id'], $info['start_time'], $info['end_time']);

		$db_groupon = Yii::app()->db_groupon;
		$db_shop = Yii::app()->db_brd_shop;
		$gconn = $db_groupon->beginTransaction();
		$sconn = $db_shop->beginTransaction();
		try {
			$i = 0;
			foreach ($good_list as $gid) {

				$updateArr['start_time']   = strtotime($start_time);
				$updateArr['end_time']     = strtotime($end_time);

				$ret = $db_shop->createCommand()->update(
						'shop_groupon_info',
						$updateArr,
						'id=:id',
						array(':id'=>$gid)
				);
				if ($ret) {
					$i ++;
				}
			}
			$data = array(
					'banner'=>	  $banner,
					'title'		=>	  $title,
					'mark'=>	  $mark,
					'start_time'=>	  $start_time,
					'end_time'	=>		$end_time,
					'create_time'=>	date('y-m-d H:i:s'),
					'updator'	=>	yii::app()->user->name,
			);

			$where = " where id=$id";
			//需要添加同步groupon_info表
			$sql_group = "update `t_groupon_online_shop`" . QdbcommManager::formatedit($data, self::$file, 'update') . $where;
			$gret = $db_groupon->createCommand($sql_group)->execute();

			$gconn->commit();
			$sconn->commit();
		} catch (Exception $e) {
			$gconn->rollBack();
			$sconn->rollBack();
			throw new Exception('编辑失败，请记录并联系开发解决');
		}
		return true;
	}

	public static function updateByWhere($update, $where) {

		if (!$update || !$where) {
			return false;
		}
		$db_groupon = Yii::app()->db_groupon;
		$sql_group = "update `t_groupon_online_shop` set " . $update . $where;
		return $db_groupon->createCommand($sql_group)->execute();
	}

	//删除
	public static function delInfo($id) {

		$db_groupon = Yii::app()->db_groupon;
		$sql_group = "delete from `t_groupon_online_shop` where id=$id";
		return $db_groupon->createCommand($sql_group)->execute();
	}

	/**
	 * 根据条件获取店铺列表
	 * @param  $event_id	活动ID
	 * @param  $shop		店铺ID，可以为空
	 * @param  $type		类型，默认为全部
	 */
	public static function getShopList($event_id, $shop='', $type=QensureScheduleManager::ALL_SORT_TYPE) {

		$db = yii::app()->sdb_groupon;
		$sql = "select * from `t_groupon_online_shop` where event_id=$event_id";

		$where = '';
		if ($shop) {
			$where .= " and shop_id='$shop'";
		}

		$order = self::getOrderType($type);
		$sql .= $where . $order. " limit 1000";

		return $db->createCommand($sql)->queryAll();
	}

	/**
	 * 获取最近参加活动的店铺下的商品
	 * @param $shop_id string 店铺ID
	 */
	public static function getLastTime($shop_id) {

		if (!$shop_id) {
			return false;
		}
		$db = Yii::app()->db_groupon;
		$sql = "select * from `t_groupon_online_shop` where shop_id='$shop_id' order by create_time desc limit 3";
		$result = $db->createCommand($sql)->queryAll();
		return $result;
	}


	/**
	 * 获取最近一段时间店铺参加清仓活动的次数
	 * @param shop_id 店铺ID
	 * @param time 希望获取店铺参加活动的时间条件
	 * @param event_id 可以为空，活动ID
	 */
	public static function getShopJoinEvent($shop_id, $time, $event_id='') {

		if (!$shop_id) {
			return false;
		}
		$where = '';
		$db = Yii::app()->db_groupon;
		if ($event_id) {
			$where = " and event_id='$event_id' ";
		}
		$order = " order by create_time desc";

		$sql = "select * from `t_groupon_online_shop` where shop_id='$shop_id' and create_time>='$time'";
		$where && $sql .= $where;
		$order && $sql .= $order;
		$result = $db->createCommand($sql)->queryAll();
		return $result;
	}

	/**
	 * 获取店铺详细信息
	 */
	public static function getShopInfoById($id) {

		$db = Yii::app()->sdb_groupon;
		$sql = "select * from `t_groupon_online_shop` where id='$id'";
		return $db->createCommand($sql)->queryRow();
	}

	/**
	 * 店铺排序类型
	 */
	public static function getOrderType($type) {

		$now = date('Y-m-d H:i:s');
		$order = '';
		if ($type ==QensureScheduleManager::LAST_SORT_TYPE) {
			//最后疯抢排序
			$order = " and `start_time`<'$now' and `end_time`>'$now' order by sort_last asc,end_time asc";
		} elseif ($type ==QensureScheduleManager::PRE_SORT_TYPE) {
			//明日预告
			$order = " and `start_time`>'$now' order by sort_prevue asc,start_time asc, end_time asc";
		} elseif($type ==QensureScheduleManager::NEW_SORT_TYPE) {
			//今日上新
			$order = "  and `start_time`<'$now' and `end_time`>'$now' order by sort_create asc, start_time desc";
		} elseif($type == QensureScheduleManager::POPULAR_SORT_TYPE) {
			$order = "  and `start_time`<'$now' and `end_time`>'$now' order by create_time desc";
		} else {
			$order = ' order by start_time desc, end_time desc';
		}
		return $order;
	}

	public static function getInfoByshopidBat($shop, $event) {

		!is_array($shop) && $shop = array($shop);
		$db = Yii::app()->sdb_groupon;
		$sql = "select * from `t_groupon_online_shop` where event_id=$event and shop_id in ('". implode("','", $shop) ."')";
		return $db->createCommand($sql)->queryAll();
	}

}
?>