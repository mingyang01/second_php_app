<?php

class ExportManager extends Manager
{

    public function exportGrouponListHtml($list, $title)
    {
        $titles = array('宝贝', '团购id', 'twitter_id', '店铺', '店铺ID', '原价', '折扣价', '折扣',  '一级分类', '末级分类', 'cvr'
                ,'是否精品', 'ka等级', '库存', '销量'
                , '商家电话', '商家QQ', '审核状态', '排期时间', '报名时间');

        $columns = array('name', 'gid', 'tid', 'shop_nick', 'shop', 'origin', 'price', 'rate', 'goods_first_catalog', 'goods_three_catalog', 'cvr'
                , 'isshow', 'level', 'repertory', 'sale_num'
                , 'partner_tel', 'partner_qq', 'audit_status', 'start_time', 'createTime');

        $tipsTypeEnum = CheckTipsManager::$tipsTypeEnum;
        foreach ($list as &$v) {
            if ($v['isshow'] == 0) {
                $v['isshow'] = '精品';
            } else {
                $v['isshow'] = '';
            }
            $v['audit_status'] = $tipsTypeEnum[$v['audit_status']];
            if ($v['start_time']) {
                $v['start_time']   = date("Y-m-d H:i:s", $v['start_time']);
            } else {
                $v['start_time']   = '';
            }
        }

        $title = $title."_".date("Y-m-d").".xls";
        $this->common->exportHtml($titles, $columns, $list, $title);
    }
}
?>