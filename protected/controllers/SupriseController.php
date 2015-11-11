<?php
class SupriseController extends Controller {
    public function ActionIndex() {

        $request = Yii::app()->getRequest();
        $params = $this->search->searchParamMaker($request);

        $to = $request->getQuery('to', date('Y-m-d'));

        $params['area'] = $this->suprise->getArea($to);
        $schedule = $request->getQuery('schedule', 0);
        if (count($params['area']) == 0) {
            $schedule = 1;
        }
        $start = $request->getQuery('start', $params['area'][0]['stime']);
        $end = date('Y-m-d 00:00:00', (strtotime($to) + 3600 * 24));

        $params['from'] = $start;
        $params['to'] = $end;

        // 默认显示排期成功的
        $previewList = $this->suprise->getPreviewList($start, $params['catagory']);
        $list = $this->common->array_column($previewList, 'id');

        debug($previewList);
        $append = array();
        foreach ($previewList as $key => $value) {
            $append[$value['id']] = array();
            $append[$value['id']]['rank'] = $value['rank'];
            $append[$value['id']]['limit'] = json_decode($value['plus_detail'])->total_limit;
            $append[$value['id']]['category'] = $value['category'];
        }

        $params['count'] = count($list);
        $params['to'] = $to;
        $params['append'] = $append;

        if ($params['count'] != 0) {
            $params['data'] = $this->audit->getTwitterDetail($list);
        }
        $params['mode'] = 'preview';
        $this->render('suprise/index.html', $params);
    }

    public function ActionSchedule() {
        $request = Yii::app()->getRequest();
        $params = $this->search->searchParamMaker($request);
        $to = $params['to'];

        $params['area'] = $this->suprise->getArea($to);
        $schedule = $request->getQuery('schedule', 1);
        if (count($params['area']) == 0) {
            echo "请先创建活动区域";
            Yii::app()->end();
        }
        $start = $request->getQuery('start', $params['area'][0]['stime']);
        $end = date('Y-m-d 00:00:00', (strtotime($to) + 3600 * 24));

        $params['from'] = $start;
        $params['to'] = $end;


        if ($schedule == 1) {
            $params['realStatus'] = 40;
            $params['event'] = 1065;
            $params['type'] = 1;
            // 忽略所有时间限制
            $list = $this->search->getTwitterList($params, 0, 0, 0);
        }

        $params['to'] = $to;
        $params['count'] = count($list);

        $params['data'] = array();

        // 瀑布流数据接口
        if (isset($_GET['data'])) {
            $page = $request->getQuery('page', 1) - 1;
            //  echo json_encode($this->audit->getTwitterDetail($list));
            $list =  array_slice($list, $page * 20, 20);
            if (count($list)){
                echo json_encode(array("total"=>$params['count'],
                    "result"=>$this->audit->getTwitterDetail($list)
                ));
            }
        } else {
            $params['limit'] = 1;
            if ($params['count'] != 0) {
                $params['data'] = $this->audit->getTwitterDetail($list);
            }
            // 拒绝原因
            $params['fail_reason'] = $this->audit->getAuditReason(51);
            $params['mode'] = 'schedule';
            $this->render('suprise/schedule.html', $params);
            // $this->render('suprise/infinite.html', $params);
        }
        // $page = $request->getQuery('page', 1) - 1;
        // $list =  array_slice($list, $page * 40, 40);
        // if ($params['count'] != 0) {
        //     $params['data'] = $this->audit->getTwitterDetail($list);
        // }
        // $this->render('suprise/waterpall.html', $params);
    }

