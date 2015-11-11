<?php
/**
 * 自动样审通过操作和导入白名单商铺
 * @author linglingqi
 * @data 2015-07-06
 */
class AutoPassWhiteshopApplyCommand extends Command {

	/**
	 * /usr/local/bin/php /home/work/websites/tuan/protected/yiic.php autoPassWhiteshopApply --type=export
	 */
    public function main($args) {

    	if (isset($args['type']) && $args['type'] == 'export') {
        	$this->intoWhiteList();
    	} else {
    		$flag = '';
    		isset($args['flag']) && $flag = $args['flag'];
			$this->passSample($flag);
    	}
    	file_put_contents('/tmp/ll.log', '样审白名单店铺通过脚本：'. date('y-m-d H:i:s') ."\r\n", FILE_APPEND);
    }

    public function passSample($flag='') {

    	//获取所有样审店铺名单，判断是否存在，存在审核通过
    	$from = date('Y-m-d', strtotime("-360 day")) ." 00:00:00";
    	// 报名结束
    	$to = date("Y-m-d",strtotime("+1 day")) ." 00:00:00";
    	// trick for status

    	$sql = "select distinct master.id from shop_groupon_info master left join shop_groupon_goods_relation twitter
                on master.twitter_id = twitter.twitter_id left join brd_shop_groupon_shops_relation shop
                on master.shop_id = shop.shop_id where 1=1  and master.audit_status=30 and
    			master.create_time>='$from'  and master.create_time <= '$to'";

    	$db  = Yii::app()->sdb_brd_shop;
    	$list = $db->createCommand($sql)->queryColumn();


    	//添加判断，对活动ID为 1065和2052两个活动的避开
    	$sql_un = "select groupon_id from tuan_events_item_detail where event_id in ('1065','2052','2456')";
    	$ids = $db->createCommand($sql_un)->queryColumn();
    	foreach($list as $key=>$val) {
    		if (in_array($val, $ids)) {
    			unset($list[$key]);
    		}
    	}

    	$count = count($list);

    	$data = array();
		if ($count > 30) {
			$chunks = array_chunk($list, 30);
			foreach($chunks as $chunk) {
				$tmp = $this->audit->getTwitterDetail($chunk);
				$data = array_merge($data, $tmp);
			}
		} else {
			$data = $this->audit->getTwitterDetail($list);
		}

		$white = new WhiteShopManager();
		$wcount = $white->getCount();
		$whtes = $white->showAll('', '', 0, $wcount);

		$good_ids = array();
		foreach ($data as $info) {
			$shop_id = $info['shop'];
			if (isset($whtes[$shop_id])) {
				$good_ids[] = $info['gid'];
			}
		}
		$connection = Yii::app()->db_brd_shop;
		$checkResult = 40;
		$comment = '该宝贝暂时无需寄样，我们接下来会进行排期；但质检部会对团购商品进行抽检，请务必注意您的货品质量，保障用户体验。谢谢！';
		foreach ($good_ids as $gval) {

			$values = '';
			$sql1 = "update shop_groupon_info set audit_status={$checkResult} where id={$gval}";

			$values .= " ($gval, $checkResult, '{$comment}', localtime(), 3195, 'system') ";

			$sql2 = "INSERT INTO shop_groupon_audit_comments (`gid`, `audit_status`, `audit_comments`, `audit_time`, `audit_user`, `audit_opname`) VALUES " . $values;

			$transaction=$connection->beginTransaction();
			try {
				$connection->createCommand($sql1)->execute();
				$connection->createCommand($sql2)->execute();

				$transaction->commit();

				Log::writeApplog('pass_succ', $gval);
			} catch(Exception $e) {
				// 如果有一条查询失败，则会抛出异常
				Log::writeApplog('pass_succ', $gval. var_export($e, true));
				$transaction->rollBack();
			}
		}
    }

    /**
     * 导入白名单商铺
     */
    public function intoWhiteList() {

    	$time = microtime();

    	$file = "/home/work/websites/tuan/file/shop_info.txt";
		$data = file_get_contents($file, "rw");

		$info = explode("\n", $data);
		$manage = new WhiteShopManager();
		$exist = $shop_ids = array();
		$i = $j = 0;
		foreach ($info as $key=>$val) {
			$arr = explode("\t", trim($val, '"'));
			$i ++;
			if (!$arr[0]) {
				unset($info[$key]);
			}
			$shop_id = trim($arr[0]);
			$tmp = $manage->getInfo($shop_id);
			if ($tmp) {
				$exist[] = $shop_id;
				file_put_contents('/home/work/websites/tuan/file/exist.log', var_export($val, true) ."\r\n", FILE_APPEND);
			} else {
				$j++;
				$shop_ids[] = $shop_id;
				$ret = $manage->add($shop_id, '戚玲玲');
				if ($ret) {
					file_put_contents('/home/work/websites/tuan/file/shop.log', var_export($val, true) ."\r\n", FILE_APPEND);
				}else  {
					file_put_contents('/home/work/websites/tuan/file/fail.log', var_export($val, true) ."\r\n", FILE_APPEND);
				}
			}
		}
		var_dump('total: '. $i ."; succ: ". $j);
		var_dump('用时： '. microtime()- $time);exit;

    }
}
?>
