<?php /* Smarty version Smarty-3.1.18, created on 2015-09-28 14:41:41
         compiled from "/home/work/websites/tuan/protected/views/online/search.html" */ ?>
<?php /*%%SmartyHeaderCode:47072585155d55a344c3df8-15040798%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '8d8e3a3b73c923a65ff7b7b898e8265393ae5885' => 
    array (
      0 => '/home/work/websites/tuan/protected/views/online/search.html',
      1 => 1441860301,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '47072585155d55a344c3df8-15040798',
  'function' => 
  array (
  ),
  'version' => 'Smarty-3.1.18',
  'unifunc' => 'content_55d55a345409b1_89910200',
  'variables' => 
  array (
    'priceRangeInfo' => 0,
    'k' => 0,
    'price' => 0,
    'categoryInfo' => 0,
    'category' => 0,
    'v' => 0,
    'endCatagory' => 0,
    'shop_val' => 0,
    'twitter_id' => 0,
    'recommendDate' => 0,
    'audit_status' => 0,
    'from' => 0,
    'to' => 0,
    'recommend_status' => 0,
    'date' => 0,
    'level' => 0,
    'type' => 0,
    'isshow_tag' => 0,
    'order_type' => 0,
  ),
  'has_nocache_code' => false,
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_55d55a345409b1_89910200')) {function content_55d55a345409b1_89910200($_smarty_tpl) {?><?php if (!is_callable('smarty_function_html_options')) include '/home/work/framework/extensions/Smarty/plugins/function.html_options.php';
?><link href="/assets/lib/select2.min.css" rel="stylesheet" />
<script src="/assets/lib/select2.min.js"></script>
<div class="form-group">
    <label class="col-md-1 control-label">价格筛选</label>
    <div class="col-md-2">
        <?php $_smarty_tpl->tpl_vars['priceRangeInfo'] = new Smarty_variable(onlineManager::$priceRangeInfo, null, 0);?>
        <select name="price" class="form-control">
            <option value="">全部</option>
            <?php  $_smarty_tpl->tpl_vars['v'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['v']->_loop = false;
 $_smarty_tpl->tpl_vars['k'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['priceRangeInfo']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['v']->key => $_smarty_tpl->tpl_vars['v']->value) {
$_smarty_tpl->tpl_vars['v']->_loop = true;
 $_smarty_tpl->tpl_vars['k']->value = $_smarty_tpl->tpl_vars['v']->key;
?>
                <option value="<?php echo $_smarty_tpl->tpl_vars['k']->value;?>
" <?php if ($_smarty_tpl->tpl_vars['price']->value==$_smarty_tpl->tpl_vars['k']->value) {?>selected<?php }?>><?php echo $_smarty_tpl->tpl_vars['k']->value;?>
</option>
            <?php } ?>
        </select>
    </div>
    <label class="col-md-1 control-label">类目筛选</label>
    <div class="col-md-2">
        <?php $_smarty_tpl->tpl_vars['categoryInfo'] = new Smarty_variable(onlineManager::$categoryInfo, null, 0);?>
        <select name="category" class="form-control">
            <option value="0">全部</option>
            <?php  $_smarty_tpl->tpl_vars['v'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['v']->_loop = false;
 $_smarty_tpl->tpl_vars['k'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['categoryInfo']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['v']->key => $_smarty_tpl->tpl_vars['v']->value) {
$_smarty_tpl->tpl_vars['v']->_loop = true;
 $_smarty_tpl->tpl_vars['k']->value = $_smarty_tpl->tpl_vars['v']->key;
?>
                <option value="<?php echo $_smarty_tpl->tpl_vars['k']->value;?>
" <?php if ($_smarty_tpl->tpl_vars['category']->value==$_smarty_tpl->tpl_vars['k']->value) {?>selected<?php }?>><?php echo $_smarty_tpl->tpl_vars['v']->value;?>
</option>
            <?php } ?>
        </select>
    </div>
    <label class="col-md-1 control-label">末级分类</label>
    <div class="col-md-2">
        <select name="end-major" class="form-control" data-value="<?php echo $_smarty_tpl->tpl_vars['endCatagory']->value;?>
">
            <option value="0">不限</option>
        </select>
    </div>
    <label class="col-md-1 control-label">店铺查询</label>
    <div class="col-md-2">
        <input class="form-control" name="shop_val"  type="text" value="<?php echo $_smarty_tpl->tpl_vars['shop_val']->value;?>
">
    </div>
</div>
<div class="form-group">
    <label class="col-md-1 control-label">推ID</label>
    <div class="col-md-2">
        <input class="form-control" name="twitter_id"  type="text" value="<?php echo $_smarty_tpl->tpl_vars['twitter_id']->value;?>
">
    </div>
    <?php if (!$_smarty_tpl->tpl_vars['recommendDate']->value) {?>
    <label class="col-md-1 control-label">排期进度</label>
    <div class="col-md-2">
        <select name="audit_status" class="form-control">
            <option <?php if ($_smarty_tpl->tpl_vars['audit_status']->value==40) {?>selected<?php }?> value="40">等待排期</option>
            <option <?php if ($_smarty_tpl->tpl_vars['audit_status']->value==50) {?>selected<?php }?> value="50">排期成功</option>
        </select>
    </div>
    <?php } else { ?>
      <label class="col-md-1 control-label"><span class="time-label"><?php if ($_smarty_tpl->tpl_vars['audit_status']->value==50) {?>排期<?php } else { ?>报名<?php }?></span>开始</label>
      <div class="col-md-2">
          <input class="picker form-control"  name="from"  type="text" value="<?php echo $_smarty_tpl->tpl_vars['from']->value;?>
"  data-date-format="yyyy-mm-dd">
      </div>
      <label class="col-md-1 control-label"><span class="time-label"><?php if ($_smarty_tpl->tpl_vars['audit_status']->value==50) {?>排期<?php } else { ?>报名<?php }?></span>结束</label>
      <div class="col-md-2">
          <input class="picker form-control"  name="to"  type="text" value="<?php echo $_smarty_tpl->tpl_vars['to']->value;?>
"  data-date-format="yyyy-mm-dd">
      </div>
        <input type="hidden" name="audit_status" value="50">
        <label class="col-md-1 control-label">排期进度</label>
        <div class="col-md-2">
            <select name="recommend_status" class="form-control">
                <option <?php if ($_smarty_tpl->tpl_vars['recommend_status']->value==1) {?>selected<?php }?> value="1">等待推荐</option>
                <option <?php if ($_smarty_tpl->tpl_vars['recommend_status']->value==2) {?>selected<?php }?> value="2">推荐成功</option>
            </select>
        </div>
    <?php }?>
    <label <?php if ($_smarty_tpl->tpl_vars['recommendDate']->value&&$_smarty_tpl->tpl_vars['recommend_status']->value==1) {?>style="display:none;"<?php }?>  class="col-md-1 control-label recommendDateCon">推荐日期</label>
    <div class="col-md-2">
        <input <?php if ($_smarty_tpl->tpl_vars['recommendDate']->value&&$_smarty_tpl->tpl_vars['recommend_status']->value==1) {?>style="display:none;"<?php }?> class="picker form-control recommendDateCon" id="date" name="date"  type="text" value="<?php echo $_smarty_tpl->tpl_vars['date']->value;?>
"  data-date-format="yyyy-mm-dd">
    </div>
</div>
<div class="form-group">
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
      <label class="col-md-1 control-label">商品类型</label>
      <div class="col-md-2">
          <select name="type" class="form-control">
              <option <?php if ($_smarty_tpl->tpl_vars['type']->value=='0') {?>selected<?php }?> value="0">普通团购</option>
              
          </select>
      </div>

        
      <label class="col-md-1 control-label">是否精品</label>
      <div class="col-md-2">
          <select name="isshow_tag" class="form-control">
              <option <?php if ($_smarty_tpl->tpl_vars['isshow_tag']->value=='#') {?>selected<?php }?> value="#">全部</option>
              <option <?php if ($_smarty_tpl->tpl_vars['isshow_tag']->value=='0') {?>selected<?php }?> value="0">精品</option>
              <option <?php if ($_smarty_tpl->tpl_vars['isshow_tag']->value=='1') {?>selected<?php }?> value="1">非精品</option>
          </select>
      </div>
</div>
<div class="form-group">
    <label class="col-md-1 control-label">排序</label>
    <div class="col-md-2">
        <select  name="order_type" class="form-control">
            <option value="">默认</option>
            <?php echo smarty_function_html_options(array('options'=>SearchManager::$orderInfo,'selected'=>$_smarty_tpl->tpl_vars['order_type']->value),$_smarty_tpl);?>

        </select>
    </div>
    <button style="margin-right: 16px;float: right;" id="exportBtn" type="button" class="btn btn-success">导出 <span class="glyphicon glyphicon-save"></span></button>
    <button style="float: right;margin-right: 16px;" id="searchSubmit" class="btn btn-default">查看</button>
</div>
<script>
$(function(){
  $('[name="type"]').change(function(e){
    if (this.value == '0') {
        $('.event').hide();
        $('[name="event"]').val(0)
    } else {
        $('.event').show();
    }
  });
  
  // 导出
  $("#exportBtn").click(function(e){
    e.stopPropagation
    e.preventDefault();
    var excelStr = window.location.href.indexOf('?') == -1 ? '?' : '&';
    window.open(window.location.href + excelStr + 'excel=1');
  });
  
  $('[name="end-major"]').select2();
  
  $('[name="category"]').change(function(e){
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
  
  $('[name="category"]').change();
});
</script><?php }} ?>
