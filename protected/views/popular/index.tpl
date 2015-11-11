{/include file="layouts/header.tpl"/}
<title>流行推荐</title>
<script src="/assets/lib/My97DatePicker/WdatePicker.js"></script>
<script src="/assets/lib/imgAjaxfileUp.js"></script>
<script src='/assets/lib/js/Sortable.js'></script>
<style type="text/css">
.rec_sku .price {
    color: #999;
    text-decoration: line-through;
    padding-left: 10px
}
.rec_sku .price_red {
    color: #474760;
    font-size: 16px
}
#thumbnail-label {
    font-size: 16px
}
.img {
    height: 300px;
}
.pinActive {
    box-shadow: 0 10px 6px rgba(0,0,0,.12),0 1px 6px rgba(0,0,0,.12) !important;
    /*width: 100%!important;*/
    background-color: white;
    z-index:999;
}

.caption > p {
    white-space: nowrap;
    /*background-color: lavenderblush;*/
}
.select2-selection {
    height: 34px !important;
}
.select2-selection__rendered {
    height: 34px !important;
    line-height: 32px !important;
}
.nav-tabs a {
    color: black;
}
.tool a, .active a {
    border: 0px !important;
    color: white !important;
    background: #474760 !important;
    border-radius: 0px !important;
}

