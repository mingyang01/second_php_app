<?php
/**
* 团购，清仓，秒杀的权限控制
* @author mingyang@meilishuo.com
* @version 2015-7-22
*/
class AuthManager extends Manager
{
    //角色判断
    public function judgeRole($sid)
    {
        $role = array('tuan'=>false,'miao'=>false,'qing'=>false);
        $re = array();
        foreach ($role as $key => $value) {
            $point = $this->enToCh($key);
            $curl = Yii::app()->curl;
            //$url = "developer.meiliworks.com/ApiForTuan/CheckRole?role=$point&sid=$sid";
            $url = "developer.meiliworks.com/api/checkAccess?point=$point&uid=$sid";
            $output = $curl->get($url);
            $output = json_decode($output['body'],true);
            if($output['data']['checked'])
            {
                $re[$key] = true;
            }
            else
            {
                $re[$key] = false;
            }
        }
        return $re;
    }

    public function enToCh($role)
    {
        switch ($role) {
            case 'tuan':
                return '商家运营-团购/团购';
                break;
            case 'miao':
                return '商家运营-团购/秒杀';
                break;
            case 'qing':
                return '商家运营-团购/清仓';
                break;
             default:
                 return '';
                break;
        }
    }

    public function createMenu($business,$item,$uid,$domain = true){


    	$redis = new Redisdb(Redisdb::REDIS_SERVER_DEFAULT);
    	$key = "tuan_menu_users";
        //$sid = 945;
        $url = "http://developer.meiliworks.com/ExtraApi/menu?business=$business&domain=$domain&uid=$uid";
        $output = Yii::app()->curl->get($url);
        $results = json_decode($output['body'], true);
        $redis->set($key, array($uid), $output['body']);
    }

    public function redirectAuto($uid,$item){

    	$redis = new Redisdb(Redisdb::REDIS_SERVER_DEFAULT);
    	$key = "tuan_redirect";

        switch ($item) {
            case 'tuan':
                $redis->set($key, array($uid), '/audit/first');
                return '/audit/first';
                break;
            case 'miao':
                $redis->set($key, array($uid), '/audit/first?type=1&event=1065&business=3');
                return '/audit/first?type=1&event=1065&business=3';
                break;
            case 'qing':
                $redis->set($key, array($uid), '/qingcang/Qfirst/list');
                return '/qingcang/Qfirst/list';
                break;
            case 'jinbi':
                $redis->set($key, array($uid), '/jinbi/Activity/showlist');
                return '/jinbi/Activity/showlist';
                break;
            case 'desire':
                $redis->set($key, array($uid), '/audit/first');
                return '/audit/first?type=1&event=2456&business=3';
                break;
            default:
                # code...
                break;
        }
    }

    public function getRedirect($uid,$clean=false){

    	$redis = new Redisdb(Redisdb::REDIS_SERVER_DEFAULT);
    	$key = "tuan_redirect";

        if($clean){
            $redis->set($key, array($uid), null);
            return ;
        }
        $results = $redis->get($key, array($uid));
        if($results){
            return $results;
        }else{
            return false;
        }
    }

}