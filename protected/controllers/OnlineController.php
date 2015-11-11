<?php

class OnlineController extends Controller
{

    public function ActionIndex()
    {
        $request = Yii::app()->getRequest();
        // 获取搜索条件
        $params = $this->online->searchParamMaker($request);
        debug($params);

        // 获取状态
        $auditStatus = $request->getQuery('audit_status', '');
        if ($auditStatus != 40 && $auditStatus != 50) {
            $auditStatus = 40;
        }
        $params['audit_status'] = $auditStatus;
        // 推荐日期
        $date = $request->getQuery('date', date("Y-m-d"));
        $params['date'] = $date;

        $list = $this->online->getTwitterList($params);
        //$params['data'] = $this->online->getTwitterDetail($list);
        $params['count'] = count($list);
        //p($list);exit();
        // 非等待审核状态不允许用户操作
        $params['needTool'] = 0;
        if ($params['audit_status'] == 40) {
            $params['needTool'] = 1;
        }

        // 数据源方法
        if (isset($_GET['data'])) {
            $page = $request->getQuery('page', 1);
            $offset = $page * 30;
            // 确保还有数据
            $result = array();
            $result['code'] = 1;
            $result['data'] = array();
            if ($params['count'] > $offset) {
                $list = array_slice($list, $offset, 30);
                $result['data']['total'] = count($list);
                $data = $this->audit->getTwitterDetail($list);
                // $result['data']['list'] = $data;
                $params['data'] = $data;
                $markup = $this->fetch('online/online-content-detail.html', $params);
                $result['data']['html'] = $markup;

            } else {
                $result['data']['total'] = 0;
            }
            echo json_encode($result);
            // echo $markup;
        } elseif (isset($_GET['excel'])) {
            // 导出数据
            $results = array();
            if ($params['count'] != 0) {
                $results = $this->audit->getTwitterDetail($list);
            }
            $this->export->exportGrouponListHtml($results, '确认排期');
        } else {
            $twitterIds = $this->online->getTwitterIds($params['date']);
            $params['stat'] = array();
            if ($twitterIds) {
                $params['stat'] = $this->ActionOnliStat($this->audit->getTwitterDetail($twitterIds));
            }

            $list = array_slice($list, 0, 30);
            $params['data'] = array();
            if ($params['count'] != 0 && $list) {
                $params['data'] = $this->audit->getTwitterDetail($list);
            }

            // 审核进度限制
            $params['limit'] = 1;
            // 通过原因
            $params['pass_reason'] = $this->audit->getAuditReason(20);
            // 拒绝原因
            $params['fail_reason'] = $this->audit->getAuditReason(21);
            $this->render('online/index.html', $params);
        }
    }

    /**
     * 保存保存排期
     */
    public function ActionSaveData()
    {
        $request = Yii::app()->getRequest();

        if (!$request->getIsAjaxRequest()) {
            output(array('succ'=>0, 'msg'=>'少年，请不要非法操作'));
        }
        //output(array('succ'=>0, 'msg'=>'少年，请不要非法操作~'));

        $tuanIdsStr = $request->getPost('tuan_ids', '');
        $startTime = $request->getPost('start_time', '');
        $isshowTag = $request->getPost('isshow_tag', 0);

        if (!$tuanIdsStr) {
            output(array('succ'=>0, 'msg'=>'请选择加入排期的内容'));
        }
        if (!preg_match("/^[0-9]{4}-[0-9]{2}-[0-9]{2}$/", $startTime)) {
            output(array('succ'=>0, 'msg'=>'少年，请填写正确的运行时间'));
        }
        if ($isshowTag) {
            $updateArr = array('isshow_tag'=>1);
        } else {
            $updateArr = array('isshow_tag'=>0);
        }

        $tuanIds = explode(",", $tuanIdsStr);
        // 开始时间为每天10点，结束时间为大于开始时间1天
        $startTime = strtotime($startTime." 10:00:00");
        $endTime   = $startTime + (3600*24);

        $sdb_brd_shop = Yii::app()->sdb_brd_shop;
        $sql = "select * from shop_groupon_info where id in (".implode(",", $tuanIds).") and audit_status='40'";
        $tuanList =$sdb_brd_shop->createCommand($sql)->queryAll();

        $succNum = 0;
        $errNum  = 0;
        $errResault = "";
        foreach ($tuanList as $k=>$v) {
            if ($v['audit_status'] == 40) {
                // 排期
                $scheduleResault = $this->schedule->setSchedule($v['id'], $startTime, $endTime);
                if ($scheduleResault['succ'] == 1) {
                    $succNum++;
                } else {
                    $errResault .= "twitter_id:".$v['twitter_id']."  tuan_id:".$v['id']."  失败提示: ".$scheduleResault['msg']."\r\n";
                    $errNum++;
                }
            }
        }

        output(array('succ'=>1, 'msg'=>'success', 'succ_num'=>$succNum, 'err_num'=>$errNum, 'err_resault'=>$errResault));
    }