    /**
     * 保存整点抢排期
     */
    public function ActionSaveZhengdianSechedule()
    {
        $request       = Yii::app()->request;
        $twitterIdsStr = $request->getPost('tuan_id','');
        $startTime     = $request->getPost('start_time', '');
        $endTime       = $request->getPost('end_time', '');
        $eventId       = $request->getPost('event_id', 1065);
        $repertory     = $request->getPost('repertory', 0);
        // 1: 秒杀商品
        $category      = 1;
        // 标签
        $opType        = $request->getPost('zdq_type', 0);
        if (!array_key_exists($opType, SupriseManager::$miaosh_tag_map)) {
            output(array('succ'=>0, 'msg'=>'标签类型错误'));
        }


        if (!$eventId) {
            output(array('succ'=>0, 'msg'=>'活动id不存在'));
        }
        if (!$eventId) {
            output(array('succ'=>0, 'msg'=>'活动id不存在'));
        }
        if (!preg_match("/^[0-9]{4}-[0-9]{2}-[0-9]{2} [0-9]{2}:[0-9]{2}$/", $startTime)) {
            output(array('succ'=>0, 'msg'=>'少年，请填写正确的开始时间'));
        }
        if (!preg_match("/^[0-9]{4}-[0-9]{2}-[0-9]{2} [0-9]{2}:[0-9]{2}$/", $endTime)) {
            output(array('succ'=>0, 'msg'=>'少年，请填写正确的结束时间'));
        }
        $eventInfo = $this->event->getEventInfo($eventId);
        if (!$eventInfo) {
            output(array('succ'=>0, 'msg'=>'活动信息不存在'));
        }
        if ($eventInfo['status'] < 30 || $eventInfo['status'] >= 40) {
            output(array('succ'=>0, 'msg'=>'活动类型不相符'));
        }

        $intStartTime = strtotime($startTime.":00");
        $intEndTime   = strtotime($endTime.":00");

        $twitterIdsArr= explode(",", $twitterIdsStr);

        $tuanids          = array();
        $valid_twitterids = array();

        $db_brd_shop    = Yii::app()->db_brd_shop;
        $sdb_brd_shop   = Yii::app()->sdb_brd_shop;
        $sql            = "select t.id, t.groupon_id, t.twitter_id, t.plus_detail from tuan_events_item_detail t join shop_groupon_info s on t.groupon_id=s.id where t.event_id={$eventId} and s.goods_type=2 and s.audit_status=40 and s.id in (" . join(",", $twitterIdsArr) . ")";
        $eventGoodsList = $sdb_brd_shop->createCommand($sql)->queryAll();
        if (!$eventGoodsList) {
            output(array('succ'=>0, 'msg'=>'没有可以排期的商品', $sql));
        }
        $succNum  = 0;
        $errNum   = 0;
        $succInfo = array();
        $scheduleResaultArr = array();
        $errResault = "";
        foreach ($eventGoodsList as $k=>$v) {

            $day  = strtotime(date('Y-m-d', $intStartTime));
            $hour = ($intStartTime - $day) / 3600;
            $scheduleResault = $this->schedule->setSchedule($v['groupon_id'], $intStartTime, $intEndTime, array('op_type'=>$opType), array(), $repertory);
            $scheduleResaultArr[] = $scheduleResault;
            if ($scheduleResault['succ'] != 1) {
                $errResault .= "twitter_id:".$v['twitter_id']."  tuan_id:".$v['groupon_id']."  失败提示: ".$scheduleResault['msg']."\r\n";
                $errNum++;
                continue;
            }

            $plusDetail = json_decode($v['plus_detail'], true);
            if (!$plusDetail) {
                $plusDetail = array();
            }
            $plusDetail['total_limit'] = $repertory;
            $plusDetailJson = json_encode($plusDetail);

            // 更新event表
            $updateSql = "update tuan_events_item_detail set plus_detail='{$plusDetailJson}',category={$category},area=1,flags=30,item_start_time={$day},subtype={$hour} where groupon_id={$v['groupon_id']} and event_id={$eventId}";
            $updateResault = $db_brd_shop->createCommand($updateSql)->execute();

            if ($updateResault) {
                $succNum++;
            } else {
                $errNum++;
                $errResault .= "twitter_id:".$v['twitter_id']."  tuan_id:".$v['groupon_id']."  失败提示: 更新 活动商品表 tuan_events_item_detail 失败 \r\n";
            }
        }

        //$this->updateCampaignInfo($eventId);

        output(array('succ'=>1, 'msg'=>'success', 'data'=>$succInfo, 'succ_num'=>$succNum, 'err_num'=>$errNum, 'schedule'=>$scheduleResaultArr, 'err_resault'=>$errResault));
    }

