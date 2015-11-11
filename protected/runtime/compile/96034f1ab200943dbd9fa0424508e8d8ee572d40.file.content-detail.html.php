<?php /* Smarty version Smarty-3.1.18, created on 2015-09-21 19:03:23
         compiled from "/home/work/websites/tuan/protected/views/eventGoods/content-detail.html" */ ?>
<?php /*%%SmartyHeaderCode:51145618055d42d567d3c78-47300762%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '96034f1ab200943dbd9fa0424508e8d8ee572d40' => 
    array (
      0 => '/home/work/websites/tuan/protected/views/eventGoods/content-detail.html',
      1 => 1442830795,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '51145618055d42d567d3c78-47300762',
  'function' => 
  array (
  ),
  'version' => 'Smarty-3.1.18',
  'unifunc' => 'content_55d42d5685c8e3_02210337',
  'variables' => 
  array (
    'data' => 0,
    'item' => 0,
    'CheckTips' => 0,
    'v' => 0,
    'schedule_start_time' => 0,
    'eventInfo' => 0,
    'schedule_end_time' => 0,
    'audit_status' => 0,
  ),
  'has_nocache_code' => false,
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_55d42d5685c8e3_02210337')) {function content_55d42d5685c8e3_02210337($_smarty_tpl) {?><style type="text/css">
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
                <span class="level pull-right" style="color: #f69;">&nbsp;&nbsp;<?php if ($_smarty_tpl->tpl_vars['item']->value['level']) {?><?php echo $_smarty_tpl->tpl_vars['item']->value['level'];?>
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
                    报名时间：<?php echo current(explode(' ',$_smarty_tpl->tpl_vars['item']->value['createTime']));?>

                </p>
                <p <?php if ($_smarty_tpl->tpl_vars['item']->value['start_time']) {?>style="width:200px;margin-left: -20px;"<?php }?>>
                  <span style="color: red;">排期开始：</span><span class="recommendDateCon"><?php if ($_smarty_tpl->tpl_vars['item']->value['start_time']) {?><?php echo date("Y-m-d H:i",$_smarty_tpl->tpl_vars['item']->value['start_time']);?>
<?php }?></span>
                </p>
                <p <?php if ($_smarty_tpl->tpl_vars['item']->value['start_time']) {?>style="width:200px;margin-left: -20px;"<?php }?>>
                    <span style="color: red;">排期结束：</span><span class="recommendDateCon"><?php if ($_smarty_tpl->tpl_vars['item']->value['end_time']) {?><?php echo date("Y-m-d H:i",$_smarty_tpl->tpl_vars['item']->value['end_time']);?>
<?php }?></span>
                </p>
                
            </div>
            <div style="height: 30px; padding-top:5px;border-top: solid 1px #ccc; padding-bottom:4px;" class="col-md-12">
              <?php if ($_smarty_tpl->tpl_vars['item']->value['audit_status']==40) {?>
                <input class="myDatePickerHMS form-control" name="start_time"  type="text" value="<?php if ($_smarty_tpl->tpl_vars['item']->value['start_time']) {?><?php echo date("Y-m-d H:i:s",$_smarty_tpl->tpl_vars['item']->value['start_time']);?>
<?php } elseif ($_smarty_tpl->tpl_vars['schedule_start_time']->value) {?><?php echo $_smarty_tpl->tpl_vars['schedule_start_time']->value;?>
<?php } elseif ($_smarty_tpl->tpl_vars['eventInfo']->value['start_time']) {?><?php echo date("Y-m-d H:i:s",$_smarty_tpl->tpl_vars['eventInfo']->value['start_time']);?>
<?php }?>" style="display:inline-block;width:160px;"> - 
                <input class="myDatePickerHMS form-control" name="end_time"  type="text" value="<?php if ($_smarty_tpl->tpl_vars['item']->value['start_time']) {?><?php echo date("Y-m-d H:i:s",$_smarty_tpl->tpl_vars['item']->value['end_time']);?>
<?php } elseif ($_smarty_tpl->tpl_vars['schedule_end_time']->value) {?><?php echo $_smarty_tpl->tpl_vars['schedule_end_time']->value;?>
<?php } elseif ($_smarty_tpl->tpl_vars['eventInfo']->value['end_time']) {?><?php echo date("Y-m-d H:i:s",$_smarty_tpl->tpl_vars['eventInfo']->value['end_time']);?>
<?php }?>" style="display:inline-block;margin-right:10px;width:160px;">
                <?php if ($_smarty_tpl->tpl_vars['eventInfo']->value['status']>=30&&$_smarty_tpl->tpl_vars['eventInfo']->value['status']<40) {?><input class="form-control input-mini" name="repertory"  type="text" value="" placeholder="库存" style="display:inline-block;margin-right:10px;"><?php }?>
                <button data-gid=<?php echo $_smarty_tpl->tpl_vars['item']->value['gid'];?>
 role="button" class="select-box btn btn-danger btnSaveScheduleOne">确认排期</button>
              <?php } elseif ($_smarty_tpl->tpl_vars['audit_status']->value==50) {?>
                <button data-gid=<?php echo $_smarty_tpl->tpl_vars['item']->value['gid'];?>
 role="button" class="select-box btn btn-danger btnCancelScheduleOne">取消排期</button>
              <?php } else { ?>
                <p>特别说明：<?php echo $_smarty_tpl->tpl_vars['item']->value['comments'];?>
</p>
              <?php }?>
            </div>
        </div>
    </div>
    <?php } ?>
</div><?php }} ?>
