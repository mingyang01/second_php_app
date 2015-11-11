<?php
// chao
class ToolController extends Controller
{
    public function ActionIndex() {
        $this->render('site/tool.html');
    }

    public function ActionApiTest() {
        $curl   = Yii::app()->curl;
        $uid    = $this->speed->id;
        $ip     = $_SERVER["REMOTE_ADDR"];
        $header = array('Meilishuo:uid:9;ip:172.16.0.40');;
        $curl->setHeader($header);

        $url = $_POST['platform'].$_POST['api'];

        $params = $_POST['params'];
        $method = $_POST['method'];

        if ($method == 'get') {
            $url = $url . '?' . $params;
            $results = $curl->$method($url);
        } else {
            $results = $curl->$method($url, $this->convertUrlQuery($params));
        }

        // print_r($results);
        // $results = json_decode($results, true);
        print_r(json_decode($results['body'], true));
        //echo $results['body'];
    }

    function convertUrlQuery($query)
    {
        if ($query == '') {
            return array();
        }
        $queryParts = explode('&', $query);
        $params = array();
        foreach ($queryParts as $param)
        {
            $item = explode('=', $param);
            $params[$item[0]] = $item[1];
        }
        return $params;
    }

    public function ActionCacheUser()
    {
        $sql = "select * from t_eel_admin_user where realname != '' and tmp != '' ";
        $db  = Yii::app()->sdb_eel;
        $resultes = $db->createCommand($sql)->queryAll();

        $workIdUserMap = array();
        foreach ($resultes as $key => $value) {
            $workIdUserMap[$value['user_id']] = $value['realname'];
        }

        $speedIdUserMap = array();
        foreach ($resultes as $key => $value) {
            $speedIdUserMap[$value['tmp']] = $value['realname'];
        }

        $redis = new Redis();

        $redis->connect('127.0.0.1');
        $redis->select(13);

        $redis->hMset('cache:speedIdUserMap', $speedIdUserMap);
        $redis->hMset('cache:workIdUserMap', $workIdUserMap);

    }

    public function ActionUpdateSchedule()
    {
        $this->render('tool/updateSchedule.html');
    }

    public function ActionSaveUpdateSchedule()
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
        file_put_contents("/home/work/logs/tuan/toolSaveUpdateSchedules.log", $dateTime." --- ".$ip." --- ".$userId." --- ".$user." --- param：".json_encode($updateScheduleFilter)." --- groupon_ids：".json_encode($grouponIdArr)." --- twitter_ids".json_encode($twitterIdArr)."\r\n", FILE_APPEND);

        $errNum     = 0;
        $succNum    = 0;
        $succResult = '';
        $errResult  = '';
        $errTwitterIds = array();
        $errGrouponIds = array();
        $db_brd_shop   = Yii::app()->db_brd_shop;
        foreach ($grouponIdArr as $gidKey=>$gid) {
            $updateScheduleFilter['twitter_id']  = $twitterIdArr[$gidKey];
            $updateScheduleFilter['campaign_id'] = $gid;

            $useTimeBegin = microtime(true);

            $setResult = $this->util->updateSchedule($updateScheduleFilter);

            $useTimeEnd = microtime(true);
            $useTime    = $useTimeEnd - $useTimeBegin;
            $logFiter = array(
                    'groupon_id' => $updateScheduleFilter['campaign_id'],
                    'twitter_id' => $updateScheduleFilter['twitter_id'],
                    'user'       => $this->user->name,
                    'name'       => '修改排期-工具',
                    'content'    => $setResult,
                    'param'      => $updateScheduleFilter,
                    'resource_name' => 'toolUpadteSchedule',
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
                $update_arr = array();
                if ($updateScheduleFilter['start_time']) {
                    $update_arr['start_time'] = strtotime($updateScheduleFilter['start_time']);
                }
                if ($updateScheduleFilter['end_time']) {
                    $update_arr['end_time'] = strtotime($updateScheduleFilter['end_time']);
                }
                if ($update_arr) {
                    $db_brd_shop->createCommand()->update(
                            'shop_groupon_info',
                            $update_arr,
                            'id=:id',
                            array(':id'=>$gid)
                    );
                }

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