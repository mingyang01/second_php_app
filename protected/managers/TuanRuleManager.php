<?php

class TuanRuleManager extends Manager
{
    //==================== 请用下面的，上面的已废弃 =========================
    /**
     * 普通团购报名规则
     */
    public static $normalRuleInfo = array(
            'apply_audit_waits'             => '等待初审数',
            'apply_first_check_fails'       => '初审失败次数',
            'apply_sale_num'                => '商品累计销量',
            'apply_reason_refund_rate'      => '店铺有理由退款率',
            'apply_shop_reason_refund_rate'    => '商铺有理由退款率',      // @FIXME 新增
            'apply_shop_score'              => '店铺DSR',
            //'apply_satisfy_rate_50-'        => '商品销量小于50的满意度',
            //'apply_satisfy_rate_50+'        => '商品销量大于50的满意度',
            //'online_list_num'               => '普通团购活动商品在线数',

            'groupon_shop_ka_list'          => '团购KA商家列表',
            'apply_audit_waits_ka'          => '普通团购KA商家等待初审数',     // @FIXME 新增
            //'apply_audit_waits_ka'          => '团购KA商家普通活动在线数量',
            'groupon_ka_notshow_num'        => '团购KA商家精品商品个数（不在团购频道页展示）',

            'apply_repertory_limit'         => '普通团购报名库存限制',
            //'apply_on_shelf_days'           => '普通团购报名上新时间限制',

            'apply_shop_dispute_rate'       => '店铺纠纷率',     // @FIXME 新增
            'apply_goods_dispute_rate'      => '单品纠纷率',     // @FIXME 新增
    );

    /**
     * 商品销量 （按分类区分）
     */
    public static $normalCategorySaleNumInfo = array(
            'apply_sale_num_nvzhuang'       => '普通团购：商品累计销量(女装)',
            'apply_sale_num_nvxie'          => '普通团购：商品累计销量(女鞋)',
            'apply_sale_num_nvbao'          => '普通团购：商品累计销量(女包)',
            'apply_sale_num_peishi'         => '普通团购：商品累计销量(配饰)',
            'apply_sale_num_jiaju'          => '普通团购：商品累计销量(家居)',
            'apply_sale_num_meizhuang'      => '普通团购：商品累计销量(美妆)',
            'apply_sale_num_nanzhuang'      => '普通团购：商品累计销量(男装)',
            'apply_sale_num_tongzhuang'     => '普通团购：商品累计销量(童装)',
            'apply_sale_num_shipin'         => '普通团购：商品累计销量(食品)',
    );

    /**
     * 在线数量（按分类区分）
     */
    public static $normalCategoryGoodsOnlineNumInfo = array(
            'apply_goods_online_num_nvzhuang'       => '普通团购：商品在线数量(女装)',
            'apply_goods_online_num_nvxie'          => '普通团购：商品在线数量(女鞋)',
            'apply_goods_online_num_nvbao'          => '普通团购：商品在线数量(女包)',
            'apply_goods_online_num_peishi'         => '普通团购：商品在线数量(配饰)',
            'apply_goods_online_num_jiaju'          => '普通团购：商品在线数量(家居)',
            'apply_goods_online_num_meizhuang'      => '普通团购：商品在线数量(美妆)',
            'apply_goods_online_num_nanzhuang'      => '普通团购：商品在线数量(男装)',
            'apply_goods_online_num_tongzhuang'     => '普通团购：商品在线数量(童装)',
            'apply_goods_online_num_shipin'         => '普通团购：商品在线数量(食品)',
    );

    /**
     * 评论数量 （按分类区分）
     */
    public static $normalCategoryGoodsCommentNumInfo = array(
            'apply_goods_comment_num_nvzhuang'       => '普通团购：商品评论数量(女装)',
            'apply_goods_comment_num_nvxie'          => '普通团购：商品评论数量(女鞋)',
            'apply_goods_comment_num_nvbao'          => '普通团购：商品评论数量(女包)',
            'apply_goods_comment_num_peishi'         => '普通团购：商品评论数量(配饰)',
            'apply_goods_comment_num_jiaju'          => '普通团购：商品评论数量(家居)',
            'apply_goods_comment_num_meizhuang'      => '普通团购：商品评论数量(美妆)',
            'apply_goods_comment_num_nanzhuang'      => '普通团购：商品评论数量(男装)',
            'apply_goods_comment_num_tongzhuang'     => '普通团购：商品评论数量(童装)',
            'apply_goods_comment_num_shipin'         => '普通团购：商品评论数量(食品)',
    );

