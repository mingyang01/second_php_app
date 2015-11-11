<?php
/**
 * 活动商品
 */

class EventGoodsController extends Controller
{

    public function ActionScheduleGoods()
    {
        $request = Yii::app()->request;
        $eventId = $request->getQuery('event_id', '');
        $params  = $this->eventGoods->searchParamMaker($request);

        if (!$eventId) {
            throwMessage("请传活动id", 'error');
        }
        $eventInfo = $this->event->getEventInfo($eventId);
        if (!$eventInfo) {
            throwMessage("活动不存在", 'error');
        }

        // 获取状态
        $auditStatus = $request->getQuery('audit_status', '');
        if (!array_key_exists($auditStatus, EventGoodsManager::$auditStatusInfo)) {
            $auditStatus = 40;
        }
        $params['audit_status'] = $auditStatus;
        $params['event']        = $eventId;

        $list = $this->eventGoods->getTwitterList($params);

        $params['count'] = count($list);

        $params['schedule_start_time'] = $request->getQuery('schedule_start_time', '');
        $params['schedule_end_time'] = $request->getQuery('schedule_end_time', '');
        if (!preg_match("/^[0-9]{4}-[0-9]{2}-[0-9]{2} [0-9]{2}:[0-9]{2}:[0-9]{2}$/", $params['schedule_start_time'])) {
            $params['schedule_start_time'] = '';
        }
        if (!preg_match("/^[0-9]{4}-[0-9]{2}-[0-9]{2} [0-9]{2}:[0-9]{2}:[0-9]{2}$/", $params['schedule_end_time'])) {
            $params['schedule_end_time'] = '';
        }


        // 非等待审核状态不允许用户操作
        $params['needTool'] = 0;
        if ($auditStatus == 40) {
            $params['needTool'] = 1;
        }
        if ($auditStatus == 50) {
            $params['needCancelTool'] = 1;
        }

        $params['data'] = array();
        if ($params['count'] != 0) {
            $params['data'] = $this->audit->getTwitterDetail($list);
        }

        if (isset($_GET['excel'])) {
            // 导出数据
            $results = array();
            if ($params['count'] != 0) {
                $results = $this->audit->getTwitterDetail($list);
            }
            $this->export->exportGrouponListHtml($results, '复审');
        } else {
            // 拒绝原因
            $params['fail_reason'] = $this->audit->getAuditReason(51);
            $params['eventInfo'] = $eventInfo;
            $params['areaInfo']  = $this->event->getEventAreas($eventInfo['event_id']);
            //p($params['areaInfo']);
            $this->render('eventGoods/scheduleGoods.html', $params);
        }
    }


    /**
     * 活动添加商品
     */
    public function ActionSaveEventGoods()
    {
        $twitter_ids    = Yii::app()->request->getPost('twitter_id', 0);
        $area_id        = (int)Yii::app()->request->getPost('area_id', 0);
        $area_sub       = (int)Yii::app()->request->getPost('area_sub', 0);
        $event_id       = (int)Yii::app()->request->getPost('event_id', 0);

        if (!$twitter_ids) {
            output(array('succ'=>0, 'msg'=>'请传入twitter_id'));
        }
        if (!$event_id) {
            output(array('succ'=>0, 'msg'=>'请传入event_id'));
        }

        $twitter_id_arr = explode(",", $twitter_ids);

        $db_brd_shop  = Yii::app()->db_brd_shop;
        $sdb_brd_shop = Yii::app()->sdb_brd_shop;

        $succ_num  = 0;
        $err_num   = 0;
        $succ_info = array();
        $rank_num  = 0;
        $err_result = '';
        foreach ($twitter_id_arr as $k=>$v) {
            // $eventGrouponInfo = $this->event->getEventGrouponInfo((int)$v,$event_id);

            $v = (int)$v;
            $sql = "select t1.* from tuan_events_item_detail t1 join shop_groupon_info t2 on t1.groupon_id=t2.id where t1.twitter_id={$v} and t1.event_id={$event_id} and t2.audit_status=50 and t2.goods_type=2 order by t1.id desc limit 1";
            $eventGrouponInfo  = $sdb_brd_shop->createCommand($sql)->queryRow();

            if (!$eventGrouponInfo) {
                $err_num++;
                $err_result .= "twitter_id:".$v." msg:商品不存在";
                continue;
            }
            // 如果已经被排期过则不能添加
            if ($eventGrouponInfo['category'] > 0) {
                $err_num++;
                $err_result .= "twitter_id:".$v." msg:已经被活动排期过";
                continue;
            }

            // 判断grouponinfo
            $grouponInfo = $this->event->getGrouponInfo($eventGrouponInfo['groupon_id']);
            if (!$grouponInfo) {
                $err_num++;
                $err_result .= "twitter_id:".$v." msg:团购商品不存在";
                continue;
            }
            if ($grouponInfo['audit_status'] != 50) {
                $err_num++;
                $err_result .= "twitter_id:".$v." msg:商品不是排期成功状态";
                continue;
            }
            if ($grouponInfo['goods_type'] != 2) {
                $err_num++;
                $err_result .= "twitter_id:".$v." msg:商品不是活动商品";
                continue;
            }

            $eventInfo = $this->event->getEventInfo($event_id);
            if (!$eventInfo) {
                $err_num++;
                $err_result .= "twitter_id:".$v." msg:活动不存在";
                continue;
            }

            // 秒杀活动的category为1 status 为30 其他的category为2
            if ($eventInfo['status'] >= 30 && $eventInfo['status'] < 40) {
                $update_sql = "update tuan_events_item_detail set category='1', status='30', area='{$area_id}', area_sub='{$area_sub}', rank='{$rank_num}' where id={$eventGrouponInfo['id']}";
            } else {
                $update_sql = "update tuan_events_item_detail set category='2',area='{$area_id}', area_sub='{$area_sub}', rank='{$rank_num}' where id={$eventGrouponInfo['id']}";
            }

            $update_result = $db_brd_shop->createCommand($update_sql)->execute();

            if ($update_result) {
                $rank_num++;
                $succ_num++;
                // 获取grouponInfo
                $grouponInfo = $this->event->getGrouponInfo($eventGrouponInfo['groupon_id']);
                if ($grouponInfo) {
                    $grouponInfo['goods_image']    = Yii::app()->image->getWebsiteImageUrl(Yii::app()->image->generateThumbUrl($grouponInfo['goods_image'], 's6', '163', '200'));
                    $grouponInfo['event_goods_id'] = $eventGrouponInfo['id'];
                    $grouponInfo['origin_price']   = $grouponInfo['off_num'] + $grouponInfo['off_price'];
                    $succ_info[] = $grouponInfo;
                }
            } else {
                $err_num++;
                $err_result .= "twitter_id:".$v." msg:内部错误";
            }
        }

        output(array('succ'=>1, 'msg'=>'success', 'data'=>$succ_info, 'succ_num'=>$succ_num, 'err_num'=>$err_num, 'err_result'=>$err_result));
    }


