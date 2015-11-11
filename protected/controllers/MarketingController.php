<?php

class MarketingController extends Controller
{

    public $code = 0;
    public $msg = '';
    public $data = '';

    public function end()
    {
        echo json_encode(array('code'=>$this->code, 'msg'=>$this->msg, 'data'=>$this->data));
        Yii::app()->end();
    }
    /**
     * 清仓
     */
    public function  ActionQingcang()
    {
        $this->request  = Yii::app()->request;
        $date = $this->request->getQuery('date', date("Y-m-d"));

        if (!$date) {
            $date = date("Y-m-d");
        }

        $list = $this->everyDay->getQingcangList($date);
        $this->assign('date', $date);
        $this->assign('shop_list', $list);
        $this->render('everyDay/qingcang.html');
    }

    /**
     * 保存清仓信息
     */
    public function ActionSaveQingcang()
    {
        $this->request  = Yii::app()->request;
        $date = $this->request->getPost('date','');
        $id   = $this->request->getPost('id',0);
        $shopImage   = $this->request->getPost('shop_image',0);
        $shopName    = $this->request->getPost('shop_name','');
        $shopUrl     = $this->request->getPost('shop_url','');
        $rank        = (int)$this->request->getPost('rank',0);

        if (!$shopImage) {
            output(array('succ'=>0, 'msg'=>'请上传图片'));
        }
        if (!$shopUrl) {
            output(array('succ'=>0, 'msg'=>'请填写地址'));
        }
        if (!preg_match("/^[0-9]{4}-[0-9]{2}-[0-9]{2}$/", $date)) {
            output(array('succ'=>0, 'msg'=>'时间格式错误'));
        }
        if (stripos($shopUrl, "http://") !== 0) {
            output(array('succ'=>0, 'msg'=>'地址格式错误'.$shopUrl));
        }

        $filter = array(
            'shop_image' => $shopImage,
            'shop_name'  => $shopName,
            'rank'       => $rank,
            'shop_url'    => $shopUrl
        );

        $db_groupon = Yii::app()->db_groupon;
        if (!$id) {
            $filter['date'] = $date;
            $filter['create_time'] = date("Y-m-d H:i:s");
            $filter['op_user']     = $this->user->name;
            $db_groupon->createCommand()->insert('t_everyday_qingcang', $filter);
            $id  = $db_groupon->getLastInsertID();
        } else {
            $db_groupon->createCommand()->update(
                    't_everyday_qingcang',
                    $filter,
                    'id=:id',
                    array(':id'=>$id)
            );
        }

        output(array('succ'=>1, 'msg'=>'success', 'id'=>$id));
    }

    public function ActionMiaosha()
    {
        $request = Yii::app()->getRequest();

        $to = $request->getQuery('to', date('Y-m-d'));
        $params = array();
        $params['area'] = $this->suprise->getArea($to);
        $params['data'] = array();
        foreach ($params['area'] as $key => &$value) {
            $special = $this->suprise->getMarketingList($value['stime']);
            $value['fengqiang'] = '';
            $value['jingxuan'] = '';
            foreach ($special as $k => $v) {
                // 12 for 疯抢
                if ($v['category'] == '12') {
                    $value['fengqiang'][] = $v['tid'];
                } elseif ($v['category'] == '13') {
                    // 13 for 精选
                    $value['jingxuan'][] = $v['tid'];
                }
            }
        }

        $params['to'] = $to;
        // echo json_encode($params);

        // 是否同步展示配置
        $syncShowInfo = TuanRuleManager::getRuleInfo('marketing_sync_show_miaosha');
        $this->assign('syncShowInfo', $syncShowInfo);

        $this->render('everyDay/miaosha.html', $params);
    }


