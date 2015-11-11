<?php
/**
 * 清仓已有数据同步脚本
 * @author linglingqi
 * @data 2015-08-27
 */
class QingcangDataCommand extends Command {

	/**
	 * /usr/local/bin/php /home/work/websites/tuan/protected/yiic.php QingcangData
	 */
    public function main($args) {

    	self::intoShopList();

    }

	public function getShoplist($file) {

    	$data = file_get_contents($file);
    	$lines = explode("\n", $data);
    	$shops = array();
		foreach($lines as $val) {
			if (trim($val)) {
				$shops[] = explode("\t", trim($val));
			}
		}
		$shop_ids = array();
		foreach ($shops as $sval) {
			$sid = trim($sval[1]);
			$shop_ids[$sid] = $sid;
		}
    	return $shop_ids;
    }

    /**
     * 导入白名单商铺
     */
    public function intoShopList() {

    	$time = microtime();

    	$ssql = "select * from t_acaleph_onsale_shop where act_id = 1503 order by sort asc,create_time desc";
    	$sdb = Yii::app()->sdb_acaleph;
    	$shop_list =  $sdb->createCommand($ssql)->queryAll();


    	$sql = "select * from t_dolphin_content_data where content_id = 7271 and password = 'mob_discount'";
    	$db = Yii::app()->sdb_dolphin;

    	$result = $db->createCommand($sql)->queryAll();
    	if (count($result) > 0) {
    		$result = $result[0];
    	}
    	$json_data = json_decode($result['data_json'],1);
    	$shops = $json_data['shops'];
    	$detail = array();

    	$i = 0;
    	foreach ($shops as  $skey=>  $sshop) {
    		$detail[$sshop['shop_id']]= $sshop;
    	}
    	$data = array();
    	$util = new UtilManager();
    	$i = $j = 0;
    	foreach ($shop_list as $key1=>$shop1){

    		if ($shop1['end_time'] != '2015-11-12 00:00:00' || $shop1['start_time'] != '2015-11-10 20:00:00') {
    			unset($shop_list[$key1]);
    		}
    	}

//     	$on_shop = self::getShoplist('/home/work/websites/tuan/protected/commands/file/old_shop.txt');
    	$shop_list = ArrFomate::hashmap($shop_list, 'shop_id');

    	$h=0;
    	foreach ($shop_list as $key=>$shop){

//     		if (!in_array($shop['shop_id'], $on_shop)) {
//     			$h++;
//     			var_dump($sshop);
//     			continue;
//     		}


    		if ($shop['shop_id'] !='111250' && $shop['shop_id']!='120775') {
    			continue;
    		}

    		$banner = '';
    		if (isset($detail[$shop['shop_id']])) {
    			$banner=$detail[$shop['shop_id']]['bg_img'];
    		}
			$data=array(
					'event_id'	=>  2502,
					'shop_id'	=>	$shop['shop_id'],
					'start_time'=>	$shop['start_time'],
					'end_time'	=>	$shop['end_time'],
					'mark'		=>	'http://d06.res.meilishuo.net/img/_o/42/c2/da172da9cae805492db0f9046857_60_70.cg.png',
					'title'		=> 	$shop['title'],
					'banner'	=>	json_encode(array('icon'=>$banner)),
					'updator'	=>	$shop['updator'],
					'create_time'=> $shop['create_time'],
					'sort_create'=>	$shop['sort'],
					'ext'	=>	'a:1:{s:3:"tab";a:1:{i:0;s:15:"美美豆抵扣";}}',
			);
			$tids = explode(',',$shop['twitter_ids']);
			$infos = $util->getGoodsInfo($tids);
			$h = 0;
			foreach ($infos['list'] as $good) {

				$image = $good['goods_img'];
				$off_num = (string) number_format(($good['campaign_phone_price_min'] * 10) / $good['max_price'], 1, '.', '') * 10;
				$grouponInfo = array(
		    			'twitter_id' => $good['twitter_id'],
		    			'goods_id' => $good['goods_id'],
		    			'goods_name' => $good['goods_title'],
		    			'goods_image' => $good['goods_img'],
		    			'off_num' => $off_num,
		    			'off_price' => $good['campaign_phone_price_min'],
		    			'audit_status' => 50,
		    			'create_time' => $shop['create_time'],
		    			'comments' => '从线上导入数据到清仓活动2502',
		    			'tags' => 1,
		    			'start_time' => strtotime($shop['start_time']),
		    			'end_time' => strtotime($shop['end_time']),
		    			'tracking_num' => '',
		    			'express_name' => '',
		    			'status' => 1,
		    			'goods_image_mob' => self::getCustomPicUrl($image, 304, 425),
		    			'goods_image_pc' => self::getCustomPicUrl($image, 229, 320),
		    			'goods_type' => 2,
		    			'shop_id' => $shop['shop_id'],
		    			'isshow_tag'=> 1,
    			);
				$result = self::insertGrouponInfo($grouponInfo);

				$eventInfo = array(
						'event_id' => 2502,
						'twitter_id' => $good['twitter_id'],
						'goods_id' => $good['goods_id'],
						'shop_id' => $shop['shop_id'],
						'op_time' => date('Y-m-d H:i:s'),
						'groupon_id' => strval($result),
				);
				self::insertTuanEventInfo($eventInfo);
				$j ++;
				$h ++;
			}
			self::insertShopList($data);
			var_dump($shop['shop_id'] ."店铺下面有商品". $h);
			$i ++;
    	}

		var_dump('total: '. $i ."个店铺; 商品: ". $j);
		var_dump('用时： '. microtime()- $time);exit;

    }

