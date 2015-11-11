<?php /* Smarty version Smarty-3.1.18, created on 2015-08-19 14:18:19
         compiled from "/home/work/websites/tuan/protected/views/qingcang/second/second-content-detail.html" */ ?>
<?php /*%%SmartyHeaderCode:199968005855d41fab164c54-25298130%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'abe3785d8ab8d775ea599071d0e0afd27be68ce6' => 
    array (
      0 => '/home/work/websites/tuan/protected/views/qingcang/second/second-content-detail.html',
      1 => 1439522157,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '199968005855d41fab164c54-25298130',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'shop_info' => 0,
    'item' => 0,
    'step' => 0,
    'val' => 0,
    'CheckTips' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.18',
  'unifunc' => 'content_55d41fab1fc6b4_82971451',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_55d41fab1fc6b4_82971451')) {function content_55d41fab1fc6b4_82971451($_smarty_tpl) {?><div class="row main-font" id="box-container">
	<?php  $_smarty_tpl->tpl_vars['item'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['item']->_loop = false;
 $_smarty_tpl->tpl_vars['key'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['shop_info']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['item']->key => $_smarty_tpl->tpl_vars['item']->value) {
$_smarty_tpl->tpl_vars['item']->_loop = true;
 $_smarty_tpl->tpl_vars['key']->value = $_smarty_tpl->tpl_vars['item']->key;
?>
	<div class="col-md-12" style="padding:0px;margin:0px;">
		<div class="well thumbnail-shop" data-shop="shop-<?php echo $_smarty_tpl->tpl_vars['item']->value['shop_id'];?>
" style="height:<?php echo $_smarty_tpl->tpl_vars['item']->value['hight'];?>
px;padding:0px;">
			<div class="col-md-12" style="height:200px;padding:0px;margin:0px;">
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
		    </div>
		    <?php  $_smarty_tpl->tpl_vars['val'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['val']->_loop = false;
 $_smarty_tpl->tpl_vars['k'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['item']->value['good_info']['data']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['val']->key => $_smarty_tpl->tpl_vars['val']->value) {
$_smarty_tpl->tpl_vars['val']->_loop = true;
 $_smarty_tpl->tpl_vars['k']->value = $_smarty_tpl->tpl_vars['val']->key;
?>
		    <div class="col-md-6 good_info">
		        <div data-total="<?php echo $_smarty_tpl->tpl_vars['item']->value['good_info']['total'];?>
" data-shop="good-<?php echo $_smarty_tpl->tpl_vars['item']->value['shop_id'];?>
" data-step="<?php echo $_smarty_tpl->tpl_vars['step']->value;?>
" data-gid="<?php echo $_smarty_tpl->tpl_vars['val']->value['gid'];?>
" data-twitter="<?php echo $_smarty_tpl->tpl_vars['val']->value['tid'];?>
" class="thumbnail" style="height: 350px;">
		            <div style="line-height:24px;height: 30px; border-bottom: solid 1px #ccc; margin-bottom:4px;" class="col-md-12">
		                <strong style="font-size:18px;"><?php $_smarty_tpl->tpl_vars['CheckTips'] = new Smarty_variable(QcheckManager::$tipsTypeEnum, null, 0);?>
		                <?php echo $_smarty_tpl->tpl_vars['CheckTips']->value[$_smarty_tpl->tpl_vars['val']->value['audit_status']];?>
 <?php echo $_smarty_tpl->tpl_vars['item']->value['gid'];?>
</strong>
		                <?php if ($_smarty_tpl->tpl_vars['val']->value['is_danger']||$_smarty_tpl->tpl_vars['val']->value['is_bad']) {?><strong style="color:red;">高危商品</strong><?php }?>
		                <span class="level pull-right" style="color: #f69;"><?php if ($_smarty_tpl->tpl_vars['val']->value['isshow']==1) {?>普通<?php }?><?php if ($_smarty_tpl->tpl_vars['val']->value['isshow']==0) {?>精品<?php }?>&nbsp;&nbsp;<?php if ($_smarty_tpl->tpl_vars['val']->value['level']) {?><?php echo $_smarty_tpl->tpl_vars['val']->value['level'];?>
<?php }?></span>
		            </div>
		            <div style="line-height:24px;height: 30px; border-bottom: solid 1px #ccc; margin-bottom:4px;" class="col-md-12">
		                清仓历史：<?php echo html_entity_decode($_smarty_tpl->tpl_vars['val']->value['tuan_history']);?>

		            </div>
		            <div class="col-md-4" >
		                <div class="img">
		                    <a target="_blank" href="http://www.meilishuo.com/share/item/<?php echo $_smarty_tpl->tpl_vars['val']->value['tid'];?>
">
		                    <img data-src="holder.js/100%x200" alt="100%x200" src="<?php echo Yii::app()->image->getWebsiteImageUrl($_smarty_tpl->tpl_vars['val']->value['img']);?>
" data-holder-rendered="true" style="height: 100%; width: 100%; display: block;"></a>
		                </div>
		            </div>
		            <div class="col-md-4" style="padding-right:0px;">
		                <p><b><?php echo $_smarty_tpl->tpl_vars['val']->value['name'];?>
</b></p>
		                <p><?php echo $_smarty_tpl->tpl_vars['val']->value['tid'];?>
</p>
		                <p style="height: 20px;overflow: hidden;"><?php echo $_smarty_tpl->tpl_vars['val']->value['goods_first_catalog'];?>
—<?php echo $_smarty_tpl->tpl_vars['val']->value['goods_three_catalog'];?>
</p>
		                <p>销量：<?php echo $_smarty_tpl->tpl_vars['val']->value['sale_num'];?>
&nbsp;&nbsp;库存：<?php echo $_smarty_tpl->tpl_vars['val']->value['repertory'];?>
</p>
		                <p>
		                    CVR：<?php echo $_smarty_tpl->tpl_vars['val']->value['cvr']*100;?>

		                </p>
		                <p>流行分：<?php echo $_smarty_tpl->tpl_vars['val']->value['popularity_score'];?>
&nbsp;&nbsp;热销分：<?php echo $_smarty_tpl->tpl_vars['val']->value['sellrate_score'];?>
</p>
		                <p class="rec_sku">
		                    <span class="price_red"><?php echo $_smarty_tpl->tpl_vars['val']->value['price'];?>
</span>
		                    <span class="sameInfo"></span>
		                    <span class="price"><?php echo $_smarty_tpl->tpl_vars['val']->value['origin'];?>
</span>
		                    <span class="price_red pull-right"><?php echo $_smarty_tpl->tpl_vars['val']->value['rate']*10;?>
折</span>
		                </p>
		            </div>
		            <div class="col-md-4">
		                <p><b><?php echo $_smarty_tpl->tpl_vars['val']->value['shop_nick'];?>
</b></p>
		                <p><?php echo $_smarty_tpl->tpl_vars['item']->value['shop'];?>
</p>
		                <p><div style="width:40px;display:inline-block;">电话:</div><?php echo $_smarty_tpl->tpl_vars['val']->value['partner_tel'];?>
</p>
		                <p><span style="width:40px;display:inline-block;">QQ:</span><?php echo $_smarty_tpl->tpl_vars['val']->value['partner_qq'];?>
</p>
		                <p>
		                    报名时间：<?php echo current(explode(' ',$_smarty_tpl->tpl_vars['val']->value['createTime']));?>

		                </p>
		                <p class="tool">
		                    
		                </p>
		            </div>
		            <div style="height: 30px; border-top: solid 1px #ccc; padding-bottom:4px;" class="col-md-12">
		                <p>特别说明：<?php echo $_smarty_tpl->tpl_vars['val']->value['comments'];?>
</p>
		            </div>
		        </div>
		    </div>
		    <?php } ?>
	    </div>
	</div>
	<?php }
if (!$_smarty_tpl->tpl_vars['item']->_loop) {
?>
		<div class="col-md-12">没有待审核店铺数据</div>
	<?php } ?>  
</div>
<?php }} ?>
