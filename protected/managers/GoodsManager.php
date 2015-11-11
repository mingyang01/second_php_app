<?php

class GoodsManager extends Manager
{
    /**
     * 获取活动报名数据
     * @param int $eventId
     * @return array
     */
    public function getGrouponInfo($grupon_id)
    {
        $grupon_id = (int)$grupon_id;
        if (!$grupon_id) return array();

        $sdb_brd_shop  = Yii::app()->sdb_brd_shop;
        $sql          = "select * from shop_groupon_info where id=$grupon_id order by id desc limit 1";
        $grouponInfo  = $sdb_brd_shop->createCommand($sql)->queryRow();

        if ($grouponInfo) {
            return $grouponInfo;
        } else {
            return array();
        }
    }

    /**
     * 获取团购twitter_id的详细信息
     * @param int $twitterId
     * @return array
     */
    public function getTuanTwitterInfo($twitterId)
    {
        $twitterId = (int)$twitterId;
        if (!$twitterId) return array();

        $sdb_brd_shop  = Yii::app()->sdb_brd_shop;
        $sql           = "select * from shop_groupon_goods_relation where twitter_id={$twitterId} order by id desc limit 1";
        $twitterInfo   = $sdb_brd_shop->createCommand($sql)->queryRow();

        if ($twitterInfo) {
            return $twitterInfo;
        } else {
            return array();
        }
    }

    /**
     * 获取brd_goods的t信息
     * @param number $twitter_id
     * @return array
     */
    public function getTwitterInfo($twitter_id)
    {
        $twitter_id = (int)$twitter_id;
        if (!$twitter_id) return array();

        $sdb_brd_goods = Yii::app()->sdb_brd_goods;
        $sql = "select * from brd_goods_info where twitter_id={$twitter_id}";
        $twitter_info   = $sdb_brd_goods->createCommand($sql)->queryRow();

        if ($twitter_info) {
            return $twitter_info;
        } else {
            return array();
        }
    }

    /**
     * 批量根据twitter ID 获取商品信息
     */
    public function getGoodsInfoBytids($tids) {

    	!is_array($tids) && $tids = array($tids);
    	$sdb_brd_goods = Yii::app()->sdb_brd_goods;
    	if (count($tids) > 20) {
    		$chunk = array_chunk($tids, 20);
    		$twitter_info = array();
    		foreach ($chunk as $val) {

    			$sql = "select * from brd_goods_info where twitter_id in ('". implode("','", $val) ."')";
    			$tmp   = $sdb_brd_goods->createCommand($sql)->queryAll();
    			$twitter_info = array_merge($twitter_info, $tmp);
    		}

    	} else {

    		$sql = "select * from brd_goods_info where twitter_id in ('". implode("','", $tids) ."')";
    		$twitter_info   = $sdb_brd_goods->createCommand($sql)->queryAll();
    	}

    	return $twitter_info;
    }

    public function getGoodsMoreInfoByTids($tids) {

    	!is_array($tids) && $tids = array($tids);
    	$sdb_brd_goods = Yii::app()->sdb_brd_shop;
    	if (count($tids) > 20) {
    		$chunk = array_chunk($tids, 20);
    		$twitter_info = array();
    		foreach ($chunk as $val) {

    			$sql = "select * from shop_groupon_goods_relation where twitter_id in ('". implode("','", $val) ."')";
    			$tmp   = $sdb_brd_goods->createCommand($sql)->queryAll();
    			$twitter_info = array_merge($twitter_info, $tmp);
    		}

    	} else {

    		$sql = "select * from shop_groupon_goods_relation where twitter_id in ('". implode("','", $tids) ."')";
    		$twitter_info   = $sdb_brd_goods->createCommand($sql)->queryAll();
    	}

    	return $twitter_info;
    }
}
?>