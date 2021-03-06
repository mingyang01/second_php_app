<?php
/**
 * 排期操作
 * @author linglingqi@
 * @version 2015-07-28
 */
class QsecondController extends Controller {

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
    	$params['realStatus'] = $request->getQuery('realStatus', QcheckManager::STATUS_FIRST_PASS);
    	$params['type'] = 1;

    	if (!isset(QcheckManager::$status[$params['realStatus']])) {
    		echo "未知的审核状态";
    		Yii::app()->end();
    	}
    	$params['twitter'] = $request->getQuery('twitter','');
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
    			$markup = $this->fetch('qingcang/second/second-content-detail.html', $params);
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
    		$exp->exportGrouponListHtml($new_export, QcommManager::$titles, QcommManager::$columns, '清仓复审');
    	} else {

    		$list = array_slice($list, 0, $this->page_size);

    		$params['shop_info'] = QcheckManager::getShopGoods($list, $params, true);
    		// 审核进度限制
    		$params['limit'] = 1;
    		// 通过原因
    		$params['pass_reason'] = QcommManager::getCheckReason(QcheckManager::STATUS_SECOND_PASS);
    		// 拒绝原因
    		$params['fail_reason'] = QcommManager::getCheckReason(QcheckManager::STATUS_SECOND_REFUS);
    		$this->render('qingcang/second/second.html', $params);
    	}
    }

    public function actioncheckByShop() {

    	$user = yii::app()->user;
    	$uid = $user->id;
    	$name = $user->name;

    	$event =  $_POST['event'] ? $_POST['event'] : QcommManager::EVENT_ID;
    	$shop_id = $_POST['shop_id'];
    	$comment = trim($_POST['comment']);
    	$checkResult = $_POST['checkResult'];

    	if (!isset(QcheckManager::$status[$checkResult])) {
    		Json::fail('请检查状态');
    	}

    	if (!$comment) {
    		Json::fail('请填写审核原因');
    	}

    	$params = array(
    			'shop'		=>	$shop_id,
    			'event'		=>	$event,
    			'realStatus'=>	QcheckManager::STATUS_FIRST_PASS,
    	);

    	$params = array_merge($params, QshopManager::defaultParams());

    	$search =  new SearchManager();
    	$infos = $search->getTwitterList($params);

    	$values = '';
    	$gids = array();
    	$time = date('Y-m-d H:i:s');
    	foreach ($infos as $key => $val) {
    		$gid = $val;
    		$gids[] = $gid;
    		if ($values) {
    			$values .= ", ($gid, $checkResult, '$comment', '$time', $uid, '$name') ";
    		} else {
    			$values .= " ($gid, $checkResult, '$comment', '$time', $uid, '$name') ";
    		}
    	}

    	$connection = Yii::app()->db_brd_shop;
    	$sql1 = "update shop_groupon_info set audit_status={$checkResult} where id in ('". implode("','", $infos) ."') and audit_status=". QcheckManager::STATUS_FIRST_PASS;
    	$sql2 = "INSERT INTO shop_groupon_audit_comments (`gid`, `audit_status`, `audit_comments`, `audit_time`, `audit_user`, `audit_opname`) VALUES " . $values;

    	$transaction=$connection->beginTransaction();
    	try {

    		$connection->createCommand($sql1)->execute();
    		$connection->createCommand($sql2)->execute();

    		$transaction->commit();

    		Json::succ('操作成功');
    	} catch(Exception $e) {// 如果有一条查询失败，则会抛出异常

    		$transaction->rollBack();
    		Json::fail("操作失败，". $e->getMessage());
    	}

    }

    public function actioncheckByGoods() {

    	$ids = $_POST['ids'];
    	$comment = trim($_POST['comment']);
    	$checkResult = $_POST['checkResult'];
    	if (!$comment) {
    		Json::fail('请填写审核原因');
    	}
    	$connection = Yii::app()->db_brd_shop;

    	$values = '';
    	$sql1 = "update shop_groupon_info set audit_status={$checkResult} where id in (" . implode(', ', $ids) . ')';

    	foreach ($ids as $key => $value) {
    		$values .= " ($value, $checkResult, '{$comment}', localtime(), {$this->user->id}, '{$this->user->name}') ";
    		if ($key != count($ids) - 1) {
    			$values .= ',';
    		}
    	}

    	$sql2 = "INSERT INTO shop_groupon_audit_comments (`gid`, `audit_status`, `audit_comments`, `audit_time`, `audit_user`, `audit_opname`) VALUES " . $values;

    	$transaction=$connection->beginTransaction();
    	try {

    		$connection->createCommand($sql1)->execute();
    		$connection->createCommand($sql2)->execute();

    		$transaction->commit();
    		Json::succ('操作成功');
    	} catch(Exception $e) {// 如果有一条查询失败，则会抛出异常

    		$transaction->rollBack();
    		Json::fail("操作失败，". $e->getMessage());
    	}
    }

}
