<?php

class AuditManager extends Manager {
    private static $logsTable = 't_dolphin_tuan_checklog';
    /**
     * [getTwitterList 通用筛选过滤]
     * @param  [type] $assigns [description]
     * @return [type]          [description]
     *
     */
    // $assigns = array(
    //     'from'=> $from,
    //     'to'=> $to,
    //     'twitter'=> $twitter,
    //     'shop'=> $shop,
    //     'status'=> $status,

    //     'type'=> $type,
    //     'level'=> $level,
    //     'catagory'=> $catagory,
    //     'endCatagory'=> $endCatagory,
    //     'event'=> $event
    // );
    public function getTwitterList($params) {
        $where = "create_time >= '" . $params['from'] . " 00:00:00' "
            . " and create_time <= '" . $params['to'] . " 24:00:00' ";

        if ($params['twitter']) {
            $where .= " and twitter_id in (" . $params['twitter'] .") ";
        }

        if ($params['shop']) {
            $where .= " and shop_id = '" . $params['shop'] . "' ";
        }

        $where .= " and audit_status = " . $params['status'];
        $sql = 'select id from shop_groupon_info where ' . $where;

        $db  = Yii::app()->sdb_brd_shop;
        $resultes = $db->createCommand($sql)->queryColumn();

        return $resultes;
    }

    public function getTwitterDetail($gids = array()) {
        $sql = "select master.goods_name name, master.shop_id shop,
            master.create_time as createTime, master.id gid,
            master.twitter_id tid,  master.goods_image_pc img,
            round(master.off_num/100, 2) rate,
            master.off_price price, round(master.off_price *100/master.off_num, 2) origin,
            master.isshow_tag isshow,
            master.comments comments,
            master.audit_status audit_status,
            master.start_time start_time,
            master.end_time end_time,
            master.op_type op_type,
            twitter.sale_num sale_num,
            twitter.goods_first_catalog goods_first_catalog,
            twitter.goods_second_catalog goods_second_catalog,
            twitter.goods_three_catalog goods_three_catalog,
            twitter.first_sort_id first_sort_id,
            twitter.repertory repertory,
            twitter.cvr cvr,
            twitter.sellrate_score sellrate_score,
            twitter.popularity_score popularity_score,
            twitter.tuan_history tuan_history,
            twitter.is_danger is_danger,
            twitter.is_bad is_bad,
            shop.ka_level level,
            shop.shop_nick shop_nick,
            shop.partner_tel partner_tel,
            shop.partner_qq partner_qq,
            shop.is_danger shop_danger,
            shop.is_bad shop_bad,
			twitter.gmv gmv
            from shop_groupon_info master
            left join shop_groupon_goods_relation twitter
                on master.twitter_id = twitter.twitter_id
            left join brd_shop_groupon_shops_relation shop
                on master.shop_id = shop.shop_id
            where master.id in (" . implode(',', $gids)
            . ")  order by field(master.id, " . implode(',', $gids)  . ")";

        //debug($sql);

        $db  = Yii::app()->sdb_brd_shop;
        $resultes = $db->createCommand($sql)->queryAll();

        return $resultes;
    }

    //  获取末级类目
    public function endCatagory($cid) {
        $sql = "SELECT
            t.name text,
            t.cid id

            FROM
            `category` t
            WHERE NOT EXISTS(
            select * from `category` t1,`category` t2  where
            t1.cid=t2.parent_cid AND t.cid=t1.cid
            ) and  t.parent_cid = $cid
            or t.parent_cid in (select cid from category where parent_cid = '$cid');";

        $db  = Yii::app()->sdb_athena;
        $results = $db->createCommand($sql)->queryAll();
        return $results;
    }

    public function statistic($from, $to){
        $sql = "select b.*, a.shop_num, a.twitter_num from
            (select DATE_FORMAT(create_time, '%Y-%m-%d') time , count(distinct shop_id) shop_num,
                count(distinct twitter_id) twitter_num
            from shop_groupon_info master
                where create_time > '$from 00:00:00'  and create_time <= '$to 24:00:00'
                group by DATE_FORMAT(create_time, '%Y-%m-%d'))   a
            left join
            (select  DATE_FORMAT(create_time, '%Y-%m-%d') time , audit_status, count(1) num

            from shop_groupon_info where create_time > '$from 00:00:00'  and create_time <= '$to 24:00:00' group by DATE_FORMAT(create_time, '%Y-%m-%d'), audit_status) b
            on b.time  = a.time ;";

        $db = Yii::app()->sdb_brd_shop;
        $results = $db->createCommand($sql)->queryAll();


        $maps = array();
        foreach ($results as $key => $value) {
            if (!isset($maps[$value['time']])) {
                $maps[$value['time']] = array();
                $maps[$value['time']]['shop'] = $value['shop_num'];
                $maps[$value['time']]['twitter'] = $value['twitter_num'];
                $maps[$value['time']]['date'] = $value['time'];
            }
            $maps[$value['time']][$value['audit_status']] = $value['num'];
        }
        // var_dump($from);exit();
        return $maps;
    }

    public function getAuditReason($status,$channel=0) {
        $condition = '';
        if(!empty($channel)||$channel==0){
            $condition = " and channel = {$channel}";
        }
        $sql = 'select * from t_groupon_check_tips where status = 1 and type=' . $status.$condition;
        $db = Yii::app()->sdb_groupon;
        $results = $db->createCommand($sql)->queryAll();

        return $results;
    }

}