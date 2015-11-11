<?php
/**
 * 白名单商家管理
 * @author linglingqi@
 * @version 2015-07-07
 */
class WhiteShopManager extends Manager {

	/**
	 * 添加白名单用户
	 * @param $ids 店铺ID，数组
	 */
	public function add($id, $uname='') {

		$id = trim($id);
		if (!$id) {
			return false;
		}
		$db = Yii::app()->db_groupon;
		$sql = "insert into t_shop_groupon_whitelist(shop_id, operator) VALUES('$id','$uname')";
		return $db->createCommand($sql)->execute();
	}

	/**
	 * 根据ID获取商品信息
	 */
	public function getInfo($ids) {

		!is_array($ids) && $ids = array($ids);
		$db = Yii::app()->sdb_groupon;
		$sql = "select * from t_shop_groupon_whitelist where shop_id in ('" .implode("','", $ids) . "')";
		return $db->createCommand($sql)->queryRow();
	}

	/**
	 * 分页获取所有列表
	 */
	public function showAll($id='', $operate='', $offset=0, $count=20) {

		$sql ="select * from t_shop_groupon_whitelist where 1=1";

		if ($id) {
			$sql .= " and shop_id=$id";
		}
		if ($operate) {
			$sql .= " and operator='$operate'";
		}

		$db = Yii::app()->sdb_groupon;
		$sql .= " order by time desc limit $offset, $count";
		$ret =  $db->createCommand($sql)->queryAll();
		return ArrFomate::hashmap($ret, 'shop_id');
	}

	/**
	 * 获取记录条数
	 */
	public function getCount ($id='', $operate=''){

		$sql ="select count(*) from t_shop_groupon_whitelist where 1=1";

		if ($id) {
			$sql .= " and shop_id=$id";
		}
		if ($operate) {
			$sql .= " and operator='$operate'";
		}

		$db = Yii::app()->sdb_groupon;
		$result = $db->createCommand($sql)->queryColumn();
		return $result[0];
	}


	/**
	 * 删除记录
	 */
	public function del($id) {

		if(!$id) {
			return false;
		}
		$db = Yii::app()->db_groupon;
		$sql = "delete from t_shop_groupon_whitelist where shop_id='$id'";
		return $db->createCommand($sql)->execute();
	}

}