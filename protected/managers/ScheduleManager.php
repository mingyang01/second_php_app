<?php
/**
 * 排期
 */
class ScheduleManager extends Manager {

    /** 允许更新的groupon字段 */
    static public $allowedUpdateInfo = array('goods_name', 'goods_image', 'goods_image_mob', 'goods_image_pc', 'weight_pc', 'weight_mob', 'tags', 'isshow_tag', 'op_type');

    /**
     * 排期
     * @param int $grouponId  团购id
     * @param int $startTime  团购开始时间
     * @param int $endTime    团购结束时间
     * @param array $updateArr  要更新groupon表中的团购信息
     * @param array $opInfo     操作者信息
     * @param array $repertory  库存信息
     * @return array succ==1 成功 succ ==0 失败
     */
    public function setSchedule($grouponId, $startTime, $endTime, $updateArr=array(), $opInfo=array(), $repertory=0)
    {
        $useTimeBegin = microtime(true);

        // 调用接口
        $r = $this->_setSchedule($grouponId, $startTime, $endTime, $updateArr, $opInfo, $repertory);

        $useTimeEnd = microtime(true);
        $useTime    = $useTimeEnd - $useTimeBegin;

        $logFiter = array(
            'groupon_id' => $grouponId,
            'user'       => $this->user->name,
            'name'       => '排期',
            'content'    => $r,
            'param'      => array(),
            'resource_name' => 'setSchedule',
        );
        $logFiter['param']['param'] = array(
            'groupon_id' => $grouponId,
            'start_time' => $startTime,
            'start_time' => $startTime,
            'end_time' => $endTime,
            'update_arr' => $updateArr,
            'op_info' => $opInfo,
            'repertory' => $repertory,
        );
        if (isset($r['campaign_filter'])) {
            $logFiter['param']['campaign_filter'] = $r['campaign_filter'];
        }
        if (isset($r['twitter_id'])) {
            $logFiter['twitter_id'] = $r['twitter_id'];
        }
        $logFiter['is_succ']  = $r['succ'];
        $logFiter['use_time'] =  number_format($useTime, 5);
        // 增加日志
        $this->tuanLog->addLog($logFiter);

        return $r;
    }

    /**
     * 取消排期
     * @pre_condition: audit_status=50
     * @post_condition: audit_status=51(排期失败) or audit_status=40(等待排期)
     *                  start_time = NULL, end_time = NULL
     *                  删除互斥表信息
     *                  删除mask
     *  if ($v == 51) {
     $start_time       = $startTimes[$k];
     $end_time         = $endTimes[$k];
     $tuanid           = $tuanIds[$k];
     $delete_condition = array(
     'campaign_type' => 2,
     'campaign_id'   => $tuanid,
     'platform'      => 1,
     );
     tuanhtModel::getInstance()->newDeleteCampaignInfo($delete_condition);
     #tuanhtModel::getInstance()->deleteCampaignInfo($delete_condition);
     tuanhtModel::getInstance()->update('t_coral_goods_mark', array('status' => 1), array('groupon_id' => $tuanid, 'mark_id' => 67));
     } //排期失败，删除互斥表,删除goods_mark的团购标签
     */
    public function cancelSchedule($grouponId, $opInfo=array(), $auditStatus=51)
    {
        $useTimeBegin = microtime(true);

        $r = $this->_cancelSchedule($grouponId, $opInfo, $auditStatus);

        $useTimeEnd = microtime(true);
        $useTime    = $useTimeEnd - $useTimeBegin;

        $logFiter = array(
                'groupon_id' => $grouponId,
                'user'       => $this->user->name,
                'name'       => '取消排期',
                'content'    => $r,
                'param'      => array(),
                'resource_name' => 'cancelSchedule',
        );
        $logFiter['param']['param'] = array(
            'groupon_id'   => $grouponId,
            'op_info' => $opInfo,
            'audit_status' => $auditStatus,
        );
        if (isset($r['campaign_filter'])) {
            $logFiter['param']['campaign_filter'] = $r['campaign_filter'];
        }
        if (isset($r['twitter_id'])) {
            $logFiter['twitter_id'] = $r['twitter_id'];
        }
        $logFiter['is_succ']  = $r['succ'];
        $logFiter['use_time'] = number_format($useTime, 5);
        // 增加日志
        $this->tuanLog->addLog($logFiter);

        return $r;
    }