    /**
     * @param unknown $event_id
     */
    private function updateCampaignInfo($event_id)
    {
        //$model      = tuanhtModel::getInstance();
        $sdb_brd_shop = Yii::app()->sdb_brd_shop;
        $db_brd_shop  = Yii::app()->db_brd_shop;
        $event_map  = array(
                'today20'    => '223',
                'today22'    => '225',
                'tomorrow20' => '227',
                'tomorrow22' => '229',
        );
        $event_list = array_values($event_map);
        $today      = date('Y-m-d');
        $tomorrow   = date('Y-m-d', strtotime("+1 day"));
        $afterday   = date('Y-m-d', strtotime("+2 day"));

        $event_map_sql  = "select start_time,id from campaign_info where id in (" . implode(",", $event_list) . ")";
        //$event_map_rows = $model->queryBrdshop($event_map_sql);
        $event_map_rows = $sdb_brd_shop->createCommand($event_map_sql)->queryAll();
        foreach ($event_map_rows as $row) {
            if ($row['start_time'] == "{$today} 20:00:00" || $row['start_time'] == "{$afterday} 20:00:00") {
                $event_map['today20'] = $row['id'];
            }
            if ($row['start_time'] == "{$today} 22:00:00" || $row['start_time'] == "{$afterday} 22:00:00") {
                $event_map['today22'] = $row['id'];
            }
            if ($row['start_time'] == "{$tomorrow} 20:00:00") {
                $event_map['tomorrow20'] = $row['id'];
            }
            if ($row['start_time'] == "{$tomorrow} 22:00:00") {
                $event_map['tomorrow22'] = $row['id'];
            }

        }

        $current_hour = intval(date('H'));

        $today20    = $event_map['today20'];
        $today22    = $event_map['today22'];
        $tomorrow20 = $event_map['tomorrow20'];
        $tomorrow22 = $event_map['tomorrow22'];
        $afterday20 = $today20;
        $afterday22 = $today22;

        $goods_sql = " select t1.twitter_id, t1.shop_id, t1.goods_id, if (round(t1.off_num/10,1), round(t1.off_num/10,1), 0.1)  off_num,"
        . " from_unixtime(item_start_time) start_day, subtype hour, t1.off_price "
        . " from shop_groupon_info t1 join tuan_events_item_detail t2 on t1.id=t2.groupon_id "
        . " where t1.audit_status=50 and t1.goods_type=2 "
        . " and t2.item_start_time in (unix_timestamp('{$today}'), unix_timestamp('{$tomorrow}'),unix_timestamp('{$afterday}') ) "
        . " and t2.subtype in (20,22) and t2.category=1 and t2.event_id={$event_id}";
        //$goods_rows = $model->queryBrdshop($goods_sql);
        $goods_rows = $sdb_brd_shop->createCommand($goods_sql)->queryAll();
        if ($current_hour < 20) {
            //只更新今天和明天的商品，不更新活动时间
            foreach ($goods_rows as $goods_row) {
                $start_day  = $goods_row['start_day'];
                $hour       = $goods_row['hour'];
                $twitter_id = $goods_row['twitter_id'];
                $shop_id    = $goods_row['shop_id'];
                $goods_id   = $goods_row['goods_id'];
                $off_num    = $goods_row['off_num'];
                $off_price  = $goods_row['off_price'];
                if (strtotime($start_day) > strtotime($tomorrow)) {
                    continue;
                }
                $check_sql = "select id from campaign_goods_info where twitter_id={$twitter_id} "
                . " and aid in (" . implode(",", $event_list) . ") and audit_status=4 limit 1";
                //$check_row = $model->queryBrdshop($check_sql);
                $check_row = $sdb_brd_shop->createCommand($check_sql)->queryAll();
                if (count($check_row) == 1) {
                    continue;
                }
                if (strtotime($start_day) == strtotime($today)) {
                    if ($hour == 20) {
                        $aid = $today20;
                    }
                    if ($hour == 22) {
                        $aid = $today22;
                    }
                }
                if (strtotime($start_day) == strtotime($tomorrow)) {
                    if ($hour == 20) {
                        $aid = $tomorrow20;
                    }
                    if ($hour == 22) {
                        $aid = $tomorrow22;
                    }
                }
                $insert_sql = "insert into campaign_goods_info (aid, shop_id, goods_id, twitter_id, campaign_price, campaign_off, audit_status, create_time) values ({$aid}, {$shop_id}, {$goods_id}, {$twitter_id},{$off_price} , {$off_num}, 4, now())";
                //$model->writeBrdshop($insert_sql);
                $db_brd_shop->createCommand($insert_sql)->execute();
            }
        } else if ($current_hour < 22) {
            //更新今天8点活动的时间，更新商品的信息
            $check_ac_sql = "select id from campaign_info where id={$afterday20} and start_time='{$afterday} 20:00:00' limit 1";
            //$check_row    = $model->queryBrdshop($check_ac_sql);
            $check_row   = $sdb_brd_shop->createCommand($check_ac_sql)->queryAll();
            if (count($check_row) == 0) {
                $delete_twitter_sql = "delete from campaign_goods_info where aid={$afterday20}";
                // $model->writeBrdshop($delete_twitter_sql);
                $db_brd_shop->createCommand($delete_twitter_sql)->execute();
                $update_ac_sql = "update campaign_info set start_time='{$afterday} 20:00:00', campaign_preheat_time='{$tomorrow}' where id={$afterday20}";
                // $model->writeBrdshop($update_ac_sql);
                $db_brd_shop->createCommand($update_ac_sql)->execute();
            }
            foreach ($goods_rows as $goods_row) {
                $start_day  = $goods_row['start_day'];
                $hour       = $goods_row['hour'];
                $twitter_id = $goods_row['twitter_id'];
                $shop_id    = $goods_row['shop_id'];
                $goods_id   = $goods_row['goods_id'];
                $off_num    = $goods_row['off_num'];
                $off_price  = $goods_row['off_price'];
                if (strtotime($start_day) < strtotime($afterday)) {
                    continue;
                }
                if ($hour != 20) {
                    continue;
                }
                $check_sql = "select id from campaign_goods_info where twitter_id={$twitter_id} "
                . " and aid ={$afterday20} and audit_status=4 limit 1";
                // $check_row = $model->queryBrdshop($check_sql);
                $check_row = $sdb_brd_shop->createCommand($check_sql)->queryAll();
                if (count($check_row) == 1) {
                    continue;
                }
                $aid        = $afterday20;
                $insert_sql = "insert into campaign_goods_info (aid, shop_id, goods_id, twitter_id, campaign_price, campaign_off, audit_status, create_time) values ({$aid}, {$shop_id}, {$goods_id}, {$twitter_id},{$off_price} , {$off_num}, 4, now())";
                //$model->writeBrdshop($insert_sql);
                $db_brd_shop->createCommand($insert_sql)->execute();
            }
        } else {
            //更新今天10点活动的时间，更新商品的信息
            $check_ac_sql = "select id from campaign_info where id={$afterday22} and start_time='{$afterday} 22:00:00' limit 1";
            // $check_row    = $model->queryBrdshop($check_ac_sql);
            $check_row    = $sdb_brd_shop->createCommand($check_ac_sql)->queryAll();
            if (count($check_row) == 0) {
                $delete_twitter_sql = "delete from campaign_goods_info where aid={$afterday22}";
                // $model->writeBrdshop($delete_twitter_sql);
                $db_brd_shop->createCommand($delete_twitter_sql)->execute();
                $update_ac_sql = "update campaign_info set start_time='{$afterday} 22:00:00', campaign_preheat_time='{$tomorrow}' where id={$afterday22}";
                // $model->writeBrdshop($update_ac_sql);
                $db_brd_shop->createCommand($update_ac_sql)->execute();
            }
            foreach ($goods_rows as $goods_row) {
                $start_day  = $goods_row['start_day'];
                $hour       = $goods_row['hour'];
                $twitter_id = $goods_row['twitter_id'];
                $shop_id    = $goods_row['shop_id'];
                $goods_id   = $goods_row['goods_id'];
                $off_num    = $goods_row['off_num'];
                $off_price  = $goods_row['off_price'];
                if (strtotime($start_day) < strtotime($afterday)) {
                    continue;
                }
                if ($hour != 22) {
                    continue;
                }
                $check_sql = "select id from campaign_goods_info where twitter_id={$twitter_id} "
                . " and aid ={$afterday22} and audit_status=4 limit 1";
                // $check_row = $model->queryBrdshop($check_sql);
                $check_row = $sdb_brd_shop->createCommand($check_sql)->queryAll();
                if (count($check_row) == 1) {
                    continue;
                }
                $aid        = $afterday22;
                $insert_sql = "insert into campaign_goods_info (aid, shop_id, goods_id, twitter_id, campaign_price, campaign_off, audit_status, create_time) values ({$aid}, {$shop_id}, {$goods_id}, {$twitter_id},{$off_price} , {$off_num}, 4, now())";
                // $model->writeBrdshop($insert_sql);
                $db_brd_shop->createCommand($insert_sql)->execute();
            }
        }
    }


