<?php
/**
* 流行推荐 
* @author mingyang@meilishuo.com
* @version 2015-7-30
*/

class PopularManager extends Manager {

    //流行推荐 活动给的id
    public static $popularEventId = 2409;

    public function getPopularList($date)
    {
        $result = array();

        if (!$date) {
            return array();
        }
 
        $startTime = strtotime($date);
        $endTime   = strtotime("+1 days",$startTime);

        $sql = "select distinct t1.id from shop_groupon_info as t1 left join tuan_events_item_detail as t2 on t1.id=t2.groupon_id where t2.event_id=".self::$popularEventId." and t1.audit_status=50 and goods_type=2 and t1.start_time={$startTime} and t1.end_time={$endTime} order by t2.rank desc";
        $sdb_brd_shop = Yii::app()->sdb_brd_shop;
        $gids = $sdb_brd_shop->createCommand($sql)->queryColumn();
        if ($gids) {
            $result = $this->audit->getTwitterDetail($gids);
        }

        return $result;
    }
}