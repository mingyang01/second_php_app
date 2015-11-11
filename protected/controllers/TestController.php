<?php
class TestController extends Setup {
    public function filters() {
        return array(
            'accessControl',
        );
    }

    public function accessRules() {
        return array(
            array('allow',
                'users' => array('*'),
            ),
        );
    }

    public function actionIndex() {
        $act_id = 1065;
        $sql = "select tuan_events_item_detail.category, tuan_events_item_detail.twitter_id, tuan_events_item_detail.rank, shop_groupon_info.id tuanid, "
        . " tuan_events_item_detail.shop_id, shop_groupon_info.off_price, round(shop_groupon_info.off_price*100/shop_groupon_info.off_num,2) price,  goods_image, goods_name, shop_groupon_info.goods_id, "
        . " from_unixtime(tuan_events_item_detail.item_start_time + 60*60*tuan_events_item_detail.subtype) start_time "
        . " from tuan_events_item_detail "
        . " join shop_groupon_info on tuan_events_item_detail.groupon_id=shop_groupon_info.id "
        . " where shop_groupon_info.audit_status=50 and tuan_events_item_detail.category in (1, 10, 11) and tuan_events_item_detail.event_id={$act_id}"
        . " and shop_groupon_info.goods_type=2 "
        . " order by tuan_events_item_detail.rank desc";

        echo $sql;
    }

    public function actionRequest() {
        echo json_encode($this->auth->getRoleUsers("技术部-数据智能/roleadmin"));
    }

    public function actionCheck() {
        $db = yii::app()->sdb_eel;
        $auth = Yii::app()->authManager;

        $sql = "select a.action, b.item from developer_action a, developer_function b where a.functionid = b.id";
        $results = $db->createCommand($sql)->queryAll();
        foreach ($results as $key => $value) {
            echo "check " . $value['item'] . "=>" . $value['action'] . ":   " . $auth->hasItemChild($value['item'], $value['action']);
            echo "</br>";
            if (!$auth->hasItemChild($value['item'], $value['action'])) {
                $auth->addItemChild($value['item'], $value['action']);
                echo "<h1 style='color:red;'>Duang" . $value['item'] . $value['action'] . "</h1>";
            }
        }
        // $auth->addItemChild($item,$action);
    }

    public function actionImport() {
        $id = $_GET['id'];
        $role = $_GET['role'];

        if (isset($_GET['role']) && isset($_GET['id'])) {
            # code...
            $users = array();

            $db = yii::app()->sdb_eel;
            $auth = Yii::app()->authManager;
            $sql = "select user_id id from t_eel_admin_relation_user where role_id = $role";
            $results = $db->createCommand($sql)->queryAll();
            $map = $this->common->userMap();

            $function = $this->function->getFunction($id);
            $n = $function[0]['item'];
            $count = 0;
            foreach ($results as $key => &$value) {
                $s = $map[$value['id']];

                if ($this->auth->checkAccess($n, $s['id'])) {
                    $s['status'] = '已有';
                } else {
                    $s['status'] = '没有';
                    if (isset($_GET['import'])) {
                        $auth->assign($n, $s['id']);
                        $count++;
                    }
                }

                $users[] = $s;
            }

            if (empty($function)) {
                echo "function id not exist!";
                Yii::app()->end();
            }

            echo $this->render('test/import.tpl', array('id' => $id, 'role' => $role, 'users' => $users, 'function' => $function, 'count' => $count));
            Yii::app()->end();

        }

        echo $this->render('test/import.tpl', array('count' => $count));
    }

    // Test for 获取审核历史
    // http://localhost:8060/test/GetAuditHistory/tid/3412074857/gid/654263
    public function ActionGetAuditHistory($tid, $gid) {
        if (empty($tid)) {
            $results = array('code' => 0, 'msg' => '参数为空，非法操作');
            echo json_encode($results);
        }

        $results = $this->audit->getAuditHistory($tid, $gid);

        $results = array('code' => 1, 'data' => $results);

        echo json_encode($results);
    }

    // Test for 获取在线团购商品
    public function ActionGetOnlineTwitterList() {
        echo json_encode($this->twitter->getTwitterList(0));
        // echo json_encode(count($this->twitter->getTwitterListWithDate('2015-04-13', 0)));
    }

