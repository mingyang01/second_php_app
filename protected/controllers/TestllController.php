<?php
class TestllController extends Controller {

	public function actionTest() {




		$redis = new Redisdb(Redisdb::REDIS_SERVER_DEFAULT);
        $key = "tuan_menu_users";
        $redis->set($key, array(99123), '111222');
		$results = $redis->get($key, array(99123));
var_dump(11,$results);exit;

	}
}