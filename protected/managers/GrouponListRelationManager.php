<?php
/**
 * 团购商品同步计划任务脚本
 * @author ruidongsong
 */
class GrouponListRelationManager extends Manager
{
    private $newGrouponList = array();
    private $twitterIds     = array();
    private $goodsIds       = array();
    private $goodsCategorys = array();
    private $crontabNextGrouponIdKey = 'crontabNextGrouponId';

    public function runGrouponInfo()
    {
        $per_page = 2000;
        $page     = Yii::app()->request->getParam('page', 1);
        $limit    = " ORDER BY `id` ASC LIMIT ".($page-1)*$per_page.", $per_page";

        $sql         = "SELECT * FROM `shop_groupon_info` where id >= 1706964 ".$limit;

        $brd_shop_db = Yii::app()->sdb_brd_shop;
        $grouponList = $brd_shop_db->createCommand($sql)->queryAll();
        if (!$grouponList) exit('已导入完成,第'.($page-1).'页');

        $twitterIds  = $this->getGrouponTwitterIds($grouponList);

        $succNum = $this->insertDB();

        echo '第'.$page.'页，同步完毕:'.$succNum;
        $page++;
        echo "<script>window.location.href='/test/runAll?page=".$page."'</script>";
        exit('同步完毕:'.$succNum);
    }


    /**
     * 跑全部的数据
     */
    public function runRelationAll()
    {
        MailManager::sendCommMail('ruidongsong', '团购商品脚本-每日-'.date("Y-m-d H:i:s"), 'runRelationAll_已结束');
        exit('end-runRelationAll');

        set_time_limit(0);
        ini_set("memory_limit","-1");

        $brd_shop_db = Yii::app()->sdb_brd_shop;
        $perpage     = 1000;
        $intval      = 500;
        $i           = 0;

        $limit       = " ORDER BY `id` ASC LIMIT " . ($i * $perpage) . ", $perpage";
        $sql         = "SELECT * FROM `shop_groupon_goods_relation` ".$limit;
        $grouponList = $brd_shop_db->createCommand($sql)->queryAll();

        $goods_total_all = 0;
        $ids_total_all   = 0;
        $succ_num_all    = 0;
        $db_brd_shop = Yii::app()->db_brd_shop;

        while(!empty ($grouponList)) {
            $twitterIds  = $this->getGrouponTwitterIdsByRelation($grouponList);

            $this->initFocusCvr();
            $this->initBrdShopRepertory();
            $this->initBrdShopSale();
            $this->initFocusInfoDaily();
            $this->initDangerGoods();
            $this->initBadGoods();

            // 各个sql变量
            $goods_first_catalog_sql    = "";
            $goods_second_catalog_sql   = "";
            $goods_three_catalog_sql    = "";
            $goods_price_sql            = "";
            $repertory_sql              = "";
            $sale_num_sql               = "";
            $cvr_sql                    = "";
            $first_sort_id_sql          = "";
            $second_sort_id_sql         = "";
            $sort_id_sql                = "";
            $sellrate_score_sql         = "";
            $popularity_score_sql       = "";
            $is_danger_sql              = "";
            $is_bad_sql                 = "";
            $gmv_sql                    = "";
            $ids_arr                    = array();

            foreach ($this->newGrouponList as $k => $v) {
                $id = $v['relation_id'];
                $goods_first_catalog_sql    .= "WHEN {$id} THEN '{$v['goods_first_catalog']}' ";
                $goods_second_catalog_sql   .= "WHEN {$id} THEN '{$v['goods_second_catalog']}' ";
                $goods_three_catalog_sql    .= "WHEN {$id} THEN '{$v['goods_three_catalog']}' ";
                $goods_price_sql            .= "WHEN {$id} THEN '{$v['goods_price']}' ";
                $repertory_sql              .= "WHEN {$id} THEN '{$v['repertory']}' ";
                $sale_num_sql               .= "WHEN {$id} THEN '{$v['sale_num']}' ";
                $cvr_sql                    .= "WHEN {$id} THEN '{$v['cvr']}' ";
                $first_sort_id_sql          .= "WHEN {$id} THEN '{$v['first_sort_id']}' ";
                $second_sort_id_sql         .= "WHEN {$id} THEN '{$v['second_sort_id']}' ";
                $sort_id_sql                .= "WHEN {$id} THEN '{$v['sort_id']}' ";
                $sellrate_score_sql         .= "WHEN {$id} THEN '{$v['sellrate_score']}' ";
                $popularity_score_sql       .= "WHEN {$id} THEN '{$v['popularity_score']}' ";
                $is_danger_sql              .= "WHEN {$id} THEN '{$v['is_danger']}' ";
                $is_bad_sql                 .= "WHEN {$id} THEN '{$v['is_bad']}' ";
                $gmv_sql                    .= "WHEN {$id} THEN '{$v['gmv']}' ";

                $ids_arr[] = $id;
            }

            $sql = "UPDATE shop_groupon_goods_relation SET ";
            $sql .= " `goods_first_catalog` = CASE id {$goods_first_catalog_sql} END ";
            $sql .= ", `goods_second_catalog` = CASE id {$goods_second_catalog_sql} END ";
            $sql .= ", `goods_three_catalog` = CASE id {$goods_three_catalog_sql} END ";
            $sql .= ", `goods_price` = CASE id {$goods_price_sql} END ";
            $sql .= ", `repertory` = CASE id {$repertory_sql} END ";
            $sql .= ", `sale_num` = CASE id {$sale_num_sql} END ";
            $sql .= ", `cvr` = CASE id {$cvr_sql} END ";
            $sql .= ", `first_sort_id` = CASE id {$first_sort_id_sql} END ";
            $sql .= ", `second_sort_id` = CASE id {$second_sort_id_sql} END ";
            $sql .= ", `sort_id` = CASE id {$sort_id_sql} END ";
            $sql .= ", `sellrate_score` = CASE id {$sellrate_score_sql} END ";
            $sql .= ", `popularity_score` = CASE id {$popularity_score_sql} END ";
            $sql .= ", `is_danger` = CASE id {$is_danger_sql} END ";
            $sql .= ", `is_bad` = CASE id {$is_bad_sql} END ";
            $sql .= ", `gmv` = CASE id {$gmv_sql} END ";
            $sql .= " WHERE id in (".implode(",", $ids_arr).")";

            try {
                $r = $db_brd_shop->createCommand($sql)->execute();
            } catch (Exception $e) {
                MailManager::sendCommMail('ruidongsong', '团购商品脚本-报警', "brd_shop.shop_groupon_goods_relation 库执行错误\r\n {$e} \r\n\r\n sql : \r\n ".$sql);
            }

            $goods_total_all += count($this->newGrouponList);
            $ids_total_all  += count($ids_arr);
            $succ_num_all += $r;

            echo count($this->newGrouponList)."----".count($ids_arr)." ---- {$r} ----- {$i} \r\n";
            //usleep($intval);
            $i++;

            $limit       = " ORDER BY `id` ASC LIMIT " . ($i * $perpage) . ", $perpage";
            $sql         = "SELECT * FROM `shop_groupon_goods_relation` ".$limit;
            $grouponList = $brd_shop_db->createCommand($sql)->queryAll();
        }

        $data = "今日执行 {$i} 页，每页 {$perpage} 条数据 \r\n";
        $data .= "商品总数：{$goods_total_all}， id总数：{$ids_total_all}， 共更新{$succ_num_all}条数据";

        MailManager::sendCommMail('ruidongsong', '团购商品脚本-每日-'.date("Y-m-d H:i:s"), $data);

        echo 'success';

    }

