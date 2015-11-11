<?php
class UtilManager extends Manager {

    private static $virus = null;
    private static $snake = null;
    private static $doota = null;
    private static $goods = null;

    public static function virus() {
        if (is_null(self::$virus)) {
            $curl = Yii::app()->curl;
            $header = array('Meilishuo:uid:9;ip:172.16.0.40');
            $curl->setHeader($header);
            self::$virus = $curl;
        }
        return self::$virus;
    }

    public static function snake() {
        if (is_null(self::$snake)) {
            $curl = Yii::app()->curl;
            $header = array('Meilishuo:uid:9;ip:172.16.0.40');
            $curl->setHeader($header);
            self::$snake = $curl;
        }
        return self::$snake;
    }

    public static function doota() {
        if (is_null(self::$doota)) {
            $curl = Yii::app()->curl;
            $header = array('Meilishuo:uid:9;ip:172.16.0.40');
            $curl->setHeader($header);
            self::$doota = $curl;
        }
        return self::$doota;
    }

    public static function goods() {

    	if (is_null(self::$goods)) {
    		$curl = Yii::app()->curl;
    		$header = array('Meilishuo:uid:9;ip:172.16.0.40');
    		$curl->setHeader($header);
    		self::$goods = $curl;
    	}
    	return self::$goods;
    }

    public function goodsUrl($url) {
    	return GOODS_MEILISHUO_API . $url;
    }

    // virus
    public function virusUrl($url) {
        return VIRUS_MEILISHUO_API . $url;
    }

    // snake
    public function snakeUrl($url) {
        return SNAKE_MEILISHUO_API . $url;
    }

    // doota
    public function dootaUrl($url) {
        return DOOTA_MEILISHUO_API . $url;
    }
    // 获取频道页列表
    /**
     * cata: 参数
     *           case 1: return "11801";
     *           case 2: return "11803";
     *           case 3: return "11805";
     *           case 4: return "11807";
     *           case 5: return "11809";
     *           case 6: return "12313";
     *           default:
     *                   return "11801";
     *
     * sbase:
     *           0 默认 1 人气 2 折扣 3 价格 4 销量
     * frame/limit/page/gids/d/sort
     */
    public function getPoster($params = array()) {
        $url = "groupon/groupon_poster";
        // 判断是否在缓存中
        $cacheKey = md5($this->virusUrl($url).serialize($params));
        $cacheValue = Yii::app()->cache->get($cacheKey);
        if ($cacheValue) {
            return $cacheValue;
        }
        $output = self::virus()->post($this->virusUrl($url), $params);

        if (!is_array($output) || $output['http_code'] != 200) {
            return false;
        }

        $body = json_decode($output['body'], true);

        // 缓存数据
        Yii::app()->cache->set($cacheKey,$body, 120);

        return $body;
    }

    /**
     * 设置排期
     * @param unknown $params
     * @return array array('succ'=>0 | 1, 'msg'=>'');   0代表失败，  1代表成功
     */
    public function newSetCampaignInfo($params = array())
    {
        $url = "campaignprice/add_goods";

        $output = self::doota()->post($this->dootaUrl($url), $params);

        $twitterId = isset($params['twitter_id']) ? $params['twitter_id'] : 0;
        $grouponId = isset($params['campaign_id']) ? $params['campaign_id'] : 0;
        $user      = $this->user->name;
        $addTime   = date("Y-m-d H:i:s");
        $logUrl    = "/home/work/logs/tuan/schedule.setSchedule.log";
        $writeLogCon = "time：{$addTime} --- 用户：{$user} --- twitter_id：{$twitterId} --- groupon_id：{$grouponId} --- url：".$this->dootaUrl($url)." --- param：".json_encode($params)." --- result：".json_encode($output)." --- ".PHP_EOL."\r\n";

        if (!is_array($output)) {
            // 写入日志
            file_put_contents($logUrl, $writeLogCon, FILE_APPEND);

            return array('succ'=>0, 'msg'=>"推ID={$params['twitter_id']}服务campaignprice/add_goods调用失败".$output);
        }

        $http_code = $output['http_code'];
        if (!in_array($http_code, array('200', '400'))) {
            // 写入日志
            file_put_contents($logUrl, $writeLogCon, FILE_APPEND);

            return array('succ'=>0, 'msg'=>"推ID={$params['twitter_id']} newUpdateCampaignInfo exception http_code=$http_code 详细信息=" . json_encode($output));
        }

        $body = json_decode($output['body'], true);
        $error_code = isset($body['error_code']) ? $body['error_code'] : -1;
        if ($error_code == '20001' or $error_code == '200001') {

            // 写入日志
            file_put_contents($logUrl, $writeLogCon, FILE_APPEND);

            $_code = $this->newUpdateCampaignInfo($params, $params);
            if ($_code['succ'] == 0) {
                return $_code;
            }
            return $_code;
        }
        if ($error_code != 0) {
            // 写入日志
            file_put_contents($logUrl, $writeLogCon, FILE_APPEND);

            return array('succ'=>0, 'msg'=>"推ID={$params['twitter_id']} http_code=$http_code 错误campaignprice/add_goods:{$body['message']}");
        }

        return array('succ'=>1, 'msg'=>"接口调用成功");
    }

