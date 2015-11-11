<?php

// bridge for works interface
class BridgeController extends Controller {
	public $host = 'http://works.meiliworks.com/tuanht/';

	// ajax_saveCheckResult
	// 审核
	public function ActionSaveAudit() {
		// array
		$ids = $_POST['ids'];
        $comment = $_POST['comment'];
        $shops = $_POST['shops'];
        $checkResult = $_POST['checkResult'];

        $comments = array();
        $blacklists = array();
        $checkResults = array();

        foreach ($ids as $key => $value) {
        	$comments[] = $comment;
        	$blacklists[] = 0;
        	$checkResults[] = $checkResult;
        }
        $url = 'ajax_saveCheckResult';

        $params = array(
        	'tuanIds'=>$ids,
        	'checkResult'=>$checkResults,
        	'shop_ids'=>$shops,
        	'blacklist'=>$blacklists,
        	'checkResultSource'=>$comments
        );

        // echo http_build_query($params);
        // exit();
        $result = $this->post($this->host.$url, $params);
        echo $result;
	}

	public function post($url, $data=array()) {
	    //对空格进行转义
	    $url = str_replace(' ','+',$url);
	    $ch = curl_init();
	    //设置选项，包括URL
	    $cookies = 'PHPSESSID=064gktpun74rujdflt4ama7m32; speed_uid=945';
	    curl_setopt($ch, CURLOPT_URL, "$url");
	    curl_setopt($ch, CURLOPT_COOKIE, $cookies);
	    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	    curl_setopt($ch, CURLOPT_HEADER, 0);
	    curl_setopt($ch,CURLOPT_TIMEOUT,3);  //定义超时3秒钟  
	    // POST数据
	    curl_setopt($ch, CURLOPT_POST, 1);
	    // 把post的变量加上
	    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
	    
	    //执行并获取url地址的内容
	    $output = curl_exec($ch);
	    //释放curl句柄
	    curl_close($ch);
	    return $output;
	}
}