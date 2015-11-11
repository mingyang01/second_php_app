<?php
/**
 * MiaoshaRuleManager.php
 * 秒杀活动规则
 * Author: ruidongsong@meilishuo.com
 * Date: 2015-7-29 下午12:46:44
 */

class MiaoshaRuleManager extends Manager
{

    public static $noraml_category_normal_rule_map = array(
        'miaosha_apply_shop_dsr_score'             => '商家DSR评分',
        'miaosha_apply_shop_reason_refund_rate'    => '店铺有理由退款率',
        'miaosha_apply_goods_reason_refund_rate'   => '单品有理由退款率',
        'miaosha_apply_shop_dispute_rate'          => '店铺纠纷率',
        'miaosha_apply_goods_dispute_rate'         => '单品纠纷率',
        'miaosha_apply_goods_repertory_limit'      => '秒杀最小库存限制',
        'miaosha_apply_shop_first_check_waits'     => '普通商家初审数量',
        'miaosha_apply_shop_first_check_fails'     => '所有商家初审失败次数',
        'miaosha_apply_shop_month_apply_limit'     => '自然月商家最多可报名次数',
        // 'miaosha_apply_shop_level'                 => '商家等级',
        'miaosha_apply_shop_white_list'            => '白名单商家',
    );

    public static $noraml_category_nvzhuang_rule_map = array(
            'miaosha_apply_goods_online_num_nvzhuang'          => '商品在线数(女装)',
            'miaosha_apply_goods_online_time_range_nvzhuang'   => '商品上新时间(女装)',
            'miaosha_apply_goods_sale_num_nvzhuang'            => '商品累计销量(女装)',
            'miaosha_apply_shop_category_nvzhuang'             => '商家主营类目(女装)',
            'miaosha_apply_goods_comment_num_nvzhuang'         => '评价个数(女装)',
            'miaosha_apply_goods_popularity_score_nvzhuang'    => '流行分(女装)',
            'miaosha_apply_goods_comment_rate_nvzhuang'        => '商品好评率(女装)',
    );

    public static $noraml_category_nvxie_rule_map = array(
            'miaosha_apply_goods_online_num_nvxie'          => '商品在线数(女鞋)',
            'miaosha_apply_goods_online_time_range_nvxie'   => '商品上新时间(女鞋)',
            'miaosha_apply_goods_sale_num_nvxie'            => '商品30天累计销量(女鞋)',
            'miaosha_apply_shop_category_nvxie'             => '商家主营类目(女鞋)',
            'miaosha_apply_goods_comment_num_nvxie'         => '评价个数(女鞋)',
            'miaosha_apply_goods_popularity_score_nvxie'    => '流行分(女鞋)',
            'miaosha_apply_goods_comment_rate_nvxie'        => '商品好评率(女鞋)',
    );

    public static $noraml_category_nvbao_rule_map = array(
            'miaosha_apply_goods_online_num_nvbao'          => '商品在线数(女包)',
            'miaosha_apply_goods_online_time_range_nvbao'   => '商品上新时间(女包)',
            'miaosha_apply_goods_sale_num_nvbao'            => '商品30天累计销量(女包)',
            'miaosha_apply_shop_category_nvbao'             => '商家主营类目(女包)',
            'miaosha_apply_goods_comment_num_nvbao'         => '评价个数(女包)',
            'miaosha_apply_goods_popularity_score_nvbao'    => '流行分(女包)',
            'miaosha_apply_goods_comment_rate_nvbao'        => '商品好评率(女包)',
    );

    public static $noraml_category_peishi_rule_map = array(
            'miaosha_apply_goods_online_num_peishi'          => '商品在线数(配饰)',
            'miaosha_apply_goods_online_time_range_peishi'   => '商品上新时间(配饰)',
            'miaosha_apply_goods_sale_num_peishi'            => '商品30天累计销量(配饰)',
            'miaosha_apply_shop_category_peishi'             => '商家主营类目(配饰)',
            'miaosha_apply_goods_comment_num_peishi'         => '评价个数(配饰)',
            'miaosha_apply_goods_popularity_score_peishi'    => '流行分(配饰)',
            'miaosha_apply_goods_comment_rate_peishi'        => '商品好评率(配饰)',
    );

    public static $noraml_category_jiaju_rule_map = array(
            'miaosha_apply_goods_online_num_jiaju'          => '商品在线数(家居)',
            'miaosha_apply_goods_online_time_range_jiaju'   => '商品上新时间(家居)',
            'miaosha_apply_goods_sale_num_jiaju'            => '商品30天累计销量(家居)',
            'miaosha_apply_shop_category_jiaju'             => '商家主营类目(家居)',
            'miaosha_apply_goods_comment_num_jiaju'         => '评价个数(家居)',
            'miaosha_apply_goods_popularity_score_jiaju'    => '流行分(家居)',
            'miaosha_apply_goods_comment_rate_jiaju'        => '商品好评率(家居)',
    );

    public static $noraml_category_meizhuang_rule_map = array(
            'miaosha_apply_goods_online_num_meizhuang'          => '商品在线数(美妆)',
            'miaosha_apply_goods_online_time_range_meizhuang'   => '商品上新时间(美妆)',
            'miaosha_apply_goods_sale_num_meizhuang'            => '商品30天累计销量(美妆)',
            'miaosha_apply_shop_category_meizhuang'             => '商家主营类目(美妆)',
            'miaosha_apply_goods_comment_num_meizhuang'         => '评价个数(美妆)',
            'miaosha_apply_goods_popularity_score_meizhuang'    => '流行分(美妆)',
            'miaosha_apply_goods_comment_rate_meizhuang'        => '商品好评率(美妆)',
    );

