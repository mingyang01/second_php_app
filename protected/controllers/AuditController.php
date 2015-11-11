<?php
/**
 * 审核
 */
class AuditController extends Controller {

    public $defaultAction = 'first';

    public function ActionFirst() {
        $request = Yii::app()->getRequest();
        $params = $this->search->searchParamMaker($request);

        if (!isset(SearchManager::$statusMap[$params['step'].$params['status']])) {
            echo "未知的审核状态";
            Yii::app()->end();
        }

        // trick for status
        $realStatus = SearchManager::$statusMap[$params['step'].$params['status']];

        $params['realStatus'] = $realStatus;
        debug($realStatus);
        $list = $this->search->getTwitterList($params);

        $params['count'] = count($list);

        // 非等待审核状态不允许用户操作
        $params['needTool'] = 0;
        if ($realStatus == 10) {
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
                $markup = $this->fetch('audit/first-content-detail.html', $params);
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
            $this->export->exportGrouponListHtml($results, '初审');
        } else {
            $list = array_slice($list, 0, 30);
            $params['data'] = array();
            if ($params['count'] != 0) {
                $params['data'] = $this->audit->getTwitterDetail($list);
            }

            // 新增判断，如果是清仓和会员阶梯价初审跳转到复审
            $channel = $request->getQuery('channel', 0);
            if ($channel && ($channel == 3 || $channel == 6)) {
                $checkResult = 20;
            } else {
                $checkResult = 30;
            }
            $this->assign('checkResult', $checkResult);

            // 审核进度限制
            $params['limit'] = 1;
            // 通过原因
            $params['pass_reason'] = $this->audit->getAuditReason(20);
            // 拒绝原因
            $params['fail_reason'] = $this->audit->getAuditReason(21);
            $this->render('audit/first.html', $params);
        }
    }

    public function ActionSecond() {
        $request = Yii::app()->getRequest();
        $params = $this->search->searchParamMaker($request);

        if (!isset(SearchManager::$statusMap[$params['step'].$params['status']])) {
            echo "未知的审核状态";
            Yii::app()->end();
        }
        $params['step'] = 3;
        $params['status'] = $request->getQuery('status', 0);
        // trick for status
        $realStatus = SearchManager::$statusMap[$params['step'].$params['status']];

        $params['realStatus'] = $realStatus;
        // 非等待审核状态不允许用户操作
        $params['needTool'] = 0;
        if ($realStatus == 20) {
            $params['needTool'] = 1;
        }
        $list = $this->search->getTwitterList($params);
        // echo "<pre>";
        // var_dump($list);
        $params['count'] = count($list);

        $params['data'] = array();
        if ($params['count'] != 0) {
            $params['data'] = $this->audit->getTwitterDetail($list);
        }
        // 审核进度限制
        $params['limit'] = 1;

        if (isset($_GET['excel'])) {
            // 导出数据
            $results = array();
            if ($params['count'] != 0) {
                $results = $this->audit->getTwitterDetail($list);
            }
            $this->export->exportGrouponListHtml($results, '复审');
        } else {
            // 新增判断，如果是清仓和会员阶梯价初审跳转到复审
            $channel = $request->getQuery('channel', 0);
            if ($channel && ($channel == 6)) {
                $checkResult = 40;
            } else {
                $checkResult = 30;
            }
            $this->assign('checkResult', $checkResult);

            // 通过原因
            $params['pass_reason'] = $this->audit->getAuditReason(30);
            // 拒绝原因
            $params['fail_reason'] = $this->audit->getAuditReason(31);
            $this->render('audit/second.html', $params);
        }
    }

    public function ActionSample() {
        $request = Yii::app()->getRequest();
        $params = $this->search->searchParamMaker($request);
        $params['step'] = 4;
        $params['status'] = $request->getQuery('status', 0);
        if (!isset(SearchManager::$statusMap[$params['step'].$params['status']])) {
            echo "未知的审核状态";
            Yii::app()->end();
        }

        // trick for status
        $realStatus = SearchManager::$statusMap[$params['step'].$params['status']];

        $params['realStatus'] = $realStatus;
        debug($realStatus);
        $list = $this->search->getTwitterList($params);

        $params['count'] = count($list);

        // 非等待审核状态不允许用户操作
        $params['needTool'] = 0;
        if ($realStatus == 30) {
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
                $markup = $this->fetch('audit/first-content-detail.html', $params);
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
            $this->export->exportGrouponListHtml($results, '样品审核');
        } else {
            $list = array_slice($list, 0, 30);
            $params['data'] = array();
            if ($params['count'] != 0) {
                $params['data'] = $this->audit->getTwitterDetail($list);
            }

            // 审核进度限制
            $params['limit'] = 1;
            // 通过原因
            $params['pass_reason'] = $this->audit->getAuditReason(40);
            // 拒绝原因
            $params['fail_reason'] = $this->audit->getAuditReason(41);
            $this->render('audit/sample.html', $params);
        }
    }

