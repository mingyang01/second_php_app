<?php /* Smarty version Smarty-3.1.18, created on 2015-09-21 19:15:48
         compiled from "/home/work/websites/tuan/protected/views/everyDay/editConfig.html" */ ?>
<?php /*%%SmartyHeaderCode:188394804155ffe6e42fa0c4-08165782%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '12f6150235787eb69187cc286889152432c5835e' => 
    array (
      0 => '/home/work/websites/tuan/protected/views/everyDay/editConfig.html',
      1 => 1439522157,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '188394804155ffe6e42fa0c4-08165782',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'keyInfo' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.18',
  'unifunc' => 'content_55ffe6e43bb8a1_26348434',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_55ffe6e43bb8a1_26348434')) {function content_55ffe6e43bb8a1_26348434($_smarty_tpl) {?><?php echo $_smarty_tpl->getSubTemplate ("layouts/header.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, null, array(), 0);?>

<script src="/assets/lib/My97DatePicker/WdatePicker.js"></script>
<script src="/assets/lib/imgAjaxfileUp.js"></script>
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
.upload-img {
    position: relative;
    cursor: pointer;
    overflow: hidden;
    display: inline-block;
    *display: inline;
    *zoom: 1
}

.upload-img  input {
    position: absolute;
    font-size: 100px;
    right: 0;
    top: 0;
    opacity: 0;
    filter: alpha(opacity=0);
    cursor: pointer
}

</style>  
<div class="container">
  <div class="row">
    <div class="col-md-12">
      <ol class="breadcrumb">
          <li><a href="/">Home</a></li>
          <li class="active">编辑配置</li>
          <li class="active"><?php echo $_smarty_tpl->tpl_vars['keyInfo']->value['comment'];?>
</li>
      </ol>
    </div>
  </div>

  <div class="row">
    <div class="col-md-12">
      <p><h3><?php echo MarketingManager::$marketing_key_map[$_smarty_tpl->tpl_vars['keyInfo']->value['key']];?>
</h3></p>
      <p></p>
      <form id="createForm" class="form-inline" enctype="multipart/form-data"  action="/marketing/saveMarketingConfig" method="post">
        
        <input type="hidden" name="key" value="<?php echo $_smarty_tpl->tpl_vars['keyInfo']->value['key'];?>
">
        
        <!-- 标题 -->
        <div class="control-group">
          <blockquote><p>标题<small>必填</small></p></blockquote>
          <input autocomplete ="off" class="require input-xxlarge form-control" type="text" name="title"  id="title" value="<?php echo $_smarty_tpl->tpl_vars['keyInfo']->value['config']['title'];?>
">
          <span class="help-inline"></span>
        </div>
        
        <!-- 说明 -->
        <div class="control-group">
          <blockquote><p>说明<small>选填</small></p></blockquote>
          <input autocomplete ="off" class="require input-xxlarge form-control" type="text" name="desc"  id="desc" value="<?php echo $_smarty_tpl->tpl_vars['keyInfo']->value['config']['desc'];?>
">
          <span class="help-inline"></span>
        </div>


        
        <p></p>
        <!-- 内容 -->
        <div class="control-group">
          <input type="submit" class="btn btn-primary Sub createBtn" value="保存">
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
    
    if (!$.trim($('#createForm input[name="title"]').val())) {
      showError($('#createForm input[name="title"]'), '请填写标题！');
      return false;
    }
    if (!$.trim($('#createForm input[name="desc"]').val())) {
      showError($('#createForm input[name="desc"]'), '请填写说明！');
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
    obj.focus();
  }
  return obj;
}
</script>
<?php echo $_smarty_tpl->getSubTemplate ("layouts/footer.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, null, array(), 0);?>
<?php }} ?>
