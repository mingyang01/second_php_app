<?php

class OnlineManager extends Manager
{
    /**
     * 价格赛选区间
     */
    public static $priceRangeInfo = array('--20' => array('to' => '20'), '20--40' => array('from' => 20, 'to' => 40), '40--80' => array('from' => 40, 'to' => 80), '80--200' => array('from' => 80, 'to' => 200), '200--' => array('from' => '200'));

    /**
     * 分类
     */
    public static $categoryInfo = array(
        '11801' => '女装',
        '11803' => '女鞋',
        '11805' => '女包',
        '11807' => '配饰',
        '11809' => '家居',
        '12313' => '美妆',
        '12511' => '男装',
        '12591' => '童装',
        '12661' => '食品',
        '12803' => '童鞋',
        '12763' => '男鞋',
        '12843' => '男包',
        '13097' => '数码小家电'
    );

    /**
     * 所属人
     */
    public static $opnickInfo = array(
        'lucui'         => '崔露',
        'xiaochenshi'   => '石晓晨',
        'haiyingli'     => '李海英',
        'binhan'        => '韩彬',
        'jinggao'       => '高静',
        'sanshanwang'   => '王三山',
        'wenyangna'     => '那文洋',
        'tingtingzhao'  => '赵婷婷',
        'qian'          => '安琪',
    );

    /**
     * 销量
     */
    public static $orderInfo = array(
        'sale_num_order'   => '销量',
        'category_order'   => '分类',
        'shop_order'       => '店铺',
        'shop_create_time' => '申请时间',
    );

    /**
     * 标签分类
     */
    public static $tuangouTagMap = array(
        '10' => '上新',
        '11' => '爆款',
        '0'  => '取消打标',
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
        // 报名开始
        $from = $request->getQuery('from', date('Y-m-d', strtotime("-14 day")));
        // 报名结束
        $to = $request->getQuery('to', date("Y-m-d",strtotime("+1 day")));
        // 0 普通 1 活动
        $type = $request->getQuery('type', 0);
        // 活动
        $event = $request->getQuery('event', '');
        // 是否是精品
        $isshow_tag = $request->getQuery('isshow_tag', '#');
        // 末级分类
        $endCatagory = $request->getQuery('end-major', '0');

        $params = array(
                'from'=> $from,
                'to'=> $to,
                'date'=> $date,
                'twitter_id'=> $twitterId,
                'shop_val'=> $shopVal,
                'opnick'=> $opnick,
                'price'=> $price,
                'category'=> $category,
                'order_type'=> $orderType,
                'level'=> $level,
                'event'=> $event,
                'type' => $type,
                'isshow_tag' => $isshow_tag,
                'endCatagory'=> $endCatagory,
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

        // step3 时间类筛选
        if (trim($params['to'])=='' || trim($params['from'])=='') {
            $needTime = 0;
        }
        // 还差price,所属人
        if ($params['price']) {
            $priceRangeInfo = self::$priceRangeInfo;
            if (array_key_exists($params['price'], $priceRangeInfo)) {
                $priceInfo = $priceRangeInfo[$params['price']];
                if (isset($priceInfo['from'])) {
                    $where .= " and master.off_price >= '{$priceInfo['from']}'";
                }
                if (isset($priceInfo['to'])) {
                    $where .= " and master.off_price <= '{$priceInfo['to']}'";
                }
            }
        }

        // 还差price,所属人
        if ($params['date']) {
            $where .= " and master.start_time >= '".strtotime($params['date']." 00:00:00")."'";
            $where .= " and master.start_time <= '".strtotime($params['date']." 23:59:59")."'";
            $needTime = 0;
        }

        if ($needTime) {
            if ($time == 0) {
                $params['from'] = $params['from'] . " 00:00:00";
                $params['to'] = $params['to'] . " 00:00:00";
            }
            // 兼容性代码
            if ($params['audit_status'] == 50) {
                $schedule = 1;
            }
            // 排期成功状态下, 按排期时间筛选, 其他状态按申请时间排序
            if ($schedule == 1) {
                if ($time == 1) {
                    // 精确时间匹配（惊喜秒杀）
                    $where .= " and master.start_time = unix_timestamp('" . $params['from'] . "') "
                            . " and master.end_time <= unix_timestamp('" . $params['from'] . "') ";
                } else {
                    $where .= " and master.start_time >= unix_timestamp('" . $params['from'] . "') "
                            . " and master.start_time <= unix_timestamp('" . $params['to'] . "') ";
                }

            } else {
                $where .= " and master.create_time >= '" . $params['from'] . "' "
                        . " and master.create_time <= '" . $params['to'] . "' ";
            }
        }

        if ($params['recommendDate'] == 1) {
            if ($params['recommend_status'] == 1) {
                $where .= " and (master.start_time = '0' or master.start_time is null)";
            } else {
                $where .= " and master.start_time is not null and master.start_time > '0'";
            }
        } elseif ($params['audit_status'] == 40 && !$params['date']) {
            $where .= " and master.start_time is not null and start_time > '0'";
        }


        $sql = 'select distinct master.id from shop_groupon_info master left join shop_groupon_goods_relation twitter
                on master.twitter_id = twitter.twitter_id
            left join brd_shop_groupon_shops_relation shop
                on master.shop_id = shop.shop_id ';

        // 活动维度
        if ($params['type'] == 1 && $params['event'] != '') {
            $where .= " and master.id in (select groupon_id from tuan_events_item_detail where event_id = '"
                    . $params['event'] . "') and master.goods_type = 2 ";
        } else {
            $where .= " and master.goods_type=0 ";
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

        // 商品维度
        // 初级类目
        /*if ($params['category']) {
            // 末级类目
            $where .= " and master.twitter_id in (select twitter_id from
                    shop_groupon_goods_relation where first_sort_id = '"
                        . $params['category'] . "') ";
        }*/

        // 初级类目
        if ($params['category']) {
            // 末级类目
            if (isset($params['endCatagory']) && $params['endCatagory'] && $params['endCatagory'] > 0) {
                $where .= " and master.twitter_id in (select twitter_id from
                    shop_groupon_goods_relation
                    where first_sort_id = '" . $params['category'] .
                            "' and sort_id = '"
                                    . $params['endCatagory'] . "') ";
            } else {
                $where .= " and master.twitter_id in (select twitter_id from
                    shop_groupon_goods_relation where first_sort_id = '"
                        . $params['category'] . "') ";
            }
        }

        $sql .= $where;

        if (isset($params['order_type'])) {
            $orderSql = $this->search->getOrderSql($params['order_type']);
            if ($orderSql) {
                $sql .= $orderSql;
            }
        }

        debug($sql);
        //$db  = Yii::app()->sdb_brd_shop;
        // @FIXME 由于主从同步延时，暂时吧从库改成主库
        $db  = Yii::app()->db_brd_shop;
        $resultes = $db->createCommand($sql)->queryColumn();
        return $resultes;
    }

    /**
     * 按天统计 获取团购id
     * @param $date
     */
    public function getTwitterIds($date)
    {
        $sql = "select distinct master.id from shop_groupon_info master where 1=1  and audit_status = 50 and start_time >= '".strtotime($date." 00:00:00")."' and start_time <= '".strtotime($date." 23:59:59")."' and goods_type=0";
        $db  = Yii::app()->sdb_brd_shop;
        $resultes = $db->createCommand($sql)->queryColumn();
        return $resultes;
    }
}
?>