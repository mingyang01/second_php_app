<?php /* Smarty version Smarty-3.1.18, created on 2015-09-28 14:40:38
         compiled from "/home/work/websites/tuan/protected/views/qingcang/first/first.html" */ ?>
<?php /*%%SmartyHeaderCode:194126878555d40fade4bd31-38908993%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '5a30911b726311ab556067a2d04ff6ca68855776' => 
    array (
      0 => '/home/work/websites/tuan/protected/views/qingcang/first/first.html',
      1 => 1441860301,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '194126878555d40fade4bd31-38908993',
  'function' => 
  array (
  ),
  'version' => 'Smarty-3.1.18',
  'unifunc' => 'content_55d40fadee7d49_26692016',
  'variables' => 
  array (
    'event' => 0,
    'shop' => 0,
    'catagory' => 0,
    'area' => 0,
    'cs_level' => 0,
    'order_type' => 0,
    'realStatus' => 0,
    'level' => 0,
    'total' => 0,
    'count' => 0,
    'page' => 0,
    'pass_reason' => 0,
    'item' => 0,
    'fail_reason' => 0,
  ),
  'has_nocache_code' => false,
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_55d40fadee7d49_26692016')) {function content_55d40fadee7d49_26692016($_smarty_tpl) {?><?php if (!is_callable('smarty_function_html_options')) include '/home/work/framework/extensions/Smarty/plugins/function.html_options.php';
?><?php echo $_smarty_tpl->getSubTemplate ("qingcang/layouts/header.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, null, array(), 0);?>

<script type="text/javascript" src="/assets/lib/bootstrap-datepicker.js"></script>
<style type="text/css" src="/assets/css/datepicker.css"></style>
<script src="/assets/lib/bufferview.js"></script>
<script src="/assets/lib/jquery.pin.js"></script>
<style type="text/css" src="/assets/css/qingcang/admin.css"></style>
<title>初审</title>

<div class="container">
    <div class="row">
        <div class="col-md-12">
            <ol class="breadcrumb">
                <li><a href="/">Home</a></li>
                <li><a href="/">宝贝审核</a></li>
                <li class="active">初审</li>
            </ol>
            <div id="well" class="well">
			    <form class="form-horizontal" role="form" id="form">
			    <input value="<?php echo $_smarty_tpl->tpl_vars['event']->value;?>
" type="hidden" class="form-control col-md-2" name="event">
			        <div class="form-group">
			            <label class="col-md-1 control-label">店铺筛选</label>
			            <div class="col-md-2">
			                <input value="<?php echo $_smarty_tpl->tpl_vars['shop']->value;?>
" type="text" class="form-control col-md-2" name="shop" placeholder="ID">
			            </div>
			            <label class="col-md-1 control-label">一级类目</label>
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
			                </select>
			            </div>
			            <label class="col-md-1 control-label">区域</label>
			            <div class="col-md-2">
			                <select style="height:34px;" name="area" class="form-control">
			                    <option value="0">全部</option>
			                    <option <?php if ($_smarty_tpl->tpl_vars['area']->value=='1') {?>selected<?php }?> value="1">华东</option>
			                    <option <?php if ($_smarty_tpl->tpl_vars['area']->value=='3') {?>selected<?php }?> value="3">华南</option>
			                    <option <?php if ($_smarty_tpl->tpl_vars['area']->value=='2') {?>selected<?php }?> value="2">华北</option>
			                </select>
			            </div>
			            <label class="col-md-1 control-label">CS商家</label>
			            <div class="col-md-2">
			                <select style="height:34px;" name="cs_level" class="form-control">
			                    <option <?php if ($_smarty_tpl->tpl_vars['cs_level']->value=='0') {?>selected<?php }?> value="0">否</option>
			                    <option <?php if ($_smarty_tpl->tpl_vars['cs_level']->value=='1') {?>selected<?php }?> value="1">是</option>
			                </select>
			            </div>
			        </div>
			        <div class="form-group">
			            <label class="col-md-1 control-label">排序</label>
				        <div class="col-md-2">
				            <select  name="order_type" class="form-control">
				               <option value="">默认</option><?php echo smarty_function_html_options(array('options'=>SearchManager::$orderInfo,'selected'=>$_smarty_tpl->tpl_vars['order_type']->value),$_smarty_tpl);?>

				          </select>
				      </div>
			            <label class="col-md-1 control-label">审核状态</label>
			            <div class="col-md-2">
			                <select name="realStatus" class="form-control">
			                    <option <?php if ($_smarty_tpl->tpl_vars['realStatus']->value==10) {?>selected<?php }?> value="10">等待</option>
			                    <option <?php if ($_smarty_tpl->tpl_vars['realStatus']->value==20) {?>selected<?php }?> value="20">成功</option>
			                    <option <?php if ($_smarty_tpl->tpl_vars['realStatus']->value==21) {?>selected<?php }?> value="21">失败</option>
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
			            <button style="margin-right: 16px;float: right;" id="exportBtn" type="button" class="btn btn-success">导出 <span class="glyphicon glyphicon-save"></span></button>
			            <button style="margin-right: 16px;float: right;" id="submit" class="btn btn-default">查看</button>
			        </div>
			    </form>
			</div>
        </div>
    </div>
    <div  class="row " >
        <div class="col-md-12 pinned" style="padding-bottom:10px;">
            <div style="padding-top:4px;color:#fd6699;font-size:18px">
                <span id="tool-tip-count">
                共有:<?php echo $_smarty_tpl->tpl_vars['total']->value;?>
个店铺报名参加</span>
            </div>
        </div>
    </div>
    <?php echo $_smarty_tpl->getSubTemplate ("qingcang/first/first-content-detail.html", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, null, array(), 0);?>

    <?php if ($_smarty_tpl->tpl_vars['total']->value>$_smarty_tpl->tpl_vars['count']->value*$_smarty_tpl->tpl_vars['page']->value) {?>
    	<div  class="row" style="height:80px;"><div class="col-md-12" style="text-align:center;"><button id="load" class="col-md-12 btn btn-default">加载更多</button></div></div>
    <?php }?>
</div>

<!-- Modal -->
<div class="modal fade" id="passModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="passModalLabel">全部通过</h4>
      </div>
      <div class="modal-body">
        <?php  $_smarty_tpl->tpl_vars['item'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['item']->_loop = false;
 $_smarty_tpl->tpl_vars['key'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['pass_reason']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['item']->key => $_smarty_tpl->tpl_vars['item']->value) {
$_smarty_tpl->tpl_vars['item']->_loop = true;
 $_smarty_tpl->tpl_vars['key']->value = $_smarty_tpl->tpl_vars['item']->key;
?>
        <div class="radio">
            <label>
                <input type="radio" name="passRadios" value="<?php echo $_smarty_tpl->tpl_vars['item']->value['content'];?>
">
                <?php echo $_smarty_tpl->tpl_vars['item']->value['content'];?>

            </label>
        </div>
        <?php } ?>
        <textarea id="passRadiosReason" class="form-control" rows="3"></textarea>
        <input type="hidden" id="opt-shop-id" value="" />
        <input type="hidden" id="opt-event-id" value="" />
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        <button type="button" id="submit-pass" class="btn btn-primary">Save changes</button>
      </div>
    </div>
  </div>
</div>

<!-- Modal -->
<div class="modal fade" id="failModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="failModalLabel">全部退回</h4>
      </div>
      <div class="modal-body">
        <?php  $_smarty_tpl->tpl_vars['item'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['item']->_loop = false;
 $_smarty_tpl->tpl_vars['key'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['fail_reason']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['item']->key => $_smarty_tpl->tpl_vars['item']->value) {
$_smarty_tpl->tpl_vars['item']->_loop = true;
 $_smarty_tpl->tpl_vars['key']->value = $_smarty_tpl->tpl_vars['item']->key;
?>
        <div class="radio">
            <label>
                <input type="radio" name="failRadios" value="<?php echo $_smarty_tpl->tpl_vars['item']->value['content'];?>
">
                <?php echo $_smarty_tpl->tpl_vars['item']->value['content'];?>

            </label>
        </div>
        <?php } ?>
        <textarea id="failRadiosReason" class="form-control" rows="3"></textarea>
        <input type="hidden" id="opt-shop-id" value="" />
        <input type="hidden" id="opt-event-id" value="" />
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        <button id="submit-fail" type="button" class="btn btn-primary">Save changes</button>
      </div>
    </div>
  </div>
</div>

<script type="text/javascript" src="/assets/js/qingcang/common.js"></script>
<script type="text/javascript" src="/assets/js/qingcang/first.js"></script>
<?php echo $_smarty_tpl->getSubTemplate ("layouts/footer.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, null, array(), 0);?>
<?php }} ?>