    /**
     * 保存保存单个排期
     */
    public function ActionSaveDataOne()
    {
        $request = Yii::app()->getRequest();

        if (!$request->getIsAjaxRequest()) {
            output(array('succ'=>0, 'msg'=>'少年，请不要非法操作'));
        }
        //output(array('succ'=>0, 'msg'=>'少年，请不要非法操作~'));

        $tuanId    = $request->getPost('tuan_id', '');
        $startTime = $request->getPost('start_time', '');

        if (!$tuanId) {
            output(array('succ'=>0, 'msg'=>'请选择加入排期的商品'));
        }
        if (!preg_match("/^[0-9]{4}-[0-9]{2}-[0-9]{2}$/", $startTime)) {
            output(array('succ'=>0, 'msg'=>'少年，请填写正确的运行时间'));
        }

        // 开始时间为每天10点，结束时间为大于开始时间1天
        $startTime = strtotime($startTime." 10:00:00");
        $endTime   = $startTime + (3600*24);

        $sdb_brd_shop = Yii::app()->sdb_brd_shop;
        $sql = "select * from shop_groupon_info where id='{$tuanId}'";
        $tuanInfo =$sdb_brd_shop->createCommand($sql)->queryAll();
        if (!$tuanInfo) {
            output(array('succ'=>0, 'msg'=>'团购报名信息不存在'));
        }

        $scheduleResault = $this->schedule->setSchedule($tuanId, $startTime, $endTime);
        if ($scheduleResault['succ'] == 1) {
            output(array('succ'=>1, 'msg'=>'success', $scheduleResault));
        } else {
            output($scheduleResault);
        }

    }

    /**
     * 退回推荐排期
     */
    public function ActionCancelData()
    {
        $request = Yii::app()->getRequest();

        if (!$request->getIsAjaxRequest()) {
            output(array('succ'=>0, 'msg'=>'少年，请不要非法操作'));
        }
        //output(array('succ'=>0, 'msg'=>'少年，请不要非法操作~'));

        $tuanId    = $request->getPost('tuan_id', '');

        if (!$tuanId) {
            output(array('succ'=>0, 'msg'=>'请选择加入排期的商品'));
        }

        $tuanIds = explode(",", $tuanId);
        if (!$tuanIds) {
            output(array('succ'=>0, 'msg'=>'请选择加入排期的商品'));
        }

        if (count($tuanIds) > 1) {
            $sdb_brd_shop = Yii::app()->sdb_brd_shop;
            $sql = "select * from shop_groupon_info where id in (".implode(",", $tuanIds).") and audit_status='40'";
            $tuanList =$sdb_brd_shop->createCommand($sql)->queryAll();

            $succNum = 0;
            $errNum  = 0;
            $db_brd_shop = Yii::app()->db_brd_shop;
            foreach ($tuanList as $k=>$v) {
                if ($v['audit_status'] == 40) {
                    // 排期
                    $sql        = "update shop_groupon_info set start_time='0' where id='{$v['id']}' and audit_status='40'";
                    $updateInfo = $db_brd_shop->createCommand($sql)->execute();
                    if ($updateInfo) {
                        $succNum++;
                    } else {
                        $errNum++;
                    }
                } else {
                    $errNum++;
                }
            }
            output(array('succ'=>1, 'msg'=>'success', 'succ_num'=>$succNum, 'err_num'=>$errNum));
        } else {
            $db_brd_shop = Yii::app()->db_brd_shop;
            $sql = "update shop_groupon_info set start_time='0' where id='{$tuanIds[0]}' and audit_status='40'";
            $updateInfo =$db_brd_shop->createCommand($sql)->execute();
            if ($updateInfo) {
                output(array('succ'=>1, 'msg'=>'success', 'succ_num'=>$succNum, 'err_num'=>$errNum));
            } else {
                output(array('succ'=>1, 'msg'=>'退回失败'));
            }
        }
    }

