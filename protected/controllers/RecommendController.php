<?php
/**
 * 商品推荐
 */
class RecommendController extends Controller
{
    /**
     * 展示排序商品列表
     */
    public function ActionIndex()
    {
        $data = array();
        $data = $this->util->getPoster(array('limit'=>1000));
        if (isset($data['data']['groupon_info']) && $data['data']['groupon_info']) {
            $twitterList = $data['data']['groupon_info'];
        } else {
            $twitterList = array();
        }
        //$brd_shop_db   = Yii::app()->sdb_brd_shop;
        //$sql = "SELECT * FROM shop_groupon_info WHERE `id` IN(810817,810818,810819,810820,810821,810823)";
        //$recommendTwitterList = $brd_shop_db->createCommand($sql)->queryAll();

        // groupon
        $recommendTwitterList = array();
        $groupon_db = Yii::app()->sdb_groupon;

        $now_date = date("Y-m-d");
        $sql = "SELECT `gid` FROM t_groupon_manual WHERE `date`='{$now_date}' AND `status`=1 ORDER BY `order` ASC";
        $gids = $groupon_db->createCommand($sql)->queryColumn();
        if (is_array($gids) && $gids) {
            $gidStr = implode(",", $gids);
            if ($gidStr) {
                $brd_shop_db   = Yii::app()->sdb_brd_shop;
                $sql = "SELECT * FROM shop_groupon_info WHERE `id` IN({$gidStr})";
                $recommendTwitterList = $brd_shop_db->createCommand($sql)->queryAll();
            }
        }

        // 重新排序
        $newRecommendTwitterList = array();
        foreach ($recommendTwitterList as $k=>$v) {
            $key = array_search($v['id'], $gids);
            if ($key !== false) {
                $newRecommendTwitterList[$key] = $v;
            } else {
                $newRecommendTwitterList[]     = $v;
            }
        }
        ksort($newRecommendTwitterList);

        $this->assign('recommendTwitterList', $newRecommendTwitterList);
        $this->assign('twitterList', $twitterList);
        $this->assign('sortArr', RecommendManager::$sortArr);
        $this->assign('cataArr', RecommendManager::$cataArr);
        $this->assign('sbaseArr', RecommendManager::$sbaseArr);

        $this->render('recommend/twitterList.html');
    }

    /**
     * 人工干预保存
     */
    public function ActionSaveRecommend()
    {
        $ids = Yii::app()->request->getPost('ids', '');
        // 拆分成数组
        $id_arr = explode(',', trim($ids, ','));
        if (!$id_arr) {
            exit(json_encode(array('succ'=>0, 'msg'=>'请选择要推荐的数据')));
        }

        $brd_shop_db  = Yii::app()->sdb_brd_shop;
        $groupon_db   = Yii::app()->db_groupon; //写链接主库
        $now_data     = date("Y-m-d");
        $add_time     = date("Y-m-d H:i:s");
        $new_arr      = array();

        foreach ($id_arr as $k=>$v) {
            $id = (int)$v;
            $good_info = $brd_shop_db->createCommand()->from('shop_groupon_info')->where('id=:id',array(':id'=>$id))->queryRow();
            if ($good_info) {
                $order = $k+1;
                // 插入数据库的sql
                $sql = "INSERT INTO t_groupon_manual(`gid`, `twitter_id`, `type`, `date`, `status`, `order`, `add_time`) VALUES('{$good_info['id']}','{$good_info['twitter_id']}','{$good_info['goods_type']}','{$now_data}','1','{$order}','{$add_time}')";
                $sql .= " ON DUPLICATE KEY UPDATE `order`='{$order}'";
                $groupon_db->createCommand($sql)->execute();
                /*$create_filter = array(
                    'gid'        =>$good_info['id'],
                    'twitter_id' => $good_info['twitter_id'],
                    'type'       => $good_info['goods_type'],
                    'date'       => $now_data,
                    'status'     => 1,
                    'order'      => $k,
                    'add_time'   => $add_time,
                    'good_name'  => $good_info['goods_name'],
                );*/
                // @FIXME 插入数据库

                $new_arr[] = $sql;
            }
        }

        echo json_encode(array('succ'=>1, 'msg'=>'添加成功~'));

        exit();
        var_dump($ids);
        echo json_encode($_POST);
    }

