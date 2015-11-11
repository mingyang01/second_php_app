<?php
/**
* 主题活动自动按销量排序脚本for2052 
* @author mingyang@meilishuo.com
* @version 2015-8-18
*/

class EventAutoSortManager extends Manager {
    //按销量排序
    public function AutoSort($event_id){
        $db = Yii::app()->sdb_brd_shop;
        $threeDays = 259200;
        $ctime = time();
        $startBefor = $ctime-$threeDays;
        $endAfter = $ctime+$threeDays;
        $area = $this->getEventArea($event_id);
        $area = (int)$area;
        $condition =" and shop_groupon_info.start_time > $startBefor and shop_groupon_info.start_time < $ctime and $endAfter > shop_groupon_info.end_time and shop_groupon_info.end_time > $ctime ";
        for ($i=1; $i <=$area; $i++) { 
            $sql = "select tuan_events_item_detail.twitter_id, shop_groupon_info.create_time,brd_shop_info.shop_nick nick, tuan_events_item_detail.rank," 
                    ."shop_groupon_info.id tuanid, shop_groupon_info.start_time time, shop_groupon_info.end_time," 
                    . " tuan_events_item_detail.shop_id, shop_groupon_info.off_price, round(shop_groupon_info.off_price*100/shop_groupon_info.off_num,2) price, goods_image, goods_name, shop_groupon_info.goods_id " 
                    . " from tuan_events_item_detail"
                    . " join shop_groupon_info on tuan_events_item_detail.groupon_id=shop_groupon_info.id join brd_shop_info on brd_shop_info.shop_id=shop_groupon_info.shop_id"
                    . " where shop_groupon_info.audit_status=50 and tuan_events_item_detail.event_id={$event_id} and tuan_events_item_detail.area={$i}" 
                    . " and shop_groupon_info.goods_type=2 ".$condition
                    . " order by tuan_events_item_detail.rank desc ";
            $results = $db->createCommand($sql)->queryAll();
            if(!$results) continue;
            $tids = array();
            foreach ($results as $key => $value) {
                $tids [] = $value['twitter_id'];
            }
            $waitSort = $this->getRealTimeSales($tids);
            $count = count($waitSort);
            $db  = Yii::app()->db_brd_shop;
            foreach ($waitSort as $key => $value) {
                $rank = $this->timeToRank($count);
                $sql = "update tuan_events_item_detail 
                        set rank={$rank}
                        where area={$i} and event_id={$event_id} and twitter_id={$value}";
                $db->createCommand($sql)->execute();
                $count--;
            }
        }
    }
    //获取一系列品的销量
    public function getSales($tids){
        $condition =" order by sale_num desc";
        $db  = Yii::app()->sdb_brd_goods;
        $sql = "select twitter_id,sale_num from brd_goods_info where twitter_id in (".$tids.") ".$condition;
        $results = $db->createCommand($sql)->queryAll();
        $variable = array();
        $tidSort = array();
        foreach ($results as $key => $value) {
            $variable[$value['twitter_id']] = $value['sale_num'];
        }
        foreach ($results as $key => $value) {
            $tidSort [] = $value['twitter_id'];
        }
        if($condition){
            return $tidSort;
        }else{
            return $variable;
        }
    }
    //时间转化为rank  (year+month+day)*1000 作为rank基数
    public function timeToRank($rank){
        $date = strtotime(date('Y-m-d 00:00:00'));
        $year = (int)date('Y',$date);
        $month = (int)date('m',$date);
        $day = (int)date('d',$date);
        $baseNum = ($year+$month*100+$day)*1000;
        if($rank>$baseNum||rank>1000 ){
            $rank = $rank - $baseNum;
        }
        return $baseNum + $rank;
    }
    //获得活动区域
    public function getEventArea($event_id){
        $db  = Yii::app()->sdb_brd_shop;
        $sql = "select area from tuan_events_item_detail where event_id=$event_id order by area desc limit 1";
        $results = $db->createCommand($sql)->queryRow();
        if(isset($results['area'])){
            return $results['area'];
        }else{
            return 0;
        }
    }
    //获取一系列品的当天销量
    public function getRealTimeSales($tids){
        $db  = Yii::app()->sdb_batman;
        $ctime = strtotime(date('Y-m-d 00:00:00'));
        $ntime = time();
        $sql = "select twitter_id ,sum(amount) as sales from t_bat_goods_map tm " 
                ."join t_bat_order tbo on tm.order_id=tbo.order_id " 
                ."where tm.twitter_id in (".implode(',',$tids).") and tbo.pay_time < $ntime and tbo.pay_time > $ctime group by tm.twitter_id order by sales desc";
        $results = $db->createCommand($sql)->queryAll();
        $tidSort = array();
        foreach ($results as $key => $value) {
            $tidSort [] = $value['twitter_id'];
        }
        return $tidSort;

    }
}
