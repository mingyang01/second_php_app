<?php
/**
 * 排期
 */
class QscheduleController extends Controller {


private $page_size = 2;

	/**
	 * 清仓复审页面
	 */
    public function ActionList() {

    	$request = Yii::app()->getRequest();
    	$search = new SearchManager();
    	$params = $search->searchParamMaker($request);
    	$params['from'] = date('Y-m-d', strtotime("-365 day"));
    	if (!$params['event']) {
    		$params['event'] = QcommManager::EVENT_ID;
    	}
    	$params['realStatus'] = $request->getQuery('realStatus', QcheckManager::STATUS_SECOND_PASS);
    	$params['type'] = 1;

    	if (!isset(QcheckManager::$status[$params['realStatus']])) {
    		echo "未知的审核状态";
    		Yii::app()->end();
    	}

    	$params['twitter'] = $request->getQuery('twitter', '');
    	$list = $search->getShopIdList($params);

    	$params['total'] = count($list);
    	$params['count'] = $this->page_size;
    	$page = $request->getQuery('page', 1);
    	$params['page'] = $page;
    	// 数据源方法
    	if (isset($_GET['data'])) {
    		$offset = $page * $this->page_size;
    		// 确保还有数据
    		$result = array();
    		$result['code'] = 1;
    		$result['data'] = array();
    		if ($params['total'] > $offset) {

    			$list = array_slice($list, $offset, $this->page_size);
    			$params['shop_info'] = QcheckManager::getShopGoods($list, $params, true);
    			$markup = $this->fetch('qingcang/schedule/schedule-content-detail.html', $params);
    			$result['data']['html'] = $markup;
    			$result['data']['total'] = $params['total'];
    		} else {
    			$result['data']['total'] = 0;
    		}
    		echo json_encode($result);
    		// echo $markup;
    	} elseif (isset($_GET['excel'])) {

    		// 导出数据
        	$export = array();
        	if (count($list) > 0) {
        		$export = QcheckManager::getShopGoods($list, $params, true);
        		$new_export = array();
        		foreach( $export as $eval) {
        			foreach ($eval['good_info']['data'] as $val) {

        				$e_key = $eval['shop_id'].'-'.$val['tid'];
        				$tmp = array();
        				foreach (QcommManager::$columns as $k=>$v) {
        					if (isset($eval[$v])) {
        						$tmp[$v] = $eval[$v];
        					}
        				}

        				$new_export[$e_key] = array_merge($tmp, $val);
        			}
        		}
        	}

    		$exp = new QcommManager();
    		$exp->exportGrouponListHtml($new_export, QcommManager::$titles, QcommManager::$columns, '清仓待排期');

    	} else {

    		$list = array_slice($list, 0, $this->page_size);

    		$params['shop_info'] = QcheckManager::getShopGoods($list, $params, true);
    		// 审核进度限制
    		$params['limit'] = 1;
    		// 通过原因
    		$params['pass_reason'] = QcommManager::getCheckReason(QcheckManager::STATUS_SCHEDULE_PASS);
    		// 拒绝原因
    		$params['fail_reason'] = QcommManager::getCheckReason(QcheckManager::STATUS_SCHEDULE_REFUS);
    		$this->render('qingcang/schedule/schedule.html', $params);
    	}
    }

    /**
     * 商品排期
     */
    public function actionschedule() {

    	$request = Yii::app()->getRequest();

    	$shop_id = $request->getPost('shop_id', '');
    	$startTime  = $request->getPost('start_time', '');
    	$endTime = $request->getPost('end_time', '');
    	$title = $request->getPost('title', '');
    	$event_id = $request->getPost('event', QcommManager::EVENT_ID);

    	if (!preg_match("/^[0-9]{4}-[0-9]{2}-[0-9]{2} [0-9]{2}:[0-9]{2}:[0-9]{2}$/", $startTime)) {
    		Json::fail('少年，请填写正确的开始时间');
    	}

    	if (!preg_match("/^[0-9]{4}-[0-9]{2}-[0-9]{2} [0-9]{2}:[0-9]{2}:[0-9]{2}$/", $endTime)) {
    		Json::fail('少年，请填写正确的结束时间');
    	}

    	//判断店铺下是不是有正在进行的清仓活动
    	$has_schedule = QensureScheduleManager::isHadScheduleShop($shop_id, $event_id);
    	if ($has_schedule) {
    		foreach ($has_schedule as $hs) {
    			$errResault .="店铺".$shop_id ."在". $hs['start_time'] ."到". $hs['end_time'] ."存在线上清仓活动";
    		}
    		Json::succ('succ', 1, (array('succ_num'=>0, 'err_num'=>'全部店铺', 'err_resault'=>$errResault)));
    	}

    	//插入到线上表
    	$params = array(
    			'shop'		=>	$shop_id,
    			'event'		=>	$event_id,
    			'realStatus'=>	QcheckManager::STATUS_SECOND_PASS,
    	);

    	$params = array_merge($params, QshopManager::defaultParams());

    	$search =  new SearchManager();
    	$infos = $search->getTwitterList($params);

    	$sdb_brd_shop = Yii::app()->sdb_brd_shop;
    	$sql = "select * from shop_groupon_info where id in (".implode(",", $infos).") and audit_status=". QcheckManager::STATUS_SECOND_PASS;
    	$list =$sdb_brd_shop->createCommand($sql)->queryAll();

    	$succNum = $errNum = 0;
    	$errResault = "";
    	$succ_twitids = $succ_gids = $err_gids = $err_msg = array();

    	foreach ($list as $k=>$v) {
    		if ($v['audit_status'] == 40) {
    			// 排期
    			$scheduleResault = $this->schedule->setSchedule($v['id'], strtotime($startTime), strtotime($endTime));
    			if ($scheduleResault['succ'] == 1 && !$has_schedule) {
    				$succ_gids[] = $v['id'];
    				$succ_twitids[] = $v['twitter_id'];
    				$succNum++;
    			} else {
    				$errResault .= "twitter_id:".$v['twitter_id']."  tuan_id:".$v['id']."  失败提示: ".$scheduleResault['msg']."\r\n";
    				$err_gids[] = $v['id'];
    				$err_msg[$v['id']] = $scheduleResault['msg'];
    				$errNum++;
    			}
    		}
    	}

		if ($succ_gids) {
			try {
				//修改20、40状态的商品为10
				$gids = $search->getShopGids($event_id, $shop_id);
				$comment = "店铺已排期，驳回已有审核商品";
				QcheckManager::checkShopGoods($gids, QcheckManager::STATUS_SCHEDULE_REFUS, $event_id, $comment);
			} catch (Exception $e) {
				Json::fail("操作失败". $e->getMessage());
			}
			$ret = QsheduleManager::addInfo($event_id, $shop_id, $startTime, $endTime, $title);
			if (!$ret) {
				QsheduleManager::addInfo($event_id, $shop_id, $startTime, $endTime, $title);
			}
		}
		if ($err_gids) {
			QcheckManager::checkShopGoods($err_gids, QcheckManager::STATUS_SCHEDULE_REFUS, $event_id, $errResault);
		}
    	Json::succ('succ', 1, (array('succ_num'=>$succNum, 'err_num'=>$errNum, 'err_resault'=>$errResault)));
    }

