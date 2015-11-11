<?php

class SupriseManager extends Manager {

    /**
     * 整点抢商品标签
     */
    public static $zdqTypeInfo = array(
        '11' => '普通',
        '1' => '秒杀',
        '10' => '精品',
    );

    public static $miaosh_tag_map = array(
        '0' => '不打标',
        '1' => '疯抢',
        '2' => '精品',
        '3' => '上新',
    );

    public function getArea($date) {
        $sql      = "select detail from tuan_events_list where event_id = 1065";
        $db       = Yii::app()->sdb_brd_shop;
        $resultes = $db->createCommand($sql)->queryColumn();

        $detail = json_decode(($resultes[0]), true);
        $detail = $detail['zhengdian_info'];

        //$date = "2015-05-17";
        $areas = array();
        foreach ($detail as $key => &$value) {
            if (strtotime($value['stime']) >= strtotime($date . '00:00:00')
                && strtotime($value['stime']) < strtotime($date . '24:00:00')) {
                $area          = explode(' ', $value['stime']);
                $value['area'] = $area[1];
                $areas[]       = $value;
            }
        }
        debug($areas);
        $order = $this->common->array_column($areas, 'area');
        array_multisort($order, SORT_ASC, $areas);
        return $areas;
    }

    public function getPrepareList() {
        $sql = "select distinct id from shop_groupon_info master
            where  audit_status = 40 and master.id in
            (select groupon_id from tuan_events_item_detail
                where event_id = '1065') and goods_type = 2";

        debug($sql);
        $db  = Yii::app()->sdb_brd_shop;
        $resultes = $db->createCommand($sql)->queryColumn();
        return $resultes;
    }

    public function getPreviewList($start, $category = 0) {
        // $start = '2015-05-16 22:00';

        $sql = "select tuan_events_item_detail.rank,tuan_events_item_detail.category, shop_groupon_info.id id, shop_groupon_info.twitter_id tid,
        plus_detail from tuan_events_item_detail  join shop_groupon_info on
        tuan_events_item_detail.groupon_id=shop_groupon_info.id ";

        $filter = "join shop_groupon_goods_relation
            on shop_groupon_info.twitter_id = shop_groupon_goods_relation.twitter_id and first_sort_id = '$category'";

        $where = "where shop_groupon_info.audit_status=50 and tuan_events_item_detail.category in (1, 10, 11 ,12 ,13)
        and tuan_events_item_detail.event_id=1065 and shop_groupon_info.goods_type=2
        and shop_groupon_info.start_time = unix_timestamp('$start')
                order by tuan_events_item_detail.rank desc;
        ";

        if ($category) {
            $sql = $sql . $filter . $where;
        } else {
            $sql .= $where;
        }
        debug($sql);
        $db  = Yii::app()->sdb_brd_shop;
        $resultes = $db->createCommand($sql)->queryAll();
        return $resultes;
    }


    // 产品营销-秒杀
    // category 12 疯抢 13 精选
    public function getMarketingList($start, $category = 0) {
        // $start = '2015-05-16 22:00';

        $sql = "select tuan_events_item_detail.rank, tuan_events_item_detail.category category, shop_groupon_info.id id, shop_groupon_info.twitter_id tid,
        plus_detail from tuan_events_item_detail  join shop_groupon_info on
        tuan_events_item_detail.groupon_id=shop_groupon_info.id ";

        $filter = "join shop_groupon_goods_relation
            on shop_groupon_info.twitter_id = shop_groupon_goods_relation.twitter_id and first_sort_id = '$category'";

        $where = "where shop_groupon_info.audit_status=50 and tuan_events_item_detail.category in (12, 13)
        and tuan_events_item_detail.event_id=1065 and shop_groupon_info.goods_type=2
        and shop_groupon_info.start_time = unix_timestamp('$start')
                order by tuan_events_item_detail.rank desc;
        ";

        if ($category) {
            $sql = $sql . $filter . $where;
        } else {
            $sql .= $where;
        }
        debug($sql);
        $db  = Yii::app()->sdb_brd_shop;
        $resultes = $db->createCommand($sql)->queryAll();
        return $resultes;
    }

    public function analyize($date) {

        $start = "$date 00:00:00";
        $end = date('Y-m-d 00:00:00', strtotime($date) + 24 * 3600);
        // 实际上线
        $sql = "select shop_groupon_info.id id, shop_groupon_info.twitter_id tid,
            from_unixtime(shop_groupon_info.start_time) start_time,
            plus_detail from tuan_events_item_detail  join shop_groupon_info on
            tuan_events_item_detail.groupon_id=shop_groupon_info.id
            where shop_groupon_info.audit_status=50 and tuan_events_item_detail.category in (1, 10, 11)
            and tuan_events_item_detail.event_id=1065 and shop_groupon_info.goods_type=2
            and shop_groupon_info.start_time > unix_timestamp('$start')
            and shop_groupon_info.start_time < unix_timestamp('$end')
            order by shop_groupon_info.start_time";

        $db  = Yii::app()->sdb_brd_shop;
        $onlines = $db->createCommand($sql)->queryAll();
        debug($sql);
        $onlineTwitter = $this->common->array_column($onlines, 'tid');
        // 互斥表
        $sql = "select * from brd_goods_campaign_price where `campaign_type` = 10
        and effective_time >= '$start'
        and effective_time < '$end'
        and `invalid_time` <= '$end'
        and invalid_time > now()
        and invalid_time != effective_time order by effective_time ;
";

        $db  = Yii::app()->sdb_brd_goods;
        $campaigns = $db->createCommand($sql)->queryAll();
        $campaignTwitter = $this->common->array_column($campaigns, 'twitter_id');


        $sql = "select * from (select event_id , count(1) number, groupon_id ,
            twitter_id , count(distinct event_id) event
            from tuan_events_item_detail  where  event_id = 1065
            group by groupon_id ) a where a.number > 1 and number != event";

        $db  = Yii::app()->sdb_brd_shop;
        $naocans = $db->createCommand($sql)->queryAll();
        $naocan = $this->common->array_column($naocans, 'twitter_id');

        return array($onlineTwitter, $campaignTwitter, $naocan);
    }
}