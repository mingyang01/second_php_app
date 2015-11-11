<?php /* Smarty version Smarty-3.1.18, created on 2015-08-19 10:36:55
         compiled from "/home/work/websites/tuan/protected/views/suprise/edit.html" */ ?>
<?php /*%%SmartyHeaderCode:203947975155d3ebc76f8122-07412598%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'b7fc95065c31c8483de5c2b31e3529803a3ab875' => 
    array (
      0 => '/home/work/websites/tuan/protected/views/suprise/edit.html',
      1 => 1439522157,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '203947975155d3ebc76f8122-07412598',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'eventInfo' => 0,
    'newZhengdianInfo' => 0,
    'v' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.18',
  'unifunc' => 'content_55d3ebc773a498_72408922',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_55d3ebc773a498_72408922')) {function content_55d3ebc773a498_72408922($_smarty_tpl) {?><?php echo $_smarty_tpl->getSubTemplate ("layouts/header.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, null, array(), 0);?>

<script src="/assets/lib/imgAjaxfileUp.js"></script>
<script src="/assets/lib/My97DatePicker/WdatePicker.js"></script>
<script src="/assets/js/suprise/editZhengdianTime.js"></script>
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
          <li class="active">惊喜秒杀</li>
      </ol>
    </div>
  </div>

  <div class="row">
    <div class="col-md-12">
      <form id="createForm" class="form-inline" enctype="multipart/form-data"  action="/suprise/saveZhengdianTime" method="post">
        
        <input type="hidden" name="event_id" value="<?php echo $_smarty_tpl->tpl_vars['eventInfo']->value['event_id'];?>
">
        
        <!-- 时间  -->
        <div class="control-group">
          <blockquote>
            <p>挣点抢时间段<small>必填</small></p>
            <button type="button" class="btn btn-primary btn-sm btnAddZhengdianTimeAll">新增时间(全天) <i class="glyphicon glyphicon-plus"></i></button>
            <button type="button" class="btn btn-primary btn-sm btnAddZhengdianTimeOne">新增时间(单个) <i class="glyphicon glyphicon-plus"></i></button>
          </blockquote>
          <div class="zhengdianTimeCon">
          <?php  $_smarty_tpl->tpl_vars['v'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['v']->_loop = false;
 $_smarty_tpl->tpl_vars['k'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['newZhengdianInfo']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['v']->key => $_smarty_tpl->tpl_vars['v']->value) {
$_smarty_tpl->tpl_vars['v']->_loop = true;
 $_smarty_tpl->tpl_vars['k']->value = $_smarty_tpl->tpl_vars['v']->key;
?>
            <div class="form-group" style="margin:3px 0;">
              <label>说明：</label><input class="require input-medium form-control" type="text" name="stime_note[]"  value="<?php echo $_smarty_tpl->tpl_vars['v']->value['stime_note'];?>
" readonly>
              <label>开始时间：</label><input class="require input-medium form-control" type="text" name="ctime[]"   value="<?php echo $_smarty_tpl->tpl_vars['v']->value['stime'];?>
" readonly>
              <label>结束时间：</label><input class="require input-medium form-control" type="text" name="etime[]"  value="<?php echo $_smarty_tpl->tpl_vars['v']->value['etime'];?>
" readonly>
              <button type="button"  class="btn btn-danger btn-xs btnDeleteTimeOnline">删除</button>
            </div>
          <?php } ?>
          </div>
          <span class="help-inline"></span>
        </div>
        <div class="control-group">
          <blockquote><p>图片</p></blockquote>
          <div class="imgbackrgound imgCon" style="height: 290px;width: 290px;">
            <img src="<?php echo getImageUrl($_smarty_tpl->tpl_vars['eventInfo']->value['detail']['q8_ruler']);?>
" style="height:290px;width:290px;">
            <input type="hidden" name="q8_ruler" value="<?php echo $_smarty_tpl->tpl_vars['eventInfo']->value['detail']['q8_ruler'];?>
">
          </div>
          <span class="btn btn-default upload-img btnUploadImg">
            <input type="file" class="uploadImgInput" name="uploaod_img" id="q8_ruler_uploaod" onchange="javascript:bannerUpload('q8_ruler_uploaod', 'q8_ruler', $(this).parent().prev())">上传
          </span>
          <span class="help-inline"></span>
        </div>
        <!-- 内容 -->
        <div class="control-group">
          <p>&nbsp;</p><p>&nbsp;</p>
          <input type="submit" class="btn btn-primary Sub createBtn" value="添加">
        </div>
      </form>
    </div>
  </div>
  

</div>
<script>
$(function(){
  /** 选择时间 */
  $('.myDatePicker').on('focus',function(){
    WdatePicker({
        dateFmt:'yyyy-MM-dd HH:00'
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

//图片上传方法
function bannerUpload(id, imputImgName,imgObj) {
  $.ajaxFileUpload({
       url:'/goods/uploadImage',
       secureuri:false,
       fileElementId:id,
       dataType:'json',
       data: {'filename':id},
       success:function(data){
           if(data.succ == 1){
               var height = imgObj.css("height");
               var width  = imgObj.css("width");
               var inImg = "<img src="+data.img+" style='height:"+height+";width:"+width+"'>";
               inImg += "<input type='hidden' name='"+imputImgName+"' value='"+data.path+"'>";
               imgObj.html(inImg);
               
           }else{
               alert(data.msg);
           }
       },
       error: function (data, status, e){
          alert(data.responseText);
       }
  });
};
</script>
<?php echo $_smarty_tpl->getSubTemplate ("layouts/footer.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, null, array(), 0);?>
<?php }} ?>
