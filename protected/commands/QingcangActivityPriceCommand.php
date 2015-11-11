<?php
/**
 * 清仓活动商品价格修改脚本
 * @author linglingqi
 * @data 2015-08-27
 */
class QingcangActivityPriceCommand extends Command {

	/**
	 * /usr/local/bin/php /home/work/websites/tuan/protected/yiic.php QingcangActivityPrice
	 */
    public function main($args) {

    	self::getShoplist();

    }

	public function getShoplist() {

		$db = Yii::app()->sdb_brd_shop;
		$sql = "select t1.* from shop_groupon_info t1, tuan_events_item_detail t2 where t1.id=t2.groupon_id and t2.event_id=2400 order by shop_id desc";
		$result = $db->createCommand($sql)->queryAll();


		$wdb = Yii::app()->db_brd_shop;
		//获取活动下的所有商品信息；获取campagin中的价格；修改其价格
		$i =0;
		foreach($result as $good) {

			$csql = "select * from campaign_goods_info where aid=3456 and audit_status=4 and twitter_id='". $good['twitter_id'] ."'";
			$camp = $db->createCommand($csql)->queryRow();
			if ($camp) {
				$price = '';
				if ($camp['campaign_price']) {

					$price = $camp['campaign_price'];
					$usql = "update shop_groupon_info set off_price=$price where id=".$good['id'];

					$ret = $wdb->createCommand($usql)->execute();
					$str = $good['twitter_id'] ."\t：". $good['off_price']. "\t". $price;
					if ($ret) {
						file_put_contents('/tmp/ll_0911_upprice.txt', $str ."\r\n", FILE_APPEND);
					} else{
						file_put_contents('/tmp/ll_0911_fail.txt', $str ."\r\n", FILE_APPEND);
					}

					$i ++;
				}
			}

		}
		var_dump($i);exit;

    }

}
?>