    /**
     * 排期私有方法  参考 setSchedule
     */
    private function _setSchedule($grouponId, $startTime, $endTime, $updateArr=array(), $opInfo=array(), $repertory=0)
    {
        $grouponId = (int)$grouponId;
        if (!$grouponId) return array('succ'=>'0', 'msg'=>'请传入tuan购id');
        if (!$startTime || !$endTime) return array('succ'=>'0', 'msg'=>'请传入开始时间和结束时间');
        if ($endTime < $startTime) return array('succ'=>'0', 'msg'=>'结束时间不可以小于开始时间');

        // 判断参数是否可以修改
        if ($updateArr) {
            $allowedUpdateInfo = self::$allowedUpdateInfo;
            foreach ($updateArr as $k=>$v) {
                if (!in_array($k, $allowedUpdateInfo)) {
                    return array('succ'=>0, 'msg'=>'允许更新的参数错误：'.$k);
                }
            }
        }

        // 判断grouponinfo
        $grouponInfo = $this->event->getGrouponInfo($grouponId);
        if (!$grouponInfo) return array('succ'=>'0', 'msg'=>'团购信息不存在');
        if ($grouponInfo['audit_status'] != 40 && $grouponInfo['audit_status'] != 50) {
            return array('succ'=>'0', 'msg'=>'状态必须是40或者50的才可以排期,'.'audit_status error:'.$grouponInfo['audit_status'], 'data'=>$grouponInfo);
        }

        // 如果audit_status != 才会去判店铺商品限制    40是添加 50是修改
        $onlyUpdateInfo = false;
        if ($grouponInfo['audit_status'] == 50 && $grouponInfo['start_time'] == $startTime && $grouponInfo['end_time'] == $endTime) {
            $onlyUpdateInfo = true;
        }
        if (!$onlyUpdateInfo) {
            // 判断商铺商品限制
            $limitInfo = $this->newCanSchedule($grouponInfo, $startTime, $endTime);
            if ($limitInfo['succ'] != 1) {
                return $limitInfo;
            }
        }

        // 默认标题是每日团购，如果是活动类型的需要设置标题为活动title
        $preheatTag = "每日团购";
        $eventInfo  = array();
        if ($grouponInfo['goods_type'] == 2) {
            // 返回event  title
            $sdb_brd_shop = Yii::app()->sdb_brd_shop;
            $eventSql     = "select event.event_id, event.title, event.status, event.business_type,event.channel,event.detail from tuan_events_list as event left join tuan_events_item_detail as item on item.event_id=event.event_id where item.groupon_id='{$grouponId}'";
            //$preheatTag   = $sdb_brd_shop->createCommand($eventSql)->queryScalar();
            $eventInfo    = $sdb_brd_shop->createCommand($eventSql)->queryRow();
            $preheatTag   = $eventInfo['title'];
        }

        // 设置互斥表
        $campaignFilter = array(
                'twitter_id'    => $grouponInfo['twitter_id'],
                'start_time'    => date("Y-m-d H:i:s", $startTime),
                'end_time'      => date("Y-m-d H:i:s", $endTime),
                'preheat_time'  => date("Y-m-d H:i:s", $startTime - 24*60*60),
                'preheat_tag'   => $preheatTag,
                'campaign_id'   => $grouponInfo['id'],
                'campaign_type' => 2,
                'discount_type' => 2, //1,discount;2,price
                'discount_off'  => $grouponInfo['off_price'],
                'campaign_sku'  => 1,
        );

        if ($eventInfo) {
            if ($eventInfo['business_type']) {
                // 如果有交易类型，则直接走交易类型
                $campaignFilter['campaign_type'] = $eventInfo['business_type'];
            } elseif ($eventInfo['status'] >= 30 && $eventInfo['status'] < 40) {
                // 如果没有交易类型 并且为秒杀 则交易类型为10
                $campaignFilter['campaign_type'] = EventManager::$eventBusinessTypeMap["30"];
            } elseif ($eventInfo['status'] >= 80 && $eventInfo['status'] < 90) {
                // 如果没有交易类型 并且为清仓 则交易类型为15
                $campaignFilter['campaign_type'] = EventManager::$eventBusinessTypeMap["80"];
            } elseif ($eventInfo['status'] >= 90 && $eventInfo['status'] < 100) {
                // 如果没有交易类型 并且为长期活动 则交易类型为16
                $campaignFilter['campaign_type'] = EventManager::$eventBusinessTypeMap["90"];
            } elseif ($eventInfo['status'] >= 100 && $eventInfo['status'] < 110) {
                // 如果没有交易类型 并且为会员阶梯价 则交易类型为17
                $campaignFilter['campaign_type'] = EventManager::$eventBusinessTypeMap["100"];
            }
        }

        // 如果是会员阶梯价，计算阶梯价格折扣
        if ($campaignFilter['campaign_type'] == EventManager::$eventBusinessTypeMap["100"]) {
            $eventDetail = json_decode($eventInfo['detail'], true);
            if (!$eventDetail) {
                return array('succ'=>'0', 'msg'=>'您要排期的类型是会员阶梯价类型，目前该活动暂时无会员阶梯价信息，请完善完该信息重试!'.$eventDetail);
            }
            if (!$eventDetail['vip_discount_range'] || !is_array($eventDetail['vip_discount_range'])) return array('succ'=>'0', 'msg'=>'阶梯价折扣信息不完整');
            if (!$eventDetail['user_limit'] || !is_array($eventDetail['user_limit'])) return array('succ'=>'0', 'msg'=>'阶梯价可购买用户等级信息不完整');
            $vip_discount_info = array(
                'vip_discount_range' => $eventDetail['vip_discount_range'],
                'user_limit'         => $eventDetail['user_limit']
            );

            $campaignFilter['vip_discount_info'] = json_encode($vip_discount_info);
        }

        /*
        // @FIXME 如果是秒杀活动 设置 campaign_type 为10， 并且设置库存
        if ($eventInfo && ($eventInfo['status'] >= 30 && $eventInfo['status'] < 40)) {
            $campaignFilter['campaign_type'] = 10;
        }
        // @FIXME 清仓是15
        if ($eventInfo && ($eventInfo['status'] >= 80 && $eventInfo['status'] < 90)) {
            $campaignFilter['campaign_type'] = 15;
        }
        */

        if ($repertory  && is_numeric($repertory)){
            $campaignFilter['repertory']     = $repertory;
        }
        // 如果当前活动不是1065 并且有预热时间，修改预热时间
        if ($eventInfo && $eventInfo['event_id'] != 1065 && $eventInfo['event_id'] != 2005 && $eventInfo['preheat_time']) {
            $campaignFilter['preheat_time'] = $eventInfo['preheat_time'];
        }

        // 排期以前先做检查是否能排期
        $checkGoodsCampaignFilter = $campaignFilter;
        if ($checkGoodsCampaignFilter['campaign_type'] == 2) {
            $checkGoodsCampaignFilter['start_time'] = date("Y-m-d H:i:s", (strtotime($checkGoodsCampaignFilter['start_time']) - 3600*24));
        } elseif ($checkGoodsCampaignFilter['campaign_type'] == 10) {
            $checkGoodsCampaignFilter['end_time'] = date("Y-m-d H:i:s", (strtotime($checkGoodsCampaignFilter['end_time']) + 3600*24));
        }
        $checkGoodsResult = $this->util->checkGoodsNew($checkGoodsCampaignFilter);
        if ($checkGoodsResult['succ'] != 0) {
            return array('succ'=>'0', 'msg'=>"排期冲突: ".$checkGoodsResult['msg']);
        }

        //@FIXME 设置互斥表 curl调用接口
        $setResault = $this->util->newSetCampaignInfo($campaignFilter);
        if ($setResault['succ'] != 1) {
            // @FIXME 开发环境展示注释掉返回值
            $setResault['campaign_filter'] = $campaignFilter;
            $setResault['twitter_id']      = $grouponInfo['twitter_id'];
            return $setResault;
        }

        $db_brd_shop   = Yii::app()->db_brd_shop;
        $sdb_brd_shop  = Yii::app()->sdb_brd_shop;
        $db_coral      = Yii::app()->db_coral;

        // 设置opinfo默认值
        $opInfo['user']         = getOperatorId();
        $opInfo['time']         = date("Y-m-d H:i:s");
        $opInfo['audit_status'] = 50;
        // 插入log
        $this->addAuditComments($grouponInfo, $opInfo);

        // 更新shop_groupoon_info  主要更新 audit_status 字段 和 时间
        $updateArr['audit_status'] = 50;
        $updateArr['start_time']   = $startTime;
        $updateArr['end_time']     = $endTime;
        $db_brd_shop->createCommand()->update(
                'shop_groupon_info',
                $updateArr,
                'id=:id',
                array(':id'=>$grouponInfo['id'])
        );

        // @FIXME 如果是活动的商品并且不是秒杀 更新bridge_goods_info
        if ($eventInfo && $eventInfo['event_id'] != 1065 && $eventInfo['event_id'] != 2005) {
            // 更新shop_groupoon_info 大促统计表
            $bridgeSql = "update bridge_goods_info set audit_status=? where twitter_id=? and aid=?";
            $db_brd_shop->createCommand($bridgeSql)->execute(array(50, $grouponInfo['twitter_id'], $eventInfo['event_id']));
        }

        // 设置参加人数初始值.
        $dataSql     = "select * from shop_groupon_data where gid={$grouponInfo['id']}";
        $dataResult   = $sdb_brd_shop->createCommand($dataSql)->queryRow();
        if (!$dataResult) {
            $db_brd_shop->createCommand()->insert(
                    'shop_groupon_data',
                    array('gid' => $grouponInfo['id'], 'join_user_num'=>rand(50, 150))
            );
        }

        // 打团购标签
        $db_coral->createCommand()->update(
                't_coral_goods_mark',
                array('status'=>1),
                'groupon_id=:groupon_id',
                array(':groupon_id'=>(int)$grouponInfo['id'])
        );
        $db_coral->createCommand()->insert(
                't_coral_goods_mark',
                array(
                        'start_time' => date("Y-m-d H:i:s", $startTime), 'end_time' => date("Y-m-d H:i:s", $endTime)
                        , 'goods_id'=>$grouponInfo['goods_id'], 'mark_id'=>67, 'status'=>0, 'op_uid'=>$opInfo['user']
                        , 'op_date'=>date("Y-m-d H:i:s", time()), 'groupon_id'=>(int)$grouponInfo['id'],
                )
        );

        // 清仓不插入 这3 张表
        if ($eventInfo && ($eventInfo['status'] < 80 || $eventInfo['status'] >= 90)) {
            // 更新brd_shop_groupon_home_page_info的时间，如果可能的话
            $db_brd_shop->createCommand()->update(
                    'brd_shop_groupon_home_page_info',
                    array('start_time' => date("Y-m-d H:i:s", $startTime), 'end_time' => date("Y-m-d H:i:s", $endTime)),
                    'gid=:gid',
                    array(':gid'=>$grouponInfo['id'])
            );
            // 更新brd_shop_groupon_week_select_info的时间，如果可能的话
            $db_brd_shop->createCommand()->update(
                    'brd_shop_groupon_week_select_info',
                    array('start_time' => date("Y-m-d H:i:s", $startTime), 'end_time' => date("Y-m-d H:i:s", $endTime)),
                    'gid=:gid',
                    array(':gid'=>$grouponInfo['id'])
            );
            // 更新brd_shop_groupon_last_sold_info的时间，如果可能的话
            $db_brd_shop->createCommand()->update(
                    'brd_shop_groupon_last_sold_info',
                    array('start_time' => date("Y-m-d H:i:s", $startTime), 'end_time' => date("Y-m-d H:i:s", $endTime)),
                    'gid=:gid',
                    array(':gid'=>$grouponInfo['id'])
            );
        }

        return array('succ'=>1, 'msg'=>'排期成功', 'interface_result'=>$setResault, 'campaign_filter'=>$campaignFilter, 'twitter_id'=>$grouponInfo['twitter_id']);
    }