    /**
     * 跑全部的数据
     */
    public function runAll()
    {
        set_time_limit(0);
        ini_set("memory_limit","-1");

        $brd_shop_db = Yii::app()->sdb_brd_shop;
        $perpage     = 1000;
        $intval      = 500;
        $i           = 0;

        $update_date = date("Y-m-d", strtotime("-20 days")). " 00:00:00";
        $grouponSql  = "select distinct twitter_id from shop_groupon_info where create_time >= '{$update_date}'";
        $limit       = " ORDER BY `id` ASC LIMIT " . ($i * $perpage) . ", $perpage";

        $sql = $grouponSql.$limit;
        $groupon_tids = $brd_shop_db->createCommand($sql)->queryColumn();
        if (!$groupon_tids) {
            $data = "今日无数据 \r\n";
            MailManager::sendCommMail('ruidongsong', '团购商品脚本-每日-'.date("Y-m-d H:i:s"), $data);
            exit();
        }

        $sql         = "SELECT * FROM `shop_groupon_goods_relation` where twitter_id in (".implode(",", $groupon_tids).")";
        $grouponList = $brd_shop_db->createCommand($sql)->queryAll();

        $allSuccNum     = 0;
        $allNum         = 0;
        $allRelationNum = 0;
        while(!empty ($grouponList)) {
            $twitterIds  = $this->getGrouponTwitterIdsByRelation($grouponList);
            $succNum = $this->insertDB();

            $grouponNum     = count($groupon_tids);
            $relationNum    = count($grouponList);

            $allSuccNum     += $succNum;
            $allRelationNum += $relationNum;
            $allNum         += $grouponNum;

            echo "{$grouponNum} ----- {$relationNum} -----   {$succNum}\r\n";
            //usleep($intval);
            $i++;

            $limit       = " ORDER BY `id` ASC LIMIT " . ($i * $perpage) . ", $perpage";
            $sql         = $grouponSql.$limit;
            $groupon_tids = $brd_shop_db->createCommand($sql)->queryColumn();
            if ($groupon_tids) {
                $sql         = "SELECT * FROM `shop_groupon_goods_relation` where twitter_id in (".implode(",", $groupon_tids).")";
                $grouponList = $brd_shop_db->createCommand($sql)->queryAll();
            } else {
                $grouponList = array();
            }
        }

        $data = "今日执行 {$i} 页，每页 {$perpage} 条数据 \r\n";
        $data .= "商品总数：{$allNum}， relation总数：{$allRelationNum}， 共更新{$allSuccNum}条数据";
        MailManager::sendCommMail('ruidongsong', '团购商品脚本-每日-'.date("Y-m-d H:i:s"), $data);

        echo 'success:'.$data;

        exit('end-runAll-end');
    }