    public function ActionEndCatagory() {
        $request = Yii::app()->getRequest();
        $cid = $request->getQuery('cid', 0);
        if (!is_numeric($cid) || $cid == 0) {
            echo json_encode(array('code'=>1, 'msg'=>'param error'));
        }
        echo json_encode(array('code'=>0, 'data'=>$this->audit->endCatagory($cid)));
    }

    public function ActionStatistic() {
        $request = Yii::app()->getRequest();
        $from = $request->getQuery('from', date('Y-m-01'));
        $to = $request->getQuery('to', date('Y-m-d'));

        if (isset($_GET['data'])) {
            $result = $this->audit->statistic($from, $to);
            $results = array();
            $results['total'] = count($result);
            $results['rows'] = array_values($result);
            echo json_encode($results);
        } else {
            $params['from'] = $from;
            $params['to'] = $to;
            $this->render('audit/statistic.html', $params);
        }
    }

    public function ActionExportAuditLog() {
        $request = Yii::app()->getRequest();
        $date = $request->getQuery('date', date('Y-m-d'));
        $step = $request->getQuery('step', 2);

        if ($step == 2) {
            $name = '初审';
            $steps = array(20, 21);
        } else if($step == 3) {
            $name = '复审';
            $steps = array(30, 31);
        } elseif ($step ==4) {
            $name = '样审';
            $steps = array(40, 41);
        }



        if (isset($_GET['excel'])) {
            $results = $this->auditLog->exportAuditLog($date, $steps);
            // exportHtml($titles, $columns, $rows, $filename='')
            $titles = array('宝贝', '店铺', '一级类目', '二级类目', '三级类目', '审核结果'
                , '审核人', '原价', '折扣价', '审核时间', '备注');

            $columns = array('twitter', 'shop', 'first', 'second', 'third',
                'result', 'audit_opname', 'origin', 'price', 'audit_time', 'audit_comments');
            $this->common->exportHtml($titles, $columns, $results, $date . "-$name.xls");
        } else {
            // 审核统计
            $statistics = $this->auditLog->AuditStatistic($date);
            $this->render("audit/auditLogExport.html", array('name'=>$name,
                'step'=>$step, 'date'=>$date, 'statistics'=>$statistics));
        }
    }

    public function ActionAuditHistory($gid) {
        $db = Yii::app()->sdb_brd_shop;
        CActiveRecord::$db = $db;
        $models = AuditLog::model()->findAll(array(
            'condition'=>'gid=:gid',
            'params'=>array(':gid'=>$gid),
            'order'=>'audit_time',
        ));

        $result = array();
        $array = array();
        foreach($models as $model){
            $status = CheckTipsManager::$tipsTypeEnum[$model->audit_status];
            $array['status'] = $status;
            $array['comments'] = $model->audit_comments;
            $array['time'] = $model->audit_time;
            $array['user'] = $model->audit_user;

            $result[] = $array;
        }
        echo $this->fetch('audit/auditHistory.html', array('data'=>$result));
    }

    public function ActionSaveAudit() {
        $ids = $_POST['ids'];
        $comment = trim($_POST['comment']);
        $shops = $_POST['shops'];
        $checkResult = $_POST['checkResult'];
        if (!$comment) {
            output(json_encode(array('code'=>0, 'data'=>'请填写审核原因')));
        }
        $connection = Yii::app()->db_brd_shop;

        $values = '';
        $sql1 = "update shop_groupon_info set audit_status={$checkResult} where id in ("
                . implode(', ', $ids) . ')';



        foreach ($ids as $key => $value) {
            $values .= " ($value, $checkResult, '{$comment}', localtime(), {$this->user->id}, '{$this->user->name}') ";
            if ($key != count($ids) - 1) {
                $values .= ',';
            }
        }

        // echo json_encode(array('code'=>0, $sql1, $values));

        $sql2 = "INSERT INTO shop_groupon_audit_comments (`gid`, `audit_status`, `audit_comments`, `audit_time`, `audit_user`, `audit_opname`) VALUES " . $values;

        // echo json_encode(array('sql'=>$sql1));exit();
        $transaction=$connection->beginTransaction();
        try
        {
            $connection->createCommand($sql1)->execute();
            $connection->createCommand($sql2)->execute();

            $transaction->commit();

            echo json_encode(array('code'=>1, 'data'=>$ids));
        }
        catch(Exception $e) // 如果有一条查询失败，则会抛出异常
        {
            echo json_encode(array('code'=>0, 'data'=>$e));
            $transaction->rollBack();
        }
    }
}
