<?php

class ShopManager extends Manager {

	public static $shopAreaArr = array('1' => '华东', '2' => '华北', '3' => '华南', '0'=>'全国');
	public static $shopLevel = array("1"=>'KA', "10"=>'CS', "0"=>'普通', "-10"=>'高危');

    /**
     * 获取活动报名数据
     * @param int $eventId
     * @return array
     */
    public function getShopInfo($shopId)
    {
        if (!shopId) return array();

        $sdb_brd_shop  = Yii::app()->sdb_focus;
        $sql          = "select * from t_focus_shop_info where shop_id=$shopId order by shop_id desc limit 1";
        $shopInfo  = $sdb_brd_shop->createCommand($sql)->queryRow();

        if ($shopInfo) {
            return $shopInfo;
        } else {
            return array();
        }
    }

    /**
     * 获取有维护人、经验类目、地域的商铺信息
     */
    public function getShopDetail($shopId) {

		$info = self::getShopInfo($shopId);
		if ($info['mayjor']) {
			$info['category'] = self::getShopCategory($info['mayjor']);
		} else {
			$info['category'] = '';
		}

		$admin = self::getShopadmin($shopId);
		$info['admin'] = $admin['realname'];
		$info['ear'] = self::$shopAreaArr[$info['partner_area_id']];
		return $info;
    }

    /**
     * 获取店铺的分类
     */
    public function getShopCategory($major_id) {

    	$sdb_brd_shop  = Yii::app()->sdb_focus;
    	$sql          = "select * from t_focus_shop_major_info where major_id=$major_id limit 1";
    	$ret = $sdb_brd_shop->createCommand($sql)->queryRow();
    	return $ret['name'];
    }

    /**
     * 获取店铺维护人
     */
    public function getShopadmin($shopId) {
    	$sdb_brd_shop  = Yii::app()->sdb_focus;
    	$sql          = "select * from t_focus_opadmin_admin where shop_id=$shopId order by shop_id desc limit 1";
    	return $sdb_brd_shop->createCommand($sql)->queryRow();
    }

    /**
     * 获取店铺的健康度
     * @param  $shop_id 店铺ID
     */
    public function getShopHealthStat($shop_id) {

    	if (!$shop_id) {
    		return array();
    	}
    	!is_array($shop_id) && $shop_id = array($shop_id);
    	$time = date('Y-m-d', strtotime('-3 day'));

    	$sdb_brd_shop  = Yii::app()->sdb_focus;
    	foreach ($shop_id as $val) {

    	}
    	$sql  = "select * from t_focus_shop_daily_health_stat where shop_id in ('". implode("','",  $shop_id) ."') and dt='$time'";
    	$result = $sdb_brd_shop->createCommand($sql)->queryAll();
    	if (!$result) {
    		$time = date('Y-m-d', strtotime('-5 day'));
    		$sql  = "select * from t_focus_shop_daily_health_stat where shop_id in ('". implode("','",  $shop_id) ."') and dt='$time'";
    		$result = $sdb_brd_shop->createCommand($sql)->queryAll();
    	}
    	return ArrFomate::hashmap($result, 'shop_id');
    }

    /**
     * 获取店铺的统计数据
     * @param  $shop_id 店铺ID
     */
    public function getShopCoralDaily($shop_id, $time='') {

    	if (!$shop_id) {
    		return array();
    	}
    	!is_array($shop_id) && $shop_id = array($shop_id);

    	if ($time) {
    		$where = " and dt>='$time'";
    	}
    	$sdb_brd_shop  = Yii::app()->sdb_focus;
    	foreach ($shop_id as $val) {
    		$sql  = "select shop_id, sum(gmv) gmv, sum(paid_goods_num) sale, avg(shop_buy_rate) shop_buy_rate from t_focus_shop_coral_daily where shop_id='$val'";
    		$where && $sql .= $where;
    		$result[$val] = $sdb_brd_shop->createCommand($sql)->queryRow();
    	}
    	return $result;
    }

    /**
     * 获取店铺的状态
     * @param  $shop_id 店铺ID
     */
    public function getShopStatus($shop_id) {

    	if (!$shop_id) {
    		return array();
    	}
    	!is_array($shop_id) && $shop_id = array($shop_id);

    	$sdb_brd_shop  = Yii::app()->sdb_focus;
    	$sql  = "select * from t_focus_shop_type where shop_id in ('". implode("','",  $shop_id) ."')";
    	$result = $sdb_brd_shop->createCommand($sql)->queryAll();
    	return ArrFomate::hashmap($result, 'shop_id');
    }

    /**
     * 获取黄金橱窗
     * @param  $shop_id
     */
    public function getShopWindow($shop_id) {

    	if (!$shop_id) {
    		return array();
    	}
    	!is_array($shop_id) && $shop_id = array($shop_id);

    	$sdb_brd_shop  = Yii::app()->sdb_brd_shop;
    	$sql  = "select shop_id,count(*) glod_window from brd_shop_goods_window where shop_id in ('". implode("','",  $shop_id) ."') and isgold=1 group by shop_id ";
    	$result = $sdb_brd_shop->createCommand($sql)->queryAll();
    	return ArrFomate::hashmap($result, 'shop_id');
    }

    /**
     * 获取店铺的销售情况等详细信息
     * @param  $shop_ids 数组，店铺ID
     */
    public function getShopDetailInfo($shop_ids) {

    	if (!$shop_ids) {
    		return false;
    	}
    	!is_array($shop_ids) && $shop_ids = array($shop_ids);

    	$db = Yii::app()->sdb_brd_shop;
    	$sql = "select * from brd_shop_groupon_shops_relation where shop_id in ('". implode("','", $shop_ids) ."')";
    	$result = $db->createCommand($sql)->queryAll();
    	return ArrFomate::hashmap($result, 'shop_id');
    }
}
?>