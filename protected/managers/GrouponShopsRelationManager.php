<?php
/**
 * 团购商铺信息同步脚本
 * @author ruidongsong
 */
class GrouponShopsRelationManager extends Manager
{
    private $newShopsList = array();
    private $shopsIds     = array();
    private $crontabNextGrouponIdKey = 'crontabNextGrouponIdToShops';
    private $shop;

    /** 团购商家ka等级列表 */
    static public $kaLevelList = array(
        '120' => array(
            102243,104875,186366,114877,108043,107889,101841,183262,108107,107583,106761,111948,129975,103569,111722,145417,104655,101313,104543,104395,100014,138887,184334,104845,118187,161075,111289,119399,101949,101747,101649,121395,102267,150807,107285,182646,185288,108463,103339,103565,101667,107313,103943,107585,120593,113131,108159,184342,108410,115248,138349,105915,116849,113227,113386,103003,114520,172037,172707,
        ),
        '240' => array(
            102037,102973,105329,1002868,102997,113283,169501,103083,103819,106517,105191,104777,104049,102493,102077,108500,106093,108230,102171,103417,102357,106621,113903,102113,102289,104625,104521,111744,101661,102441,113274,104095,108101,150673,103475,107511,159453,102231,101633,172947,1002792,
        ),
        '360' => array(
            102225,104815,151751,101645,101913,108174,108333,103525,101823,105349,104205,130925,111090,10010615,124201,113486,104351,102901,161797,102011,105059,164101,
        ),
        '480' => array(
            103391,103509,101791,111871,103447,102035,103027,158471,
        ),
        '600' => array(
            111348,102063,105425,143213,140909,108017,104141,101943,194692,157309,107101,174267,179768,
        )
    );

    /** 数据库插入的字段 */
    private $dbKey = array(
        'shop_id', 'shop_nick', 'partner_tel', 'partner_qq', 'major', 'level', 'ka_level', 'is_realshot', 'health_socre', 'glod_window', 'area',
    	'cs_level', 'reason_refund_rate', 'gmv_30', 'paid_goods_num_30', 'paid_goods_num_7', 'gmv_7', 'shop_buy_rate'
    );

    public function runShopInfo()
    {
        $per_page = 2000;
        $page     = Yii::app()->request->getParam('page', 1);
        $limit    = " ORDER BY `id` ASC LIMIT ".($page-1)*$per_page.", $per_page";

        $sql         = "SELECT * FROM `shop_groupon_info` where id >= 1599746 ".$limit;

        $brd_shop_db = Yii::app()->sdb_brd_shop;
        $shopsList   = $brd_shop_db->createCommand($sql)->queryAll();
        if (!$shopsList) exit('已导入完成,第'.($page-1).'页');

        $shopsIds    = $this->getGrouponShopIds($shopsList);
        $succNum = $this->insertDB();

        echo '第'.$page.'页，同步完毕:'.$succNum;
        $page++;
        echo "<script>window.location.href='/test/runAllShop?page=".$page."'</script>";
        exit('同步完毕:'.$succNum);
    }

    /**
     * 跑全部的数据
     */
    public function runAll()
    {
        set_time_limit(0);
        ini_set("memory_limit","-1");

        // 用分页跑全部shop_groupon_info的数据
        /*
        $per_page = 2000;
        $page     = Yii::app()->request->getParam('page', 1);
        $limit    = " ORDER BY `id` ASC LIMIT ".($page-1)*$per_page.", $per_page";

        $sql         = "SELECT * FROM `shop_groupon_info` ".$limit;

        $brd_shop_db = Yii::app()->sdb_brd_shop;
        $grouponList = $brd_shop_db->createCommand($sql)->queryAll();
        if (!$grouponList) exit('已导入完成,第'.($page-1).'页');

        $shopsIds  = $this->getGrouponShopIds($grouponList);

        $succNum = $this->insertDB();

        echo '第'.$page.'页，同步完毕:'.$succNum;
        $page++;
        echo "<script>window.location.href='/event/runAll?page=".$page."'</script>";
        exit('同步完毕:'.$succNum);
        */

        $brd_shop_db = Yii::app()->sdb_brd_shop;
        $perpage     = 200;
        $intval      = 500;
        $i           = 0;

        $limit       = " ORDER BY `id` ASC LIMIT " . ($i * $perpage) . ", $perpage";
        $sql         = "SELECT * FROM `brd_shop_groupon_shops_relation` ".$limit;
        $shopsList   = $brd_shop_db->createCommand($sql)->queryAll();

        while(!empty ($shopsList)) {
            $twitterIds  = $this->getGrouponShopsIdsByRelation($shopsList);
            $succNum = $this->insertDB();

            echo "{$succNum}\r\n";
            //usleep($intval);
            $i++;

            $limit       = " ORDER BY `id` ASC LIMIT " . ($i * $perpage) . ", $perpage";
            $sql         = "SELECT * FROM `brd_shop_groupon_shops_relation` ".$limit;
            $shopsList   = $brd_shop_db->createCommand($sql)->queryAll();
        }
        echo 'success';
    }

