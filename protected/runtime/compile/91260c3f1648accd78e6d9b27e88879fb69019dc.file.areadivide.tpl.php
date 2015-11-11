<?php /* Smarty version Smarty-3.1.18, created on 2015-11-10 12:43:35
         compiled from "/home/work/websites/tuan/protected/views/activity/areadivide.tpl" */ ?>
<?php /*%%SmartyHeaderCode:31708713855ebb2251d6ff6-79611842%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '91260c3f1648accd78e6d9b27e88879fb69019dc' => 
    array (
      0 => '/home/work/websites/tuan/protected/views/activity/areadivide.tpl',
      1 => 1446710488,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '31708713855ebb2251d6ff6-79611842',
  'function' => 
  array (
  ),
  'version' => 'Smarty-3.1.18',
  'unifunc' => 'content_55ebb225206ac5_79237763',
  'variables' => 
  array (
    'activityInfo' => 0,
    'tab' => 0,
    'item' => 0,
    'area' => 0,
    'time' => 0,
    'goodsIds' => 0,
    'event_id' => 0,
  ),
  'has_nocache_code' => false,
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_55ebb225206ac5_79237763')) {function content_55ebb225206ac5_79237763($_smarty_tpl) {?><?php echo $_smarty_tpl->getSubTemplate ("layouts/header.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, null, array(), 0);?>

<script type="text/javascript" src="/assets/lib/bootstrap-datepicker.js"></script>
<style type="text/css" src="/assets/css/datepicker.css"></style>
<script src="/assets/lib/My97DatePicker/WdatePicker.js"></script>
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <ol class="breadcrumb">
                <li><a href="/">Home</a></li>
                <li><a href="active">商品区域划分</a></li>
            </ol>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="well">
                <div class="form-inline">
                    <div class="form-group">
                        <label for="">活动名称</label>
                        <input type="text" disabled style="width:150px;" class="form-control" value="<?php echo $_smarty_tpl->tpl_vars['activityInfo']->value['event_name'];?>
">
                    </div>
                    <div class="form-group">
                        <label for="">活动ID</label>
                        <input type="text" disabled style="width:100px;" class="form-control" value="<?php echo $_smarty_tpl->tpl_vars['activityInfo']->value['event_id'];?>
">
                    </div>
                    <div class="form-group">
                        <label for="">区域</label>
                        <select name="" id="area" class="form-control">
                            <?php if ($_smarty_tpl->tpl_vars['tab']->value) {?>
                                <?php  $_smarty_tpl->tpl_vars['item'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['item']->_loop = false;
 $_smarty_tpl->tpl_vars['key'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['tab']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['item']->key => $_smarty_tpl->tpl_vars['item']->value) {
$_smarty_tpl->tpl_vars['item']->_loop = true;
 $_smarty_tpl->tpl_vars['key']->value = $_smarty_tpl->tpl_vars['item']->key;
?>
                                    <?php if ($_smarty_tpl->tpl_vars['item']->value) {?>
                                    <option <?php if ($_smarty_tpl->tpl_vars['item']->value['id']==$_smarty_tpl->tpl_vars['area']->value) {?> selected <?php }?> value="<?php echo $_smarty_tpl->tpl_vars['item']->value['id'];?>
"><?php echo $_smarty_tpl->tpl_vars['item']->value['name'];?>
</option>
                                    <?php }?>
                                <?php } ?>
                            <?php }?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="">是否为长期活动</label>
                        <select name="" id="activity-type" class="form-control">
                            <option <?php if ($_smarty_tpl->tpl_vars['time']->value) {?> selected <?php }?>value="1">是</option>
                            <option <?php if (!$_smarty_tpl->tpl_vars['time']->value) {?> selected <?php }?> value="0">否</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="">开始时间</label>
                        <input class="picker form-control time-box"  style="width:120px;" id="start_time" name="to"  type="text" <?php if ($_smarty_tpl->tpl_vars['time']->value) {?>value="<?php echo $_smarty_tpl->tpl_vars['time']->value;?>
"<?php } else { ?>disabled<?php }?>  date-format="yyyy-mm-dd">
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="alert alert-danger">
                该区域中的商品如下，将要修改区域商品的推id写在相应区域中即可，推id用英文的逗号隔开，并保存！
            </div>
        </div>
        <div class="col-md-12">
            <div class="well">
                <textarea name="" id="goodsIds" cols="30" rows="10" class="form-control"><?php echo $_smarty_tpl->tpl_vars['goodsIds']->value;?>
</textarea>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <button class="btn btn-success btn-block save-change-area">保存</button>
        </div>
    </div>
</div>
<script>
var event_id = <?php echo $_smarty_tpl->tpl_vars['event_id']->value;?>
;
var goodsIds = '<?php echo $_smarty_tpl->tpl_vars['goodsIds']->value;?>
';
var flag = '0';
//日期插件
$('.picker').datepicker({
    format: "yyyy-mm-dd",
    autoclose: true
}).on('changeDate', function(ev){
    $(this).datepicker('hide');
    start_time = $("#start_time").val();
    var area = $("#area").val();
    window.location="/activity/areaDivide?event_id="+event_id+"&area="+area+"&start_time="+start_time;
    
});
$('#area').change(function(){
    var area = $("#area").val();
    var start_time = $("#start_time").val();
    window.location="/activity/areaDivide?event_id="+event_id+"&area="+area+"&start_time="+start_time;
})
$('.save-change-area').click(function(){
    var changeGoods = $('#goodsIds').val();
    var area = $("#area").val();
    if(changeGoods==goodsIds){
        alert("没有新增的商品")
    }
    var params = {'goodsIds':goodsIds,'changeGoods':changeGoods,'event_id':event_id,'area':area}
    $.post('/activity/saveDivideStatus',params, function(data) {
        window.location.reload();
    });
})
$('#activity-type').change(function() {
    flag = $(this).val();
    var area = $("#area").val();
    var start_time = '';
    if(flag=='0'){
        $("#start_time").attr('disabled', 'disabled');
        window.location="/activity/areaDivide?event_id="+event_id+"&area="+area+"&start_time="+start_time;
        flag='0'
    }else{
        $("#start_time").removeAttr('disabled');
        flag='1'
    }
});
</script><?php }} ?>
