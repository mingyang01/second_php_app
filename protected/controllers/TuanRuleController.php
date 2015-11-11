<?php

class TuanRuleController extends Controller
{

    public function ActionNormalRule()
    {
        /*
        $normalRuleInfo = TuanRuleManager::getAllNoramlRlueInfo();


        $newNormalRuleInfo = array();
        foreach ($normalRuleInfo as $k=>$v) {
            $newNormalRuleInfo["'{$k}'"] = $v;
        }
        $sqlKey      = implode(',', array_keys($newNormalRuleInfo));
        $sql         = "select * from t_groupon_global_config where `key` in (".$sqlKey.") order by field(".$sqlKey.")";
        debug($sql);
        $sdb_groupon = Yii::app()->sdb_groupon;
        $result      = $sdb_groupon->createCommand($sql)->queryAll();

        $allNormalRuleInfo = TuanRuleManager::getAllNoramlRlueInfo();
        $param = array(
                'normalRuleList'     => $result,
                'allNormalRuleInfo'  => $allNormalRuleInfo
        );

        $this->render("tuanRule/editNormalRule.html", $param);
        */

        $request = Yii::app()->request;
        $category = $request->getQuery('category', 'normal');
        $rule_category_map = TuanRuleManager::$rule_category_map;
        if (!array_key_exists($category, $rule_category_map)) {
            throwMessage('类型错误','error');
        }
        $category_key = "noraml_category_{$category}_rule_map";
        $category_key_list = TuanRuleManager::$$category_key;
        //p($category_key_list);exit();

        $newNormalRuleInfo = array();
        foreach ($category_key_list as $k=>$v) {
            $newNormalRuleInfo["'{$k}'"] = $v;
        }
        $sqlKey      = implode(',', array_keys($newNormalRuleInfo));
        $sql         = "select * from t_groupon_global_config where `key` in (".$sqlKey.") order by field(".$sqlKey.")";
        $sdb_groupon = Yii::app()->sdb_groupon;
        $result      = $sdb_groupon->createCommand($sql)->queryAll();

        $this->assign('rule_list', $result);
        $this->assign('category', $category);
        $this->assign('rule_category_map', $rule_category_map);
        $this->render("tuanRule/editTuangouNormalRule.html");
    }

    public function ActionQingcangRule()
    {
        $request = Yii::app()->request;
        $category = $request->getQuery('category', 'normal');
        $rule_category_map = TuanRuleManager::$rule_category_map;
        if (!array_key_exists($category, $rule_category_map)) {
            throwMessage('类型错误','error');
        }
        $category_key = "noraml_category_{$category}_rule_map";
        $category_key_list = QingcangRuleManager::$$category_key;
        //p($category_key_list);exit();

        $newNormalRuleInfo = array();
        foreach ($category_key_list as $k=>$v) {
            $newNormalRuleInfo["'{$k}'"] = $v;
        }
        $sqlKey      = implode(',', array_keys($newNormalRuleInfo));
        $sql         = "select * from t_groupon_global_config where `key` in (".$sqlKey.") order by field(".$sqlKey.")";
        $sdb_groupon = Yii::app()->sdb_groupon;
        $result      = $sdb_groupon->createCommand($sql)->queryAll();

        $this->assign('rule_list', $result);
        $this->assign('category', $category);
        $this->assign('rule_category_map', $rule_category_map);
        $this->render("tuanRule/editQingcangNormalRule.html");
    }

    public function ActionMiaoshaRule()
    {
        $request = Yii::app()->request;
        $category = $request->getQuery('category', 'normal');
        $rule_category_map = TuanRuleManager::$rule_category_map;
        if (!array_key_exists($category, $rule_category_map)) {
            throwMessage('类型错误','error');
        }
        $category_key = "noraml_category_{$category}_rule_map";
        $category_key_list = MiaoshaRuleManager::$$category_key;
        //p($category_key_list);exit();

        $newNormalRuleInfo = array();
        foreach ($category_key_list as $k=>$v) {
            $newNormalRuleInfo["'{$k}'"] = $v;
        }
        $sqlKey      = implode(',', array_keys($newNormalRuleInfo));
        $sql         = "select * from t_groupon_global_config where `key` in (".$sqlKey.") order by field(".$sqlKey.")";
        $sdb_groupon = Yii::app()->sdb_groupon;
        $result      = $sdb_groupon->createCommand($sql)->queryAll();

        $this->assign('rule_list', $result);
        $this->assign('category', $category);
        $this->assign('rule_category_map', $rule_category_map);
        $this->render("tuanRule/editMiaoshaNormalRule.html");
    }

