<?php /* Smarty version Smarty-3.1.18, created on 2015-08-25 12:04:14
         compiled from "/home/work/websites/tuan/protected/views/audit/first-content-detail.html" */ ?>
<?php /*%%SmartyHeaderCode:55290741655cd5e04ea6049-95815268%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '66871dd50915522504afa07a539e875a5aaad3bd' => 
    array (
      0 => '/home/work/websites/tuan/protected/views/audit/first-content-detail.html',
      1 => 1440065605,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '55290741655cd5e04ea6049-95815268',
  'function' => 
  array (
  ),
  'version' => 'Smarty-3.1.18',
  'unifunc' => 'content_55cd5e04f2c1c4_80465120',
  'variables' => 
  array (
    'data' => 0,
    'item' => 0,
    'step' => 0,
    'CheckTips' => 0,
    'v' => 0,
    'status' => 0,
  ),
  'has_nocache_code' => false,
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_55cd5e04f2c1c4_80465120')) {function content_55cd5e04f2c1c4_80465120($_smarty_tpl) {?><style type="text/css">
.img {
height: 200px;
}

.level {
    color: #f69;
    font-size: 18px;
}

.tool {
    position: relative;
    /*top: 20px;*/
    left: 40px;
}

.btn-danger {
    background-color: #f46;
}

.popover {
  max-width: 562px;
}

.ComparePrice{
    display: none;
}
</style>



