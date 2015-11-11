<?php
/**
                   _ooOoo_
                  o8888888o
                  88" . "88
                  (| -_- |)
                  O\  =  /O
               ____/`---'\____
             .'  \\|     |//  `.
            /  \\|||  :  |||//  \
           /  _||||| -:- |||||-  \
           |   | \\\  -  /// |   |
           | \_|  ''\---/''  |   |
           \  .-\__  `-`  ___/-. /
         ___`. .'  /--.--\  `. . __
      ."" '<  `.___\_<|>_/___.'  >'"".
     | | :  `- \`.;`\ _ /`;.`/ - ` : | |
     \  \ `-.   \_ __\ /__ _/   .-` /  /
======`-.____`-.___\_____/___.-`____.-'======
                   `=---='
^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^
         佛祖保佑       永无BUG

 * EverydayRecommendController.php
 * 团购每日推荐
 * Author: ruidongsong@meilishuo.com
 * Date: 2015-9-21 下午5:21:42
 */

class EverydayRecommendController extends Controller
{
    private $event_id = 2411;
    public $time_list = array(
        '00'  => array('00:00:00', '08:00:00'),
        '08'  => array('08:00:00', '10:00:00'),
        '10' => array('10:00:00', '12:00:00'),
        '12' => array('12:00:00', '14:00:00'),
        '14' => array('14:00:00', '16:00:00'),
        '16' => array('16:00:00', '18:00:00'),
        '18' => array('18:00:00', '20:00:00'),
        '20' => array('20:00:00', '22:00:00'),
        '22' => array('22:00:00', '00:00:00'),
    );

    public function actionIndex()
    {
        $request = Yii::app()->getRequest();
        $date = $request->getQuery('date', date('Y-m-d'));

        $new_time_list = array();
        foreach ($this->time_list as $k=>$v) {
            $new_time_list[$k]['start_time'] = $date." ".$v[0];
            if ($v[1] == '00:00:00') {
                $new_time_list[$k]['end_time'] = date("Y-m-d", strtotime($date)+86400)." ".$v[1];
            } else {
                $new_time_list[$k]['end_time'] = $date." ".$v[1];
            }
            $new_time_list[$k]['goods_list'] = array();
        }

        $goods_list = $this->eventGoods->getEventGoodsList(array(
            'event_id'   => $this->event_id,
            'start_time' => strtotime($date."00:00:00"),
            'end_time'   => strtotime($date."00:00:00") + 3600*24,
        ));

        foreach ($goods_list as $k=>$v) {
            // 必败理由
            $event_groupon_info = $this->event->getEventGrouponInfoByGid($v['gid']);
            $plus_detail = json_decode($event_groupon_info['plus_detail'], true);
            if (!$plus_detail) {
                $plus_detail = array();
            }
            $v['sale_point'] = $plus_detail['sale_point'];

            // 分配到对应的时间段
            $hour = date("H", $v['start_time']);
            if (array_key_exists($hour, $new_time_list)) {
                $new_time_list[$hour]['goods_list'][] = $v;
            }
        }

        $this->assign('date', $date);
        $this->assign('event_id', $this->event_id);
        $this->assign('time_list', $new_time_list);
        $this->render('everyDay/everyday_recommend.html');
    }

    public function actionSaveEverydayRecommendGoods()
    {
        $request = Yii::app()->getRequest();

        $start_time = $request->getPost('start_time', '');
        $end_time   = $request->getPost('end_time', '');
        $twitter_id = $request->getPost('twitter_id', '');
        $sale_point = $request->getPost('sale_point', '');
        $goods_name = $request->getPost('goods_name', '');

        if (!$twitter_id) {
            output(array('succ'=>0, 'msg'=>'请传入twitter_id'));
        }
        if (!$sale_point) {
            output(array('succ'=>0, 'msg'=>'请传入必败理由'));
        }
        if (!preg_match("/^[0-9]{4}-[0-9]{2}-[0-9]{2} [0-9]{2}:[0-9]{2}:[0-9]{2}$/", $start_time)) {
            output(array('succ'=>0, 'msg'=>'少年，请填写正确的运行时间'));
        }
        if (!preg_match("/^[0-9]{4}-[0-9]{2}-[0-9]{2} [0-9]{2}:[0-9]{2}:[0-9]{2}$/", $end_time)) {
            output(array('succ'=>0, 'msg'=>'少年，请填写正确的结束时间'));
        }

        $event_groupon_info = $this->event->getEventGrouponInfo($twitter_id, $this->event_id);
        if (!$event_groupon_info) {
            output(array('succ'=>0, 'msg'=>'活动商品不存在'));
        }
        $groupon_info = $this->goods->getGrouponInfo($event_groupon_info['groupon_id']);
        if (!$groupon_info) {
            output(array('succ'=>0, 'msg'=>'团购信息不存在'));
        }

        // 排期
        $update_arr = array();
        if ($goods_name) {
            $update_arr['goods_name'] = $goods_name;
        }
        $setResult = $this->schedule->setSchedule($groupon_info['id'], strtotime($start_time), strtotime($end_time), $update_arr);
        if ($setResult['succ'] != 1) {
            output($setResult);
        }

        // 更新必败理由
        $plus_detail = json_decode($event_groupon_info['plus_detail'], true);
        if (!$plus_detail) {
            $plus_detail = array();
        }
        $plus_detail['sale_point'] = $sale_point;
        $plus_detail_json = json_encode($plus_detail);
        $db_brd_shop = Yii::app()->db_brd_shop;
        $db_brd_shop->createCommand()->update(
                'tuan_events_item_detail',
                array("plus_detail" => $plus_detail_json),
                'id=:id',
                array(':id'=>$event_groupon_info['id'])
        );

        output(array('succ'=>1, 'msg'=>'success'));
    }

}
?>