    /**
     * 增量跑
     */
    public function run() {

        $redis = new Redisdb(Redisdb::REDIS_SERVER_DEFAULT);

        // 获取最后一次操作的id
        $nextId = $redis->get($this->crontabNextGrouponIdKey, array());

        if ($nextId < 1400000) {
            $grouponList = $this->getGrouponList(array('date'=>date("Y-m-d")));
        } else {
            $grouponList = $this->getGrouponList(array('next_id'=>$nextId));
        }

        if (!$grouponList) exit('没有团购数据');

        $shopsIds = $this->getGrouponShopIds($grouponList);

        $r = $this->insertDB($redis);
        //$r = $this->insertDB();

        echo "成功插入:".$r." 最后一次记录id：".$redis->get($this->crontabNextGrouponIdKey, array());
        exit();
    }

    /**
     * 插入数据库
     * @param object $redisObj
     * @FIXME 这里请传入redis对象，用作记录最后一次插入的groupon自增id
     */
    private function insertDB($redisObj=null) {

    	$this->shop = new ShopManager();

        $this->initShopInfo();
        $this->initHealthSocre();
        $this->initKaLevel();
        $this->initDangerShops();
        //CS数据同步
        $this->initCsLevel();
        //黄金橱窗数据
        $this->initWindows();
        //有理由退款率；
        $this->initReasonRefund();
        //近30天销售量；近30天GMV；近7天销售量；近7天GMV；转换率（30天）
        $this->initSaleInfo();

        $bar_shop_db = Yii::app()->db_brd_shop;
        $succNum = 0;
        foreach ($this->newShopsList as $k=>$v) {
            // 最后一次操作的groupon_id，用于记录到缓存中下次增量插入操作
            $lasgGrouponId = $v['groupon_id'];
            // @FIXME 记得一定要删除groupon_id, 否则数据库会报错
            unset($v['groupon_id']);

            $insertKey = array();
            $insertVal = array();
            $updateSql = "";
            $succKey   = 0;

            // @FIXME 这里请注意 addslashes  转译值，防止'等字符
            foreach ($v as $key=>$val) {
                $insertKey[] = $key;
                $insertVal[] = "'".addslashes($val)."'";
                // 组装更新sql
                if ($succKey == 0) {
                    $updateSql .= " {$key}='".addslashes($val)."'";
                } else {
                    $updateSql .= ", {$key}='".addslashes($val)."'";
                }
                $succKey++;
            }
			try {
				$sql  = "INSERT INTO brd_shop_groupon_shops_relation";

				$sql .= "(" . implode(",", $insertKey) . ")";
				$sql .= "VALUES(" . implode(",", $insertVal) . ")";
				$sql .= " ON DUPLICATE KEY UPDATE ";
				$sql .= $updateSql;

				//$sql .= "(`shop_id`, `shop_nick`, `partner_tel`, `partner_qq`, `major`, `level`, `ka_level`, `is_realshot`, `health_socre`)";
				//$sql .= "VALUES('{$v['shop_id']}', '{$v['shop_nick']}', '{$v['partner_tel']}', '{$v['partner_qq']}', '{$v['major']}'";
				//$sql .= ", '{$v['level']}', '{$v['ka_level']}', '{$v['is_realshot']}', '{$v['health_socre']}')";
				//$sql .= " ON DUPLICATE KEY UPDATE `shop_nick`='{$v['shop_nick']}', `partner_tel`='{$v['partner_tel']}', `partner_qq`='{$v['partner_qq']}'";
				//$sql .= ", `major`='{$v['major']}', `level`='{$v['level']}', `ka_level`='{$v['ka_level']}', `is_realshot`='{$v['is_realshot']}', `health_socre`='{$v['health_socre']}'";

				$r = $bar_shop_db->createCommand($sql)->execute();
				//$r = 1;
				if ($r) {
					if ($redisObj) {
						// 设置最后一次操作的key
						$redisObj->set($this->crontabNextGrouponIdKey, array(), $lasgGrouponId);
					}

					$succNum++;
				}
			} catch (Exception $e) {
				MailManager::sendCommMail('linglingqi', '清仓数据同步', $sql);
			}

        }

        return $succNum;
    }

