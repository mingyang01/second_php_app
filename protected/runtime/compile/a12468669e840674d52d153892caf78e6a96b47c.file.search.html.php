<?php /* Smarty version Smarty-3.1.18, created on 2015-09-28 12:23:14
         compiled from "/home/work/websites/tuan/protected/views/suprise/search.html" */ ?>
<?php /*%%SmartyHeaderCode:133114270355d3ebcf7095c2-09615646%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'a12468669e840674d52d153892caf78e6a96b47c' => 
    array (
      0 => '/home/work/websites/tuan/protected/views/suprise/search.html',
      1 => 1442830795,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '133114270355d3ebcf7095c2-09615646',
  'function' => 
  array (
  ),
  'version' => 'Smarty-3.1.18',
  'unifunc' => 'content_55d3ebcf737379_23903315',
  'variables' => 
  array (
    'to' => 0,
    'mode' => 0,
    'twitter' => 0,
    'shop' => 0,
    'catagory' => 0,
  ),
  'has_nocache_code' => false,
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_55d3ebcf737379_23903315')) {function content_55d3ebcf737379_23903315($_smarty_tpl) {?><?php if (!is_callable('smarty_function_html_checkboxes')) include '/home/work/framework/extensions/Smarty/plugins/function.html_checkboxes.php';
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

            <label class="col-md-1 control-label">排期时间</label>
            <div class="col-md-2">
                <input class="picker form-control" id="date" name="to"  type="text" value="<?php echo $_smarty_tpl->tpl_vars['to']->value;?>
"  data-date-format="yyyy-mm-dd">
            </div>
            <?php if ($_smarty_tpl->tpl_vars['mode']->value!='preview') {?>
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
            <?php }?>
            <?php if ($_smarty_tpl->tpl_vars['mode']->value=='preview') {?>
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
            <?php }?>
        </div>
        <?php if ($_smarty_tpl->tpl_vars['mode']->value!='preview') {?>
        <div class="form-group" style="padding-top: 10px;">
            <div class="col-md-12">
                <label class="">商品分类：</label>
                <?php echo smarty_function_html_checkboxes(array('name'=>"major",'options'=>OnlineManager::$categoryInfo,'selected'=>$_smarty_tpl->tpl_vars['catagory']->value,'separator'=>"&nbsp;"),$_smarty_tpl);?>

            </div>
        </div>
        <?php }?>
    </form>
</div>
<script type="text/javascript">
$('.picker').datepicker({
    format: "yyyy-mm-dd",
    autoclose: true
}).on('changeDate', function(ev){
    $(this).datepicker('hide');
});

//$('[name="end-major"]').select2();

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

/*
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

        }, 'json');
});
*/
</script><?php }} ?>