    /**
     * 流行分 （按分类区分）
     */
    public static $normalCategoryGoodsPopularityScoreInfo = array(
            'apply_goods_popularity_score_nvzhuang'       => '普通团购：商品流行分(女装)',
            'apply_goods_popularity_score_nvxie'          => '普通团购：商品流行分(女鞋)',
            'apply_goods_popularity_score_nvbao'          => '普通团购：商品流行分(女包)',
            'apply_goods_popularity_score_peishi'         => '普通团购：商品流行分(配饰)',
            'apply_goods_popularity_score_jiaju'          => '普通团购：商品流行分(家居)',
            'apply_goods_popularity_score_meizhuang'      => '普通团购：商品流行分(美妆)',
            'apply_goods_popularity_score_nanzhuang'      => '普通团购：商品流行分(男装)',
            'apply_goods_popularity_score_tongzhuang'     => '普通团购：商品流行分(童装)',
            'apply_goods_popularity_score_shipin'         => '普通团购：商品流行分(食品)',
    );

    /**
     * 好评率 （按分类区分）
     */
    public static $normalCategoryGoodsGoodCommentRateInfo = array(
            'apply_goods_good_comment_nvzhuang'       => '普通团购：商品好评率(女装)',
            'apply_goods_good_comment_nvxie'          => '普通团购：商品好评率(女鞋)',
            'apply_goods_good_comment_nvbao'          => '普通团购：商品好评率(女包)',
            'apply_goods_good_comment_peishi'         => '普通团购：商品好评率(配饰)',
            'apply_goods_good_comment_jiaju'          => '普通团购：商品好评率(家居)',
            'apply_goods_good_comment_meizhuang'      => '普通团购：商品好评率(美妆)',
            'apply_goods_good_comment_nanzhuang'      => '普通团购：商品好评率(男装)',
            'apply_goods_good_comment_tongzhuang'     => '普通团购：商品好评率(童装)',
            'apply_goods_good_comment_shipin'         => '普通团购：商品好评率(食品)',
    );

    /**
     * 上线时间范围（按分类区分）
     */
    public static $normalCategoryGoodsOnlineRangeInfo = array(
            'apply_goods_online_range_nvzhuang'       => '普通团购：商品上线时间范围(女装)',
            'apply_goods_online_range_nvxie'          => '普通团购：商品上线时间范围(女鞋)',
            'apply_goods_online_range_nvbao'          => '普通团购：商品上线时间范围(女包)',
            'apply_goods_online_range_peishi'         => '普通团购：商品上线时间范围(配饰)',
            'apply_goods_online_range_jiaju'          => '普通团购：商品上线时间范围(家居)',
            'apply_goods_online_range_meizhuang'      => '普通团购：商品上线时间范围(美妆)',
            'apply_goods_online_range_nanzhuang'      => '普通团购：商品上线时间范围(男装)',
            'apply_goods_online_range_tongzhuang'     => '普通团购：商品上线时间范围(童装)',
            'apply_goods_online_range_shipin'         => '普通团购：商品上线时间范围(食品)',
    );


    //==================== 请用下面的，上面的已废弃 =========================

    public static $rule_category_map = array(
            'normal' => '普通规则',
            'nvzhuang' => '女装',
            'nvxie' => '女鞋',
            'nvbao' => '女包',
            'peishi' => '配饰',
            'jiaju' => '家居',
            'meizhuang' => '美妆',
            'nanzhuang' => '男装',
            'tongzhuang' => '童装',
            'shipin' => '食品',
            'tongxie' => '童鞋',
            'nanxie'  => '男鞋',
            'nanbao'  => '男包',
            'shumaxiaojiadian' => '数码小家电',
    );

    /**
     * 普通团购报名规则
     */
    public static $noraml_category_normal_rule_map = array(
            'apply_audit_waits'             => '等待初审数',
            'apply_first_check_fails'       => '初审失败次数',
            'apply_sale_num'                => '商品累计销量',
            'apply_reason_refund_rate'      => '店铺有理由退款率',
            'apply_shop_reason_refund_rate'    => '商铺有理由退款率',      // @FIXME 新增
            'apply_shop_score'              => '店铺DSR',
            //'apply_satisfy_rate_50-'        => '商品销量小于50的满意度',
            //'apply_satisfy_rate_50+'        => '商品销量大于50的满意度',
            //'online_list_num'               => '普通团购活动商品在线数',

            'groupon_shop_ka_list'          => '团购KA商家列表',
            'apply_audit_waits_ka'          => '普通团购KA商家等待初审数',     // @FIXME 新增
            //'apply_audit_waits_ka'          => '团购KA商家普通活动在线数量',
            'groupon_ka_notshow_num'        => '团购KA商家精品商品个数（不在团购频道页展示）',

            'apply_repertory_limit'         => '普通团购报名库存限制',
            //'apply_on_shelf_days'           => '普通团购报名上新时间限制',

            'apply_shop_dispute_rate'       => '店铺纠纷率',     // @FIXME 新增
            'apply_goods_dispute_rate'      => '单品纠纷率',     // @FIXME 新增
            'tuangou_apply_shop_white_list'       => '团购商家白名单',     // @FIXME 新增
            'tuangou_apply_shop_white_audit_waits_num'      => '团购白名单商家等待初审数量',     // @FIXME 新增
    );