    //黄金橱窗数据
    public function initWindows() {

    	$windows = $this->shop->getShopWindow($this->shopsIds);

    	if(!$windows) {
    		return false;
    	}

    	foreach($windows as $k=>$v) {
    		if (isset($this->newShopsList[$v['shop_id']])) {
    			$this->newShopsList[$v['shop_id']]['glod_window'] = $v['glod_window'];
    		}
    	}
    	return true;
    }

    //有理由退款率；
    public function initReasonRefund() {

    	$resons = $this->shop->getShopHealthStat($this->shopsIds);

    	if(!$resons) {
    		return false;
    	}

    	foreach($resons as $k=>$v) {
    		if (isset($this->newShopsList[$v['shop_id']])) {
    			$this->newShopsList[$v['shop_id']]['reason_refund_rate'] = $v['periods_reason_refund_rate'];
    		}
    	}
    	return true;
    }

    //近30天销售量；近30天GMV；近7天销售量；近7天GMV；转换率（30天）
    public function initSaleInfo() {

    	$time =  date("Y-m-d", strtotime("-1 month"));
		$time1 = date("Y-m-d", strtotime("-1 week"));
		$coral_list_30 = $this->shop->getShopCoralDaily($this->shopsIds, $time);
		if ($coral_list_30) {
			foreach($coral_list_30 as $key30=>$val30) {
				if (isset($this->newShopsList[$val30['shop_id']])) {
					$this->newShopsList[$val30['shop_id']]['gmv_30'] = $val30['gmv'];
					$this->newShopsList[$val30['shop_id']]['paid_goods_num_30'] = $val30['sale'];
					$this->newShopsList[$val30['shop_id']]['shop_buy_rate'] = $val30['shop_buy_rate'];
				}
			}
		}

		$coral_list_7 = $this->shop->getShopCoralDaily($this->shopsIds, $time);
		if ($coral_list_7) {
			foreach($coral_list_7 as $key7=>$val7) {
				if (isset($this->newShopsList[$val7['shop_id']])) {
					$this->newShopsList[$val7['shop_id']]['gmv_7'] = $val7['gmv'];
					$this->newShopsList[$val7['shop_id']]['paid_goods_num_7'] = $val7['sale'];
				}
			}
		}
		return true;
    }

    //获取店铺CS信息
    public function initCsLevel() {

    	$shopInfo = $this->shop->getShopStatus($this->shopsIds);

    	if(!$shopInfo) {
    		return false;
    	}

    	foreach($shopInfo as $k=>$v) {
    		if (isset($this->newShopsList[$v['shop_id']])) {
    			$this->newShopsList[$v['shop_id']]['cs_level'] = $v['cs_level'];
    		}
    	}
    	return true;
    }

    /**
     * 初始化商铺基本信息
     */
    public function initShopInfo()
    {
        $sdb_focus     = Yii::app()->sdb_focus;
        $shopsQuerySql = "SELECT * FROM `t_focus_shop_info` WHERE `shop_id` IN (" . implode(",", $this->shopsIds) . ")";
        $shopsList     = $sdb_focus->createCommand($shopsQuerySql)->queryAll();

        if (!$shopsList) return false;

        foreach($shopsList as $k=>$v) {
            if (array_key_exists($v['shop_id'], $this->newShopsList)) {
                // 商铺id
                $this->newShopsList[$v['shop_id']]['shop_id']  = $v['shop_id'];
                // 商铺昵称
                $this->newShopsList[$v['shop_id']]['shop_nick']  = $v['shop_nick'];
                // 商家电话
                $this->newShopsList[$v['shop_id']]['partner_tel']  = $v['partner_tel'];
                // 商家QQ
                $this->newShopsList[$v['shop_id']]['partner_qq']  = $v['partner_qq'];
                // 主营类目
                // @FIXME focus库t_focus_shop_info中的  major 是错误的
                $this->newShopsList[$v['shop_id']]['major']  = $v['mayjor'];
                // 商家等级 10：头部商家；0：普通商家；-10：高危商家
                $this->newShopsList[$v['shop_id']]['level']  = $v['level'];
                // 是否实拍 0否 1是
                $this->newShopsList[$v['shop_id']]['is_realshot']  = $v['is_realshot'];
                //店铺区域
                $this->newShopsList[$v['shop_id']]['area']  = $v['partner_area_id'];
            }
        }

        return true;
    }

