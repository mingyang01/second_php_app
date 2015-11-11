<?php /* Smarty version Smarty-3.1.18, created on 2015-10-14 14:56:36
         compiled from "/home/work/websites/tuan/protected/views/everyDay/everyday_recommend.html" */ ?>
<?php /*%%SmartyHeaderCode:2108620569561dfca4d59d05-94839434%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'd2bd44edec85d601f927db0bba9ea3d4c3f0f3e0' => 
    array (
      0 => '/home/work/websites/tuan/protected/views/everyDay/everyday_recommend.html',
      1 => 1444794404,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '2108620569561dfca4d59d05-94839434',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'date' => 0,
    'event_id' => 0,
    'time_list' => 0,
    'v' => 0,
    'goods_info' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.18',
  'unifunc' => 'content_561dfca4ecae23_36149557',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_561dfca4ecae23_36149557')) {function content_561dfca4ecae23_36149557($_smarty_tpl) {?><?php echo $_smarty_tpl->getSubTemplate ("layouts/header.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, null, array(), 0);?>

<script src="/assets/lib/My97DatePicker/WdatePicker.js"></script>
<title>团购-每日必败</title>
<style type="text/css">
.price_red {
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

h3 {
  margin-top:0px;
}
.price {
    color: #999;
    text-decoration: line-through;
    padding-left: 10px;
}
</style>
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <ol class="breadcrumb">
                <li><a href="/">Home</a></li>
                <li class="active">每日必败</li>
            </ol>
        <div id="well" class="well pinned">
                <form class="form-inline" role="form" id="form">
                    <div class="form-group">
                        <label>日期：</label>
                        <input class="myDatePicker form-control" id="date" name="date"  type="text" value="<?php echo $_smarty_tpl->tpl_vars['date']->value;?>
"  data-date-format="yyyy-mm-dd">
                    </div>
                    <button type="submit" class="btn btn-default">查看</button>
                    
                      <a class="btn btn-primary" href="/audit/sample?type=1&business=3&event=<?php echo $_smarty_tpl->tpl_vars['event_id']->value;?>
" style="float:right;">样核</a>
                      <a class="btn btn-primary" href="/audit/second?type=1&business=3&event=<?php echo $_smarty_tpl->tpl_vars['event_id']->value;?>
" style="float:right;margin-right:10px;">复审</a>
                      <a class="btn btn-primary" href="/audit/first?type=1&business=3&event=<?php echo $_smarty_tpl->tpl_vars['event_id']->value;?>
" style="float:right;margin-right:10px;">初审</a>
                      
                </form>
        </div>
    </div>
</div>
    <div class="row">
        <div class="col-md-12">
            <table class="table">
                <tbody>
                    <tr>
                        <td style="width:150px;">开始时间</td>
                        <td style="width:150px;">结束时间</td>
                        <td style="text-align:center">商品杀</td>
                        <td style="text-align:center" colspan="2">精选</td>
                    </tr>
                    <?php  $_smarty_tpl->tpl_vars['v'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['v']->_loop = false;
 $_smarty_tpl->tpl_vars['k'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['time_list']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['v']->key => $_smarty_tpl->tpl_vars['v']->value) {
$_smarty_tpl->tpl_vars['v']->_loop = true;
 $_smarty_tpl->tpl_vars['k']->value = $_smarty_tpl->tpl_vars['v']->key;
?>
                    <tr style="height:116px;">
                        <td style="line-height:100px;"><?php echo $_smarty_tpl->tpl_vars['v']->value['start_time'];?>
</td>
                        <td style="line-height:100px;"><?php echo $_smarty_tpl->tpl_vars['v']->value['end_time'];?>
</td>
                        <td>
                          <?php if ($_smarty_tpl->tpl_vars['v']->value['goods_list'][0]) {?>
                          <?php $_smarty_tpl->tpl_vars['goods_info'] = new Smarty_variable($_smarty_tpl->tpl_vars['v']->value['goods_list'][0], null, 0);?>
                          <div style="height:100px;">
                            <div style="float:left;">
                              <a target="_blank" href="http://www.meilishuo.com/share/item/<?php echo $_smarty_tpl->tpl_vars['goods_info']->value['tid'];?>
"><img src="<?php echo Yii::app()->image->getWebsiteImageUrl($_smarty_tpl->tpl_vars['goods_info']->value['img']);?>
" data-holder-rendered="true"  draggable="false" style="max-height:100px;"></a>
                            </div>
                            <div style="margin-left:10px;float:left;">
                              <h3 id="thumbnail-label"><?php echo $_smarty_tpl->tpl_vars['goods_info']->value['name'];?>
<span class="price_red" style="color: #f69;padding-left:5px;"><?php echo $_smarty_tpl->tpl_vars['goods_info']->value['price'];?>
</span><span class="price" style="padding-left:5px;"><?php echo $_smarty_tpl->tpl_vars['goods_info']->value['origin'];?>
</span> <span class="price_red" style="padding-left:5px;"><?php echo $_smarty_tpl->tpl_vars['goods_info']->value['rate']*10;?>
折</span></h3>
                              <p><span>twitter_id：</span><span><?php echo $_smarty_tpl->tpl_vars['goods_info']->value['tid'];?>
</span>&nbsp;&nbsp;&nbsp;&nbsp;<span>gid：</span><span><?php echo $_smarty_tpl->tpl_vars['goods_info']->value['gid'];?>
</span>&nbsp;&nbsp;&nbsp;&nbsp;<span>shop_id：</span><span><?php echo $_smarty_tpl->tpl_vars['goods_info']->value['shop'];?>
</span></p>
                              <p><span>必败理由：</span><span><?php echo $_smarty_tpl->tpl_vars['goods_info']->value['sale_point'];?>
</span></p>
                            </div>
                          </div>
                          <?php } else { ?>
                          <div style="max-height:100px;" class="addGoodsCon">
                            <div class="form-inline" style="margin-top:5px;">
                            <input type="text" class="form-control input-large" placeholder="twitter_id" value="" name="twitter_id">
                            <input type="text" class="form-control input-xlarge" placeholder="标题 (选填)" value="" name="goods_name" style="width:315px !important">
                            </div>
                            <br>
                            <input type="text" class="form-control input-xxlarge" placeholder="必败理由" value="" name="sale_point">
                            <input type="hidden" name="start_time" value="<?php echo $_smarty_tpl->tpl_vars['v']->value['start_time'];?>
">
                            <input type="hidden" name="end_time" value="<?php echo $_smarty_tpl->tpl_vars['v']->value['end_time'];?>
">
                          </div>
                          <?php }?>
                        </td>
                        <td style="line-height:100px;">
                        <?php if ($_smarty_tpl->tpl_vars['v']->value['goods_list'][0]) {?>
                          <a style="margin-left:15px;" href="javascript:;" class="clearData btn btn-warning btn-sm" data-gid=<?php echo $_smarty_tpl->tpl_vars['goods_info']->value['gid'];?>
>删除</a>
                          <a style="margin-left:15px;" href="/goods/editGoods?gid=<?php echo $_smarty_tpl->tpl_vars['goods_info']->value['gid'];?>
" target="_blank" class="btn btn-default btn-sm" data-gid=<?php echo $_smarty_tpl->tpl_vars['goods_info']->value['gid'];?>
>编辑</a>
                        <?php } else { ?>
                          <a href="javascript:;" class="saveGoods btn btn-default btn-sm">保存</a>
                        <?php }?>
                        </td>
                    </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<script type="text/javascript">
$(function(){
  /** 选择时间 */
  $('.myDatePicker').on('focus',function(){
    WdatePicker({
        dateFmt:'yyyy-MM-dd'
    });
  });
  
  $(".saveGoods").click(function(){
    if ($(this).hasClass("disabled")) return false;
    var thisObj = $(this);
    var goodsConObj = thisObj.closest("tr").find(".addGoodsCon");
    
    var twitterId = goodsConObj.find("input[name='twitter_id']").val();
    var salePoint = goodsConObj.find("input[name='sale_point']").val();
    var startTime = goodsConObj.find("input[name='start_time']").val();
    var endTime   = goodsConObj.find("input[name='end_time']").val();
    var goodsName = goodsConObj.find("input[name='goods_name']").val();
    
    if (!twitterId) {
      alert('请输入twitter_id');
      return false;
    }
    if (!salePoint) {
      alert('请输入必败理由');
      return false;
    }
    
    var postData = {'twitter_id':twitterId, 'sale_point':salePoint, 'start_time':startTime, 'end_time':endTime, 'goods_name':goodsName};
    var objInHtml = thisObj.html();
    setBtnStatus(thisObj, '提交中...', 'disabled');
    $.post('/everydayRecommend/saveEverydayRecommendGoods', postData, function(json){
      if (json.succ == 1) {
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
  $(".clearData").click(function(){
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
});
</script>
<?php echo $_smarty_tpl->getSubTemplate ("layouts/footer.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, null, array(), 0);?>
<?php }} ?>
