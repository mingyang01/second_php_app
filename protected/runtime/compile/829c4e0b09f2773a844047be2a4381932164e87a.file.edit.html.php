<?php /* Smarty version Smarty-3.1.18, created on 2015-10-28 11:41:20
         compiled from "/home/work/websites/tuan/protected/views/goods/edit.html" */ ?>
<?php /*%%SmartyHeaderCode:174346494855ffe3f3959e97-98087397%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '829c4e0b09f2773a844047be2a4381932164e87a' => 
    array (
      0 => '/home/work/websites/tuan/protected/views/goods/edit.html',
      1 => 1444794404,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '174346494855ffe3f3959e97-98087397',
  'function' => 
  array (
  ),
  'version' => 'Smarty-3.1.18',
  'unifunc' => 'content_55ffe3f39d4dc5_68356511',
  'variables' => 
  array (
    'tuanInfo' => 0,
    'imageSize' => 0,
  ),
  'has_nocache_code' => false,
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_55ffe3f39d4dc5_68356511')) {function content_55ffe3f39d4dc5_68356511($_smarty_tpl) {?><?php echo $_smarty_tpl->getSubTemplate ("layouts/header.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, null, array(), 0);?>

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
          <li class="active">编辑商品</li>
          <li class="active"><?php echo $_smarty_tpl->tpl_vars['tuanInfo']->value['goods_name'];?>
</li>
      </ol>
    </div>
  </div>

  <div class="row">
    <div class="col-md-12">
      <form id="createForm" class="form-inline" enctype="multipart/form-data"  action="/goods/saveGoods" method="post">
        
        <input type="hidden" name="id" value="<?php echo $_smarty_tpl->tpl_vars['tuanInfo']->value['id'];?>
">
        
        <!-- 标题 -->
        <div class="control-group">
          <blockquote><p>标题<small>必填</small></p></blockquote>
          <input autocomplete ="off" class="require input-xxlarge form-control" type="text" name="goods_name"  id="goods_name" value="<?php echo $_smarty_tpl->tpl_vars['tuanInfo']->value['goods_name'];?>
">
          <span class="help-inline"></span>
        </div>
        
        <!-- 标题 -->
        <div class="control-group">
          <blockquote><p>卖点信息<small>选填</small></p></blockquote>
          <input autocomplete ="off" class="require input-xxlarge form-control" type="text" name="sale_point"  id="goods_name" value="<?php echo $_smarty_tpl->tpl_vars['tuanInfo']->value['sale_point'];?>
">
          <span class="help-inline"></span>
        </div>
        
        <!-- 颜色 -->
        <div class="control-group">
          <blockquote><p>商品颜色<small>选填 - (如果需要正确填写颜色 例如：#FFFFFF， 如果不需要不用管)</small></p></blockquote>
          <input autocomplete ="off" class="require input-medium form-control" type="text" name="goods_color"  id="goods_color" value="<?php echo $_smarty_tpl->tpl_vars['tuanInfo']->value['goods_color'];?>
">
          <span class="help-inline"></span>
        </div>
        
        <div class="control-group">
          <blockquote><p>图片<small>当前该图大小为：<?php echo $_smarty_tpl->tpl_vars['imageSize']->value[0];?>
*<?php echo $_smarty_tpl->tpl_vars['imageSize']->value[1];?>
</small></p></blockquote>
          <div class="imgbackrgound imgCon" style="height: 290px;">
            <img src="<?php echo getImageUrl($_smarty_tpl->tpl_vars['tuanInfo']->value['goods_image_pc']);?>
" style="height:290px;">
            <input type="hidden" name="goods_image" value="<?php echo $_smarty_tpl->tpl_vars['tuanInfo']->value['goods_image'];?>
">
          </div>
          <span class="btn btn-default upload-img btnUploadImg">
            <input type="file" class="uploadImgInput" name="uploaod_img" id="goods_img_uploaod" onchange="javascript:bannerUpload('goods_img_uploaod', 'goods_image', $(this).parent().prev())">上传
          </span>
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
    
    if (!$.trim($('#createForm input[name="goods_name"]').val())) {
      showError($('#createForm input[name="goods_name"]'), '请填写商品名称！');
      return false;
    }
    
    if (!$.trim($('#createForm input[name="goods_name"]').val())) {
      showError($('#createForm input[name="goods_name"]'), '请上传图片！');
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