    /**
     * 增量跑
     */
    public function run() {

//         $redis = new Redis();
//         $redis->connect('127.0.0.1', 6379);

    	$redis = new Redisdb(Redisdb::REDIS_SERVER_DEFAULT);

        // 获取最后一次操作的id
        $nextId = $redis->get($this->crontabNextGrouponIdKey, array());

        // 这里判断是为了防止从头开始查找
        if ($nextId < 1400000) {
            $grouponList = $this->getGrouponList(array('date'=>date("Y-m-d")));
        } else {
            $grouponList = $this->getGrouponList(array('next_id'=>$nextId));
        }

        //var_dump(count($grouponList));exit();
        if (!$grouponList) exit('没有团购数据');

        $twitterIds = $this->getGrouponTwitterIds($grouponList);
        //$twitterIds = array_values($newGrouponList);

        $r = $this->insertDB($redis);
        //$r = $this->insertDB();

        echo "成功插入:".$r." 最后一次记录id：".$redis->get($this->crontabNextGrouponIdKey, array());
        exit();

        //p(count($this->newGrouponList), $this->newGrouponList);exit();
        // 大类
        // $sql = "select * from "
    }

    /**
     * 插入数据库
     * @param object $redisObj
     * @FIXME 这里请传入redis对象，用作记录最后一次插入的groupon自增id
     */
    public function insertDB($redisObj=null)
    {
        try {
            $redis_log_obj = new Redisdb(Redisdb::REDIS_SERVER_DEFAULT);
        } catch (Exception $e) {
            MailManager::sendCommMail('ruidongsong', '团购商品脚本-报警-初始化脚本失败', "redis初始化失败 : \r\n {$e}");
        }

        $this->initFocusCvr();
        $this->initBrdShopRepertory();
        $this->initBrdShopSale();
        $this->initFocusInfoDaily();
        $this->initDangerGoods();
        $this->initBadGoods();

        $bar_shop_db = Yii::app()->db_brd_shop;
        $succNum = 0;
        foreach ($this->newGrouponList as $k=>$v) {
            try {
                $add_time = date("Y-m-d H:i:s");
                // @FIXME 将history转译
                $tuanHistory = addslashes($v['tuan_history']);
                $sql  = "INSERT INTO shop_groupon_goods_relation";
                $sql .= "(`twitter_id`, `goods_id`, `goods_first_catalog`, `goods_second_catalog`, `goods_three_catalog`, `goods_price`, `repertory`, `sale_num`, `cvr`,
                		`first_sort_id`, `second_sort_id`, `sort_id`, `create_time`, `add_time`, `tuan_history`, `goods_type`, `sellrate_score`, `popularity_score`, `is_danger`,
                		 `is_bad`, `gmv`)";
                $sql .= " VALUES('{$v['twitter_id']}', '{$v['goods_id']}', '{$v['goods_first_catalog']}', '{$v['goods_second_catalog']}', '{$v['goods_three_catalog']}'";
                $sql .= ", '{$v['goods_price']}', '{$v['repertory']}', '{$v['sale_num']}', '{$v['cvr']}', '{$v['first_sort_id']}', '{$v['second_sort_id']}', '{$v['sort_id']}', '{$v['create_time']}', '{$add_time}'";
                $sql .= ", '{$tuanHistory}', '{$v['goods_type']}', '{$v['sellrate_score']}', '{$v['popularity_score']}', '{$v['is_danger']}', '{$v['is_bad']}', '{$v['gmv']}')";
                $sql .= " ON DUPLICATE KEY UPDATE `goods_first_catalog`='{$v['goods_first_catalog']}', `goods_second_catalog`='{$v['goods_second_catalog']}', `goods_three_catalog`='{$v['goods_three_catalog']}'";
                $sql .= ", `goods_price`='{$v['goods_price']}', `repertory`='{$v['repertory']}', `sale_num`='{$v['sale_num']}', `cvr`='{$v['cvr']}', `first_sort_id`='{$v['first_sort_id']}', `second_sort_id`='{$v['second_sort_id']}', `sort_id`='{$v['sort_id']}'";
                $sql .= ", `sellrate_score`='{$v['sellrate_score']}', `popularity_score`='{$v['popularity_score']}', `tuan_history`='{$tuanHistory}', `is_danger`='{$v['is_danger']}', `is_bad`='{$v['is_bad']}', `gmv`='{$v['gmv']}'";

                // echo $sql."\r\n";
                $r = $bar_shop_db->createCommand($sql)->execute();
                //$r = 1;
                if ($r) {
                    if ($redisObj) {
                        // 设置最后一次操作的key
                        $redisObj->set($this->crontabNextGrouponIdKey, array(), $v['groupon_id']);
                    }

                    $succNum++;
                }
            } catch (Exception $e) {
                // 将错误写入日志文件
                $logUrl    = "/home/work/logs/tuan/grouponListRelationManager.insertDB.log";
                file_put_contents($logUrl, date("Y-m-d H:i:s")."\r\n{$e}\r\n{$sql}\r\n\r\n\r\n", FILE_APPEND);

                // 发报价邮件
                if ($redis_log_obj) {
                    $lastErrorTimeKey = 'GrouponListRelationManager_insertDB_lastErrorTime';
                    $lastErrorTime    = $redis_log_obj->get($lastErrorTimeKey, array());
                    if ($lastErrorTime) {
                        // 120秒以内不允许多次发送
                        if ((time() - $lastErrorTime) > 120) {
                            $redis_log_obj->set($lastErrorTimeKey, array(), time());
                            MailManager::sendCommMail('ruidongsong', '团购商品脚本-报警-更新', "brd_shop.shop_groupon_goods_relation 库执行错误: \r\n {$e} \r\n\r\n sql : \r\n ".$sql);
                        }
                    } else {
                        $redis_log_obj->set($lastErrorTimeKey, array(), time());
                        MailManager::sendCommMail('ruidongsong', '团购商品脚本-报警-更新', "brd_shop.shop_groupon_goods_relation 库执行错误: \r\n {$e} \r\n\r\n sql : \r\n ".$sql);
                    }
                }
            }
        }

