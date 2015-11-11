<?php /* Smarty version Smarty-3.1.18, created on 2015-08-18 17:21:43
         compiled from "/home/work/websites/tuan/protected/views/everyDay/qingcang.html" */ ?>
<?php /*%%SmartyHeaderCode:182597696355d2f927889f08-50238417%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'dbed0c6135c334f568116f3b9cf393662eb898ca' => 
    array (
      0 => '/home/work/websites/tuan/protected/views/everyDay/qingcang.html',
      1 => 1439522157,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '182597696355d2f927889f08-50238417',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'date' => 0,
    'dateList' => 0,
    'v' => 0,
    'qingcangList' => 0,
    'k' => 0,
    'tuanInfo' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.18',
  'unifunc' => 'content_55d2f9278f7539_54888339',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_55d2f9278f7539_54888339')) {function content_55d2f9278f7539_54888339($_smarty_tpl) {?><?php echo $_smarty_tpl->getSubTemplate ("layouts/header.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, null, array(), 0);?>

<title>清仓-营销产品</title>
<script src="/assets/lib/My97DatePicker/WdatePicker.js"></script>
<script src="/assets/lib/imgAjaxfileUp.js"></script>
<style>
.rec_sku {
overflow: hidden;
margin: 0 auto;
}

.imgbackrgound {
    /*background: url(http://img.meilishuo.net//css/images/0.gif);*/
    text-align: center;
    display: inline-block;
}
.btn-primary {
    background-color: #474760;
}

