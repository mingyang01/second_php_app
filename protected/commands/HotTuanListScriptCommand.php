<?php
/**
 * HotTuanListScriptCommand.php
 * 团购爆款商品脚本
 * Author: ruidongsong@meilishuo.com
 * Date: 2015-10-20 下午4:15:32
 */

// 加载公用函数库
require_once APP_PATH . "/protected/config/helper.php";

class HotTuanListScriptCommand extends Command
{
    public function main($args)
    {
        /**
         *  团购爆款商品脚本
         */
        $this->hotTuanListScript->run();
    }
}
?>