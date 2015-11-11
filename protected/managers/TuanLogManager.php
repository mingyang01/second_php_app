<?php
/**
 * log 日志
 */

class TuanLogManager extends Manager
{
    /**
     * 添加日志
     * @param array $params
     */
    public function addLog($params=array())
    {
        $createFilter = array();

        if (isset($params['user']) && $params['user']) {
            $createFilter['user'] = $params['user'];
        }
        if (isset($params['resource_name']) && $params['resource_name']) {
            $createFilter['resource_name'] = $params['resource_name'];
        }
        if (isset($params['resource_id']) && $params['resource_id']) {
            $createFilter['resource_id'] = $params['resource_id'];
        }
        if (isset($params['twitter_id']) && $params['twitter_id']) {
            $createFilter['twitter_id'] = $params['twitter_id'];
        }
        if (isset($params['groupon_id']) && $params['groupon_id']) {
            $createFilter['groupon_id'] = $params['groupon_id'];
        }
        if (isset($params['name']) && $params['name']) {
            $createFilter['name'] = $params['name'];
        }
        if (isset($params['use_time'])) {
            $createFilter['use_time'] = $params['use_time'];
        }

        if (isset($params['content']) && $params['content']) {
            $createFilter['content'] = is_array($params['content']) ? serialize($params['content']) : $params['content'];
        } else {
            $createFilter['content'] = '';
        }
        if (isset($params['param']) && $params['param']) {
            $createFilter['param'] = is_array($params['param']) ? serialize($params['param']) : $params['param'];
        } else {
            $createFilter['param'] = '';
        }
        if (isset($params['url']) && $params['url']) {
            $createFilter['url'] = $params['url'];
        }
        if (!isset($params['ip']) || !$params['ip']) {
            $createFilter['ip'] = $params['ip'] ? $params['ip'] : Yii::app()->request->getUserHostAddress();
        }

        if (isset($params['is_succ'])) {
            $createFilter['is_succ'] = $params['is_succ'];
        }
        $createFilter['add_time'] = date("Y-m-d H:i:s");
        $db_groupon = Yii::app()->db_groupon;
        $db_groupon->createCommand()->insert('t_groupon_log', $createFilter);

        return true;
    }

    public function addSystemLog($params=array())
    {
        $name = $this->getAction()->getId();  // controller
        p($name);
    }

}
?>