    /**
     * 修改活动规则
     */
    public function ActionSaveNormalRule()
    {
        $useTimeBegin = microtime(true);

        $request = Yii::app()->request;

        $ruleValue = $request->getPost('rule_value', '');
        $ruleKey   = $request->getPost('rule_key', '');

        $sdb_groupon = Yii::app()->sdb_groupon;
        $sql = "select * from t_groupon_global_config where `key` = '{$ruleKey}'";
        $ruleInfo  = $sdb_groupon->createCommand($sql)->queryRow();
        if (!$ruleInfo) {
            output(array('succ'=>0, 'msg'=>'规则不存在'));
        }
        if ($ruleInfo['value'] == $ruleValue) {
            output(array('succ'=>0, 'msg'=>'请改动规则后再来提交'));
        }

        $updateArr = array(
            'value'             => $ruleValue,
            'last_op_user'      => $this->user->username,
            'last_update_time'  => date("Y-m-d H:i:s")
        );

        $db_groupon = Yii::app()->db_groupon;
        $r = $db_groupon->createCommand()->update(
                't_groupon_global_config',
                $updateArr,
                '`key`=:key',
                array(':key'=>$ruleKey)
        );
        if (!$r) {
            output(array('succ'=>0, 'msg'=>'更新失败'));
        }

        $useTimeEnd = microtime(true);
        $useTime    = $useTimeEnd - $useTimeBegin;
        $logFiter = array(
                'user'          => $this->user->name,
                'name'          => '更改报名规则',
                'content'       => array('old_content' => $ruleInfo, 'new_content' => $updateArr),
                'param'         => array('key'=>$ruleKey),
                'resource_name' => 't_groupon_global_config',
                'resource_id'   => $ruleInfo['id'],
                'is_succ'       => 1,
                'use_time'      => number_format($useTime, 5)
        );
        // 增加日志
        $this->tuanLog->addLog($logFiter);

        output(array('succ'=>1, 'msg'=>'修改成功'));
    }

    /**
     * 修改活动规则是否生效
     */
    public function ActionEditRuleInvalid()
    {
        $useTimeBegin = microtime(true);

        $request = Yii::app()->request;

        $ruleKey   = $request->getPost('rule_key', '');

        $ruleInfo  = TuanRuleManager::getRuleInfo($ruleKey);
        if (!$ruleInfo) {
            output(array('succ'=>0, 'msg'=>'规则不存在'));
        }
        $updateArr = array(
            'last_op_user'      => $this->user->username,
            'last_update_time'  => date("Y-m-d H:i:s")
        );
        if (1 == $ruleInfo['invalid']) {
            $updateArr['invalid'] = 0;
        } else {
             $updateArr['invalid'] = 1;
        }

        $db_groupon = Yii::app()->db_groupon;
        $r = $db_groupon->createCommand()->update(
                't_groupon_global_config',
                $updateArr,
                '`key`=:key',
                array(':key'=>$ruleKey)
        );
        if (!$r) {
            output(array('succ'=>0, 'msg'=>'更新失败'));
        }

        $useTimeEnd = microtime(true);
        $useTime    = $useTimeEnd - $useTimeBegin;
        $logFiter = array(
                'user'          => $this->user->name,
                'name'          => '更改报名规则',
                'content'       => array('old_content' => $ruleInfo, 'new_content' => $updateArr),
                'param'         => array('key'=>$ruleKey),
                'resource_name' => 't_groupon_global_config',
                'resource_id'   => $ruleInfo['id'],
                'is_succ'       => 1,
                'use_time'      => number_format($useTime, 5)
        );
        // 增加日志
        $this->tuanLog->addLog($logFiter);

        output(array('succ'=>1, 'msg'=>'修改成功', 'invalid' => $updateArr['invalid']));
    }

