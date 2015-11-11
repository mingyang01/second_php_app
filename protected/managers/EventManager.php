<?php

class EventManager extends Manager
{
    /**
     * 活动的类型 主要用于前台赛选展示
     */
    static $show_event_type_enum = array(
            "1" => "主题活动",
            "2" => "聚合活动",
            "3" => "惊喜秒杀",
            "4" => "精品抢鲜",
            "8" => "清仓活动",
            "9" => "长期活动",
            "10" => "会员阶梯价活动"
    );

    /**
     * 活动状态 主要用于前台赛选展示
     */
    static $show_event_delete_type_enum = array(
            "0" => "全部",
            "1" => "删除",
    );

    static private $event_type_enum = array(
        "ZHUTI"   => 1,
        "JUHEYE"  => 2,
        "JINGXI"  => 3,
        "JINGPIN" => 4,
        "QINGCANG" => 8,
        "CHANGQI"  => 9,
        "HUIYUAN"  => 10,
    );

    static private $event_page_type_enum = array(
        "NORMAL" => 0,
        "DELETED" => 1,
    );

    static private $event_declare = array(
        "ALL"     => "活动有以下类型：主题活动、聚合页活动",
        "ZHUTI"   => "主题活动：
                   可通过两种方式加入：商家报名方式、后台直接加入twitter_id的方式。",
        "JUHEYE"  => "聚合页活动：仅能从已验货成功的团购商品中加入。",
        "JINGXI" => "惊喜秒杀活动",
        "JINGPIN"  => "精品抢鲜活动",
        "QINGCANG" => "清仓活动",
        "CHANGQI"  => "长期活动",
        "HUIYUAN"  => "会员阶梯价活动",
    );

    /**
     * notice 状态 status
     */
    static public $noticeStatusEnum = array(
        0 => '展示',
        1 => '不展示'
    );

    /**
     * notice 分类  cata_id
     */
    static public $noticeCateIdEnum = array(
        2  => '团购公告',
        21 => '主题活动公告'
    );

    /**
     * 添加活动时候的活动类型
     */
    static public $evnetTypeEnum = array(
            "10" => "主题活动", "20" => "聚合页", "30"=>"惊喜秒杀", "40"=>"精品抢鲜", "80"=>"清仓活动","90"=>"长期活动",
            "100" => "会员阶梯价活动"
    );

    /**
     * 活动所属频道
     */
    static public $channelMap = array(
        "1" => "团购",
        "2" => "秒杀",
        "3" => "清仓",
        "6" => "会员",
    );

    static public $evnetCateEnum = array(
            '11801' => '女装', '11803' => '女鞋', '11805' => '女包', '11807' => '配饰', '11809' => '家居', '12313' => '美妆',
            '12511' => '男装', '12591' => '童装', '12661' => '食品', '12803' => '童鞋', '12763' => '男鞋', '12843' => '男包', '13097' => '数码小家电'
    );

    /**
     * 活动的交易类型
     * 活动类型 =》 交易类型
     * 2团购，10秒杀，15清仓，16长期
     */
    static public $eventBusinessTypeMap = array(
            "10" => "2",
            "20" => "2",
            "30" => "10",
            "40" => "2",
            "80" => "15",
            "90" => "16",
            "100" => "17"
    );

    /**
     * 根据event自增id获取eventInfo
     * @param int $eventId
     * @return array
     */
    public function getEventInfo($eventId)
    {
        if (!$eventId) return array();

        $sdb_brd_shop  = Yii::app()->sdb_brd_shop;
        $searchSql = "select * from tuan_events_list where event_id='{$eventId}' limit 1";
        $eventInfo     = $sdb_brd_shop->createCommand($searchSql)->queryRow();
        if ($eventInfo) {
            return $eventInfo;
        } else {
            return array();
        }
    }

    /**
     * 获取活动报名数据
     * @param int $eventId
     * @return array
     */
    public function getEventGrouponInfo($twitterId,$eventId)
    {
        if (!$twitterId) return array();

        $sdb_brd_shop  = Yii::app()->sdb_brd_shop;
        $sql          = "select * from tuan_events_item_detail where twitter_id=$twitterId and event_id=$eventId order by id desc limit 1";
        $grouponInfo  = $sdb_brd_shop->createCommand($sql)->queryRow();
        if ($grouponInfo) {
            return $grouponInfo;
        } else {
            return array();
        }
    }

    /**
     * 获取活动报名信息
     * @param int $eventId
     * @return array
     */
    public function getEventGrouponInfoByGid($groupon_id)
    {
        if (!$groupon_id) return array();

        $sdb_brd_shop  = Yii::app()->sdb_brd_shop;
        $sql          = "select * from tuan_events_item_detail where groupon_id={$groupon_id} order by id desc limit 1";
        $grouponInfo  = $sdb_brd_shop->createCommand($sql)->queryRow();
        if ($grouponInfo) {
            return $grouponInfo;
        } else {
            return array();
        }
    }