    /**
     * 检测是否可以排期
     */
    public function actionCanSchedule() {

    	$request = Yii::app()->getRequest();

    	$shop_id = $request->getPost('shop_id', '');
    	$startTime  = $request->getPost('start_time', '');
    	$endTime = $request->getPost('end_time', '');
    	$title = $request->getPost('title', '');
    	$event_id = $request->getPost('event', QcommManager::EVENT_ID);

    	//判断店铺下是不是有正在进行的清仓活动
    	$has_schedule = QensureScheduleManager::isHadScheduleShop($shop_id, $event_id);
    	if ($has_schedule) {
    		foreach ($has_schedule as $hs) {
    			$errResault .="店铺".$shop_id ."在". $hs['start_time'] ."到". $hs['end_time'] ."存在线上清仓活动";
    		}
    		Json::fail($errResault);
    	}

    	//插入到线上表
    	$params = array(
    			'shop'		=>	$shop_id,
    			'event'		=>	$event_id,
    			'realStatus'=>	QcheckManager::STATUS_SECOND_PASS,
    	);

    	$params = array_merge($params, QshopManager::defaultParams());

    	$search =  new SearchManager();
    	$infos = $search->getTwitterList($params);

    	$sdb_brd_shop = Yii::app()->sdb_brd_shop;
    	$sql = "select * from shop_groupon_info where id in (".implode(",", $infos).") and audit_status=". QcheckManager::STATUS_SECOND_PASS;
    	$list =$sdb_brd_shop->createCommand($sql)->queryAll();

    	$succNum = $errNum = 0;
    	$errResault = "";
    	$succ_twitids = $succ_gids = $err_gids = $err_tid = $err_msg = array();
    	$has_schedule = QensureScheduleManager::isHadScheduleShop($shop_id, $event_id);
    	foreach ($list as $k=>$v) {
    		if ($v['audit_status'] == QcheckManager::STATUS_SECOND_PASS) {

    			// 排期
    			$scheduleResault = $this->schedule->checkSchedule($v['id'], strtotime($startTime), strtotime($endTime));
    			if ($scheduleResault['succ'] == 1) {
    				$succ_gids[] = $v['id'];
    				$succ_twitids[] = $v['twitter_id'];
    				$succNum++;
    			} else {
    				$errResault .= "twitter_id:".$v['twitter_id']."  tuan_id:".$v['id']."  失败提示: ".$scheduleResault['msg']."\r\n";
    				$err_tid[] = $v['twitter_id'];
    				$errNum++;
    			}
    		}
    	}

    	Json::succ('成功', 1, array('err_num'=>$errNum, 'succ_num'=>$succNum, 'msg'=>$errResault));
    }


    /**
     * 根据店铺驳回选中的商品
     */
    public function actioncheckByShop() {

    	$shop_id = $_POST['shop_id'];
    	$event_id = $_POST['event'] ? $_POST['event'] : QcommManager::EVENT_ID;
    	$gids = $_POST['ids'];

    	$comment = trim($_POST['comment']);
    	$checkResult = $_POST['checkResult'];

    	if (!isset(QcheckManager::$status[$checkResult])) {
    		Json::fail('请检查状态');
    	}

    	if (!$comment) {
    		Json::fail('请填写审核原因');
    	}

    	//插入到线上表
    	$params = array(
    			'shop'		=>	$shop_id,
    			'event'		=>	$event_id,
    			'realStatus'=>	QcheckManager::STATUS_SECOND_PASS,
    	);

    	$params = array_merge($params, QshopManager::defaultParams());

    	$search =  new SearchManager();
    	$infos = $search->getTwitterList($params);

    	try {
    		QcheckManager::checkShopGoods($infos, $checkResult, $event_id, $comment);
    	} catch (Exception $e) {
    		Json::fail("操作失败". $e->getMessage());
    	}

    	Json::succ('操作成功');
    }

}
