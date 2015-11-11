<?php /* Smarty version Smarty-3.1.18, created on 2015-08-20 14:59:22
         compiled from "/home/work/websites/tuan/protected/views/qingcang/checktips/add.html" */ ?>
<?php /*%%SmartyHeaderCode:3272198855d55603969c15-28340112%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'd38fccd9c5653a2c28537f13244b26da43eb0ff5' => 
    array (
      0 => '/home/work/websites/tuan/protected/views/qingcang/checktips/add.html',
      1 => 1440046305,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '3272198855d55603969c15-28340112',
  'function' => 
  array (
  ),
  'version' => 'Smarty-3.1.18',
  'unifunc' => 'content_55d55603a33e69_16474659',
  'variables' => 
  array (
    'tipInfo' => 0,
  ),
  'has_nocache_code' => false,
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_55d55603a33e69_16474659')) {function content_55d55603a33e69_16474659($_smarty_tpl) {?><?php if (!is_callable('smarty_function_html_radios')) include '/home/work/framework/extensions/Smarty/plugins/function.html_radios.php';
?><?php echo $_smarty_tpl->getSubTemplate ("layouts/header.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, null, array(), 0);?>

<div class="container">
  <div class="row">
    <div class="col-md-12">
      <ol class="breadcrumb">
          <li><a href="/">Home</a></li>
          <li><a href="/checkTips">主题活动</a></li>
          <li class="active"><?php if ($_smarty_tpl->tpl_vars['tipInfo']->value) {?>编辑原因<?php } else { ?>添加原因<?php }?></li>
      </ol>
    </div>
  </div>

  <div class="row">
    <div class="col-md-12">
      <form id="createForm" class="form-inline" enctype="multipart/form-data"  action="/qingcang/QcheckTips/save" method="post">
        
        <input type="hidden" name="id" value="<?php echo $_smarty_tpl->tpl_vars['tipInfo']->value['id'];?>
">
        
        <!-- 展示控制 -->
        <div class="control-group">
          <blockquote><p>类型<small>必选</small></p></blockquote>
              <?php echo smarty_function_html_radios(array('name'=>"type",'options'=>QcheckTipsManager::$tipsTypeEnum,'selected'=>((string)$_smarty_tpl->tpl_vars['tipInfo']->value['type']),'separator'=>"&nbsp;&nbsp;"),$_smarty_tpl);?>

          <span class="help-inline"></span>
        </div>
        
        <!-- 内容 -->
        <div class="control-group">
          <blockquote><p>内容<small>必填</small></p></blockquote>
          <textarea class="require input-xxlarge form-control" name="content" id="postEdit"><?php echo $_smarty_tpl->tpl_vars['tipInfo']->value['content'];?>
</textarea>
          <span class="help-inline"></span>
        </div>
        
        <!-- 内容 -->
        <div class="control-group">
          <p></p>
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
    
    if ($('#createForm :radio[name="type"]:checked').length < 1) {
      showError($('#createForm :radio[name="type"]:last'), '请填类型！');
      return false;
    }
    
    if (!$.trim($('#createForm textarea[name="content"]').val())) {
      showError($('#createForm textarea[name="content"]'), '请填写内容！');
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
    var parentObj = obj.closest('.control-group');
    parentObj.addClass('error').find('.help-inline').text(msg)
    //obj.siblings('.help-inline').text(msg).closest('.control-group').addClass('error');
    obj.focus();
  }
  return obj;
}
</script>
<?php echo $_smarty_tpl->getSubTemplate ("layouts/footer.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, null, array(), 0);?>
<?php }} ?>