    /**
     * 获取活动报名数据
     * @param int $eventId
     * @return array
     */
    public function getGrouponInfo($grupon_id)
    {
        if (!$grupon_id) return array();

        $sdb_brd_shop  = Yii::app()->sdb_brd_shop;
        $sql          = "select * from shop_groupon_info where id=$grupon_id order by id desc limit 1";
        $grouponInfo  = $sdb_brd_shop->createCommand($sql)->queryRow();

        if ($grouponInfo) {
            return $grouponInfo;
        } else {
            return array();
        }
    }


    /**
     * 获取活动列表
     * @param array $params
     * @return
     *  event_type_detail       赛选分类标题
     *  event_declare_detail    赛选分类说明
     *  page_type               显示类型 （用于操作按钮展示）
     *  result                  列表
     *  pager                   分页
     *  total                   活动总数量
     */
    public function getEventList($params)
    {
        $sql  = "select *, ";
        $sql .= " (case when t1.status % 10 = 1 then 'deleted!' else 'ok' end) event_status, ";
        $sql .= " (case when t1.status >= 10 and t1.status < 20 then '主题活动'  when t1.status >= 20 and t1.status < 30 then '聚合活动' when t1.status >= 30 and t1.status < 40 then '惊喜秒杀' when t1.status >= 40 and t1.status < 50 then '精品抢鲜' end) event_type ";
        $sql .= " from tuan_events_list as t1";

        // where条件
        $where_string = "";
        // 赛选分类标题
        $event_type_detail = "";
        // 赛选分类说明
        $event_declare_detail = "";
        // 排序条件
        $order = " ORDER BY event_id desc";
        // 显示类型 （用于操作按钮展示）
        $page_type = self::$event_page_type_enum["NORMAL"];

        // 活动名称
        if (!empty($params['event_name'])) {
            $where_string .= " AND event_name LIKE '%{$params['event_name']}%'";
        }
        // 活动标题
        if (!empty($params['title'])) {
            $where_string .= " AND title LIKE '%{$params['title']}%'";
        }
        // 活动开始时间
        if (!empty($params['start_time'])) {
            $start_time    = strtotime("{$params['start_time']} 00:00:00");
            $where_string .= " AND start_time >= '{$start_time}'";
        }
        // 活动结束时间
        if (!empty($params['end_time'])) {
            $end_time      = strtotime("{$params['end_time']} 23:59:59");
            $where_string .= " AND end_time <= '{$end_time}'";
        }
        // 活动结束时间
        if (!empty($params['channel']) && array_key_exists($params['channel'], self::$channelMap)) {
            $where_string .= " AND channel = '{$params['channel']}'";
        }

        // 判断类型
        if (!empty($params['show_type'])) {
            // 主题活动
            if (intval($params['show_type']) == self::$event_type_enum["ZHUTI"]) {
                // 正常的
                if (empty($params['show_deletes_type'])) {
                    $where_string .= " AND status >= 10 AND status < 20 AND status <> 11";
                    $event_type_detail = "主题活动";
                // 已删除
                } else {
                    $where_string .= " AND status = 11 ";
                    $event_type_detail = "主题活动(已删除)";
                }
                // 排序
                $order = " ORDER BY status, event_id desc";
                // 类型详情
                $event_declare_detail = self::$event_declare["ZHUTI"];
            // 聚合活动
            } else if (intval($params['show_type']) == self::$event_type_enum["JUHEYE"]) {
                if (empty($params['show_deletes_type'])) {
                    $where_string .= " AND status >= 20 AND status < 30 AND status <> 21";
                    $event_type_detail = "聚合页活动";
                } else {
                    $where_string .= " AND status = 21 ";
                    $event_type_detail = "聚合页活动(已删除)";
                }

                $order = " ORDER BY status desc , event_id desc";
                $event_declare_detail = self::$event_declare["JUHEYE"];
            // 惊喜秒杀
            } else if (intval($params['show_type']) == self::$event_type_enum["JINGXI"]) {
                if (empty($params['show_deletes_type'])) {
                    $where_string .= " AND status >= 30 AND status < 40 AND status <> 31";
                    $event_type_detail = "惊喜秒杀活动";
                } else {
                    $where_string .= " AND status = 31 ";
                    $event_type_detail = "惊喜秒杀活动(已删除)";
                }

                $order = " ORDER BY status desc , event_id desc";
                $event_declare_detail = self::$event_declare["JINGXI"];
            // 精品抢鲜
            } else if (intval($params['show_type']) == self::$event_type_enum["JINGPIN"]) {
                if (empty($params['show_deletes_type'])) {
                    $where_string .= " AND status >= 40 AND status < 50 AND status <> 41";
                    $event_type_detail = "精品抢鲜活动";
                } else {
                    $where_string .= " AND status = 41 ";
                    $event_type_detail = "精品抢鲜活动(已删除)";
                }

                $order = " ORDER BY status desc , event_id desc";
                $event_declare_detail = self::$event_declare["JINGPIN"];

            } else if (intval($params['show_type']) == self::$event_type_enum["QINGCANG"]) {
                if (empty($params['show_deletes_type'])) {
                    $where_string .= " AND status >= 80 AND status < 90 AND status <> 81";
                    $event_type_detail = "清仓活动";
                } else {
                    $where_string .= " AND status = 81 ";
                    $event_type_detail = "清仓活动(已删除)";
                }

                $order = " ORDER BY status desc , event_id desc";
                $event_declare_detail = self::$event_declare["QINGCANG"];
            }
            else if (intval($params['show_type']) == self::$event_type_enum["CHANGQI"]) {
                if (empty($params['show_deletes_type'])) {
                    $where_string .= " AND status >= 90 AND status < 100 AND status <> 91";
                    $event_type_detail = "长期活动";
                } else {
                    $where_string .= " AND status = 91 ";
                    $event_type_detail = "长期活动(已删除)";
                }

                $order = " ORDER BY status desc , event_id desc";
                $event_declare_detail = self::$event_declare["CHANGQI"];
            }
        // 查找已删除的
        } else if (!empty($params['show_deletes_type'])) {  // 展示“已删除”的活动
            $where_string .= " AND status IN (11,21,31,41,81,91)";
            $event_type_detail = "全部活动(已删除)";

            $page_type = self::$event_page_type_enum["DELETED"];
        }

        // 活动结束时间
        if (empty($params['is_show_end'])) {
            $now           = time();
            $where_string .= " AND end_time >= '{$now}'";
        }

        // 如果没有where条件默认暂时所有正常的活动
        if (empty($where_string)) {
            $where_string = " where (status % 10) <> 1";
            $event_type_detail = "所有有效活动";
            $event_declare_detail = self::$event_declare["ALL"];
        } else {
            $where_string = " where 1=1 " . $where_string;
        }

        $countSql = "select count(*) from tuan_events_list as t1";
        $countSql .= $where_string;

        //p($params,$sql, $countSql);//exit();
        $sdb_brd_shop = Yii::app()->sdb_brd_shop;
        $total        = $sdb_brd_shop->createCommand($countSql)->queryScalar();

        $out = array(
                "event_type_detail" => $event_type_detail,
                "event_declare_detail" => $event_declare_detail,
                "page_type" => $page_type,
                'result' => array(),
                'pager'  => array(),
                'total' => $total,
        );

        if ($total) {
            //分页的使用
            $pager = new PageManager($total,50);
            $out['pager'] = $pager;
            //p($pager,$total);
            $where_string .= $order;
            $sql          .= $where_string;
            $sql          .= " {$pager->limit}";

            $list=$sdb_brd_shop->createCommand($sql)->queryAll();

            // 判断是否有公告和签到
            foreach ($list as $k => $v) {
                // 公告在detail里面
                $detail = json_decode($v['detail'], true);
                if (is_array($detail)) {
                    $list[$k]['notice_id'] = $detail['notice_id'];
                } else {
                    $list[$k]['notice_id'] = 0;
                }
                if (preg_match("/签到商城/", $out['result'][$k]['event_name'])) {
                    $list[$k]['qiandao'] = 1;
                } else {
                    $list[$k]['qiandao'] = 0;
                }
            }

            $out['result'] = $list;
        }

        return $out;

    }

