<?php
class eventController extends Controller
{
    public function ActionIndex()
    {
        //p($_GET);
        $eventData = $this->event->getEventList($_GET);

        //p($eventData);
        $this->assign('eventData', $eventData);

        $this->assign('pager', $eventData['pager']);
        $this->assign('searchFilter', $_GET);
        $this->assign('eventType', eventManager::$show_event_type_enum);
        $this->assign('eventDeleteType', eventManager::$show_event_delete_type_enum);

        $channel = Yii::app()->request->getQuery('channel');
        $this->assign('channel',$channel);

        $this->render('event/eventList.html');
    }

    public function ActionAddEvent()
    {
        $s_score = range(3.8, 4.9, 0.1);
        $this->assign('scoreEnum', $s_score);

        // 活动所属频道
        $this->assign('channel', Yii::app()->request->getQuery('channel'));

        $this->render('event/addEvent.html');
    }

    /**
     * 创建活动
     */
    public function ActionsaveAddEvent()
    {
        $useTimeBegin = microtime(true);

        $this->request  = Yii::app()->request; // 实例化 request 方法
        $eventName      = trim(htmlspecialchars($this->request->getPost('event_name', '')));
        $startTime      = $this->request->getPost('start_time', '');
        $endTime        = $this->request->getPost('end_time', '');
        $preheatTime    = $this->request->getPost('preheat_time', '');
        $tuanEventType  = $this->request->getPost('tuan_event_type', '');
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
        // 所属频道
        $channel        = $this->request->getPost('channel', 0);
        // 会员阶梯价格折扣
        $vip_discount_range = array();
        foreach (VipEventManager::$vip_level_map as $k=>$v) {
            $vip_discount = $this->request->getPost("{$k}_discount", 0);
            if ($vip_discount) {
                $vip_discount_range[$k] = $vip_discount;
            }
        }
        // 获取可以够用户等级
        $user_limit_arr = $this->request->getPost('user_limit', array());

        // 判断活动是否存在
        $sdb_brd_shop  = Yii::app()->sdb_brd_shop;
        $searchSql = "select * from tuan_events_list where event_name='{$eventName}' limit 1";
        $eventInfo     = $sdb_brd_shop->createCommand($searchSql)->queryRow();
        if ($eventInfo) {
            throwMessage("活动名称：{$eventName} 已存在", 'error');
        }

        if (!preg_match("/^[0-9]{4}-[0-9]{2}-[0-9]{2} [0-9]{2}:[0-9]{2}$/", $startTime)) {
            throwMessage("请填写正确的开始时间", 'error');
        }
        if (!preg_match("/^[0-9]{4}-[0-9]{2}-[0-9]{2} [0-9]{2}:[0-9]{2}$/", $endTime)) {
            throwMessage("请填写正确的结束时间", 'error');
        }
        if ($preheatTime && !preg_match("/^[0-9]{4}-[0-9]{2}-[0-9]{2} [0-9]{2}:[0-9]{2}$/", $preheatTime)) {
            throwMessage("请填写正确的预热时间", 'error');
        }
        // 设置时间为时间戳 数据库存储为时间戳
        $startTime = strtotime($startTime.":00");
        $endTime   = strtotime($endTime.":00");
        if ($preheatTime) {
            $preheatTime = strtotime($preheatTime.":00");
        } else {
            $preheatTime = $startTime - 24*60*60;
        }
        if ($endTime < $startTime) {
            throwMessage("结束时间不可以小于开始时间", 'error');
        }
        if (!array_key_exists($tuanEventType, EventManager::$evnetTypeEnum)) {
            throwMessage("请填选择正确的活动类型", 'error');
        }
        if ($priceRange1 && $priceRange2 && $priceRange1 == $priceRange2) {
            throwMessage("价格区间不能相同!!中不中!!", 'error');
        }
        if(($priceRange1 && $priceRange1<=0) ||($priceRange2 && $priceRange2<=0)){
            throwMessage("价格不能小于0!!中不中!!", 'error');
        }
        if(($priceRange1 && !is_numeric($priceRange1)) ||($priceRange2 && !is_numeric($priceRange2))){
            throwMessage("价格区间请输入数字!!中不中!!", 'error');
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
            throwMessage("折扣不能大于1也不能为0!!中不中!!", 'error');
        }
        if(($discountRange1 && !is_numeric($discountRange1)) ||($discountRange2 && !is_numeric($discountRange2))){
            throwMessage("折扣区间请输入数字!!中不中!!", 'error');
        }
        // 设置折扣区间
        if ($discountRange1 && $discountRange2) {
            if ($discountRange1 < $discountRange2) {
                $discountRange = (string) $discountRange1 . "-" . (string) $discountRange2;
            } else {
                $discountRange = (string) $discountRange2 . "-" . (string) $discountRange1;
            }
        }
        // 判断频道
        if (!array_key_exists($channel, EventManager::$channelMap)) {
            throwMessage("请选择正确的频道", 'error');
        }

        $eventCreateFilter = array();
        $eventCreateFilter['event_name']        = $eventName;
        $eventCreateFilter['start_time']        = $startTime;
        $eventCreateFilter['end_time']          = $endTime;
        $eventCreateFilter['preheat_time']      = $preheatTime;
        $eventCreateFilter['status']            = $tuanEventType;
        $eventCreateFilter['op_time']           = date("Y-m-d H:i:s");
        // @FIXME 新增交易类型
        $eventCreateFilter['business_type']     = EventManager::$eventBusinessTypeMap[$tuanEventType];
        // @FIXME 新增所属频道
        $eventCreateFilter['channel']           = $channel;
        // 添加页面没有
        $eventCreateFilter['join_start_time']   = "0000-00-00 00:00:00";
        $eventCreateFilter['join_end_time']     = "0000-00-00 00:00:00";
        $eventCreateFilter['title']             = '';
        $eventCreateFilter['key_words']         = '';
        $eventCreateFilter['bg_color']          = '';
        $eventCreateFilter['detail']            = '';
        $eventCreateFilter['banner_pc']         = '';
        $eventCreateFilter['banner_mob']        = '';
        $eventCreateFilter['description']       = '';

        if ($eventCreateFilter['business_type'] == 17) {
            if (!$vip_discount_range) {
                throwMessage('请选择折扣范围','error');
            }
            if (!$user_limit_arr) {
                throwMessage('请选择可购买用户等级','error');
            }
        }
        // 将会员阶梯价格折扣存入到detail字段
        $eventCreateFilter['detail']['vip_discount_range'] = $vip_discount_range;
        $eventCreateFilter['detail']['user_limit'] = $user_limit_arr;
        $eventCreateFilter['detail'] = json_encode($eventCreateFilter['detail']);

        $db_brd_shop = Yii::app()->db_brd_shop;
        $eventResult = $db_brd_shop->createCommand()->insert('tuan_events_list', $eventCreateFilter);
        if (!$eventResult) {
            throwMessage('系统出错了~活动没有创建成功','error');
        }
        // 获取自增id
        $eventId     = $db_brd_shop->getLastInsertID();
        if (!$eventId) {
            throwMessage('系统出错了~活动没有创建成功1','error');
        }

        // 活动规则
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
        // @FIXME 先插入event表再获取id
        $eventRuleFilter['event_id']            = $eventId;
        $eventRuleFilter['detail']['shop_first_check_waits'] = $shop_first_check_waits;
        $eventRuleFilter['detail']['shop_level']             = $shop_level;
        $eventRuleFilter['detail'] = json_encode($eventRuleFilter['detail']);
        // 活动规则
        //p($eventRuleFilter);exit();
        $db_groupon      = Yii::app()->db_groupon;
        $eventRuleResult = $db_groupon->createCommand()->insert('t_groupon_event_ruler', $eventRuleFilter);
        if (!$eventRuleResult) {
            throwMessage('活动规则创建失败了','error');
        }

        // 添加日志
        $useTimeEnd = microtime(true);
        $useTime    = $useTimeEnd - $useTimeBegin;
        $logFiter = array(
                'user'          => $this->user->name,
                'name'          => '创建活动',
                'content'       => array(),
                'param'         => array('event_params' => $eventCreateFilter, 'event_rule_params' => $eventRuleFilter),
                'resource_name' => 'tuan_events_list',
                'resource_id'   => $eventId,
                'is_succ'       => 1,
                'use_time'      => number_format($useTime, 5)
        );
        // 增加日志
        $this->tuanLog->addLog($logFiter);

        throwMessage('恭喜您，活动创建成功'.$eventName,'success', "/event?channel={$channel}");
    }