        return $succNum;
    }

    /**
     * 初始化focus库中分类信息
     * @FXIME 已废弃 请使用 $this->initBrdShopSale()
     * @return boolean
     */
    public function initFocusGoodsList()
    {
        if (!$this->twitterIds) return false;

        $focus_db = Yii::app()->sdb_focus;
        $sql = "SELECT * FROM `t_focus_goods_info` WHERE `twitter_id` IN (" . implode(",", $this->twitterIds) . ")";
        $goodsList = $focus_db->createCommand($sql)->queryAll();
        foreach ($goodsList as $k=>$v) {
            if (array_key_exists($v['twitter_id'], $this->newGrouponList)) {
                // 一级分类
                $this->newGrouponList[$v['twitter_id']]['goods_first_catalog']  = $v['goods_first_catalog'];
                //  二级分类
                if (!$v['goods_second_catalog']) {
                    $this->newGrouponList[$v['twitter_id']]['goods_second_catalog'] = $this->newGrouponList[$v['twitter_id']]['goods_first_catalog'];
                } else {
                    $this->newGrouponList[$v['twitter_id']]['goods_second_catalog'] = $v['goods_second_catalog'];
                }
                // 三级分类
                if (!$v['goods_three_catalog']) {
                    $this->newGrouponList[$v['twitter_id']]['goods_three_catalog']  = $this->newGrouponList[$v['twitter_id']]['goods_second_catalog'];
                } else {
                    $this->newGrouponList[$v['twitter_id']]['goods_three_catalog']  = $v['goods_three_catalog'];
                }
                // 价格
                $this->newGrouponList[$v['twitter_id']]['goods_price']          = $v['goods_price'];
                //if ($v['goods_first_catalog'] == '')
            }
        }

        return true;
    }

    /**
     * 初始化focus库中的cvr
     * @return boolean
     */
    public function initFocusCvr()
    {
        if (!$this->twitterIds) return false;

        $focus_db = Yii::app()->sdb_focus;
        if (date("d") <= 10) {
            // 如果是每个月的第前10天，则到上个月中取数据
            $yearMonth = date('Ym', strtotime("-1 month"));
        } else {
            $yearMonth = date('Ym');
        }
        // $yearMonth = date('Ym');    // 获取年月 例：201504
        $table     = "t_focus_goods_coral_daily_{$yearMonth}";
        // 先找到最后一天
        $dt_sql    = "select max(dt) dt from {$table}";
        $last_date = $focus_db->createCommand($dt_sql)->queryScalar();
        // 查询cvr
        $sql       = "select twitter_id, sum(gmv) as gmv,avg(buy_rate) as cvr from {$table} where twitter_id in (" . implode(',', $this->twitterIds) . ") group by twitter_id";
        $cvrList   = $focus_db->createCommand($sql)->queryAll();
        foreach ($cvrList as $k=>$v) {
            if (array_key_exists($v['twitter_id'], $this->newGrouponList)) {
                $this->newGrouponList[$v['twitter_id']]['cvr'] = $v['cvr'];
                $this->newGrouponList[$v['twitter_id']]['gmv'] = $v['gmv'];
            }
        }
    }

    /**
     * 初始化brd_shop库中的库存
     */
    public function initBrdShopRepertory()
    {
        if (!$this->goodsIds) return false;

        // 库存
        // $brd_shop_db = Yii::app()->sdb_brd_shop;
        $brd_shop_db = Yii::app()->sdb_brd_goods;
        $sql        = "select goods_id, sum(repertory) as repertory from brd_goods_sku_info where status=1 and goods_id in (" . implode(",", $this->goodsIds) . ") group by goods_id";
        $goodsRepertoryList = $brd_shop_db->createCommand($sql)->queryAll();
        foreach ($goodsRepertoryList as $k=>$v) {
            $key = array_search($v['goods_id'], $this->goodsIds);
            if (array_key_exists($key, $this->newGrouponList)) {
                $this->newGrouponList[$key]['repertory'] = $v['repertory'];
            }
        }

        return true;
    }

    /**
     * 初始化销量、分类、分类id
     * @return boolean
     */
    public function initBrdShopSale()
    {
        if (!$this->twitterIds) return false;

        // 分类/销量
        //$brd_shop_db  = Yii::app()->sdb_brd_shop;
        $brd_shop_db  = Yii::app()->sdb_brd_goods;
        $sql          = "select twitter_id,sale_num,first_sort_id,sort_id,goods_price from brd_goods_info where twitter_id in (" . implode(",", $this->twitterIds) . ")";
        $brdGoodsList = $brd_shop_db->createCommand($sql)->queryAll();

        // 一级、二级、末级类没有id数组，用于查询category表
        $firstCategoryIds = array();
        $lastCategoryIds  = array();
        $secondCategoryIds = array();

        if ($brdGoodsList) {
            foreach ($brdGoodsList as $k=>$v) {
                if (array_key_exists($v['twitter_id'], $this->newGrouponList)) {
                    $this->newGrouponList[$v['twitter_id']]['first_sort_id'] = $v['first_sort_id'];
                    $this->newGrouponList[$v['twitter_id']]['sort_id']       = $v['sort_id'];
                    $this->newGrouponList[$v['twitter_id']]['sale_num']      = $v['sale_num'];
                    $this->newGrouponList[$v['twitter_id']]['goods_price']   = $v['goods_price'];

                    //  默认给三个分类都为空
                    $this->newGrouponList[$v['twitter_id']]['goods_first_catalog']  = "";
                    $this->newGrouponList[$v['twitter_id']]['goods_second_catalog'] = "";
                    $this->newGrouponList[$v['twitter_id']]['goods_three_catalog']  = "";

                    // 二级类目id  默认为二级类目
                    $this->newGrouponList[$v['twitter_id']]['second_sort_id'] = $v['sort_id'];

                    // 大类，查找大类用
                    $this->goodsCategorys[$v['twitter_id']] = $v['first_sort_id'];
                    if ($v['first_sort_id']) {
                        $firstCategoryIds[$v['first_sort_id']] = $v['first_sort_id'];
                    }
                    if ($v['sort_id']) {
                        $lastCategoryIds[$v['sort_id']]        = $v['sort_id'];
                    }
                }
            }

            if ($lastCategoryIds) {
                // 调用接口获取类没有信息
                $categoryResult  = $this->util->getCategoryInfo(implode(",", $lastCategoryIds));
                $allCategoryInfo = $this->setArrayKey($categoryResult, 'cid');
                if ($allCategoryInfo) {
                    // 遍历数组填充末级分类
                    foreach ($this->newGrouponList as $k=>$v) {
                        if (array_key_exists($v['sort_id'], $allCategoryInfo)) {
                            $this->newGrouponList[$k]['goods_first_catalog']  = $allCategoryInfo[$v['sort_id']]['first_name'] ? $allCategoryInfo[$v['sort_id']]['first_name'] : $allCategoryInfo[$v['sort_id']]['second_name'];
                            $this->newGrouponList[$k]['goods_second_catalog'] = $allCategoryInfo[$v['sort_id']]['second_name'] ? $allCategoryInfo[$v['sort_id']]['second_name'] : $allCategoryInfo[$v['sort_id']]['name'];
                            $this->newGrouponList[$k]['goods_three_catalog']  = $allCategoryInfo[$v['sort_id']]['third_name'] ? $allCategoryInfo[$v['sort_id']]['third_name'] : $allCategoryInfo[$v['sort_id']]['name'];
                            // 如果没有一级分类，默认使用二级分类
                            $this->newGrouponList[$k]['first_sort_id']        = $allCategoryInfo[$v['sort_id']]['first_id'] ? $allCategoryInfo[$v['sort_id']]['first_id'] : $allCategoryInfo[$v['sort_id']]['second_id'];

                            // 判断如果没有一级类目直接使用sort_id
                            if (!$this->newGrouponList[$k]['first_sort_id']) {
                                $this->newGrouponList[$k]['first_sort_id']        = $allCategoryInfo[$v['sort_id']]['cid'];
                                $this->newGrouponList[$k]['goods_first_catalog']  = $allCategoryInfo[$v['sort_id']]['name'];
                            }

                            // 二级类目id
                            $this->newGrouponList[$k]['second_sort_id']       = $allCategoryInfo[$v['sort_id']]['second_id'] ? $allCategoryInfo[$v['sort_id']]['second_id'] : $allCategoryInfo[$v['sort_id']]['cid'];
                        }
                    }
                }
            }

            /*
            // 这个已废弃，因为brd_goods_info不再提供一级分类
            if ($lastCategoryIds) {
                // 一级、二级、末级类目新数组，将则增cid作为下标
                $newLastCategoryList    = array();
                $newSecondCategoryList  = array();
                $newFirstCategoryList   = array();

                $athena_db       = Yii::app()->sdb_athena;

                // 末级、二级类目操作
                $lastCategorySql   = "select * from category where cid in (" . implode(",", $lastCategoryIds) . ")";
                $lastCategoryList  = $athena_db->createCommand($lastCategorySql)->queryAll();
                // 遍历为一个新数组
                foreach ($lastCategoryList as $k=>$v) {
                    $newLastCategoryList[$v['cid']] = $v;
                    // 设置二级类目id
                    if ($v['parent_cid'] != 0 && $v['is_parent'] == 0) {
                        $secondCategoryIds[$v['parent_cid']] = $v['parent_cid'];
                    }
                }
                if ($secondCategoryIds) {
                    // 二级分类
                    $secondCategorySql   = "select * from category where cid in (" . implode(",", $secondCategoryIds) . ")";
                    $secondCategoryList  = $athena_db->createCommand($secondCategorySql)->queryAll();
                    // 遍历为一个新数组
                    foreach ($secondCategoryList as $k=>$v) {
                        $newSecondCategoryList[$v['cid']] = $v;
                    }
                }
                // 遍历末级分类 将父级分类标题加到末级分类
                foreach ($newLastCategoryList as $k=>$v) {
                    if (array_key_exists($v['parent_cid'], $newSecondCategoryList)) {
                        // 判断如果二级类目是顶级类目就将末级类目添加到二级类目
                        if ($newSecondCategoryList[$v['parent_cid']]['parent_cid'] == 0) {
                            $newLastCategoryList[$k]['parent_name'] = $v['name'];
                        } else {
                            $newLastCategoryList[$k]['parent_name'] = $newSecondCategoryList[$v['parent_cid']]['name'];
                        }
                    } else {
                        $newLastCategoryList[$k]['parent_name'] = $v['name'];
                    }
                }
                // 遍历数组填充末级分类
                foreach ($this->newGrouponList as $k=>$v) {
                    if (array_key_exists($v['sort_id'], $newLastCategoryList)) {
                        $this->newGrouponList[$k]['goods_three_catalog']  = $newLastCategoryList[$v['sort_id']]['name'];
                        $this->newGrouponList[$k]['goods_second_catalog'] = $newLastCategoryList[$v['sort_id']]['parent_name'];
                    }
                }

                if ($firstCategoryIds) {
                    // 顶级类目
                    $firstCategorySql   = "select * from category where cid in (" . implode(",", $firstCategoryIds) . ")";
                    $firstCategoryList  = $athena_db->createCommand($firstCategorySql)->queryAll();
                    foreach ($firstCategoryList as $k=>$v) {
                        $newFirstCategoryList[$v['cid']] = $v;
                    }

                    // 遍历数组填充末级分类
                    foreach ($this->newGrouponList as $k=>$v) {
                        if (array_key_exists($v['first_sort_id'], $newFirstCategoryList)) {
                            $this->newGrouponList[$k]['goods_first_catalog'] = $newFirstCategoryList[$v['first_sort_id']]['name'];

                            // 如果没有二级类目或者末级类目，自动用顶级类目填充
                            if (!$v['goods_second_catalog']) {
                                $this->newGrouponList[$k]['goods_second_catalog'] = $newFirstCategoryList[$v['first_sort_id']]['name'];
                            }
                            if (!$v['goods_three_catalog']) {
                                $this->newGrouponList[$k]['goods_three_catalog'] = $newFirstCategoryList[$v['first_sort_id']]['name'];
                            }
                        }
                    }
                } // end firstCategoryIds

            }
            */

        }
        return true;
    }

    /**
     * 初始化热销分 和 流行度分数
     */
    public function initFocusInfoDaily()
    {
        if (!$this->twitterIds) return false;

        $focus_db = Yii::app()->sdb_focus;
        if (date("d") == 1) {
            // 如果是每个月的第一天则到上一个月中去查找
            $yearMonth = date('Ym', strtotime("-1 month"));
        } else {
            $yearMonth = date('Ym');
        }
        $table     = "t_focus_goods_info_daily_{$yearMonth}";
        // 先找到最后一天
        $dt_sql    = "select max(dt) dt from {$table}";
        $last_date = $focus_db->createCommand($dt_sql)->queryScalar();
        // 查询cvr
        $sql       = "select twitter_id, sellrate_score,sellrate_type,popularity_score from {$table} where twitter_id in (" . implode(',', $this->twitterIds) . ") and dt='{$last_date}'";
        $cvrList   = $focus_db->createCommand($sql)->queryAll();
        foreach ($cvrList as $k=>$v) {
            if (array_key_exists($v['twitter_id'], $this->newGrouponList)) {
                $this->newGrouponList[$v['twitter_id']]['sellrate_score']   = $v['sellrate_score'];
                $this->newGrouponList[$v['twitter_id']]['popularity_score'] = $v['popularity_score'];
            }
        }
    }

    /**
     * 初始化团购历史
     */
    public function initTuanHistory()
    {
        if (!$this->twitterIds) return false;

        $brd_shop_db = Yii::app()->sdb_brd_shop;
        $nowTime = time();
        $sql  = "select twitter_id, id ,off_price, FROM_UNIXTIME( start_time, '%Y-%m-%d %H:%i') as history_start_time,FROM_UNIXTIME( end_time, '%Y-%m-%d %H:%i') as history_end_time from shop_groupon_info";
        $sql .= " where twitter_id in (" . implode(',', $this->twitterIds) . ") and audit_status=50 and end_time<{$nowTime}  order by id asc";
        $historyList = $brd_shop_db->createCommand($sql)->queryAll();
        foreach ($historyList as $k=>$v) {
            if (array_key_exists($v['twitter_id'], $this->newGrouponList)) {
                $tuanHistory = htmlspecialchars("{$v['history_start_time']}--{$v['history_end_time']}  <span style='color:red'>{$v['off_price']}</span>");
                $this->newGrouponList[$v['twitter_id']]['tuan_history'] = $tuanHistory;
            }
        }
    }

    /**
     * 初始化是否是高危商品
     */
    public function initDangerGoods()
    {
        if (!$this->twitterIds) return false;

        $date = date("Y-m-d", strtotime("-7 day"));
        $tableMonth = date("Ym", strtotime("-1 day"));

        $focus_db = Yii::app()->sdb_focus;

        $sql = "select * from t_focus_goods_daily_health_stat where twitter_id in (" . implode(',', $this->twitterIds) . ") and dt>'{$date}' and hazard_prob > 0 order by dt asc";
        $list = $focus_db->createCommand($sql)->queryAll();
        $newList = array();
        foreach ($list as $k=>$v) {
            $newList[$v['twitter_id']] = $v;
        }

        foreach ($this->newGrouponList as $k=>$v) {
            if (array_key_exists($v['twitter_id'], $newList)) {
                $this->newGrouponList[$v['twitter_id']]['is_danger'] = 1;
            } else {
                $this->newGrouponList[$v['twitter_id']]['is_danger'] = 0;
            }
        }
    }

    /**
     * 初始化是否是高危商家
     */
    public function initBadGoods()
    {
//         $redis = new Redis();
//         $redis->connect('127.0.0.1', 6379);

    	$redis = new Redisdb(Redisdb::REDIS_SERVER_DEFAULT);
    	$reids_key = 'allBadGoods';

        // 获取缓存记录
        //$redis->set('allBadGoods', '', 240);
        $allBadGoodsJson = $redis->get($reids_key, array());
        if ($allBadGoodsJson) {
            $allBadGoods = json_decode($allBadGoodsJson, true);
        } else {
            $allBadGoods = array();
        }

        if (!$allBadGoods) {
            $allBadGoods = array();
            $risk_stat_db = Yii::app()->sdb_risk_stat;
            $sql = "select goods_punish_ids from t_dolphin_stat_shop_penalty_record where punish_target=2 and is_valid=1 and punish_has_done=penalty_type";
            $res = $risk_stat_db->createCommand($sql)->queryAll();

            foreach ($res as $k=>$v) {
                $goodsIdsStr = $v['goods_punish_ids'];
                // 数据类型： 3261011239,231123885  3261012403,231123907  3261049797,231125013
                // 如果没有逗号应该直接是twitter_id   如果有逗号应该是 twitter_id,goods_id
                if ($goodsIdsStr) {
                    $goodsIdsArr = explode(" ", $goodsIdsStr);
                    foreach ($goodsIdsArr as $key=>$val) {
                        $ondGoodsIdsArr = explode(",", $val);
                        if ($ondGoodsIdsArr[0]) {
                            $allBadGoods[] = $ondGoodsIdsArr[0];
                        }
                    }
                }
            }
            // 设置最后一次操作的key
           $redis->set($reids_key, array(), json_encode($allBadGoods));
        }

        foreach ($this->newGrouponList as $k=>$v) {
            if (in_array($v['twitter_id'], $allBadGoods)) {
                $this->newGrouponList[$v['twitter_id']]['is_bad'] = 1;
            } else {
                $this->newGrouponList[$v['twitter_id']]['is_bad'] = 0;
            }
        }
    }

    /**
     * 获取团购列表
     * @param date $date  例：2015-04-11
     * @return array
     */
    public function getGrouponList($params)
    {
        // 时间
        if (isset($params['date']) && ($params['date'])) {
            $create_time = $params['date']." 00:00:00";
        } else {
            $create_time = date("Y-m-d")." 00:00:00";
        }

        $nextId = 0;
        // 下一个id
        if (isset($params['next_id']) && ($params['next_id'])) {
            $nextId = $params['next_id'];
        }

        // 如果有id按照id查询，如果没有id按照日期查询
        if ($nextId) {
            $sql = "SELECT * FROM `shop_groupon_info` WHERE `id` > '{$nextId}' ORDER BY `id` ASC LIMIT 2000";
        } else {
            $sql = "SELECT * FROM `shop_groupon_info` WHERE `create_time` >= '{$create_time}' ORDER BY `id` ASC";
        }

        $brd_shop_db = Yii::app()->sdb_brd_shop;
        $grouponList = $brd_shop_db->createCommand($sql)->queryAll();
        return $grouponList;
    }

    /**
     * 初始化团购列表，初始化为twitterid，goodsid 和 新的数组
     * @param array $grouponList
     */
    public function getGrouponTwitterIds($grouponList)
    {
        foreach ($grouponList as $k=>$v) {
            $this->newGrouponList[$v['twitter_id']] = array('groupon_id'=>$v['id'], 'twitter_id'=>$v['twitter_id'], 'goods_id'=>$v['goods_id'], 'create_time'=>$v['create_time'], 'goods_type'=>$v['goods_type'], 'tuan_history'=>'');
            $this->twitterIds[$v['twitter_id']]     = $v['twitter_id'];
            $this->goodsIds[$v['twitter_id']]       = $v['goods_id'];
        }
        // 在这里初始化团购历史，第一次插入数据库的时候初始化就可以了
        $this->initTuanHistory();

        return $this->twitterIds;
    }

    /**
     * 初始化团购列表
     * @param unknown $grouponList
     * @return multitype:
     */
    public function getGrouponTwitterIdsByRelation($grouponList)
    {
        // 每次都清空
        $this->newGrouponList = array();
        $this->twitterIds = array();
        $this->goodsIds   = array();

        foreach ($grouponList as $k=>$v) {
            $this->newGrouponList[$v['twitter_id']] = array('twitter_id'=>$v['twitter_id'], 'goods_id'=>$v['goods_id'], 'create_time'=>$v['create_time'], 'goods_type'=>$v['goods_type'], 'tuan_history'=>$v['tuan_history'], 'relation_id'=>$v['id']);
            $this->twitterIds[$v['twitter_id']]     = $v['twitter_id'];
            $this->goodsIds[$v['twitter_id']]       = $v['goods_id'];
        }

        return $this->twitterIds;
    }


    /**
     * 数组转换
     * @param unknown $arr
     * @param unknown $key
     * @return unknown|multitype:unknown
     */
    public function setArrayKey($arr, $key)
    {
        if (!$arr || !$key) return $arr;

        $newArr = array();
        foreach ($arr as $k=>$v) {
            if (isset($v[$key])) {
                $newArr[$v[$key]] = $v;
            }
        }

        return $newArr;
    }
}