<?php /* Smarty version Smarty-3.1.18, created on 2015-09-21 19:19:03
         compiled from "/home/work/websites/tuan/protected/views/popular/index.tpl" */ ?>
<?php /*%%SmartyHeaderCode:22542420455ffe31aa70027-41347084%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'ecd072a8069f10afe51d50d4f9e39d611ea7ef3c' => 
    array (
      0 => '/home/work/websites/tuan/protected/views/popular/index.tpl',
      1 => 1442834325,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '22542420455ffe31aa70027-41347084',
  'function' => 
  array (
  ),
  'version' => 'Smarty-3.1.18',
  'unifunc' => 'content_55ffe31ab931f0_72895799',
  'variables' => 
  array (
    'date' => 0,
    'tuangou_event_id' => 0,
    'tuan_list' => 0,
    'item' => 0,
  ),
  'has_nocache_code' => false,
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_55ffe31ab931f0_72895799')) {function content_55ffe31ab931f0_72895799($_smarty_tpl) {?><?php echo $_smarty_tpl->getSubTemplate ("layouts/header.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, null, array(), 0);?>

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
window.nowDate = '<?php echo $_smarty_tpl->tpl_vars['date']->value;?>
';
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
              <input class="picker form-control myDatePicker" id="date" name="date"  type="text" value="<?php echo $_smarty_tpl->tpl_vars['date']->value;?>
"  data-date-format="yyyy-mm-dd">
          </div>
          <button type="submit" class="btn btn-default">查看</button>
      </form>
      
      </div>
    </div>
  </div>
  <div  class="row">
      <div class="col-md-12">
           <p style="float:right;" class="btnCon">
           <button class="btn btn-warning" id="btnSaveRank" type="button" data-shopId="0">保存</button>
           <a class="btn btn-primary" href="/audit/first?type=1&business=3&event=<?php echo $_smarty_tpl->tpl_vars['tuangou_event_id']->value;?>
" >初审</a>
          <a class="btn btn-primary" href="/audit/second?type=1&business=3&event=<?php echo $_smarty_tpl->tpl_vars['tuangou_event_id']->value;?>
" >复审</a>
          <a class="btn btn-primary" href="/audit/sample?type=1&business=3&event=<?php echo $_smarty_tpl->tpl_vars['tuangou_event_id']->value;?>
" >样核</a>
          <a class="btn btn-primary" href="/eventGoods/scheduleGoods?event_id=<?php echo $_smarty_tpl->tpl_vars['tuangou_event_id']->value;?>
&schedule_start_time=<?php echo date("Y-m-d H:i:s",strtotime($_smarty_tpl->tpl_vars['date']->value));?>
&schedule_end_time=<?php echo date("Y-m-d H:i:s",strtotime("+1days",strtotime($_smarty_tpl->tpl_vars['date']->value)));?>
" >排期</a>
         </p>
          <h4 id="tool-tip-count" style="color:#474760 ">商品数目:<?php echo count($_smarty_tpl->tpl_vars['tuan_list']->value);?>
</h4>  
          <p >提示：本页面主要提供预览功能，以及排序修改  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</p>
      </div>
  </div>
  <div class="row" id="box-contailer">
      <?php  $_smarty_tpl->tpl_vars['item'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['item']->_loop = false;
 $_smarty_tpl->tpl_vars['key'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['tuan_list']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['item']->key => $_smarty_tpl->tpl_vars['item']->value) {
$_smarty_tpl->tpl_vars['item']->_loop = true;
 $_smarty_tpl->tpl_vars['key']->value = $_smarty_tpl->tpl_vars['item']->key;
?>
      <div class="col-md-3 col-sm-4">
          <div class="thumbnail" data-shop=<?php echo $_smarty_tpl->tpl_vars['item']->value['shop'];?>
 data-gid=<?php echo $_smarty_tpl->tpl_vars['item']->value['gid'];?>
 data-tid=<?php echo $_smarty_tpl->tpl_vars['item']->value['tid'];?>
>
              <div class="img">
                  <a target="_blank" href="http://www.meilishuo.com/share/item/<?php echo $_smarty_tpl->tpl_vars['item']->value['tid'];?>
">
                      <img data-src="holder.js/100%x200" alt="100%x200" src="<?php echo Yii::app()->image->getWebsiteImageUrl($_smarty_tpl->tpl_vars['item']->value['img']);?>
" data-holder-rendered="true" style="height: 100%; width: 100%; display: block;">
                  </a>
              </div>
              <div class="caption">
                  <h3 id="thumbnail-label"><?php echo $_smarty_tpl->tpl_vars['item']->value['name'];?>
<a class="anchorjs-link" href="#thumbnail-label"><span class="anchorjs-icon"></span></a><span class="price_red pull-right" style="color: #f69;"><?php echo $_smarty_tpl->tpl_vars['item']->value['level'];?>
</span></h3>
                  <p><span>店铺：</span><?php echo $_smarty_tpl->tpl_vars['item']->value['shop_nick'];?>
</p>
                  <p><?php echo $_smarty_tpl->tpl_vars['item']->value['tid'];?>
@<?php echo $_smarty_tpl->tpl_vars['item']->value['shop'];?>
</p>
                  <p style=" white-space: nowrap;"><?php echo $_smarty_tpl->tpl_vars['item']->value['goods_first_catalog'];?>
—<?php echo $_smarty_tpl->tpl_vars['item']->value['goods_second_catalog'];?>
—<?php echo $_smarty_tpl->tpl_vars['item']->value['goods_three_catalog'];?>
</p>
                  <p>销量：<?php echo $_smarty_tpl->tpl_vars['item']->value['sale_num'];?>
&nbsp;&nbsp;库存：<?php echo $_smarty_tpl->tpl_vars['item']->value['repertory'];?>
</p>
                  <p class="rec_sku">
                      <span class="price_red"><?php echo $_smarty_tpl->tpl_vars['item']->value['price'];?>
</span>
                      <span class="price" style="padding-left:5px;"><?php echo $_smarty_tpl->tpl_vars['item']->value['origin'];?>
</span>
                      <span class="price_red"><?php echo $_smarty_tpl->tpl_vars['item']->value['rate']*10;?>
折</span>
                      <span class="pull-right">
                        <a href="/goods/editGoods?gid=<?php echo $_smarty_tpl->tpl_vars['item']->value['gid'];?>
" target="_blank" class="btn btn-default btn-sm">编辑</a>
                        <button data-gid="<?php echo $_smarty_tpl->tpl_vars['item']->value['gid'];?>
" role="button" class="btn btn-warning btnCancelGoodsOne btn-sm">退回</button>
                      </span>
                  </p>
              </div>
          </div>
      </div>
      <?php } ?>
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
<?php echo $_smarty_tpl->getSubTemplate ("layouts/footer.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, null, array(), 0);?>
<?php }} ?>
