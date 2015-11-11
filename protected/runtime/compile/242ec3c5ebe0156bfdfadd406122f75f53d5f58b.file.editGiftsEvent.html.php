<?php /* Smarty version Smarty-3.1.18, created on 2015-11-05 18:15:38
         compiled from "/home/work/websites/tuan/protected/views/event/editGiftsEvent.html" */ ?>
<?php /*%%SmartyHeaderCode:475443044563b2c4acb6c85-73987644%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '242ec3c5ebe0156bfdfadd406122f75f53d5f58b' => 
    array (
      0 => '/home/work/websites/tuan/protected/views/event/editGiftsEvent.html',
      1 => 1439522157,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '475443044563b2c4acb6c85-73987644',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'eventInfo' => 0,
    'tipInfo' => 0,
    'v' => 0,
    'k' => 0,
    'eventGoodsList' => 0,
    'val' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.18',
  'unifunc' => 'content_563b2c4ae05220_06421068',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_563b2c4ae05220_06421068')) {function content_563b2c4ae05220_06421068($_smarty_tpl) {?><?php echo $_smarty_tpl->getSubTemplate ("layouts/header.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, null, array(), 0);?>

<title>编辑活动</title>
<script src="/assets/lib/My97DatePicker/WdatePicker.js"></script>
<script src="/assets/lib/imgAjaxfileUp.js"></script>
<script src='/assets/lib/js/Sortable.js'></script>

<style>
.rec_sku {
  overflow: hidden;
  margin: 0 auto;
  
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

</style>
<div class="container">
  <div class="row">
    <div class="col-md-12">
      <ol class="breadcrumb">
          <li><a href="/">Home</a></li>
          <li><a href="/event">主题活动 - 精品抢先</a></li>
          <li class="active">编辑活动 - <?php echo $_smarty_tpl->tpl_vars['eventInfo']->value['event_name'];?>
</li>
      </ol>
    </div>
  </div>
  <div class="row">
    <div class="col-md-12">
    
      <form id="createForm" class="form-inline" enctype="multipart/form-data"  action="/event/saveGiftsEvent" method="post">
        
        <input type="hidden" name="id" value="<?php echo $_smarty_tpl->tpl_vars['tipInfo']->value['id'];?>
">
        <input type="hidden" id="eventId" name="event_id" value="<?php echo $_smarty_tpl->tpl_vars['eventInfo']->value['event_id'];?>
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
        
        <!-- 顶部图片 -->
        <div class="control-group">
          <blockquote><p>顶部图片-PC</p></blockquote>
          <div class="imgbackrgound imgCon" style="height: 100px;width: 750px;line-height:100px;">
            <?php if (isset($_smarty_tpl->tpl_vars['eventInfo']->value['detail']['top_banner_pc'])&&$_smarty_tpl->tpl_vars['eventInfo']->value['detail']['top_banner_pc']) {?>
              <img src="<?php echo getImageUrl($_smarty_tpl->tpl_vars['eventInfo']->value['detail']['top_banner_pc']);?>
" style="height: 100px;width: 750px;">
              <input type="hidden" name="top_banner_pc" value="<?php echo $_smarty_tpl->tpl_vars['eventInfo']->value['detail']['top_banner_pc'];?>
">
            <?php } else { ?>
              请上传 750*100的图片
            <?php }?>
          </div>
          <span class="btn btn-default upload-img btnUploadImg">
            <input type="file" class="uploadImgInput" name="uploaod_img" id="top_banner_pc_uploaod" onchange="javascript:bannerUpload('top_banner_pc_uploaod', 'top_banner_pc',$(this).parent().prev())">上传
          </span>
          <span class="help-inline"></span>
        </div>
        
        <!-- 手机顶部图片 -->
        <div class="control-group">
          <blockquote><p>顶部图片-mob</p></blockquote>
          <div class="imgbackrgound imgCon" style="height: 170px;width: 320px;line-height:170px;">
            <?php if (isset($_smarty_tpl->tpl_vars['eventInfo']->value['detail']['top_banner_mob'])&&$_smarty_tpl->tpl_vars['eventInfo']->value['detail']['top_banner_mob']) {?>
              <img src="<?php echo getImageUrl($_smarty_tpl->tpl_vars['eventInfo']->value['detail']['top_banner_mob']);?>
" style="height: 170px;width: 320px;">
              <input type="hidden" name="top_banner_mob" value="<?php echo $_smarty_tpl->tpl_vars['eventInfo']->value['detail']['top_banner_mob'];?>
">
            <?php } else { ?>
              请上传 640*340的图片
            <?php }?>
          </div>
          <span class="btn btn-default upload-img btnUploadImg">
            <input type="file" class="uploadImgInput" name="uploaod_img" id="top_banner_mob_uploaod" onchange="javascript:bannerUpload('top_banner_mob_uploaod', 'top_banner_mob',$(this).parent().prev())">上传
          </span>
          <span class="help-inline"></span>
        </div>
        
        
        <!-- 内容 -->
        <div class="control-group">
          <p></p>
          <input type="submit" class="btn btn-primary Sub createBtn" value="保存活动信息">
        </div>
      </form>
      
      <br><br><br>
      <!-- 聚焦模块 -->
      <div class="control-group form-inline">
        <blockquote>
          <p>商品管理<small><span style="color:red;">注：</span>商品管理与活动基本信息是分开操作的</small></p>
          <button type="button" class="btn btn-primary btn-sm btnAddShop">添加店铺 <i class="glyphicon glyphicon-plus"></i></button>
        </blockquote>
        <div class="shopsCon">
        <?php if (isset($_smarty_tpl->tpl_vars['eventInfo']->value['detail']['shops'])&&$_smarty_tpl->tpl_vars['eventInfo']->value['detail']['shops']) {?>
          <?php  $_smarty_tpl->tpl_vars['v'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['v']->_loop = false;
 $_smarty_tpl->tpl_vars['k'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['eventInfo']->value['detail']['shops']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['v']->key => $_smarty_tpl->tpl_vars['v']->value) {
$_smarty_tpl->tpl_vars['v']->_loop = true;
 $_smarty_tpl->tpl_vars['k']->value = $_smarty_tpl->tpl_vars['v']->key;
?>
            <div class="add-con shopAddCon">
              <div class="add-con-header">
                <label>店铺名称:</label>
                <input type="text" class="require input-xlarge form-control" name="shop_name" value="<?php echo $_smarty_tpl->tpl_vars['v']->value['shop_name'];?>
">
                <label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;排序:</label>
                <input type="text" class="require input-mini form-control" name="shop_sort" value="<?php echo $_smarty_tpl->tpl_vars['v']->value['shop_sort'];?>
">(越小越靠前)
                <button type="button" class="btn btn-default btn-sm btnHideShop up" style="float:right;"><span>收起店铺</span> <i class="glyphicon glyphicon-arrow-down"></i></button>
                <button type="button" class="btn btn-danger btn-sm btnSaveShop" style="float:right;margin-right:10px;">保存店铺信息 <i class="glyphicon glyphicon-ok"></i></button>
              </div>
              <div class="add-con-body">
                <p>
                  <label>店铺描述：</label>
                  <input type="text" class="require input-xxlarge form-control" name="shop_desc" value="<?php echo $_smarty_tpl->tpl_vars['v']->value['shop_desc'];?>
">
                </p>
                <label>店铺图片：</label>
                <div class="imgbackrgound imgCon" style="height: 100px;width: 300px;line-height:100px;">
                  <?php if ($_smarty_tpl->tpl_vars['v']->value['shop_img']) {?>
                    <img src="<?php echo getImageUrl($_smarty_tpl->tpl_vars['v']->value['shop_img']);?>
" style="height: 100px;width: 300px;">
                    <input type="hidden" name="shop_img" value="<?php echo $_smarty_tpl->tpl_vars['v']->value['shop_img'];?>
">
                  <?php } else { ?>
                    请上传 600*195的图片
                  <?php }?>
                </div>
                <span class="btn btn-default upload-img btnUploadImg">
                
                  <input type="file" class="uploadImgInput" name="uploaod_img" id="shop_img_uploaod_<?php echo $_smarty_tpl->tpl_vars['k']->value;?>
" onchange="javascript:bannerUpload('shop_img_uploaod_<?php echo $_smarty_tpl->tpl_vars['k']->value;?>
', 'shop_img',$(this).parent().prev())">上传
                </span>
                <label style="margin-left:20px;">焦点图片：</label>
                <div class="imgbackrgound imgCon focuMobImgCon" style="height: 100px;width: 130px;line-height:100px;">
                  <?php if ($_smarty_tpl->tpl_vars['v']->value['focus_img_mob']) {?>
                    <img src="<?php echo getImageUrl($_smarty_tpl->tpl_vars['v']->value['focus_img_mob']);?>
" style="height: 100px;width: 130px;">
                    <input type="hidden" name="focus_img_mob" value="<?php echo $_smarty_tpl->tpl_vars['v']->value['focus_img_mob'];?>
">
                  <?php } else { ?>
                    请上传 190*220的图片
                  <?php }?>
                </div>
                <span class="btn btn-default upload-img btnUploadFocusImg">
                  <input type="file" class="uploadImgInput" name="uploaod_img" id="focus_img_mob_upload_<?php echo $_smarty_tpl->tpl_vars['k']->value;?>
" onchange="javascript:bannerUpload('focus_img_mob_upload_<?php echo $_smarty_tpl->tpl_vars['k']->value;?>
', 'focus_img_mob',$(this).parent().prev())">上传
                </span>
                <a class="glyphicon glyphicon-trash btnDeleteFocusMobImg" style="position:relative;top:40px;left:-56px;"></a>
                <p>
                  <label>店铺类型：</label>
                  <label><input type="checkbox" name="shop_shipai" <?php if ($_smarty_tpl->tpl_vars['v']->value['shop_shipai']==1) {?>checked="checked"<?php }?>> 实拍 </label> &nbsp;&nbsp;&nbsp;
                  <label><input type="checkbox" name="shop_youzhi" <?php if ($_smarty_tpl->tpl_vars['v']->value['shop_youzhi']==1) {?>checked="checked"<?php }?>> 优质商家 </label>
                </p>
                <div class="alert alert-danger shopGoodsAllConTips" style="display:none;">注意：只有保存店铺信息才可以添加商品哦~</div>
                <div class="shopGoodsAllCon">
                    <div class="shop-goods-con-header">
                      <label>添加商品：</label>
                      <input type="text" class="require input-xxlarge form-control" name="add_goods" style="height:30px;" placeholder="多个id用,分隔">
                      <button class="btn btn-primary btn-sm btnAddShopGoods" type="button" data-shopId="<?php echo $_smarty_tpl->tpl_vars['k']->value;?>
">添加</button>
                      <button class="btn btn-primary btn-sm btnSaveEventGoodsSort" type="button">保存排序</button>
                    </div>
                    <div class="shop-goods-con-body" style="padding-top:10px;">
                      <ul class="weekessence rec_sku shopGoodsCon" id="shopGoodsCon<?php echo $_smarty_tpl->tpl_vars['k']->value;?>
">
                      <?php $_smarty_tpl->tpl_vars['eventGoodsList'] = new Smarty_variable(EventManager::getEventGoodsList($_smarty_tpl->tpl_vars['eventInfo']->value['event_id'],$_smarty_tpl->tpl_vars['k']->value), null, 0);?>
                      <?php  $_smarty_tpl->tpl_vars['val'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['val']->_loop = false;
 $_smarty_tpl->tpl_vars['key'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['eventGoodsList']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['val']->key => $_smarty_tpl->tpl_vars['val']->value) {
$_smarty_tpl->tpl_vars['val']->_loop = true;
 $_smarty_tpl->tpl_vars['key']->value = $_smarty_tpl->tpl_vars['val']->key;
?>
                        <li class="shopGoodsList" data-grouponId="<?php echo $_smarty_tpl->tpl_vars['val']->value['groupon_id'];?>
" data-id="<?php echo $_smarty_tpl->tpl_vars['val']->value['id'];?>
" data-twitterId="<?php echo $_smarty_tpl->tpl_vars['val']->value['twitter_id'];?>
">
                          <div class="s_picBox">
                            <img src="<?php echo getImageUrl($_smarty_tpl->tpl_vars['val']->value['goods_image'],array('s6','163','200'));?>
" style="margin-left: 0px;">
                          </div> 
                          <p class="txt"><?php echo $_smarty_tpl->tpl_vars['val']->value['goods_name'];?>
</p>
                          <p class="price_box">
                            <span class="price_red"><?php echo $_smarty_tpl->tpl_vars['val']->value['off_price'];?>
</span>
                            <span class="price"><?php echo $_smarty_tpl->tpl_vars['val']->value['off_price']+$_smarty_tpl->tpl_vars['val']->value['off_num'];?>
</span>
                          </p>
                          <p class="txt"><span class="listTwitterId"><?php echo $_smarty_tpl->tpl_vars['val']->value['twitter_id'];?>
</span></p>
                          <a class="glyphicon glyphicon-trash deleteGoods" data-id="<?php echo $_smarty_tpl->tpl_vars['val']->value['id'];?>
" data-eventId=<?php echo $_smarty_tpl->tpl_vars['eventInfo']->value['event_id'];?>
></a>
                        </li>
                      <?php } ?>
                      </ul>
                    </div>
                </div>
              </div>
              <input type="hidden" name="shop_id" value="<?php echo $_smarty_tpl->tpl_vars['k']->value;?>
">
            </div>
          <?php } ?>
        <?php } else { ?>
          <div class="add-con shopAddCon">
            <div class="add-con-header">
              <label>店铺名称:</label>
              <input type="text" class="require input-xlarge form-control" name="shop_name" value="">
              <label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;排序:</label>
              <input type="text" class="require input-mini form-control" name="shop_sort" value="0">(越小越靠前)
              <button type="button" class="btn btn-default btn-sm btnHideShop up" style="float:right;"><span>收起店铺</span> <i class="glyphicon glyphicon-arrow-down"></i></button>
              <button type="button" class="btn btn-danger btn-sm btnSaveShop" style="float:right;margin-right:10px;">保存店铺信息 <i class="glyphicon glyphicon-ok"></i></button>
            </div>
            <div class="add-con-body">
              <p>
                <label>店铺描述：</label>
                <input type="text" class="require input-xxlarge form-control" name="shop_desc" value="">
              </p>
              <label>店铺图片：</label>
              <div class="imgbackrgound imgCon" style="height: 100px;width: 300px;line-height:100px;">
                请上传 600*195的图片
              </div>
              <span class="btn btn-default upload-img btnUploadImg">
                <input type="file" class="uploadImgInput" name="uploaod_img" id="shop_img_uploaod" onchange="javascript:bannerUpload('shop_img_uploaod', 'shop_img',$(this).parent().prev())">上传
              </span>
              <label style="margin-left:20px;">焦点图片：</label>
              <div class="imgbackrgound imgCon focuMobImgCon" style="height: 100px;width: 130px;line-height:100px;">
                请上传 190*220的图片
              </div>
              <span class="btn btn-default upload-img btnUploadFocusImg">
                <input type="file" class="uploadImgInput" name="uploaod_img" id="focus_img_mob_upload" onchange="javascript:bannerUpload('focus_img_mob_upload', 'focus_img_mob',$(this).parent().prev())">上传
              </span>
              <a class="glyphicon glyphicon-trash btnDeleteFocusMobImg" style="position:relative;top:40px;left:-56px;"></a>
              <p>
                <label>店铺类型：</label>
                <label><input type="checkbox" name="shop_shipai" value="0"> 实拍 </label> &nbsp;&nbsp;&nbsp;
                <label><input type="checkbox" name="shop_youzhi" value="0"> 优质商家 </label>
              </p>
              <div class="alert alert-danger shopGoodsAllConTips">注意：只有保存店铺信息才可以添加商品哦~</div>
              <div class="shopGoodsAllCon" style="display:none;">
                  <div class="shop-goods-con-header">
                    <label>添加商品：</label>
                    <input type="text" class="require input-xxlarge form-control" name="add_goods" style="height:30px;" placeholder="多个id用,分隔">
                    <button class="btn btn-primary btn-sm btnAddShopGoods" type="button" data-shopId="0">添加</button>
                  </div>
                  <div class="shop-goods-con-body" style="padding-top:10px;">
                    <ul class="weekessence rec_sku shopGoodsCon">
                    </ul>
                  </div>
              </div>
            </div>
          </div>
        <?php }?>
        </div>
      </div>
        
        
    </div>
  </div>
  
  <div class="appendCon" style="display:none">
    <div class="appendFocusImgList">
      <li class="focusImgList">
        <div class="imgbackrgound imgCon" style="height: 100px;width: 340px;line-height:100px;">
          请上传 640*100的图片
        </div>
        <span class="btn btn-default upload-img btnUploadImg">
          <input type="file" class="uploadImgInput" name="uploaod_img" id="focus_img_pc_upload" onchange="javascript:bannerUpload('focus_img_pc_upload', 'focus_img_pc[]',$(this).parent().prev())">上传
        </span>
        <a class="glyphicon glyphicon-trash btnDeleteFocusImg" style="position:relative;top:40px;left:-56px;"></a>
        <input type="text" class="require input-xlarge form-control" name="focus_img_link_pc[]" value="" style="display: block;">
      </li>
    </div>

    <div class="appendFocusImgMobList">
      <li class="focusImgList">
        <div class="imgbackrgound imgCon" style="height: 220px;width: 190px;line-height:220px;">
          请上传 190*220的图片
        </div>
        <span class="btn btn-default upload-img btnUploadImg">
          <input type="file" class="uploadImgInput" name="uploaod_img" id="focus_img_mob_upload" onchange="javascript:bannerUpload('focus_img_mob_upload', 'focus_img_mob[]',$(this).parent().prev())">上传
        </span>
        <a class="glyphicon glyphicon-trash btnDeleteFocusImg" style="position:relative;top:40px;left:-56px;"></a>
        <input type="text" class="require input-xlarge form-control" name="focus_img_link_mob[]" value="" style="display: block;">
      </li>
    </div>

    <div class="aappendShopAddCon">
      <div class="add-con shopAddCon">
        <div class="add-con-header">
          <label>店铺名称:</label>
          <input type="text" class="require input-xlarge form-control" name="shop_name" value="">
          <label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;排序:</label>
          <input type="text" class="require input-mini form-control" name="shop_sort" value="0">(越小越靠前)
          <button type="button" class="btn btn-default btn-sm btnHideShop up" style="float:right;"><span>收起店铺</span> <i class="glyphicon glyphicon-arrow-down"></i></button>
          <button type="button" class="btn btn-danger btn-sm btnSaveShop" style="float:right;margin-right:10px;">保存店铺信息 <i class="glyphicon glyphicon-ok"></i></button>
        </div>
        <div class="add-con-body">
          <p>
            <label>店铺描述：</label>
            <input type="text" class="require input-xxlarge form-control" name="shop_desc" value="">
          </p>
          <label>店铺图片：</label>
          <div class="imgbackrgound imgCon" style="height: 100px;width: 300px;line-height:100px;">
            请上传 600*195的图片
          </div>
          <span class="btn btn-default upload-img btnUploadImg">
            <input type="file" class="uploadImgInput" name="uploaod_img" id="shop_img_uploaod" onchange="javascript:bannerUpload('shop_img_uploaod', 'shop_img',$(this).parent().prev())">上传
          </span>
          <label style="margin-left:20px;">焦点图片：</label>
          <div class="imgbackrgound imgCon focuMobImgCon" style="height: 100px;width: 130px;line-height:100px;">
            请上传 190*220的图片
          </div>
          <span class="btn btn-default upload-img btnUploadFocusImg">
            <input type="file" class="uploadImgInput" name="uploaod_img" id="focus_img_mob_upload" onchange="javascript:bannerUpload('focus_img_mob_upload', 'focus_img_mob',$(this).parent().prev())">上传
          </span>
          <a class="glyphicon glyphicon-trash btnDeleteFocusMobImg" style="position:relative;top:40px;left:-56px;"></a>
          <p>
            <label>店铺类型：</label>
            <label><input type="checkbox" name="shop_shipai" value="0"> 实拍 </label> &nbsp;&nbsp;&nbsp;
            <label><input type="checkbox" name="shop_youzhi" value="0"> 优质商家 </label>
          </p>
          <div class="alert alert-danger shopGoodsAllConTips">注意：只有保存店铺信息才可以添加商品哦~</div>
          <div class="shopGoodsAllCon" style="display:none;">
              <div class="shop-goods-con-header">
                <label>添加商品：</label>
                <input type="text" class="require input-xxlarge form-control" name="add_goods" style="height:30px;" placeholder="多个id用,分隔">
                <button class="btn btn-primary btn-sm btnAddShopGoods" type="button" data-shopId="0" >添加</button>
              </div>
              <div class="shop-goods-con-body" style="padding-top:10px;">
                <ul class="weekessence rec_sku shopGoodsCon">
                </ul>
              </div>
          </div>
        </div>
      </div>
    </div>
    
    <li class="shopGoodsList" data-id="918064" data-twitterId="3534936897" data-goodsid="254902619">
      <div class="s_picBox">
        <img src="http://d06.res.meilishuo.net//pic/_o/b9/69/47e7efe431e5bdfb87d29bf08225_640_900.ch.png_4d167a26_s2_229_320.jpg" style="margin-left: 0px;">
      </div> 
      <p class="txt">高腰牛仔+横条套装</p>
      <p class="price_box">
        <span class="price_red">43.80</span>
        <span class="price">23.0</span>
      </p>
      <a class="glyphicon glyphicon-trash deleteGoods" data-twitterId="3534936897"></a>
    </li>
  </div>
</div>
<script>
$(function(){
  /** 添加焦点pc */
  $(".addFocusImgPc").click(function(){
    $(this).closest(".control-group").find(".focusImgCon").append($(".appendCon").find(".appendFocusImgList").html());
    var conList = $(this).closest(".control-group").find(".focusImgCon").find("li");
    var nowlength = conList.length - 1;
    var obj = $(conList[nowlength]);
    var inHtml = '<input type="file" class="uploadImgInput" name="uploaod_img" id="focus_img_pc_upload_'+nowlength+'" onchange="javascript:bannerUpload(\'focus_img_pc_upload_'+nowlength+'\', \'focus_img_pc[]\', $(this).parent().prev())">上传';
    obj.find(".btnUploadImg").html(inHtml);
  });

  /** 添加焦点mob */
  $(".addFocusImgMob").click(function(){
    $(this).closest(".control-group").find(".focusImgCon").append($(".appendCon").find(".appendFocusImgMobList").html());
    var conList = $(this).closest(".control-group").find(".focusImgCon").find("li");
    var nowlength = conList.length - 1;
    var obj = $(conList[nowlength]);
    var inHtml = '<input type="file" class="uploadImgInput" name="uploaod_img" id="focus_img_mob_upload_'+nowlength+'" onchange="javascript:bannerUpload(\'focus_img_mob_upload_'+nowlength+'\', \'focus_img_mob[]\', $(this).parent().prev())">上传';
    obj.find(".btnUploadImg").html(inHtml);
  });
  
  /** 添加商铺 */
  $(".btnAddShop").click(function(){
    $(this).closest(".control-group").find(".shopsCon").append($(".appendCon").find(".aappendShopAddCon").html());
    var conList = $(this).closest(".control-group").find(".shopAddCon");
    var nowlength = conList.length - 1;
    var obj = $(conList[nowlength]);
    var inHtml = '<input type="file" class="uploadImgInput" name="uploaod_img" id="shop_img_uploaod_'+nowlength+'" onchange="javascript:bannerUpload(\'shop_img_uploaod_'+nowlength+'\', \'shop_img\', $(this).parent().prev())">上传';
    var inFocusHtml = '<input type="file" class="uploadImgInput" name="uploaod_img" id="focus_img_mob_upload_'+nowlength+'" onchange="javascript:bannerUpload(\'focus_img_mob_upload_'+nowlength+'\', \'focus_img_mob\',$(this).parent().prev())">上传';
    obj.find(".btnUploadImg").html(inHtml);
    obj.find(".btnUploadFocusImg").html(inFocusHtml);
    obj.find("input[name='shop_name']").focus();
    
    // 排序
    // new Sortable(obj.find(".shopGoodsCon")[0]);
    new Sortable(obj.find(".shopGoodsCon")[0], {
      handle: 'img'
    });
  });
  
  /** 保存商铺 */
  $(".shopsCon").on("click", ".btnSaveShop", function(){
    if ($(this).hasClass("disabled")) return false;
    var thisObj    = $(this);
    var eventId    = $("#eventId").val();
    var shopObj    = thisObj.closest(".shopAddCon");
    var shopId     = shopObj.find("input[name='shop_id']").val();
    var shopName   = shopObj.find("input[name='shop_name']").val();
    var shopDesc   = shopObj.find("input[name='shop_desc']").val();
    var shopImg    = shopObj.find("input[name='shop_img']").val();
    var shopSort   = shopObj.find("input[name='shop_sort']").val();
    var focusImgMob    = shopObj.find("input[name='focus_img_mob']").val();
    var shopShipai = 0;
    var shopYouzhi = 0;
    if (shopObj.find("input[name='shop_shipai']").prop("checked") == true) {
      var shopShipai = 1;
    }
    if (shopObj.find("input[name='shop_youzhi']").prop("checked") == true) {
      var shopYouzhi = 1;
    }
    
    if (!shopName) {
      alert('请填写商铺名称');
      return false;
    }
    if (!shopImg) {
      alert('请给商铺上传图片');
      return false;
    }
    
    var postData = {'event_id':eventId, 'shop_name':shopName, 'shop_desc':shopDesc, 'shop_img':shopImg, 'shop_shipai':shopShipai, 'shop_youzhi':shopYouzhi, 'shop_sort':shopSort, 'focus_img_mob':focusImgMob}
    if (shopId != 'undefined') {
      postData['shop_id'] = shopId;
    }
    //console.log(postData);return false;
    var objInHtml = thisObj.html();
    setBtnStatus(thisObj, '提交中...', 'disabled');
    
    $.post('/event/saveGiftsShop', postData, function(json){
      console.log(json)
      if (json.succ == 1) {
        // 设置shopId
        if (shopObj.find("input[name='shop_id']").length > 1) {
          shopObj.find("input[name='shop_id']").val(json.shop_id);
        } else {
          var shopIdCon = '<input type="hidden" name="shop_id" value="'+json.shop_id+'">';
          shopObj.append(shopIdCon);
        }
        
        shopObj.find(".shopGoodsAllConTips").hide();
        shopObj.find(".shopGoodsAllCon").show();
      } else {
        alert(json.msg);
      }
      setBtnStatus(thisObj, objInHtml, 'succ');
    }, 'json').error(function(code, data){
      alert('服务器繁忙,请稍后再试');
      setBtnStatus(thisObj, objInHtml, 'succ');
    });
  });
  
  /** 添加商品 */
  $(".shopsCon").on("click", ".btnAddShopGoods", function(){
    if ($(this).hasClass("disabled")) return false;
    var thisObj = $(this);
    var shopObj = thisObj.closest(".shopAddCon");
    var twitterId = thisObj.siblings("input[name='add_goods']").val();
    var eventId   = $("#eventId").val();
    var shopId    = shopObj.find("input[name='shop_id']").val();
    
    if (!twitterId) {
      alert('请输入twitter_id');
      return false;
    }
    if (!eventId) {
      alert('活动信息不存在');
      return false;
    }
    if (!shopId) {
      alert('区域id不存在');
      return false;
    }
    
    var objInHtml = thisObj.html();
    setBtnStatus(thisObj, '提交中...', 'disabled');
    $.post('/eventGoods/saveEventGoods', {'twitter_id':twitterId, 'event_id':eventId, 'area_id':shopId}, function(json){
      if (json.succ == "1") {
        //var nowLength = thisObj.attr("data-shopId");
        $.each(json.data,function(k,v){
          var inHtml = '<li class="shopGoodsList" data-grouponId="'+v.id+'" data-id="'+v.event_goods_id+'" data-twitterId="'+v.twitter_id+'">';
          inHtml += '<div class="s_picBox">';
          inHtml += '<img src="'+v.goods_image+'" style="margin-left: 0px;">';
          inHtml += '</div>';
          inHtml += '<p class="txt">'+v.goods_name+'</p>';
          inHtml += '<p class="price_box">';
          inHtml += '<span class="price_red">'+v.off_price+'</span>';
          inHtml += '<span class="price">'+v.origin_price+'</span>';
          inHtml += '</p>';
          inHtml += '<p class="txt"><span class="listTwitterId">'+v.twitter_id+'</span></p>';
          inHtml += '<a class="glyphicon glyphicon-trash deleteGoods" data-id="'+v.event_goods_id+'" data-eventId="'+eventId+'"></a>';
          inHtml += '</li>';
          thisObj.closest(".shopAddCon").find(".shopGoodsCon").append(inHtml);
          thisObj.siblings("input[name='add_goods']").val("");
        });
        alert('成功添加：'+json.succ_num+'个商品，添加失败：'+json.err_num+"个商品");
        if (json.err_num > 0) {
          alert(json.err_result);
        }
      } else {
        console.log(json)
        alert(json.msg);
      }
      setBtnStatus(thisObj, objInHtml, 'succ');
    },'json').error(function(data,code){
      alert('服务器繁忙，请稍后重试');
      setBtnStatus(thisObj, objInHtml, 'succ');
    });
  });
  
  
  /** 隐藏显示店铺 */
  $(".shopsCon").on("click", ".btnHideShop", function(){
    if ($(this).hasClass("up")) {
      $(this).find("span").html("显示店铺");
      $(this).find(".glyphicon").addClass("glyphicon-arrow-up").removeClass("glyphicon-arrow-down");
      $(this).removeClass("up").addClass("down");
      $(this).closest(".shopAddCon").find(".add-con-body").stop().slideUp();
    } else {
      $(this).find("span").html("隐藏店铺");
      $(this).find(".glyphicon").addClass("glyphicon-arrow-down").removeClass("glyphicon-arrow-up");
      $(this).removeClass("down").addClass("up");
      $(this).closest(".shopAddCon").find(".add-con-body").stop().slideDown();
    }
  });
  
  /** 排序 */
  $.each($(".shopsCon").find(".shopGoodsCon"), function(k, v){
    // @fixme 请注意，这里要取第一个
    // new Sortable($(v)[0]);
    new Sortable($(v)[0], {
      handle: 'img'
    });
  });
  
  /** 保存排序字段 */
  $(".shopsCon").on("click", ".btnSaveEventGoodsSort", function(){
    if ($(this).hasClass("disabled")) return false;
    var thisObj = $(this);
    var shopObj = thisObj.closest(".shopAddCon");
    var eventId   = $("#eventId").val();
    var shopId    = shopObj.find("input[name='shop_id']").val();
    
    var ids = [];
    shopObj.find(".shopGoodsList").each(function(){
        var recommendId = $(this).attr("data-Id");
        if (recommendId) {
            ids.push(recommendId);
        }
    });
    if (ids.length < 1) return false;
    
    idsStr = ids.join(",");
    
    var objInHtml = thisObj.html();
    setBtnStatus(thisObj, '提交中...', 'disabled');
    $.post('/event/saveEventGoodsSort', {'ids':idsStr, 'area':shopId, 'event_id':eventId}, function(json){
      if (json.succ == 1) {
        alert('排序成功，成功操作 '+json.succ_num+' 个商品，操作失败 '+json.err_num+' 个商品');
      } else {
        alert(json.msg);
      }
      setBtnStatus(thisObj, objInHtml, 'succ');
    },'json').error(function(code, data){
      alert('服务器繁忙，请稍后重试');
      setBtnStatus(thisObj, objInHtml, 'succ');
    });
  });
  
  /** 删除聚焦图 */
  $(".focusImgCon").on("click", ".btnDeleteFocusImg", function(e){
    e.preventDefault();
    var removeObj = $(this).closest("li");
    removeObj.fadeOut(function(){
      removeObj.remove();
    });
  });
  
  /** 删除商铺焦点图片 */
  $(".shopsCon").on("click", ".btnDeleteFocusMobImg", function(e){
    e.preventDefault();
    $(this).siblings(".focuMobImgCon").html("请上传 190*220的图片");
  });
  
  /** 精品抢鲜商品删除 {直接操作为排期失败} */
  $(".shopsCon").on("click", ".deleteGoods", function(e){
    e.preventDefault();
    if ($(this).hasClass("disabled")) return false;
    var thisObj = $(this);
    var goodsObj = thisObj.closest(".shopGoodsList");
    var grouponId = goodsObj.attr("data-grouponId");
    if (!grouponId) {
      alter('商品不存在');
      return false;
    }
    if (!confirm('您确定要删除该商品吗？删除后该商品会进入排期失败状态')) {
      return false;
    }
    
    var objInHtml = thisObj.html();
    setBtnStatus(thisObj, objInHtml, 'disabled');
    $.post('/schedule/cancelSchedule', {'tuan_id':grouponId}, function(json){
      if (json.succ == 1) {
        setBtnStatus(thisObj, objInHtml, 'succ');
        goodsObj.fadeOut(function(){
          $(this).remove();
        });
      } else {
        alert(json.msg);
        setBtnStatus(thisObj, objInHtml, 'succ');
      }
    },'json').error(function(code, data){
      alert('服务器繁忙，请稍后重试');
      setBtnStatus(thisObj, objInHtml, 'succ');
    });
  });
  
});

//图片上传方法
function bannerUpload(id, imputImgName,imgObj) {
  $.ajaxFileUpload({
       url:'/event/uploadImage',
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

$(function(){
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
    var eventNameObj        = $('#createForm input[name="event_name"]');
    if ($('.createBtn').hasClass('disabled')) {
      alert('请稍等，正在保存');
      return false;
    }
    if (!$.trim(eventNameObj.val())) {
        showError(eventNameObj, '请填写标题');
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
