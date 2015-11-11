<?php /* Smarty version Smarty-3.1.18, created on 2015-11-05 15:10:05
         compiled from "/home/work/websites/tuan/protected/views/search/search.html" */ ?>
<?php /*%%SmartyHeaderCode:142201755955cd5e04da4f50-19341401%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'bf12eca3eb06cbe632233973ed51cd517a4cfe99' => 
    array (
      0 => '/home/work/websites/tuan/protected/views/search/search.html',
      1 => 1446699906,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '142201755955cd5e04da4f50-19341401',
  'function' => 
  array (
  ),
  'version' => 'Smarty-3.1.18',
  'unifunc' => 'content_55cd5e04e9a072_24211608',
  'variables' => 
  array (
    'twitter' => 0,
    'shop' => 0,
    'step' => 0,
    'status' => 0,
    'from' => 0,
    'to' => 0,
    'limit' => 0,
    'catagory' => 0,
    'endCatagory' => 0,
    'isshow_tag' => 0,
    'level' => 0,
    'business' => 0,
    'type' => 0,
    'event' => 0,
    'order_type' => 0,
    'channel' => 0,
    'business_type' => 0,
  ),
  'has_nocache_code' => false,
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_55cd5e04e9a072_24211608')) {function content_55cd5e04e9a072_24211608($_smarty_tpl) {?><?php if (!is_callable('smarty_function_html_options')) include '/home/work/framework/extensions/Smarty/plugins/function.html_options.php';
?><link href="/assets/lib/select2.min.css" rel="stylesheet" />
<script src="/assets/lib/select2.min.js"></script>
<style type="text/css">
.time-label {
    color: red;
}
</style>
<div id="well" class="well">
    <form class="form-horizontal" role="form" id="form">
        <div class="form-group">
            <label class="col-md-1 control-label">宝贝筛选</label>
            <div class="col-md-2">
                <input value="<?php echo $_smarty_tpl->tpl_vars['twitter']->value;?>
" type="text" class="form-control col-md-2" name="twitter" placeholder="宝贝">
            </div>
            <label class="col-md-1 control-label">店铺筛选</label>
            <div class="col-md-2">
                <input value="<?php echo $_smarty_tpl->tpl_vars['shop']->value;?>
" type="text" class="form-control col-md-2" name="shop" placeholder="店铺">
            </div>
            <label class="col-md-1 control-label"><span class="time-label"><?php if ($_smarty_tpl->tpl_vars['step']->value==5&&$_smarty_tpl->tpl_vars['status']->value==1) {?>排期<?php } else { ?>报名<?php }?></span>开始</label>
            <div class="col-md-2">
                <input class="picker form-control" id="date" name="from"  type="text" value="<?php echo $_smarty_tpl->tpl_vars['from']->value;?>
"  data-date-format="yyyy-mm-dd">
            </div>
            <label class="col-md-1 control-label"><span class="time-label"><?php if ($_smarty_tpl->tpl_vars['step']->value==5&&$_smarty_tpl->tpl_vars['status']->value==1) {?>排期<?php } else { ?>报名<?php }?></span>结束</label>
            <div class="col-md-2">
                <input class="picker form-control" id="date" name="to"  type="text" value="<?php echo $_smarty_tpl->tpl_vars['to']->value;?>
"  data-date-format="yyyy-mm-dd">
            </div>
        </div>
        <div class="form-group">
            <label class="col-md-1 control-label">审核进度</label>
            <div class="col-md-2">
                <select data-limit="<?php echo $_smarty_tpl->tpl_vars['limit']->value;?>
" name="step" class="form-control">
                    <option <?php if ($_smarty_tpl->tpl_vars['step']->value==1) {?>selected<?php }?> value="1">不限</option>
                    <option <?php if ($_smarty_tpl->tpl_vars['step']->value==2) {?>selected<?php }?> value="2">初审</option>
                    <option <?php if ($_smarty_tpl->tpl_vars['step']->value==3) {?>selected<?php }?> value="3">复审</option>
                    <option <?php if ($_smarty_tpl->tpl_vars['step']->value==4) {?>selected<?php }?> value="4">样审</option>
                    <option <?php if ($_smarty_tpl->tpl_vars['step']->value==5) {?>selected<?php }?> value="5">排期</option>
                </select>
            </div>
            <label class="col-md-1 control-label">审核状态</label>
            <div class="col-md-2">
                <select name="status" class="form-control">
                    <option <?php if ($_smarty_tpl->tpl_vars['status']->value==0) {?>selected<?php }?> value="0">等待</option>
                    <option <?php if ($_smarty_tpl->tpl_vars['status']->value==1) {?>selected<?php }?> value="1">成功</option>
                    <option <?php if ($_smarty_tpl->tpl_vars['status']->value==2) {?>selected<?php }?> value="2">失败</option>
                </select>
            </div>

            <label class="col-md-1 control-label">商品分类</label>
            <div class="col-md-2">
                <select style="height:34px;" name="major" class="form-control">
                    <option value="0">不限</option>
                    <option <?php if ($_smarty_tpl->tpl_vars['catagory']->value=='11801') {?>selected<?php }?> value="11801">女装</option>
                    <option <?php if ($_smarty_tpl->tpl_vars['catagory']->value=='11805') {?>selected<?php }?> value="11805">女包</option>
                    <option <?php if ($_smarty_tpl->tpl_vars['catagory']->value=='11803') {?>selected<?php }?> value="11803">女鞋</option>
                    <option <?php if ($_smarty_tpl->tpl_vars['catagory']->value=='11809') {?>selected<?php }?> value="11809">家具</option>
                    <option <?php if ($_smarty_tpl->tpl_vars['catagory']->value=='11807') {?>selected<?php }?> value="11807">配饰</option>
                    <option <?php if ($_smarty_tpl->tpl_vars['catagory']->value=='12313') {?>selected<?php }?> value="12313">美妆</option>
                    <option <?php if ($_smarty_tpl->tpl_vars['catagory']->value=='12511') {?>selected<?php }?> value="12511">男装</option>
                    <option <?php if ($_smarty_tpl->tpl_vars['catagory']->value=='12591') {?>selected<?php }?> value="12591">童装</option>
                    <option <?php if ($_smarty_tpl->tpl_vars['catagory']->value=='12661') {?>selected<?php }?> value="12661">食品</option>
                    <option <?php if ($_smarty_tpl->tpl_vars['catagory']->value=='12803') {?>selected<?php }?> value="12803">童鞋</option>
                    <option <?php if ($_smarty_tpl->tpl_vars['catagory']->value=='12763') {?>selected<?php }?> value="12763">男鞋</option>
                    <option <?php if ($_smarty_tpl->tpl_vars['catagory']->value=='12843') {?>selected<?php }?> value="12843">男包</option>
                    <option <?php if ($_smarty_tpl->tpl_vars['catagory']->value=='13097') {?>selected<?php }?> value="13097">数码小家电</option>
                </select>
            </div>
            <label class="col-md-1 control-label">末级分类</label>
            <div class="col-md-2">
                <select name="end-major" class="form-control" data-value="<?php echo $_smarty_tpl->tpl_vars['endCatagory']->value;?>
">
                    <option value="0">不限</option>
                </select>
            </div>
        </div>
        <div class="form-group">
            <label class="col-md-1 control-label">是否精品</label>
            <div class="col-md-2">
                <select name="isshow_tag" class="form-control">
                    <option <?php if ($_smarty_tpl->tpl_vars['isshow_tag']->value=='#') {?>selected<?php }?> value="#">全部</option>
                    <option <?php if ($_smarty_tpl->tpl_vars['isshow_tag']->value=='0') {?>selected<?php }?> value="0">精品</option>
                    <option <?php if ($_smarty_tpl->tpl_vars['isshow_tag']->value=='1') {?>selected<?php }?> value="1">非精品</option>
                </select>
            </div>
        
            <label class="col-md-1 control-label">店铺类型</label>
            <div class="col-md-2">
                <select name="level" class="form-control">
                    <option <?php if ($_smarty_tpl->tpl_vars['level']->value=='#') {?>selected<?php }?> value="#">不限</option>
                    <option <?php if ($_smarty_tpl->tpl_vars['level']->value=='##') {?>selected<?php }?> value="##">签约商家</option>
                    <option <?php if ($_smarty_tpl->tpl_vars['level']->value=='0') {?>selected<?php }?> value="0">普通商家</option>
                    <option <?php if ($_smarty_tpl->tpl_vars['level']->value=='120') {?>selected<?php }?> value="120">120商家</option>
                    <option <?php if ($_smarty_tpl->tpl_vars['level']->value=='240') {?>selected<?php }?> value="240">240商家</option>
                    <option <?php if ($_smarty_tpl->tpl_vars['level']->value=='360') {?>selected<?php }?> value="360">360商家</option>
                    <option <?php if ($_smarty_tpl->tpl_vars['level']->value=='480') {?>selected<?php }?> value="480">480商家</option>
                    <option <?php if ($_smarty_tpl->tpl_vars['level']->value=='600') {?>selected<?php }?> value="600">600商家</option>
                </select>
            </div>
            <label class="col-md-1 control-label hidden">商品类型</label>
            <div class="col-md-2 hidden">
                <select name="type" class="form-control">
                    <?php if ($_smarty_tpl->tpl_vars['business']->value!=3) {?><option <?php if ($_smarty_tpl->tpl_vars['type']->value=='0') {?>selected<?php }?> value="0">普通团购</option><?php }?>
                    <option <?php if ($_smarty_tpl->tpl_vars['type']->value=='1') {?>selected<?php }?> value="1">团购活动</option>
                </select>
            </div>

            <label <?php if ($_smarty_tpl->tpl_vars['type']->value=='0') {?>style="display:none;"<?php }?> class="event col-md-1 control-label hidden">活动ID</label>
            <div <?php if ($_smarty_tpl->tpl_vars['type']->value=='0') {?>style="display:none;"<?php }?> class="event col-md-2 hidden">
                <input value="<?php echo $_smarty_tpl->tpl_vars['event']->value;?>
" type="text" class="form-control col-md-2" name="event">
            </div>
            
            <label class="col-md-1 control-label">排序</label>
            <div class="col-md-2">
                <select  name="order_type" class="form-control">
                    <option value="">默认</option>
                    <?php echo smarty_function_html_options(array('options'=>SearchManager::$orderInfo,'selected'=>$_smarty_tpl->tpl_vars['order_type']->value),$_smarty_tpl);?>

                </select>
            </div>
            
            <input type="hidden" name='business' value="<?php echo $_smarty_tpl->tpl_vars['business']->value;?>
">
            <input type="hidden" name='channel' value="<?php echo $_smarty_tpl->tpl_vars['channel']->value;?>
">
            <input type="hidden" name='business_type' value="<?php echo $_smarty_tpl->tpl_vars['business_type']->value;?>
">
        </div>
        <div class="form-group">
            <button style="margin-right: 16px;float: right;" id="exportBtn" type="button" class="btn btn-success">导出</button>
            <button style="margin-right: 16px;float: right;" id="submit" class="btn btn-default">查看</button>
        </div>
    </form>
</div>
<script type="text/javascript">
$('.picker').datepicker({
    format: "yyyy-mm-dd",
    autoclose: true
}).on('changeDate', function(ev){
    $(this).datepicker('hide');
});

$('[name="end-major"]').select2();

$('[name="type"]').change(function(e){
    if (this.value == '0') {
        $('.event').hide();
        $('[name="event"]').val(0)
    } else {
        $('.event').show();
    }
});

$('[name="step"]').change(function(e){
    if (this.value == 5 && $('[name="status"]').val() == 1) {
        $('.time-label').text('排期');
    } else {
        $('.time-label').text('报名');
    }
});

$('[name="status"]').change(function(e){
    if (this.value == 1 && $('[name="step"]').val() == 5) {
        $('.time-label').text('排期');
    } else {
        $('.time-label').text('报名');
    }
});


$('[name="major"]').change(function(e){
    console.log(this.value);
    $.get('/audit/endCatagory', {'cid': this.value}, function(data){
        $('[name="end-major"]').select2({
            data:data.data
        });

        var tpl = '<option value="0" selected>不限</option> ';
        $('[name="end-major"]').html(tpl)
        $.each(data.data,function(i,info){
            tpl+="<option value='" + info.id + "'>"+info.text
            + "</option>";
        })

        $('[name="end-major"]').html(tpl);
        $('[name="end-major"]').val($('[name="end-major"]').data('value')).change()
        }, 'json');
});
$('[name="major"]').change();

if ($('[name="step"]').data('limit')) {
    $('[name="step"]').html($('[name="step"] option:selected'))
}

$(function(){
  // 导出
  $("#exportBtn").click(function(e){
    e.stopPropagation
    e.preventDefault();
    var excelStr = window.location.href.indexOf('?') == -1 ? '?' : '&';
    window.open(window.location.href + excelStr + 'excel=1');
  });
});
</script>
<?php }} ?>