.btnCon .btn {
  border:0px;
  border-radius:0px;
  margin-left:4px;
}
</style>
<script>
window.nowDate = '{/$date/}';
</script>
<div class="container">
  <div class="row">
    <div class="col-md-12">
      <ol class="breadcrumb">
          <li><a href="/">Home</a></li>
          <li class="active">流行推荐</li>
      </ol>
    </div>
  </div>
  <div class="row">
    <div class="col-md-12" style="border-bottom: 1px solid #e3e3e3;padding-bottom: 10px;">
      <div role="tabpanel">
      <form class="form-inline" role="form" id="form" method="get">
          <div class="form-group">
              <label>日期：</label>
              <input class="picker form-control myDatePicker" id="date" name="date"  type="text" value="{/$date/}"  data-date-format="yyyy-mm-dd">
          </div>
          <button type="submit" class="btn btn-default">查看</button>
      </form>
      {/*
        <ul class="nav nav-tabs" role="tablist">
          {/$dateList = MarketingManager::getDaysList()/}
          {/foreach $dateList as $k=>$v /}
            <li role="presentation" {/if $date == $v/}class="active"{//if/} data-date="{$v}">
              <a href="/marketing/tuangou?date={/$v/}" >{/$v/}</a>
            </li>
          {//foreach/}
        </ul>
        */}
      </div>
    </div>
  </div>
  <div  class="row">
      <div class="col-md-12">
           <p style="float:right;" class="btnCon">
           <button class="btn btn-warning" id="btnSaveRank" type="button" data-shopId="0">保存</button>
           <a class="btn btn-primary" href="/audit/first?type=1&business=3&event={/$tuangou_event_id/}" >初审</a>
          <a class="btn btn-primary" href="/audit/second?type=1&business=3&event={/$tuangou_event_id/}" >复审</a>
          <a class="btn btn-primary" href="/audit/sample?type=1&business=3&event={/$tuangou_event_id/}" >样核</a>
          <a class="btn btn-primary" href="/eventGoods/scheduleGoods?event_id={/$tuangou_event_id/}&schedule_start_time={/date("Y-m-d H:i:s",strtotime($date))/}&schedule_end_time={/date("Y-m-d H:i:s",strtotime("+1days", strtotime($date)))/}" >排期</a>
         </p>
          <h4 id="tool-tip-count" style="color:#474760 ">商品数目:{/count($tuan_list)/}</h4>  
          <p >提示：本页面主要提供预览功能，以及排序修改  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</p>
      </div>
  </div>
  <div class="row" id="box-contailer">
      {/foreach from=$tuan_list item=item key=key/}
      <div class="col-md-3 col-sm-4">
          <div class="thumbnail" data-shop={/$item.shop/} data-gid={/$item.gid/} data-tid={/$item.tid/}>
              <div class="img">
                  <a target="_blank" href="http://www.meilishuo.com/share/item/{/$item.tid/}">
                      <img data-src="holder.js/100%x200" alt="100%x200" src="{/Yii::app()->image->getWebsiteImageUrl($item.img)/}" data-holder-rendered="true" style="height: 100%; width: 100%; display: block;">
                  </a>
              </div>
              <div class="caption">
                  <h3 id="thumbnail-label">{/$item.name/}<a class="anchorjs-link" href="#thumbnail-label"><span class="anchorjs-icon"></span></a><span class="price_red pull-right" style="color: #f69;">{/$item.level/}</span></h3>
                  <p><span>店铺：</span>{/$item.shop_nick/}</p>
                  <p>{/$item.tid/}@{/$item.shop/}</p>
                  <p style=" white-space: nowrap;">{/$item.goods_first_catalog/}—{/$item.goods_second_catalog/}—{/$item.goods_three_catalog/}</p>
                  <p>销量：{/$item.sale_num/}&nbsp;&nbsp;库存：{/$item.repertory/}</p>
                  <p class="rec_sku">
                      <span class="price_red">{/$item.price/}</span>
                      <span class="price" style="padding-left:5px;">{/$item.origin/}</span>
                      <span class="price_red">{/$item.rate * 10/}折</span>
                      <span class="pull-right">
                        <a href="/goods/editGoods?gid={/$item.gid/}" target="_blank" class="btn btn-default btn-sm">编辑</a>
                        <button data-gid="{/$item.gid/}" role="button" class="btn btn-warning btnCancelGoodsOne btn-sm">退回</button>
                      </span>
                  </p>
              </div>
          </div>
      </div>
      {//foreach/}
  </div>
</div>

<script>
$(function(){
  /** 选择时间 */
  $('.myDatePicker').on('focus',function(){
    WdatePicker({
        dateFmt:'yyyy-MM-dd'
    });
  });
  
  /** 推荐位移动 */
  var moveRecommend = document.getElementById("box-contailer");
  new Sortable(moveRecommend, {
    handle: '.img'
  });
  $("#btnSaveRank").click(function(){
    if ($(this).hasClass("disabled")) return false;
    var thisObj = $(this);

    var selectedList = $(".thumbnail");
    var idsArr = [];
    $.each(selectedList, function(k, v){
      idsArr.push($(v).closest(".thumbnail").attr("data-gid"));
    });
    if (idsArr.length < 1) {
      alert('请选择商品');
      return false;
    }
    var ids = idsArr.join(",");
    
    if (confirm("您确定要将 "+idsArr.length+"个商品重新排序吗？") == false) {
      return false;
    }
    
    var postData = {'tuan_id':ids};
    var objInHtml = thisObj.html();
    setBtnStatus(thisObj, '提交中...', 'disabled');
    $.post('/popular/saveTuangouRank', postData, function(json){
      if (json.succ == 1) {
        //alert('成功操作： '+json.succ_num+" 个商品，操作失败： "+json.err_num+" 个商品");
        window.location.reload();
        setBtnStatus(thisObj, objInHtml, 'succ');
      } else {
        alert(json.msg);
        setBtnStatus(thisObj, objInHtml, 'succ');
      }
    },'json').error(function(code, data){
      alert('遇到服务器错误');
      setBtnStatus(thisObj, objInHtml, 'succ');
    });
  });
  
  /**
   * 取消排期
   */
  $(".btnCancelGoodsOne").click(function(){
    if ($(this).hasClass("disabled")) return false;
    var thisObj = $(this);
    var tuanId  = thisObj.attr("data-gid");
    if (!tuanId) {
      alert('商品id不存在');
      return false;
    }
    if (confirm("您确定要取消排期吗？") == false) {
      return false;
    }
    
    var postData = {'tuan_id':tuanId};
    var objInHtml = thisObj.html();
    setBtnStatus(thisObj, '提交中...', 'disabled');
    $.post('/schedule/cancelSchedule', postData, function(json){
      if (json.succ == 1) {
        setBtnStatus(thisObj, objInHtml, 'succ');
        thisObj.closest(".thumbnail").parent(".col-md-3").fadeOut(function(){
          $(this).remove();
        });
      } else {
        alert(json.msg);
        setBtnStatus(thisObj, objInHtml, 'succ');
      }
    },'json').error(function(code, data){
      alert('遇到服务器错误');
      setBtnStatus(thisObj, objInHtml, 'succ');
    });
  });
});
</script>
{/include file="layouts/footer.tpl"/}