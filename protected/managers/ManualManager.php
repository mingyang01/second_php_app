<?php
// chao
class ManualManager extends Manager {

    // 几张表
    public static $dbInfo = array('week'=>'brd_shop_groupon_week_select_info', 'last'=>'brd_shop_groupon_last_sold_info', 'home'=>'brd_shop_groupon_home_page_info');

    function getWeekList($date) {
        $end  = $date . " 10:00:00";
        // $start = $date . " " . date("H:i:s", time());

        $sql = "select gid from brd_shop_groupon_week_select_info
            where start_time = '{$end}'
            and status=0 order by `order` desc";

        debug($sql);
        $db  = Yii::app()->sdb_brd_shop;
        $resultes = $db->createCommand($sql)->queryColumn();

        return $resultes;
    }

    function getLastList($date) {
        $end  = date('Y-m-d 10:00:00', (strtotime($date) + 86400));
        // $start = $date . " " . date("H:i:s", time());

        $sql = "select gid from brd_shop_groupon_last_sold_info
            where end_time = '{$end}'
            and status=0 order by `order` desc";


        $db  = Yii::app()->sdb_brd_shop;
        $resultes = $db->createCommand($sql)->queryColumn();

        return $resultes;
    }

    function getWorthList($date) {

        $end  = $date . " 10:00:00";
        // $start = $date . " " . date("H:i:s", time());

        $sql = "select gid from brd_shop_groupon_home_page_info
            where start_time = '{$end}'
            and status=0 order by `order` desc";


        $db  = Yii::app()->sdb_brd_shop;
        $resultes = $db->createCommand($sql)->queryColumn();

        return $resultes;

// "select gid from brd_shop_groupon_home_page_info where start_time <= now() and end_time > now() and status=0 order by `order` desc limit";

        /*
        $end  = $date . " 23:59:59";
        $start = $date . " " . date("H:i:s", time());

        $sql = "select gid from brd_shop_groupon_home_page_info
        where start_time < '{$end}' and end_time > '{$start}'
        and status=0 order by `order` desc";


        $db  = Yii::app()->sdb_brd_shop;
        $resultes = $db->createCommand($sql)->queryColumn();
*/
        return $resultes;
    }

    // 频道列表
    // type = 0  date for start date else for end
    // select = 1 已到 2 未到
    public function getCurrentList($date, $gids=array(), $type=0, $select=1) {
        $time = strtotime($date . ' 10:00:00');
        $sql = "select shop_id shop, from_unixtime(start_time,  '%Y-%m-%d' ) as start, from_unixtime(end_time, '%Y-%m-%d') end, id gid,
            twitter_id tid,  goods_image_pc img, round((off_num/(off_num + off_price)),2) rate,
            off_price price, (off_num + off_price) origin from shop_groupon_info
            where audit_status = 50 and goods_type = 0 ";

        if ($type == 0) {
            if ($select == 1) {
                $sql .= " and start_time <= $time ";
            } else {
                $sql .= " and end_time > $time and start_time <= $time";
            }
        } else {
            if ($select == 1) {
                $sql .= " and end_time >= $time ";
            } else {
                // 最后疯抢结束时间为当前时间+1天
                $sql .= " and end_time = ". ($time+3600*24);
            }
        }

        if (!empty($gids)) {
            $sql .= ' and id in (' . implode(', ', $gids) . ') order by field(id,'
                .implode(', ', $gids) .') ';
        } else {
            $sql .= ' order by start_time desc, off_price asc';
        }
        debug($sql);
        $db = yii::app()->sdb_brd_shop;
        $results = $db->createCommand($sql)->queryAll();
        return $results;
    }
}
