<?php /* Smarty version Smarty-3.1.18, created on 2015-08-26 12:03:36
         compiled from "/home/work/websites/tuan/protected/views/event/editBasicInfo.html" */ ?>
<?php /*%%SmartyHeaderCode:92747365455cdc66f64d761-13660505%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '3b6db51b3334d413aba24fca4398c033fa1e7dcf' => 
    array (
      0 => '/home/work/websites/tuan/protected/views/event/editBasicInfo.html',
      1 => 1440560805,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '92747365455cdc66f64d761-13660505',
  'function' => 
  array (
  ),
  'version' => 'Smarty-3.1.18',
  'unifunc' => 'content_55cdc66f744fd6_27994788',
  'variables' => 
  array (
    'eventInfo' => 0,
  ),
  'has_nocache_code' => false,
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_55cdc66f744fd6_27994788')) {function content_55cdc66f744fd6_27994788($_smarty_tpl) {?><?php echo $_smarty_tpl->getSubTemplate ("layouts/header.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, null, array(), 0);?>

<title>编辑活动基本信息</title>
<script src="/assets/lib/My97DatePicker/WdatePicker.js"></script>
<style>
.container{
  /*font-size:12px;*/
}
</style>
<style>
.goods-photos {
  float:left;
}
.goods-photos li {
  width:107px;
  height:125px;
  border: 1px solid #ccc;
  padding:1px;
}

.goods-photos img {
  max-width:none;
}

.sku-error-label {
  margin-left: 5px;
  margin-top: 4px;
  color:#b94a48;
}

</style>  
<div class="container">
  <div class="row">
    <div class="col-md-12">
      <ol class="breadcrumb">
          <li><a href="/">Home</a></li>
          <li><a href="/event">活动</a></li>
          <li class="active"><?php echo $_smarty_tpl->tpl_vars['eventInfo']->value['event_name'];?>
</li>
          <li class="active">编辑活动</li>
      </ol>
    </div>
  </div>

  <div class="row">
    <div class="col-md-12">
      <form id="createForm" class="form-inline" enctype="multipart/form-data"  action="/event/saveBasicInfo" method="post">
        <p>所属频道：<span style="color:red;"><?php echo EventManager::$channelMap[$_smarty_tpl->tpl_vars['eventInfo']->value['channel']];?>
</span> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 活动类型：<span style="color:red;"><?php echo EventManager::$evnetTypeEnum[$_smarty_tpl->tpl_vars['eventInfo']->value['status']];?>
</span></p>
        <p>活动时间：<?php echo date("Y-m-d H:i",$_smarty_tpl->tpl_vars['eventInfo']->value['start_time']);?>
 - <?php echo date("Y-m-d H:i",$_smarty_tpl->tpl_vars['eventInfo']->value['end_time']);?>
 &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;预热时间：<?php if ($_smarty_tpl->tpl_vars['eventInfo']->value['preheat_time']) {?><?php echo date("Y-m-d H:i",$_smarty_tpl->tpl_vars['eventInfo']->value['preheat_time']);?>
<?php }?></p>
        <input type="hidden" name="event_id" value="<?php echo $_smarty_tpl->tpl_vars['eventInfo']->value['event_id'];?>
">
        
        <!-- 活动名称 -->
        <div class="control-group">
          <blockquote><p>活动名称<small>必选</small></p></blockquote>
          <input type="text" class="require input-xlarge form-control" name="event_name" id="event_name" value="<?php echo $_smarty_tpl->tpl_vars['eventInfo']->value['event_name'];?>
">
          <span class="help-inline"></span>
        </div>
        
        <!-- 活动标题 -->
        <div class="control-group">
          <blockquote><p>活动标题<small>选填</small></p></blockquote>
          <input type="text" class="require input-xlarge form-control" name="title" id="title" value="<?php echo $_smarty_tpl->tpl_vars['eventInfo']->value['title'];?>
">
          <span class="help-inline"></span>
        </div>
        <!-- 时间  -->
        <div class="control-group">
          <blockquote><p>报名时间<small>可选</small></p></blockquote>
          <input type="text" class="require input-medium form-control myDatePicker" name="join_start_time" id="join_start_time" value="<?php if ($_smarty_tpl->tpl_vars['eventInfo']->value['join_start_time']&&$_smarty_tpl->tpl_vars['eventInfo']->value['join_start_time']!='0000-00-00 00:00:00') {?><?php echo date('Y-m-d H:i',strtotime($_smarty_tpl->tpl_vars['eventInfo']->value['join_start_time']));?>
<?php }?>"> - 
          <input type="text" class="require input-medium form-control myDatePicker" name="join_end_time" id="join_end_time" value="<?php if ($_smarty_tpl->tpl_vars['eventInfo']->value['join_end_time']&&$_smarty_tpl->tpl_vars['eventInfo']->value['join_end_time']!='0000-00-00 00:00:00') {?><?php echo date('Y-m-d H:i',strtotime($_smarty_tpl->tpl_vars['eventInfo']->value['join_end_time']));?>
<?php }?>">
          <span class="help-inline"></span>
        </div>
        
        <!-- 活动类型 -->
        <div class="control-group">
          <blockquote><p>活动类型<small>必选</small></p></blockquote>
          <select class="require input-medium form-control" name="join_status" id="join_status">
            <option value="1" <?php if ($_smarty_tpl->tpl_vars['eventInfo']->value['join_status']==1) {?>selected<?php }?>>不可报名</option>
            <option value="0" <?php if ($_smarty_tpl->tpl_vars['eventInfo']->value['join_status']==0) {?>selected<?php }?>>可报名</option>
          </select>
          <span class="help-inline"></span>
        </div>
        
        
        <!-- 内容 -->
        <div class="control-group">
          <p>&nbsp;</p>
          <input type="submit" class="btn btn-primary Sub createBtn" value="添加">
        </div>
      </form>
    </div>
  </div>
  

</div>
<script>
$(function(){
  $('.datepicker').datepicker({
      format: "yyyy-mm-dd",
      autoclose: true
  }).on('changeDate', function(ev){
      $(this).datepicker('hide');
  });
  
  /** 选择时间 */
  $('.myDatePicker').on('focus',function(){
    WdatePicker({
        dateFmt:'yyyy-MM-dd HH:mm'
    });
  });
});


$(function(){
  var isEdit = false;
  // 表单提交
  $('#createForm').submit(function(e){
    e.preventDefault();
    
    if ($('.createBtn').hasClass('disabled')) {
      alert('请稍等，正在保存');
      return false;
    }
    
    if (!$.trim($('#createForm input[name="event_name"]').val())) {
      showError($('#createForm input[name="event_name"]'), '请填写活动名称！');
      return false;
    }
    
    $('.createBtn').addClass('disabled');
    $('#createForm').unbind().submit();
  });
});

/**
 * 显示错误信息
 */
function showError(obj, msg)
{
  if (obj.length) {
    obj.siblings('.help-inline').text(msg).closest('.control-group').addClass('error');
    obj.focus();
  }
  return obj;
}
</script>
<?php echo $_smarty_tpl->getSubTemplate ("layouts/footer.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, null, array(), 0);?>
<?php }} ?>
