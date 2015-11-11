<?php

class AuditCommentsController extends Controller
{
    public function ActionRunCommentsUserName()
    {
        $sql = "select distinct audit_user from shop_groupon_audit_comments where audit_user > 0 and audit_opname = '' and audit_time >= '2015-05-01 00:00:00'";

        $sdb_brd_shop = Yii::app()->sdb_brd_shop;
        $Ids      = $sdb_brd_shop->createCommand($sql)->queryColumn();
        $userIds  = array();
        foreach ($Ids as $k=>$v) {
            $userIds[$v] = $v;
        }

        $sdb_eel = Yii::app()->sdb_eel;
        $sql = "select * from t_eel_admin_user where user_id in (".implode(',', $userIds).")";
        $list = $sdb_eel->createCommand($sql)->queryAll();

        $newArr = array();
        $noArr  = array();
        $new = array();
        //$db_brd_shop = Yii::app()->db_brd_shop;
        foreach ($list as $k=>$v) {
            $userId = (int)$v['user_id'];
            $tmpId  = (int)$v['tmp'];
            $userName = $v['realname'];
            $url = "http://api.speed.meilishuo.com/user/show?token=e98cfc1a4f23ae1699919c505ae2ba04&mail=".$v['username'];
            $data = $this->curl_get($url);

            if (isset($data['data']['depart']) && stripos($data['data']['depart'], '团购') !== false) {
                $newArr[$userId] = $data['data']['name'];
                unset($userIds[$userId]);
            } else {
                $noArr[$userId] = $userName;
                $new[] = $tmpId;
            }
            $updateSql = "update shop_groupon_audit_comments set audit_opname='{$userName}' where audit_user='{$userId}' and audit_opname = '' and audit_time >= '2015-04-20 00:00:00'";
            p($updateSql);
            //$db_brd_shop->createCommand($updateSql)->execute();
        }

        $sql = "select * from t_eel_admin_user where tmp in (".implode(',', $userIds).")";
        $newArr1 = array();
        $noArr1  = array();
        $new1 = array();
        $list = $sdb_eel->createCommand($sql)->queryAll();
        foreach ($list as $k=>$v) {
            $userId = (int)$v['user_id'];
            $tmpId  = (int)$v['tmp'];
            $userName = $v['realname'];
            $url = "http://api.speed.meilishuo.com/user/show?token=e98cfc1a4f23ae1699919c505ae2ba04&mail=".$v['username'];
            $data = $this->curl_get($url);
            //p($data);
            if (isset($data['data']['depart']) && stripos($data['data']['depart'], '团购') !== false) {
                $newArr1[$tmpId]  = $data['data']['name'];
                unset($userIds[$tmpId]);

                $updateSql = "update shop_groupon_audit_comments set audit_opname='{$userName}' where audit_user='{$tmpId}' and audit_opname = '' and audit_time >= '2015-04-20 00:00:00'";
                //$db_brd_shop->createCommand($updateSql)->execute();
            } else {
                $noArr1[$tmpId] = $userName;
                $new1[]         = $data;
                unset($userIds[$tmpId]);

                $updateSql = "update shop_groupon_audit_comments set audit_opname='{$userName}' where audit_user='{$tmpId}' and audit_opname = '' and audit_time >= '2015-04-20 00:00:00'";
                //$db_brd_shop->createCommand($updateSql)->execute();
            }
        }
        p($newArr, $noArr,$newArr1, $noArr1, $new1,implode(",", $userIds));exit();
    }

    public function  ActionRunOld()
    {
        $sql = "select distinct audit_user from shop_groupon_audit_comments where audit_user > 0 and audit_opname = '' and audit_time < '2015-05-01 00:00:00'";

        $sdb_brd_shop = Yii::app()->sdb_brd_shop;
        $Ids      = $sdb_brd_shop->createCommand($sql)->queryColumn();
        $userIds  = array();
        foreach ($Ids as $k=>$v) {
            $userIds[$v] = $v;
        }

        $sdb_eel = Yii::app()->sdb_eel;
        $sql = "select * from t_eel_admin_user where user_id in (".implode(',', $userIds).")";
        $list = $sdb_eel->createCommand($sql)->queryAll();

        p($Ids);
        $db_brd_shop = Yii::app()->db_brd_shop;
        foreach ($list as $k=>$v) {
            $userId = (int)$v['user_id'];
            $tmpId  = (int)$v['tmp'];
            $userName = $v['realname'];
            $updateSql = "update shop_groupon_audit_comments set audit_opname='{$userName}' where audit_user='{$userId}' and audit_opname = '' and audit_time < '2015-04-20 00:00:00'";
            //p($updateSql);
            $db_brd_shop->createCommand($updateSql)->execute();
        }
    }

    public function curl_get($url)
    {
        $start_time = microtime(true);
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        // 启用时会将头文件的信息作为数据流输出。
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_TIMEOUT, 5);
        // 更改referer
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $data = curl_exec($ch);
        curl_close($ch);
        $end_time = microtime(true);
        $use_time = $end_time - $start_time;

        return json_decode($data, true);
    }

}
?>