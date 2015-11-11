<?php
/**
 * NoticeManager.php
 * 通知公用文件
 * Author: ruidongsong@meilishuo.com
 * Date: 2015-7-28 下午5:59:07
 */
class NoticeManager extends Manager
{
    public static $channel_notice_cate_id_map = array(
        '1' => '2',
        '2' => '22',
        '3' => '23',
        '6' => '26'
    );

    /**
     * 通知分类
     */
    public static $notice_cate_id_map = array(
        '2'  => '团购',
        '22' => '秒杀',
        '23' => '清仓',
        '26' => '会员阶梯价'
    );

    /**
     * notice 状态 status
     */
    public static $notice_status_map = array(
            0 => '展示',
            1 => '不展示'
    );

    /**
     * 获取通知信息
     * @param number $notice_id
     * @return array
     */
    public static function getNoticeInfo($notice_id)
    {
        $notice_id = (int)$notice_id;
        if (!$notice_id) return array();

        $sdb_brd_shop = Yii::app()->sdb_brd_shop;

        $notice_sql  = "select * from brd_shop_posts where id={$notice_id}";
        $notice_info = $sdb_brd_shop->createCommand($notice_sql)->queryRow();
        if (!$notice_info) {
            return array();
        }
        return $notice_info;
    }

    /**
     * 获取通知列表
     * @param array $filter
     * @param string $order
     * @return array
     */
    public function getNoticeList($filter=array(), $order = " order by ctime desc")
    {
        $sql = "select id, title, ctime, author, cate_id,status from brd_shop_posts where 1=1 ";

        if (isset($filter['status'])) {
            $sql .= " and status={$filter['status']}";
        }
        if (isset($filter['cate_id'])) {
            if (is_array($filter['cate_id'])) {
                $sql .= " and cate_id in (".implode(",", $filter['cate_id']).")";
            } else {
                $sql .= " and cate_id = {$filter['cate_id']}";
            }
        }

        $sql .= $order;

        $sdb_brd_shop = Yii::app()->sdb_brd_shop;
        $result = $sdb_brd_shop->createCommand($sql)->queryAll();

        return $result;
    }


    /**
     * 恢复
     * @param int $eventId
     * @return boolean|unknown
     */
    public function recoverNotice($id)
    {
        if (!$id) return false;

        $sql          = "update brd_shop_posts set status=0 where id=" . intval($id);
        $db_groupon   = Yii::app()->db_brd_shop;
        $result       = $db_groupon->createCommand($sql)->execute();

        return $result;
    }

    /**
     * 删除
     * @param int $eventId
     * @return boolean|unknown
     */
    public function deleteNotice($id)
    {
        if (!$id) return false;

        $sql          = "update brd_shop_posts set status=1 where id=" . intval($id);
        $db_groupon   = Yii::app()->db_brd_shop;
        $result       = $db_groupon->createCommand($sql)->execute();

        return $result;
    }
}
?>