    /**
     * 推荐排期
     */
    public function ActionRecommendDate()
    {
        $request = Yii::app()->getRequest();
        // 获取搜索条件
        $params = $this->online->searchParamMaker($request);
        debug($params);

        // 获取状态
        $auditStatus = $request->getQuery('audit_status', '');

        if ($auditStatus != 40) {
            $auditStatus = 40;
        }
        $params['audit_status']  = $auditStatus;

        // 标识为推荐排期
        $params['recommendDate'] = 1;

        // 是否推荐排期   1-等待推荐  2-推荐成功
        $recommendStatus = $request->getQuery('recommend_status', '1');
        $params['recommend_status'] = $recommendStatus;

        debug($auditStatus);
        $list = $this->online->getTwitterList($params);

        $params['count'] = count($list);

        // 非等待审核状态不允许用户操作
        $params['needTool'] = 0;
        if ($auditStatus == 40) {
            $params['needTool'] = 1;
        }

        // 数据源方法
        if (isset($_GET['data'])) {
            $page = $request->getQuery('page', 1);
            $offset = $page * 30;
            // 确保还有数据
            $result = array();
            $result['code'] = 1;
            $result['data'] = array();
            if ($params['count'] > $offset) {
                $list = array_slice($list, $offset, 30);
                $result['data']['total'] = count($list);
                $data = $this->audit->getTwitterDetail($list);
                // $result['data']['list'] = $data;
                $params['data'] = $data;
                $markup = $this->fetch('online/online-content-detail.html', $params);
                $result['data']['html'] = $markup;

            } else {
                $result['data']['total'] = 0;
            }
            echo json_encode($result);
            // echo $markup;
        } elseif (isset($_GET['excel'])) {
            // 导出数据
            $results = array();
            if ($params['count'] != 0) {
                $results = $this->audit->getTwitterDetail($list);
            }
            $this->export->exportGrouponListHtml($results, '等待排期');
        } else {
            $list = array_slice($list, 0, 30);
            $params['data'] = array();
            if ($params['count'] != 0) {
                $params['data'] = $this->audit->getTwitterDetail($list);
            }
            // @FIXME 如果是活动类型并且不是秒杀则 推荐时间默认为活动开始时间
            if ($params['type'] == 1 && $params['event'] && $params['event'] != 1065) {
                $eventInfo = $this->event->getEventInfo($params['event']);
                if ($eventInfo) {
                    if (date("H:i", $eventInfo['start_time']) != "10:00") {
                        throwMessage('活动开始时间必须是10点才可以推荐排期哦~');
                    }
                    $this->assign('event_start_time', $eventInfo['start_time']);
                }
            }

            // 审核进度限制
            $params['limit'] = 1;
            // 通过原因
            $params['pass_reason'] = $this->audit->getAuditReason(50);
            // 拒绝原因
            $params['fail_reason'] = $this->audit->getAuditReason(51);
            $this->render('online/recommendDate.html', $params);
        }
    }

    /**
     * 保存推荐排期
     */
    public function ActionSaveRecommendData()
    {
        $request = Yii::app()->getRequest();

        if (!$request->getIsAjaxRequest()) {
            output(array('succ'=>0, 'msg'=>'少年，请不要非法操作'));
        }
        //output(array('succ'=>0, 'msg'=>'少年，请不要非法操作~'));

        $tuanIdsStr = $request->getPost('tuan_id', '');
        $startTime  = $request->getPost('start_time', '');

        if (!$tuanIdsStr) {
            output(array('succ'=>0, 'msg'=>'请选择加入排期的内容'));
        }
        if (!preg_match("/^[0-9]{4}-[0-9]{2}-[0-9]{2}$/", $startTime)) {
            output(array('succ'=>0, 'msg'=>'少年，请填写正确的运行时间'));
        }

        $tuanIds = explode(",", $tuanIdsStr);
        // 开始时间为每天10点，结束时间为大于开始时间3天
        $startTime = strtotime($startTime." 10:00:00");

        if (count($tuanIds) > 1) {
            $sdb_brd_shop = Yii::app()->sdb_brd_shop;
            $sql = "select * from shop_groupon_info where id in (".implode(",", $tuanIds).") and audit_status='40'";
            $tuanList =$sdb_brd_shop->createCommand($sql)->queryAll();

            $succNum = 0;
            $errNum  = 0;
            $db_brd_shop = Yii::app()->db_brd_shop;
            foreach ($tuanList as $k=>$v) {
                if ($v['audit_status'] == 40) {
                    // 排期
                    $sql        = "update shop_groupon_info set start_time='{$startTime}' where id='{$v['id']}' and audit_status='40'";
                    $updateInfo = $db_brd_shop->createCommand($sql)->execute();
                    if ($updateInfo) {
                        $succNum++;
                    } else {
                        $errNum++;
                    }
                }
            }
            output(array('succ'=>1, 'msg'=>'success', 'succ_num'=>$succNum, 'err_num'=>$errNum));
        } else {
            $sdb_brd_shop = Yii::app()->sdb_brd_shop;
            $sql = "select * from shop_groupon_info where id='{$tuanIds[0]}'";
            $tuanInfo =$sdb_brd_shop->createCommand($sql)->queryAll();
            if (!$tuanInfo) {
                output(array('succ'=>0, 'msg'=>'团购报名信息不存在'));
            }

            // 排期
            $db_brd_shop = Yii::app()->db_brd_shop;
            $sql         = "update shop_groupon_info set start_time='{$startTime}' where id='{$tuanIds[0]}' and audit_status='40'";
            $updateInfo  = $db_brd_shop->createCommand($sql)->execute();
            if ($updateInfo) {
                output(array('succ'=>1, 'msg'=>'success'));
            } else {
                output(array('succ'=>0, 'msg'=>'推荐排期失败'));
            }
        }
    }


