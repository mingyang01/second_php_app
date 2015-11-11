<?php
class SearchManager extends Manager {


	CONST HAS_APPLY = 10; //报名后待审核的状态；10

    public static $statusMap = array(20=>10, 21=>20, 22=>21,
        30=>20, 31=>30, 32=>31,
        40=>30, 41=>40, 42=>41,
        50=>40, 51=>50, 52=>51);

    /**
     * 排序类型
     */
    public static $orderInfo = array(
            'price_desc'  => '价格由高到低',
            'price_asc'   => '价格由低到高',
            'sale_desc'   => '销量由高到低',
            'sale_asc'    => '销量由低到高',
    );

    /**
     * 获取排序 sql
    */
    public function getOrderSql($orderType)
    {
        switch ($orderType) {
            case 'price_desc' :
                return ' order by master.off_price desc ';
                break;
            case 'price_asc' :
                return ' order by master.off_price asc ';
                break;
            case 'sale_desc' :
                return ' order by twitter.sale_num desc ';
                break;
            case 'sale_asc' :
                return ' order by twitter.sale_num asc ';
                break;
            default:
                return '';
                break;
        }
    }


    public function searchParamMaker($request) {
        $shop = $request->getQuery('shop', '');
        // twitter
        $twitter = $request->getQuery('twitter', '');
        // 报名开始
        $from = $request->getQuery('from', date('Y-m-d', strtotime("-14 day")));
        // 报名结束
        $to = $request->getQuery('to', date("Y-m-d",strtotime("+1 day")));
        // 审核进度
        $step = $request->getQuery('step', 2);
        // 审核状态
        $status = $request->getQuery('status', 0);
        // 商品类型
        // 0 普通 1 活动
        $type = $request->getQuery('type', 0);
        // 店铺类型
        $level = $request->getQuery('level', '#');
        // 商品分类
        $catagory = $request->getQuery('major', '0');
        // 末级分类
        $endCatagory = $request->getQuery('end-major', '0');
        // 活动
        $event = $request->getQuery('event', '');
        // 是否是精品
        $isshow_tag = $request->getQuery('isshow_tag', '#');
        // 业务  惊喜秒杀=3
        $business = $request->getQuery('business', '');
        // 排序
        $orderType = $request->getQuery('order_type', '');
        //区域
        $area = $request->getQuery('area', 0);
		//cs
		$cs_level = $request->getQuery('cs_level', 0);
        // 频道
        $channel = $request->getQuery('channel', '');
        // 交易类型
        $business_type = $request->getQuery('business_type', '');

        $params = array(
            'from'=> $from,
            'to'=> $to,
            'twitter'=> $twitter,
            'shop'=> $shop,
            'status'=> $status,
            'type'=> $type,
            'level'=> $level,
            'catagory'=> $catagory,
            'endCatagory'=> $endCatagory,
            'event'=> $event,
            'step'=> $step,
            'isshow_tag' => $isshow_tag,
            'business' => $business,
            'order_type' => $orderType,
        	'area'		=>	$area,
        	'cs_level'	=>	$cs_level,
            'business_type' => $business_type,
            'channel' => $channel,
        );

        return $params;
    }

