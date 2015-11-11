<?php
/**
* 比价工具
*/
class ComparePriceManager extends Manager {
    
    public  function ComparePriceInfo($tid,$showall = ''){
        $curl = Yii::app()->curl;
        $header = array('Meilishuo: uid:1178834;ip:123');
        $url = 'focus.mlservice.meilishuo.com/focus/GetSameStyleInfo?tids='.$tid;
        $output = $curl->get($url);
        $results = json_decode($output['body'], true);
        $re = $results['data'];
        if(!empty($showall))
        {
            return $re;
        }
        if(is_array($re))
        {
            $msg['flag'] = 1;
            $msg['data'] = $this->getMinPriceGoods($re,$tid);
            return $msg;
        }
        else
        {
            $msg['flag'] = 0;
            $msg['data'] = $re;
            return $msg;
        }
        
    }
    //获得最低价商品的信息
    public function getMinPriceGoods($arr,$tid){
        $min_price = $arr[$tid]['min_price'];
        $count = $arr[$tid]['count'];
        $arrGoods = $arr[$tid];
        $results = array();
        foreach ($arrGoods as $key => $value) {
            if(isset($value['price']))
            {
               if($value['price']==$min_price)
                {
                    $results['tid'] = $value['tid'];
                    $results['price'] = $value['price'];
                    $results['shop_id'] = $value['shop_id'];
                    $results['shop_nick'] = $value['shop_nick'];
                    $results['partner_area'] = $value['partner_area'];
                    $results['count'] = $count;
                } 
            }
        }
        return $results;
    }

    //获得详细信息
    public function showComparePriceDetail($tid)
    {
        $sameInfo = $this->ComparePriceInfo($tid,true);
        $db  = Yii::app()->sdb_brd_goods;
        $sameInfo = $sameInfo[$tid];
        //var_dump($sameInfo);exit();
        $data = array();
        foreach ($sameInfo as $key => $value) {
            $twid = $value['tid'];
            $sql = "select * from brd_goods_info where twitter_id='$twid'";
            $resultes = $db->createCommand($sql)->queryAll();
            if(is_numeric($key))
            {
                $sameInfo[$key]['goods_title'] = $resultes[0]['goods_title'];
                $sameInfo[$key]['goods_img'] = $resultes[0]['goods_img'];
                $sameInfo[$key]['goods_subtitle'] = $resultes[0]['goods_subtitle'];
            }
        }
        foreach ($sameInfo as $key => $value) {
            if(is_numeric($key))
            {
                $data['data'][$key] = $value; 
            }
            else
            {
                $data['msg'][$key] = $value;
            }
        }
        return $data;
    }
}