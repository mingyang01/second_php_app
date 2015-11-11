<?php
class GrouponShopsRelationCommand extends Command
{
    public function main($args) {
        /**
         * 如果type是all 则跑全部数据 一天一次
         * 如果否则是增量跑，5分钟跑一次
         */

    	$start = time();
        if (isset($args['type']) && $args['type'] == 'all') {
            // 跑全部 一天一次
            $this->grouponShopsRelation->runAll();
        } else {
            // 增量同步
            $this->grouponShopsRelation->run();
        }
        $end = time();
        Json::outPut('QingcangShopInfo', $start, $end);
        return true;
    }
}
?>