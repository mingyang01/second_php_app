<?php /* Smarty version Smarty-3.1.18, created on 2015-10-15 18:57:10
         compiled from "/home/work/websites/tuan/protected/views/online/online-content-detail.html" */ ?>
<?php /*%%SmartyHeaderCode:191950402855d55a34548027-07226056%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'c4d73a3febcee12ba001448e6a8a3b95fd8dd12f' => 
    array (
      0 => '/home/work/websites/tuan/protected/views/online/online-content-detail.html',
      1 => 1444904004,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '191950402855d55a34548027-07226056',
  'function' => 
  array (
  ),
  'version' => 'Smarty-3.1.18',
  'unifunc' => 'content_55d55a345cf6a2_25340394',
  'variables' => 
  array (
    'data' => 0,
    'item' => 0,
    'CheckTips' => 0,
    'v' => 0,
    'needTool' => 0,
    'recommendDate' => 0,
  ),
  'has_nocache_code' => false,
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_55d55a345cf6a2_25340394')) {function content_55d55a345cf6a2_25340394($_smarty_tpl) {?><style type="text/css">
.img {
height: 200px;
}

.level {
    color: #f69;
    font-size: 18px;
}

.tool {
    position: relative;
    top: 20px;
    left: 40px;
}

.btn-danger {
    background-color: #f46;
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
        <div data-shop=<?php echo $_smarty_tpl->tpl_vars['item']->value['shop'];?>
 data-gid=<?php echo $_smarty_tpl->tpl_vars['item']->value['gid'];?>
 class="thumbnail" style="height: 350px;">
            <div style="line-height:24px;height: 30px; border-bottom: solid 1px #ccc; margin-bottom:4px;" class="col-md-12">
                <strong style="font-size:18px;"><?php $_smarty_tpl->tpl_vars['CheckTips'] = new Smarty_variable(CheckTipsManager::$tipsTypeEnum, null, 0);?>
                <?php echo $_smarty_tpl->tpl_vars['CheckTips']->value[$_smarty_tpl->tpl_vars['item']->value['audit_status']];?>
 <small><?php echo $_smarty_tpl->tpl_vars['item']->value['gid'];?>
</small></strong>
                <?php if ($_smarty_tpl->tpl_vars['v']->value['is_danger']||$_smarty_tpl->tpl_vars['v']->value['is_bad']) {?><strong style="color:red;">高危商品</strong><?php }?>
                <span class="level pull-right" style="color: #f69;"><?php if ($_smarty_tpl->tpl_vars['item']->value['isshow']==1) {?>普通<?php }?><?php if ($_smarty_tpl->tpl_vars['item']->value['isshow']==0) {?>精品<?php }?>&nbsp;&nbsp;<?php if ($_smarty_tpl->tpl_vars['item']->value['level']) {?><?php echo $_smarty_tpl->tpl_vars['item']->value['level'];?>
<?php }?></span>
            </div>
            <div style="line-height:24px;height: 30px; border-bottom: solid 1px #ccc; margin-bottom:4px;" class="col-md-12">
                团购历史：<?php echo html_entity_decode($_smarty_tpl->tpl_vars['item']->value['tuan_history']);?>

            </div>
            <div class="col-md-4" >
                <div class="img" style="position: relative;">
                    <a target="_blank" href="http://www.meilishuo.com/share/item/<?php echo $_smarty_tpl->tpl_vars['item']->value['tid'];?>
">
                    <img data-src="holder.js/100%x200" alt="100%x200" src="<?php echo Yii::app()->image->getWebsiteImageUrl($_smarty_tpl->tpl_vars['item']->value['img']);?>
" data-holder-rendered="true" style="height: 100%; width: 100%; display: block;"></a>
                    
                    <?php if ($_smarty_tpl->tpl_vars['item']->value['op_type']!=0&&array_key_exists($_smarty_tpl->tpl_vars['item']->value['op_type'],OnlineManager::$tuangouTagMap)) {?>
                      <div style="position: absolute;top: 0px;left: 0px;"><img src="/assets/images/tuangou_<?php echo $_smarty_tpl->tpl_vars['item']->value['op_type'];?>
.png" style="width:40px;"></div>
                    <?php }?>
                </div>
            </div>
            <div class="col-md-4">
                <h3 style="margin-top:0px;height:35px" id="thumbnail-label"><?php echo $_smarty_tpl->tpl_vars['item']->value['name'];?>
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
                <p>
                    <span style="color: red;">推荐排期：</span><span class="recommendDateCon"><?php if ($_smarty_tpl->tpl_vars['item']->value['start_time']) {?><?php echo date("Y-m-d",$_smarty_tpl->tpl_vars['item']->value['start_time']);?>
<?php }?></span>
                </p>
                <p>
                    报名时间：<?php echo current(explode(' ',$_smarty_tpl->tpl_vars['item']->value['createTime']));?>

                </p>
                <p class="tool" style="width: 170px;left:0;">
                  <?php if ($_smarty_tpl->tpl_vars['needTool']->value==1&&!$_smarty_tpl->tpl_vars['recommendDate']->value) {?>
                    <?php if ($_smarty_tpl->tpl_vars['item']->value['start_time']) {?><button data-gid=<?php echo $_smarty_tpl->tpl_vars['item']->value['gid'];?>
 role="button" class="select-box btn btn-warning btnCancelDataOne">退回</button><?php }?>
                  <?php }?>
                </p>
            </div>
            <div style="height: 30px; padding-top:5px; border-top: solid 1px #ccc; padding-bottom:4px;" class="col-md-12">
              <?php if ($_smarty_tpl->tpl_vars['needTool']->value==1&&$_smarty_tpl->tpl_vars['recommendDate']->value==1) {?>
                <input class="myDatePicker form-control input-medium" name="start_time"  type="text" value="<?php if ($_smarty_tpl->tpl_vars['item']->value['start_time']) {?><?php echo date("Y-m-d",$_smarty_tpl->tpl_vars['item']->value['start_time']);?>
<?php }?>" style="display:inline-block;margin-right:10px;"><button data-gid=<?php echo $_smarty_tpl->tpl_vars['item']->value['gid'];?>
 role="button" class="select-box btn <?php if ($_smarty_tpl->tpl_vars['item']->value['start_time']) {?>btn-success<?php } else { ?>btn-danger<?php }?> btnSaveRecommendDataOne"><?php if ($_smarty_tpl->tpl_vars['item']->value['start_time']) {?>重新推荐<?php } else { ?>推荐排期<?php }?></button>
              <?php } elseif ($_smarty_tpl->tpl_vars['needTool']->value==1) {?>
                <input style="display:none;" class="myDatePicker form-control input-medium" name="start_time"  type="text" value="<?php if ($_smarty_tpl->tpl_vars['item']->value['start_time']) {?><?php echo date("Y-m-d",$_smarty_tpl->tpl_vars['item']->value['start_time']);?>
<?php }?>" style="display:inline-block;margin-right:10px;"><button data-gid=<?php echo $_smarty_tpl->tpl_vars['item']->value['gid'];?>
 role="button" class="select-box btn btn-danger btnSaveDataOne">确认排期</button>
              <?php } elseif (!$_smarty_tpl->tpl_vars['needTool']->value&&!$_smarty_tpl->tpl_vars['recommendDate']->value) {?>
                
                <button data-gid=<?php echo $_smarty_tpl->tpl_vars['item']->value['gid'];?>
 role="button" class="select-box btn btn-warning btnCancelScheduleOne">取消排期</button>
              <?php }?>
            </div>
        </div>
    </div>
    <?php } ?>
</div><?php }} ?>