    public function newUpdateCampaignInfo($data, $where)
    {
        $update_data = array(
            //condition
            'twitter_id'    => $where['twitter_id'],
            'campaign_id'   => $where['campaign_id'],
            'campaign_type' => $where['campaign_type'],
            //update value
            'start_time'    => $data['start_time'],
            'end_time'      => $data['end_time'],
            'preheat_time'  => $data['preheat_time'],
            //'preheat_tag'   => $data['preheat_tag'],
            //'discount_type' => 2, //1,discount;2,price
            //'discount_off'  => $data['discount_off'],
            //'campaign_sku'  => 1,
        );
        if (isset($data['preheat_tag'])) {
            $update_data['preheat_tag'] = $data['preheat_tag'];
        }
        if (isset($data['discount_off'])) {
            $update_data['discount_off'] = $data['discount_off'];
        }
        if (isset($data['discount_type'])) {
            $update_data['discount_type'] = $data['discount_type'];
        }
        if (isset($data['campaign_sku'])) {
            $update_data['campaign_sku'] = $data['campaign_sku'];
        }
        // 设置活动库存
        if (isset($data['repertory']) && !empty($data['repertory']) && is_numeric($data['repertory'])) {
            $update_data['repertory'] = (int)$data['repertory'];
        }
        // 会员阶梯价折扣信息
        if (isset($data['vip_discount_info']) && $data['vip_discount_info']) {
            $update_data['vip_discount_info'] = (int)$data['vip_discount_info'];
        }

        $url = "campaignprice/update_goods";
        $update_result = self::doota()->post($this->dootaUrl($url), $update_data);

        // log
        $twitterId = $update_data['twitter_id'];
        $grouponId = $update_data['campaign_id'];
        $user      = $this->user->name;
        $addTime   = date("Y-m-d H:i:s");
        $logUrl    = "/home/work/logs/tuan/schedule.updateSchedule.log";
        $writeLogCon = "time：{$addTime} --- 用户：{$user} --- twitter_id：{$twitterId} --- groupon_id：{$grouponId} --- url：".$this->dootaUrl($url)." --- param：".json_encode($update_data)." --- result：".json_encode($update_result)." --- ".PHP_EOL."\r\n";


        if (!is_array($update_result)) {
            if ($update_result['http_code'] == 400) {
                // return 0;
                return array('succ'=>1, 'msg'=>'update success');
            }

            // 写入日志
            file_put_contents($logUrl, $writeLogCon, FILE_APPEND);

            return array('succ'=>0, 'msg'=>"推ID={$update_data['twitter_id']} 服务campaignprice/update_goods调用失败");
        }

        $body = json_decode($update_result['body'], true);
        if ($body['error_code'] > 0) {
            // 写入日志
            file_put_contents($logUrl, $writeLogCon, FILE_APPEND);

            return array('succ'=>0, 'msg'=>"推ID={$update_data['twitter_id']} 错误campaignprice/update_goods:{$body['message']}");
        }
        return array('succ'=>1, 'msg'=>'success');
    }