    /**
     * 添加log信息
     * @param array $grouponInfo
     * @param array $opInfo
     */
    private function addAuditComments($grouponInfo, $opInfo)
    {
        // 添加log
        $checkLog = array(
                'object_id' => $grouponInfo['id'],
                'types'     => 2,
                'ctime'     => isset($opInfo['time']) ? $opInfo['time'] : date("Y-m-d H:i:s"),
                'opuser'    => isset($opInfo['user']) ? $opInfo['user'] : getOperatorId(),
                'status'    => isset($opInfo['audit_status']) ? $opInfo['audit_status'] : 50,
                'datas'     => isset($opInfo['comments']) ? $opInfo['comments'] : '',
        );
        //p($checkLog);exit();
        $db_dolphin = Yii::app()->db_dolphin;
        $db_dolphin->createCommand()->insert('t_dolphin_tuan_checklog', $checkLog);

        $auditComments = array(
                'gid'            => $grouponInfo['id'],
                'audit_status'   => isset($opInfo['audit_status']) ? $opInfo['audit_status'] : 50,
                'audit_comments' => isset($opInfo['comments']) ? $opInfo['comments'] : '',
                'audit_time'     => isset($opInfo['time']) ? $opInfo['time'] : date("Y-m-d H:i:s"),
                'audit_user'     => isset($opInfo['user']) ? $opInfo['user'] : getOperatorId(),
                'audit_opname'   => isset($opInfo['opname']) ? $opInfo['opname'] : $this->user->name,
        );
        $db_brd_shop = Yii::app()->db_brd_shop;
        $db_brd_shop->createCommand()->insert('shop_groupon_audit_comments', $auditComments);
    }


