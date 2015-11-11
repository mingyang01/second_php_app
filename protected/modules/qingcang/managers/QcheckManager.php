<?php
/**
 * 审核管理
 * @author linglingqi@
 * @version 2015-07-30
 */
class QcheckManager extends Manager {

	const STATUS_APPLY = 10;	//已报名
	CONST STATUS_FIRST_REFUS = 21;	//店铺审核没有通过
	CONST STATUS_FIRST_PASS = 20;	//店铺审核通过
	CONST STATUS_SECOND_REFUS = 41;	//复审没有通过
	CONST STATUS_SECOND_PASS = 40;		//复审通过
	CONST STATUS_SCHEDULE_PASS = 50;	//排期成功
	CONST STATUS_SCHEDULE_REFUS = 51;   //排期失败

	public static $status = array(
			self::STATUS_APPLY	=>	1,
			self::STATUS_FIRST_PASS	=>	1,
			self::STATUS_FIRST_REFUS	=>	1,
			self::STATUS_SECOND_PASS	=>	1,
			self::STATUS_SECOND_REFUS	=>1,
			self::STATUS_SCHEDULE_PASS	=>	1,
			self::STATUS_SCHEDULE_REFUS	=>	1,
	);


	public static $tipsTypeEnum = array(
			'10' => '等待初审',
			'20' => '初审成功',
			'21' => '初审失败',
			'40' => '复审成功',
			'41' => '复审失败',
			'50' => '排期成功',
			'51' => '排期失败'
	);

	/**
	 * 获取店铺信息及店铺下面的商品信息
	 * @param  $list 店铺ID数组
	 * @param  $params 获取店铺下面的商品信息的参数
	 * @param  $has_goods 是否要获取商品信息
	 */
	public static function getShopGoods($list, $params, $has_goods=false) {

		if(!$list || !$params || !isset($params['event'])) {
			return array();
		}
		$search = new SearchManager();
		$infos = QshopManager::getQingcangShopInfos($list, $params['event'], $params['realStatus']);
		$tmp_data = array();
		if ($has_goods) {

			//得到店铺下面的商品信息
			//不对店铺下的商品的报名时间做判断
			unset($params['from']);
			unset($params['to']);
			$audit = new AuditManager();
			foreach ($infos as $key=>$val) {

				$params['shop']	= $val['shop_id'];
				$gids = $search->getTwitterList($params);
				if (!$gids) {
					unset($infos[$key]);
					continue;
				}
				$tmp['total'] = count($gids);

				$ret = array();
				if ($gids) {

					$data = $audit->getTwitterDetail($gids);
					$data = ArrFomate::hashmap($data, 'tid');
					$util = new UtilManager();
					$goods = $util->getGoodsInfo(array_keys($data));
					foreach ($goods['list'] as $gkey=>$gv) {
						$ret[$gkey] = array_merge($gv, $data[$gv['twitter_id']]);
					}
					$ret = ArrFomate::hashmap($ret, 'twitter_id');
				}

				//获取清仓频道下的历史纪录
				$good_history = $search->getGoodHistoty(array_keys($ret), 80);
				$good_history = ArrFomate::hashmap($good_history, 'twitter_id');

				foreach($ret as &$rval) {
					if (isset( $good_history[$rval['twitter_id']])) {
						$rval['good_history']['event_id'] = $good_history[$rval['twitter_id']]['event_id'];
						$rval['good_history']['start_time'] =  date('Y-m-d H:i:s', $good_history[$rval['twitter_id']]['start_time']);
						$rval['good_history']['end_time'] = date('Y-m-d H:i:s', $good_history[$rval['twitter_id']]['end_time']);
					}else {
						$rval['good_history'] = array();
					}
				}
				$tmp['data'] = $ret;
				$tmp_data[$key] = $val;
				$tmp_data[$key]['hight'] = ceil($tmp['total']/2) * 350 + 230;
				$tmp_data[$key]['good_info'] = $tmp;
			}
			$infos = $tmp_data;
		}
		return $infos;
	}

	/**
	 * 修改商品的状态
	 * @param  $gids	商品id
	 * @param  $status	修改的商品状态
	 * @param  $event_id	活动ID
	 */
	public static function checkShopGoods($gids, $checkResult, $event_id, $comment) {

		if (!$gids) {
			return false;
		}

		$user = yii::app()->user;
		$uid = $user->id;
		$name = $user->name;

		$values = '';
		$time = date('Y-m-d H:i:s');
		foreach ($gids as $key => $gid) {
			if ($values) {
				$values .= ", ($gid, $checkResult, '$comment', '$time', $uid, '$name') ";
			} else {
				$values .= " ($gid, $checkResult, '$comment', '$time', $uid, '$name') ";
			}
		}
		$connection = Yii::app()->db_brd_shop;
		$sql1 = "update shop_groupon_info set audit_status={$checkResult}, op_time='$time' where id in (" . implode(', ', $gids) . ")";
		$sql2 = "INSERT INTO shop_groupon_audit_comments (`gid`, `audit_status`, `audit_comments`, `audit_time`, `audit_user`, `audit_opname`) VALUES " . $values;

		$transaction=$connection->beginTransaction();
		try {

			$connection->createCommand($sql1)->execute();
			$connection->createCommand($sql2)->execute();

			$transaction->commit();
			return true;
		} catch(Exception $e) {// 如果有一条查询失败，则会抛出异常

			$transaction->rollBack();
			throw new Exception($e);
		}
		return true;
	}

}
?>