    /**
     * 取消排期
     */
    public function ActionCancelSchedule()
    {
        $request = Yii::app()->getRequest();

        if (!$request->getIsAjaxRequest()) {
            output(array('succ'=>0, 'msg'=>'少年，请不要非法操作'));
        }
        //output(array('succ'=>0, 'msg'=>'少年，请不要非法操作~'));

        $tuanId    = $request->getPost('tuan_id', '');

        if (!$tuanId) {
            output(array('succ'=>0, 'msg'=>'请选择加入排期的商品'));
        }

        // 排期
        $scheduleResault = $this->schedule->cancelSchedule($tuanId);
        if ($scheduleResault['succ'] !== 1) {
            output(array('succ'=>1, 'msg'=>'success', $scheduleResault));
        } else {
            output($scheduleResault);
        }
    }

    /**
     * 确认排期页面统计信息
     */
    public function ActionOnliStat($data)
    {
        $totalData = count($data);
        $categoryInfo = OnlineManager::$categoryInfo;
        $priceStat = array(
                'range'   => array(
                    '--20' => array('from' => 0, 'to' => 20.0, 'rec_rate' => '5%'),
                    '20--40' => array('from' => 20.0, 'to' => 40, 'rec_rate' => '25%'),
                    '40--80' => array('from' => 40, 'to' => 80, 'rec_rate' => '45%'),
                    '80--120' => array('from' => 80, 'to' => 120, 'rec_rate' => '15%'),
                    '120--200' => array('from' => 120, 'to' => 200, 'rec_rate' => '10%'),
                ),
                'title'=> array('header'=>'价格', '11801'=>'女装', '11805'=>'女包', '11803'=>'女鞋', '11809'=>'家居', '11807'=>'配饰', '12313'=>'美妆', '12511'=>'男装', '12591'=>'童装', '12661'=>'食品', 'total_num'=>'总量', 'rate'=>'占比', 'rec_rate' => '建议占比',),
                'data' => array(
                    '--20'     => array('header'=>'--20', '11801' => '0', '11805' => '0', '11803' => '0', '11809' => '0','11807' => '0', '12313' => '0', '12511' => '0', '12591' => '0', '12661' => '0', 'total_num' => '0', 'rate' => '0','rec_rate' => '5%',),
                    '20--40'   => array('header'=>'20--40', '11801' => '0', '11805' => '0', '11803' => '0', '11809' => '0','11807' => '0', '12313' => '0', '12511' => '0', '12591' => '0', '12661' => '0', 'total_num' => '0', 'rate' => '0','rec_rate' => '25%'),
                    '40--80'   => array('header'=>'40--80', '11801' => '0', '11805' => '0', '11803' => '0', '11809' => '0','11807' => '0', '12313' => '0', '12511' => '0', '12591' => '0', '12661' => '0', 'total_num' => '0', 'rate' => '0','rec_rate' => '45%'),
                    '80--120'  => array('header'=>'80--120', '11801' => '0', '11805' => '0', '11803' => '0', '11809' => '0','11807' => '0', '12313' => '0', '12511' => '0', '12591' => '0', '12661' => '0', 'total_num' => '0', 'rate' => '0','rec_rate' => '15%'),
                    '120--200' => array('header'=>'120--200', '11801' => '0', '11805' => '0', '11803' => '0', '11809' => '0','11807' => '0', '12313' => '0', '12511' => '0', '12591' => '0', '12661' => '0', 'total_num' => '0', 'rate' => '0','rec_rate' => '10%'),
                ),
        );

        $popularityScoreStat = array(
            'title'=> array('header'=>'流行分', '11801'=>'女装', '11805'=>'女包', '11803'=>'女鞋', '11809'=>'家居', '11807'=>'配饰', '12313'=>'美妆', '12511'=>'男装', '12591'=>'童装', '12661'=>'食品', 'total_num'=>'总量', 'rate'  => '占比'),
            'data' => array(
                0 => array('header'=>'0', '11801' => '0', '11805' => '0', '11803' => '0', '11809' => '0','11807' => '0', '12313' => '0', '12511' => '0', '12591' => '0', '12661' => '0', 'total_num'=>'0', 'rate'  => '0'),
                1 => array('header'=>'1', '11801' => '0', '11805' => '0', '11803' => '0', '11809' => '0','11807' => '0', '12313' => '0', '12511' => '0', '12591' => '0', '12661' => '0', 'total_num'=>'0', 'rate'  => '0'),
                2 => array('header'=>'2', '11801' => '0', '11805' => '0', '11803' => '0', '11809' => '0','11807' => '0', '12313' => '0', '12511' => '0', '12591' => '0', '12661' => '0', 'total_num'=>'0', 'rate'  => '0'),
                3 => array('header'=>'3', '11801' => '0', '11805' => '0', '11803' => '0', '11809' => '0','11807' => '0', '12313' => '0', '12511' => '0', '12591' => '0', '12661' => '0', 'total_num'=>'0', 'rate'  => '0'),
                4 => array('header'=>'4', '11801' => '0', '11805' => '0', '11803' => '0', '11809' => '0','11807' => '0', '12313' => '0', '12511' => '0', '12591' => '0', '12661' => '0', 'total_num'=>'0', 'rate'  => '0'),
            ),
        );

        $categoryStat = array(
            'title'=> array('header'=>'类目', 'total_num'=>'数量', 'rec_num'=>'建议数量', 'rate'=>'占比', 'rec_rate'=>'建议占比', 'average_price'=>'均价', 'rec_average_price'=>'目标均价', 'platform_average_price'=>'平台价格', 'last_month_average_price'=>'上月均价', 'gifts_num'=>'精品数量', 'level_num'=>'KA商品数量', 'rec_num'=>'建议数量'),
             'data' => array(
                 '11801' => array('header'=>'女装', 'total_num'=>'0', 'rec_num'=>'0', 'rate'=>'0', 'rec_rate'=>'0', 'average_price'=>'0', 'rec_average_price'=>'0', 'platform_average_price'=>'0', 'last_month_average_price'=>'0', 'gifts_num'=>'0', 'level_num'=>'0', 'rec_num'=>'0'),
                 '11805' => array('header'=>'女包', 'total_num'=>'0', 'rec_num'=>'0', 'rate'=>'0', 'rec_rate'=>'0', 'average_price'=>'0', 'rec_average_price'=>'0', 'platform_average_price'=>'0', 'last_month_average_price'=>'0', 'gifts_num'=>'0', 'level_num'=>'0', 'rec_num'=>'0'),
                 '11803' => array('header'=>'女鞋', 'total_num'=>'0', 'rec_num'=>'0', 'rate'=>'0', 'rec_rate'=>'0', 'average_price'=>'0', 'rec_average_price'=>'0', 'platform_average_price'=>'0', 'last_month_average_price'=>'0', 'gifts_num'=>'0', 'level_num'=>'0', 'rec_num'=>'0'),
                 '11809' => array('header'=>'家居', 'total_num'=>'0', 'rec_num'=>'0', 'rate'=>'0', 'rec_rate'=>'0', 'average_price'=>'0', 'rec_average_price'=>'0', 'platform_average_price'=>'0', 'last_month_average_price'=>'0', 'gifts_num'=>'0', 'level_num'=>'0', 'rec_num'=>'0'),
                 '11807' => array('header'=>'配饰', 'total_num'=>'0', 'rec_num'=>'0', 'rate'=>'0', 'rec_rate'=>'0', 'average_price'=>'0', 'rec_average_price'=>'0', 'platform_average_price'=>'0', 'last_month_average_price'=>'0', 'gifts_num'=>'0', 'level_num'=>'0', 'rec_num'=>'0'),
                 '12313' => array('header'=>'美妆', 'total_num'=>'0', 'rec_num'=>'0', 'rate'=>'0', 'rec_rate'=>'0', 'average_price'=>'0', 'rec_average_price'=>'0', 'platform_average_price'=>'0', 'last_month_average_price'=>'0', 'gifts_num'=>'0', 'level_num'=>'0', 'rec_num'=>'0'),
                 '12511' => array('header'=>'男装', 'total_num'=>'0', 'rec_num'=>'0', 'rate'=>'0', 'rec_rate'=>'0', 'average_price'=>'0', 'rec_average_price'=>'0', 'platform_average_price'=>'0', 'last_month_average_price'=>'0', 'gifts_num'=>'0', 'level_num'=>'0', 'rec_num'=>'0'),
                 '12591' => array('header'=>'童装', 'total_num'=>'0', 'rec_num'=>'0', 'rate'=>'0', 'rec_rate'=>'0', 'average_price'=>'0', 'rec_average_price'=>'0', 'platform_average_price'=>'0', 'last_month_average_price'=>'0', 'gifts_num'=>'0', 'level_num'=>'0', 'rec_num'=>'0'),
                 '12661' => array('header'=>'食品', 'total_num'=>'0', 'rec_num'=>'0', 'rate'=>'0', 'rec_rate'=>'0', 'average_price'=>'0', 'rec_average_price'=>'0', 'platform_average_price'=>'0', 'last_month_average_price'=>'0', 'gifts_num'=>'0', 'level_num'=>'0', 'rec_num'=>'0'),
            ),
        );

        foreach ($data as $data_k=>$data_v) {
            // 价格
            if (array_key_exists($data_v['first_sort_id'], $categoryInfo)) {
                foreach ($priceStat['range'] as $k=>$v) {
                    if ($data_v['price'] > $v['from'] && $data_v['price'] <= $v['to']) {
                        $priceStat['data'][$k][$data_v['first_sort_id']];
                        $priceStat['data'][$k][$data_v['first_sort_id']] += 1;
                        $priceStat['data'][$k]['total_num'] += 1;
                    }
                }
            }
            // 热销分
            if (array_key_exists($data_v['popularity_score'], $popularityScoreStat['data'])) {
                $popularityScoreStat['data'][$data_v['popularity_score']][$data_v['first_sort_id']] += 1;
                $popularityScoreStat['data'][$data_v['popularity_score']]['total_num'] += 1;
            }
            // 推荐排期
            if (array_key_exists($data_v['first_sort_id'], $categoryStat['data'])) {
                $categoryStat['data'][$data_v['first_sort_id']]['total_num']   += 1;
                $categoryStat['data'][$data_v['first_sort_id']]['total_price'] += $data_v['price'];
                if ($data_v['isshow'] == 0) {
                    $categoryStat['data'][$data_v['first_sort_id']]['gifts_num'] += 1;
                }
                if ($data_v['level'] > 0) {
                    $categoryStat['data'][$data_v['first_sort_id']]['level_num'] += 1;
                }
            }
        }

        foreach ($priceStat['data'] as $k=>$v) {
            $priceStat['data'][$k]['rate'] = number_format(($v['total_num'] / $totalData) * 100, 2)."%";
        }

        foreach ($popularityScoreStat['data'] as $k=>$v) {
            $popularityScoreStat['data'][$k]['rate'] = number_format(($v['total_num'] / $totalData) * 100, 2)."%";
        }


        foreach ($categoryStat['data'] as $k=>$v) {
            // 占比
            $categoryStat['data'][$k]['rate'] = number_format(($v['total_num'] / $totalData) * 100, 2)."%";
            // 均价
            $categoryStat['data'][$k]['average_price'] = number_format($v['total_price'] / $v['total_num'], 2);
        }

        return array('price_stat'=>$priceStat, 'popularity_score_stat'=>$popularityScoreStat, 'category_stat'=>$categoryStat, 'total'=>$totalData);
    }