    // $time = 1 时间段精细化查询
    // $schedule = 1 时间为排期维度 否则为申请时间维度
    // $needTime = 1 默认会通过时间维度筛选
    public function getTwitterList($params, $time = 0, $schedule = 0, $needTime=1) {
        $where = 'where 1=1 ';
        // step1 时间类筛选
        if ($params['twitter']) {
            $where .= " and master.twitter_id in (" . $params['twitter'] .") ";
            $needTime = 0;
        }

        if ($params['shop']) {
            $where .= " and master.shop_id = '" . $params['shop'] . "' ";
            $needTime = 0;
        }
        // step2 状态筛选
        if ($params['realStatus'] != 0) {
            $where .= " and master.audit_status = " . $params['realStatus'];
        }

        // 是否精品
        if ($params['isshow_tag'] != '#') {
            $where .= " and master.isshow_tag='{$params['isshow_tag']}'";
        }

        // step3 时间类筛选
        if (trim($params['to'])=='' || trim($params['from'])=='') {
            $needTime = 0;
        }

        if ($needTime) {
            if ($time == 0) {
                $params['from'] = $params['from'] . " 00:00:00";
                $params['to'] = $params['to'] . " 00:00:00";
            }
            // 兼容性代码
            if ($params['step'] == 5 && $params['status'] == 1) {
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

        $sql = 'select distinct master.id from shop_groupon_info master left join shop_groupon_goods_relation twitter
                on master.twitter_id = twitter.twitter_id
            left join brd_shop_groupon_shops_relation shop
                on master.shop_id = shop.shop_id ';


        // 活动维度
        if ($params['type'] == 1) {
            $eventFilter = "select groupon_id from tuan_events_item_detail
                where master.goods_type = 2 ";
            if ($params['event'] != '') {
                $eventFilter .= " and event_id = '" . $params['event'] . "' ";
            }
            $where .= " and master.id in ($eventFilter) and master.goods_type = 2 ";
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
        if ($params['catagory'] != '0') {
            // @FIXME 这里新增一级分类可能是个数组
            if (is_array($params['catagory'])) {
                $where .= " and master.twitter_id in (select twitter_id from
                    shop_groupon_goods_relation where first_sort_id in ("
                        . implode(",", $params['catagory']) . ")) ";
            } else {
                // 末级类目
                if ($params['endCatagory'] != '0') {
                    $where .= " and master.twitter_id in (select twitter_id from
                    shop_groupon_goods_relation
                    where first_sort_id = '" . $params['catagory'] .
                                    "' and sort_id = '"
                                            . $params['endCatagory'] . "') ";
                } else {
                    $where .= " and master.twitter_id in (select twitter_id from
                    shop_groupon_goods_relation where first_sort_id = '"
                            . $params['catagory'] . "') ";
                }
            }
        }


        $sql .= $where;

        if (isset($params['order_type'])) {
            $orderSql = $this->getOrderSql($params['order_type']);
            if ($orderSql) {
               $sql .= $orderSql;
            }
        }

        debug($sql);
        //debug($sql); //exit();
        $db  = Yii::app()->sdb_brd_shop;
        // @FIXME 由于主从同步延时，暂时吧从库改成主库
        // $db  = Yii::app()->db_brd_shop;
        $resultes = $db->createCommand($sql)->queryColumn();
        return $resultes;
    }

    /**
     * 获取店铺ID
     * @param unknown $params
     * @param $time = 1 时间段精细化查询
     * @param $schedule = 1 时间为排期维度 否则为申请时间维度
     * @param $needTime = 1 默认会通过时间维度筛选
     */
    public function getShopIdList($params, $time = 0, $schedule = 0, $needTime=1) {
    	$where = 'where 1=1 ';
    	// step1 时间类筛选
    	if ($params['twitter']) {
    		$where .= " and master.twitter_id in (" . $params['twitter'] .") ";
    		$needTime = 0;
    	}

    	if ($params['shop']) {
    		$where .= " and master.shop_id = '" . $params['shop'] . "' ";
    		$needTime = 0;
    	}
    	// step2 状态筛选
    	if ($params['realStatus'] != 0) {
    		$where .= " and master.audit_status = " . $params['realStatus'];
    	}

    	// 是否精品
    	if ($params['isshow_tag'] != '#') {
    		$where .= " and master.isshow_tag='{$params['isshow_tag']}'";
    	}

    	// step3 时间类筛选
    	if (trim($params['to'])=='' || trim($params['from'])=='') {
    		$needTime = 0;
    	}

    	if ($needTime) {
    		if ($time == 0) {
    			$params['from'] = $params['from'] . " 00:00:00";
    			$params['to'] = $params['to'] . " 00:00:00";
    		}
    		// 兼容性代码
    		if ($params['step'] == 5 && $params['status'] == 1) {
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

    	$sql = 'select distinct master.shop_id from shop_groupon_info master left join shop_groupon_goods_relation twitter
                on master.twitter_id = twitter.twitter_id
            left join brd_shop_groupon_shops_relation shop
                on master.shop_id = shop.shop_id ';

    	//店铺维度
    	if ($params['cs_level']) {
    		$where .= " and master.shop_id in (select shop_id from brd_shop_groupon_shops_relation where cs_level > 0)";
    	}

    	//区域维度
    	if ($params['area']) {
    		$where .= " and master.shop_id in (select shop_id from brd_shop_groupon_shops_relation where area=". $params['area'] .")";
    	}

    	// 活动维度
    	if ($params['type'] == 1) {
    		$eventFilter = "select groupon_id from tuan_events_item_detail
                where master.goods_type = 2 ";
    		if ($params['event'] != '') {
    			$eventFilter .= " and event_id = '" . $params['event'] . "' ";
    		}
    		$where .= " and master.id in ($eventFilter) and master.goods_type = 2 ";
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
    	if ($params['catagory'] != '0') {
    		// 末级类目
    		if ($params['endCatagory'] != '0') {
    			$where .= " and master.twitter_id in (select twitter_id from
                    shop_groupon_goods_relation
                    where first_sort_id = '" . $params['catagory'] .
                        "' and sort_id = '"
                        		. $params['endCatagory'] . "') ";
    		} else {
    			$where .= " and master.twitter_id in (select twitter_id from
                    shop_groupon_goods_relation where first_sort_id = '"
    					. $params['catagory'] . "') ";
    		}
    	}

    	$sql .= $where;


    	if (isset($params['order_type'])) {
    		$orderSql = $this->getOrderSql($params['order_type']);
    		if ($orderSql) {
    			$sql .= $orderSql;
    		}
    	}

    	//debug($sql); //exit();
    	$db  = Yii::app()->sdb_brd_shop;
    	// @FIXME 由于主从同步延时，暂时吧从库改成主库
    	// $db  = Yii::app()->db_brd_shop;
    	$resultes = $db->createCommand($sql)->queryColumn();
    	return $resultes;
    }

    /**
     * 获取店铺下的审核中的商品
     */
    public function getShopGids($evnet_id, $shop_id) {

    	$db  = Yii::app()->db_brd_shop;
    	$sql = "select distinct a.`id` from shop_groupon_info a, tuan_events_item_detail b where a.id = b.groupon_id and b.event_id=$evnet_id and a.shop_id=$shop_id and a.`audit_status` in (10, 20, 40) order by last_update_time desc";

    	$ids = $db->createCommand($sql)->queryColumn();

    	return $ids;
    }

    /**
     * 获取商品的历史纪录
     * @param 数组 $twitter_id
     * @param  $channal 业务类型
     */
    public function getGoodHistoty($twitter_id, $channal=80) {

    	!is_array($twitter_id) && $twitter_id=array($twitter_id);

    	$db  = Yii::app()->db_brd_shop;
    	$sql = "select a.*, b.event_id from shop_groupon_info a, tuan_events_item_detail b, tuan_events_list c
    	where a.id = b.groupon_id and b.event_id=c.event_id and c.status=$channal and a.twitter_id in ('".implode("','", $twitter_id) ."')
    	and a.audit_status=50 order by a.start_time asc";

    	$ids = $db->createCommand($sql)->queryAll();
    	return $ids;
    }

    //批量获取店铺下的商品twitter
    public function getShopTwitters($shop_ids, $even_id, $status=50) {

    	!is_array($shop_ids) && $shop_ids=array($shop_ids);

    	$db  = Yii::app()->db_brd_shop;
    	$sql = "select a.id, a.twitter_id from shop_groupon_info a, tuan_events_item_detail b
    	where a.id = b.groupon_id and a.shop_id in ('".implode("','", $shop_ids) ."') and a.audit_status=50 and b.event_id=$even_id";

    	$ids = $db->createCommand($sql)->queryAll();
    	return ArrFomate::hashmap($ids, 'twitter_id');
    }
}