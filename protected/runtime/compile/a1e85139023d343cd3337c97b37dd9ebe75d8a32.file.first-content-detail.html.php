<?php /* Smarty version Smarty-3.1.18, created on 2015-09-28 14:40:38
         compiled from "/home/work/websites/tuan/protected/views/qingcang/first/first-content-detail.html" */ ?>
<?php /*%%SmartyHeaderCode:46052376155d40fae077bf2-26207922%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'a1e85139023d343cd3337c97b37dd9ebe75d8a32' => 
    array (
      0 => '/home/work/websites/tuan/protected/views/qingcang/first/first-content-detail.html',
      1 => 1441860301,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '46052376155d40fae077bf2-26207922',
  'function' => 
  array (
  ),
  'version' => 'Smarty-3.1.18',
  'unifunc' => 'content_55d40fae0bcce3_64319282',
  'variables' => 
  array (
    'shop_info' => 0,
    'item' => 0,
    'event' => 0,
    'realStatus' => 0,
  ),
  'has_nocache_code' => false,
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_55d40fae0bcce3_64319282')) {function content_55d40fae0bcce3_64319282($_smarty_tpl) {?><div class="row main-font" id="box-container">
<?php  $_smarty_tpl->tpl_vars['item'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['item']->_loop = false;
 $_smarty_tpl->tpl_vars['key'-'key'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['shop_info']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['item']->key => $_smarty_tpl->tpl_vars['item']->value) {
$_smarty_tpl->tpl_vars['item']->_loop = true;
 $_smarty_tpl->tpl_vars['key'-'key']->value = $_smarty_tpl->tpl_vars['item']->key;
?>
<div class="col-md-12" style="padding:0px;margin:0px;">
	<div class="thumbnail" style="height:200px;padding:0px;">
		<input type="hidden" id="shop_id" value="<?php echo $_smarty_tpl->tpl_vars['item']->value['shop_id'];?>
"/>
		<input type="hidden" id="op_eventid" value="<?php echo $_smarty_tpl->tpl_vars['event']->value;?>
"/>
	        <div class="col-md-2" style="height:200px; border-right: solid 1px #eee;padding: 0px;margin:0px;">
	            <p><?php if ($_smarty_tpl->tpl_vars['item']->value['ka']) {?><b><font color="red">KA</font></b><?php }?></p>
	            <div class="col-md-12" style="margin-top:45px;margin-left:25px;">
	                <p><a href="http://www.meilishuo.com/shop/<?php echo $_smarty_tpl->tpl_vars['item']->value['shop_id'];?>
" target="_blank"><?php echo $_smarty_tpl->tpl_vars['item']->value['shop_id'];?>
</a></p>
	                <p><a href="http://www.meilishuo.com/shop/<?php echo $_smarty_tpl->tpl_vars['item']->value['shop_id'];?>
" target="_blank"><?php echo $_smarty_tpl->tpl_vars['item']->value['shop_nick'];?>
</a></p>
	            </div>
	        </div>
	        <div class="col-md-8" style="margin-left:0px; padding:0px;">
	        	<div class="form-group" style="height:50px;margin:0px !important;padding:0px !important;border-bottom: solid 1px #eee;">
		        	<div class="col-md-3" style="height:50px;margin-top:2px;margin-bottom:0px;border-right: solid 1px #eee;">
		                主营类目：<?php echo $_smarty_tpl->tpl_vars['item']->value['category'];?>

		            </div>
		            <div class="col-md-2" style="height:50px;margin-top:2px;margin-bottom:0px;border-right: solid 1px #eee;">
		                商家等级：<?php echo $_smarty_tpl->tpl_vars['item']->value['level'];?>

		            </div>
		            <div class="col-md-3" style="height:50px;margin-top:2px;margin-bottom:0px;border-right: solid 1px #eee;">
		                本自然月参加次数：<?php echo $_smarty_tpl->tpl_vars['item']->value['had_join_count'];?>

		            </div>
		            <div class="col-md-2" style="height:50px;margin-top:2px;margin-bottom:0px;border-right: solid 1px #eee;">
		                黄金橱窗位：<?php echo $_smarty_tpl->tpl_vars['item']->value['window'];?>

		            </div>
		            <div class="col-md-2">
		                CS：<?php if ($_smarty_tpl->tpl_vars['item']->value['cs_level']) {?>是<?php } else { ?>否<?php }?>
		            </div>
	            </div>
	            <div class="form-group" style="height:50px;margin:0px !important;padding:0px !important;border-bottom: solid 1px #eee;">
		        	<div class="col-md-3" style="height:50px;margin-top:2px;margin-bottom:0px;border-right: solid 1px #eee;">
		                有理由退款率：<?php echo $_smarty_tpl->tpl_vars['item']->value['reason_refund_rate']*100;?>
%
		            </div>
		            <div class="col-md-2" style="height:50px;margin-top:2px;margin-bottom:0px;border-right: solid 1px #eee;">
		                近30天销售量：<?php echo $_smarty_tpl->tpl_vars['item']->value['paid_goods_num_30'];?>

		            </div>
		            <div class="col-md-3" style="height:50px;margin-top:2px;margin-bottom:0px;border-right: solid 1px #eee;">
		                近30天GMV：<?php echo $_smarty_tpl->tpl_vars['item']->value['gmv_30'];?>

		            </div>
		            <div class="col-md-2" style="height:50px;margin-top:2px;margin-bottom:0px;border-right: solid 1px #eee;">
		                近7天销售量：<?php echo $_smarty_tpl->tpl_vars['item']->value['paid_goods_num_7'];?>

		            </div>
		            <div class="col-md-2">
		                近7天GMV：<?php echo $_smarty_tpl->tpl_vars['item']->value['gmv_7'];?>

		            </div>
	            </div>
	            <div class="form-group" style="height:50px;margin:0px !important;padding:0px !important;border-bottom: solid 1px #eee;">
			        <div class="col-md-3" style="height:50px;margin-top:2px;margin-bottom:0px;border-right: solid 1px #eee;">
			            转化率：<?php echo $_smarty_tpl->tpl_vars['item']->value['shop_buy_rate']*100;?>
%
			        </div>
			         <div class="col-md-2" style="height:50px;margin-top:2px;margin-bottom:0px;border-right: solid 1px #eee;">
			            区域：<?php echo $_smarty_tpl->tpl_vars['item']->value['area'];?>

			         </div>
			         <div class="col-md-6" style="height:50px;">
			             待审核商品数：<?php echo $_smarty_tpl->tpl_vars['item']->value['uncheck'];?>

			         </div>
		         </div>
		         <div class="form-group" style="margin:0px !important;padding:0px !important;border-bottom: solid 1px #eee;">
			        <div class="col-md-12" style="height:50px;">
			            历史清仓：<?php if ($_smarty_tpl->tpl_vars['item']->value['history']['event_id']) {?><?php echo $_smarty_tpl->tpl_vars['item']->value['history']['event_id'];?>
活动在<?php echo $_smarty_tpl->tpl_vars['item']->value['history']['time'];?>
<?php }?>
			        </div>
		         </div>
		     </div>
	        <div class="col-md-2" style="height:200px; border-left: solid 1px #eee;padding: 0px;margin:0px;">
	        	<div class="form-group" style="height:145px;margin-right:0px !important;">
		        	<div class="col-md-12" style="height:135px;margin-top:15px;border-bottom: solid 1px #eee;">
		                <p>联系方式</p>
		                <p>QQ：<?php echo $_smarty_tpl->tpl_vars['item']->value['partner_qq'];?>
</p>
			            <p>电话:<?php echo $_smarty_tpl->tpl_vars['item']->value['partner_tel'];?>
</p>
		            </div>
	            </div>
	            <?php if ($_smarty_tpl->tpl_vars['realStatus']->value==10) {?>
	            <div class="form-group" style="height:55px;margin-left:10px;margin:0px !important;">
	            	<div class="col-md-6" >
		                <a id="refuse" style="margin-left:10px" class="pull-right btn btn-default" onclick="refuse('<?php echo $_smarty_tpl->tpl_vars['event']->value;?>
', '<?php echo $_smarty_tpl->tpl_vars['item']->value['shop_id'];?>
')" data-toggle="modal" data-target="#failModal">退回</a>
		            </div>
		            <div class="col-md-6">
		                <a id="pass" class="pull-right btn btn-default" data-toggle="modal" onclick="pass('<?php echo $_smarty_tpl->tpl_vars['event']->value;?>
', '<?php echo $_smarty_tpl->tpl_vars['item']->value['shop_id'];?>
')" data-target="#passModal">通过</a>
		            </div>
	            </div>
	            <?php }?>
	        </div>
    	</div>
  
</div>
<?php }
if (!$_smarty_tpl->tpl_vars['item']->_loop) {
?>
	<div class="col-md-12">没有待审核店铺数据</div>
<?php } ?>
</div>
<?php }} ?>