    /**
     * 恢复活动
     * @param int $eventId
     * @return boolean|unknown
     */
    public function recoverEvent($eventId)
    {
        if (!$eventId) return false;

        $sql          = "update tuan_events_list set status=(floor(status/10)*10) where event_id=" . intval($eventId);
        $db_brd_shop  = Yii::app()->db_brd_shop;
        $result       = $db_brd_shop->createCommand($sql)->execute();

        return $result;
    }

    /**
     * 删除活动
     * @param int $eventId
     * @return boolean|unknown
     */
    public function deleteEvent($eventId)
    {
        if (!$eventId) return false;

        $sql          = "update tuan_events_list set status=(floor(status/10)*10 + 1) where event_id=" . intval($eventId);
        $db_brd_shop  = Yii::app()->db_brd_shop;
        $result       = $db_brd_shop->createCommand($sql)->execute();

        return $result;
    }

    /**
     * 获取商品下的图片
     * @param int $eventId
     * @param int $areaId 区块id
     * @param int $areaSub  区块下子id
     * @return array
     */
    static public function getEventGoodsList($eventId, $areaId='', $areaSub='')
    {
        if (!$eventId) return array();

        $sql = "select event.id,event.twitter_id,event.event_id,event.category,event.area,event.rank,event.shop_id,event.groupon_id";
        $sql .= ",groupon.goods_name,groupon.goods_image,groupon.off_num,groupon.off_price,groupon.audit_status";
        $sql .= " from tuan_events_item_detail as event left join shop_groupon_info groupon on event.groupon_id=groupon.id where event_id={$eventId} AND category>0 and groupon.audit_status=50";

        $where = "";
        if (is_numeric($areaId)) {
            $where .= " AND event.area={$areaId}";
        }
        if (is_numeric($areaSub)) {
            $where .= " AND event.area_sub={$areaSub}";
        }
        if ($where) {
            $sql .= $where;
        }

        $order = " ORDER BY event.rank DESC";

        $sql .= $order;

        $sdb_brd_shop   = Yii::app()->sdb_brd_shop;
        $eventGoodsList = $sdb_brd_shop->createCommand($sql)->queryAll();

        if ($eventGoodsList) {
            return $eventGoodsList;
            //$sql =
        } else {
            return array();
        }
    }