    /**
     * html页面ajax请求数据接口
     */
    public function ActionGetList()
    {
        $limit = Yii::app()->request->getPost('limit', 1000);
        // 分类
        $cata  = Yii::app()->request->getPost('cata', 0);
        // 排序类型 升序－降序
        $sort  = Yii::app()->request->getPost('sort', 0);
        // 排序
        $sbase = Yii::app()->request->getPost('sbase', 0);
        // 搜索－支持批量
        $gids  = Yii::app()->request->getPost('gids', '');

        // @FIXME 默认取1000个，不做分页
        $postData = array('limit'=>1000);
        // 判断参数
        if (array_key_exists($cata, RecommendManager::$cataArr)) {
            $postData['cata'] = $cata;
        }
        if (array_key_exists($sbase, RecommendManager::$sbaseArr)) {
            $postData['sbase'] = $sbase;
        }
        //  如果排序是默认和人气只有降序
        if (array_key_exists($sort, RecommendManager::$sortArr) && ($sbase != 0 && $sbase != 1)) {
            $postData['sort'] = $sort;
        }

        // 搜索
        if ($gids) {
            $postData['gids'] = $gids;
        }

        // 请求数据
        $data = $this->util->getPoster($postData);

        if (isset($data['data']['groupon_info']) && $data['data']['groupon_info']) {
            $twitterList = $data['data']['groupon_info'];
        } else {
            $twitterList = array();
        }
        // 数量
        if (isset($data['data']['totalNum']) && $data['data']['totalNum']) {
            $totalNum = $data['data']['totalNum'];
        } else {
            $totalNum = 0;
        }

        echo json_encode(array('succ'=>1, 'twitterList'=>$twitterList, 'totalNum'=>$totalNum, 'postData'=>$postData));
    }

    /**
     * 删除人工干预的内容
     */
    public function ActionDelete()
    {
        $twitterId = Yii::app()->request->getPost('twitter_id', 0);
        if (!$twitterId) {
            exit(json_encode(array('succ'=>0, 'msg'=>'请选择您要操作的信息')));
        }

        $groupon_db = Yii::app()->db_groupon;
        $now_date   = date("Y-m-d");
        $sql        = "DELETE FROM `t_groupon_manual` WHERE `twitter_id` = '{$twitterId}' AND `date`='{$now_date}'";
        $result     = $groupon_db->createCommand($sql)->execute();
        if ($result) {
            echo json_encode(array('succ'=>1, 'msg'=>'删除成功', 'r'=>$result));
        } else {
            echo json_encode(array('succ'=>0, 'msg'=>'删除失败', 'r'=>$result));
        }
        exit();
    }

    public function ActionTest()
    {
        // 810817,810818,810819
        $postData = array('gids'=>'101747', 'limit'=>1000);
        // 请求数据
        $data = $this->util->getPoster($postData);
        p($data);

        exit();
        $db   = Yii::app()->sdb_brd_shop;
        //$sql = "SELECT `twitter_id` FROM shop_groupon_info WHERE `id` in(768356,768358,768362,768360,768364,768366)";
        //$results = $db->createCommand($sql)->queryColumn();
        //$good_info = $db->createCommand()->select('id')->from('shop_groupon_info')->where('id=:id',array(':id'=>'768366,768364'))->queryRow();;
        //p($results, implode(",", $results));exit();
        $sql = "SELECT * FROM shop_groupon_info WHERE `id` IN(768358,768356,768362,768360,768364,768366)";
        $results = $db->createCommand($sql)->queryAll();
        p($results);exit();
        //array(768356,768358,768362,768360,768364,768366)
        //$ids=Yii::app()->db->createCommand()->select('id')->from('user')->where('id>:id',array(':id'=>1))->queryColumn();

        // $row=Yii::app()->db->createCommand()->select('id,username,city_id')->from('user')->where('id=:id',array(':id'=>2))->queryRow();
        $db = Yii::app()->sdb_brd_shop;
        $r= $db->createCommand()->from('shop_groupon_info')->where('id=:id',array(':id'=>768354))->queryRow();
        p($r);
        $r = Yii::app()->request->getPost('ids', array());
        print_r($r);
        //exit(json_encode(array('succ'=>1, $r)));
        //p(Yii::app()->request);
        //p(Yii::app()->sdb_brd_shop);

        $log = new \Phplib\Tools\Liblog("Groupon_poster.log", "normal");
        $log->w_log("aaplatform: " . $this->platform . ", offset: " . $this->offset . ", limit: " . $this->limit . ", cata: " . $this->cata . " , sbase:".$this->sbase." , sort:".$this->sort." , gids:".$this->gids);
    }
}