    public function insertShopList($data, $ignore=false) {

    	if (!$data) {
    		return false;
    	}

    	$shop_fields = array(
    			'event_id',
    			'shop_id',
    			'banner',
    			'title',
    			'mark',
    			'sort_create',
    			'start_time',
    			'end_time',
    			'create_time',
    			'updator',
    			'ext',
    	);

    	$fields = array();
    	foreach ($data as $key => $value) {
    		if (in_array($key, $shop_fields)) {
    			array_push($fields, $key);
    		}
    	}
    	$col = '(';
    	$values = "('";
    	foreach ($fields as $fieldKey) {
    		$col .= "`{$fieldKey}`,";
    		$sqlData[$fieldKey] = isset($data[$fieldKey]) ? $data[$fieldKey] : '';
    			//$values .= ":{$fieldKey},";
    	}

    	$col = rtrim($col, ',') . ')';
    	$values .= implode("','", $sqlData) . "')";
    	if ($ignore == TRUE) {
    		$sql = "INSERT IGNORE INTO t_groupon_online_shop {$col} VALUES {$values}";
   		} else {
   			$sql = "INSERT INTO t_groupon_online_shop {$col} VALUES {$values}";
   		}
   		file_put_contents('/tmp/ll_0910_shop.txt', $sql ."\r\n", FILE_APPEND);
   		$db = Yii::app()->db_groupon;
   		$conn = $db->beginTransaction();
   		$result = $db->createCommand($sql)->execute();
   		if ($result == FALSE) {
    		file_put_contents('/tmp/ll_0910_shop_fail.txt', $sql ."\r\n", FILE_APPEND);
    		$id = $result;
    	} else {
    		$id = $db->getLastInsertID();
    	}
    	//$conn->rollback();
     	$conn->commit();

    	return true;

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
    	file_put_contents('/tmp/ll_0910_event.txt', $sql ."\r\n", FILE_APPEND);
    	$db = Yii::app()->db_brd_shop;
    	$conn = $db->beginTransaction();
    	$result = $db->createCommand($sql)->execute();

    	if ($result == FALSE) {
    		file_put_contents('/tmp/ll_0910_event_fail.txt', $sql ."\r\n", FILE_APPEND);
    		$id = $result;
    	} else {
    		$id = $db->getLastInsertID();
    	}
    	//$conn->rollback();
     	$conn->commit();
    	return $id;
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

    	file_put_contents('/tmp/0910_goods.txt', $sql ."\r\n", FILE_APPEND);
    	$db = Yii::app()->db_brd_shop;
    	$conn = $db->beginTransaction();
    	$result = $db->createCommand($sql)->execute($sqlData);
    	if ($result == FALSE) {
    		file_put_contents('/tmp/0910_goods_fail.txt', $sql ."\r\n", FILE_APPEND);
    		$grouponId = $result;
    	} else {
    		$grouponId = $db->getLastInsertID();
    	}
//     	$conn->rollback();
     	$conn->commit();
    	return $grouponId;
    }

}
?>
