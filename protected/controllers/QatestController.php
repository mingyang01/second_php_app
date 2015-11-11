<?php
/**
                   _ooOoo_
                  o8888888o
                  88" . "88
                  (| -_- |)
                  O\  =  /O
               ____/`---'\____
             .'  \\|     |//  `.
            /  \\|||  :  |||//  \
           /  _||||| -:- |||||-  \
           |   | \\\  -  /// |   |
           | \_|  ''\---/''  |   |
           \  .-\__  `-`  ___/-. /
         ___`. .'  /--.--\  `. . __
      ."" '<  `.___\_<|>_/___.'  >'"".
     | | :  `- \`.;`\ _ /`;.`/ - ` : | |
     \  \ `-.   \_ __\ /__ _/   .-` /  /
======`-.____`-.___\_____/___.-`____.-'======
                   `=---='
^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^
         佛祖保佑       永无BUG

 * QatestController.php
 * QA测试专用
 * Author: ruidongsong@meilishuo.com
 * Date: 2015-9-10 下午12:54:52
 */

class QatestController extends Controller
{

    public function ActionIndex()
    {
        $request = Yii::app()->request;
        $event_id = $request->getQuery('event_id', '');
        $type_map = QatestManager::$typeMap;
        if (!array_key_exists($event_id, $type_map)) {
            $event_id = QatestManager::$tuangou_event_id;
        }

        $sql = "select t2.twitter_id,t2.id,t2.start_time,t2.end_time,t2.off_price,t1.event_id from tuan_events_item_detail t1 left join shop_groupon_info t2 on t1.groupon_id=t2.id where t2.goods_type=2 and t1.event_id={$event_id} order by t2.id desc";
        debug($sql);
        $sdb_brd_shop  = Yii::app()->sdb_brd_shop;
        $goods_list    = $sdb_brd_shop->createCommand($sql)->queryAll();

        $this->assign('goods_list', $goods_list);
        $this->assign('type_map', $type_map);
        $this->assign('event_id', $event_id);
        $this->render('qatest/goodsList.html');
    }

    public function ActionSchedule()
    {
        $this->render('qatest/updateSchedule.html');
    }