    /**
     * 活动增删商品
     */
    public function ActionEditEvent()
    {
        $eventId = Yii::app()->request->getQuery('event_id', 0);
        if (!$eventId) {
            throwMessage('请传入eventID', 'error');
        }

        $sdb_brd_shop  = Yii::app()->sdb_brd_shop;
        $searchSql = "select * from tuan_events_list where event_id='{$eventId}' limit 1";
        $eventInfo     = $sdb_brd_shop->createCommand($searchSql)->queryRow();
        if (!$eventInfo) {
            throwMessage('活动不存在', 'error');
        }

        $sdb_groupon     = Yii::app()->sdb_groupon;
        $grouponQuerySql = "select * from t_groupon_event_ruler where event_id='{$eventId}' limit 1";
        $eventRuleInfo   = $sdb_groupon->createCommand($grouponQuerySql)->queryRow();

        $eventInfo['detail'] = json_decode($eventInfo['detail'], true);
        $this->assign('eventInfo', $eventInfo);
        $this->assign('eventRuleInfo', $eventRuleInfo);

        //p($eventInfo, $eventRuleInfo);
        if ($eventInfo['event_id'] == 1065) {
            $this->redirect("http://works.meiliworks.com/tuanht/edit_activity?event_id={$eventId}");
            exit();
        } elseif ($eventInfo['status'] >= 40 && $eventInfo['status'] < 50) {
            $this->render('event/editGiftsEvent.html');
        } elseif ($eventInfo['status'] >= 30 && $eventInfo['status'] < 40) {
            $this->render('event/editSupriseEvent.html');
        } elseif ($eventInfo['status'] >= 80 && $eventInfo['status'] < 90) {
            $this->render('qingcang/event/editQingcangEvent.html');
        } else {
            $this->redirect("http://works.meiliworks.com/tuanht/edit_activity?event_id={$eventId}");
            exit();
            //$this->render('event/editEvent.html');
        }
    }

