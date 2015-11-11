<?php
/**
 * VipEventManager.php
 * 会员阶梯价格活动
 * Author: ruidongsong@meilishuo.com
 * Date: 2015-10-22 下午6:52:10
 */

class VipEventManager extends Manager
{
    /**
     * vip等级折扣信息
     */
    public static $vip_level_map = array(
        '0' => 'V0',
        '1' => 'V1',
        '2' => 'V2',
        '3' => 'V3',
        '4' => 'V4',
    );
}
?>