.rec_sku li{
float: left;
display: inline;
margin-right: 10px;
margin-bottom: 10px;
width: 165px;
height: 280px;
border: 1px solid rgb(29, 148, 242);
}
.rec_sku li .s_picBox {
height: 200px;
width: auto;
display: block;
overflow: hidden;
}
.rec_sku li p {
margin: 5px 10px 0 10px;
}
.deleteGoods {
position: relative;
top: -18px;
right: -140px;
}
.rec_sku li .price_red {
color: #f69;
font-size: 16px;
}
.rec_sku li .price {
color: #999;
text-decoration: line-through;
padding-left: 10px;
}
.rec_sku li .txt {
display: block;
height: 18px;
line-height: 18px;
overflow: hidden;
font-size: 14px;
}
.pcImgs {
overflow: hidden;
margin: 0 auto;
}
.pcImgs li{
float: left;
display: inline;
margin-right: 10px;
margin-bottom: 10px;
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
.tool a, .active a {
border: 0px !important;
color: white !important;
background: #474760 !important;
border-radius: 0px !important;
}
</style>
<script>
window.nowDate = '<?php echo $_smarty_tpl->tpl_vars['date']->value;?>
';
</script>
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <ol class="breadcrumb">
                <li><a href="/">Home</a></li>
                <li class="active">营销产品</li>
                <li class="active">清仓</li>
            </ol>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div role="tabpanel">
                <ul class="nav nav-tabs" role="tablist">
                    <?php $_smarty_tpl->tpl_vars['dateList'] = new Smarty_variable(MarketingManager::getDaysList(), null, 0);?>
                    <?php  $_smarty_tpl->tpl_vars['v'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['v']->_loop = false;
 $_smarty_tpl->tpl_vars['k'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['dateList']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['v']->key => $_smarty_tpl->tpl_vars['v']->value) {
$_smarty_tpl->tpl_vars['v']->_loop = true;
 $_smarty_tpl->tpl_vars['k']->value = $_smarty_tpl->tpl_vars['v']->key;
?>
                    <li role="presentation" <?php if ($_smarty_tpl->tpl_vars['date']->value==$_smarty_tpl->tpl_vars['v']->value) {?>class="active"<?php }?> data-date="{$v}">
                        <a href="/marketing/qingcang?date=<?php echo $_smarty_tpl->tpl_vars['v']->value;?>
" ><?php echo $_smarty_tpl->tpl_vars['v']->value;?>
</a>
                    </li>
                    <?php } ?>
                    
                </ul>
            </div>
        </div>
    </div>
    <p>&nbsp;</p>
    
    <div class="row" id="shopCon">
        <?php $_smarty_tpl->tpl_vars['qingcangList'] = new Smarty_variable(MarketingManager::getQingcangAllList($_smarty_tpl->tpl_vars['date']->value), null, 0);?>
        <?php  $_smarty_tpl->tpl_vars['v'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['v']->_loop = false;
 $_smarty_tpl->tpl_vars['k'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['qingcangList']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['v']->key => $_smarty_tpl->tpl_vars['v']->value) {
$_smarty_tpl->tpl_vars['v']->_loop = true;
 $_smarty_tpl->tpl_vars['k']->value = $_smarty_tpl->tpl_vars['v']->key;
?>
        <?php if ($_smarty_tpl->tpl_vars['v']->value) {?>
        <div class="col-sm-6 col-md-3">
            <div class="thumbnail" data-id="<?php echo $_smarty_tpl->tpl_vars['v']->value['id'];?>
" data-shop-id="<?php echo $_smarty_tpl->tpl_vars['v']->value['shop_id'];?>
">
                <div class="imgbackrgound imgCon" style="height: 320px;width: 255px;">
                    <img src="<?php echo getImageUrl($_smarty_tpl->tpl_vars['v']->value['shop_image']);?>
" style="height: 320px;width: 255px;">
                    <input type="hidden" name="shop_image" value="<?php echo $_smarty_tpl->tpl_vars['v']->value['goods_image'];?>
">
                </div>
                <div class="caption form-inline">
                    
                    <p><label control-label">地址：</label><input class="form-control" name="shop_url"  type="text" value="<?php echo $_smarty_tpl->tpl_vars['v']->value['shop_url'];?>
" ></p>
                    <p><label control-label">排序：</label><input class="form-control" name="rank"  type="text" value="<?php echo $_smarty_tpl->tpl_vars['v']->value['rank'];?>
" ></p>
                    <p>          <span class="btn btn-default upload-img btnUploadImg">
                        <input type="file" class="uploadImgInput" name="uploaod_img" id="shop_images_<?php echo $_smarty_tpl->tpl_vars['k']->value;?>
" onchange="javascript:bannerUpload('shop_images_<?php echo $_smarty_tpl->tpl_vars['k']->value;?>
', 'shop_image', $(this).parent().prev())">修改
                    </span><button class="pull-right btn btn-primary btnSaveShopInfo" role="button" data-id="<?php echo $_smarty_tpl->tpl_vars['v']->value['id'];?>
">保存</button></p>
                </div>
            </div>
        </div>
        <?php } else { ?>
        <div class="col-sm-6 col-md-3">
            <div class="thumbnail">
                <div class="imgbackrgound imgCon" style="height: 320px;width: 255px;">
                    <img src="<?php echo getImageUrl($_smarty_tpl->tpl_vars['tuanInfo']->value['goods_image_pc']);?>
" style="height: 320px;width: 255px;">
                    <input type="hidden" name="goods_image" value="<?php echo $_smarty_tpl->tpl_vars['tuanInfo']->value['goods_image'];?>
">
                </div>

                <div class="caption form-inline">
                    
                    <p><label control-label">地址：</label><input class="form-control" name="shop_url"  type="text" value="" ></p>
                    <p><label control-label">排序：</label><input class="form-control" name="rank"  type="text" value="" ></p>
                    <p>                <span class="btn btn-default upload-img btnUploadImg">
                    <input type="file" class="uploadImgInput" name="uploaod_img" id="shop_images_<?php echo $_smarty_tpl->tpl_vars['k']->value;?>
" onchange="javascript:bannerUpload('shop_images_<?php echo $_smarty_tpl->tpl_vars['k']->value;?>
', 'shop_image', $(this).parent().prev())">上传
                </span>
                <button class="pull-right btn btn-primary btnSaveShopInfo" role="button">保存</button></p>
                </div>
            </div>
        </div>
        <?php }?>
        <?php } ?>
    </div>
</div>
<script>
$(function(){
$("#shopCon").on("click", ".btnSaveShopInfo", function(){
if ($(this).hasClass("disabled")) return false;
var thisObj   = $(this);
var shopObj   = thisObj.closest(".thumbnail");
var id = 0;
if (shopObj.attr("data-id")) {
id = shopObj.attr("data-id");
}
var shopImage = shopObj.find("input[name='shop_image']").val();
var shopUrl   = shopObj.find("input[name='shop_url']").val();
var rank      = shopObj.find("input[name='rank']").val();
var postData = {'id':id, 'shop_image':shopImage, 'shop_url':shopUrl, 'rank':rank, 'date':window.nowDate};
var objInHtml = thisObj.html();
setBtnStatus(thisObj, '提交中...', 'disabled');
$.post('/marketing/saveQingcang', postData, function(json){
if (json.succ == '1') {
if (id) {
alert('修改成功');
} else {
shopObj.attr("data-id", json.id);
thisObj.attr("data-id", json.id);
alert('添加成功');
}
} else {
alert(json.msg);
}
setBtnStatus(thisObj, objInHtml, 'succ');
}, 'json').error(function(code, data){
alert('遇到服务器错误');
setBtnStatus(thisObj, objInHtml, 'succ');
});
});
})
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