    /**
     * 判断是否可以排期
     * @param array $grouponInfo
     * @param int $startTime
     * @param int $endTime
     * @return array
     */
    public function newCanSchedule($grouponInfo, $startTime, $endTime) {
        $results         = array();
        $results['succ'] = 1;
        $results['msg']  = 'success';

        // 获取该店铺这段时间内排期的所有商品
        $shopGoodsSql   = "select * from shop_groupon_info where shop_id='{$grouponInfo['shop_id']}' and audit_status='50' and goods_type='{$grouponInfo['goods_type']}' and start_time='{$startTime}' and end_time='{$endTime}'";
        $sdb_brd_shop   = Yii::app()->sdb_brd_shop;
        $shopGoodsList  = $sdb_brd_shop->createCommand($shopGoodsSql)->queryAll();

        $count1 = 0;
        $count2 = 0;
        $ts1 = $startTime;
        $items2 = array();

        foreach ($shopGoodsList as $item) {
            if ($item['id'] == $grouponInfo['id']) {
                // 排除自己
                continue;
            }
            $items2[] = array(
                    'id' => $item['id'], 'twitter_id' => $item['twitter_id'], 'start_time' => $item['start_time'], 'end_time' => $item['end_time']
            );
            if ($item['end_time'] > $ts1) {
                ++$count1;
            }
        }

        $items1 = $items2;
        $items1[] = array('id'=>$grouponInfo['id'], 'twitter_id'=>$grouponInfo['twitter_id'], 'start_time'=>$startTime, 'end_time'=>$endTime);
        // @FIXME 对比到这里
        $items1 = $this->getMaxIntersect($items1, 'start_time', 'end_time');
        if ($grouponInfo['goods_type'] == 0) {
            $sql            = "select value from t_groupon_global_config where `key` = 'online_list_num'";
            $sdb_groupon    = Yii::app()->sdb_groupon;
            $res            = $sdb_groupon->createCommand($sql)->queryRow();
            $_limit         = intval($res['value']);
            #$_limit = 3;
            if (count($items1) >= $_limit) {
                // 冲突的
                $results['succ'] = 0;
                $results['msg'] = "超过{$_limit}款同时在线";
                $results['shops'] = array(
                        'count1' => count($count1),
                        'items1' => $items1,
                );
                return $results;
            }
        }
        return $results;
    }

