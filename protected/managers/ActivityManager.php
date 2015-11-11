<?php
/**
* 主题活动
* @author mingyang@meilishuo.com
* @version 2015-7-30
*/

class ActivityManager extends Manager {

    public function getActivityInfo($event_id){
        $db  = Yii::app()->sdb_brd_shop;
        $sql = "select event_name, title, status,join_start_time,join_end_time ,join_status,"
            ." from_unixtime(start_time) start_time,from_unixtime(end_time) end_time, key_words, bg_color, banner_pc,"
            ."banner_mob, detail, event_id "
            ." from tuan_events_list where event_id = {$event_id} limit 1";
        $rows = $db->createCommand($sql)->queryRow();
        return $rows;
    }
    public function getAreaOfEvent($event_id, $time=''){

    	!$time && $time = date('Y-m-d H:i:s');

        $detail = $this->getActivityInfo($event_id);
        $detail = json_decode($detail['detail'],true);
        $area = $detail['right_nav_info']['right_nav'];
//         array_pop($detail);
        $results = array();
        foreach ($area as $key => $value) {
        	if ($value['link'] == '#pgtop' || empty($value['name'])) {
        		continue;
        	}
        	if ($value['endtime']) {
        		if ($value['endtime']<$time) {
        			continue;
        		}
        	}
        	if ($value['starttime']) {
        		if ($value['starttime']>$time) {
        			continue;
        		}
        	}
            $results[$key+1]['name'] = $value['name'];
            $results[$key+1]['price'] = $value['price'];
            $results[$key+1]['id'] = $value['id']? $value['id'] : substr($value['link'], strpos($value['link'], '_') + 1);
        }
        return $results;
    }
    //获取商品信息
    public function getGoodsInfo($event_id,$time = ''){

    	//!$time && $time = date('Y-m-d H:i:s');
        $detail = self::getAreaOfEvent($event_id, $time);
        $db  = Yii::app()->sdb_brd_shop;
        $column_info = array();
        $timeCondition = "";
        $time = !empty($time)?strtotime($time):'';
        if($time){
            $timeCondition = " and  shop_groupon_info.start_time={$time} ";
        }
        foreach ($detail as $key => $value) {
        	if ($value['link'] == '#pgtop') {
        		continue;
        	}
            $area = $value['id'];
            $sql = "select tuan_events_item_detail.twitter_id, shop_groupon_info.create_time,brd_shop_info.shop_nick nick, tuan_events_item_detail.rank,"
                ."shop_groupon_info.id gid, shop_groupon_info.id tuanid, shop_groupon_info.start_time time, shop_groupon_info.end_time,"
                . " tuan_events_item_detail.shop_id, shop_groupon_info.off_price, round(shop_groupon_info.off_price*100/shop_groupon_info.off_num,2) price, goods_image, goods_name, shop_groupon_info.goods_id "
                . " from tuan_events_item_detail"
                . " join shop_groupon_info on tuan_events_item_detail.groupon_id=shop_groupon_info.id join brd_shop_info on brd_shop_info.shop_id=shop_groupon_info.shop_id"
                . " where shop_groupon_info.audit_status=50 and tuan_events_item_detail.event_id={$event_id} and tuan_events_item_detail.area={$area}"
                . " and shop_groupon_info.goods_type=2 ".$timeCondition
                . " order by tuan_events_item_detail.rank desc ";
            $results = $db->createCommand($sql)->queryAll();
            $tids = '';
            $count = count($results);
            foreach ($results as $key => $value) {
                if($key!=$count-1){
                    $tids .= $value['twitter_id'].",";
                }else{
                    $tids .= $value['twitter_id'];
                }
            }
            if(!empty($tids)){
                $salesArr = $this->getSales($tids);
            }
            foreach ($results as $key => $value) {
                $results[$key]['sales'] = $salesArr[$value['twitter_id']];
            }
            $column_info[$area]['pic'] = $value['pic'];
            $column_info[$area]['link'] = $value['link'];
            $column_info[$area]['pic_mob'] = $value['pic_mob'];
            $column_info[$area]['link_mob'] = $value['link_mob'];
            $column_info[$area]['raw_json'] = json_encode($value);
            $column_info[$area]['goods_list'] = $results;
        }
        return $column_info;
    }
    //退回排期
    public function removeActivity($event,$tid){
        try {
            $db  = Yii::app()->db_brd_shop;
            $delete_sql = "update tuan_events_item_detail
                            set area=0,category=0,rank=0
                            where event_id={$event} and twitter_id={$tid}";
            $db->createCommand($delete_sql)->execute();
            return true;

        } catch (Exception $e) {
            return false;
        }
    }
    //人工排序
    public function sortEvent($event_id,$area,$tids,$time){
        try {
            $tidArr = explode(',',$tids);
            $time = strtotime($time);
            $count = count($tidArr)-1;
            $db  = Yii::app()->db_brd_shop;
            foreach ($tidArr  as $key => $value) {
                if(!empty($value)){
                    $rank = $this->timeToRank($count,$time);
                    $sql = "update tuan_events_item_detail
                            set rank={$rank}
                            where area={$area} and event_id={$event_id} and twitter_id={$value}";
                    $db->createCommand($sql)->execute();
                    $count --;
                }
            }
            return true;

        } catch (Exception $e) {
            return false;
        }
    }
    //价格排序,销量排序
    public function sortByCondition($event_id,$area,$condition){
        $items = explode('_', $condition);
        $item = $items[0];
        $sortCondition = $items[1];
        $db  = Yii::app()->sdb_brd_shop;
        $sql = "select tuan_events_item_detail.twitter_id, tuan_events_item_detail.rank,"
                ."shop_groupon_info.id tuanid, "
                . " tuan_events_item_detail.shop_id, shop_groupon_info.off_price, round(shop_groupon_info.off_price*100/shop_groupon_info.off_num,2) price, goods_image, goods_name, shop_groupon_info.goods_id "
                . " from tuan_events_item_detail"
                . " join shop_groupon_info on tuan_events_item_detail.groupon_id=shop_groupon_info.id "
                . " where shop_groupon_info.audit_status=50 and tuan_events_item_detail.event_id={$event_id} and tuan_events_item_detail.area={$area}"
                . " and shop_groupon_info.goods_type=2 "
                . " order by shop_groupon_info.off_price ".$sortCondition;
        $results = $db->createCommand($sql)->queryAll();
        $waitSort = array();
        $sortArr = array();
        foreach ($results as $key => $value) {
            $waitSort[] = $value['twitter_id'];
        }
        $tids = '';
        $count = count($results);
        foreach ($results as $key => $value) {
            if($key!=$count-1){
                $tids .= $value['twitter_id'].",";
            }else{
                $tids .= $value['twitter_id'];
            }
        }
        if($item == 'sales'){
            $waitSort = array();
            $waitSort = $this->getSales($tids,$sortCondition);
        }
        try {
            $rank = count($waitSort);
            $db  = Yii::app()->db_brd_shop;
            foreach ($waitSort as $key => $value) {
                $sql = "update tuan_events_item_detail
                        set rank={$rank}
                        where area={$area} and event_id={$event_id} and twitter_id={$value}";
                $db->createCommand($sql)->execute();
                $rank--;
            }
            return true;
        } catch (Exception $e) {
            return false;
        }

    }
    //条件转化
    public function conversion($condition){
        $item = explode('_', $condition);
        $condition = $item[1];
        $arr = array("price"=>"shop_groupon_info.off_price");
        return $arr['price']." ".$condition;
    }
    //导出数据
    public function exportHtml($list, $title)
    {
        $titles = array('宝贝','类别','店铺名称', '团购id', 'twitter_id', '店铺ID', '原价', '折扣价',
                '销量','报名时间','开始时间','结束时间');

        $columns = array('goods_name','category','nick', 'tuanid', 'twitter_id', 'shop_id', 'price', 'off_price', 'sales', 'create_time','time','end_time');

        $tipsTypeEnum = CheckTipsManager::$tipsTypeEnum;
        $tid= array();
        foreach ($list as $key => $value) {
            $tid[] = $value['twitter_id'];
        }
        $tidToCategory = $this->getCatogryOfGoods($tid);
        foreach ($list as &$v) {
            if ($v['time']) {
                $v['time']   = date("Y-m-d H:i:s", $v['time']);
            } else {
                $v['time']   = '';
            }
            if ($v['end_time']) {
                $v['end_time']   = date("Y-m-d H:i:s", $v['end_time']);
            } else {
                $v['end_time']   = '';
            }
            $v['category'] = $tidToCategory[$v['twitter_id']];
        }
        $title = $title."_".date("Y-m-d").".xls";
        $this->common->exportHtml($titles, $columns, $list, $title);
    }
    //自动划分区域 同时设置一时间的品的rank值
    public function autoDivide($event_id,$str_time){

        $time = strtotime($str_time);
        $sdb  = Yii::app()->sdb_brd_shop;
        $db  = Yii::app()->db_brd_shop;
        $sql = "select shop_groupon_info.id id, tuan_events_item_detail.twitter_id tid,tuan_events_item_detail.rank,shop_groupon_info.start_time,shop_groupon_info.off_price price from tuan_events_item_detail"
                . " join shop_groupon_info on tuan_events_item_detail.groupon_id=shop_groupon_info.id "
                . " where shop_groupon_info.audit_status=50 and tuan_events_item_detail.event_id={$event_id}"
                . " and shop_groupon_info.goods_type=2 and shop_groupon_info.start_time='$time' ";

        $variable = $sdb->createCommand($sql)->queryAll();
        try {

        	$tabs = $this->getAreaOfEvent($event_id, $str_time);

            foreach ($variable as $key => $value) {

                $area = $this->priceToArea($tabs,$value['price']);
                $rank =  $this->timeToRank($value['rank'],$value['start_time']);
                $groupon_id = $value['id'];
                if($area&&$groupon_id){
                    $sql = "update tuan_events_item_detail set area={$area},rank={$rank} where  groupon_id='$groupon_id'";
                   $db->createCommand($sql)->execute();
                }

            }
            return true;

         } catch (Exception $e) {
            return false;
         }

    }
    //价格转化为区域
    public function priceToArea($tabs, $price){

    	$price = (float)$price;
    	$tabs = ArrFomate::sortByCol($tabs, 'price');

    	$count = count($tabs);
    	for ($i=0; $i<$count; $i++) {
    		if ($i==0) {
    			if ($price>=0 && $price<=$tabs[$i]['price']) {
    				return $tabs[$i]['id'];
    			}
    		} elseif($i == $count-1) {
    			if ($price >=$tabs[$i]['price'] || ($price>=$tabs[$i-1]['price'] && $price<$tabs[$i]['price'])) {
    				return $tabs[$count-1]['id'];
    			}
    		} elseif($price>$tabs[$i-1]['price'] && $price<=$tabs[$i]['price'] ) {
    			return $tabs[$i]['id'];
    		}

    	}

    }
    //时间转化为rank  (year+month+day)*1000 作为rank基数
    public function timeToRank($rank,$date){
        $year = (int)date('Y',$date);
        $month = (int)date('m',$date);
        $day = (int)date('d',$date);
        $baseNum = ($year+$month*100+$day)*1000;
        if($rank>$baseNum||$rank>1000){
            $rank = $rank - $baseNum;
        }
        return $baseNum + $rank;
    }
    //获取一系列品的销量
    public function getSales($tids,$condition=''){
        if($condition){
            $condition =" order by sale_num {$condition}";
        }
        $db  = Yii::app()->sdb_brd_goods;
        $sql = "select twitter_id,sale_num from brd_goods_info where twitter_id in (".$tids.") ".$condition;
        $results = $db->createCommand($sql)->queryAll();
        $variable = array();
        $tidSort = array();
        foreach ($results as $key => $value) {
            $variable[$value['twitter_id']] = $value['sale_num'];
        }
        foreach ($results as $key => $value) {
            $tidSort [] = $value['twitter_id'];
        }
        if($condition){
            return $tidSort;
        }else{
            return $variable;
        }
    }
    //获取店铺信息
    public function getShopInfo($tids){
        $db  = Yii::app()->sdb_brd_goods;
        $sql = "select * from brd_goods_info where twitter_id in (".$tids.") ";
        $results = $db->createCommand($sql)->queryAll();
        $reArr = array();
        foreach ($results as $key => $value) {
            $reArr[$value['twitter_id']] = $value;
        }
        return $reArr;
    }
    //排序商品返回排序商品数组
    public function getSortGoods($event_id,$area,$condition,$time){
        $items = explode('_', $condition);
        $item = $items[0];
        $sortCondition = $items[1];
        $time = strtotime($time);
        $timeCondition = isset($time)?" and shop_groupon_info.start_time = $time":'';
        $db  = Yii::app()->sdb_brd_shop;
        $sql = "select tuan_events_item_detail.twitter_id,shop_groupon_info.start_time, tuan_events_item_detail.rank,"
                ."shop_groupon_info.id tuanid, "
                . " tuan_events_item_detail.shop_id, shop_groupon_info.off_price, round(shop_groupon_info.off_price*100/shop_groupon_info.off_num,2) price, goods_image, goods_name, shop_groupon_info.goods_id "
                . " from tuan_events_item_detail"
                . " join shop_groupon_info on tuan_events_item_detail.groupon_id=shop_groupon_info.id "
                . " where shop_groupon_info.audit_status=50 and tuan_events_item_detail.event_id={$event_id} and tuan_events_item_detail.area={$area}"
                . " and shop_groupon_info.goods_type=2 ".$timeCondition
                . " order by shop_groupon_info.off_price ".$sortCondition;
        $results = $db->createCommand($sql)->queryAll();
        $salesArr = array();
        $tids = '';
        $count = count($results);
        foreach ($results as $key => $value) {
            if($key!=$count-1){
                $tids .= $value['twitter_id'].",";
            }else{
                $tids .= $value['twitter_id'];
            }
        }
        if(!empty($tids)){
                $salesArr = $this->getSales($tids);
            }
        foreach ($results as $key => $value) {
            $results[$key]['sales'] = $salesArr[$value['twitter_id']];
            $results[$key]['start_time'] = date("Y-m-d H:i:s",$value['start_time']);
        }
        if($item == 'sales'){
            $tid='';
            $arrGoods = array();
            foreach ($results as $key => $value) {
                $results[$value['twitter_id']] = $value;
            }
            $waitSort = $this->getSales($tids,$sortCondition);
            foreach ($waitSort as $key => $value) {
                $arrGoods[] = $results[$value];
            }
            return $arrGoods;
        }else{
            return $results;
        }
    }
    //获得商品的类别
    public function getCatogryOfGoods($arrTid){
        $db = yii::app()->sdb_brd_shop;
        $sql = "select twitter_id ,goods_first_catalog from shop_groupon_goods_relation where twitter_id in(".implode(',', $arrTid).")";
        $variable = $db->createCommand($sql)->queryAll();
        $results = array();
        foreach ($variable as $key => $value) {
            $results[$value['twitter_id']] = $value['goods_first_catalog'];
        }
        return $results;
    }
    //更改商品的区域
    public function changeGoodsArea($event_id,$arrTid,$area){
        $db = yii::app()->db_brd_shop;
        $update = "update tuan_events_item_detail set area={$area} where event_id={$event_id} and twitter_id in (".implode(',', $arrTid).")";
        $db->createCommand($update)->execute();
    }
}
