<?php
class TwitterManager extends Manager {
    /**
     * 获取当前在线商品列表
     * type 0 普通
     *      2 活动
     */
    public static $goodType = ['0' => '普通', '2' => '活动'];

    public function getTwitterList($type = 0) {

        $sql = "select * from shop_groupon_info
            where audit_status = 50 and end_time > unix_timestamp(now())
            and start_time <= unix_timestamp(now()) and goods_type = $type ";

        $db = yii::app()->sdb_brd_shop;
        $results = $db->createCommand($sql)->queryAll();
        return $results;
    }

    /**
     * 获取某天在线商品列表
     * date 2015-4-13
     * type 0 普通
     *      2 活动
     */
    public function getTwitterListWithDate($date, $type = 0) {

        $time = strtotime($date . ' 12:00:00');
        $sql = "select * from shop_groupon_info
            where audit_status in (50, 51) and end_time > $time
            and start_time <= $time and goods_type = $type ";

        $db = yii::app()->sdb_brd_shop;
        $results = $db->createCommand($sql)->queryAll();
        return $results;
    }
}