    // 排期
    public function ActionSaveSchedule()
    {
        $request = Yii::app()->getRequest();

        if (!$request->getIsAjaxRequest()) {
            output(array('succ'=>0, 'msg'=>'少年，请不要非法操作'));
        }

        $grouponIds    = $request->getPost('groupon_ids', '');
        $twitterIds    = $request->getPost('twitter_ids', '');
        $startTime     = $request->getPost('start_time', '');
        $endTime       = $request->getPost('end_time', '');
        $preheatTime   = $request->getPost('preheat_time', '');

        $repertory     = (int)$request->getPost('repertory', '');
        $platform      = (int)$request->getPost('platform', '');
        $campaignType  = (int)$request->getPost('campaign_type', '');

        if (!$grouponIds || !$twitterIds) {
            output(array('succ'=>0, 'msg'=>'请传入团购id和twitter_id'));
        }
        if ($startTime && !preg_match("/^[0-9]{4}-[0-9]{2}-[0-9]{2} [0-9]{2}:[0-9]{2}:[0-9]{2}$/", $startTime)) {
            output(array('succ'=>0, 'msg'=>'少年，请填写正确的运行时间'));
        }
        if ($endTime && !preg_match("/^[0-9]{4}-[0-9]{2}-[0-9]{2} [0-9]{2}:[0-9]{2}:[0-9]{2}$/", $endTime)) {
            output(array('succ'=>0, 'msg'=>'少年，请填写正确的结束时间'));
        }
        if ($preheatTime && !preg_match("/^[0-9]{4}-[0-9]{2}-[0-9]{2} [0-9]{2}:[0-9]{2}:[0-9]{2}$/", $preheatTime)) {
            output(array('succ'=>0, 'msg'=>'少年，请填写正确的结束时间'));
        }
        if ($repertory && !is_numeric($repertory)) {
            output(array('succ'=>0, 'msg'=>'少年，库存必须为数字'));
        }
        if ($campaignType != 2 && $campaignType != 10 && $campaignType != 15 && $campaignType != 16 ) {
            output(array('succ'=>0, 'msg'=>'少年，活动类型错误'));
        }

        if (!$startTime || !$startTime) {
            output(array('succ'=>0, 'msg'=>'开始时间或结束时间不可为空'));
        }

        $updateScheduleFilter = array();
        $updateScheduleFilter['campaign_type']  = $campaignType;
        $updateScheduleFilter['platform']       = $platform;
        if (isset($startTime) && $startTime) {
            $updateScheduleFilter['start_time'] = $startTime;
        }
        if (isset($endTime) && $endTime) {
            $updateScheduleFilter['end_time']   = $endTime;
        }
        if (isset($preheatTime) && $preheatTime) {
            $updateScheduleFilter['preheat_time']   = $preheatTime;
        }
        if (isset($repertory) && $repertory) {
            $updateScheduleFilter['repertory']  = $repertory;
        }

        $grouponIdArr = explode(",", $grouponIds);
        $twitterIdArr = explode(",", $twitterIds);

        if (count($grouponIdArr) != count($twitterIdArr)) {
            output(array('succ'=>0, 'msg'=>'groupon_id和twitter_id数量不一致，请仔细检查'));
        }

        // 写日志
        $ip     = Yii::app()->request->getUserHostAddress();
        $user   = isset(Yii::app()->user->name) ? Yii::app()->user->name : '未知';
        $userId = isset(Yii::app()->user->id) ? Yii::app()->user->id : '0';
        $dateTime = date("Y-m-d H:i:s");
        file_put_contents("/home/work/logs/tuan/qatestSaveUpdateSchedules.log", $dateTime." --- ".$ip." --- ".$userId." --- ".$user." --- param：".json_encode($updateScheduleFilter)." --- groupon_ids：".json_encode($grouponIdArr)." --- twitter_ids".json_encode($twitterIdArr)."\r\n", FILE_APPEND);

        $errNum     = 0;
        $succNum    = 0;
        $succResult = '';
        $errResult  = '';
        $errTwitterIds = array();
        $errGrouponIds = array();
        $db_brd_shop   = Yii::app()->db_brd_shop;
        foreach ($grouponIdArr as $gidKey=>$gid) {

            $grouponInfo = $this->goods->getGrouponInfo($gid);
            if (!$grouponInfo) continue;

            $updateScheduleFilter['twitter_id']  = $twitterIdArr[$gidKey];
            $updateScheduleFilter['campaign_id'] = $gid;
            $updateScheduleFilter['campaign_sku'] = 1;
            $updateScheduleFilter['discount_type'] = 2;
            $updateScheduleFilter['discount_off'] = $grouponInfo['off_price'];
            $updateScheduleFilter['preheat_tag']  = QatestManager::$type_title_map[$campaignType];

            $useTimeBegin = microtime(true);

            $setResult = $this->util->newSetCampaignInfo($updateScheduleFilter);

            $useTimeEnd = microtime(true);
            $useTime    = $useTimeEnd - $useTimeBegin;
            $logFiter = array(
                    'groupon_id' => $updateScheduleFilter['campaign_id'],
                    'twitter_id' => $updateScheduleFilter['twitter_id'],
                    'user'       => $this->user->name,
                    'name'       => 'qatest-测试排期-工具',
                    'content'    => $setResult,
                    'param'      => $updateScheduleFilter,
                    'resource_name' => 'qatestSchedule',
            );
            $logFiter['is_succ']  = $setResult['succ'];
            $logFiter['use_time'] = number_format($useTime, 5);
            // 增加日志
            $this->tuanLog->addLog($logFiter);

            if ($setResult['succ'] != 1) {
                $errNum++;
                $errResult .= "twitter_id：{$updateScheduleFilter['twitter_id']}   groupon_id：{$updateScheduleFilter['campaign_id']}   msg：{$setResult['msg']}\r\n";
                $errTwitterIds[] = $updateScheduleFilter['twitter_id'];
                $errGrouponIds[] = $updateScheduleFilter['campaign_id'];
            } else {
                // 更新shop_groupon_info
                $db_brd_shop->createCommand()->update(
                        'shop_groupon_info',
                        array('start_time'=>strtotime($startTime), 'end_time'=>strtotime($endTime)),
                        'id=:id',
                        array(':id'=>$gid)
                );

                $succNum++;
                $succResult .= "twitter_id：{$updateScheduleFilter['twitter_id']}   groupon_id：{$updateScheduleFilter['campaign_id']}   msg：{$setResult['msg']}\r\n";
            }
        }

        $resultMsg = "成功操作：{$succNum}个商品，失败操作{$errNum}个商品\r\n";
        if ($errNum > 0) {
            $resultMsg .= "失败操作商品twitter_id：".implode(",", $errTwitterIds)."\r\n";
            $resultMsg .= "失败操作商品groupon_id：".implode(",", $errGrouponIds)."\r\n";
        }
        $resultMsg .= "\r\n失败详细原因：\r\n";
        $resultMsg .= $errResult;
        $resultMsg .= "\r\n成功详细原因：\r\n";
        $resultMsg .= $succResult;

        output(array('succ'=>1, 'msg'=>1, 'result_msg'=>$resultMsg));
    }
}
?>