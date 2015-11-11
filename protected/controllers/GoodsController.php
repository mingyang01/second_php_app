<?php

class GoodsController extends Controller
{
    /**
     * 编辑商品
     */
    public function ActionEditGoods()
    {
        $request     = Yii::app()->request;
        $grouponId   = (int)$request->getParam('gid', 0);

        $grouponInfo = $this->goods->getGrouponInfo($grouponId);
        if (!$grouponInfo) {
            throwMessage('团购信息不存在','error');
        }
        // 获取活动报名信息
        $eventGrouponInfo = $this->event->getEventGrouponInfoByGid($grouponId);
        if ($eventGrouponInfo) {
            $plusDetail = array();
            if ($eventGrouponInfo['plus_detail']) {
                $plusDetail = json_decode($eventGrouponInfo['plus_detail'], true);
            }
            if ($plusDetail['sale_point']) {
                $grouponInfo['sale_point'] = $plusDetail['sale_point'];
            }
            if ($plusDetail['goods_color']) {
                $grouponInfo['goods_color'] = $plusDetail['goods_color'];
            }
        }

        $this->assign('imageSize', getimagesize(getImageUrl($grouponInfo['goods_image_pc'])));
        $this->assign('tuanInfo', $grouponInfo);
        $this->render('goods/edit.html');
    }

    /**
     * 保存编辑商品
     */
    public function ActionSaveGoods()
    {
        $request    = Yii::app()->request;
        $grouponId  = (int)$request->getPost('id', 0);

        $grouponInfo = $this->goods->getGrouponInfo($grouponId);
        if (!$grouponInfo) {
            throwMessage('团购信息不存在', 'error');
        }

        $goodsName       = htmlspecialchars($request->getPost('goods_name', ''));
        $goodsImage      = htmlspecialchars($request->getPost('goods_image', ''));
        $salePoint       = htmlspecialchars($request->getPost('sale_point', ''));
        $goodsColor      = htmlspecialchars($request->getPost('goods_color', ''));

        if (!$goodsName) {
            throwMessage('商品标题不可为空', 'error');
        }
        if (!$goodsName) {
            throwMessage('请上传图片', 'error');
        }

        $updateArr = array(
                'goods_name'        => $goodsName,
                'goods_image'       => $goodsImage,
                'goods_image_pc'    => $goodsImage,
                'goods_image_mob'   => $goodsImage,
        );

        $db_brd_shop = Yii::app()->db_brd_shop;
        $db_brd_shop->createCommand()->update(
                'shop_groupon_info',
                $updateArr,
                'id=:id',
                array(':id'=>$grouponId)
        );

        // 卖点信息，目前只有活动商品可以添加卖点信息
        if ($salePoint || $goodsColor) {
            // 获取活动报名信息
            $eventGrouponInfo = $this->event->getEventGrouponInfoByGid($grouponId);
            if ($eventGrouponInfo) {
                $plusDetail = json_decode($eventGrouponInfo['plus_detail'], true);
                if (!$plusDetail) {
                    $plusDetail = array();
                }
                // 颜色
                if ($goodsColor) {
                    $plusDetail['goods_color'] = $goodsColor;
                }
                // 卖点
                if ($salePoint) {
                    $plusDetail['sale_point'] = $salePoint;
                }

                $plusDetailJson = json_encode($plusDetail);
                $db_brd_shop->createCommand()->update(
                        'tuan_events_item_detail',
                        array('plus_detail'=>$plusDetailJson),
                        'id=:id',
                        array(':id'=>$eventGrouponInfo['id'])
                );
            }
        }

        throwMessage('修改成功', 'success');
    }

    /**
     * 上传图片
     */
    public function ActionUploadImage()
    {
        $imgAl = Yii::app()->image;
        $imgStr = $imgAl->uploadWebsiteImage($_FILES['uploaod_img']['tmp_name']);
        if (!imgStr) {
            output(array('succ'=>0, 'msg'=>'上传失败'));
        }
        $showImg = $imgAl->getWebsiteImageUrl($imgStr);

        output(array('succ'=>1, 'img'=>$showImg, 'path'=>$imgStr));
    }

    /**
     * 团购历史
     * @param unknown $gid
     */
    public function ActionGetAuditHistory($gid) {
        $db = Yii::app()->sdb_brd_shop;
        CActiveRecord::$db = $db;
        $models = AuditLog::model()->findAll(array(
                'condition'=>'gid=:gid',
                'params'=>array(':gid'=>$gid),
                'order'=>'audit_time',
        ));

        $result = array();
        $array = array();
        foreach($models as $model){
            $status = CheckTipsManager::$tipsTypeEnum[$model->audit_status];
            $array['status'] = $status;
            $array['comments'] = $model->audit_comments;
            $array['time'] = $model->audit_time;
            $array['user'] = $model->audit_opname;

            $result[] = $array;
        }
        $markup = $this->fetch('goods/auditHistory.html', array('data'=>$result));
        output(array('succ'=>1,'data'=>$markup));
    }

    /**
     *  团购历史
     * @param unknown $gid
     */
    public function ActionGetAuditAddress($gid) {
        $grouponInfo = $this->goods->getGrouponInfo($gid);
        if (!$grouponInfo) {
            output(array('succ'=>0, 'msg'=>'团购信息不存在'));
        }

        $shopInfo = $this->shop->getShopInfo($grouponInfo['shop_id']);
        if (!$shopInfo) {
            output(array('succ'=>0, 'msg'=>'商铺信息不存在'));
        }

        $markup = $this->fetch('goods/auditAddress.html', array('grouponInfo'=>$grouponInfo, 'shopInfo'=>$shopInfo));
        output(array('succ'=>1,'data'=>$markup));
    }
    /**
     *  比价工具
     * @param unknown $gid
     */
    public function ActionComparePrice($gid){
        $message = $this->ComparePrice->ComparePriceInfo($gid);
        $markup = $this->fetch('goods/compareprice.html', array('data'=>$message));
        output(array('succ'=>1,'data'=>$markup));
    }

    /**
     *  比价详情
     * @param $tid
     */
    public  function ActionComparePriceDetail($tid)
    {
        $results = $this->ComparePrice->showComparePriceDetail($tid);
        $this->render('goods/comparepricedetail.tpl',array('data'=>$results));
    }

    public function ActionSysComparePriceInfo($tid)
    {
        $message = $this->ComparePrice->ComparePriceInfo($tid);
        echo json_encode($message);
    }

}
?>