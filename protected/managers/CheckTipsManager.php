<?php

class CheckTipsManager extends Manager
{
    /**
     * 审核元婴
     * 与数据库 t_groupon_check_tips 中的 type 对应
     */
    public static $tipsTypeEnum = array(
        // 1 => '初审',
        // 2 => '复审',
        // 3 => '样品审核',
        // 4 => '审核不通过',
        // 5 => '审核通过',
        // 6 => '排期'
        '10' => '等待初审',
        '20' => '初审成功',
        '21' => '初审失败',
        '30' => '复审成功',
        '31' => '复审失败',
        '40' => '验货成功',
        '41' => '验货失败',
        '50' => '排期成功',
        '51' => '排期失败'
    );

    /**
     * 状态
     * 与数据库 t_groupon_check_tips 中的 statis 对应
     */
    public static $tipsStatusEnum = array(
        1 => '正常',
        0 => '删除',
    );

    public function getList($params)
    {
        $sql  = "select * from t_groupon_check_tips";

        // where条件
        $where_string = "";
        $condition = "";
        // 排序条件
        $order = " ORDER BY id desc";

        // 类型
        if (!empty($params['type'])) {
            $where_string .= " AND type={$params['type']}";
        }
        //channel=1 是清仓活动 0为其他活动
        if(!empty($params['channel'])||$params['channel']==0){
            $condition = " and channel={$params['channel']}";
        }
        // 状态
        if (isset($params['status'])) {
            $where_string .= " AND status={$params['status']}";
        }

        // 如果没有where条件默认暂时所有正常的活动
        if (empty($where_string)) {
            $where_string = " where status=1";
        } else {
            $where_string = " where 1=1 " . $where_string;
        }
        //p($sql,$where_string);exit();
        $countSql  = "select count(*) from t_groupon_check_tips";
        $where_string .= $condition;
        $countSql .= $where_string;

        //p($params,$sql, $countSql);//exit();
        $sdb_groupon  = Yii::app()->sdb_groupon;
        $total        = $sdb_groupon->createCommand($countSql)->queryScalar();
        $out = array(
            'result' => array(),
            'pager'  => array(),
            'total' => $total,
        );

        if ($total) {
            //分页的使用
            $pager = new PageManager($total,50);
            $out['pager'] = $pager;
            //p($pager,$total);
            $where_string .= $order;
            $sql          .= $where_string;
            $sql          .= " {$pager->limit}";

            $list=$sdb_groupon->createCommand($sql)->queryAll();

            $out['result'] = $list;
        }
        return $out;
    }

    /**
     * 恢复活动
     * @param int $eventId
     * @return boolean|unknown
     */
    public function recoverCheckTips($id)
    {
        if (!$id) return false;

        $sql          = "update t_groupon_check_tips set status=1 where id=" . intval($id);
        $db_groupon   = Yii::app()->db_groupon;
        $result       = $db_groupon->createCommand($sql)->execute();

        return $result;
    }

    /**
     * 删除活动
     * @param int $eventId
     * @return boolean|unknown
     */
    public function deleteCheckTips($id)
    {
        if (!$id) return false;

        $sql          = "update t_groupon_check_tips set status=0 where id=" . intval($id);
        $db_groupon   = Yii::app()->db_groupon;
        $result       = $db_groupon->createCommand($sql)->execute();

        return $result;
    }
}