<?php
/**
 * 团购报名页面
 * author:ruidongsong
 */

class TakepartController extends Controller
{

    public function ActionIndex()
    {
        // $events = $this->event->getEvents();
        $type   = Yii::app()->request->getParam('type', '');

        $sql = "select event_id, event_name from tuan_events_list where end_time > unix_timestamp()  or event_id in (1065,1076) order by event_id asc";
        $sdb_brd_shop   = Yii::app()->sdb_brd_shop;
        $eventList      = $sdb_brd_shop->createCommand($sql)->queryAll();
        $events = array();
        foreach ($eventList as $k=>$v) {
            $events[$v['event_id']] = $v['event_name']." -- ".$v['event_id'];
        }

        $this->assign('searchFilter', $_GET);
        $this->assign('events', $events);

        $not_check_apply = Yii::app()->request->getParam('not_check_apply', 0);
        if ($not_check_apply != 1) {
            $not_check_apply = 0;
        }
        $this->assign('not_check_apply', $not_check_apply);

        //$this->render('takepart/index.html');
        $this->render('takepart/takepart.html');

        /*if ($type == 'more') {
            $this->render('takepart/takepartMore.html');
        } else {
            $this->render('takepart/index.html');
        }*/
    }

    public function ActionNewApply()
    {
        $type   = Yii::app()->request->getParam('type', '');

        $sql = "select event_id, event_name from tuan_events_list where end_time > unix_timestamp()  or event_id in (1065,1076, 2005) order by event_id asc";
        $sdb_brd_shop   = Yii::app()->sdb_brd_shop;
        $eventList      = $sdb_brd_shop->createCommand($sql)->queryAll();
        $events = array();
        foreach ($eventList as $k=>$v) {
            $events[$v['event_id']] = $v['event_name']." -- ".$v['event_id'];
        }

        $this->assign('searchFilter', $_GET);
        $this->assign('events', $events);

        $not_check_apply = Yii::app()->request->getParam('not_check_apply', 0);
        if ($not_check_apply != 1) {
            $not_check_apply = 0;
        }
        $this->assign('not_check_apply', $not_check_apply);

        $this->render('takepart/index.html');
    }

    /**
     * 根据tid获取信息
     */
    public function ActionGetTwitterInfo()
    {
        $twitter_id = (int)Yii::app()->request->getPost('twitter_id', 0);

        if (!$twitter_id) {
            output(array('succ'=>0, 'msg'=>'请传入twitter_id'));
        }

        $twitter_info = $this->goods->getTwitterInfo($twitter_id);

        if (!$twitter_info) {
            output(array('succ'=>0, 'msg'=>'商品不存在'));
        }

        $twitter_info['goods_image'] = getImageUrl($twitter_info['goods_img']);

        $markup = $this->fetch('takepart/twitter_info.html', array('twitter_info'=>$twitter_info));

        output(array('succ'=>1, 'msg'=>'success', 'data'=>$markup));
    }

    /**
     * 报名
     */
    public function ActionSaveTwitterInfo()
    {
        $useTimeBegin = microtime(true);

        $request        = Yii::app()->getRequest();
        $twitter_id     = (int)$request->getPost('twitter_id', 0);
        $event_id       = (int)$request->getPost('event_id', 0);
        $price          = floatval($request->getPost('price', ''));
        $not_check_apply = (int)$request->getPost('not_check_apply', 0);
        $goods_type      = 0;

        if (!$twitter_id) {
            output(array('succ'=>0, 'msg'=>'请传入twitter_id'));
        }
        if (!$price) {
            output(array('succ'=>0, 'msg'=>'请传入价格'));
        }

        $twitter_info = $this->goods->getTwitterInfo($twitter_id);
        if (!$twitter_info) {
            output(array('succ'=>0, 'msg'=>'商品不存在'));
        }

        if ($event_id) {
            $event_info = $this->event->getEventInfo($event_id);
            if (!$event_info) {
                output(array('succ'=>0, 'msg'=>'活动不存在'));
            }
            $goods_type = 2;
        }

        $shop_id = $twitter_info['shop_id'];

        // 检测是否可以报名
        if (!$not_check_apply) {
            $check_filter = array('shop_id' => $shop_id, 'twitter_id' => $twitter_id, 'goods_type' => $goods_type, 'event_id' => $event_id);
            $check_result = $this->util->checkApply($check_filter);
            if ($check_result['succ'] != 1) {
                output($check_result);
            }
        }

        $off_num = $this->countOffNum($twitter_info['goods_price'], $price);
        if ($off_num < 0 || $off_num > 100) {
            output(array('succ'=>0, 'msg'=>'折扣不正确'));
        }

        $apply_filter = array(
            'tid'           => $twitter_id,
            'shop_id'       => $shop_id,
            'goods_id'      => $twitter_info['goods_id'],
            'goods_image'   => $twitter_info['goods_img'],
            'goods_name'    => $twitter_info['goods_title'],
            'off_price'     => $price,
            'off_num'       => $off_num,
            'comments'      => '运营手动报名',
            'goods_type'    => $goods_type,
            'isshow_tag'    => 1,
        );

        if ($event_id) {
            //$apply_filter['goods_type'] = $goods_type;
            $apply_filter['event_id']   = $event_id;
        }

        // 每个类型的图片大小都不一样
        if (intval($event_id) == 0) {
            $apply_filter['goods_image'] = $twitter_info['goods_img'];
            $apply_filter['goods_image_mob'] = Yii::app()->image->generateThumbUrl($twitter_info['goods_img'], 's1', 304, 425);
            $apply_filter['goods_image_pc']  = Yii::app()->image->generateThumbUrl($twitter_info['goods_img'], 's1', 229, 320);

        } elseif (intval($event_id) != 0 && intval($event_id) != 1065 && intval($event_id) != 1487) {
            $apply_filter['goods_image'] = $twitter_info['goods_img'];
            $apply_filter['goods_image_mob'] = Yii::app()->image->generateThumbUrl($twitter_info['goods_img'], 's1', 304, 425);
            $apply_filter['goods_image_pc']  = Yii::app()->image->generateThumbUrl($twitter_info['goods_img'], 's1', 222, 310);

        } else {
            $apply_filter['goods_image'] = Yii::app()->image->generateThumbUrl($twitter_info['goods_img'], 's1', 340, 340);
            $apply_filter['goods_image_mob'] = Yii::app()->image->generateThumbUrl($twitter_info['goods_img'], 's1', 290, 290);
            $apply_filter['goods_image_pc']  = Yii::app()->image->generateThumbUrl($twitter_info['goods_img'], 's1', 300, 300);
        }

        $result = $this->util->tuanApply($apply_filter);

        $useTimeEnd = microtime(true);
        $useTime    = $useTimeEnd - $useTimeBegin;
        $logFiter = array(
            'user'          => $this->user->name,
            'name'          => '手动报名',
            'content'       => $result,
            'param'         => array(),
            'resource_name' => 'apply',
            'twitter_id'    => $twitter_id,
        );
        $logFiter['param']['param'] = $apply_filter;
        $logFiter['param']['post']  = $_POST;
        $logFiter['is_succ']  = $result['succ'];
        $logFiter['use_time'] =  number_format($useTime, 5);
        // 增加日志
        $this->tuanLog->addLog($logFiter);

        output($result);
    }

    function countOffNum($origin_price, $price) {
        return (string) number_format(($price * 10) / $origin_price, 1, '.', '') * 10;
    }
}
?>