    /**
     * 保存精品抢先活动
     */
    public function ActionSaveGiftsEvent()
    {
        $this->request      = Yii::app()->request;
        $event_id           = (int)$this->request->getPost('event_id', 0);
        $event_name         = htmlspecialchars(trim($this->request->getPost('event_name', '')));
        $title              = htmlspecialchars(trim($this->request->getPost('title', '')));
        $top_banner_pc      = htmlspecialchars(trim($this->request->getPost('top_banner_pc', '')));
        $top_banner_mob     = htmlspecialchars(trim($this->request->getPost('top_banner_mob', '')));
        $focus_img_pc       = $this->request->getPost('focus_img_pc', array());
        $focus_img_link_pc  = $this->request->getPost('focus_img_link_pc', array());
        $focus_img_mob      = $this->request->getPost('focus_img_mob', array());
        $focus_img_link_mob = $this->request->getPost('focus_img_link_mob', array());
        if (!$event_id) {
            throwMessage('活动不存在', 'error');
        }
        if (!$event_name) {
            throwMessage('活动名称不可以为空', 'error');
        }

        $event_info = $this->event->getEventInfo($event_id);
        if (!$event_info) {
            throwMessage('活动信息不存在', 'error');
        }

        // 如果接收到的event_name与原来的不用则取查询新接收的event_name有没有
        if ($event_info['event_name'] != $event_name) {
            $sdb_brd_shop  = Yii::app()->sdb_brd_shop;
            $searchSql = "select * from tuan_events_list where event_name='{$event_name}' limit 1";
            $eventInfo     = $sdb_brd_shop->createCommand($searchSql)->queryRow();
            if ($eventInfo) {
                throwMessage("活动名称：{$event_name} 已存在", 'error');
            }
        }

        // 拼装聚焦图片-pc
        $new_focus_img_pc = array();
        if ($focus_img_pc && is_array($focus_img_pc)) {
            foreach ($focus_img_pc as $k=>$v) {
                if ($v) {
                    $new_focus_img_pc[] = array(
                        'path' => $v,
                        'link' => $focus_img_link_pc[$k]
                    );
                }
            }
        }
        // 拼装聚焦图片-mob
        $new_focus_img_mob = array();
        if ($focus_img_mob && is_array($focus_img_mob)) {
            foreach ($focus_img_mob as $k=>$v) {
                if ($v) {
                    $new_focus_img_mob[] = array(
                            'path' => $v,
                            'link' => $focus_img_link_mob[$k]
                    );
                }
            }
        }
        // detail数组，切记，不可以覆盖。。。
        if ($event_info['detail']) {
            $detail = json_decode($event_info['detail'], true);
        } else {
            $detail = array();
        }

        // 将新接收的数据放到detail数组中
        //if ($new_focus_img_pc) {
            $detail['focus_img_pc'] = $new_focus_img_pc;
        //}
        //if ($top_banner_pc) {
            $detail['top_banner_pc'] = $top_banner_pc;
        //}
        //if ($top_banner_mob) {
            $detail['top_banner_mob'] = $top_banner_mob;
        //}
            $detail['focus_img_mob'] = $new_focus_img_mob;

        $update_filter = array(
            'event_name' => $event_name,
            'title'      => $title,
            'detail'     => json_encode($detail)
        );

        $db_brd_shop   = Yii::app()->db_brd_shop;
        $update_result = $db_brd_shop->createCommand()->update('tuan_events_list', $update_filter, 'event_id=:event_id',array(':event_id'=>$event_id));

        throwMessage('更新成功', 'success', '/event/');
    }

    /**
     * 保存商铺信息
     */
    public function ActionSaveGiftsShop()
    {
        $this->request              = Yii::app()->request;
        $event_id                   = (int)$this->request->getPost('event_id', 0);
        if (isset($_POST['shop_id'])) {
            $shop_id                = (int)$this->request->getPost('shop_id');
        } else {
            $shop_id                = "";
        }
        $shop_filter                = array();
        $shop_filter['shop_name']   = htmlspecialchars(trim($this->request->getPost('shop_name', '')));
        $shop_filter['shop_desc']   = htmlspecialchars(trim($this->request->getPost('shop_desc', '')));
        $shop_filter['shop_img']    = htmlspecialchars(trim($this->request->getPost('shop_img', '')));
        $shop_filter['shop_shipai'] = (int)$this->request->getPost('shop_shipai', 0);
        $shop_filter['shop_youzhi'] = (int)$this->request->getPost('shop_youzhi', 0);
        $shop_filter['shop_sort']   = (int)$this->request->getPost('shop_sort', 0);
        $shop_filter['focus_img_mob']    = htmlspecialchars(trim($this->request->getPost('focus_img_mob', '')));

        $event_info = $this->event->getEventInfo($event_id);
        if (!$event_info) {
            output(array('succ'=>0,'活动信息不存在'));
        }

        if ($event_info['detail']) {
            $detail = json_decode($event_info['detail'], true);
        } else {
            $detail = array();
        }

        if (isset($detail['shops'])) {
            $shops = $detail['shops'];
        } else {
            $shops = array();
        }

        if (is_numeric($shop_id) && array_key_exists($shop_id, $shops)) {
            $shops[$shop_id] = $shop_filter;
        } else {
            $shops[] = $shop_filter;
            $shop_id = count($shops) - 1;
        }

        $detail['shops'] = $shops;


        $db_brd_shop   = Yii::app()->db_brd_shop;
        //$update_sql    = "update tuan_events_list set detail='".addslashes(json_encode($detail))."' where event_id={$event_id}";
        //$update_result = $db_brd_shop->createCommand($update_sql)->execute();
        $update_filter = array('detail' => json_encode($detail));
        $update_result = $db_brd_shop->createCommand()->update('tuan_events_list', $update_filter, 'event_id=:event_id',array(':event_id'=>$event_id));

        output(array('succ'=>1, 'msg'=>'店铺创建成功', 'shop_id'=>$shop_id, $shop_filter, json_encode($detail)));
    }

