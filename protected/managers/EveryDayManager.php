<?php

class EveryDayManager extends Manager
{

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
}
?>