    /**
     * 保存秒杀信息
     */
    public function ActionSaveMiaosha()
    {
        $request = Yii::app()->getRequest();

        $msg = '';

        $stime = $request->getPost('stime', '');
        $etime = $request->getPost('etime', '');

        $fengqiang = (int)$request->getPost('fengqiang', '');
        $jingxuan_1 = (int)$request->getPost('jingxuan_1', '');
        $jingxuan_2 = (int)$request->getPost('jingxuan_2', '');

        if (empty($stime) || empty($etime) || empty($fengqiang) || empty($jingxuan_1) || empty($jingxuan_2)) {
            echo json_encode(array('code'=>1, 'msg'=>'参数错误', 'data'=>''));
            exit();
        }

        $list = $this->suprise->getPreviewList($stime);

        // 营销商品列表
        $special = $this->suprise->getMarketingList($stime);
        $specialList = $this->common->array_column($special, 'id');

        $listMap = array();
        $this->convertMap($list, 'tid', $listMap);
        $twitterList = array_keys($listMap);

        // $this->data = $twitterList;

        if (!in_array($fengqiang, $twitterList) || !in_array($jingxuan_1, $twitterList) || !in_array($jingxuan_2, $twitterList)) {
            $this->msg = '选择的商品不在当前时段排期列表中';
            $this->code = 2;
            $this->end();
        }

        if ($fengqiang == $jingxuan_1 || $fengqiang == $jingxuan_2) {
            $this->msg = '选择的商品不能重复';
            $this->code = 3;
            $this->end();
        }

        $connection = Yii::app()->db_brd_shop;

        $sql1 = 'update tuan_events_item_detail set category = 1 where groupon_id in (' . implode(', ', $specialList). ')';

        $sql2 = 'update tuan_events_item_detail set category = 12 where groupon_id = ' . $listMap[$fengqiang]['id'];

        $sql3 = "update tuan_events_item_detail set category = 13 where groupon_id in ({$listMap[$jingxuan_1]['id']}, {$listMap[$jingxuan_2]['id']})";  // = ' . $listMap[$jingxuan_1]['id'];

        $sql_arr = array($sql1,$sql2,$sql3);

        $transaction=$connection->beginTransaction();

        try
        {
            if(!empty($specialList))
            {
                $connection->createCommand($sql1)->execute();
            }
            $connection->createCommand($sql2)->execute();
            $connection->createCommand($sql3)->execute();
            $transaction->commit();
            $this->data = array($sql1, $sql2, $sql3);
            $this->msg = '成功';
            $this->end();


        }
        catch(Exception $e) // 如果有一条查询失败，则会抛出异常
        {
            $transaction->rollBack();
            $this->msg = print_r($e);
            $this->code = 5;
            $this->data = $sql_arr;
            $this->end();
        }
    }

    public function ActionClearMiaosha()
    {
        $request = Yii::app()->getRequest();

        $msg = '';

        $stime = $request->getPost('stime', '');
        $etime = $request->getPost('etime', '');

        // 营销商品列表
        $special = $this->suprise->getMarketingList($stime);
        $specialList = $this->common->array_column($special, 'id');
        if (!$specialList) {
            output(array('code'=>1,'msg'=>'当前时间没有商品~~'));
        }

        $connection = Yii::app()->db_brd_shop;
        $sql1 = 'update tuan_events_item_detail set category = 1 where groupon_id in (' . implode(', ', $specialList). ')';

        $connection->createCommand($sql1)->execute();

        output(array('code'=>0,'msg'=>'ok', 'sql'=>$sql1));
    }

    public function convertMap($input, $needle, &$output) {
        foreach ($input as $key => $value) {
            $output[$value[$needle]] = $value;
        }
    }



    /**
     * 团购
     */
    public function ActionTuangou()
    {
        $this->request  = Yii::app()->request;
        $date = $this->request->getQuery('date', date("Y-m-d"));

        if (!$date) {
            $date = date("Y-m-d");
        }

        $list = $this->marketing->getTuangouList($date);
        $this->assign('date', $date);
        $this->assign('tuan_list', $list);
        $this->assign('tuangou_event_id', MarketingManager::$tuangou_event_id);
        $this->render('everyDay/tuangou.html');
    }

