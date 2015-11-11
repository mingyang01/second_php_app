<?php
/**
 * 清仓公共manage
 * @author linglingqi@
 * @version 2015-07-30
 */
class QcommManager extends Manager {


	const EVENT_ID = 2005;   //普通清仓对应的活动ID

	const QINGCANG_ICON = "http://d04.res.meilishuo.net/img/_o/ca/97/8bff47115d46d24190866648d37b_60_72.cg.png";
	const QINGCANG_SHOP_ICON = "http://d02.res.meilishuo.net/img/_o/92/b1/87b765a4b3e8ae33d47eb396694b_640_123.cg.jpg";

	public static $titles = array('店铺', '店铺ID', '分类', '区域', 'ka等级', '30天gmv', '7天gmv', '30天销售量', '7天销售量', '商家电话', '商家QQ', '审核状态', '审核时间'
    				, '商品名称', '推id', '商品goodID', '原价', '价格', '报名时间');
    public static $columns = array('shop_nick', 'shop_id', 'category', 'area', 'level', 'gmv_30', 'gmv_7', 'paid_goods_num_30', 'paid_goods_num_7'
    				, 'partner_tel', 'partner_qq', 'audit_status', 'start_time', 'name', 'tid', 'gid','origin', 'price', 'createTime');

	public static function getCheckReason($status) {


		$tips = new QcheckTipsManager();
		$reason = $tips->getAuditReason($status, 1);

		if ($reason) {
			return $reason;
		}

		if ($status == QcheckManager::STATUS_FIRST_PASS) {
			$reason = array(
					'商品质量很好，初审通过',
					'CS店家，初审通过'
			);
		} elseif ($status == QcheckManager::STATUS_FIRST_REFUS) {
			$reason = array(
					'质量不过关，初审不通过',
					'店家发货太慢，初审不通过',
					'店铺等级太低，不慎不通过',
			);
		} elseif ($status == QcheckManager::STATUS_SECOND_PASS) {
			$reason = array(
					'商品质量很好，复审通过',
					'靠谱店家，复审通过'
			);
		} elseif ($status == QcheckManager::STATUS_SECOND_REFUS) {
			$reason = array(
					'商品质量太差，复审不通过',
					'商品价格太高，复审不通过'
			);
		} elseif ($status == QcheckManager::STATUS_SCHEDULE_REFUS) {
			$reason = array(
					'该商品近期参加活动太多，不能排期',
					'改商品价格太高',
			);
		}elseif ($status == QcheckManager::STATUS_SCHEDULE_PASS) {
			$reason = array(
					'商品质量很好',
					'靠谱店家'
			);
		}

		$reuslt = array();
        foreach ($reason as  $key=>$val) {
            $reuslt[$key]['content'] = $val;
        }
        return $reuslt;
	}

	public static function exportGrouponListHtml($list, $titles, $columns, $title='清仓导出数据') {

		$tipsTypeEnum = QcheckManager::$tipsTypeEnum;
		foreach ($list as &$v) {

			$v['audit_status'] = $tipsTypeEnum[$v['audit_status']];
			if ($v['start_time']) {
				$v['start_time']   = date("Y-m-d H:i:s", $v['start_time']);
			} else {
				$v['start_time']   = '';
			}
		}

		$title = $title."_".date("Y-m-d").".xls";
		$comm = new CommonManager();
		$comm->exportHtml($titles, $columns, $list, $title);
	}

}
?>