    /**
     * 删除互斥表信息
     * @param array $where
     */
    public function newDeleteCampaignInfo($where)
    {
        $update_data = array(
            //condition
            'twitter_id'    => $where['twitter_id'],
            'campaign_id'   => $where['campaign_id'],
            'campaign_type' => $where['campaign_type'],
            //update value
            'start_time'    => $where['start_time'] ? $where['start_time'] : date('Y-m-d H:i:s', time()),
            'end_time'      => $where['end_time'] ? $where['end_time'] : date('Y-m-d H:i:s', time()),
        );
        $url = "campaignprice/update_goods";
        $update_result = self::doota()->post($this->dootaUrl($url), $update_data);

        // log
        $twitterId = $update_data['twitter_id'];
        $grouponId = $update_data['campaign_id'];
        $user      = $this->user->name;
        $addTime   = date("Y-m-d H:i:s");
        $logUrl    = "/home/work/logs/tuan/schedule.cancelSchedule.log";
        $logUrlAccess    = "/home/work/logs/tuan/schedule.cancelSchedule.access.log";
        $writeLogCon = "time：{$addTime} --- 用户：{$user} --- twitter_id：{$twitterId} --- groupon_id：{$grouponId} --- url：".$this->dootaUrl($url)." --- param：".json_encode($update_data)." --- result：".json_encode($update_result)." --- ".PHP_EOL."\r\n";

        // 写日志
        file_put_contents($logUrlAccess, $writeLogCon, FILE_APPEND);

        if (!is_array($update_result) || $update_result['http_code'] != 200) {
            $err_body = json_decode($update_result['body'], true);
            if ($update_result['http_code'] == 400) {
                if ($err_body['error_code'] == 0) {
                    return array('succ'=>1, 'msg'=>'update success');
                }
            }

            // 写入日志
            file_put_contents($logUrl, $writeLogCon, FILE_APPEND);

            return array('succ'=>0, 'msg'=>"推ID={$update_data['twitter_id']} 服务campaignprice/update_goods调用失败:{$update_result['http_code']}:{$err_body['message']}");
        }
        $body = json_decode($update_result['body'], true);
        if ($body['error_code'] > 0) {
            // 写入日志
            file_put_contents($logUrl, $writeLogCon, FILE_APPEND);

            return array('succ'=>0, 'msg'=>"推ID={$update_data['twitter_id']} 错误campaignprice/update_goods:{$body['message']}");
        }

        return array('succ'=>1, 'msg'=>'success~', 'con'=>$update_result);
    }

    /**
     * 检查商品是否在互斥表
     * @param int $twitter_id
     * @param int $event_id    活动id
     * @param float $off_price 团购价格
     * @return array
     */
    public function checkGoods($twitterId, $eventId, $offPrice, $startTime='', $endTime='')
    {
        $logfile = "/tmp/tuanht.check_goods.log";
        $eventId = intval($eventId);
        if(empty($startTime)) {
        	$startTime = date('Y-m-d H:i:s', time() + 60);
        }
        if (empty($endTime)) {
        	$endTime = date('Y-m-d H:i:s', time() + 5*24*60*60);
        }

        $eventInfo = $this->event->getEventInfo($eventId);
        if ($eventInfo) {
            !empty($eventInfo['start_time']) && $startTime = date('Y-m-d H:i:s', $eventInfo['start_time']);
            !empty($eventInfo['end_time']) && $endTime = date('Y-m-d H:i:s', $eventInfo['end_time']);
        }

        $params = array(
            'twitter_id'        => $twitterId,
            'start_time'        => $startTime,
            'end_time'          => $endTime,
            'campaign_type'     => 2,
            'discount_type'     => 2,
            'mob_discount_type' => 2,
            'discount_off'      => $offPrice,
            'mob_discount_off'  => $offPrice,
        );

        $url = "campaignprice/check_goods";
        $re  = self::doota()->post($this->dootaUrl($url), $params);

        $body = json_decode($re['body'], true);

        $error_code = isset($body['error_code']) ? $body['error_code'] : -100;

        $message = $body['message'] ? $body['message']: 'OK';

        return array('error_code'=>$error_code, 'message'=>$message);
    }

