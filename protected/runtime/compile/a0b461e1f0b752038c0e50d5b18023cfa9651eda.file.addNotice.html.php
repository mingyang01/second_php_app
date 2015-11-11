<?php /* Smarty version Smarty-3.1.18, created on 2015-09-28 12:31:20
         compiled from "/home/work/websites/tuan/protected/views/notice/addNotice.html" */ ?>
<?php /*%%SmartyHeaderCode:1107939305608c2985053a8-58476233%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'a0b461e1f0b752038c0e50d5b18023cfa9651eda' => 
    array (
      0 => '/home/work/websites/tuan/protected/views/notice/addNotice.html',
      1 => 1439522157,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '1107939305608c2985053a8-58476233',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'notice_info' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.18',
  'unifunc' => 'content_5608c2985e0950_14556199',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_5608c2985e0950_14556199')) {function content_5608c2985e0950_14556199($_smarty_tpl) {?><?php if (!is_callable('smarty_function_html_options')) include '/home/work/framework/extensions/Smarty/plugins/function.html_options.php';
?><?php echo $_smarty_tpl->getSubTemplate ("layouts/header.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, null, array(), 0);?>

<script src="/assets/lib/ueditor/editor_all.js"></script>
<script src="/assets/lib/ueditor/editor_config.js"></script>
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
          <li><a href="/notice">公告管理</a></li>
          <li class="active"><?php if ($_smarty_tpl->tpl_vars['notice_info']->value) {?>编辑公告<?php } else { ?>添加公告<?php }?></li>
      </ol>
    </div>
  </div>

  <div class="row">
    <div class="col-md-12">
      <form id="createForm" class="form-inline" enctype="multipart/form-data"  action="/notice/saveNotice" method="post">
        
        <input type="hidden" name="id" value="<?php echo $_smarty_tpl->tpl_vars['notice_info']->value['id'];?>
">
        <!-- 分类 -->
        <div class="control-group">
          <blockquote><p>分类</p></blockquote>
            <select name="cate_id" id="cate_id" class="form-control input-medium">
              <?php echo smarty_function_html_options(array('options'=>NoticeManager::$notice_cate_id_map,'selected'=>((string)$_smarty_tpl->tpl_vars['notice_info']->value['cate_id'])),$_smarty_tpl);?>

            </select>
          <span class="help-inline"></span>
        </div>

        <!-- 标题 -->
        <div class="control-group">
          <blockquote><p>标题<small>必填</small></p></blockquote>
          <input autocomplete ="off" class="require input-xxlarge form-control" type="text" name="title"  id="title" value="<?php echo $_smarty_tpl->tpl_vars['notice_info']->value['title'];?>
">
          <span class="help-inline"></span>
        </div>
        
        <!-- 作者 -->
        <div class="control-group">
          <blockquote><p>作者<small>必填</small></p></blockquote>
          <input autocomplete ="off" class="require input-medium form-control" type="text" name="author"  id="author" value="<?php echo $_smarty_tpl->tpl_vars['notice_info']->value['author'];?>
">
          <span class="help-inline"></span>
        </div>
        
        <!-- 时间  -->
        <div class="control-group">
          <blockquote><p>时间<small>必填</small></p></blockquote>
          <input class="require input-medium form-control date datepicker" type="text" name="ctime"  id="ctime" value="<?php if ($_smarty_tpl->tpl_vars['notice_info']->value['ctime']&&$_smarty_tpl->tpl_vars['notice_info']->value['ctime']!='00-00-00 00:00:00') {?><?php echo date('Y-m-d',strtotime($_smarty_tpl->tpl_vars['notice_info']->value['ctime']));?>
<?php }?>" data-date-format="yyyy-mm-dd">
          <span class="help-inline"></span>
        </div>
        
        <!-- 展示控制 -->
        <div class="control-group">
          <blockquote><p>展示控制<small>必选</small></p></blockquote>
            <select name="status" id="status" class="form-control input-medium">
              <?php echo smarty_function_html_options(array('options'=>NoticeManager::$notice_status_map,'selected'=>((string)$_smarty_tpl->tpl_vars['notice_info']->value['status'])),$_smarty_tpl);?>

            </select>
          <span class="help-inline"></span>
        </div>
        
        <!-- 内容 -->
        <div class="control-group">
          <blockquote><p>内容</p><small>必填</small></blockquote>
          <textarea class="require" name="content" id="postEdit"><?php echo $_smarty_tpl->tpl_vars['notice_info']->value['content'];?>
</textarea>
          <span class="help-inline"></span>
        </div>
        
        <!-- 内容 -->
        <div class="control-group">
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
  
  UE.getEditor('postEdit',{
      autoClearinitialContent:false,
      wordCount:true,
      elementPathEnabled:false,
      initialFrameHeight:300,
      zIndex:0,
      maximumWords: 99999,
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
    
    if (!$('#createForm select[name="status"]').val()) {
      showError($('#createForm select[name="status"]'), '请选择展示分类！');
      return false;
    }
    
    if (!$('#createForm input[name="ctime"]').val()) {
      showError($('#createForm input[name="ctime"]'), '请选时间！');
      return false;
    } 
    
    if (!$.trim($('#createForm select[name="cate_id"]').val()) && !isEdit) {
      showError($('#createForm select[name="cate_id"]'), '请选择分类！');
      return false;
    }
    UE.getEditor('postEdit').sync();
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