    /**
     * 活动商品批量排期
     */
    public function ActionEventGoodsSchedule()
    {
        $request = Yii::app()->getRequest();

        if (!$request->getIsAjaxRequest()) {
            output(array('succ'=>0, 'msg'=>'少年，请不要非法操作'));
        }

        $tuanIds            = $request->getPost('tuan_id', '');
        $eventId            = (int)$request->getPost('event_id', 0);
        $startTime          = $request->getPost('start_time', '');
        $endTime            = $request->getPost('end_time', '');
        $repertory          = $request->getPost('repertory', '');
        $useOriginRepertory = $request->getPost('use_origin_repertory', '');
        $needRepertory      = false;

        if (!$eventId) {
            output(array('succ'=>0, 'msg'=>'请传入活动id'));
        }
        if (!$tuanIds) {
            output(array('succ'=>0, 'msg'=>'请选择要排期的商品'));
        }
        if (!preg_match("/^[0-9]{4}-[0-9]{2}-[0-9]{2} [0-9]{2}:[0-9]{2}:[0-9]{2}$/", $startTime)) {
            output(array('succ'=>0, 'msg'=>'请填写正确的开始时间'));
        }
        if (!preg_match("/^[0-9]{4}-[0-9]{2}-[0-9]{2} [0-9]{2}:[0-9]{2}:[0-9]{2}$/", $endTime)) {
            output(array('succ'=>0, 'msg'=>'请填写正确的结束时间'));
        }
        if (strtotime($endTime) < strtotime($startTime)) {
            output(array('succ'=>0, 'msg'=>'结束时间不可以小于开始时间'));
        }

        $eventInfo = $this->event->getEventInfo($eventId);
        if (!$eventInfo) {
            output(array('succ'=>0, 'msg'=>'活动信息不存在'));
        }
        if ($eventInfo['status'] >= 30 && $eventInfo['status'] < 40) {
            $needRepertory = true;
        }

        // @FIXME 新增判断如果是首页每日特惠活动部判断时间
        if ($eventId != MarketingManager::$tuangou_event_id) {
            if($eventInfo['start_time']!=0||$eventInfo['end_time']!=0){
                if (strtotime($startTime) < $eventInfo['start_time'] || strtotime($endTime) > $eventInfo['end_time']) {
                    output(array('succ'=>0, 'msg'=>'排期时间必须在活动时间范围之内，活动时间：'.date("Y-m-d H:i:s",$eventInfo['start_time'])." -- ".date("Y-m-d H:i:s",$eventInfo['end_time'])));
                }
            }
        }

        if ($needRepertory) {
            if (!$repertory && !$useOriginRepertory) {
                output(array('succ'=>0, 'msg'=>'请填写库存信息'));
            }
        }

        $tuanIdsArr = explode(",", $tuanIds);
        if (!$tuanIdsArr) {
            output(array('succ'=>0, 'msg'=>'请选择要排期的商品~'));
        }

        $succNum  = 0;
        $errNum   = 0;
        $succInfo = array();
        $scheduleResultArr = array();
        $errResult = "";

        $db_brd_shop  = Yii::app()->db_brd_shop;
        foreach ($tuanIdsArr as $k=>$v) {
            $tuanInfo = $this->goods->getGrouponInfo($v);

            if (!$tuanInfo) {
                $errNum++;
                $errResult .= "gid：{$v}, msg：团购信息不存在\r\n";
                continue;
            }

            $postRepertory = 0;
            if ($needRepertory) {
                if ($useOriginRepertory) {
                    $tuanTwitterInfo = $this->goods->getTuanTwitterInfo($tuanInfo['twitter_id']);
                    if (!$tuanTwitterInfo || !$tuanTwitterInfo['repertory']) {
                        $errResult .= "gid：{$v}, tid：{$tuanInfo['twitter_id']}, msg：团购信息没有库存了\r\n";
                    }
                    $postRepertory = $tuanTwitterInfo['repertory'];
                } else {
                    $postRepertory = $repertory;
                }
                // 排期
                $setResult = $this->schedule->setSchedule($tuanInfo['id'], strtotime($startTime), strtotime($endTime), array(), array(), $postRepertory);
            } else {
                // 排期
                $setResult = $this->schedule->setSchedule($tuanInfo['id'], strtotime($startTime), strtotime($endTime));
            }

            $scheduleResultArr[] = $setResult;

            if ($setResult['succ'] != 1) {
                $errResult .= "gid：".$tuanInfo['id'].", tid：".$tuanInfo['twitter_id'].", msg：".$setResult['msg']."\r\n";
                $errNum++;
                continue;
            } else {
                $succNum++;
            }

            // 如果设置了库存则更新活动团购信息库存
            if ($postRepertory) {
                $eventTuanInfo = $this->event->getEventGrouponInfo($tuanInfo['twitter_id'], $eventId);
                if (!$eventTuanInfo) {
                    continue;
                }
                $plusDetail = json_decode($eventTuanInfo['plus_detail'], true);
                if (!$plusDetail) {
                    $plusDetail = array();
                }
                $plusDetail['total_limit'] = $postRepertory;
                $plusDetailJson = json_encode($plusDetail);
                // 更新event表
                $updateSql = "update tuan_events_item_detail set plus_detail='{$plusDetailJson}' where id={$eventTuanInfo['id']}";
                $updateResault = $db_brd_shop->createCommand($updateSql)->execute();
            }
        }

        output(array('succ'=>1, 'msg'=>'success', 'succ_num'=>$succNum, 'err_num'=>$errNum, 'schedule'=>$scheduleResultArr, 'err_result'=>$errResult));
    }

