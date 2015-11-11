<?php
/**
 * 活动商品
 */

class EventGoodsManager extends Controller
{
    /**
     * 检索排期
     */
    public static $auditStatusInfo = array(
        '40' => '等待排期',
        '50' => '已排期',
        '51' => '排期失败',
    );

    /**
     * 检索活动商品是否被分配
     */
    public static $statusInfo = array(
            '0'  => '全部',
            '1'  => '未分配',
            '2'  => '已分配',
    );

    /**
     * 获取检索条件
     */
    public function searchParamMaker($request)
    {
        $shopVal = $request->getQuery('shop_val', '');
        // twitter
        $twitterId = $request->getQuery('twitter_id', '');
        // 推荐日期
        $date = $request->getQuery('date', '');
        // 价格
        $price = $request->getQuery('price', '');
        // 类目
        $category = $request->getQuery('category', '');
        // 所属人
        $opnick = $request->getQuery('opnick', '');
        // 排序
        $orderType = $request->getQuery('order_type', '');
        // 店铺类型
        $level = $request->getQuery('level', '#');
        // 状态
        $status = $request->getQuery('status', 0);
        // 是否是精品
        $isshow_tag = $request->getQuery('isshow_tag', '#');
        // 排序
        $orderType = $request->getQuery('order_type', '');

        $params = array(
            'twitter_id'=> $twitterId,
            'shop_val'=> $shopVal,
            'opnick'=> $opnick,
            'price'=> $price,
            'category'=> $category,
            'level'=> $level,
            'status'=>$status,
            'isshow_tag' => $isshow_tag,
            'order_type'=> $orderType
        );

        return $params;
    }

    // $time = 1 时间段精细化查询
    // $schedule = 1 时间为排期维度 否则为申请时间维度
    // $needTime = 1 默认会通过时间维度筛选
    public function getTwitterList($params, $time = 0, $schedule = 0, $needTime=1) {
        $where = 'where 1=1 ';
        // step1 时间类筛选
        if ($params['twitter_id']) {
            $where .= " and master.twitter_id in (" . $params['twitter_id'] .") ";
            $needTime = 0;
        }

        if ($params['shop_val']) {
            $where .= " and master.shop_id = '" . $params['shop_val'] . "' ";
            $needTime = 0;
        }
        // step2 状态筛选
        if ($params['audit_status'] != 0) {
            $where .= " and master.audit_status = " . $params['audit_status'];
        }
        // 是否精品
        if ($params['isshow_tag'] != '#') {
            $where .= " and master.isshow_tag='{$params['isshow_tag']}'";
        }
        // 还差price,所属人
        if ($params['price']) {
            $priceRangeInfo = OnlineManager::$priceRangeInfo;
            if (array_key_exists($params['price'], $priceRangeInfo)) {
                $priceInfo = $priceRangeInfo[$params['price']];
                if (isset($priceInfo['from'])) {
                    $where .= " and off_price >= '{$priceInfo['from']}'";
                }
                if (isset($priceInfo['to'])) {
                    $where .= " and off_price <= '{$priceInfo['to']}'";
                }
            }
        }

        $sql = 'select distinct master.id from shop_groupon_info master left join shop_groupon_goods_relation twitter
                on master.twitter_id = twitter.twitter_id
            left join brd_shop_groupon_shops_relation shop
                on master.shop_id = shop.shop_id ';

        // 活动维度
        if (!$params['status']) {
            $where .= " and master.id in (select groupon_id from tuan_events_item_detail where event_id = '"
                    . $params['event'] . "') and master.goods_type = 2";
        } elseif ($params['status'] == 1) {
            $where .= " and master.id in (select groupon_id from tuan_events_item_detail where event_id = '"
                    . $params['event'] . "' and category='0') and master.goods_type = 2";
        } else {
            $where .= " and master.id in (select groupon_id from tuan_events_item_detail where event_id = '"
                    . $params['event'] . "' and category>0) and master.goods_type = 2";
        }

        // 商家维度
        if ($params['level'] != '#') {
            // 签约商家
            if ($params['level'] == '##') {
                $where .= " and master.shop_id in (select shop_id from brd_shop_groupon_shops_relation where ka_level > 0) ";
            } else {
                $where .= " and master.shop_id in (select shop_id from brd_shop_groupon_shops_relation where ka_level = '"
                    . $params['level'] . "') ";
            }
        }

        // 初级类目
        if ($params['category']) {
            // 末级类目
            $where .= " and master.twitter_id in (select twitter_id from
                    shop_groupon_goods_relation where first_sort_id = '"
                        . $params['category'] . "') ";
        }

        $sql .= $where;

        if (isset($params['order_type'])) {
            $orderSql = $this->search->getOrderSql($params['order_type']);
            if ($orderSql) {
                $sql .= $orderSql;
            }
        }

        debug($sql);
        $db  = Yii::app()->sdb_brd_shop;
        $resultes = $db->createCommand($sql)->queryColumn();
        return $resultes;
    }