    public function getMaxIntersect($set, $k1, $k2)
    {
        if (!is_array($set)) {
            throw array();
        }
        if (empty($set)) {
            return array();
        }
        if (count($set) == 1) {
            return $set;
        }
        $_kmap = array();
        foreach ($set as $item) {
            for ($start = $item[$k1] + 60 * 60, $end = $item[$k2]; $start < $end; $start += 60 * 60) {
                if (isset($_kmap[$start]['num'])) {
                    $_kmap[$start]['num'] += 1;
                } else {
                    $_kmap[$start]['num'] = 1;
                };
                if (isset($_kmap[$start]['items'])) {
                    $_kmap[$start]['items'][] = $item;
                } else {
                    $num_list[$start]['items'] = array();
                };
            }
        }
        $num_list = array();
        foreach ($_kmap as $k => $v) {
            $num_list[] = $v['num'];
        }
        array_multisort($num_list, SORT_DESC, $_kmap);
        $first = array_shift($_kmap);
        return $first['items'];
    }

    /**
     * 取消排期私有方法，参考  cancelSchedule
     */
    private function _cancelSchedule($grouponId, $opInfo=array(), $auditStatus=51)
    {
        $grouponId = (int)$grouponId;

        if ($auditStatus != 51 && $auditStatus != 40) {
            return array('succ'=>0, 'msg'=>'要修改的状态必须为40或51'."audit_status error:".$auditStatus);
        }

        // 判断grouponinfo
        $grouponInfo = $this->event->getGrouponInfo($grouponId);
        if (!$grouponInfo) return array('succ'=>'0', 'msg'=>'团购信息不存在');
        if ($grouponInfo['audit_status'] != 50) {
            return array('succ'=>'0', 'msg'=>'状态必须是50的才可以取消排期,'.'audit_status error:'.$grouponInfo['audit_status'], 'data'=>$grouponInfo);
        }

        $now = time();
        $opInfo['audit_status'] = $auditStatus;
        $opInfo['time']         = date("Y-m-d H:i:s");

        $this->addAuditComments($grouponInfo, $opInfo);

        $db_brd_shop   = Yii::app()->db_brd_shop;
        $sdb_brd_shop  = Yii::app()->sdb_brd_shop;
        $db_coral      = Yii::app()->db_coral;

        $eventInfo  = array();
        if ($grouponInfo['goods_type'] == 2) {
            // 返回event  title
            $sdb_brd_shop = Yii::app()->sdb_brd_shop;
            $eventSql     = "select event.event_id, event.title, event.status, event.business_type,event.channel from tuan_events_list as event left join tuan_events_item_detail as item on item.event_id=event.event_id where item.groupon_id='{$grouponId}'";
            $eventInfo    = $sdb_brd_shop->createCommand($eventSql)->queryRow();
        }

        //@FIXME 设置互斥表 curl调用接口  t_shop_campaign_price_info
        $campaignFilter = array(
            'campaign_type' => 2,
            'campaign_id'   => $grouponInfo['id'],
            'twitter_id'    => $grouponInfo['twitter_id'],
            'platform'      => 1,
            'start_time'    => date('Y-m-d H:i:s', time()),
            'end_time'      => date('Y-m-d H:i:s', (time() + 2)),
        );


        if ($eventInfo) {
            if ($eventInfo['business_type']) {
                // 如果有交易类型，则直接走交易类型
                $campaignFilter['campaign_type'] = $eventInfo['business_type'];
            } elseif ($eventInfo['status'] >= 30 && $eventInfo['status'] < 40) {
                // 如果没有交易类型 并且为秒杀 则交易类型为10
                $campaignFilter['campaign_type'] = EventManager::$eventBusinessTypeMap["30"];
            } elseif ($eventInfo['status'] >= 80 && $eventInfo['status'] < 90) {
                // 如果没有交易类型 并且为清仓 则交易类型为15
                $campaignFilter['campaign_type'] = EventManager::$eventBusinessTypeMap["80"];
            } elseif ($eventInfo['status'] >= 90 && $eventInfo['status'] < 100) {
                // 如果没有交易类型 并且为长期活动 则交易类型为16
                $campaignFilter['campaign_type'] = EventManager::$eventBusinessTypeMap["90"];
            } elseif ($eventInfo['status'] >= 100 && $eventInfo['status'] < 110) {
                // 如果没有交易类型 并且为会员阶梯价 则交易类型为17
                $campaignFilter['campaign_type'] = EventManager::$eventBusinessTypeMap["100"];
            }
        }

        /*
        // @FIXME 如果是秒杀活动 设置 campaign_type 为10， 并且设置库存
        if ($eventInfo && ($eventInfo['status'] >= 30 && $eventInfo['status'] < 40)) {
            $campaignFilter['campaign_type'] = 10;
        }
        */

        $setResault = $this->util->newDeleteCampaignInfo($campaignFilter);
        if ($setResault['succ'] != 1) {
            // @FIXME 测试，注释掉接口限制
            $setResault['campaign_filter'] = $campaignFilter;
            $setResault['twitter_id']      = $grouponInfo['twitter_id'];
            return $setResault;
        }

        // 删除goods_mark的团购标签
        $goodsMarkUpdateSql = "update t_coral_goods_mark set status='1' where groupon_id='{$grouponId}' and mark_id='67'";
        $db_coral->createCommand($goodsMarkUpdateSql)->execute();

        // 更新audit_status
        $infoToUpdate = array(
                'audit_status'=>$auditStatus,
                'start_time'  => 0,
                'end_time'    => 0
        );
        $db_brd_shop->createCommand()->update(
                'shop_groupon_info',
                $infoToUpdate,
                'id=:id',
                array(':id'=>$grouponInfo['id'])
        );

        // 如果是活动商品则需要更新活动商品表
        if ($grouponInfo['goods_type'] == 2 && $eventInfo) {
            $updateSql = "update tuan_events_item_detail set flags=0, item_start_time=0, category=0, area=0, area_sub=0, subtype=0 where event_id={$eventInfo['event_id']} and groupon_id={$grouponInfo['id']}";
            $db_brd_shop->createCommand($updateSql)->execute();
        }

        // 更新brd_shop_groupon_home_page_info的时间，如果可能的话
        if ($auditStatus == 40) {
            $updateArr = array('start_time'=>'','end_time'=>'');
        } else {
            $updateArr = array('status' => 1);
        }

        // 更新brd_shop_groupon_home_page_info的时间，如果可能的话
        $db_brd_shop->createCommand()->update(
                'brd_shop_groupon_home_page_info',
                $updateArr,
                'gid=:gid',
                array(':gid'=>$grouponInfo['id'])
        );
        // 更新brd_shop_groupon_week_select_info的时间，如果可能的话
        $db_brd_shop->createCommand()->update(
                'brd_shop_groupon_week_select_info',
                $updateArr,
                'gid=:gid',
                array(':gid'=>$grouponInfo['id'])
        );
        // 更新brd_shop_groupon_last_sold_info的时间，如果可能的话
        $db_brd_shop->createCommand()->update(
                'brd_shop_groupon_last_sold_info',
                $updateArr,
                'gid=:gid',
                array(':gid'=>$grouponInfo['id'])
        );

        return array('succ'=>1, 'msg'=>'取消排期成功', 'interface_result'=>$setResault, 'campaign_filter'=>$campaignFilter, 'twitter_id'=>$grouponInfo['twitter_id']);
    }

