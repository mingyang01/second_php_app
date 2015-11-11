<?php
/**
 * 修改清仓活动2052活动的报名时间
 * @author linglingqi
 * @data 2015-08-27
 */
class QingcangUpdataEventCommand extends Command {

	/**
	 * /usr/local/bin/php /home/work/websites/tuan/protected/yiic.php QingcangUpdataEvent
	 */
    public function main($args) {

    	self::intoShopList();

    }

    /**
     * 导入白名单商铺
     */
    public function intoShopList() {

    	$time = microtime();

    	$sql1 = "select * from campaign_goods_info where aid=3712 and audit_status=4";
    	$sdb = Yii::app()->sdb_brd_shop;
    	$goods1 =  $sdb->createCommand($sql1)->queryAll();
    	$goods1 = ArrFomate::hashmap($goods1, 'twitter_id');

    	$sql2 = "select a.* from shop_groupon_info a,tuan_events_item_detail b where a.id=b.groupon_id and b.event_id=2502";
    	$sdb = Yii::app()->sdb_brd_shop;
    	$goods2 =  $sdb->createCommand($sql2)->queryAll();
    	$goods2 = ArrFomate::hashmap($goods2, 'twitter_id');

    	$tids = array_keys($goods2);
    	$i = $j = 0;

    	$uninsert = $updata = array();
    	foreach($goods1 as $tid=>$good) {

    		if(!in_array($tid, $tids) ) {
    			$uninsert[$tid] = $good;
    			$i++;
    		}elseif ($good['campaign_price'] != $goods2[$tid]['off_price']) {
    			$updata[$tid]['off_price'] = $good['campaign_price'];
    			$updata[$tid]['off_num'] = $good['campaign_off'];
    			$updata[$tid]['id'] = $goods2[$tid]['id'];
    			$updata[$tid]['twitter_id'] = $tid;
    			$j ++;
    		}

    	}
		$this->insertGoods($uninsert);
		$this->updatePrice($updata);
		var_dump("添加商品".$i);
		var_dump("修改商品". $j);
    }

    public function updatePrice($goods) {

    	if(!$goods) {
    		return true;
    	}
    	$db = Yii::app()->db_brd_shop;
    	$conn = $db->beginTransaction();
    	foreach ($goods as $tid=>$good) {
file_put_contents('/tmp/ll_1010_update.txt', $good['twitter_id'] ."\r\n", FILE_APPEND);
    		$sql = "update shop_groupon_info set off_price=".$good['off_price'].",off_num=".$good['off_num']." where id=".$good['id'];

    		$ret = $db->createCommand($sql)->execute();
    	}
    	//$conn->rollback();
    	$conn->commit();
    }

    public function insertGoods($goods) {

    	if (!$goods) {
    		return  true;
    	}
    	$util = new UtilManager();
    	$infos = $util->getGoodsInfo(array_keys($goods));

    	$h = 0;
    	foreach ($infos['list'] as $good) {
file_put_contents('/tmp/ll_1010_insert.txt', $good['twitter_id'] ."\r\n", FILE_APPEND);
    		$image = $good['goods_img'];
    		$off_price = $goods[$good['twitter_id']]['campaign_price'];
    		$off_num = $goods[$good['twitter_id']]['campaign_off'];
    		$grouponInfo = array(
    				'twitter_id' => $good['twitter_id'],
    				'goods_id' => $good['goods_id'],
    				'goods_name' => $good['goods_title'],
    				'goods_image' => $good['goods_img'],
    				'off_num' => $off_num,
    				'off_price' => $off_price,
    				'audit_status' => 50,
    				'create_time' => $goods[$good['twitter_id']]['create_time'],
    				'comments' => '从线上导入数据到清仓活动2502',
    				'tags' => 1,
    				'start_time' => strtotime('2015-11-12 00:00:00'),
    				'end_time' => strtotime('2015-11-10 20:00:00'),
    				'tracking_num' => '',
    				'express_name' => '',
    				'status' => 1,
    				'goods_image_mob' => self::getCustomPicUrl($image, 304, 425),
    				'goods_image_pc' => self::getCustomPicUrl($image, 229, 320),
    				'goods_type' => 2,
    				'shop_id' => $goods[$good['twitter_id']]['shop_id'],
    				'isshow_tag'=> 1,
    		);
     		$result = self::insertGrouponInfo($grouponInfo);

    		$eventInfo = array(
    				'event_id' => 2502,
    				'twitter_id' => $good['twitter_id'],
    				'goods_id' => $good['goods_id'],
    				'shop_id' => $goods[$good['twitter_id']]['shop_id'],
    				'op_time' => date('Y-m-d H:i:s'),
    				'groupon_id' => strval($result),
    		);
     		self::insertTuanEventInfo($eventInfo);
     		$h ++;
    	}
    	var_dump('增加商品'.$h);
    }