    public static $noraml_category_nanzhuang_rule_map = array(
            'miaosha_apply_goods_online_num_nanzhuang'          => '商品在线数(男装)',
            'miaosha_apply_goods_online_time_range_nanzhuang'   => '商品上新时间(男装)',
            'miaosha_apply_goods_sale_num_nanzhuang'            => '商品30天累计销量(男装)',
            'miaosha_apply_shop_category_nanzhuang'             => '商家主营类目(男装)',
            'miaosha_apply_goods_comment_num_nanzhuang'         => '评价个数(男装)',
            'miaosha_apply_goods_popularity_score_nanzhuang'    => '流行分(男装)',
            'miaosha_apply_goods_comment_rate_nanzhuang'        => '商品好评率(男装)',
    );

    public static $noraml_category_tongzhuang_rule_map = array(
            'miaosha_apply_goods_online_num_tongzhuang'          => '商品在线数(童装)',
            'miaosha_apply_goods_online_time_range_tongzhuang'   => '商品上新时间(童装)',
            'miaosha_apply_goods_sale_num_tongzhuang'            => '商品30天累计销量(童装)',
            'miaosha_apply_shop_category_tongzhuang'             => '商家主营类目(童装)',
            'miaosha_apply_goods_comment_num_tongzhuang'         => '评价个数(童装)',
            'miaosha_apply_goods_popularity_score_tongzhuang'    => '流行分(童装)',
            'miaosha_apply_goods_comment_rate_tongzhuang'        => '商品好评率(童装)',
    );

    public static $noraml_category_shipin_rule_map = array(
            'miaosha_apply_goods_online_num_shipin'          => '商品在线数(食品)',
            'miaosha_apply_goods_online_time_range_shipin'   => '商品上新时间(食品)',
            'miaosha_apply_goods_sale_num_shipin'            => '商品30天累计销量(食品)',
            'miaosha_apply_shop_category_shipin'             => '商家主营类目(食品)',
            'miaosha_apply_goods_comment_num_shipin'         => '评价个数(食品)',
            'miaosha_apply_goods_popularity_score_shipin'    => '流行分(食品)',
            'miaosha_apply_goods_comment_rate_shipin'        => '商品好评率(食品)',
    );

    public static $noraml_category_tongxie_rule_map = array(
            'miaosha_apply_goods_online_num_tongxie'          => '商品在线数(童鞋)',
            'miaosha_apply_goods_online_time_range_tongxie'   => '商品上新时间(童鞋)',
            'miaosha_apply_goods_sale_num_tongxie'            => '商品30天累计销量(童鞋)',
            'miaosha_apply_shop_category_tongxie'             => '商家主营类目(童鞋)',
            'miaosha_apply_goods_comment_num_tongxie'         => '评价个数(童鞋)',
            'miaosha_apply_goods_popularity_score_tongxie'    => '流行分(童鞋)',
            'miaosha_apply_goods_comment_rate_tongxie'        => '商品好评率(童鞋)',
    );

    public static $noraml_category_nanxie_rule_map = array(
            'miaosha_apply_goods_online_num_nanxie'          => '商品在线数(男鞋)',
            'miaosha_apply_goods_online_time_range_nanxie'   => '商品上新时间(男鞋)',
            'miaosha_apply_goods_sale_num_nanxie'            => '商品30天累计销量(男鞋)',
            'miaosha_apply_shop_category_nanxie'             => '商家主营类目(男鞋)',
            'miaosha_apply_goods_comment_num_nanxie'         => '评价个数(男鞋)',
            'miaosha_apply_goods_popularity_score_nanxie'    => '流行分(男鞋)',
            'miaosha_apply_goods_comment_rate_nanxie'        => '商品好评率(男鞋)',
    );

    public static $noraml_category_nanbao_rule_map = array(
            'miaosha_apply_goods_online_num_nanbao'          => '商品在线数(男包)',
            'miaosha_apply_goods_online_time_range_nanbao'   => '商品上新时间(男包)',
            'miaosha_apply_goods_sale_num_nanbao'            => '商品30天累计销量(男包)',
            'miaosha_apply_shop_category_nanbao'             => '商家主营类目(男包)',
            'miaosha_apply_goods_comment_num_nanbao'         => '评价个数(男包)',
            'miaosha_apply_goods_popularity_score_nanbao'    => '流行分(男包)',
            'miaosha_apply_goods_comment_rate_nanbao'        => '商品好评率(男包)',
    );

    public static $noraml_category_shumaxiaojiadian_rule_map = array(
            'miaosha_apply_goods_online_num_shumaxiaojiadian'          => '商品在线数(数码小家电)',
            'miaosha_apply_goods_online_time_range_shumaxiaojiadian'   => '商品上新时间(数码小家电)',
            'miaosha_apply_goods_sale_num_shumaxiaojiadian'            => '商品30天累计销量(数码小家电)',
            'miaosha_apply_shop_category_shumaxiaojiadian'             => '商家主营类目(数码小家电)',
            'miaosha_apply_goods_comment_num_shumaxiaojiadian'         => '评价个数(数码小家电)',
            'miaosha_apply_goods_popularity_score_shumaxiaojiadian'    => '流行分(数码小家电)',
            'miaosha_apply_goods_comment_rate_shumaxiaojiadian'        => '商品好评率(数码小家电)',
    );
}
?>