    /**
     * 排序惊喜秒杀活动
     */
    public function ActionSaveZhengdianSort()
    {
        $request       = Yii::app()->request;
        $twitterIdsStr = $request->getPost('tuan_id','');
        $eventId       = $request->getPost('event_id', 1065);

        if (!$eventId) {
            output(array('succ'=>0, 'msg'=>'活动id不存在'));
        }
        if (!$eventId) {
            output(array('succ'=>0, 'msg'=>'活动id不存在'));
        }
        $eventInfo = $this->event->getEventInfo($eventId);
        if (!$eventInfo) {
            output(array('succ'=>0, 'msg'=>'活动信息不存在'));
        }
        if ($eventInfo['status'] < 30 || $eventInfo['status'] >= 40) {
            output(array('succ'=>0, 'msg'=>'活动类型不相符'));
        }

        $twitterIdsArr = explode(",", $twitterIdsStr);
        $db_brd_shop   = Yii::app()->db_brd_shop;
        $rank = count($twitterIdsArr);
        // @FIXME 注意，是倒叙排序
        foreach ($twitterIdsArr as $k=>$v) {
            $grouponId = (int)$v;
            $updateSql = "update tuan_events_item_detail set rank={$rank} where groupon_id={$grouponId} and event_id={$eventId}";
            $update_result = $db_brd_shop->createCommand($updateSql)->execute();
            $rank--;
        }

        output(array('succ'=>1, 'msg'=>'排序成功'));
    }