    public function ActionTest()
    {

        $db_brd_shop = Yii::app()->db_brd_shop;
        $sql = "update shop_groupon_info set audit_status=50 where id=1736856";
        $db_brd_shop->createCommand($sql)->execute();

        echo "aaaa";exit();

        $request = Yii::app()->getRequest();
        $limit = $request->getQuery('limit', '');

        $sdb_groupon = Yii::app()->sdb_groupon;
        $sql = "select * from t_groupon_log where resource_name='setSchedule' and is_succ=1 and add_time >= '2015-10-21 00:00:00' and add_time <= '2015-10-21 13:00:00'";
        $result = $sdb_groupon->createCommand($sql)->queryAll();

        $succNum = 0;
        $errNum  = 0;
        $errResault = "";
        $succResult = "";
        $sdb_brd_goods = Yii::app()->sdb_brd_goods;
        foreach ($result as $k=>$v) {
            $content = unserialize($v['content']);
            $where = "campaign_type='{$content['campaign_filter']['campaign_type']}' and campaign_id='{$content['campaign_filter']['campaign_id']}'";
            $sql = "select * from brd_goods_campaign_price where {$where} limit 1";
//p($sql);
            $goods_info = $sdb_brd_goods->createCommand($sql)->queryRow();
            if (!$goods_info) {
                $errNum++;
                $errResault .= "twitter_id:{$goods_info['twitter_id']}, campaign_type:{$content['campaign_filter']['campaign_type']}, campaign_id:{$content['campaign_filter']['campaign_id']}\r\n";
            } else {
                $succNum++;
                $succResult .= "twitter_id:{$goods_info['twitter_id']}, campaign_type:{$goods_info['campaign_type']}, campaign_id:{$goods_info['campaign_id']}, effective_time:{$goods_info['effective_time']}, invalid_time:{$goods_info['invalid_time']}\r\n";
            }
        }

        p($errNum, $succNum, $errResault, $succResult);
        exit();

        $succNum = 0;
        $errNum  = 0;
        $errResault = "";
        foreach ($result as $k=>$v) {
            $content = unserialize($v['content']);
            //@FIXME 设置互斥表 curl调用接口
            $setResault = $this->util->newSetCampaignInfo($content['campaign_filter']);
            if ($setResault['succ'] == 1) {
                $succNum++;
            } else {
                $errResault .= var_export($content['campaign_filter'], true)."  失败提示: ".$setResault['msg']."\r\n";
                $errNum++;
            }
        }

        p($succNum,$errNum, $errResault);


        exit();
        $arr = array('1'=>1,'2'=>2);
        var_export($arr, true);exit();

        $this->hotTuanListScript->run();
        exit();


        $url = 'http://virus.meilishuo.com/brdgoods/batch_goods_info';
        $params = array(
            /*'twitter_id' => array(
                '3829511845', '3746171131', '3841916283'
            ),
            */
                //'twitter_id' =>'3829511845,3746171131,3841916283'
                'twitter_id' => 3829511845,
        );

        $curl = Yii::app()->curl;
        $header = array('Meilishuo:uid:9;ip:183.60.189.52');
        $curl->setHeader($header);
        $output = $curl->post($url, $params);
        p('广州',$output);

        $curl = Yii::app()->curl;
        $header = array('Meilishuo:uid:9;ip:172.17.88.32');
        $curl->setHeader($header);
        $output = $curl->post($url, $params);
        p('北京',$output);
    }

     public function ActionRunAll()
     {
        $db_brd_shop   = Yii::app()->db_brd_shop;
        $db_brd_shop->createCommand()->update(
                'shop_groupon_info',
                array('audit_status'=>50),
                'id=:id',
                array(':id'=>1736856)
        );

        echo "aaaa";exit();
     }

    public function ActionRunallShop()
    {
        var_dump('hehe');
        exit();
        return true;
    }

    public function ActionBackUp()
    {
        exit('hello world');


        $event_id = 1995;

        $sql = "select t1.twitter_id,t1.groupon_id from tuan_events_item_detail t1 left join shop_groupon_info t2 on t1.groupon_id=t2.id where t2.create_time >= '2015-08-21 00:00:00' and t1.event_id={$event_id}";
        $sdb_brd_shop = Yii::app()->sdb_brd_shop;
        $result = $sdb_brd_shop->createCommand($sql)->queryAll();

        $sdb_brd_goods = Yii::app()->sdb_brd_goods;
        //$db_brd_shop  = Yii::app()->db_brd_shop;
        $succNum = 0;
        foreach ($result as $k=>$v) {
            $brd_goods_sql = "select * from brd_goods_info where twitter_id = {$v['twitter_id']} limit 1";
            $goods_result = $sdb_brd_goods->createCommand($brd_goods_sql)->queryRow();
            if ($goods_result && $goods_result['goods_img']) {
                $goods_image = $goods_result['goods_img'];
                $goods_image_pc   = Yii::app()->image->generateThumbUrl($goods_result['goods_img'], 's2', 222, 310);
                $goods_image_mob  = Yii::app()->image->generateThumbUrl($goods_result['goods_img'], 's2', 304, 425);

                $update_sql = "update shop_groupon_info set goods_image='{$goods_image}', goods_image_mob='{$goods_image_mob}', goods_image_pc='{$goods_image_pc}' where id={$v['groupon_id']}";

                //$update_result = $db_brd_shop->createCommand($update_sql)->execute();
                if ($update_result) {
                    $succNum++;
                }
            }
        }

        echo "共 ".count($result)."条数据,成功执行".$succNum;
    }
}