    public function ActionTest()
    {

        $sql = "insert into t_groupon_global_config(`key`, `value`, `last_op_user`, `comment`) values";

        $arr = array('30', '0-90', '5', '11801,11803,11805', '3', '1', '85');
        $arr = array('30', '15-300', '5','10', '20',  '2', '85');
        $noraml_category_rule_map = array(
                'miaosha_apply_goods_online_num_'          => '商品在线数',
                'miaosha_apply_goods_online_time_range_'   => '商品上新时间',
                'miaosha_apply_goods_sale_num_'            => '商品30天累计销量',
                'miaosha_apply_shop_category_'             => '商家主营类目',
                'miaosha_apply_goods_comment_num_'         => '评价个数',
                'miaosha_apply_goods_popularity_score_'    => '流行分',
                'miaosha_apply_goods_comment_rate_'        => '商品好评率',
        );

        //$arr = array('4.5', '0.03', '0.03', '0.08', '0.003', '100', '24', '10', '3', '100843');
        /*
        $noraml_category_normal_rule_map = array(
                'miaosha_apply_shop_dsr_score'             => '商家DSR评分',
                'miaosha_apply_shop_reason_refund_rate'    => '店铺有理由退款率',
                'miaosha_apply_goods_reason_refund_rate'   => '单品有理由退款率',
                'miaosha_apply_shop_dispute_rate'          => '店铺纠纷率',
                'miaosha_apply_goods_dispute_rate'         => '单品纠纷率',
                'miaosha_apply_goods_repertory_limit'      => '秒杀最小库存限制',
                'miaosha_apply_shop_first_check_waits'     => '普通商家初审数量',
                'miaosha_apply_shop_first_check_fails'     => '所有商家初审失败次数',
                'miaosha_apply_shop_month_apply_limit'     => '自然月商家最多可报名次数',
                // 'miaosha_apply_shop_level'                 => '商家等级',
                'miaosha_apply_shop_white_list'            => '白名单商家',
        );
        */
        $noraml_category_rule_map = array(
            'miaosha_apply_goods_online_num_'          => '商品在线数',
            'miaosha_apply_goods_online_time_range_'   => '商品上新时间',
            'miaosha_apply_goods_sale_num_'            => '商品累计销量',
            'miaosha_apply_shop_category_'             => '商家主营类目',
            'miaosha_apply_goods_comment_num_'         => '评价个数',
            'miaosha_apply_goods_popularity_score_'    => '流行分',
            'miaosha_apply_goods_comment_rate_'        => '商品好评率',

        );
        /*
        $new_arr = array(
            array('nvzhuang','(女装)'),
                array('nvxie','(女鞋)'),
                array('nvbao','(女包)'),
                array('peishi','(配饰)'),
                array('jiaju','(家居)'),
                array('meizhuang','(美妆)'),
                array('nanzhuang','(男装)'),
                array('tongzhuang','(童装)'),
                array('shipin','(食品)'),
                array('tongxie','(童鞋)'),
                array('nanxie','(男鞋)'),
                array('nanbao','(男包)'),
        );
        */
        $new_arr = array(
                array('shumaxiaojiadian','(数码小家电)'),

        );
        $i = 0;
        foreach ($noraml_category_rule_map as $k=>$v){
            foreach ($new_arr as $key_1=>$val_1) {
                $key = $k.$val_1[0];
                $com = $v.$val_1[1];
                $val = $arr[$i];
                p("('{$key}', '{$val}', 'ruidongsong', '{$com}'), ");
                $sql .= "('{$key}', '{$val}', 'ruidongsong', '{$com}'), ";
            }
            $i++;
        }
        /*
        foreach ($new_arr as $k=>$v) {
            $i = 0;
            foreach ($noraml_category_rule_map as $key=>$val) {
                $new_key = $key.$v[0];
                $new_com = $val.$v[1];
                $new_val = $arr[$i];
                $i++;
                p("('{$new_key}', '{$new_val}', 'ruidongsong', '{$new_com}'), ");
                $sql .= "('{$new_key}', '{$new_val}', 'ruidongsong', '{$new_com}'), ";
            }
        }
        */
        /*
        $i = 0;
        foreach ($noraml_category_normal_rule_map as $k=>$v) {
            $new_val = $arr[$i];
            p("('{$k}', '{$new_val}', 'ruidongsong', '{$v}'), ");
            $sql .= "('{$k}', '{$new_val}', 'ruidongsong', '{$v}'), ";
            $i++;
        }
        */
        p($sql);
    }
}
?>