    /**
     * 获取活动列表
     * @return multitype:unknown
     */
    public function getEvents() {
        $now_time = time();
        //$sql = "select event_id, event_name from tuan_events_list where (end_time > unix_timestamp() and (status is null or status < 20)) or (status >= 30 and status < 40)";
        $sql = "select event_id, event_name from tuan_events_list where (end_time > {$now_time} and (status is null or status < 20)) or status in (20,30,40)";
        $sdb_brd_shop   = Yii::app()->sdb_brd_shop;
        $eventList      = $sdb_brd_shop->createCommand($sql)->queryAll();
        $events = array();
        foreach ($eventList as $k=>$v) {
            $events[$v['event_id']] = $v['event_name'];
        }
        return $events;
    }

    /**
     * 获取活动的商品区域
     */
    public function getEventAreas($eventId)
    {
        if (!$eventId) return array();
        $eventInfo = $this->getEventInfo($eventId);
        if (!$eventInfo) return array();
        $detail = json_decode($eventInfo['detail'], true);
        if (!$detail) return array();

        //"10" => "主题活动", "20" => "聚合页", "30"=>"惊喜秒杀", "40"=>"精品抢鲜"
        $areaArr = array();
        if ($eventInfo['status'] < 30) {
            if (isset($detail['goods_list_area_banner']) && $detail['goods_list_area_banner']) {
                foreach ($detail['goods_list_area_banner'] as $k=>$v) {
                    $areaArr[$k] = array(
                        'area_id' => $k,
                        'pic'     => getImageUrl($v['pic']),
                        'title'   => $detail['right_nav_info']['right_nav'][$k]['name'],
                    );
                }
            }
        } elseif ($eventInfo['status'] >= 40 && $eventInfo['status'] < 50) {
            if (isset($detail['shops']) && $detail['shops']) {
                foreach ($detail['shops'] as $k=>$v) {
                    $areaArr[$k] = array(
                            'area_id' => $k,
                            'pic'     => getImageUrl($v['shop_img']),
                            'title'   => $v['shop_name'],
                    );
                }
            }
        } elseif ($eventInfo['status'] >= 30 && $eventInfo['status'] < 40) {
            if (isset($detail['shops']) && $detail['shops']) {
                foreach ($detail['tags'] as $k=>$v) {
                    if (isset($v['modules'])) {
                        foreach ($v['modules'] as $key=>$val) {
                            $areaArr[$k] = array(
                                    'area_id' => $k,
                                    'sub_area'=> $key,
                                    'pic'     => getImageUrl($val['module_img']),
                                    'title'   => $v['tag_name']." -- ".$val['module_name'],
                            );
                        }
                    }
                }
            } elseif (isset($detail['zhengdian_info']) && $detail['zhengdian_info']) {
                foreach ($detail['zhengdian_info'] as $k=>$v) {
                    $areaArr[$k] = array(
                        'area_id' => $k,
                        'pic'     => '',
                        'title'   => $v['stime']." -- ".$v['etime'],
                    );
                }
            }
        }
        return $areaArr;
    }

    /**
     * 根据event自增id获取活动规则
     * @param int $eventId
     * @return array
     */
    public function getEventRuleInfo($eventId)
    {
        if (!$eventId) return array();

        $sdb_groupon  = Yii::app()->sdb_groupon;
        $searchSql = "select * from t_groupon_event_ruler where event_id='{$eventId}' limit 1";
        $eventRuleInfo     = $sdb_groupon->createCommand($searchSql)->queryRow();
        if ($eventRuleInfo) {
            $eventRuleInfo['detail'] = json_decode($eventRuleInfo['detail'], true);
            return $eventRuleInfo;
        } else {
            return array();
        }
    }
}
