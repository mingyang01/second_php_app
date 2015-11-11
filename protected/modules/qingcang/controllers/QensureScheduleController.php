<?php
/**
 * 排期确认
 */
class QensureScheduleController extends Controller {


private $page_size = 2;

	/**
	 * 清仓一排期店铺页
	 */
    public function ActionList() {

    	$request = Yii::app()->getRequest();
		$shop_id = $request->getQuery('shop', '');
        $type = $request->getQuery('type', QensureScheduleManager::ALL_SORT_TYPE);

        $params['event'] = $request->getQuery('event', QcommManager::EVENT_ID);
        $params['tab'] = self::getTab($type);
        if ($params['event'] != 2005) {
        	$params['tab'] = array($params['tab'][QensureScheduleManager::ALL_SORT_TYPE]);
        }
        $params['shop'] = $shop_id;
        $params['type'] = $type;

    	$this->render('qingcang/ensureschedule/ensureschedule.html', $params);
    }


    function actiongetShopList() {

		$shop_id = $_POST['shop'] ? $_POST['shop'] : '';
        $type = $_POST['type'] ? $_POST['type'] : QensureScheduleManager::ALL_SORT_TYPE;
        $event_id = $_POST['event'] ? $_POST['event'] : QcommManager::EVENT_ID;

    	$now = time();
    	//$event_id = QcommManager::EVENT_ID;
    	$shops = QensureScheduleManager::getShopList($event_id, $shop_id, $type);


    	$s_manage = new ShopManager();
		$shop_ids = ArrFomate::cols($shops, 'shop_id');
    	$infos = $s_manage->getShopDetailInfo($shop_ids);
    	foreach ($shops as &$shop){

    		$start = strtotime($shop['start_time']);
    		$end = strtotime($shop['end_time'] );
    		if ($now >= $start  && $end > $now) {
    			$shop['status'] = '<span style="color:green;font-size:14px;font-weight:bold">已上架</span>';
    		}else if($now < $start){
    			$shop['status'] = '<span style="color:gray;font-size:14px;font-weight:bold">未上架</span>';
    		}else if($now >= $end){
    			$shop['status'] = '<span style="color:red;font-size:14px;font-weight:bold">已下架</span>';
    		}
    		if ($shop['banner']) {
    			$banner = json_decode($shop['banner'], true);
    			isset($banner['icon']) && $shop['bg_img']=$banner['icon'];
    		}
    		if ($shop['ext']) {
    			$ext = unserialize($shop['ext']);
    			if (isset($ext['tab'])) {
    				$shop['tab'] = implode("；", $ext['tab']);
    			}else {
    				$shop['tab'] = '无';
    			}
    		}else {
    			$shop['tab'] = '无';
    		}
    		$shop['shop_nick'] = $infos[$shop['shop_id']]['shop_nick'];
    	}
    	$count = count($shops);
    	$opt = true;
    	if ($type == QensureScheduleManager::ALL_SORT_TYPE || $type == QensureScheduleManager::POPULAR_SORT_TYPE) {
    		$opt = false;
    	}
    	$result = array('data'=>$shops, 'count'=>$count, 'opt'=>$opt, 'type'=>$type, 'event'=>$event_id);
    	Json::succ('success', 1, $result);

    }

    /**
     * 获取店铺详细信息
     */
    public function actiongetShopInfo() {

    	$request = Yii::app()->getRequest();
    	$id = $request->getQuery('id', 0);
    	if (!$id) {
    		Json::fail('请求异常，请联系开发解决');
    	}
    	$result = QdonlineManager::getShopInfoById($id);
    	$banner = json_decode($result['banner'], true);
    	if ($banner['icon']) {
    		$result['banner'] = $banner['icon'];
    	} else {
    		$result['banner'] = '';
    	}
    	if(!$result['mark']) {
    		$result['mark'] = '';
    	}
    	if (!$request) {
    		Json::fail('访问姿势不对，请刷新后重新点击');
    	}
    	Json::succ('成功', 1, $result);
    }

    //修改店铺信息
    public function actionUpdateShopInfo() {

    	$request = Yii::app()->getRequest();
    	$id = $request->getPost('id', 0);
    	if (!$id) {
    		Json::fail('请求异常，请联系开发解决');
    	}

    	$shop_id = $request->getPost('shop_id', '');
    	$startTime  = $request->getPost('start_time', '');
    	$endTime = $request->getPost('end_time', '');
    	$title = $request->getPost('title', '');

    	if (!preg_match("/^[0-9]{4}-[0-9]{2}-[0-9]{2} [0-9]{2}:[0-9]{2}:[0-9]{2}$/", $startTime)) {
    		Json::fail('少年，请填写正确的开始时间');
    	}

    	if (!preg_match("/^[0-9]{4}-[0-9]{2}-[0-9]{2} [0-9]{2}:[0-9]{2}:[0-9]{2}$/", $endTime)) {
    		Json::fail('少年，请填写正确的结束时间');
    	}
    	$mark = $request->getPost('mark');
    	$banner = $request->getPost('banner');

    	try {
    		$ret = QdonlineManager::updateInfo($id, $startTime, $endTime, QensureScheduleManager::formateBanner($banner), $mark, $title);
    	} catch (Exception $e) {
    		Json::fail('操作失败'. $e->getMessage());
    	}

    	Json::succ('修改成功');
    }

    //删除店铺信息
    public function actionDelShop() {

    	$request = Yii::app()->getRequest();
    	$id = $request->getPost('id', 0);
    	if (!$id) {
    		Json::fail('请求异常，请联系开发解决');
    	}
    	try {
    		$ret = QdonlineManager::delInfo($id);
    	} catch (Exception $e) {
    		Json::fail('操作失败'. $e->getMessage());
    	}

    	Json::succ('操作成功!');
    }

