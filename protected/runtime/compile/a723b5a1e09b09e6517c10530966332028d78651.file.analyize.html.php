<?php /* Smarty version Smarty-3.1.18, created on 2015-08-19 10:36:59
         compiled from "/home/work/websites/tuan/protected/views/suprise/analyize.html" */ ?>
<?php /*%%SmartyHeaderCode:185627509355d3ebcb8745b4-82933181%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'a723b5a1e09b09e6517c10530966332028d78651' => 
    array (
      0 => '/home/work/websites/tuan/protected/views/suprise/analyize.html',
      1 => 1439522157,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '185627509355d3ebcb8745b4-82933181',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'date' => 0,
    'data' => 0,
    'online' => 0,
    'campaign' => 0,
    'naocan' => 0,
    'online_diff' => 0,
    'campaign_diff' => 0,
    'naocan_diff' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.18',
  'unifunc' => 'content_55d3ebcb8c0980_89303168',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_55d3ebcb8c0980_89303168')) {function content_55d3ebcb8c0980_89303168($_smarty_tpl) {?><?php echo $_smarty_tpl->getSubTemplate ("layouts/header.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, null, array(), 0);?>

<style type="text/css">
.form-group {
    margin-bottom: 0px !important;
}
</style>
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <ol class="breadcrumb">
                <li><a href="/">Home</a></li>
                <li><a href="/">惊喜秒杀</a></li>
                <li class="active">秒杀统计</li>
            </ol>
            <div id="well" class="well pinned">
                <form class="form-inline" role="form" id="form">
                    <div class="form-group">
                        <label>日期：</label>
                        <input class="picker form-control" id="date" name="date"  type="text" value="<?php echo $_smarty_tpl->tpl_vars['date']->value;?>
"  data-date-format="yyyy-mm-dd">
                    </div>

                    <button id="submit" class="btn btn-default">查看</button>
                </form>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <h4>实际上线<?php echo count($_smarty_tpl->tpl_vars['data']->value[0]);?>
个商品</h4>
            <textarea class="form-control" rows="8"><?php echo $_smarty_tpl->tpl_vars['online']->value;?>
</textarea>
            <h4 style="margin-top:20px;">互斥表目前剩余<?php echo count($_smarty_tpl->tpl_vars['data']->value[1]);?>
个商品</h4>
            <textarea class="form-control" rows="8" ><?php echo $_smarty_tpl->tpl_vars['campaign']->value;?>
</textarea>
            <h4 style="margin-top:20px;">脑残商品不要排这<?php echo count($_smarty_tpl->tpl_vars['data']->value[2]);?>
个商品</h4>
            <textarea class="form-control" rows="8" ><?php echo $_smarty_tpl->tpl_vars['naocan']->value;?>
</textarea>
        </div>
    </div>

    <div class="row" style="margin-top:20px;margin-bottom:120px;">
        <div class="col-md-12">
            <h4 style="color:red">分析结论：<?php if (count($_smarty_tpl->tpl_vars['data']->value[0])==count($_smarty_tpl->tpl_vars['data']->value[1])) {?>实际上线与互斥表商品数据一致<?php } else { ?>实际上线与互斥表商品数据不一致, 请根据下面提示仔细审查<?php }?></h4>
            <p>出现在线上, 但没有真实写入互斥表中的商品: <?php echo implode(', ',$_smarty_tpl->tpl_vars['online_diff']->value);?>
</p>
            <p>排期成功, 但不会出现在线上的商品, 可能是退回失败的: <?php echo implode(', ',$_smarty_tpl->tpl_vars['campaign_diff']->value);?>
</p>
            <h4 style="color:red">退回这几个脑残品: <?php echo implode(', ',$_smarty_tpl->tpl_vars['naocan_diff']->value);?>
</h4>
        </div>
    </div>
</div>
<script type="text/javascript">
$('.picker').datepicker({
format: "yyyy-mm-dd",
autoclose: true
}).on('changeDate', function(ev){
$(this).datepicker('hide');
});
</script><?php }} ?>