    /**
     * 确认排期页面统计信息
     */
    public function ActionRecommendDateStat($data)
    {
        $totalData = count($data);
        $categoryInfo = OnlineManager::$categoryInfo;
        $priceStat = array(
                'range'   => array(
                        '--20' => array('from' => 0, 'to' => 20.0, 'rec_rate' => '5%'),
                        '20--40' => array('from' => 20.0, 'to' => 40, 'rec_rate' => '25%'),
                        '40--80' => array('from' => 40, 'to' => 80, 'rec_rate' => '45%'),
                        '80--120' => array('from' => 80, 'to' => 120, 'rec_rate' => '15%'),
                        '120--200' => array('from' => 120, 'to' => 200, 'rec_rate' => '10%'),
                ),
                'title'=> array('header'=>'价格', '11801'=>'女装', '11805'=>'女包', '11803'=>'女鞋', '11809'=>'家居', '11807'=>'配饰', '12313'=>'美妆', '12511'=>'男装', '12591'=>'童装', '12661'=>'食品', 'total_num'=>'总量', 'rate'=>'占比', 'rec_rate' => '建议占比',),
                'data' => array(
                        '--20'     => array('header'=>'--20', '11801' => '0', '11805' => '0', '11803' => '0', '11809' => '0','11807' => '0', '12313' => '0', '12511' => '0', '12591' => '0', '12661' => '0', 'total_num' => '0', 'rate' => '0','rec_rate' => '5%',),
                        '20--40'   => array('header'=>'20--40', '11801' => '0', '11805' => '0', '11803' => '0', '11809' => '0','11807' => '0', '12313' => '0', '12511' => '0', '12591' => '0', '12661' => '0', 'total_num' => '0', 'rate' => '0','rec_rate' => '25%'),
                        '40--80'   => array('header'=>'40--80', '11801' => '0', '11805' => '0', '11803' => '0', '11809' => '0','11807' => '0', '12313' => '0', '12511' => '0', '12591' => '0', '12661' => '0', 'total_num' => '0', 'rate' => '0','rec_rate' => '45%'),
                        '80--120'  => array('header'=>'80--120', '11801' => '0', '11805' => '0', '11803' => '0', '11809' => '0','11807' => '0', '12313' => '0', '12511' => '0', '12591' => '0', '12661' => '0', 'total_num' => '0', 'rate' => '0','rec_rate' => '15%'),
                        '120--200' => array('header'=>'120--200', '11801' => '0', '11805' => '0', '11803' => '0', '11809' => '0','11807' => '0', '12313' => '0', '12511' => '0', '12591' => '0', '12661' => '0', 'total_num' => '0', 'rate' => '0','rec_rate' => '10%'),
                ),
        );

        $popularityScoreStat = array(
                'title'=> array('header'=>'流行分', '11801'=>'女装', '11805'=>'女包', '11803'=>'女鞋', '11809'=>'家居', '11807'=>'配饰', '12313'=>'美妆', '12511'=>'男装', '12591'=>'童装', '12661'=>'食品', 'total_num'=>'总量', 'rate'  => '占比'),
                'data' => array(
                        0 => array('header'=>'0', '11801' => '0', '11805' => '0', '11803' => '0', '11809' => '0','11807' => '0', '12313' => '0', '12511' => '0', '12591' => '0', '12661' => '0', 'total_num'=>'0', 'rate'  => '0'),
                        1 => array('header'=>'1', '11801' => '0', '11805' => '0', '11803' => '0', '11809' => '0','11807' => '0', '12313' => '0', '12511' => '0', '12591' => '0', '12661' => '0', 'total_num'=>'0', 'rate'  => '0'),
                        2 => array('header'=>'2', '11801' => '0', '11805' => '0', '11803' => '0', '11809' => '0','11807' => '0', '12313' => '0', '12511' => '0', '12591' => '0', '12661' => '0', 'total_num'=>'0', 'rate'  => '0'),
                        3 => array('header'=>'3', '11801' => '0', '11805' => '0', '11803' => '0', '11809' => '0','11807' => '0', '12313' => '0', '12511' => '0', '12591' => '0', '12661' => '0', 'total_num'=>'0', 'rate'  => '0'),
                        4 => array('header'=>'4', '11801' => '0', '11805' => '0', '11803' => '0', '11809' => '0','11807' => '0', '12313' => '0', '12511' => '0', '12591' => '0', '12661' => '0', 'total_num'=>'0', 'rate'  => '0'),
                ),
        );

        $categoryStat = array(
                'title'=> array('header'=>'类目', 'total_num'=>'数量', 'rec_num'=>'建议数量', 'rate'=>'占比', 'rec_rate'=>'建议占比', 'average_price'=>'均价', 'rec_average_price'=>'目标均价', 'platform_average_price'=>'平台价格', 'last_month_average_price'=>'上月均价', 'gifts_num'=>'精品数量', 'level_num'=>'KA商品数量', 'rec_num'=>'建议数量'),
                'data' => array(
                        '11801' => array('header'=>'女装', 'total_num'=>'0', 'rec_num'=>'0', 'rate'=>'0', 'rec_rate'=>'0', 'average_price'=>'0', 'rec_average_price'=>'0', 'platform_average_price'=>'0', 'last_month_average_price'=>'0', 'gifts_num'=>'0', 'level_num'=>'0', 'rec_num'=>'0'),
                        '11805' => array('header'=>'女包', 'total_num'=>'0', 'rec_num'=>'0', 'rate'=>'0', 'rec_rate'=>'0', 'average_price'=>'0', 'rec_average_price'=>'0', 'platform_average_price'=>'0', 'last_month_average_price'=>'0', 'gifts_num'=>'0', 'level_num'=>'0', 'rec_num'=>'0'),
                        '11803' => array('header'=>'女鞋', 'total_num'=>'0', 'rec_num'=>'0', 'rate'=>'0', 'rec_rate'=>'0', 'average_price'=>'0', 'rec_average_price'=>'0', 'platform_average_price'=>'0', 'last_month_average_price'=>'0', 'gifts_num'=>'0', 'level_num'=>'0', 'rec_num'=>'0'),
                        '11809' => array('header'=>'家居', 'total_num'=>'0', 'rec_num'=>'0', 'rate'=>'0', 'rec_rate'=>'0', 'average_price'=>'0', 'rec_average_price'=>'0', 'platform_average_price'=>'0', 'last_month_average_price'=>'0', 'gifts_num'=>'0', 'level_num'=>'0', 'rec_num'=>'0'),
                        '11807' => array('header'=>'配饰', 'total_num'=>'0', 'rec_num'=>'0', 'rate'=>'0', 'rec_rate'=>'0', 'average_price'=>'0', 'rec_average_price'=>'0', 'platform_average_price'=>'0', 'last_month_average_price'=>'0', 'gifts_num'=>'0', 'level_num'=>'0', 'rec_num'=>'0'),
                        '12313' => array('header'=>'美妆', 'total_num'=>'0', 'rec_num'=>'0', 'rate'=>'0', 'rec_rate'=>'0', 'average_price'=>'0', 'rec_average_price'=>'0', 'platform_average_price'=>'0', 'last_month_average_price'=>'0', 'gifts_num'=>'0', 'level_num'=>'0', 'rec_num'=>'0'),
                        '12511' => array('header'=>'男装', 'total_num'=>'0', 'rec_num'=>'0', 'rate'=>'0', 'rec_rate'=>'0', 'average_price'=>'0', 'rec_average_price'=>'0', 'platform_average_price'=>'0', 'last_month_average_price'=>'0', 'gifts_num'=>'0', 'level_num'=>'0', 'rec_num'=>'0'),
                        '12591' => array('header'=>'童装', 'total_num'=>'0', 'rec_num'=>'0', 'rate'=>'0', 'rec_rate'=>'0', 'average_price'=>'0', 'rec_average_price'=>'0', 'platform_average_price'=>'0', 'last_month_average_price'=>'0', 'gifts_num'=>'0', 'level_num'=>'0', 'rec_num'=>'0'),
                        '12661' => array('header'=>'食品', 'total_num'=>'0', 'rec_num'=>'0', 'rate'=>'0', 'rec_rate'=>'0', 'average_price'=>'0', 'rec_average_price'=>'0', 'platform_average_price'=>'0', 'last_month_average_price'=>'0', 'gifts_num'=>'0', 'level_num'=>'0', 'rec_num'=>'0'),
                ),
        );

        foreach ($data as $data_k=>$data_v) {
            // 价格
            if (array_key_exists($data_v['first_sort_id'], $categoryInfo)) {
                foreach ($priceStat['range'] as $k=>$v) {
                    if ($data_v['price'] > $v['from'] && $data_v['price'] <= $v['to']) {
                        $priceStat['data'][$k][$data_v['first_sort_id']];
                        $priceStat['data'][$k][$data_v['first_sort_id']] += 1;
                        $priceStat['data'][$k]['total_num'] += 1;
                    }
                }
            }
            // 热销分
            if (array_key_exists($data_v['popularity_score'], $popularityScoreStat['data'])) {
                $popularityScoreStat['data'][$data_v['popularity_score']][$data_v['first_sort_id']] += 1;
                $popularityScoreStat['data'][$data_v['popularity_score']]['total_num'] += 1;
            }
            // 推荐排期
            if (array_key_exists($data_v['first_sort_id'], $categoryStat['data'])) {
                $categoryStat['data'][$data_v['first_sort_id']]['total_num']   += 1;
                $categoryStat['data'][$data_v['first_sort_id']]['total_price'] += $data_v['price'];
                if ($data_v['isshow'] == 0) {
                    $categoryStat['data'][$data_v['first_sort_id']]['gifts_num'] += 1;
                }
                if ($data_v['level'] > 0) {
                    $categoryStat['data'][$data_v['first_sort_id']]['level_num'] += 1;
                }
            }
        }

        foreach ($priceStat['data'] as $k=>$v) {
            $priceStat['data'][$k]['rate'] = number_format(($v['total_num'] / $totalData) * 100, 2)."%";
        }

        foreach ($popularityScoreStat['data'] as $k=>$v) {
            $popularityScoreStat['data'][$k]['rate'] = number_format(($v['total_num'] / $totalData) * 100, 2)."%";
        }


        foreach ($categoryStat['data'] as $k=>$v) {
            // 占比
            $categoryStat['data'][$k]['rate'] = number_format(($v['total_num'] / $totalData) * 100, 2)."%";
            // 均价
            $categoryStat['data'][$k]['average_price'] = number_format($v['total_price'] / $v['total_num'], 2);
        }

        return array('price_stat'=>$priceStat, 'popularity_score_stat'=>$popularityScoreStat, 'category_stat'=>$categoryStat, 'total'=>$totalData);
    }