    public static $noraml_category_nvzhuang_rule_map = array(
            'apply_sale_num_nvzhuang'               => '普通团购：商品累计销量(女装)',
            'apply_goods_online_num_nvzhuang'       => '普通团购：商品在线数量(女装)',
            'apply_goods_comment_num_nvzhuang'      => '普通团购：商品评论数量(女装)',
            'apply_goods_popularity_score_nvzhuang' => '普通团购：商品流行分(女装)',
            'apply_goods_good_comment_nvzhuang'     => '普通团购：商品好评率(女装)',
            'apply_goods_online_range_nvzhuang'     => '普通团购：商品上线时间范围(女装)',
    );

    public static $noraml_category_nvxie_rule_map = array(
            'apply_sale_num_nvxie'               => '普通团购：商品累计销量(女鞋)',
            'apply_goods_online_num_nvxie'       => '普通团购：商品在线数量(女鞋)',
            'apply_goods_comment_num_nvxie'      => '普通团购：商品评论数量(女鞋)',
            'apply_goods_popularity_score_nvxie' => '普通团购：商品流行分(女鞋)',
            'apply_goods_good_comment_nvxie'     => '普通团购：商品好评率(女鞋)',
            'apply_goods_online_range_nvxie'     => '普通团购：商品上线时间范围(女鞋)',
    );

    public static $noraml_category_nvbao_rule_map = array(
            'apply_sale_num_nvbao'               => '普通团购：商品累计销量(女包)',
            'apply_goods_online_num_nvbao'       => '普通团购：商品在线数量(女包)',
            'apply_goods_comment_num_nvbao'      => '普通团购：商品评论数量(女包)',
            'apply_goods_popularity_score_nvbao' => '普通团购：商品流行分(女包)',
            'apply_goods_good_comment_nvbao'     => '普通团购：商品好评率(女包)',
            'apply_goods_online_range_nvbao'     => '普通团购：商品上线时间范围(女包)',
    );


    public static $noraml_category_peishi_rule_map = array(
            'apply_sale_num_peishi'               => '普通团购：商品累计销量(配饰)',
            'apply_goods_online_num_peishi'       => '普通团购：商品在线数量(配饰)',
            'apply_goods_comment_num_peishi'      => '普通团购：商品评论数量(配饰)',
            'apply_goods_popularity_score_peishi' => '普通团购：商品流行分(配饰)',
            'apply_goods_good_comment_peishi'     => '普通团购：商品好评率(配饰)',
            'apply_goods_online_range_peishi'     => '普通团购：商品上线时间范围(配饰)',
    );


    public static $noraml_category_jiaju_rule_map = array(
            'apply_sale_num_jiaju'               => '普通团购：商品累计销量(家居)',
            'apply_goods_online_num_jiaju'       => '普通团购：商品在线数量(家居)',
            'apply_goods_comment_num_jiaju'      => '普通团购：商品评论数量(家居)',
            'apply_goods_popularity_score_jiaju' => '普通团购：商品流行分(家居)',
            'apply_goods_good_comment_jiaju'     => '普通团购：商品好评率(家居)',
            'apply_goods_online_range_jiaju'     => '普通团购：商品上线时间范围(家居)',
    );


    public static $noraml_category_meizhuang_rule_map = array(
            'apply_sale_num_meizhuang'               => '普通团购：商品累计销量(美妆)',
            'apply_goods_online_num_meizhuang'       => '普通团购：商品在线数量(美妆)',
            'apply_goods_comment_num_meizhuang'      => '普通团购：商品评论数量(美妆)',
            'apply_goods_popularity_score_meizhuang' => '普通团购：商品流行分(美妆)',
            'apply_goods_good_comment_meizhuang'     => '普通团购：商品好评率(美妆)',
            'apply_goods_online_range_meizhuang'     => '普通团购：商品上线时间范围(美妆)',
    );


    public static $noraml_category_nanzhuang_rule_map = array(
            'apply_sale_num_nanzhuang'               => '普通团购：商品累计销量(男装)',
            'apply_goods_online_num_nanzhuang'       => '普通团购：商品在线数量(男装)',
            'apply_goods_comment_num_nanzhuang'      => '普通团购：商品评论数量(男装)',
            'apply_goods_popularity_score_nanzhuang' => '普通团购：商品流行分(男装)',
            'apply_goods_good_comment_nanzhuang'     => '普通团购：商品好评率(男装)',
            'apply_goods_online_range_nanzhuang'     => '普通团购：商品上线时间范围(男装)',
    );


