<?php
/**
 * 活动规则
 */

class EventRuleController extends Controller
{
    /**
     * 编辑活动规则
     */
    public function ActionEditEventRule()
    {
        $eventId = Yii::app()->request->getQuery('event_id', 0);
        if (!$eventId) {
            throwMessage('少年，请传入eventID', 'error');
        }
        if ($eventId == 1065) {
            $this->redirect("http://works.meiliworks.com//tuanht/qiang8_rule");
            exit();
        }
        $eventInfo     = $this->event->getEventInfo($eventId);
        if (!$eventInfo) {
            throwMessage('少年，活动不存在', 'error');
        }

        $sdb_groupon     = Yii::app()->sdb_groupon;
        $eventRuleInfo   = $this->event->getEventRuleInfo($eventId);
        if ($eventRuleInfo) {
            if ($eventRuleInfo['product_type']) {
                $eventRuleInfo['product_type'] = explode(",", $eventRuleInfo['product_type']);
            }
            if ($eventRuleInfo['price_range']) {
                $priceRange = explode("-", $eventRuleInfo['price_range']);
                $eventRuleInfo['price_range_1'] = $priceRange[0];
                $eventRuleInfo['price_range_2'] = $priceRange[1];
            }
            if ($eventRuleInfo['discount_range']) {
                $discountRange = explode("-", $eventRuleInfo['discount_range']);
                $eventRuleInfo['discount_range_1'] = $discountRange[0];
                $eventRuleInfo['discount_range_2'] = $discountRange[1];
            }
        }

        $eventInfo['detail'] = json_decode($eventInfo['detail'], true);
        $this->assign('eventInfo', $eventInfo);
        $this->assign('eventRuleInfo', $eventRuleInfo);

        $s_score = range(3.8, 4.9, 0.1);
        $this->assign('scoreEnum', $s_score);

        $this->render('event/editEventRuleInfo.html');
    }

    /**
     * 保存活动规则
     */
    public function ActionSaveEventRule()
    {
        $this->request  = Yii::app()->request; // 实例化 request 方法
        $eventId        = $this->request->getPost('event_id', 0);
        $shopIds        = $this->request->getPost('shop_ids', '');
        $shopScore      = $this->request->getPost('shop_score', '0');
        $productScore   = $this->request->getPost('product_score', '0');
        $productNum     = trim($this->request->getPost('product_num', 0));
        $repertoryLimit = trim($this->request->getPost('repertory_limit', ''));
        $productPic     = $this->request->getPost('product_pic', 0);
        $priceRange1    = $this->request->getPost('price_range_1', '');
        $priceRange2    = $this->request->getPost('price_range_2', '');
        $discountRange1 = $this->request->getPost('discount_range_1', '');
        $discountRange2 = $this->request->getPost('discount_range_2', '');
        // 拼配定向报名
        $productType    = $this->request->getPost('product_type', array());
        // 数据库存储的价格区间和折扣区间  $priceRange1-$priceRange2
        $priceRange     = "";
        $discountRange  = "";
        // 活动规则等待初审数量
        $shop_first_check_waits    = (int)$this->request->getPost('shop_first_check_waits', 0);
        // 商家等级
        $shop_level = (int)$this->request->getPost('shop_level', 0);

        if (!$eventId) {
            throwMessage('少年，请传入eventID', 'error');
        }

        $eventInfo     = $this->event->getEventInfo($eventId);
        if (!$eventInfo) {
            throwMessage('少年，活动不存在', 'error');
        }
        $eventRuleInfo = $this->event->getEventRuleInfo($eventId);
        if (!$eventRuleInfo) {
            throwMessage('少年，活动规则不存在', 'error');
        }

        if ($priceRange1 && $priceRange2 && $priceRange1 == $priceRange2) {
            throwMessage("少年，价格区间不能相同!!中不中!!", 'error');
        }
        if(($priceRange1 && $priceRange1<=0) ||($priceRange2 && $priceRange2<=0)){
            throwMessage("少年，价格不能小于0!!中不中!!", 'error');
        }
        if(($priceRange1 && !is_numeric($priceRange1)) ||($priceRange2 && !is_numeric($priceRange2))){
            throwMessage("少年，价格区间请输入数字!!中不中!!", 'error');
        }
        // 设置价格区间
        if ($priceRange1 && $priceRange2) {
            if ($priceRange1 < $priceRange2) {
                $priceRange = (string) $priceRange1 . "-" . (string) $priceRange2;
            } else {
                $priceRange = (string) $priceRange2 . "-" . (string) $priceRange1;
            }
        }

        if(($discountRange1 && $discountRange1 > 1) || ($discountRange2 && $discountRange2 >1) || ($discountRange1 && $discountRange1 <= 0) || ($discountRange2 && $discountRange2 <= 0)){
            throwMessage("少年，折扣不能大于1也不能为0!!中不中!!", 'error');
        }
        if(($discountRange1 && !is_numeric($discountRange1)) ||($discountRange2 && !is_numeric($discountRange2))){
            throwMessage("少年，折扣区间请输入数字!!中不中!!", 'error');
        }
        // 设置折扣区间
        if ($discountRange1 && $discountRange2) {
            if ($discountRange1 < $discountRange2) {
                $discountRange = (string) $discountRange1 . "-" . (string) $discountRange2;
            } else {
                $discountRange = (string) $discountRange2 . "-" . (string) $discountRange1;
            }
        }

        $eventRuleFilter = array();
        $eventRuleFilter['shop_ids'] = $shopIds;
        // productType接收过来为数组形式
        if ($productType && is_array($productType)) {
            $eventRuleFilter['product_type']    = implode(",", $productType);
        } else {
            $eventRuleFilter['product_type']    = '';
        }
        $eventRuleFilter['shop_score']          = $shopScore ? $shopScore : '0.0';
        if ($repertoryLimit) {
            $eventRuleFilter['repertory_limit'] = $repertoryLimit;
        } else {
            $eventRuleFilter['repertory_limit'] = 300;
        }
        $eventRuleFilter['product_score']       = $productScore ? $productScore : '0.0';
        $eventRuleFilter['product_num']         = $productNum ? $productNum : 0;
        $eventRuleFilter['product_pic']         = $productPic ? $productPic : 0;
        $eventRuleFilter['price_range']         = $priceRange;
        $eventRuleFilter['discount_range']      = $discountRange;

        // 其他规则
        $eventRuleFilter['detail'] = $eventRuleInfo['detail'];
        $eventRuleFilter['detail']['shop_first_check_waits'] = $shop_first_check_waits;
        $eventRuleFilter['detail']['shop_level']             = $shop_level;
        $eventRuleFilter['detail'] = json_encode($eventRuleFilter['detail']);

        // 活动规则
        //p($eventRuleFilter);exit();
        $db_groupon      = Yii::app()->db_groupon;
        $eventRuleResult = $db_groupon->createCommand()->update(
                't_groupon_event_ruler',
                $eventRuleFilter,
                'event_id=:event_id',
                array(':event_id'=>$eventId)
        );
        if (!$eventRuleResult) {
            throwMessage('活动规则更新失败了','error');
        }

        throwMessage('恭喜您，更新活动规则成功','success', '/event');
    }
}
?>