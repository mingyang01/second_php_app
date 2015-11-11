<?php
/**
 * 清仓活动页
 * @author linglingqi@
 * @version 2015-09-07
 */
class QactivityController extends Controller {

	//清仓普通活动ID
	const EVENT_ID = 2015;

	private $page_size = 2;

    public function ActionList() {

    	$request = Yii::app()->getRequest();
    	$id = $request->getPost('id');
    	$manage = new EventManager();
    	$info = $manage->getEventInfo($id);
    	$info['end_time'] = date('Y-m-d H:i:s', $info['end_time']);
    	$info['preheat_time'] = date('Y-m-d H:i:s', $info['start_time']);
    	Json::succ('成功', 1, $info);
    }

}
