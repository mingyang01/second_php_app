<?php
/**
 * HotTuanListScriptManager.php
 * 普通团购爆款脚本
 * Author: ruidongsong@meilishuo.com
 * Date: 2015-10-14 上午11:31:01
 */
class HotTuanListScriptManager extends Manager
{

    private $date               = null;     // 日期
    private $goods_list         = null;     // 全部商品列表
    private $twitter_ids        = null;     // tids
    private $rank_goods_list    = null;     // 爆款列表
    private $total_goods        = 0;        // 商品总数
    private $gam_rate           = 0.5;      // gmv排序比例
    private $sale_num_rate      = 0.5;      // 销量排序比例
    private $goods_category_stat = array(); // 分类统计数据

    private $send_mail_list     = 'ruidongsong';

    public function run()
    {
        $this->init();
    }

    public function init()
    {
        $this->date = date("Y-m-d", strtotime("-1 days"))." 10:00:00";
        // 获取列表
        $this->_getTuanList();
        // 设置销量和gmv
        $this->_setSaleNum();
        // 对列表进行排序
        $this->_setSort();
        // 获取商品
        $this->_getRankGoods();
        // 保存商品
        $this->_saveGoods();

        echo "success";
        exit('-exit');
    }

    private function _getTuanList()
    {
        $start_time = strtotime($this->date);
        $sql = "select t1.id,t1.twitter_id,t1.goods_id,t1.off_price,t2.first_sort_id,t1.start_time,t1.end_time,t1.shop_id
        from shop_groupon_info t1 left join shop_groupon_goods_relation t2 on t1.twitter_id=t2.twitter_id
        where t1.audit_status=50 and t1.goods_type=0 and t1.start_time={$start_time} and isshow_tag=1";

        debug($sql);
        $sdb_brd_shop = Yii::app()->sdb_brd_shop;
        $result = $sdb_brd_shop->createCommand($sql)->queryAll();

        if (!$result) {
            MailManager::sendCommMail($this->send_mail_list, '团购爆款数据-每日-暂无数据_getTuanList'.date("Y-m-d H:i:s"), '暂无数据_getTuanList '.$this->date);
            exit('暂无数据 '.$this->date);
        }

        foreach ($result as $k=>$v) {
            $this->twitter_ids[$v['twitter_id']] = $v['twitter_id'];
            $this->goods_list[$v['twitter_id']]  = $v;
        }
        return $result;
    }

    private function _setSaleNum()
    {
        if (!$this->twitter_ids) {
            MailManager::sendCommMail($this->send_mail_list, '团购爆款数据-每日-暂无数据_setSaleNum'.date("Y-m-d H:i:s"), '暂无数据_setSaleNum');
            exit('暂无数据');
        }

        $ctime = strtotime($this->date);
        $ntime = time();
        $sql = "select twitter_id ,sum(amount) as sale_num from t_bat_goods_map tm "
               ."join t_bat_order tbo on tm.order_id=tbo.order_id "
               ."where tm.twitter_id in (".implode(',',$this->twitter_ids).") and tm.ctime >={$ctime} and tbo.pay_time < {$ntime} and tbo.pay_time > {$ctime} group by tm.twitter_id";

        debug($sql);
        $sdb_batman  = Yii::app()->sdb_batman;
        $result = $sdb_batman->createCommand($sql)->queryAll();
        $result = setArrayKey($result, 'twitter_id');

        foreach ($this->goods_list as $k=>&$v) {
            if (isset($result[$v['twitter_id']])) {
                $v['sale_num'] = $result[$v['twitter_id']]['sale_num'];
            } else {
                $v['sale_num'] = 0;
            }

            $v['gmv'] = $v['off_price'] * $v['sale_num'];
        }
    }


