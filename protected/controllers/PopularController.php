<?php
/**
* 流行推荐 
* @author mingyang@meilishuo.com
* @version 2015-9-21
*/
class PopularController extends Controller{

    

    public function actionIndex(){
        // $date = date('2015-8-25 0:0:0');
        // $list = $this->Popular->getPopularList($date);
        // $this->render('popular/index.tpl',array('list'=>$list));

        // $this->request  = Yii::app()->request;
        // $date = $this->request->getQuery('date', date("Y-m-d"));
        $request       = Yii::app()->request;
        $date = $request->getParam('date','');
        if (!$date) {
            $date = date("Y-m-d");
        }
        //$date = date('2015-8-25');
        $list = $this->Popular->getPopularList($date);
        $params = array(
            'tuan_list'=>$list,
            'date'=>$date,
            'tuangou_event_id'=>PopularManager::$popularEventId
            );
        $this->render('popular/index.tpl',$params);
    }

    public function ActionSaveTuangouRank()
    {
        $request       = Yii::app()->request;
        $twitterIdsStr = $request->getPost('tuan_id','');
        $eventId       = PopularManager::$popularEventId;

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

    
}