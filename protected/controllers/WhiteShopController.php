<?php
/**
 * 白名单店铺管理
 * @author linglingqi@meilishuo.com
 * @version 2015-07-07
 */
class WhiteShopController extends Controller {

	/**
	 * 展现
	 */
	public function ActionList() {

		$request = Yii::app()->getRequest();
		$nowPage = $request->getQuery('nowPage', 1);
		$count = 20;
		$request = Yii::app()->getRequest();
		$id = $request->getQuery('id', '');
		$operate = $request->getQuery('operate', '');

		$manage = new WhiteShopManager();
		$shop = new ShopManager();

		$offset = ($nowPage-1) * $count;
		$list = $manage->showAll($id, $operate, $offset, $count);
		$total = $manage->getCount($id, $operate);

		$data = array();
		foreach($list as $val) {
			$shop_id = $val['shop_id'];
			$data[$shop_id] = $shop->getShopDetail($shop_id);
			$data[$shop_id]['operator'] = $val['operator'];
			$data[$shop_id]['time'] = $val['time'];
		}
		$pageInfo = Page::getPageInfo($total, $nowPage, $count);

		$this->render('white/shop.html', array('data'=>$data, 'total'=>$total, 'pageInfo'=>	$pageInfo, 'search_id'=>$id, 'operate'=>$operate));
	}

	/**
	 * 添加白名单
	 */
	public function ActionAdd() {

		$request = Yii::app()->getRequest();
		$ids = $request->getPost('ids_str', '');
		$shop_ids = explode("|", $ids);

		$user = Yii::app()->user;
		$username = $user->name;
		$manage = new WhiteShopManager();
		//先检测是否已经添加；再检测是否店铺存在，然后添加
		$tmp = $manage->getInfo($shop_ids);
		if ($tmp) {
			Json::fail('添加失败,店铺已添加');
		}

		$fail = array();
		$shop = new ShopManager();
		foreach($shop_ids as $id) {
			$exist_shop = $shop->getShopInfo($id);
			if (!$exist_shop) {
				$fail[] = $id;
			}
			$ret = $manage->add($id, $username);
		}

		if ($fail) {
			Json::fail('添加失败店铺ID'. implode(',', $fail));
		} else {
			Json::succ("添加成功");
		}
	}

	/**
	 * 删除
	 */
	public function ActionDel() {

		$request = Yii::app()->getRequest();
		$id = $request->getPost('id', 0);

		if (!$id) {
			Json::fail("删除失败，没有店铺ID");
		}

		$manage = new WhiteShopManager();
		$ret = $manage->del($id);
		if ($ret) {
			Json::succ("删除成功");
		}else {
			Json::fail("删除失败");
		}
	}
}