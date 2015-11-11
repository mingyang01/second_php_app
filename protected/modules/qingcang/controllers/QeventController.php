<?php
/**
 * 清仓活动管理
 * @author linglingqi@meilishuo.com
 * @version 2015-10-21
 */
class QeventController extends Controller {

	/**
	 * 保存标签信息
	 */
	public function ActionSaveSupriseTag() {

		$this->request              = Yii::app()->request;
		$event_id                   = (int)$this->request->getPost('event_id', 0);
		if (isset($_POST['tag_id'])) {
			$tag_id                = (int)$this->request->getPost('tag_id');
		} else {
			$tag_id                = "";
		}
		$shop_filter                = array();
		$shop_filter['tag_name']   = htmlspecialchars(trim($this->request->getPost('tag_name', '')));
		$shop_filter['tag_sort']   = (int)$this->request->getPost('tag_sort', 0);

		$event_info = $this->event->getEventInfo($event_id);
		if (!$event_info) {
			output(array('succ'=>0,'活动信息不存在'));
		}

		if ($event_info['detail']) {
			$detail = json_decode($event_info['detail'], true);
		} else {
			$detail = array();
		}

		if (isset($detail['tags'])) {
			$tags = $detail['tags'];
		} else {
			$tags = array();
		}

		if (is_numeric($tag_id) && array_key_exists($tag_id, $tags)) {
			$tags[$tag_id]['tag_name'] = $shop_filter['tag_name'];
			$tags[$tag_id]['tag_sort'] = $shop_filter['tag_sort'];
		} else {
			$tags[] = $shop_filter;
			$tag_id = count($tags) - 1;
		}

		$detail['tags'] = $tags;


		$db_brd_shop   = Yii::app()->db_brd_shop;
		//$update_sql    = "update tuan_events_list set detail='".addslashes(json_encode($detail))."' where event_id={$event_id}";
		//$update_result = $db_brd_shop->createCommand($update_sql)->execute();
		$update_filter = array('detail' => json_encode($detail));
		$update_result = $db_brd_shop->createCommand()->update('tuan_events_list', $update_filter, 'event_id=:event_id',array(':event_id'=>$event_id));

		output(array('succ'=>1, 'msg'=>'店铺标签成功', 'tag_id'=>$tag_id, $shop_filter, json_encode($detail)));
	}

	/**
	 * 活动添加商品
	 */
	public function ActionSaveEventGoods() {

		$shop_ids    = Yii::app()->request->getPost('shop_id', 0);
		$area_id        = (int)Yii::app()->request->getPost('area_id', 0);
		$area_sub       = (int)Yii::app()->request->getPost('area_sub', 0);
		$event_id       = (int)Yii::app()->request->getPost('event_id', 0);

		if (!$shop_ids) {
			output(array('succ'=>0, 'msg'=>'请传入shop_ids'));
		}
		if (!$event_id) {
			output(array('succ'=>0, 'msg'=>'请传入event_id'));
		}

		$shop_id_arr = explode(",", $shop_ids);
		//获取店铺下的商品ids
		$search = new SearchManager();
		$twitter_id_arr = $search->getShopTwitters($shop_id_arr, $event_id, 50);

		$db_brd_shop  = Yii::app()->db_brd_shop;
		$sdb_brd_shop = Yii::app()->sdb_brd_shop;

		$succ_num  = 0;
		$err_num   = 0;
		$succ_info = array();
		$rank_num  = 0;
		$err_result = '';
		foreach ($twitter_id_arr as $k=>$v) {
			// $eventGrouponInfo = $this->event->getEventGrouponInfo((int)$v,$event_id);

			$id = (int)$v['id'];
			$sql = "select t1.* from tuan_events_item_detail t1 join shop_groupon_info t2 on t1.groupon_id=t2.id where t2.id={$id} and t1.event_id={$event_id} and t2.audit_status=50 and t2.goods_type=2 order by t1.id desc limit 1";
			$eventGrouponInfo  = $sdb_brd_shop->createCommand($sql)->queryRow();

			if (!$eventGrouponInfo) {
				$err_num++;
				$err_result .= "twitter_id:".$v['twitter_id']." msg:商品不存在";
				continue;
			}
			// 如果已经被排期过则不能添加
			if ($eventGrouponInfo['category'] > 0) {
				$err_num++;
				$err_result .= "twitter_id:".$v['twitter_id']." msg:已经被活动排期过";
				continue;
			}

			// 判断grouponinfo
			$grouponInfo = $this->event->getGrouponInfo($eventGrouponInfo['groupon_id']);
			if (!$grouponInfo) {
				$err_num++;
				$err_result .= "twitter_id:".$v['twitter_id']." msg:团购商品不存在";
				continue;
			}
			if ($grouponInfo['audit_status'] != 50) {
				$err_num++;
				$err_result .= "twitter_id:".$v['twitter_id']." msg:商品不是排期成功状态";
				continue;
			}
			if ($grouponInfo['goods_type'] != 2) {
				$err_num++;
				$err_result .= "twitter_id:".$v['twitter_id']." msg:商品不是活动商品";
				continue;
			}

			$eventInfo = $this->event->getEventInfo($event_id);
			if (!$eventInfo) {
				$err_num++;
				$err_result .= "twitter_id:".$v['twitter_id']." msg:活动不存在";
				continue;
			}

			// 秒杀活动的category为1 status 为30 其他的category为2
			if ($eventInfo['status'] >= 30 && $eventInfo['status'] < 40) {
				$update_sql = "update tuan_events_item_detail set category='1', status='30', area='{$area_id}', area_sub='{$area_sub}' where id={$eventGrouponInfo['id']}";
			} else {
				$update_sql = "update tuan_events_item_detail set category='2',area='{$area_id}', area_sub='{$area_sub}' where id={$eventGrouponInfo['id']}";
			}

			$update_result = $db_brd_shop->createCommand($update_sql)->execute();

			if ($update_result) {
				$succ_num++;
				// 获取grouponInfo
				$grouponInfo = $this->event->getGrouponInfo($eventGrouponInfo['groupon_id']);
				if ($grouponInfo) {
					$grouponInfo['goods_image']    = Yii::app()->image->getWebsiteImageUrl(Yii::app()->image->generateThumbUrl($grouponInfo['goods_image'], 's6', '163', '200'));
					$grouponInfo['event_goods_id'] = $eventGrouponInfo['id'];
					$grouponInfo['origin_price']   = $grouponInfo['off_num'] + $grouponInfo['off_price'];
					$succ_info[] = $grouponInfo;
				}
			} else {
				$err_num++;
				$err_result .= "twitter_id:".$v['twitter_id']." msg:内部错误";
			}
		}

		output(array('succ'=>1, 'msg'=>'success', 'data'=>$succ_info, 'succ_num'=>$succ_num, 'err_num'=>$err_num, 'err_result'=>$err_result));
	}