    private function _setSort()
    {
        // 获取GMV排序字段
        $gmv_sort_filter = array();
        foreach ($this->goods_list as $k=>$v) {
            $gmv_sort_filter[] = $v['gmv'];
        }
        sort($gmv_sort_filter);
        $min_gmv = $gmv_sort_filter[0];
        $max_gmv = $gmv_sort_filter[count($gmv_sort_filter) - 1];
        foreach ($this->goods_list as $k=>$v) {
            if ($max_gmv - $min_gmv == 0) {
                $gmv_rank = 0;
            } else {
                $gmv_rank = (($v['gmv'] - $min_gmv) / ($max_gmv - $min_gmv) * 100) * $this->gam_rate;
            }
            $this->goods_list[$k]['gmv_rank'] = number_format($gmv_rank,2);
        }

        // 获取销量排序字段
        $sale_num_sort_filter = array();
        foreach ($this->goods_list as $k=>$v) {
            $sale_num_sort_filter[] = $v['sale_num'];
        }
        $r_sale_num_sort_filter = $sale_num_sort_filter;
        sort($sale_num_sort_filter);
        $min_sale_num = $sale_num_sort_filter[0];
        $max_sale_num = $sale_num_sort_filter[count($sale_num_sort_filter) - 1];
        foreach ($this->goods_list as $k=>$v) {
            if ($max_sale_num - $min_sale_num == 0) {
                $sale_num_rank = 0;
            } else {
                $sale_num_rank = (($v['sale_num'] - $min_sale_num) / ($max_sale_num - $min_sale_num) * 100) * $this->sale_num_rate;
            }
            $this->goods_list[$k]['sale_num_rank'] = number_format($sale_num_rank, 2);
        }

        // 排序
        $sort_arr= array();
        foreach ($this->goods_list as $k=>&$v) {
            $all_rank = $v['gmv_rank'] + $v['sale_num_rank'];
            $sort_arr[] = $all_rank;
            $v['all_rank'] = $all_rank;
        }
        array_multisort($sort_arr, SORT_DESC, $this->goods_list);
        $this->goods_list = setArrayKey($this->goods_list, 'twitter_id');

        // p(count($this->goods_list), $this->goods_list);exit();
    }

    private function _getRankGoods()
    {
        if (!$this->goods_list) {
            MailManager::sendCommMail($this->send_mail_list, '团购爆款数据-每日-暂无数据_getRankGoods'.date("Y-m-d H:i:s"), '暂无数据_getRankGoods');
            exit('暂无数据_getRankGoods');
        }

        $this->total_goods = count($this->goods_list);

        // 各分类的比例
        $goods_category_rate = array(
            '11801' => 0.3,     // 女装
            '11803' => 0.2,     // 女鞋
            '11805' => 0.2,     // 女包
            '11807' => 0.2,     // 配饰
        );

        $category_goods_list = array();
        foreach ($this->goods_list as $k=>$v) {
            $category_goods_list[$v['first_sort_id']][] = $v;
        }

        $goods_category_stat = array();
        $new_goods_list = array();
        foreach ($category_goods_list as $k=>$v) {
            if (array_key_exists($k, $goods_category_rate)) {
                $goods_rate  = $goods_category_rate[$k];
                $goods_total = count($v);
                $goods_num   = ceil($goods_total*$goods_rate);
                if ($goods_num <= 0) continue;
                $goods_val_list = array();
                // p($k, $goods_total, $goods_num);
                foreach ($v as $goods_key=>$goods_val) {
                    if (count($goods_val_list) < $goods_num) {
                        $goods_val_list[] = $goods_val;
                    }
                }
                // 分类统计
                $goods_category_stat[$k] = array(
                    'total_num' => $goods_total,
                    'get_num'   => $goods_num,
                );
                //p($goods_val_list);
                $new_goods_list = array_merge($new_goods_list, $goods_val_list);
            }
        }
        // p($new_goods_list);

        // 重新排序
        $new_sort_filter = array();
        foreach ($new_goods_list as $k=>$v) {
            $new_sort_filter[] = $v['all_rank'];
        }
        array_multisort($new_sort_filter, SORT_DESC, $new_goods_list);
        $this->rank_goods_list = $new_goods_list;
        $this->goods_category_stat = $goods_category_stat;

        // p($this->goods_category_stat,$this->rank_goods_list);
    }