    /**
     * 检查商品是否在互斥表
     * @param array $params
     */
    public function checkGoodsNew($where) {

    	if (!$where) {
    		return false;
    	}

    	$params = array(
    			'twitter_id'        => $where['twitter_id'],
    			'start_time'        => $where['start_time'],
    			'end_time'          => $where['end_time'],
    			'campaign_type'     => $where['campaign_type'],
    			'discount_type'     => 2,
    			'mob_discount_type' => 2,
    			'discount_off'      => $where['discount_off'],
    			'mob_discount_off'  => $where['discount_off'],
    			'discount_type'		=>	$where['discount_type'],
    	        'campaign_id'		=>	$where['campaign_id'],
    	);

    	$url = "campaignprice/check_goods";
    	$re  = self::doota()->post($this->dootaUrl($url), $params);

    	// log
    	$twitterId = $where['twitter_id'];
    	$grouponId = $where['campaign_id'];
    	$user      = $this->user->name;
    	$addTime   = date("Y-m-d H:i:s");
    	$logUrl    = "/home/work/logs/tuan/schedule.checkGoodsNew.log";
    	$writeLogCon = "time：{$addTime} --- 用户：{$user} --- twitter_id：{$twitterId} --- groupon_id：{$grouponId} --- url：".$this->dootaUrl($url)." --- param：".json_encode($where)." --- result：".json_encode($re)." --- ".PHP_EOL."\r\n";


    	$body = json_decode($re['body'], true);

    	$error_code = isset($body['error_code']) ? $body['error_code'] : -100;

    	$message = $body['message'] ? $body['message']: 'OK';

    	if ($error_code != 0) {
    	    // 写入日志
    	    file_put_contents($logUrl, $writeLogCon, FILE_APPEND);
    	}

    	return array('succ'=>$error_code, 'msg'=>$message);
    }


    /**
     * 更新排期
     * @param array $update_data
     * @param array $op_info  更新操作的用户信息，注意，crontab必须指定用户，否则会报错
     * @return array
     */
    public function updateSchedule($update_data, $op_info=array())
    {
        $url = "campaignprice/update_goods";
        $update_result = self::doota()->post($this->dootaUrl($url), $update_data);

        // log
        $twitterId = $update_data['twitter_id'];
        $grouponId = $update_data['campaign_id'];
        $user      = isset($op_info['user']) ? $op_info['user'] : $this->user->name;
        $addTime   = date("Y-m-d H:i:s");
        $logUrl    = "/home/work/logs/tuan/schedule.updateSchedule.log";
        $writeLogCon = "time：{$addTime} --- 用户：{$user} --- twitter_id：{$twitterId} --- groupon_id：{$grouponId} --- url：".$this->dootaUrl($url)." --- param：".json_encode($update_data)." --- result：".json_encode($update_result)." --- ".PHP_EOL."\r\n";


        if (!is_array($update_result)) {
            if ($update_result['http_code'] == 400) {
                // return 0;
                return array('succ'=>1, 'msg'=>'update success');
            }

            // 写入日志
            file_put_contents($logUrl, $writeLogCon, FILE_APPEND);

            return array('succ'=>0, 'msg'=>"推ID={$update_data['twitter_id']} 服务campaignprice/update_goods调用失败");
        }

        $body = json_decode($update_result['body'], true);
        if ($body['error_code'] > 0) {
            // 写入日志
            file_put_contents($logUrl, $writeLogCon, FILE_APPEND);

            return array('succ'=>0, 'msg'=>"推ID={$update_data['twitter_id']} 错误campaignprice/update_goods:{$body['message']}");
        }
        return array('succ'=>1, 'msg'=>"success_.{$update_result['body']}");
    }


    /**
     * 检测商品是否可以报名团购
     * @param array $params
     * @return array
     */
    public function checkApply($params)
    {
        $shopId     = $params['shop_id'] ? (int)$params['shop_id'] : 0;
        $twitterId  = $params['twitter_id'] ? (int)$params['twitter_id'] : 0;
        $goodType   = $params['goods_type'] ? (int)$params['goods_type'] : 0;
        $eventId    = $params['event_id'] ? (int)$params['event_id'] : 0;
        if (!$shopId) {
            return array('succ'=>0, 'msg'=>'商铺id为空');
        }
        if (!$twitterId) {
            return array('succ'=>0, 'msg'=>'twitter_id为空');
        }

        $postData = array('shop_id' => $shopId, 'twitter_id' => $twitterId, 'goods_type' => $goodType, 'event_id' => $eventId);
        $url = "groupon/grouponcan_apply";
        $updateResult = self::virus()->post($this->virusUrl($url), $postData);
        if (!is_array($updateResult) || $updateResult['http_code'] != 200) {
            // 写入日志
            //file_put_contents($logUrl, $writeLogCon, FILE_APPEND);
            return array('succ'=>0, 'msg'=>"推ID={$postData['twitter_id']} 服务{$url}调用失败,{$updateResult['http_code']}");
        }
        $body = json_decode($updateResult['body'], true);
        if ($body['error_code'] > 0) {
            // 写入日志
            //file_put_contents($logUrl, $writeLogCon, FILE_APPEND);
            return array('succ'=>0, 'msg'=>"推ID={$postData['twitter_id']} 服务{$url}调用失败,{$body['error_code']}~~{$body['message']}");
        }
        if ($body['data']['code'] != 0) {
            return array('succ'=>0, 'msg'=>"调用失败，{$body['data']['code']}, {$body['data']['msg']['title']} - {$body['data']['msg']['content']}");
        }

        return array('succ'=>1, 'msg'=>'success~');
    }