    /**
     * 整点秒杀编辑
     */
    public function ActionEdit()
    {
        $eventId = 1065;

        $eventInfo = $this->event->getEventInfo($eventId);
        if (!$eventInfo) {
            throwMessage('活动不存在','error');
        }

        $detail = json_decode($eventInfo['detail'], true);

        if ($detail['zhengdian_info']) {
            $zhengdianInfo = $detail['zhengdian_info'];
        } else {
            $zhengdianInfo = array();
        }
        $eventInfo['detail'] = $detail;

        $nowDate = date("Y-m-d");
        $now     = time();
        $newZhengdianInfo = array();
        //p($zhengdianInfo);
        foreach ($zhengdianInfo as $k=>$v) {
            if (strtotime(date('Y-m-d', strtotime($v['stime']))) >= strtotime($nowDate)) {
                $newZhengdianInfo[] = $v;
            }
        }

        $this->assign('newZhengdianInfo', $newZhengdianInfo);
        $this->assign('eventInfo', $eventInfo);
        $this->render('suprise/edit.html');
    }

    public function ActionAnalyize() {
        $request = Yii::app()->request;
        $date = $request->getQuery('date', date('Y-m-d'));
        $result = $this->suprise->analyize($date);

        $this->render('suprise/analyize.html', array(
            'online'=>implode(', ', $result[0]),
            'campaign'=>implode(', ', $result[1]),
            'naocan'=>implode(', ', $result[2]),
            'data'=>$result,
            'date'=>$date,
            'online_diff'=>array_diff($result[0], $result[1]),
            'campaign_diff'=>array_diff($result[1], $result[0]),
            'naocan_diff'=>array_unique(array_intersect($result[0], $result[2]))
            )
        );
    }

