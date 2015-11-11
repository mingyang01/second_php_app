<?php
class RecommendManager extends Manager
{
    /**
     * 人工干预团购列表 －商品分类
     */
    public static $cataArr = array(
            0 => array('title'=>'全部', 'en_title'=>'all'),
            1 => array('title'=>'衣服', 'en_title'=>'cloth'),
            2 => array('title'=>'鞋子', 'en_title'=>'shoes'),
            3 => array('title'=>'包包', 'en_title'=>'baobao'),
            4 => array('title'=>'配饰', 'en_title'=>'jiaju'),
            5 => array('title'=>'家居', 'en_title'=>'peishi'),
            6 => array('title'=>'美妆', 'en_title'=>'beauty'),
    );

    /**
     * 人工干预团购列表 － 排序规则
     * @var unknown
    */
    public static $sortArr = array(
            0 => '倒序',
            1 => '正序'
    );

    /**
     * 人工干预团购列表 － 排序类型
    */
    public static $sbaseArr = array(
            0 => '默认',
            1 => '人气',
            2 => '折扣',
            3 => '价格',
            4 => '销量',
    );

    /**
     * 将今天的数据复制到明天
     * 计划任务执行
     */
    public function todayListToTomorrow()
    {
        //exit('aaaaaaaa');
        $groupon_sdb = Yii::app()->sdb_groupon;
        $nowDate = date("Y-m-d");
        $tomorrowDate = date("Y-m-d", strtotime("+1 days"));

        $sql = "SELECT `gid` FROM t_groupon_manual WHERE `date`='{$tomorrowDate}' AND `status`=1 ORDER BY `order` ASC";
        $gids = $groupon_sdb->createCommand($sql)->queryColumn();
        if ($gids) {
            echo '明天已有数据';
            exit();
        }

        $sql = "SELECT * FROM t_groupon_manual WHERE `date`='{$nowDate}' AND `status`=1 ORDER BY `order` ASC";
        $list = $groupon_sdb->createCommand($sql)->queryAll();
        if (!$list) {
            echo '今天还没有数据';
            exit();
        }

        $groupon_db   = Yii::app()->db_groupon; //写链接主库
        $addTime = date("Y-m-d H:i:s");
        foreach ($list as $k=>$v) {
            // 插入数据库的sql
            $sql = "INSERT INTO t_groupon_manual(`gid`, `twitter_id`, `type`, `date`, `status`, `order`, `add_time`) VALUES('{$v['gid']}','{$v['twitter_id']}','{$v['type']}','{$tomorrowDate}','1','{$v['order']}','{$addTime}')";
            $sql .= " ON DUPLICATE KEY UPDATE `order`='{$v['order']}'";
            $groupon_db->createCommand($sql)->execute();
            //p($v,$sql);
        }

        echo "执行成功".$addTime;
        exit();
    }
}
?>