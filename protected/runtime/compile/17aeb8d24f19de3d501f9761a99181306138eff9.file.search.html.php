<?php /* Smarty version Smarty-3.1.18, created on 2015-08-19 15:16:38
         compiled from "/home/work/websites/tuan/protected/views/eventGoods/search.html" */ ?>
<?php /*%%SmartyHeaderCode:39515995755d42d56763178-92661981%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '17aeb8d24f19de3d501f9761a99181306138eff9' => 
    array (
      0 => '/home/work/websites/tuan/protected/views/eventGoods/search.html',
      1 => 1439522157,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '39515995755d42d56763178-92661981',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'categoryInfo' => 0,
    'k' => 0,
    'audit_status' => 0,
    'v' => 0,
    'status' => 0,
    'priceRangeInfo' => 0,
    'price' => 0,
    'category' => 0,
    'twitter_id' => 0,
    'shop_val' => 0,
    'level' => 0,
    'isshow_tag' => 0,
    'order_type' => 0,
    'event' => 0,
    'schedule_start_time' => 0,
    'schedule_end_time' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.18',
  'unifunc' => 'content_55d42d567cb605_10013046',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_55d42d567cb605_10013046')) {function content_55d42d567cb605_10013046($_smarty_tpl) {?><?php if (!is_callable('smarty_function_html_options')) include '/home/work/framework/extensions/Smarty/plugins/function.html_options.php';
?><div class="form-group">
    <label class="col-md-1 control-label">排期状态</label>
    <div class="col-md-2">
        <?php $_smarty_tpl->tpl_vars['categoryInfo'] = new Smarty_variable(EventGoodsManager::$auditStatusInfo, null, 0);?>
        <select name="audit_status" class="form-control">
            <?php  $_smarty_tpl->tpl_vars['v'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['v']->_loop = false;
 $_smarty_tpl->tpl_vars['k'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['categoryInfo']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['v']->key => $_smarty_tpl->tpl_vars['v']->value) {
$_smarty_tpl->tpl_vars['v']->_loop = true;
 $_smarty_tpl->tpl_vars['k']->value = $_smarty_tpl->tpl_vars['v']->key;
?>
                <option value="<?php echo $_smarty_tpl->tpl_vars['k']->value;?>
" <?php if ($_smarty_tpl->tpl_vars['audit_status']->value==$_smarty_tpl->tpl_vars['k']->value) {?>selected<?php }?>><?php echo $_smarty_tpl->tpl_vars['v']->value;?>
</option>
            <?php } ?>
        </select>
    </div>
    <label class="col-md-1 control-label">分配状态</label>
    <div class="col-md-2">
        <?php $_smarty_tpl->tpl_vars['categoryInfo'] = new Smarty_variable(EventGoodsManager::$statusInfo, null, 0);?>
        <select name="status" class="form-control">
            <?php  $_smarty_tpl->tpl_vars['v'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['v']->_loop = false;
 $_smarty_tpl->tpl_vars['k'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['categoryInfo']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['v']->key => $_smarty_tpl->tpl_vars['v']->value) {
$_smarty_tpl->tpl_vars['v']->_loop = true;
 $_smarty_tpl->tpl_vars['k']->value = $_smarty_tpl->tpl_vars['v']->key;
?>
                <option value="<?php echo $_smarty_tpl->tpl_vars['k']->value;?>
" <?php if ($_smarty_tpl->tpl_vars['status']->value==$_smarty_tpl->tpl_vars['k']->value) {?>selected<?php }?>><?php echo $_smarty_tpl->tpl_vars['v']->value;?>
</option>
            <?php } ?>
        </select>
    </div>
    <label class="col-md-1 control-label">价格筛选</label>
    <div class="col-md-2">
        <?php $_smarty_tpl->tpl_vars['priceRangeInfo'] = new Smarty_variable(OnlineManager::$priceRangeInfo, null, 0);?>
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
        <?php $_smarty_tpl->tpl_vars['categoryInfo'] = new Smarty_variable(OnlineManager::$categoryInfo, null, 0);?>
        <select name="category" class="form-control">
            <option value="">全部</option>
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
</div>
<div class="form-group">
    <label class="col-md-1 control-label">推ID</label>
    <div class="col-md-2">
        <input class="form-control" name="twitter_id"  type="text" value="<?php echo $_smarty_tpl->tpl_vars['twitter_id']->value;?>
">
    </div>
    <label class="col-md-1 control-label">店铺查询</label>
    <div class="col-md-2">
        <input class="form-control" name="shop_val"  type="text" value="<?php echo $_smarty_tpl->tpl_vars['shop_val']->value;?>
">
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
    <label class="col-md-1 control-label">是否精品</label>
    <div class="col-md-2">
        <select name="isshow_tag" class="form-control">
            <option <?php if ($_smarty_tpl->tpl_vars['isshow_tag']->value=='#') {?>selected<?php }?> value="#">全部</option>
            
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
    <input type="hidden" name="event_id" value="<?php echo $_smarty_tpl->tpl_vars['event']->value;?>
">
    <input type="hidden" name="schedule_start_time" value="<?php echo $_smarty_tpl->tpl_vars['schedule_start_time']->value;?>
">
    <input type="hidden" name="schedule_end_time" value="<?php echo $_smarty_tpl->tpl_vars['schedule_end_time']->value;?>
">
    
    <button style="margin-right: 16px;float: right;" id="exportBtn" type="button" class="btn btn-success">导出 <span class="glyphicon glyphicon-save"></span></button>
    <button style="float: right;margin-right: 16px;" id="searchSubmit" class="btn btn-default">查看</button>
</div>
<script>
$(function(){
  // 导出
  $("#exportBtn").click(function(e){
    e.stopPropagation
    e.preventDefault();
    var excelStr = window.location.href.indexOf('?') == -1 ? '?' : '&';
    window.open(window.location.href + excelStr + 'excel=1');
  });
  
});
</script><?php }} ?>