    public function _saveGoods()
    {
        if (!$this->rank_goods_list) {
            MailManager::sendCommMail($this->send_mail_list, '团购爆款数据-每日-没有可以保存的商品_saveGoods'.date("Y-m-d H:i:s"), '没有可以保存的商品_saveGoods');
            exit('没有可以保存的商品_saveGoods');
        }

        $errNum     = 0;
        $succNum    = 0;
        $succResult = '';
        $errResult  = '';
        $errTwitterIds = array();
        $errGrouponIds = array();
        $db_brd_shop   = Yii::app()->db_brd_shop;
        $sdb_brd_shop  = Yii::app()->sdb_brd_shop;
        foreach ($this->rank_goods_list as $k=>$v) {
            $start_time = $v['start_time'];
            $end_time   = $v['end_time'];
            // @FIXME 暂时对大于1天的商品不做延期
            if ($end_time - $start_time > 3600*24) {
                $updateScheduleEndTime = date("Y-m-d H:i:s", $end_time);
            } else {
                $updateScheduleEndTime = date("Y-m-d H:i:s", ($end_time + 3600*24));
            }
            $updateScheduleFilter = array();
            $updateScheduleFilter['start_time'] = date("Y-m-d H:i:s", $start_time);
            $updateScheduleFilter['end_time']   = $updateScheduleEndTime;
            $updateScheduleFilter['campaign_type'] = 2;
            $updateScheduleFilter['platform']      = 1;
            $updateScheduleFilter['twitter_id']  = $v['twitter_id'];
            $updateScheduleFilter['campaign_id'] = $v['id'];

            // 修改排期
            $useTimeBegin = microtime(true);
            // @FIXME 注意这里问题
            $setResult = $this->util->updateSchedule($updateScheduleFilter, array('user'=>'crontab_hotTuanList'));
            $useTimeEnd = microtime(true);
            $useTime    = $useTimeEnd - $useTimeBegin;
            $logFiter = array(
                    'groupon_id' => $updateScheduleFilter['campaign_id'],
                    'twitter_id' => $updateScheduleFilter['twitter_id'],
                    'user'       => 'crontab',
                    'name'       => '团购爆款-修改排期',
                    'content'    => $setResult,
                    'param'      => $updateScheduleFilter,
                    'resource_name' => 'HotTuanList',
            );
            $logFiter['is_succ']  = $setResult['succ'];
            $logFiter['use_time'] = number_format($useTime, 5);
            // 写入日志
            $this->tuanLog->addLog($logFiter);

            if ($setResult['succ'] != 1) {
                $errNum++;
                $errResult .= "twitter_id：{$updateScheduleFilter['twitter_id']}   groupon_id：{$updateScheduleFilter['campaign_id']}   msg：{$setResult['msg']}\r\n";
                $errTwitterIds[] = $updateScheduleFilter['twitter_id'];
                $errGrouponIds[] = $updateScheduleFilter['campaign_id'];
            } else {
                // 更新shop_groupon_info
                $update_arr = array();
                $update_arr['start_time'] = strtotime($updateScheduleFilter['start_time']);
                $update_arr['end_time']   = strtotime($updateScheduleFilter['end_time']);
                if ($update_arr) {
                    // @FIXME 注意这里注释掉了插入表
                    try {
                        $db_brd_shop->createCommand()->update(
                                'shop_groupon_info',
                                $update_arr,
                                'id=:id',
                                array(':id'=>$v['id'])
                        );
                    } catch (Exception $e) {
                        MailManager::sendCommMail($this->send_mail_list, '团购爆款数据-每日-报警', "brd_shop.shop_groupon_info库执行错误: \r\n {$e} \r\n\r\n sql : \r\n --- update: ".var_export($update_arr, true).var_export(array(':id'=>$v['id']), true));
                    }
                }
                // p($update_arr);

                // @FIXME插入到推荐表
                $recommend_filter = array(
                    'date'          => date("Y-m-d"),
                    'channel_id'    => 10,
                    'twitter_id'    => $v['twitter_id'],
                    'shop_id'       => $v['shop_id'],
                    'groupon_id'    => $v['id'],
                    'type'          => 0,
                    'start_time'    => strtotime($updateScheduleFilter['start_time']),
                    'end_time'      => strtotime($updateScheduleFilter['end_time']),
                    'goods_id'      => $v['goods_id'],
                    'rank'          => $v['all_rank'],
                    'create_time'   => date("Y-m-d H:i:s"),
                );
                // p($recommend_filter);
                // @FIXME 判断如果存在就更新，不存在就插入
                $sql              = "select * from shop_groupon_recommend_goods where channel_id=10 and date='".date("Y-m-d")."' and type=0 and groupon_id='{$v['id']}' order by id desc limit 1";
                $recommend_info   = $sdb_brd_shop->createCommand($sql)->queryRow();
                if ($recommend_info) {
                    // 设置更新时间
                    unset($recommend_filter['create_time']);
                    $recommend_filter['update_time'] = date("Y-m-d H:i:s");
                    try {
                        $db_brd_shop->createCommand()->update(
                                'shop_groupon_recommend_goods',
                                $recommend_filter,
                                'id=:id',
                                array(':id'=>$recommend_info['id'])
                        );
                    } catch (Exception $e) {
                        MailManager::sendCommMail($this->send_mail_list, '团购爆款数据-每日-报警', "brd_shop.shop_groupon_recommend_goods 库执行错误: \r\n {$e} \r\n\r\n sql : \r\n --- update: ".var_export($recommend_filter, true).var_export(array(':id'=>$recommend_info['id']), true));
                    }
                } else {
                    try {
                        $db_brd_shop->createCommand()->insert('shop_groupon_recommend_goods', $recommend_filter);
                    } catch (Exception $e) {
                        MailManager::sendCommMail($this->send_mail_list, '团购爆款数据-每日-报警', "brd_shop.shop_groupon_recommend_goods 库执行错误: \r\n {$e} \r\n\r\n sql : \r\n --- insert: ".var_export($recommend_filter, true));
                    }
                }

                $succNum++;
                $succResult .= "twitter_id：{$updateScheduleFilter['twitter_id']}   groupon_id：{$updateScheduleFilter['campaign_id']}   msg：{$setResult['msg']}\r\n";
            }
        }

        // 发送邮件
        $mail_content = $this->date. "共有爆款商品 ".count($this->goods_list)." 个，提取爆款商品 ".count($this->rank_goods_list)." 个 \r\n";
        $mail_content .= "\r\n分类爆款商品数量：\r\n";
        foreach ($this->goods_category_stat as $k=>$v) {
            $mail_content .= "分类id：{$k}， 商品总数：{$v['total_num']}， 爆款商品数：{$v['get_num']} \r\n";
        }
        $mail_content .= "\r\n 成功排期商品 {$succNum} 个, 排期失败商品 {$errNum} 个 \r\n";
        if ($errNum > 0) {
            $mail_content .= "\r\n排期失败商品twitter_id：".implode(",", $errTwitterIds)." \r\n";
            $mail_content .= "\r\n排期失败商品groupon_id：".implode(",", $errGrouponIds)." \r\n";
        }
        $mail_content .= "\r\n失败详细原因：\r\n";
        $mail_content .= $errResult;
        $mail_content .= "\r\n成功详细原因：\r\n";
        $mail_content .= $succResult;
        MailManager::sendCommMail($this->send_mail_list, '团购爆款数据-每日-完成'.date("Y-m-d H:i:s"), $mail_content);

        return true;
    }
}
?>