    /**
     * 团购排序保存
     */
    public function ActionSaveTuangouRank()
    {
        $request       = Yii::app()->request;
        $twitterIdsStr = $request->getPost('tuan_id','');
        $eventId       = MarketingManager::$tuangou_event_id;

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
     * 编辑配置信息
     */
    public function ActionEditMarketingConfig()
    {
        $request       = Yii::app()->request;
        $key           = $request->getQuery('key','');
        if (!array_key_exists($key, MarketingManager::$marketing_key_map)) {
            throwMessage('参数错误');
        }

        $keyInfo = TuanRuleManager::getRuleInfo($key);
        if (!$keyInfo) {
            throwMessage('没有这个配置哦');
        }

        $keyInfo['config'] = json_decode($keyInfo['value'], true);
        if (!$keyInfo['config']) {
            $keyInfo['config'] = array();
        }

        $this->assign('keyInfo', $keyInfo);
        $this->render('everyDay/editConfig.html');
    }


    /**
     * 保存配置信息
     */
    public function ActionSaveMarketingConfig()
    {
        $useTimeBegin = microtime(true);

        $request       = Yii::app()->request;
        $key           = $request->getPost('key','');
        if (!array_key_exists($key, MarketingManager::$marketing_key_map)) {
            throwMessage('参数错误');
        }
        $title = $request->getPost('title','');
        $desc = $request->getPost('desc','');
        $link = $request->getPost('link','');

        if (!$title) {
            throwMessage('标题不可为空');
        }
        if (!$desc) {
            throwMessage('说明不可为空');
        }

        $ruleInfo  = TuanRuleManager::getRuleInfo($key);
        if (!$ruleInfo) {
            throwMessage('规则不存在');
        }

        $config = array(
            'title'=>$title,
            'desc' => $desc,
        );
        $updateArr = array(
                'value'             => json_encode($config),
                'last_op_user'      => $this->user->username,
                'last_update_time'  => date("Y-m-d H:i:s")
        );

        $db_groupon = Yii::app()->db_groupon;
        $r = $db_groupon->createCommand()->update(
                't_groupon_global_config',
                $updateArr,
                '`key`=:key',
                array(':key'=>$key)
        );
        if (!$r) {
            throwMessage('更新失败');
        }

        $useTimeEnd = microtime(true);
        $useTime    = $useTimeEnd - $useTimeBegin;
        $logFiter = array(
                'user'          => $this->user->name,
                'name'          => '更改报名规则',
                'content'       => array('old_content' => $ruleInfo, 'new_content' => $updateArr),
                'param'         => array('key'=>$key),
                'resource_name' => 't_groupon_global_config',
                'resource_id'   => $ruleInfo['id'],
                'is_succ'       => 1,
                'use_time'      => number_format($useTime, 5)
        );
        // 增加日志
        $this->tuanLog->addLog($logFiter);

        throwMessage('修改成功', 'success');
    }

    /**
     * 更新是否同步显示
     */
    public function ActionChangeMiaoshaSyncShow()
    {
        $useTimeBegin = microtime(true);

        $request       = Yii::app()->request;
        $key           = 'marketing_sync_show_miaosha';
        $value         = (int)$request->getPost('value','');

        $ruleInfo  = TuanRuleManager::getRuleInfo($key);
        if (!$ruleInfo) {
            output(array('succ'=>0, 'msg'=>'配置不存在'));
        }

        $updateArr = array(
                'value'             => $value,
                'last_op_user'      => $this->user->username,
                'last_update_time'  => date("Y-m-d H:i:s")
        );


        $db_groupon = Yii::app()->db_groupon;
        $r = $db_groupon->createCommand()->update(
                't_groupon_global_config',
                $updateArr,
                '`key`=:key',
                array(':key'=>$key)
        );
        if (!$r) {
            output(array('succ'=>0, 'msg'=>'更新失败'));
        }

        $useTimeEnd = microtime(true);
        $useTime    = $useTimeEnd - $useTimeBegin;
        $logFiter = array(
                'user'          => $this->user->name,
                'name'          => '更改报名规则',
                'content'       => array('old_content' => $ruleInfo, 'new_content' => $updateArr),
                'param'         => array('key'=>$key),
                'resource_name' => 't_groupon_global_config',
                'resource_id'   => $ruleInfo['id'],
                'is_succ'       => 1,
                'use_time'      => number_format($useTime, 5)
        );
        // 增加日志
        $this->tuanLog->addLog($logFiter);

        output(array('succ'=>1, 'msg'=>'success'));
    }
}
?>