    //店铺排序
    public function actionSortShop() {

    	$request = Yii::app()->getRequest();
    	$sort = $request->getPost('sort');
		if (!$sort) {
			Json::fail('程序异常，请联系开发解决');
		}
    	$type = $request->getPost('type', 0);

    	$flag = QensureScheduleManager::sortShopList($sort, $type);
    	if ($flag) {
    		Json::succ('排序成功');
    	}else{
    		Json::fail('修改失败');
    	}
    }

    //获取店铺下的商品列表
    public function actiononlineShopGoods() {

    	$request = Yii::app()->getRequest();
    	$id = $request->getQuery('id');
    	if (!$id) {
    		Json::fail('程序异常，请联系开发解决');
    	}
    	$info = QdonlineManager::getShopInfoById($id);
    	if (!$info) {
    		Json::fail('程序异常，请联系开发解决');
    	}

    	$gids = QsheduleManager::getScheduleShopTids($info['shop_id'], $info['event_id'], $info['start_time'], $info['end_time']);

    	if ($info['twitters']) {
    		$twitter_ids = unserialize($info['twitters']);
    	}
    	$total = count($gids);
    	$ret = array();
    	if ($gids) {
    		$audit = new AuditManager();
    		$data = $audit->getTwitterDetail($gids);
    		$data = ArrFomate::hashmap($data, 'tid');
    		$util = new UtilManager();
    		$goods = $util->getGoodsInfo(array_keys($data));
    		foreach ($goods['list'] as $key=>$gv) {
    			$ret[$key] = array_merge($gv, $data[$gv['twitter_id']]);
    			if ($twitter_ids) {
    				$ret[$key]['sort'] = isset($twitter_ids[$gv['twitter_id']]) ? $twitter_ids[$gv['twitter_id']]:100;
    			} else {
    				$ret[$key]['sort'] = 100;
    			}
    		}
    		$ret = ArrFomate::sortByMultiCols($ret, array('sort'=>'SORT_ASC', 'gmv'=>'SORT_DESC'));
    	}
    	$result = array('total'=>$total, 'data'=>$ret, 'shop_id'=>$info['shop_id'], 'id'=>$id);
    	$this->render('qingcang/ensureschedule/online-good-detail.html', $result);

    }


    /**
     * 获取清仓确认排期页顶导
     */
    public function getTab($type=QensureScheduleManager::ALL_SORT_TYPE) {

    	$shop_type = QensureScheduleManager::getShopType();
    	$tab = array();
    	foreach ($shop_type as $key=>$val) {
    		$tmp = array();
    		$tmp['name'] = $val;
    		$tmp['id'] = $key;
    		if($key == $type) {
    			$tmp['class'] = 'class="active"';
    		} else {
    			$tmp['class'] = ' ';
    		}
    		$tab[$key] = $tmp;
    	}
    	return $tab;
    }

    //获取店铺的tab
    public function actiongetShopTab() {

    	$request = Yii::app()->getRequest();
    	$id = $request->getQuery('id');
    	if (!$id) {
    		Json::fail('程序异常，请联系开发解决');
    	}
    	$info = QdonlineManager::getShopInfoById($id);
    	if (!$info) {
    		Json::fail('程序异常，请联系开发解决');
    	}
    	if ($info['ext']) {
			$ext = unserialize($info['ext']);
			if (isset($ext['tab'])) {
				$info['tab'] = implode("\n", $ext['tab']);
			}
    	}
    	Json::succ('成功', 1, $info);
    }

    /**
     * 修改店铺tab
     */
    public function actioneditShopTab() {

    	$request = Yii::app()->getRequest();
    	$id = $request->getPost('id');
    	$tab = $request->getPost('tab', '');
    	$tab = explode("\n", $tab);

    	foreach($tab as &$tval) {
    		$tval = trim($tval);
    	}

    	if (!$id) {
    		Json::fail('程序异常，请联系开发解决');
    	}
    	$info = QdonlineManager::getShopInfoById($id);
    	if (!$info) {
    		Json::fail('程序异常，请联系开发解决');
    	}
    	if ($info['ext']) {
    		$ext = unserialize($info['ext']);
    	}
    	$ext['tab'] = $tab;
    	if (count($ext['tab'])>3) {
    		Json::fail('标签数不能超过3个');
    	}
    	$ext = serialize($ext);

    	$manage = new QdonlineManager();
    	$where = " where id=$id";
    	$update = " ext='$ext'";
    	try {
    		$ret = $manage->updateByWhere($update, $where);
    	}catch (Exception $e) {
    		Json::fail('操作失败'.$e->getMessage());
    	}
    	Json::succ('操作成功');
    }

    public function actionSortShoptid() {

    	$request = Yii::app()->getRequest();
    	$id = $request->getPost('id');
    	$str = $request->getPost('sort', '');
    	$str_arr = explode(";", $str);
    	foreach($str_arr as $val) {
    		if($val) {
    			$tmp = explode(",", $val);
    			$tid[$tmp[0]] = $tmp[1];
    		}
    	}

    	$twitters = serialize($tid);

    	$manage = new QdonlineManager();
    	$where = " where id=$id";
    	$update = " twitters='$twitters'";
    	try {
    		$ret = $manage->updateByWhere($update, $where);
    	}catch (Exception $e) {
    		Json::fail('操作失败'.$e->getMessage());
    	}
		Json::succ('操作成功');
    }

}
