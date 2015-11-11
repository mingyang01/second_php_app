<?php

class MenuManager extends Manager {
	/**
	 * [getMenu description]
	 * @param  [type]
	 * @return [type]
	 */
	private static $menuOFF = 'on';
	public static function getMenu($business, $uid, $domain = true) {

		$redis = new Redisdb(Redisdb::REDIS_SERVER_DEFAULT);
        $key = "tuan_menu_users";
		$results = $redis->get($key, array($uid));

        if($results&&self::$menuOFF=='on'){
        	return json_decode($results, true);
        }
        //$sid = 945;
		$url = "http://developer.meiliworks.com/ExtraApi/menu?business=$business&domain=$domain&uid=$uid";
		$output = Yii::app()->curl->get($url);
		$body = $output['body'];
		$redis->set($key, array($uid), $body);
		$results = json_decode($body, true);
		return $results;
	}
}
