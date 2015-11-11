<?php
/**
 * 活动报名manager
 */

class TakepartManager extends Manager
{

    /**
     * 检测是否可以报名
     * @param int $twitterId
     * @param int $eventId  活动id
     * @return boolean  true 可以报名  false 不可以报名
     */
    public function canTakePart($twitterId, $eventId)
    {
        $goodsType = 0;
        if ($eventId >= 1) {
            $goodsType = 2;
        }
        //1.普通团购，如果待审，不能报名
        $sdb_brd_shop = Yii::app()->sdb_brd_sdb;
        if ($goodsType == 0) {
            $sql = "select id from shop_groupon_info where goods_type=0 and audit_status in (10,20,30,40) and twitter_id={$twitterId}";
            $grouponInfo = $sdb_brd_shop->createCommand($sql)->queryRow();
            if ($grouponInfo) {
                return false;
            }
        }
        if ($goodsType == 2) {
            $sql = "select * from tuan_events_list where event_id={$eventId}";
            $eventInfo = $sdb_brd_shop->createCommand($sql)->queryRow();
            if (!$eventInfo) {
                return false;
            }

            $eventType = $eventInfo['status'];
            if (in_array($eventType, array(10, 20))) { //列表页和主题活动
                // 判断是否已报名
                $sql = "select t1.id from shop_groupon_info t1 join tuan_events_item_detail t2 on t1.id=t2.groupon_id where t1.audit_status in (10,20,30,40,50) and t1.goods_type=2 and t2.event_id={$eventId} and t1.twitter_id={$twitterId}";
                $eventGoodsInfo = $sdb_brd_shop->createCommand($sql)->queryRow();
                if ($eventGoodsInfo) {
                    return false;
                }
            } else {
                $sql = "select t1.id from shop_groupon_info t1 join tuan_events_item_detail t2 on t1.id=t2.groupon_id where  t1.goods_type=2 and  ((t1.audit_status in (10,20,30,40)) or (t1.audit_status=50 and t1.end_time > unix_timestamp()) ) and t2.event_id={$eventId} and t1.twitter_id={$twitterId}";
                $eventGoodsInfo = $sdb_brd_shop->createCommand($sql)->queryRow();
                if ($eventGoodsInfo) {
                    return false;
                }
            }
        }
        return true;
    }
}
?>