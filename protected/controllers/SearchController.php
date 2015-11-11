<?php

class SearchController extends Controller {
    public function actionIndex() {

        $request = Yii::app()->getRequest();
        $params = $this->search->searchParamMaker($request);
        // 默认不限进度
        $params['step'] = $request->getQuery('step', 1);

        if (!isset(SearchManager::$statusMap[$params['step'].$params['status']])) {
            // 不对状态进行限制
            $realStatus = 0;
        } else {
            // trick for status
            $realStatus = SearchManager::$statusMap[$params['step'].$params['status']];
        }



        $params['realStatus'] = $realStatus;

        $list = $this->search->getTwitterList($params);
        // echo "<pre>";
        // var_dump($list);
        // exit();
        $params['count'] = count($list);
        $params['needTool'] = 0;
        // 数据源方法
        if (isset($_GET['data'])) {
            $page = $request->getQuery('page', 1);
            $offset = $page * 40;
            // 确保还有数据
            $result = array();
            $result['code'] = 1;
            $result['data'] = array();
            if ($params['count'] > $offset) {
                $list = array_slice($list, $offset, 40);
                $result['data']['total'] = count($list);
                $data = $this->audit->getTwitterDetail($list);
                // $result['data']['list'] = $data;

                $markup = $this->fetch('audit/first-content-detail.html', array('data'=>$data));
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
            $this->export->exportGrouponListHtml($results, '搜索工具－导出');
        } else {

            $list = array_slice($list, 0, 40);
            $params['data'] = array();
            if ($params['count'] != 0) {
                $params['data'] = $this->audit->getTwitterDetail($list);
            }

            // 不对审核进度限制
            $params['limit'] = 0;
            // var_dump($params['data']); exit();
            $this->render('search/index.html', $params);
        }
    }

    public function actionSuprise() {
        $sql = "select id from shop_groupon_info
        where start_time >= unix_timestamp('2015-05-10 00:00:00')
        and end_time < unix_timestamp('2015-05-11 00:00:00')
        and audit_status = 50 and goods_type = 2
        and id in (select groupon_id
            from tuan_events_item_detail where event_id = 1065);";
    }
}