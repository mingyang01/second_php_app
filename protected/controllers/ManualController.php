<?php
// chao
class ManualController extends Controller
{
    public function ActionWeek()
    {
        $request = Yii::app()->request;
        $date = $request->getQuery('date', date('Y-m-d', time()));
        // 1 已到 2 未到
        $type = $request->getQuery('type', 1);

        $list = $this->manual->getWeekList($date);

        if ($type == 1) {
            if ($list) {
                $currentList = $this->manual->getCurrentList($date, $list, 0, $type);
            } else {
                $currentList = array();
            }
        } elseif ($type == 2) {
            $currentList = $this->manual->getCurrentList($date, array(), 0, $type);
            foreach ($currentList as $key => $value) {
                if (in_array($value['gid'] , $list)) {
                    unset($currentList[$key]);
                }
            }
        }

        $this->render('manual/week.html', array('date'=>$date, 'type'=>$type, 'data'=>$currentList, 'count'=>count($currentList)));
    }

    //////////////////////// HOME /////////////////////////
    public function ActionWorth()
    {
        $request = Yii::app()->request;
        $date = $request->getQuery('date', date('Y-m-d', time()));
        // 1 已到 2 未到
        $type = $request->getQuery('type', 1);

        $list = $this->manual->getWorthList($date);

        if ($type == 1) {
            if ($list) {
                $currentList = $this->manual->getCurrentList($date, $list, 0, $type);
            } else {
                $currentList = array();
            }
        } elseif ($type == 2) {
            $currentList = $this->manual->getCurrentList($date, array(), 0, $type);
            foreach ($currentList as $key => $value) {
                if (in_array($value['gid'] , $list)) {
                    unset($currentList[$key]);
                }
            }
        }
        $this->render('manual/worth.html', array('date'=>$date,
            'type'=>$type, 'data'=>$currentList,
            'count'=>count($currentList)));
    }

    ////////////////////////最后疯抢////////////////////////////////////
    public function ActionLast()
    {
        $request = Yii::app()->request;
        $date = $request->getQuery('date', date('Y-m-d', time()));
        // 1 已到 2 未到
        $type = $request->getQuery('type', 1);

        $list = $this->manual->getLastList($date);

        if ($type == 1) {
            if ($list) {
                $currentList = $this->manual->getCurrentList($date, $list, 1, $type);
            } else {
                $currentList = array();
            }
        } elseif ($type == 2) {
            $currentList = $this->manual->getCurrentList($date, array(), 1, $type);
            foreach ($currentList as $key => $value) {
                if (in_array($value['gid'] , $list)) {
                    unset($currentList[$key]);
                }
            }
        }
        $this->render('manual/last.html', array('date'=>$date,
            'type'=>$type, 'data'=>$currentList,
            'count'=>count($currentList)));
    }

    public function ActionDeleteFromLast($gid)
    {
        $sql = "update brd_shop_groupon_week_select_info set status=1 where gid={$gid}";

        $db  = Yii::app()->db_brd_shop;
        $resultes = $db->createCommand($sql)->excute();

        // todo check delete status
        echo json_encode(array('code' => 1, 'data' => array()));
    }

    public function ActionAddToLast(array $gids, array $tids, array $starts, array $ends)
    {
        // foreach ($gids as $k => $v) {
        //     $update = array(
        //         'gid'        => $v,
        //         'twitter_id' => $tids[$k],
        //         'start_time' => $starts[$k],
        //         'end_time'   => $ends[$k]
        //     );

        //     $where = array('gid' => $v);

        //     // $sql = "INSERT table (gid, twitter_id, start_time, end_time) values (?,?,?,?)
        //     //     ON DUPLICATE KEY UPDATE auto_name='yourname'";

        //     $sql = "select gid from brd_shop_groupon_week_select_info
        //         where gid = $gid and status = 0";
        //     $db  = Yii::app()->db_brd_shop;
        //     $resultes = $db->createCommand($sql)->quer();



        //     $model->set('brd_shop_groupon_week_select_info', $update_info, $where);
        // }
        // echo json_encode(array('code' => 1, 'data' => array()));
    }