    /**
     * 保存商品标签
     */
    public function ActionSaveTag()
    {
        $useTimeBegin = microtime(true);

        $request = Yii::app()->getRequest();

        if (!$request->getIsAjaxRequest()) {
            output(array('succ'=>0, 'msg'=>'少年，请不要非法操作'));
        }
        //output(array('succ'=>0, 'msg'=>'少年，请不要非法操作~'));

        $tuanIdsStr = $request->getPost('tuan_ids', '');
        $tagType  = $request->getPost('tag_type', '');

        if (!$tuanIdsStr) {
            output(array('succ'=>0, 'msg'=>'请选择加入排期的内容'));
        }
        if (!array_key_exists($tagType, OnlineManager::$tuangouTagMap)) {
            output(array('succ'=>0, 'msg'=>'请选择正确的标签'));
        }

        $tuanIds = explode(",", $tuanIdsStr);

        // 排期
        $db_brd_shop = Yii::app()->db_brd_shop;
        $sql         = "update shop_groupon_info set op_type='{$tagType}' where id in (".$tuanIdsStr.") and audit_status='50'";

        $updateInfo  = $db_brd_shop->createCommand($sql)->execute();

        $useTimeEnd = microtime(true);
        $useTime    = $useTimeEnd - $useTimeBegin;
        $logFiter = array(
                'groupon_id' => 0,
                'user'       => $this->user->name,
                'name'       => '普通团购商品打标',
                'content'    => $sql,
                'param'      => $tuanIdsStr,
                'resource_name' => 'setTuangouTag',
                'is_succ'       => 1,
        );
        if ($updateInfo) {
            $logFiter['is_succ']  = 1;
        }
        $logFiter['use_time'] =  number_format($useTime, 5);

        // 增加日志
        $this->tuanLog->addLog($logFiter);

        output(array('succ'=>1, 'msg'=>'操作成功'));
    }
}
?>