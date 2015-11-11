{/include file="layouts/header.tpl"/}
<script>
    console.log({/json_encode($data.data)/})
</script>
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <ol class="breadcrumb">
                <li><a href="/">Home</a></li>
                <li><a href="/">初审</a></li>
                <li class="active">比价详情</li>
            </ol>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="alert alert-danger" role="alert">
                <ul class="list-inline">
                    <li>同款数量：{/$data.msg.count/}</li>
                    <li>同款最低价：{/$data.msg.min_price/}</li>
                    <li>同款最高价：{/$data.msg.max_price/}</li>
                </ul>
            </div>
        </div>
        {/foreach from=$data.data item=item key=key/}
        <div class="col-md-6" style="height:300px;">
            <div data-shop="{/$item.shop/}" data-gid="{/$item.gid/}" data-twitter="{/$item.tid/}" class="thumbnail clearfix" >
                <div class="well clearfix">
                    <div class="col-md-4">
                        <a href="http://www.meilishuo.com/share/item/{/$data.data.$key.tid/}" target="_blank"> 
                            <img data-src="holder.js/100%x200" alt="100%x200" src="{/Yii::app()->image->getWebsiteImageUrl($data.data.$key.goods_img)/}" data-holder-rendered="true" >
                        </a>
                    </div>
                    <div class="col-md-8" style="font-size:16px;">
                        <p>
                            <h4>{/$data.data.$key.goods_title/}</h4>
                            <h5>{/if $data.data.$key.goods_subtitle/}{/$data.data.$key.goods_subtitle/}{//if/}</h5>
                        </p>
                        <p>商品的推id：{/$data.data.$key.tid/}</p>
                        <p>店铺：{/$data.data.$key.shop_nick/}</p>
                        <p>店铺id：{/$data.data.$key.shop_id/}</p>
                        <p>区域：{/$data.data.$key.partner_area/}</p>
                        <p>价格：{/$data.data.$key.price/}</p>
                    </div>
                </div>
            </div>
        </div>
        {//foreach/}
    </div>
</div>