    /**
     * 批量更新活动下面的商品排期
     * @param int $eventId
     * @param array $params   start_time Y-m-d H:i:s
     */
    public function updateScheduleEventGoods($eventId, $params)
    {
        if ($eventId == 1065) return array('succ'=>0, 'msg'=>'1065活动不可被编辑');

        if (!$eventId) return array('succ'=>0, 'msg'=>'请传入event_id');

        $eventInfo = $this->event->getEventInfo($eventId);
        if (!$eventInfo) {
            return array('succ'=>0, 'msg'=>'活动信息不存在');
        }
        $startTime    = $params['start_time'] ? $params['start_time'] : $eventInfo['start_time'];
        $endTime      = $params['end_time'] ? $params['end_time'] : $eventInfo['end_time'];
        $preheatTime  = $params['preheat_time'] ? $params['preheat_time'] : $eventInfo['preheat_time'];
        if (!$preheatTime) {
            $preheatTime = $startTime - 24*60*60;
        }
        $updateDate   = array(
                "start_time"   => $startTime,
                "end_time"     => $endTime,
                "preheat_time" => $preheatTime,
        );

        $sdb_brd_shop = Yii::app()->sdb_brd_shop;
        $sql = "select t2.twitter_id, t2.id from tuan_events_item_detail t1 join shop_groupon_info t2 on t1.groupon_id=t2.id where t2.audit_status=50 and t1.event_id={$eventId} limit 5000";
        $goodsList = $sdb_brd_shop->createCommand($sql)->queryAll();

        $errNum  = 0;
        $succNum = 0;
        $errStr  = '';
        $db_brd_shop = Yii::app()->db_brd_shop;
        foreach ($goodsList as $k=>$v) {
            $where = array(
                    "twitter_id"    => $v['twitter_id'],
                    "campaign_id"   => $v['id'],
                    "campaign_type" => 2,
            );
            // 修改互斥表
            $_code = $this->util->newUpdateCampaignInfo($updateDate, $where);
            if ($_code['succ'] == 0) {
                $errNum++;
                $errStr .= "twitter_id：{$v['twitter_id']},groupon_id：{$v['id']},start_time：{$updateDate['start_time']},end_time：{$updateDate['end_time']},preheat_time：{$updateDate['preheat_time']},msg：{$_code['msg']} __\r\n";
                continue;
            }
            // 更新groupon表
            $db_brd_shop->createCommand()->update(
                    'shop_groupon_info',
                    array("start_time" => $startTime, "end_time" => $endTime),
                    'id=:id',
                    array(':id'=>$v['id'])
            );
            $succNum++;
        }

        return array('succ'=>1, 'msg'=>'success', 'succ_num'=>$succNum, 'err_num'=>$errNum, 'err_str'=>$errStr);
    }

    /**
     * 获取活动下面的商品
     * @param array $params
     */
    public function getEventGoodsList($params)
    {
        $event_id = isset($params['event_id']) ? $params['event_id'] : 0;
        $start_time = isset($params['start_time']) ? $params['start_time'] : 0;
        $end_time   = isset($params['end_time']) ? $params['end_time'] : 0;
        $order_by   = isset($params['order_by']) ? $params['order_by'] : '';
        $order_type = isset($params['order_type']) ? $params['order_type'] : '';

        if (!$event_id) return array();

        $sql = "select distinct t1.id from shop_groupon_info as t1 left join tuan_events_item_detail as t2 on t1.id=t2.groupon_id where t2.event_id=".$event_id." and t1.audit_status=50 and goods_type=2";
        if ($start_time) {
            $sql .= " and t1.start_time>={$start_time}";
        }
        if ($end_time) {
            $sql .= " and t1.end_time<={$end_time}";
        }
        if ($order_by && $order_type) {
            $sql .= " order by t2.{$order_by} {$order_type}";
        } else {
            $sql .= " order by t2.rank desc";
        }

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