    /**
     * 检测时间段是否有排期过的商品
     */
    public function ActionCheckTimeHasGoods()
    {
        $request = Yii::app()->request;
        $startTime = $request->getPost('start_time', '');
        $endTime   = $request->getPost('end_time', '');

        if (!preg_match("/^[0-9]{4}-[0-9]{2}-[0-9]{2} [0-9]{2}:[0-9]{2}$/", $startTime)) {
            output(array('succ'=>0, 'msg'=>'请填写正确的开始时间'));
        }
        if (!preg_match("/^[0-9]{4}-[0-9]{2}-[0-9]{2} [0-9]{2}:[0-9]{2}$/", $endTime)) {
            output(array('succ'=>0, 'msg'=>'请填写正确的结束时间'));
        }

        $sql = "select count(t2.id) from tuan_events_item_detail t1 join shop_groupon_info t2 on t1.groupon_id=t2.id where t2.audit_status=50 and t2.start_time=unix_timestamp('{$startTime}')  and  t1.event_id=1065";
        $sdb_brd_shop = Yii::app()->sdb_brd_shop;
        $count = $sdb_brd_shop->createCommand($sql)->queryScalar();

        if ($count) {
            output(array('succ'=>0, 'msg'=>"{$startTime}时间段还有{$count}个商品已被排期，请取消排期后再来操作"));
        } else {
            output(array('succ'=>1, 'msg'=>'success', 'sql'=>$sql));
        }
    }

    /**
     * 保存整点抢时间
     */
    public function ActionSaveZhengdianTime()
    {
        $request = Yii::app()->request;
        $eventId        = $request->getPost('event_id', 0);
        $q8Ruler        = $request->getPost('q8_ruler', '');
        $ctimeArr       = $request->getPost('ctime', array());
        $etimeArr       = $request->getPost('etime', array());
        $stimeNoteArr   = $request->getPost('stime_note', array());

        $eventId   = 1065;
        $eventInfo = $this->event->getEventInfo($eventId);
        if (!$eventInfo) {
            throwMessage('活动不存在','error');
        }

        $count = count($ctimeArr);
        if(count($ctimeArr) != $count || count($etimeArr) != $count || count($stimeNoteArr) != $count) {
            throwMessage('非法操作','error');
        }

        $detail = json_decode($eventInfo['detail'], true);

        $newZhengdianTimeArr = array();
        foreach ($ctimeArr as $k=>$v) {
            $newZhengdianTimeArr[$k]['stime'] = $ctimeArr[$k];
            $newZhengdianTimeArr[$k]['etime'] = $etimeArr[$k];
            $newZhengdianTimeArr[$k]['stime_note'] = $stimeNoteArr[$k];
        }

        // 这里记录上次操作的整点抢防止误操作
        //$old_key = 'old_zhengdian_info_'.$this->user->id."_".time();
        //$detail[$old_key] = $detail['zhengdian_info'];
        $oldZhengdinInfo = $detail['zhengdian_info'];


        $detail['zhengdian_info'] = $newZhengdianTimeArr;
        $detail['q8_ruler']       = $q8Ruler;

        $detail = json_encode($detail);
        $db_brd_shop = Yii::app()->db_brd_shop;
        $db_brd_shop->createCommand()->update(
                'tuan_events_list',
                array("detail" => $detail),
                'event_id=:event_id',
                array(':event_id'=>$eventId)
        );

        // 添加log
        $logFiter = array(
            'user'          => $this->user->name,
            'name'          => '更新挣点抢时间',
            'content'       => $detail,
            'is_succ'       => 1,
            'param'         => array('event_id'=>$eventId, 'old_zhengdian_info'=>$oldZhengdinInfo),
            'resource_name' => 'update_event_zhengdian_info',
            'resource_id'   => $eventId,
        );
        $this->tuanLog->addLog($logFiter);

        throwMessage('修改成功','success','/suprise/edit');
    }

}
?>