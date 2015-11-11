<?php
class GrouponListRelationCommand extends Command
{
    public function main($args)
    {
        /**
         *  如果type是all 则跑全部数据 一天一次
         *  如果否则是增量跑，5分钟跑一次
         */
        if (isset($args['type']) && $args['type'] == 'all') {
            $this->grouponListRelation->runAll();
        } else {
            $this->grouponListRelation->run();
        }
    }
}
?>