<div class="row" id="box-container">
    <?php  $_smarty_tpl->tpl_vars['item'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['item']->_loop = false;
 $_smarty_tpl->tpl_vars['key'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['data']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['item']->key => $_smarty_tpl->tpl_vars['item']->value) {
$_smarty_tpl->tpl_vars['item']->_loop = true;
 $_smarty_tpl->tpl_vars['key']->value = $_smarty_tpl->tpl_vars['item']->key;
?>
    <div class="col-md-6">
        <div data-shop="<?php echo $_smarty_tpl->tpl_vars['item']->value['shop'];?>
" data-step="<?php echo $_smarty_tpl->tpl_vars['step']->value;?>
" data-gid="<?php echo $_smarty_tpl->tpl_vars['item']->value['gid'];?>
" data-twitter="<?php echo $_smarty_tpl->tpl_vars['item']->value['tid'];?>
" class="thumbnail" style="height: 360px;">
            <div style="line-height:24px;height: 30px; border-bottom: solid 1px #ccc; margin-bottom:4px;" class="col-md-12">
                <strong style="font-size:18px;"><?php $_smarty_tpl->tpl_vars['CheckTips'] = new Smarty_variable(CheckTipsManager::$tipsTypeEnum, null, 0);?>
                <?php echo $_smarty_tpl->tpl_vars['CheckTips']->value[$_smarty_tpl->tpl_vars['item']->value['audit_status']];?>
 <?php echo $_smarty_tpl->tpl_vars['item']->value['gid'];?>
</strong>
                <?php if ($_smarty_tpl->tpl_vars['v']->value['is_danger']||$_smarty_tpl->tpl_vars['v']->value['is_bad']) {?><strong style="color:red;">高危商品</strong><?php }?>
                <span class="level pull-right" style="color: #f69;"><?php if ($_smarty_tpl->tpl_vars['item']->value['isshow']==1) {?>普通<?php }?><?php if ($_smarty_tpl->tpl_vars['item']->value['isshow']==0) {?>精品<?php }?>&nbsp;&nbsp;<?php if ($_smarty_tpl->tpl_vars['item']->value['level']) {?><?php echo $_smarty_tpl->tpl_vars['item']->value['level'];?>
<?php }?></span>
            </div>
            <div style="line-height:24px;height: 30px; border-bottom: solid 1px #ccc; margin-bottom:4px;" class="col-md-12">
                团购历史：<?php echo html_entity_decode($_smarty_tpl->tpl_vars['item']->value['tuan_history']);?>

            </div>
            <div class="col-md-4" >
                <div class="img">
                    <a target="_blank" href="http://www.meilishuo.com/share/item/<?php echo $_smarty_tpl->tpl_vars['item']->value['tid'];?>
">
                    <img data-src="holder.js/100%x200" alt="100%x200" src="<?php echo Yii::app()->image->getWebsiteImageUrl($_smarty_tpl->tpl_vars['item']->value['img']);?>
" data-holder-rendered="true" style="height: 100%; width: 100%; display: block;"></a>
                </div>
            </div>
            <div class="col-md-4" style="padding-right:0px;">
                <h3 style="margin-top:0px;height:35px" id="thumbnail-label" class="thumbnailTitle"><?php echo $_smarty_tpl->tpl_vars['item']->value['name'];?>
</h3>
                <p><?php echo $_smarty_tpl->tpl_vars['item']->value['tid'];?>
</p>
                <p style="height: 20px;overflow: hidden;"><?php echo $_smarty_tpl->tpl_vars['item']->value['goods_first_catalog'];?>
—<?php echo $_smarty_tpl->tpl_vars['item']->value['goods_three_catalog'];?>
</p>
                <p>销量：<?php echo $_smarty_tpl->tpl_vars['item']->value['sale_num'];?>
&nbsp;&nbsp;库存：<?php echo $_smarty_tpl->tpl_vars['item']->value['repertory'];?>
</p>
                <p>
                    CVR：<?php echo $_smarty_tpl->tpl_vars['item']->value['cvr']*100;?>

                </p>
                <p>流行分：<?php echo $_smarty_tpl->tpl_vars['item']->value['popularity_score'];?>
&nbsp;&nbsp;热销分：<?php echo $_smarty_tpl->tpl_vars['item']->value['sellrate_score'];?>
</p>
                <p class="rec_sku">
                    <span class="price_red"><?php echo $_smarty_tpl->tpl_vars['item']->value['price'];?>
</span>
                    <span class="sameInfo"></span>
                    <span class="price"><?php echo $_smarty_tpl->tpl_vars['item']->value['origin'];?>
</span>
                    <span class="price_red pull-right"><?php echo $_smarty_tpl->tpl_vars['item']->value['rate']*10;?>
折</span>
                </p>
            </div>
            <div class="col-md-4">
                <h3 style="margin-top:0px;height:35px" id="thumbnail-label"><?php echo $_smarty_tpl->tpl_vars['item']->value['shop_nick'];?>
</h3>
                <p><?php echo $_smarty_tpl->tpl_vars['item']->value['shop'];?>
</p>
                <p><div style="width:40px;display:inline-block;">电话:</div><?php echo $_smarty_tpl->tpl_vars['item']->value['partner_tel'];?>
</p>
                <p><span style="width:40px;display:inline-block;">QQ:</span><?php echo $_smarty_tpl->tpl_vars['item']->value['partner_qq'];?>
</p>
                <p>
                    报名时间：<?php echo current(explode(' ',$_smarty_tpl->tpl_vars['item']->value['createTime']));?>

                </p>
                <p>
                   <?php if ($_smarty_tpl->tpl_vars['item']->value['start_time']) {?>
                     <strong style="color:red;">排期：</strong><?php echo date("Y-m-d H:i",$_smarty_tpl->tpl_vars['item']->value['start_time']);?>

                   <?php } else { ?>
                     &nbsp;
                   <?php }?>
                </p>
                <p class="tool">
                   
                   <?php if ($_smarty_tpl->tpl_vars['step']->value!=2||$_smarty_tpl->tpl_vars['status']->value!=0) {?>
                    <span class="historyCon">
                      <button type="button" data-gid=<?php echo $_smarty_tpl->tpl_vars['item']->value['gid'];?>
 class="showHistoryBtn btn btn-default" data-html="true" data-toggle="popover" data-placement="bottom">历史</button>
                    </span>
                    <?php }?>
                    <?php if ($_smarty_tpl->tpl_vars['step']->value==2&&$_smarty_tpl->tpl_vars['status']->value==0) {?>
                    <span class="historyCon">
                      <button type="button" data-gid=<?php echo $_smarty_tpl->tpl_vars['item']->value['gid'];?>
 class="changePriceBtn btn btn-danger">改价</button>
                    </span>
                    <span>
                      <button type="button" data-gid=<?php echo $_smarty_tpl->tpl_vars['item']->value['tid'];?>
 class="ComparePrice btn btn-info"
                      data-html="true" data-toggle="popover" data-placement="bottom">同款</button>
                    </span>
                    <?php }?>
                    <?php if ($_smarty_tpl->tpl_vars['step']->value==4) {?>
                    
                    <span class="addressCon">
                      <button type="button" data-gid=<?php echo $_smarty_tpl->tpl_vars['item']->value['gid'];?>
 class="showAddressBtn btn btn-info" data-html="true" data-toggle="popover" data-placement="bottom">地址</button>
                    </span>
                    <?php }?>
                </p>
            </div>
            <div style="height: 30px; border-top: solid 1px #ccc; padding-bottom:4px;" class="col-md-12">
                <p>特别说明：<?php echo $_smarty_tpl->tpl_vars['item']->value['comments'];?>
</p>
            </div>
        </div>
    </div>
    <?php } ?>
</div>
<?php }} ?>