    /**
     * 初始化健康度
     */
    public function initHealthSocre()
    {
        $sdb_focus     = Yii::app()->sdb_focus;
        //$shopsQuerySql = "select * from (select * from t_focus_shop_info_daily where shop_id in (" . implode(",", $this->shopsIds) . ") order by dt desc) as tb1 group by shop_id";

        // 先找到最后一天
        $dtSql    = "select max(dt) dt from t_focus_shop_info_daily";
        $lastDate = $sdb_focus->createCommand($dtSql)->queryScalar();

        // 查找列表
        $shopsQuerySql = "select * from t_focus_shop_info_daily where dt='{$lastDate}' and shop_id in (" . implode(",", $this->shopsIds) . ")";
        $shopsDayList  = $sdb_focus->createCommand($shopsQuerySql)->queryAll();

        if (!$shopsDayList) return false;

        foreach($shopsDayList as $k=>$v) {
            if (array_key_exists($v['shop_id'], $this->newShopsList)) {
                // 商铺健康度
                $this->newShopsList[$v['shop_id']]['health_socre']  = $v['health_socre'];
            }
        }

        return true;
    }

    /**
     * 初始化ka等级
     */
    public function initKaLevel()
    {
        foreach($this->newShopsList as $k=>$v) {
            $kaLevel = $this->getKaLevel($v['shop_id']);
            // 商铺ka等级
            $this->newShopsList[$v['shop_id']]['ka_level']  = $kaLevel;
        }

        return true;
    }

    /**
     * 获取ka等级
     * @param int $shopId
     * @return numbe
     */
    public function getKaLevel($shopId)
    {
        if (!$shopId) return 0;
        foreach (self::$kaLevelList as $k=>$v) {
            if (in_array($shopId, $v)) {
                return $k;
                break;
            }
        }
        return 0;
    }

    /**
     * 初始化是否是高危商家
     */
    public function initDangerShops()
    {
        $date = date("Y-m-d", strtotime("-1 days"));
        $tableMonth = date("Ym", strtotime("-1 days"));

        $focus_db = Yii::app()->sdb_focus;
        $sql = "select shop_id, bad from t_focus_shop_level_bad where shop_id in (" . implode(",", $this->shopsIds) . ") and dt='{$date}'";
        $list = $focus_db->createCommand($sql)->queryAll();
        $newList = array();
        foreach ($list as $k=>$v) {
            $newList[$v['shop_id']] = $v;
        }

        foreach($this->newShopsList as $k=>$v) {
            if (array_key_exists($v['shop_id'], $newList) && $newList[$v['shop_id']]['bad'] == 1) {
                $this->newShopsList[$v['shop_id']]['is_danger'] = 1;
            } else {
                $this->newShopsList[$v['shop_id']]['is_danger'] = 0;
            }
        }

        return true;
    }

    /**
     * 获取团购列表
     * @param date $date  例：2015-04-11
     * @return array
     */
    public function getGrouponList($params) {

    	$nextId = '';

        // 时间
        if (isset($params['date']) && ($params['date'])) {
            $create_time = $params['date']." 00:00:00";
        } else {
            $create_time = date("Y-m-d")." 00:00:00";
        }

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
     * 初始化团购列表，初始化为shop_id 和 新的数组
     * @param array $shopsIds
     */
    public function getGrouponShopIds($grouponList)
    {
        foreach ($grouponList as $k=>$v) {
            $this->newShopsList[$v['shop_id']] = array('groupon_id'=>$v['id'], 'shop_id'=>$v['shop_id']);
            $this->shopsIds[$v['shop_id']]     = $v['shop_id'];
        }

        return $this->shopsIds;
    }

    /**
     * 初始化团购列表
     * @param unknown $grouponList
     * @return multitype:
     */
    public function getGrouponShopsIdsByRelation($shopsList)
    {
        // 每次都清空
        $this->newShopsList = array();
        $this->shopsIds     = array();
        foreach ($shopsList as $k=>$v) {
            $this->newShopsList[$v['shop_id']] = array('groupon_id'=>0, 'shop_id'=>$v['shop_id']);
            $this->shopsIds[$v['shop_id']]     = $v['shop_id'];
        }

        return $this->shopsIds;
    }
}