	/**
	 * 活动下的店铺排序
	 * ids为逗号分隔的字符串
	 */
	public function ActionSaveEventGoodsSort() {

		$this->request = Yii::app()->request;
		$event_id = (int)$this->request->getPost('event_id', 0);
		$ids      = $this->request->getPost('ids', 0);
		if (!$ids) {
			output(array('succ'=>0, 'msg'=>'请选择要排序的店铺'));
		}
		if (!$event_id) {
			output(array('succ'=>0, 'msg'=>'请选择活动'));
		}


		$sql = "select event.id, event.twitter_id, groupon.shop_id";
		$sql .= " from tuan_events_item_detail as event, shop_groupon_info groupon
		where event.groupon_id=groupon.id and event_id={$event_id} AND category>0 and groupon.audit_status=50";

		if (isset($_POST['area'])) {
			$area = htmlspecialchars(trim($_POST['area']));
			$where .= " AND event.area={$area}";
		}
		if (isset($_POST['area_sub'])) {
			$area_sub = htmlspecialchars(trim($_POST['area_sub']));
			$where .= " AND event.area_sub={$area_sub}";
		}
		$idsArr = explode(",", $ids);
		$idsArr = array_unique($idsArr);
		if ($idsArr) {
			$where .= " AND event.shop_id in ('". implode("','", $idsArr) ."')";
		}

		$db_brd_shop   = Yii::app()->db_brd_shop;
		$where && $sql .= $where;
		$groupon_id = $db_brd_shop->createCommand($sql)->queryAll();
		$groupon_id = ArrFomate::hashmap($groupon_id, 'twitter_id');

		$new_where = " AND event_id={$event_id}";

		if (isset($_POST['area'])) {
			$area = htmlspecialchars(trim($_POST['area']));
			$new_where .= " AND area={$area}";
		}
		if (isset($_POST['area_sub'])) {
			$area_sub = htmlspecialchars(trim($_POST['area_sub']));
			$new_where .= " AND area_sub={$area_sub}";
		}

		$succ_num = 0;
		$err_num  = 0;
		$rank_arr = array_flip($idsArr);
		foreach ($groupon_id as $k=>$v) {
			$id = (int)$v['id'];
			$rank = (int)$rank_arr[$v['shop_id']];
			$update_sql  = "update tuan_events_item_detail set rank={$rank} where id='{$id}'";
			$update_sql .= $new_where;

			$update_result = $db_brd_shop->createCommand($update_sql)->execute();
			if ($update_result) {
				$succ_num++;
			} else {
				$err_num++;
			}
			$rank--;
		}

		output(array('succ'=>1, 'msg'=>'success', 'succ_num'=>$succ_num, 'err_num'=>$err_num));
	}
}