    /**
     * 取消排期
     */
    public function ActionEventGoodsCancelSchedule()
    {
        $request = Yii::app()->getRequest();

        if (!$request->getIsAjaxRequest()) {
            output(array('succ'=>0, 'msg'=>'少年，请不要非法操作'));
        }

        $tuanIds            = $request->getPost('tuan_id', '');
        $comments           = $request->getPost('comments', '单个取消排期');
        if (!$tuanIds) {
            output(array('succ'=>0, 'msg'=>'请选择要排期的商品'));
        }
        $tuanIdsArr = explode(",", $tuanIds);
        if (!$tuanIdsArr) {
            output(array('succ'=>0, 'msg'=>'请选择要排期的商品~'));
        }

        $succNum  = 0;
        $errNum   = 0;
        $succInfo = array();
        $scheduleResultArr = array();
        $errResult = "";

        $db_brd_shop  = Yii::app()->db_brd_shop;
        foreach ($tuanIdsArr as $k=>$v) {
            $tuanInfo = $this->goods->getGrouponInfo($v);

            if (!$tuanInfo) {
                $errNum++;
                $errResult .= "gid：{$v}, msg：团购信息不存在\r\n";
                continue;
            }

            // 取消排期
            $setResult = $this->schedule->cancelSchedule($tuanInfo['id'], array('comments'=>$comments));

            $scheduleResultArr[] = $setResult;

            if ($setResult['succ'] != 1) {
                $errResult .= "gid：".$tuanInfo['id'].", tid：".$tuanInfo['twitter_id'].", msg：".$setResult['msg']."\r\n";
                $errNum++;
                continue;
            } else {
                $succNum++;
            }
        }

        output(array('succ'=>1, 'msg'=>'success', 'succ_num'=>$succNum, 'err_num'=>$errNum, 'schedule'=>$scheduleResultArr, 'err_result'=>$errResult));
    }
}
?>