    /**
     * 团购报名
     * @param array $params
     * @return array
     */
    public function tuanApply($params)
    {
        $url = "groupon/groupon_apply";
        $updateResult = self::virus()->post($this->virusUrl($url), $params);
        $twitterId = isset($params['twitter_id']) ? $params['twitter_id'] : 0;
        $user      = $this->user->name;
        $addTime   = date("Y-m-d H:i:s");
        $logUrl    = "/home/work/logs/tuan/apply.log";
        $writeLogCon = "time：{$addTime} --- 用户：{$user} --- twitter_id：{$twitterId} --- url：".$this->virusUrl($url)." --- param：".json_encode($params)." --- result：".json_encode($updateResult).PHP_EOL."\r\n";

        $body = json_decode($updateResult['body'], true);

        if (!is_array($updateResult) || $updateResult['http_code'] != 200) {
            // 写入日志
            file_put_contents($logUrl, $writeLogCon, FILE_APPEND);

            return array('succ'=>0, 'msg'=>"推ID={$params['twitter_id']} 服务{$url}调用失败,{$updateResult['http_code']},{$body['message']}");
        }
        if ($body['error_code'] > 0) {
            // 写入日志
            file_put_contents($logUrl, $writeLogCon, FILE_APPEND);

            return array('succ'=>0, 'msg'=>"推ID={$params['twitter_id']} 服务{$url}调用失败,{$body['error_code']}~~{$body['message']}");
        }
        if ($body['data']['code'] != 0) {
            // 写入日志
            file_put_contents($logUrl, $writeLogCon, FILE_APPEND);

            return array('succ'=>0, 'msg'=>"调用失败，{$body['data']['message']}");
        }

        return array('succ'=>1, 'msg'=>'success!');
    }


    /**
     * 获取分类详细信息
     * @param number/string $sort_ids    可传多个，用逗号分开
     * @param string $fields             可传多个，用逗号分开
     * @return array
     */
    public function getCategoryInfo($sort_ids, $fields='')
    {
        if (!$fields) {
            $fields = 'first_id,first_name,second_id,second_name,third_id,third_name,cid,name';
        }

        $url = 'platform/Get_item_cats';
        $params = array('cids'=>$sort_ids, 'fields'=>$fields);
        $result = self::virus()->post($this->virusUrl($url), $params);

        $body = json_decode($result['body'], true);

        if ($body['error_code'] != 0) {
            return array();
        }

        if (isset($body['data']['item_cats'])) {
            return $body['data']['item_cats'];
        }

        return array();
    }

    public function getGoodsInfo($tids, $order='sale_num') {

    	$fields =  array(
    			'goods_id',
    			'twitter_id',
    			'goods_title',
    			'goods_img',
    			'sale_num',
    			'goods_price',
    			'max_price',
    			'first_sort_id',
    			'campaign',
    			'campaign_phone_price_min',
    			'goods_img',
    			'repertory',
    			'gold'
    	);
    	$tids = implode(',', $tids);
    	$fields = implode(',', $fields);
    	$params = array ('twitter_id' => $tids, 'order'=>$order, 'fields' => $fields);
    	$url = 'goods/query_goods';

    	$result = self::goods()->post($this->goodsUrl($url), http_build_query($params));

    	$body = json_decode($result['body'], true);

    	if ($body['error_code'] != 0) {
    		return array();
    	}

    	if (isset($body['data'])) {
    		return $body['data'];
    	}

    	return array();

    }

}