    /**
     * 保存标签信息
     */
    public function ActionSaveSupriseTag()
    {
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
     * 保存模块信息
     */
    public function ActionSaveSupriseModule()
    {
        $this->request              = Yii::app()->request;
        $event_id                   = (int)$this->request->getPost('event_id', 0);
        if (isset($_POST['module_id'])) {
            $module_id                = (int)$this->request->getPost('module_id');
        } else {
            $module_id                = "";
        }
        if (isset($_POST['tag_id'])) {
            $tag_id                = (int)$this->request->getPost('tag_id');
        } else {
            $tag_id                = "";
        }

        $shop_filter                  = array();
        $shop_filter['module_name']   = htmlspecialchars(trim($this->request->getPost('module_name', '')));
        $shop_filter['module_img']    = htmlspecialchars(trim($this->request->getPost('module_img', '')));
        $shop_filter['module_sort']   = (int)$this->request->getPost('module_sort', 0);
        $shop_filter['module_type']   = htmlspecialchars(trim($this->request->getPost('module_type', '')));

        if (!is_numeric($tag_id)) {
            output(array('succ'=>0, 'msg'=>'请先保存标签'));
        }
        if (!$shop_filter['module_type'] || !in_array($shop_filter['module_type'], array('text', 'img'))) {
            output(array('succ'=>0, 'msg'=>'保存失败~'));
        }
        if ($shop_filter['module_type'] == 'text' && !$shop_filter['module_name']) {
            output(array('succ'=>0, 'msg'=>'请填写模块名称'));
        }
        if ($shop_filter['module_type'] == 'img' && !$shop_filter['module_img']) {
            output(array('succ'=>0, 'msg'=>'请上传模块图片'));
        }

        $event_info = $this->event->getEventInfo($event_id);
        if (!$event_info) {
            output(array('succ'=>0, 'msg'=>'活动信息不存在'));
        }

        if ($event_info['detail']) {
            $detail = json_decode($event_info['detail'], true);
        } else {
            $detail = array();
        }

        if (isset($detail['tags'][$tag_id])) {
            $tag_info = $detail['tags'][$tag_id];
        } else {
            output(array('succ'=>0, 'msg'=>'模块信息不存在'));
        }

        if (isset($tag_info['modules'])) {
            $modules = $tag_info['modules'];
        } else {
            $modules = array();
        }
        if (is_numeric($module_id) && array_key_exists($module_id, $modules)) {
            $modules[$module_id] = $shop_filter;
        } else {
            $modules[] = $shop_filter;
            $module_id = count($modules) - 1;
        }

        $tag_info['modules']     = $modules;
        $detail['tags'][$tag_id] = $tag_info;


        $db_brd_shop   = Yii::app()->db_brd_shop;
        //$update_sql    = "update tuan_events_list set detail='".addslashes(json_encode($detail))."' where event_id={$event_id}";
        //$update_result = $db_brd_shop->createCommand($update_sql)->execute();
        $update_filter = array('detail' => json_encode($detail));
        $update_result = $db_brd_shop->createCommand()->update('tuan_events_list', $update_filter, 'event_id=:event_id',array(':event_id'=>$event_id));

        output(array('succ'=>1, 'msg'=>'保存模块成功', 'tag_id'=>$tag_id, 'module_id'=>$module_id, $shop_filter, json_encode($detail)));
    }


    /**
     * 保存精品抢先活动
     */
    public function ActionSaveSupriseEvent()
    {
        $this->request      = Yii::app()->request;
        $event_id           = (int)$this->request->getPost('event_id', 0);
        $event_name         = htmlspecialchars(trim($this->request->getPost('event_name', '')));
        $title              = htmlspecialchars(trim($this->request->getPost('title', '')));
        $top_banner_pc      = htmlspecialchars(trim($this->request->getPost('top_banner_pc', '')));
        $top_banner_mob     = htmlspecialchars(trim($this->request->getPost('top_banner_mob', '')));

        if (!$event_id) {
            throwMessage('活动不存在', 'error');
        }
        if (!$event_name) {
            throwMessage('活动名称不可以为空', 'error');
        }

        $event_info = $this->event->getEventInfo($event_id);
        if (!$event_info) {
            throwMessage('活动信息不存在', 'error');
        }

        // 如果接收到的event_name与原来的不用则取查询新接收的event_name有没有
        if ($event_info['event_name'] != $event_name) {
            $sdb_brd_shop  = Yii::app()->sdb_brd_shop;
            $searchSql = "select * from tuan_events_list where event_name='{$event_name}' limit 1";
            $eventInfo     = $sdb_brd_shop->createCommand($searchSql)->queryRow();
            if ($eventInfo) {
                throwMessage("活动名称：{$event_name} 已存在", 'error');
            }
        }

        // detail数组，切记，不可以覆盖。。。
        if ($event_info['detail']) {
            $detail = json_decode($event_info['detail'], true);
        } else {
            $detail = array();
        }

        // 将新接收的数据放到detail数组中
        //if ($top_banner_pc) {
        $detail['top_banner_pc'] = $top_banner_pc;
        //}
        //if ($top_banner_mob) {
        $detail['top_banner_mob'] = $top_banner_mob;
        //}

        $update_filter = array(
                'event_name' => $event_name,
                'title'      => $title,
                'detail'     => json_encode($detail)
        );

        $db_brd_shop   = Yii::app()->db_brd_shop;
        $update_result = $db_brd_shop->createCommand()->update('tuan_events_list', $update_filter, 'event_id=:event_id',array(':event_id'=>$event_id));

        throwMessage('更新成功', 'success', '/event/');
    }

    /**
     * 活动添加商品
     */
    public function ActionSaveEventGoods()
    {
        $twitter_ids    = Yii::app()->request->getPost('twitter_id', 0);
        $area_id        = (int)Yii::app()->request->getPost('area_id', 0);
        $area_sub       = (int)Yii::app()->request->getPost('area_sub', 0);
        $event_id       = (int)Yii::app()->request->getPost('event_id', 0);
        $repertory      = (int)Yii::app()->request->getPost('repertory', 0);

        if (!$twitter_ids) {
            output(array('succ'=>0, 'msg'=>'请传入twitter_id'));
        }
        if (!$event_id) {
            output(array('succ'=>0, 'msg'=>'请传入event_id'));
        }

        $twitter_id_arr = explode(",", $twitter_ids);

        $db_brd_shop  = Yii::app()->db_brd_shop;
        $sdb_brd_shop = Yii::app()->sdb_brd_shop;

        $succ_num  = 0;
        $err_num   = 0;
        $succ_info = array();
        $rank_num  = 0;
        $err_result = "";
        foreach ($twitter_id_arr as $k=>$v) {
            $eventGrouponInfo = $this->event->getEventGrouponInfo((int)$v,$event_id);
            if (!$eventGrouponInfo) {
                $err_num++;
                $err_result .= "twitter_id: {$v}   msg: 活动商品不存在";
                continue;
            }
            // 如果已经被排期过则不能添加
            if ($eventGrouponInfo['category'] > 0) {
                $err_num++;
                $err_result .= "twitter_id: {$v}   msg: 商品已被排期过";
                continue;
            }

            // 判断grouponinfo
            $grouponInfo = $this->event->getGrouponInfo($eventGrouponInfo['groupon_id']);
            if (!$grouponInfo) {
                $err_num++;
                $err_result .= "twitter_id: {$v}   msg: 团购商品不存在";
                continue;
            }
            if ($grouponInfo['audit_status'] != 40) {
                $err_num++;
                $err_result .= "twitter_id: {$v}   msg: 必须是等待排期的商品才可以排期哦";
                continue;
            }
            if ($grouponInfo['goods_type'] != 2) {
                $err_num++;
                $err_result .= "twitter_id: {$v}   msg: 该商品不是活动商品";
                continue;
            }

            $eventInfo = $this->event->getEventInfo($event_id);
            if (!$eventInfo) {
                $err_num++;
                $err_result .= "twitter_id: {$v}   msg: 活动不存在";
                continue;
            }
            if ($eventInfo['status'] >= 30 && $eventInfo['status'] < 40) {
                if (!$repertory) {
                    $err_num++;
                    $err_result .= "twitter_id: {$v}   msg: 该活动是秒杀活动，必须设置库存才可以排期";
                    continue;
                }
            }
            // 去操作
            // @FIXME 还差eventinfo
            //$setScheduleResault = $this->event->eventScheduleOneItem($grouponInfo, $eventGrouponInfo, $eventInfo);
            if ($repertory) {
                $setScheduleResault = $this->schedule->setSchedule($grouponInfo['id'], $eventInfo['start_time'], $eventInfo['end_time'], array(), array(), $repertory);
            } else {
                $setScheduleResault = $this->schedule->setSchedule($grouponInfo['id'], $eventInfo['start_time'], $eventInfo['end_time']);
            }
            if ($setScheduleResault['succ'] != 1) {
                $err_num++;
                $err_result .= "twitter_id: {$v}   msg: {$setScheduleResault['msg']}";
                continue;
            }

            // 更新活动报名信息
            //$update_sql = "update tuan_events_item_detail set category='2',area='{$area_id}',area_sub='{$area_sub}',rank='{$rank_num}' where id={$eventGrouponInfo['id']}";

            // 秒杀活动的category为1 status 为30 其他的category为2
            if ($eventInfo['status'] >= 30 && $eventInfo['status'] < 40) {
                // 如果是秒杀，设置库存
                $plusDetail = json_decode($eventGrouponInfo['plus_detail'], true);
                if (!$plusDetail) {
                    $plusDetail = array();
                }
                $plusDetail['total_limit'] = $repertory;
                $plusDetailJson = json_encode($plusDetail);

                $update_sql = "update tuan_events_item_detail set category='1', status='30', area='{$area_id}', area_sub='{$area_sub}', rank='{$rank_num}', plus_detail='{$plusDetailJson}' where id={$eventGrouponInfo['id']}";
            } else {
                $update_sql = "update tuan_events_item_detail set category='2',area='{$area_id}', area_sub='{$area_sub}', rank='{$rank_num}' where id={$eventGrouponInfo['id']}";

                // 更新shop_groupoon_info 大促统计表
                $bridgeSql = "update bridge_goods_info set audit_status=? where twitter_id=? and aid=?";
                $db_brd_shop->createCommand($bridgeSql)->execute(array(50, $eventGrouponInfo['twitter_id'], $eventGrouponInfo['event_id']));
            }

            $update_result = $db_brd_shop->createCommand($update_sql)->execute();

            if ($update_result) {
                $rank_num++;
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
                $err_result .= "twitter_id: {$v}   msg: 内部错误~";
            }
        }

        output(array('succ'=>1, 'msg'=>'success', 'data'=>$succ_info, 'succ_num'=>$succ_num, 'err_num'=>$err_num, 'err_result'=>$err_result));
    }

    /**
     * 还原活动
     */
    public function ActionRecoverEvent()
    {
        $eventId = Yii::app()->request->getQuery('event_id', 0);

        if (!$eventId) {
            output(array('succ'=>0, 'msg'=>'请传入要恢复的id'));
        }

        $r = $this->event->recoverEvent($eventId);
        //$r = 1;
        if ($r) {
            output(array('succ'=>1, 'msg'=>'success', $eventId));
        } else {
            output(array('succ'=>0, 'msg'=>'还原失败'));
        }
    }

    /**
     * 删除活动
     */
    public function ActionDeleteEvent()
    {
        $eventId = Yii::app()->request->getQuery('event_id', 0);

        if (!$eventId) {
            output(array('succ'=>0, 'msg'=>'请传入要恢复的id'));
        }

        // 判断是否已经被排期过
        $sdb_brd_shop = Yii::app()->sdb_brd_shop;
        $sql = "select t2.twitter_id, t2.id from tuan_events_item_detail t1 join shop_groupon_info t2 on t1.groupon_id=t2.id where t2.audit_status=50 and t1.event_id={$eventId}";
        $goodsList = $sdb_brd_shop->createCommand($sql)->queryAll();
        if ($goodsList) {
            output(array('succ'=>0, 'msg'=>'该活动已经有排期的商品，不可删除，请把排期商品删除后再来操作'));
        }

        $r = $this->event->deleteEvent($eventId);
        //$r = 1;
        if ($r) {
            output(array('succ'=>1, 'msg'=>'success', $eventId));
        } else {
            output(array('succ'=>0, 'msg'=>'删除失败'));
        }
    }

    /**
     * 编辑公告信息
     */
    public function ActionEditNotice()
    {
        $eventId = Yii::app()->request->getQuery('event_id', 0);

        if (!$eventId) {
            throwMessage("请传入event_id", 'error');
        }

        // 先查找活动信息
        $sql           = "select * from tuan_events_list where event_id={$eventId}";
        $sdb_brd_shop  = Yii::app()->sdb_brd_shop;
        $eventInfo     = $sdb_brd_shop->createCommand($sql)->queryRow();
        if (!$eventInfo) {
            throwMessage("活动不存在", 'error');
        }

        // 公告在detail里面
        $detail = json_decode($eventInfo['detail'], true);
        if (is_array($detail) && isset($detail['notice_id'])) {
            $noticeId = $detail['notice_id'];
        } else {
            $noticeId = 0;
        }

        // 如果有公告则获取公告信息
        if ($noticeId) {
            $noticeSql  = "select * from brd_shop_posts where id={$noticeId}";
            $noticeInfo = $sdb_brd_shop->createCommand($noticeSql)->queryRow();
            $this->assign('noticeInfo', $noticeInfo);
        }

        $this->assign('eventInfo', $eventInfo);

        $this->render('event/addNotice.html');
        //p( $noticeId,$eventInfo,$noticeInfo);
    }

    /**
     * 保存公告信息
     */
    public function ActionSaveNotice()
    {
        $sdb_brd_shop  = Yii::app()->sdb_brd_shop;
        $db_brd_shop   = Yii::app()->db_brd_shop;

        $id        = Yii::app()->request->getPost('id', 0);
        $eventId   = Yii::app()->request->getPost('event_id', 0);
        $status    = Yii::app()->request->getPost('status', '');
        $title     = Yii::app()->request->getPost('title', '');
        $author    = Yii::app()->request->getPost('author', '');
        $cateId    = Yii::app()->request->getPost('cate_id', 0);
        $ctime     = Yii::app()->request->getPost('ctime', '');
        $content   = Yii::app()->request->getPost('content', '');
        $utime     = date("Y-m-d H:i:s");

        if (!$eventId) {
            throwMessage('活动不存在', 'error');
        }
        // 先查找活动信息
        $eventSql   = "select * from tuan_events_list where event_id={$eventId}";
        $eventInfo  = $sdb_brd_shop->createCommand($eventSql)->queryRow();
        if (!$eventInfo) {
            throwMessage("活动不存在", 'error');
        }
        if (!array_key_exists($status, EventManager::$noticeStatusEnum)) {
            throwMessage('请选择正确的展示类型', 'error');
        }
        if (!$title) {
            throwMessage('请填写标题', 'error');
        }
        if (!array_key_exists($cateId, EventManager::$noticeCateIdEnum)) {
            throwMessage('请选择正确的分类', 'error');
        }
        if (!preg_match("/^[0-9]{4}-[0-9]{2}-[0-9]{2}$/", $ctime)) {
            throwMessage('请填写正确的竞猜结束时间', 'error');
        }

        $ctime .= " 00:00:00";
        $insertSql  = "insert into brd_shop_posts (`cate_id`, `title`, `content`, `author`, `status`, `ctime`, `utime`) values('{$cateId}', '{$title}', '{$content}', '{$author}', '{$status}', '{$ctime}', '{$utime}')";
        $updateSql  = "update brd_shop_posts set `cate_id`='{$cateId}', `title`='{$title}', `content`='{$content}', `author`='{$author}', `status`='{$status}', `ctime`='{$ctime}', `utime`='{$utime}' where `id`='{$id}'";


        // 有过有id是更新
        if ($id) {
            $noticeSql  = "select * from brd_shop_posts where id={$id}";
            $noticeInfo = $sdb_brd_shop->createCommand($noticeSql)->queryRow();
            if (!$noticeInfo) {
                throwMessage('公告信息不存在','error');
            }

            $result = $db_brd_shop->createCommand($updateSql)->execute();
            throwMessage('更新成功', 'success', '/event');
        // 如果没有是新增
        } else {
            $result = $db_brd_shop->createCommand($insertSql)->execute();
            if ($result) {
                $lastId = $db_brd_shop->getLastInsertID();
                if ($lastId) {
                    $detail     = json_decode($eventInfo['detail'], true);
                    if (!is_array($detail)) {
                        $detail = array();
                    }
                    $detail['notice_id'] = $lastId;
                    $jd                  = json_encode($detail);
                    $updateEventSql      = "update tuan_events_list set detail='{$jd}' where event_id={$eventId}";
                    $eventResult = $db_brd_shop->createCommand($updateEventSql)->execute();
                    throwMessage('添加成功', 'success', '/event');
                } else {
                    throwMessage('添加失败', 'error');
                }
            } else {
                throwMessage('添加失败', 'error');
            }
        }
    }


    /**
     * 活动商品排序
     * ids为逗号分隔的字符串
     */
    public function ActionSaveEventGoodsSort()
    {
        $this->request = Yii::app()->request;
        $event_id = (int)$this->request->getPost('event_id', 0);
        $ids      = $this->request->getPost('ids', 0);
        if (!$ids) {
            output(array('succ'=>0, 'msg'=>'请选择要排序的商品'));
        }
        if (!$event_id) {
            output(array('succ'=>0, 'msg'=>'请选择活动'));
        }

        $where = " AND event_id={$event_id}";

        if (isset($_POST['area'])) {
            $area = htmlspecialchars(trim($_POST['area']));
            $where .= " AND area={$area}";
        }
        if (isset($_POST['area_sub'])) {
            $area_sub = htmlspecialchars(trim($_POST['area_sub']));
            $where .= " AND area_sub={$area_sub}";
        }

        $db_brd_shop   = Yii::app()->db_brd_shop;
        $idsArr = explode(",", $ids);
        $succ_num = 0;
        $err_num  = 0;
        $rank = count($idsArr);
        foreach ($idsArr as $k=>$v) {
            $id = (int)$v;
            $update_sql  = "update tuan_events_item_detail set rank={$rank} where id={$id}";
            $update_sql .= $where;

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

    /**
     * 获取商品信息
     */
    public function ActionGetGrouponInfo()
    {
        $twitter_id = Yii::app()->request->getPost('twitter_id', 0);

        if (!$twitter_id) {
            output(array('succ'=>0, 'msg'=>'请传入twitter_id'));
        }

        $sdb_brd_shop = Yii::app()->sdb_brd_shop;
        $sql          = "select * from shop_groupon_info where twitter_id=$twitter_id order by id desc limit 1";
        $grouponInfo  = $sdb_brd_shop->createCommand($sql)->queryRow();

        if (!$grouponInfo) {
            output(array('succ'=>0, 'msg'=>'团购信息不存在'));
        }

        if (!$grouponInfo['audit_status'] > 50) {
            output(array('succ'=>0, 'msg'=>'团购信息未经过审核'));
        }

        if (!$grouponInfo['audit_status'] == 51) {
            output(array('succ'=>0, 'msg'=>'该商品已被排期'));
        }

        $grouponInfo['goods_image'] = Yii::app()->image->getWebsiteImageUrl(Yii::app()->image->generateThumbUrl($grouponInfo['goods_image'], 's6', '163', '200'));

        output(array('succ'=>1, 'msg'=>'success', 'data'=>$grouponInfo));
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
     * 编辑基本信息
     */
    public function ActionEditBasicInfo()
    {
        $eventId = Yii::app()->request->getQuery('event_id', 0);
        if (!$eventId) {
            throwMessage('请传入eventID', 'error');
        }
        if ($eventId == 1065) {
            throwMessage('秒杀信息不可被编辑', 'error');
        }
        $eventInfo     = $this->event->getEventInfo($eventId);
        if (!$eventInfo) {
            throwMessage('活动不存在', 'error');
        }

        $sdb_groupon     = Yii::app()->sdb_groupon;
        $grouponQuerySql = "select * from t_groupon_event_ruler where event_id='{$eventId}' limit 1";
        $eventRuleInfo   = $sdb_groupon->createCommand($grouponQuerySql)->queryRow();

        $eventInfo['detail'] = json_decode($eventInfo['detail'], true);
        $this->assign('eventInfo', $eventInfo);
        $this->assign('eventRuleInfo', $eventRuleInfo);

        if ($eventInfo['event_id'] == 1065) {
            throwMessage('秒杀信息不可被编辑', 'error');
        } elseif ($eventInfo['status'] < 30 || $eventInfo['status'] >= 50 ) {
            $this->redirect("/activity/edit?event_id={$eventId}");
            //$this->render('event/editBasicInfo.html');
        } else {
            $this->render('event/editBasicInfo.html');
        }
    }

    /**
     * 保存基本信息
     */
    public function ActionSaveBasicInfo()
    {
        $request        = Yii::app()->request;

        $eventId        = $request->getPost('event_id', 0);
        $eventName      = $request->getPost('event_name', '');
        $title          = $request->getPost('title', '');
        $joinStartTime  = $request->getPost('join_start_time', '');
        $joinEndTime    = $request->getPost('join_end_time', '');
        $joinStatus     = $request->getPost('join_status', 0);
        $startTime      = $request->getPost('start_time', '');
        $endTime        = $request->getPost('end_time', '');
        $preheatTime    = $request->getPost('preheat_time', '');


        if (!$eventId) {
            throwMessage('请传入eventID', 'error');
        }

        $eventInfo     = $this->event->getEventInfo($eventId);
        if (!$eventInfo) {
            throwMessage('活动不存在', 'error');
        }

        // 如果接收到的event_name与原来的不用则取查询新接收的event_name有没有
        if ($eventInfo['event_name'] != $eventName) {
            $sdb_brd_shop  = Yii::app()->sdb_brd_shop;
            $searchSql = "select * from tuan_events_list where event_name='{$eventName}' limit 1";
            $eventInfo     = $sdb_brd_shop->createCommand($searchSql)->queryRow();
            if ($eventInfo) {
                throwMessage("活动名称：{$eventName} 已存在", 'error');
            }
        }

        if (!$title) {
            throwMessage('请填写活动标题', 'error');
        }
        if ($joinStartTime && !preg_match("/^[0-9]{4}-[0-9]{2}-[0-9]{2} [0-9]{2}:[0-9]{2}$/", $joinStartTime)) {
            throwMessage('请填写正确的报名开始时间', 'error');
        }
        if ($joinEndTime && !preg_match("/^[0-9]{4}-[0-9]{2}-[0-9]{2} [0-9]{2}:[0-9]{2}$/", $joinEndTime)) {
            throwMessage('请填写正确的报名结束时间', 'error');
        }
        if ($startTime && !preg_match("/^[0-9]{4}-[0-9]{2}-[0-9]{2} [0-9]{2}:[0-9]{2}$/", $startTime)) {
            throwMessage('请填写正确的活动开始时间', 'error');
        }
        if ($endTime && !preg_match("/^[0-9]{4}-[0-9]{2}-[0-9]{2} [0-9]{2}:[0-9]{2}$/", $endTime)) {
            throwMessage('请填写正确的活动结束时间', 'error');
        }
        if ($preheatTime && !preg_match("/^[0-9]{4}-[0-9]{2}-[0-9]{2} [0-9]{2}:[0-9]{2}$/", $preheatTime)) {
            throwMessage('请填写正确的预热时间', 'error');
        }

        $updateFilter = array(
            'event_name'      => $eventName,
            'title'           => $title,
            'join_status'     => $joinStatus,
        );
        if ($joinStartTime) {
            $updateFilter['join_start_time'] = $joinStartTime.":00";
        }
        if ($joinEndTime) {
            $updateFilter['join_end_time'] = $joinEndTime.":00";
        }

        /*
        $startTime   = strtotime($startTime.":00");
        $endTime     = strtotime($endTime.":00");
        $preheatTime = strtotime($preheatTime.":00");
        $updateSchedule = false;
        if ($eventInfo['start_time'] != $startTime) {
            $updateFilter['start_time'] = $startTime;
            $updateSchedule = true;
        }
        if ($eventInfo['end_time'] != $endTime) {
            $updateFilter['end_time'] = $endTime;
            $updateSchedule = true;
        }
        if ($eventInfo['preheat_time'] != $preheatTime) {
            $updateFilter['preheat_time'] = $preheatTime;
            $updateSchedule = true;
        }
        // 如果修改 活动开始时间、结束时间、预热时间则需要全部修改活动的互斥表
        if ($updateSchedule) {
            $updateScheduleResault = $this->eventGoods->updateScheduleEventGoods($eventId, array('start_time'=>$startTime,'end_time'=>$endTime,'preheat_time'=>$preheatTime));
            if ($updateScheduleResault['succ'] != 1) {
                throwMessage($updateScheduleResault['msg'], 'error');
            }
        }
        */
        //p($eventInfo, $updateFilter);exit();

        $db_brd_shop = Yii::app()->db_brd_shop;
        $updateResault = $db_brd_shop->createCommand()->update(
                'tuan_events_list',
                $updateFilter,
                'event_id=:event_id',
                array(':event_id'=>$eventId)
        );

        $tips = '更新成功';
        /*if (isset($updateScheduleResault)) {
            $tips .= "成功更新:{$updateScheduleResault['succ_num']},更新失败:{$updateScheduleResault['err_num']},失败原因:{$updateScheduleResault['err_str']}";
        }*/
        throwMessage($tips, 'success', '/event');
    }



}