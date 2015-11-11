<?php
/**
* 主题活动
* @author mingyang@meilishuo.com
* @version 2015-7-30
*/
class ActivityController extends Controller{

    public function actionIndex($event_id = 2052,$area = 1,$time = ''){
        if(!$time){
            $time = date('Y-m-d')." 00:00:00";
        }
        $new_time = $time;
        $goodsInfo = $this->activity->getGoodsInfo($event_id, $new_time);
        $tab = $this->activity->getAreaOfEvent($event_id, $new_time);
        $params = array('data'=>$goodsInfo,'area'=>$area,'event_id'=>$event_id,'time'=>$time,'tab'=>$tab);
        $this->render('activity/index.tpl',$params);
    }

    public function actionEdit($event_id = 0){
        if(empty($event_id))
        {
            $event_id = 0;
        }
        $eventInfo = $this->activity->getActivityInfo($event_id);
        $goodsInfo = $this->activity->getGoodsInfo($event_id);
        $eventInfo['detail'] = json_decode($eventInfo['detail'], true);
        $array = array('eventInfo'=>$eventInfo,'goodsInfo'=>$goodsInfo,'event_id'=>$event_id);
        $this->render('activity/edit.tpl',$array);
    }
    //退回商品
    public function actionRemoveActivity($event_id = '',$tid = ''){

        if(!$tid||!$event_id){
            $results = array('code'=>0,'msg'=>'param event, tid is required!');
            echo json_encode($results);
        }else{
            $results = $this->activity->removeActivity($event_id,$tid);
            if($results){
                $results = array('code'=>1,'msg'=>'删除成功');
            }else{
                $results = array('code'=>0,'msg'=>'未知错误');
            }
            echo json_encode($results);
        }

    }
    //人工排序
    public function actionSortEvent(){
        $request = Yii::app()->request;
        $event_id = $request->getparam('event_id', '');
        $tids = $request->getparam('tids', '');
        $area = $request->getparam('area', '');
        $time = $request->getparam('time', '');
        $results = array();

        if(!$event_id||!$tids||$area==''){
            $retults = array('code'=>0,'msg'=>'params area,event_id,tids is required!');
        }else{
            $flag = $this->activity->sortEvent($event_id,$area,$tids,$time);
            if($flag){
                $results = array('code'=>1,'msg'=>'排序成功');
            }else{
                $results = array('code'=>0,'msg'=>'遇到未知错误');
            }
        }
        echo json_encode($results);
    }
    //根据条件排序
    public function actionSortByCondition(){
        $request = Yii::app()->request;
        $event_id = $request->getparam('event_id', '');
        $condition = $request->getparam('condition', '');
        $area = $request->getparam('area', '');
        $time = $request->getparam('time', '');
        $retults = array();
        if(!$event_id||!$condition||$area==''){
            $retults = array('code'=>0,'msg'=>'params area,event_id,condition is required!');
        }else{
            $results = $this->activity->getSortGoods($event_id,$area,$condition,$time);
            $results = array('code'=>1,'data'=>$results);
        }
        echo json_encode($results);
    }
    //导出数据
    public function actionExportHtml($event_id = 0,$area = 1,$time = ''){
        $goodsInfo = $this->activity->getGoodsInfo($event_id,$time);
        $list = array();
        foreach ($goodsInfo as $key => $value) {
                $variable = $value['goods_list'];
                foreach ($variable as $k => $val) {
                    $list []= $val;
                }
        }
        $params = array('data'=>$goodsInfo,'area'=>$area,'event_id'=>$event_id,'time'=>$time);
        $this->activity->exportHtml($list,'主题活动');
    }
    //划分区域
    public function actionAutoDivide($event_id,$time){

    	$time = $time . ' 00:00:00';
        $flag = $this->activity->autoDivide($event_id,$time);
        if(!$flag){
            $results = array('code'=>0,'msg'=>'成功为商品划分了区域');
        }else{
            $results = array('code'=>1,'msg'=>'为商品划分区域时出现了问题！');
        }
        echo json_encode($results);
    }
    //活动信息修改
    public function ActionSaveActivityInfo()
    {
        $this->request  = Yii::app()->request;
        $event_id       = (int)$this->request->getPost('event_id', 0);
        $event_name     = htmlspecialchars(trim($this->request->getPost('event_name', '')));
        $title          = htmlspecialchars(trim($this->request->getPost('title', '')));
        $top_banner_pc  = htmlspecialchars(trim($this->request->getPost('top_banner_pc', '')));
        $top_banner_mob = htmlspecialchars(trim($this->request->getPost('top_banner_mob', '')));
        $shareImage     = htmlspecialchars(trim($this->request->getPost('share_img', '')));
        $shareText      = htmlspecialchars(trim($this->request->getPost('shareText', '')));
        if (!$event_id) {
            throwMessage('活动不存在', 'error');
        }
        if (!$event_name) {
            throwMessage('活动名称不可以为空', 'error');
        }

        $event_info = $this->event->getEventInfo($event_id);
        if (!$event_info) {
            throwMessage('活动信息不存在', 'error');
        }

        if ($event_info['detail']) {
            $detail = json_decode($event_info['detail'], true);
        } else {
            $detail = array();
        }
        $detail['share']['shareImage'] = $shareImage;
        $detail['share']['shareText']  = $shareText;
        $detail['top_banner_pc']       = $top_banner_pc;
        $detail['top_banner_mob']      = $top_banner_mob;

        // 如果接收到的event_name与原来的不用则取查询新接收的event_name有没有
        if ($event_info['event_name'] != $event_name) {
            $sdb_brd_shop  = Yii::app()->sdb_brd_shop;
            $searchSql = "select * from tuan_events_list where event_name='{$event_name}' limit 1";
            $eventInfo     = $sdb_brd_shop->createCommand($searchSql)->queryRow();
            if ($eventInfo) {
                throwMessage("活动名称：{$event_name} 已存在", 'error');
            }
        }

        $update_filter = array(
            'detail'     => json_encode($detail),
            'event_name' => $event_name,
            'title'      => $title,
            'banner_pc'  => $top_banner_pc,
            'banner_mob' => $top_banner_mob
        );
        $db_brd_shop   = Yii::app()->db_brd_shop;
        $update_result = $db_brd_shop->createCommand()->update('tuan_events_list', $update_filter, 'event_id=:event_id',array(':event_id'=>$event_id));
        $data = array('succ'=>1,'msg'=>'update done!');
        echo json_encode($data);
    }
    //保存区域信息
    public function actionSaveAreaInfo(){
        $request  = Yii::app()->request;
        $data     = $request->getPost('data', '');
        $event_id = (int)$request->getPost('event_id', 0);
        if (!$event_id) {
        	Json::fail('活动不存在');
        }
        if(!$data){
            Json::fail('保存的信息失败');
        }
        $event_info = $this->event->getEventInfo($event_id);
        if (!$event_info) {
            Json::fail('活动信息不存在');
        }

//         foreach($data as $dk=>$dv) {
//         	if(trim($dv['link']) != '#pgtop') {
//         		if (!$dv['price']) {
//         			Json::fail('tab不存在');
//         		}
//         	}
//         }

        if ($event_info['detail']) {
            $detail = json_decode($event_info['detail'], true);
        } else {
            $detail = array();
        }
        //时间判断，如果没有默认为0，表示永久
        if (!preg_match("/^[0-9]{4}-[0-9]{2}-[0-9]{2} [0-9]{2}:[0-9]{2}$/", $data['starttime'])) {
        	$data['starttime'] = 0;
        }
        if (!preg_match("/^[0-9]{4}-[0-9]{2}-[0-9]{2} [0-9]{2}:[0-9]{2}$/", $data['endtime'])) {
        	$data['starttime'] = 0;
        }
        //格式化data信息
        foreach($data as &$dval) {
        	$dval['id'] =  substr($dval['link'], strpos($dval['link'], '_') + 1);
        }
        $detail['right_nav_info']['right_nav']=$data;
        $update_filter = array(
            'detail'     => json_encode($detail),
        );
        $db_brd_shop   = Yii::app()->db_brd_shop;
        $update_result = $db_brd_shop->createCommand()->update('tuan_events_list', $update_filter, 'event_id=:event_id',array(':event_id'=>$event_id));
        Json::succ('修改成功');

    }
    //更新活动时间
    public function actionUpdateEventTime(){
        $request  = Yii::app()->request;
        $event_id = (int)$request->getPost('event_id', 0);
        $start_time = $request->getPost('start_time', '');
        $end_time = $request->getPost('end_time', '');
        $join_status = $request->getPost('join_status', '');
        if($event_id&&$start_time&&$end_time){
            $update_filter = array(
                'join_start_time'     => $start_time,
                'join_end_time'     => $end_time,
                'join_status'     => $join_status
            );
            $db_brd_shop   = Yii::app()->db_brd_shop;
            $update_result = $db_brd_shop->createCommand()->update('tuan_events_list', $update_filter, 'event_id=:event_id',array(':event_id'=>$event_id));
            $retuslts = array('succ'=>'1','msg'=>'success');
            echo json_encode($retuslts);
        }else{
            $retuslts = array('succ'=>'0','msg'=>'params event_id,start_time,end_time is required!');
            echo json_encode($retuslts);
        }
    }
    //手动为活动商品划分区域
    public function actionAreaDivide(){
        $request = Yii::app()->request;
        $event_id = $request->getparam('event_id',0);
        $area = $request->getparam('area',1);
        $time = $request->getparam('start_time','');
        if(!$time){
            $time = '';
            $formatTime = '';
        }else{
            $time = $time." 00:00:00";
            $formatTime = date('Y-m-d',strtotime($time));
        }
        $activityInfo = $this->activity->getActivityInfo($event_id);
        $tab = $this->activity->getAreaOfEvent($event_id, $time);
        $goodsInfo = $this->activity->getGoodsInfo($event_id, $time);
        $arrIds = DataToArray($goodsInfo[$area]['goods_list'],'twitter_id');
        $goodsIds = implode(',',$arrIds);
        $params = array('tab'=>$tab,'activityInfo'=>$activityInfo,'goodsIds'=>$goodsIds,'event_id'=>$event_id,'time'=>$formatTime,'area'=>$area);
        $this->render('activity/areadivide.tpl',$params);
    }
    //保存更改的区域商品
    public function actionSaveDivideStatus(){
        $request = Yii::app()->request;
        $goodsIds = $request->getparam('goodsIds','');
        $changeGoods = $request->getparam('changeGoods','');
        $event_id = $request->getparam('event_id','');
        $area = $request->getparam('area','');
        $goodsIds = explode(',', $goodsIds);
        $changeGoods = explode(',', $changeGoods);
        $addIds = array_diff($changeGoods,$goodsIds);
        $addIds = array_filter($addIds);
        $this->activity->changeGoodsArea($event_id,$addIds,$area);
    }

    public function actionTest(){
        $this->eventAutoSort->AutoSort('2052');
    }

}