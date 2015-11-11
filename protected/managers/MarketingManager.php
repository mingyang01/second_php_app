<?php

class MarketingManager extends Manager
{

    public static $tuangou_event_id = 1995;
    public static $marketing_key_map = array('marketing_tuangou_config'=>'团购配置', 'marketing_miaosha_config'=>'秒杀配置');

    /**
     * 获取清仓信息
     */
    public static function getQingcangList($date, $shop_id=0)
    {
        if (!$date) {
            $date = date("Y-m-d");
        }

        $sql = "select * from t_everyday_qingcang where date='{$date}'";
        if ($shop_id) {
            $sql .= " and shop_id='{$shop_id}'";
        }

        $sql .= " order by rank asc";

        $sdb_groupon = Yii::app()->sdb_groupon;

        $result = array();
        if ($shop_id) {
            $result = $sdb_groupon->createCommand($sql)->queryRow();
        } else {
            $result = $sdb_groupon->createCommand($sql)->queryAll();
        }
        return $result;
    }

    /**
     * 获取今天到大于今天10天的日期
     */
    public static function getDaysList()
    {
        $todayTime = strtotime(date("Y-m-d"));

        $dateList = array();
        for ($i=0; $i<10; $i++ ) {
            $dateList[] = date("Y-m-d",strtotime("+{$i} days"));
        }
        return $dateList;
    }

    /**
     * 获取清仓全部信息，不够4个补到4位
     */
    public static function getQingcangAllList($date)
    {
        $result = array();
        if (!$date) {
            return array_pad($result, 4, array());
        }
        $sql = "select * from t_everyday_qingcang where date='{$date}' order by rank desc";

        $sdb_groupon = Yii::app()->sdb_groupon;

        $result = $sdb_groupon->createCommand($sql)->queryAll();

        return array_pad($result, 4, array());
    }


    public function getTuangouList($date)
    {
        $result = array();
        if (!$date) {
            return array();
        }

        $startTime = strtotime($date);
        $endTime   = strtotime("+1 days",$startTime);

        $sql = "select distinct t1.id from shop_groupon_info as t1 left join tuan_events_item_detail as t2 on t1.id=t2.groupon_id where t2.event_id=".self::$tuangou_event_id." and t1.audit_status=50 and goods_type=2 and t1.start_time={$startTime} and t1.end_time={$endTime} order by t2.rank desc";
        debug($sql);
        $sdb_brd_shop = Yii::app()->sdb_brd_shop;
        $gids = $sdb_brd_shop->createCommand($sql)->queryColumn();

        if ($gids) {
            $result = $this->audit->getTwitterDetail($gids);
        }

        return $result;
    }
}
?>