    /**
     * 检测排期
     */
    public function checkSchedule($grouponId, $startTime, $endTime) {

    	$grouponId = (int)$grouponId;
    	if (!$grouponId) return array('succ'=>'0', 'msg'=>'请传入tuan购id');
    	if (!$startTime || !$endTime) return array('succ'=>'0', 'msg'=>'请传入开始时间和结束时间');
    	if ($endTime < $startTime) return array('succ'=>'0', 'msg'=>'结束时间不可以小于开始时间');

    	// 判断grouponinfo
        $grouponInfo = $this->event->getGrouponInfo($grouponId);
        if (!$grouponInfo) return array('succ'=>'0', 'succ'=>'团购信息不存在');
        if ($grouponInfo['audit_status'] != 40 && $grouponInfo['audit_status'] != 50) {
            return array('succ'=>'0', 'succ'=>'状态必须是40或者50的才可以排期,'.'audit_status error:'.$grouponInfo['audit_status'], 'data'=>$grouponInfo);
        }

        // 如果audit_status != 才会去判店铺商品限制    40是添加 50是修改
        $onlyUpdateInfo = false;
        if ($grouponInfo['audit_status'] == 50 && $grouponInfo['start_time'] == $startTime && $grouponInfo['end_time'] == $endTime) {
            $onlyUpdateInfo = true;
        }
        if (!$onlyUpdateInfo) {
            // 判断商铺商品限制
            $limitInfo = $this->newCanSchedule($grouponInfo, $startTime, $endTime);
            if ($limitInfo['succ'] != 1) {
                return $limitInfo;
            }
        }

    	// 默认标题是每日团购，如果是活动类型的需要设置标题为活动title
    	$preheatTag = "每日团购";
    	$eventInfo  = array();
    	if ($grouponInfo['goods_type'] == 2) {
    		// 返回event  title
    		$sdb_brd_shop = Yii::app()->sdb_brd_shop;
    		$eventSql     = "select event.event_id, event.title, event.status from tuan_events_list as event left join tuan_events_item_detail as item on item.event_id=event.event_id where item.groupon_id='{$grouponId}'";
    		//$preheatTag   = $sdb_brd_shop->createCommand($eventSql)->queryScalar();
    		$eventInfo    = $sdb_brd_shop->createCommand($eventSql)->queryRow();
    		$preheatTag   = $eventInfo['title'];
    	}

    	// 设置互斥表
    	$campaignFilter = array(
    			'twitter_id'    => $grouponInfo['twitter_id'],
    			'start_time'    => date("Y-m-d H:i:s", $startTime),
    			'end_time'      => date("Y-m-d H:i:s", $endTime),
    			'preheat_time'  => date("Y-m-d H:i:s", $startTime - 24*60*60),
    			'preheat_tag'   => $preheatTag,
    			'campaign_id'   => $grouponInfo['id'],
    			'campaign_type' => 2,
    			'discount_type' => 2, //1,discount;2,price
    			'discount_off'  => $grouponInfo['off_price'],
    			'campaign_sku'  => 1,
    	);
    	// @FIXME 如果是秒杀活动 设置 campaign_type 为10， 并且设置库存
    	if ($eventInfo && ($eventInfo['status'] >= 30 && $eventInfo['status'] < 40)) {
    		$campaignFilter['campaign_type'] = 10;
    	}
    	// @FIXME 清仓是15
    	if ($eventInfo && ($eventInfo['status'] >= 80 && $eventInfo['status'] < 90)) {
    		$campaignFilter['campaign_type'] = 15;
    	}

    	// 如果当前活动不是1065 并且有预热时间，修改预热时间
    	if ($eventInfo && $eventInfo['event_id'] != 1065 && $eventInfo['event_id'] != 2005 && $eventInfo['preheat_time']) {
    		$campaignFilter['preheat_time'] = $eventInfo['preheat_time'];
    	}
    	//判断互斥表是否允许添加
    	$util = new UtilManager();
    	$ret = $util->checkGoodsNew($campaignFilter);
    	if ($ret['succ'] == 0) {
    		return array('succ'=>'1', 'msg'=>'操作成功');
    	}
		return $ret;
    }
}