    public function ActionSaveLast(array $gids)
    {
        $count   = count($gids);
        foreach ($gids as $key => $gid) {
            $order = $count;
            $sql   = "update brd_shop_groupon_week_select_info
                set `order`={$order} where gid={$gid}";

            $db  = Yii::app()->db_brd_shop;
            $resultes = $db->createCommand($sql)->excute();
            $count = $count - 1;
        }

        echo json_encode(array('code' => 1, 'data' => array()));
    }
    public function ActionIndex()
    {
        $db = Yii::app()->sdb_brd_shop;
        CActiveRecord::$db = $db;
        $weekList = WeekSelect::model()->findByPk(1887);
        var_dump($weekList);
    }

    /**
     * 保存商品
     */
    public function ActionAddToGoods()
    {
        $request       = Yii::app()->request;
        $tuanIdsStr    = $request->getPost('tuan_id','');

        // 判断要操作的数据表
        $db            = $request->getPost('db','');
        $dbInfo        = ManualManager::$dbInfo;
        if (!array_key_exists($db, $dbInfo)) {
            output(array('succ'=>0, 'msg'=>'非法操作'));
        }

        if (!$tuanIdsStr) {
            output(array('succ'=>0, 'msg'=>'请选择加入排期的商品'));
        }

        $sdb_brd_shop = yii::app()->sdb_brd_shop;
        $db_brd_shop  = yii::app()->db_brd_shop;
        $tuanIdsArr   = explode(",", $tuanIdsStr);
        $succNum=0;
        $errNum =0;
        $rr = array();
        foreach ($tuanIdsArr as $k=>$v) {
            $sql      = "select * from shop_groupon_info where id ='{$v}'";
            $tuanInfo = $sdb_brd_shop->createCommand($sql)->queryRow();
            if (!$tuanInfo) {
                $errNum++;
                continue;
            }

            // 开始时间和结束时间
            $startTime  = date("Y-m-d H:i:s", $tuanInfo['start_time']);
            $endTime    = date("Y-m-d H:i:s", $tuanInfo['end_time']);

            $insertSql  = "insert {$dbInfo[$db]}(`gid`,`twitter_id`,`start_time`,`end_time`,`status`) values(";
            $insertSql .= "'{$v}', '{$tuanInfo['twitter_id']}', '{$startTime}', '{$endTime}', '0')";
            $insertSql .= " ON DUPLICATE KEY UPDATE `start_time`='{$startTime}', `end_time`='{$endTime}', `status`='0'";

            $rr[] = $insertSql;
            $r = $db_brd_shop->createCommand($insertSql)->execute();

            $succNum++;
        }
        output(array('succ'=>1, 'msg'=>'success', 'succ_num'=>$succNum, 'err_num'=>$errNum, $rr, $tuanInfo));
    }

    /**
     * 排序商品
     */
    public function ActionAddToSort()
    {
        $request       = Yii::app()->request;
        $tuanIdsStr    = $request->getPost('tuan_id','');
        $db            = $request->getPost('db','');
        $dbInfo        = ManualManager::$dbInfo;

        if (!array_key_exists($db, $dbInfo)) {
            output(array('succ'=>0, 'msg'=>'非法操作'));
        }

        $tuanIdsArr    = explode(",", $tuanIdsStr);
        $db_brd_shop   = Yii::app()->db_brd_shop;
        $rank = count($tuanIdsArr);
        // @FIXME 注意，是倒叙排序
        foreach ($tuanIdsArr as $k=>$v) {
            $grouponId = (int)$v;
            $updateSql = "update {$dbInfo[$db]} set `order`={$rank} where gid={$grouponId}";
            $update_result = $db_brd_shop->createCommand($updateSql)->execute();
            $rank--;
        }

        output(array('succ'=>1, 'msg'=>'排序成功'));
    }

    /**
     * 删除商品
     */
    public function ActionDeleteGoods()
    {
        $request       = Yii::app()->request;
        $tuan_id       = $request->getPost('tuan_id', 0);
        if (!$tuan_id) {
            output(array('succ'=>0,'msg'=>'请传入团购id'));
        }
        // 判断要操作的数据表
        $db            = $request->getPost('db','');
        $dbInfo        = ManualManager::$dbInfo;
        if (!array_key_exists($db, $dbInfo)) {
            output(array('succ'=>0, 'msg'=>'非法操作'));
        }

        $sql = "update {$dbInfo[$db]} set status=1 where gid={$tuan_id}";

        $db  = Yii::app()->db_brd_shop;
        $resultes = $db->createCommand($sql)->execute();

        echo json_encode(array('succ' => 1, 'data' => array()));
    }
}