    public static $noraml_category_tongzhuang_rule_map = array(
            'apply_sale_num_tongzhuang'               => '普通团购：商品累计销量(童装)',
            'apply_goods_online_num_tongzhuang'       => '普通团购：商品在线数量(童装)',
            'apply_goods_comment_num_tongzhuang'      => '普通团购：商品评论数量(童装)',
            'apply_goods_popularity_score_tongzhuang' => '普通团购：商品流行分(童装)',
            'apply_goods_good_comment_tongzhuang'     => '普通团购：商品好评率(童装)',
            'apply_goods_online_range_tongzhuang'     => '普通团购：商品上线时间范围(童装)',
    );


    public static $noraml_category_shipin_rule_map = array(
            'apply_sale_num_shipin'               => '普通团购：商品累计销量(食品)',
            'apply_goods_online_num_shipin'       => '普通团购：商品在线数量(食品)',
            'apply_goods_comment_num_shipin'      => '普通团购：商品评论数量(食品)',
            'apply_goods_popularity_score_shipin' => '普通团购：商品流行分(食品)',
            'apply_goods_good_comment_shipin'     => '普通团购：商品好评率(食品)',
            'apply_goods_online_range_shipin'     => '普通团购：商品上线时间范围(食品)',
    );


    public static $noraml_category_tongxie_rule_map = array(
            'apply_sale_num_tongxie'               => '普通团购：商品累计销量(童鞋)',
            'apply_goods_online_num_tongxie'       => '普通团购：商品在线数量(童鞋)',
            'apply_goods_comment_num_tongxie'      => '普通团购：商品评论数量(童鞋)',
            'apply_goods_popularity_score_tongxie' => '普通团购：商品流行分(童鞋)',
            'apply_goods_good_comment_tongxie'     => '普通团购：商品好评率(童鞋)',
            'apply_goods_online_range_tongxie'     => '普通团购：商品上线时间范围(童鞋)',
    );

    public static $noraml_category_nanxie_rule_map = array(
            'apply_sale_num_nanxie'               => '普通团购：商品累计销量(男鞋)',
            'apply_goods_online_num_nanxie'       => '普通团购：商品在线数量(男鞋)',
            'apply_goods_comment_num_nanxie'      => '普通团购：商品评论数量(男鞋)',
            'apply_goods_popularity_score_nanxie' => '普通团购：商品流行分(男鞋)',
            'apply_goods_good_comment_nanxie'     => '普通团购：商品好评率(男鞋)',
            'apply_goods_online_range_nanxie'     => '普通团购：商品上线时间范围(男鞋)',
    );

    public static $noraml_category_nanbao_rule_map = array(
            'apply_sale_num_nanbao'               => '普通团购：商品累计销量(男包)',
            'apply_goods_online_num_nanbao'       => '普通团购：商品在线数量(男包)',
            'apply_goods_comment_num_nanbao'      => '普通团购：商品评论数量(男包)',
            'apply_goods_popularity_score_nanbao' => '普通团购：商品流行分(男包)',
            'apply_goods_good_comment_nanbao'     => '普通团购：商品好评率(男包)',
            'apply_goods_online_range_nanbao'     => '普通团购：商品上线时间范围(男包)',
    );

    public static $noraml_category_shumaxiaojiadian_rule_map = array(
            'apply_sale_num_shumaxiaojiadian'               => '普通团购：商品累计销量(数码小家电)',
            'apply_goods_online_num_shumaxiaojiadian'       => '普通团购：商品在线数量(数码小家电)',
            'apply_goods_comment_num_shumaxiaojiadian'      => '普通团购：商品评论数量(数码小家电)',
            'apply_goods_popularity_score_shumaxiaojiadian' => '普通团购：商品流行分(数码小家电)',
            'apply_goods_good_comment_shumaxiaojiadian'     => '普通团购：商品好评率(数码小家电)',
            'apply_goods_online_range_shumaxiaojiadian'     => '普通团购：商品上线时间范围(数码小家电)',
    );

    public static function getAllNoramlRlueInfo()
    {
        //return self::$normalRuleInfo;

        return array_merge(self::$normalRuleInfo,self::$normalCategoryGoodsCommentNumInfo,self::$normalCategoryGoodsGoodCommentRateInfo,self::$normalCategoryGoodsOnlineNumInfo,self::$normalCategoryGoodsOnlineRangeInfo,self::$normalCategoryGoodsPopularityScoreInfo,self::$normalCategorySaleNumInfo);
    }

    /**
     * 获取规则信息
     */
    public static function getRuleInfo($key)
    {
        if (!$key) return array();

        $sql = "select * from t_groupon_global_config where `key`='{$key}' order by id desc limit 1";
        $sdb_groupon = Yii::app()->sdb_groupon;
        $result = $sdb_groupon->createCommand($sql)->queryRow();
        return $result;
    }
}
?>