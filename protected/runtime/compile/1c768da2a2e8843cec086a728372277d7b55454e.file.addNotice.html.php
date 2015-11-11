<?php /* Smarty version Smarty-3.1.18, created on 2015-10-14 16:47:03
         compiled from "/home/work/websites/tuan/protected/views/event/addNotice.html" */ ?>
<?php /*%%SmartyHeaderCode:77172063561e1687e91145-90485607%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '1c768da2a2e8843cec086a728372277d7b55454e' => 
    array (
      0 => '/home/work/websites/tuan/protected/views/event/addNotice.html',
      1 => 1439522157,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '77172063561e1687e91145-90485607',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'eventInfo' => 0,
    'noticeInfo' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.18',
  'unifunc' => 'content_561e168802a7d6_73727757',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_561e168802a7d6_73727757')) {function content_561e168802a7d6_73727757($_smarty_tpl) {?><?php if (!is_callable('smarty_function_html_options')) include '/home/work/framework/extensions/Smarty/plugins/function.html_options.php';
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
          <li><a href="/event">主题活动</a></li>
          <li class="active"><?php echo $_smarty_tpl->tpl_vars['eventInfo']->value['event_name'];?>
</li>
          <li class="active"><?php if ($_smarty_tpl->tpl_vars['noticeInfo']->value) {?>编辑公告<?php } else { ?>添加公告<?php }?></li>
      </ol>
    </div>
  </div>

  <div class="row">
    <div class="col-md-12">
      <form id="createForm" class="form-inline" enctype="multipart/form-data"  action="/event/saveNotice" method="post">
        
        <input type="hidden" name="id" value="<?php echo $_smarty_tpl->tpl_vars['noticeInfo']->value['id'];?>
">
        <input type="hidden" name="event_id" value="<?php echo $_smarty_tpl->tpl_vars['eventInfo']->value['event_id'];?>
">
        
        <!-- 展示控制 -->
        <div class="control-group">
          <blockquote><p>展示控制<small>必选</small></p></blockquote>
            <select name="status" id="status" class="form-control input-medium">
              <?php echo smarty_function_html_options(array('options'=>EventManager::$noticeStatusEnum,'selected'=>((string)$_smarty_tpl->tpl_vars['noticeInfo']->value['status'])),$_smarty_tpl);?>

            </select>
          <span class="help-inline"></span>
        </div>
        
        <!-- 标题 -->
        <div class="control-group">
          <blockquote><p>标题<small>必填</small></p></blockquote>
          <input autocomplete ="off" class="require input-xxlarge form-control" type="text" name="title"  id="title" value="<?php echo $_smarty_tpl->tpl_vars['noticeInfo']->value['title'];?>
">
          <span class="help-inline"></span>
        </div>
        
        <!-- 作者 -->
        <div class="control-group">
          <blockquote><p>作者<small>必填</small></p></blockquote>
          <input autocomplete ="off" class="require input-medium form-control" type="text" name="author"  id="author" value="<?php echo $_smarty_tpl->tpl_vars['noticeInfo']->value['author'];?>
">
          <span class="help-inline"></span>
        </div>
        
        <!-- 时间  -->
        <div class="control-group">
          <blockquote><p>时间<small>必填</small></p></blockquote>
          <input class="require input-medium form-control date datepicker" type="text" name="ctime"  id="ctime" value="<?php if ($_smarty_tpl->tpl_vars['noticeInfo']->value['ctime']&&$_smarty_tpl->tpl_vars['noticeInfo']->value['ctime']!='00-00-00 00:00:00') {?><?php echo date('Y-m-d',strtotime($_smarty_tpl->tpl_vars['noticeInfo']->value['ctime']));?>
<?php }?>" data-date-format="yyyy-mm-dd">
          <span class="help-inline"></span>
        </div>
        
        <!-- 分类 -->
        <div class="control-group">
          <blockquote><p>分类</p></blockquote>
            <select name="cate_id" id="cate_id" class="form-control input-medium">
              <?php echo smarty_function_html_options(array('options'=>EventManager::$noticeCateIdEnum,'selected'=>((string)$_smarty_tpl->tpl_vars['noticeInfo']->value['cate_id'])),$_smarty_tpl);?>

            </select>
          <span class="help-inline"></span>
        </div>
        
        <!-- 内容 -->
        <div class="control-group">
          <blockquote><p>内容</p><small>必填</small></blockquote>
          <textarea class="require" name="content" id="postEdit"><?php echo $_smarty_tpl->tpl_vars['noticeInfo']->value['content'];?>
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