    /**
     * 添加团购活动表
     */
    public function insertTuanEventInfo($eventInfo, $ignore = FALSE) {
    	if (empty($eventInfo)) {
    		return -1;
    	}
    	if (!$eventInfo['twitter_id'] || !$eventInfo['event_id'] || !$eventInfo['shop_id'] || !$eventInfo['groupon_id']) {
    		return -2;
    	}
    	$tid = $eventInfo['twitter_id'];
    	$event_id = $eventInfo['event_id'];

    	$tuan_event_fields = array(
    			'id',   //团购id
    			'twitter_id', //推id
    			'event_id',
    			'goods_id', //宝贝id
    			'item_start_time',   //排期开始时间
    			'item_end_time',     //排期结束时间
    			'category',  // 类别
    			'area',  //
    			'rank',  //
    			'flags',  //
    			'status',  //
    			'op_time',  //最近更新时间
    			'shop_id',
    			'groupon_id',
    	);

    	$fields = array();
    	foreach ($eventInfo as $key => $value) {
    		if (in_array($key, $tuan_event_fields)) {
    			array_push($fields, $key);
    		}
    	}
    	$col = '(';
    	$values = "('";
    	foreach ($fields as $fieldKey) {
    		$col .= "`{$fieldKey}`,";
    		$sqlData[$fieldKey] = $eventInfo[$fieldKey];
    		//$values .= ":{$fieldKey},";
    	}
    	$col = rtrim($col, ',') . ')';
    	$values .= implode("','", $sqlData) . "')";

    	if ($ignore == TRUE) {
    		$sql = "INSERT IGNORE INTO tuan_events_item_detail {$col} VALUES {$values}";
    	} else {
    		$sql = "INSERT INTO tuan_events_item_detail {$col} VALUES {$values}";
    	}
    	file_put_contents('/tmp/ll_1010_event.txt', $sql ."\r\n", FILE_APPEND);
    	$db = Yii::app()->db_brd_shop;
    	$conn = $db->beginTransaction();
    	$result = $db->createCommand($sql)->execute();

    	if ($result == FALSE) {
    		file_put_contents('/tmp/ll_1010_event_fail.txt', $sql ."\r\n", FILE_APPEND);
    		$id = $result;
    	} else {
    		$id = $db->getLastInsertID();
    	}
    	//$conn->rollback();
    	$conn->commit();
    	return $id;
    }

    public function insertGrouponInfo($grouponInfo, $ignore = FALSE) {
    	if (empty($grouponInfo)) {
    		return FALSE;
    	}
    	$fields = array();
    	$groupon_fields = array(
    			'id',   //团购id
    			'twitter_id', //推id
    			'goods_id', //宝贝id
    			'goods_name', //团购时的商品名称
    			'goods_image', //团购时的图片
    			'off_num',      //优惠折扣数
    			'off_price',    //优惠后价格
    			'audit_status', //审核状态
    			'create_time',  //报名时间
    			'comments',     //备注
    			'start_time',   //排期开始时间
    			'end_time',     //排期结束时间
    			'tags',
    			'tracking_num',     //快递单号
    			'express_name',     //快递公司名称
    			'status',           //团购状态
    			'goods_image_mob',  //mob端图片
    			'goods_image_pc',   //pc端图片
    			'goods_type',
    			'last_update_time',
    			'op_type',
    			'op_time',
    			'cash_recved',
    			'weight_pc',
    			'weight_mob',
    			'shop_id',
    			'isshow_tag',
    	);
    	foreach ($grouponInfo as $key => $value) {
    		if (in_array($key, $groupon_fields)) {
    			array_push($fields, $key);
    		}
    	}
    	$col = '(';
    	$values = "('";
    	foreach ($fields as $fieldKey) {
    		$col .= "`{$fieldKey}`,";
    		$sqlData[$fieldKey] = $grouponInfo[$fieldKey];
    		//$values .= ":{$fieldKey},";
    	}
    	$col = rtrim($col, ',') . ')';
    	$values .= implode("','", $sqlData) . "')";
    	if ($ignore == TRUE) {
    		$sql = "INSERT IGNORE INTO shop_groupon_info  {$col} VALUES {$values}";
    	} else {
    		$sql = "INSERT INTO shop_groupon_info {$col} VALUES {$values}";
    	}

    	file_put_contents('/tmp/1010_goods.txt', $sql ."\r\n", FILE_APPEND);
    	$db = Yii::app()->db_brd_shop;
    	$conn = $db->beginTransaction();
    	$result = $db->createCommand($sql)->execute($sqlData);
    	if ($result == FALSE) {
    		file_put_contents('/tmp/1010_goods_fail.txt', $sql ."\r\n", FILE_APPEND);
    		$grouponId = $result;
    	} else {
    		$grouponId = $db->getLastInsertID();
    	}
    	//     	$conn->rollback();
    	$conn->commit();
    	return $grouponId;
    }

    public static function getCustomPicUrl($uri, $width, $height) {
    	$imgHash = 'meilishuonewyearhappy';
    	if (empty($uri)) {
    		return FALSE;
    	}
    	if (strpos($uri, 'http://') !== FALSE) {
    		$urlArgs = parse_url($uri);
    		$uri = ltrim($urlArgs['path'], '/');
    	}
    	$token = $width.$height.$imgHash;
    	$type = 's2';
    	$uri = ltrim($uri, '/');
    	$md5sum = md5($uri . $token);
    	$eightM = substr($md5sum, 0, 8);
    	$target = '/'.$uri . "_{$eightM}_{$type}";
    	$target .= "_{$width}_{$height}.jpg";
    	return $target;
    }

}
?>
