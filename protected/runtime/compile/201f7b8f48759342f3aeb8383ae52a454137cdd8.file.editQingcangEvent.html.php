<?php /* Smarty version Smarty-3.1.18, created on 2015-10-14 14:45:39
         compiled from "/home/work/websites/tuan/protected/views/event/editQingcangEvent.html" */ ?>
<?php /*%%SmartyHeaderCode:1129790618561dfa13c3ee78-77312304%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '201f7b8f48759342f3aeb8383ae52a454137cdd8' => 
    array (
      0 => '/home/work/websites/tuan/protected/views/event/editQingcangEvent.html',
      1 => 1441860301,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '1129790618561dfa13c3ee78-77312304',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'eventInfo' => 0,
    'tipInfo' => 0,
    'v' => 0,
    'k' => 0,
    'val' => 0,
    'key' => 0,
    'eventGoodsList' => 0,
    'goodsVal' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.18',
  'unifunc' => 'content_561dfa13dc5bc6_24861537',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_561dfa13dc5bc6_24861537')) {function content_561dfa13dc5bc6_24861537($_smarty_tpl) {?><?php echo $_smarty_tpl->getSubTemplate ("layouts/header.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, null, array(), 0);?>

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

.oneTagCon {
  border: 1px solid #bce8f1;  border-radius: 4px;padding-bottom:5px;margin-bottom:10px;
}
.oneTagCon .alert{
  margin-bottom:0px;border-top:0;border-left:0;border-right:0;
}
.tagModuleCon { min-height:100px; }
</style>
<div class="container">
  <div class="row">
    <div class="col-md-12">
      <ol class="breadcrumb">
          <li><a href="/">Home</a></li>
          <li><a href="/event">主题活动 - 惊喜秒杀</a></li>
          <li class="active">编辑活动 - <?php echo $_smarty_tpl->tpl_vars['eventInfo']->value['event_name'];?>
</li>
      </ol>
    </div>
  </div>
  <div class="row">
    <div class="col-md-12">
    
      <form id="createForm" class="form-inline" enctype="multipart/form-data"  action="/event/saveSupriseEvent" method="post">
        
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
          <button type="button" class="btn btn-primary btn-sm btnAddTag">添加标签 <i class="glyphicon glyphicon-plus"></i></button>
        </blockquote>
        <div class="allTagCon">
        <?php if (isset($_smarty_tpl->tpl_vars['eventInfo']->value['detail']['tags'])&&$_smarty_tpl->tpl_vars['eventInfo']->value['detail']['tags']) {?>
          <?php  $_smarty_tpl->tpl_vars['v'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['v']->_loop = false;
 $_smarty_tpl->tpl_vars['k'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['eventInfo']->value['detail']['tags']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['v']->key => $_smarty_tpl->tpl_vars['v']->value) {
$_smarty_tpl->tpl_vars['v']->_loop = true;
 $_smarty_tpl->tpl_vars['k']->value = $_smarty_tpl->tpl_vars['v']->key;
?>
            <div class="oneTagCon" >
              <div class="alert alert-info">
                <label>标签名称:</label>
                <input type="text" class="require input-xlarge form-control" name="tag_name" value="<?php echo $_smarty_tpl->tpl_vars['v']->value['tag_name'];?>
">
                <label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;序号:</label>
                <input type="text" class="require input-mini form-control" name="tag_id" disabled value="<?php echo $_smarty_tpl->tpl_vars['k']->value;?>
">（越小越在前面）
                <button type="button" class="btn btn-default btn-sm btnHideTag up" style="float:right;"><span>收起标签</span> <i class="glyphicon glyphicon-arrow-down"></i></button>
                <button type="button" class="btn btn-danger btn-sm btnSaveTag" style="float:right;margin-right:10px;">保存标签信息 <i class="glyphicon glyphicon-ok"></i></button>
                <div class="btn-group btnAddModuleCon" style="float:right;margin-right:10px;">
                  <button type="button" class="btn btn-success btn-sm dropdown-toggle" data-toggle="dropdown" aria-expanded="false">模块 <i class="glyphicon glyphicon-plus"></i> <span class="caret"></span></button>
                  <ul class="dropdown-menu" role="menu">
                    <li><a href="javascript:;" class="btnAddModule" data-module_type="text">文字模块</a></li>
                    <li><a href="javascript:;" class="btnAddModule" data-module_type="img">图片模块</a></li>
                  </ul>
                </div>
              </div>
              <div class="tagModuleCon">
                <?php if (isset($_smarty_tpl->tpl_vars['v']->value['modules'])&&$_smarty_tpl->tpl_vars['v']->value['modules']) {?>
                  <?php  $_smarty_tpl->tpl_vars['val'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['val']->_loop = false;
 $_smarty_tpl->tpl_vars['key'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['v']->value['modules']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['val']->key => $_smarty_tpl->tpl_vars['val']->value) {
$_smarty_tpl->tpl_vars['val']->_loop = true;
 $_smarty_tpl->tpl_vars['key']->value = $_smarty_tpl->tpl_vars['val']->key;
?>
                    <?php if ($_smarty_tpl->tpl_vars['val']->value['module_type']=='text') {?>
                      <div class="add-con textAddCon moduleAddCon" style="margin:5px 10px 0 10px;">
                        <div class="add-con-header">
                          <label>模块名称:</label>
                          <input type="text" class="require input-xlarge form-control" name="module_name" value="<?php echo $_smarty_tpl->tpl_vars['val']->value['module_name'];?>
">
                          <label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;排序:</label>
                          <input type="text" class="require input-mini form-control" name="module_sort" value="<?php echo $_smarty_tpl->tpl_vars['val']->value['module_sort'];?>
">(越小越靠前)
                          <button type="button" class="btn btn-default btn-sm btnHideModule up" style="float:right;"><span>收起模块</span> <i class="glyphicon glyphicon-arrow-down"></i></button>
                          <button type="button" class="btn btn-danger btn-sm btnSaveModule" style="float:right;margin-right:10px;">保存模块信息 <i class="glyphicon glyphicon-ok"></i></button>
                        </div>
                        <div class="add-con-body">
                          <div class="alert alert-danger moduleGoodsAllConTips" style="display:none">注意：只有保存模块信息才可以添加商品哦~</div>
                          <div class="moduleGoodsAllCon" style="display:block;">
                              <div class="module-goods-con-header moduleGoodsConHeader">
                                <label>添加商品：</label>
                                <input type="text" class="require input-xxlarge form-control" name="add_goods" style="height:30px;" placeholder="多个id用,分隔">
                                
                                <button class="btn btn-primary btn-sm btnAddModuleGoods" type="button" data-shopId="0">添加</button>
                                <button class="btn btn-primary btn-sm btnSaveEventGoodsSort" type="button">保存排序</button>
                              </div>
                              <div class="module-goods-con-body moduleGoodsConBody" style="padding-top:10px;">
                                <ul class="weekessence rec_sku moduleGoodsCon">
                                  <?php $_smarty_tpl->tpl_vars['eventGoodsList'] = new Smarty_variable(EventManager::getEventGoodsList($_smarty_tpl->tpl_vars['eventInfo']->value['event_id'],$_smarty_tpl->tpl_vars['k']->value,$_smarty_tpl->tpl_vars['key']->value), null, 0);?>
                                  <?php  $_smarty_tpl->tpl_vars['goodsVal'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['goodsVal']->_loop = false;
 $_smarty_tpl->tpl_vars['goodsKey'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['eventGoodsList']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['goodsVal']->key => $_smarty_tpl->tpl_vars['goodsVal']->value) {
$_smarty_tpl->tpl_vars['goodsVal']->_loop = true;
 $_smarty_tpl->tpl_vars['goodsKey']->value = $_smarty_tpl->tpl_vars['goodsVal']->key;
?>
                                    <li class="shopGoodsList" data-grouponId="<?php echo $_smarty_tpl->tpl_vars['goodsVal']->value['groupon_id'];?>
" data-id="<?php echo $_smarty_tpl->tpl_vars['goodsVal']->value['id'];?>
" data-twitterId="<?php echo $_smarty_tpl->tpl_vars['goodsVal']->value['twitter_id'];?>
">
                                      <div class="s_picBox">
                                        <img src="<?php echo getImageUrl($_smarty_tpl->tpl_vars['goodsVal']->value['goods_image'],array('s6','163','200'));?>
" style="margin-left: 0px;">
                                      </div> 
                                      <p class="txt"><?php echo $_smarty_tpl->tpl_vars['goodsVal']->value['goods_name'];?>
</p>
                                      <p class="price_box">
                                        <span class="price_red"><?php echo $_smarty_tpl->tpl_vars['goodsVal']->value['off_price'];?>
</span>
                                        <span class="price"><?php echo $_smarty_tpl->tpl_vars['goodsVal']->value['off_price']+$_smarty_tpl->tpl_vars['goodsVal']->value['off_num'];?>
</span>
                                      </p>
                                      <p class="txt"><span class="listTwitterId"><?php echo $_smarty_tpl->tpl_vars['goodsVal']->value['twitter_id'];?>
</span></p>
                                      <a class="glyphicon glyphicon-trash deleteGoods" data-id="<?php echo $_smarty_tpl->tpl_vars['goodsVal']->value['id'];?>
" data-eventId=<?php echo $_smarty_tpl->tpl_vars['eventInfo']->value['event_id'];?>
></a>
                                    </li>
                                  <?php } ?>
                                </ul>
                              </div>
                          </div>
                        </div>
                        <input type="hidden" name="module_type" value="text">
                        <input type="hidden" name="module_id" value="<?php echo $_smarty_tpl->tpl_vars['key']->value;?>
">
                      </div>
                    <?php } elseif ($_smarty_tpl->tpl_vars['val']->value['module_type']=='img') {?>
                      <div class="add-con imgAddCon moduleAddCon" style="margin:5px 10px 0 10px;">
                        <div class="add-con-header">
                          <label>模块图片:</label>
                          <div class="imgbackrgound imgCon" style="height: 100px;width: 210px;line-height:100px;">
                            <img src="<?php ob_start();?><?php echo $_smarty_tpl->tpl_vars['val']->value['module_img'];?>
<?php $_tmp1=ob_get_clean();?><?php echo getImageUrl($_tmp1);?>
" style="height: 100px;width: 210px;">
                            <input type="hidden" name="module_img" value="<?php echo $_smarty_tpl->tpl_vars['val']->value['module_img'];?>
">
                          </div>
                          <span class="btn btn-default upload-img btnUploadImg">
                            <input type="file" class="uploadImgInput" name="uploaod_img" id="module_img_uploaod_<?php echo $_smarty_tpl->tpl_vars['key']->value;?>
" onchange="javascript:bannerUpload('module_img_uploaod_<?php echo $_smarty_tpl->tpl_vars['key']->value;?>
', 'module_img',$(this).parent().prev())">上传
                          </span>
                          <label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;排序:</label>
                          <input type="text" class="require input-mini form-control" name="module_sort" value="<?php echo $_smarty_tpl->tpl_vars['val']->value['module_sort'];?>
">(越小越靠前)
                          <button type="button" class="btn btn-default btn-sm btnHideModule up" style="float:right;"><span>收起模块</span> <i class="glyphicon glyphicon-arrow-down"></i></button>
                          <button type="button" class="btn btn-danger btn-sm btnSaveModule" style="float:right;margin-right:10px;">保存模块信息 <i class="glyphicon glyphicon-ok"></i></button>
                        </div>
                        <div class="add-con-body">
                          <div class="alert alert-danger moduleGoodsAllConTips" style="display:none;">注意：只有保存模块信息才可以添加商品哦~</div>
                          <div class="moduleGoodsAllCon" style="display:block;">
                              <div class="module-goods-con-header moduleGoodsConHeader">
                                <label>添加商品：</label>
                                <input type="text" class="require input-xxlarge form-control" name="add_goods" style="height:30px;" placeholder="多个id用,分隔">
                                
                                <button class="btn btn-primary btn-sm btnAddModuleGoods" type="button" data-shopId="0">添加</button>
                                <button class="btn btn-primary btn-sm btnSaveEventGoodsSort" type="button">保存排序</button>
                              </div>
                              <div class="module-goods-con-body moduleGoodsConBody" style="padding-top:10px;">
                                <ul class="weekessence rec_sku moduleGoodsCon">
                                  <?php $_smarty_tpl->tpl_vars['eventGoodsList'] = new Smarty_variable(EventManager::getEventGoodsList($_smarty_tpl->tpl_vars['eventInfo']->value['event_id'],$_smarty_tpl->tpl_vars['k']->value,$_smarty_tpl->tpl_vars['key']->value), null, 0);?>
                                  <?php  $_smarty_tpl->tpl_vars['goodsVal'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['goodsVal']->_loop = false;
 $_smarty_tpl->tpl_vars['goodsKey'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['eventGoodsList']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['goodsVal']->key => $_smarty_tpl->tpl_vars['goodsVal']->value) {
$_smarty_tpl->tpl_vars['goodsVal']->_loop = true;
 $_smarty_tpl->tpl_vars['goodsKey']->value = $_smarty_tpl->tpl_vars['goodsVal']->key;
?>
                                    <li class="shopGoodsList" data-grouponId="<?php echo $_smarty_tpl->tpl_vars['goodsVal']->value['groupon_id'];?>
" data-id="<?php echo $_smarty_tpl->tpl_vars['goodsVal']->value['id'];?>
" data-twitterId="<?php echo $_smarty_tpl->tpl_vars['goodsVal']->value['twitter_id'];?>
">
                                      <div class="s_picBox">
                                        <img src="<?php echo getImageUrl($_smarty_tpl->tpl_vars['goodsVal']->value['goods_image'],array('s6','163','200'));?>
" style="margin-left: 0px;">
                                      </div> 
                                      <p class="txt"><?php echo $_smarty_tpl->tpl_vars['goodsVal']->value['goods_name'];?>
</p>
                                      <p class="price_box">
                                        <span class="price_red"><?php echo $_smarty_tpl->tpl_vars['goodsVal']->value['off_price'];?>
</span>
                                        <span class="price"><?php echo $_smarty_tpl->tpl_vars['goodsVal']->value['off_price']+$_smarty_tpl->tpl_vars['goodsVal']->value['off_num'];?>
</span>
                                      </p>
                                      <p class="txt"><span class="listTwitterId"><?php echo $_smarty_tpl->tpl_vars['goodsVal']->value['twitter_id'];?>
</span></p>
                                      <a class="glyphicon glyphicon-trash deleteGoods" data-id="<?php echo $_smarty_tpl->tpl_vars['goodsVal']->value['id'];?>
" data-eventId=<?php echo $_smarty_tpl->tpl_vars['eventInfo']->value['event_id'];?>
></a>
                                    </li>
                                  <?php } ?>
                                </ul>
                              </div>
                          </div>
                        </div>
                        <input type="hidden" name="module_type" value="img">
                        <input type="hidden" name="module_id" value="<?php echo $_smarty_tpl->tpl_vars['key']->value;?>
">
                      </div>
                    <?php }?>
                  <?php } ?>
                <?php }?>
              </div>
              <input type="hidden" name="tag_id" value="<?php echo $_smarty_tpl->tpl_vars['k']->value;?>
">
            </div>
          <?php } ?>
        <?php } else { ?>
          <div class="oneTagCon" >
            <div class="alert alert-info">
              <label>标签名称:</label>
              <input type="text" class="require input-xlarge form-control" name="tag_name" value="">
              <label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;排序:</label>
              <input type="text" class="require input-mini form-control" name="tag_sort" value="">(越小越靠前)
              <button type="button" class="btn btn-default btn-sm btnHideTag up" style="float:right;"><span>收起标签</span> <i class="glyphicon glyphicon-arrow-down"></i></button>
              <button type="button" class="btn btn-danger btn-sm btnSaveTag" style="float:right;margin-right:10px;">保存标签信息 <i class="glyphicon glyphicon-ok"></i></button>
              <div class="btn-group btnAddModuleCon" style="float:right;margin-right:10px;display:none;">
                <button type="button" class="btn btn-success btn-sm dropdown-toggle" data-toggle="dropdown" aria-expanded="false">模块 <i class="glyphicon glyphicon-plus"></i> <span class="caret"></span></button>
                <ul class="dropdown-menu" role="menu">
                  <li><a href="javascript:;" class="btnAddModule" data-module_type="text">文字模块</a></li>
                  <li><a href="javascript:;" class="btnAddModule" data-module_type="img">图片模块</a></li>
                </ul>
              </div>
            </div>
            <div class="tagModuleCon">
              <div class="alert alert-danger tagModuleConTips" style="margin: 5px;display:block;">注意：只有保存标签信息才可以添加模块哦~</div>
            </div>
          </div>
        <?php }?>
        </div>
      </div>
      
      
      
      
    </div>
  </div>
  
  <div class="appendCon" style="display:none">
    <div class="aappendOneTagCon">
      <div class="oneTagCon" >
        <div class="alert alert-info">
          <label>标签名称:</label>
          <input type="text" class="require input-xlarge form-control" name="tag_name" value="">
          <label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;排序:</label>
          <input type="text" class="require input-mini form-control" name="tag_sort" value="0">(越小越靠前)
          
          <button type="button" class="btn btn-default btn-sm btnHideTag up" style="float:right;"><span>收起标签</span> <i class="glyphicon glyphicon-arrow-down"></i></button>
          <button type="button" class="btn btn-danger btn-sm btnSaveTag" style="float:right;margin-right:10px;">保存标签信息 <i class="glyphicon glyphicon-ok"></i></button>
          <div class="btn-group btnAddModuleCon" style="float:right;margin-right:10px;display:none;">
            <button type="button" class="btn btn-success btn-sm dropdown-toggle" data-toggle="dropdown" aria-expanded="false">模块 <i class="glyphicon glyphicon-plus"></i> <span class="caret"></span></button>
            <ul class="dropdown-menu" role="menu">
              <li><a href="javascript:;" class="btnAddModule" data-module_type="text">文字模块</a></li>
              <li><a href="javascript:;" class="btnAddModule" data-module_type="img">图片模块</a></li>
            </ul>
          </div>
        </div>
        
        <div class="tagModuleCon">
          <div class="alert alert-danger tagModuleConTips" style="margin: 5px;display:block;">注意：只有保存标签信息才可以添加模块哦~</div>
        </div>
      </div>
    </div>
    <div class="aappendTextAddCon">
      <div class="add-con textAddCon moduleAddCon" style="margin:5px 10px 0 10px;">
        <div class="add-con-header">
          <label>模块名称:</label>
          <input type="text" class="require input-xlarge form-control" name="module_name" value="">
          <label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;排序:</label>
          <input type="text" class="require input-mini form-control" name="module_sort" value="0">(越小越靠前)
          <button type="button" class="btn btn-default btn-sm btnHideModule up" style="float:right;"><span>收起模块</span> <i class="glyphicon glyphicon-arrow-down"></i></button>
          <button type="button" class="btn btn-danger btn-sm btnSaveModule" style="float:right;margin-right:10px;">保存模块信息 <i class="glyphicon glyphicon-ok"></i></button>
        </div>
        <div class="add-con-body">
          <div class="alert alert-danger moduleGoodsAllConTips" style="display:block">注意：只有保存模块信息才可以添加商品哦~</div>
          <div class="moduleGoodsAllCon" style="display:none;">
              <div class="module-goods-con-header moduleGoodsConHeader">
                <label>添加商品：</label>
                <input type="text" class="require input-xxlarge form-control" name="add_goods" style="height:30px;" placeholder="多个id用,分隔">
                
                <button class="btn btn-primary btn-sm btnAddModuleGoods" type="button" data-shopId="0">添加</button>
                <button class="btn btn-primary btn-sm btnSaveEventGoodsSort" type="button">保存排序</button>
              </div>
              <div class="module-goods-con-body moduleGoodsConBody" style="padding-top:10px;">
                <ul class="weekessence rec_sku moduleGoodsCon">
                </ul>
              </div>
          </div>
        </div>
        <input type="hidden" name="module_type" value="text">
      </div>
    </div>
    <div class="aappendImgAddCon">
      <div class="add-con imgAddCon moduleAddCon" style="margin:5px 10px 0 10px;">
        <div class="add-con-header">
          <label>模块图片:</label>
          <div class="imgbackrgound imgCon" style="height: 100px;width: 210px;line-height:100px;">
            请上传 640*340的图片
          </div>
          <span class="btn btn-default upload-img btnUploadImg">
            <input type="file" class="uploadImgInput" name="uploaod_img" id="module_img_uploaod" onchange="javascript:bannerUpload('module_img_uploaod', 'module_img',$(this).parent().prev())">上传
          </span>
          <label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;排序:</label>
          <input type="text" class="require input-mini form-control" name="module_sort" value="0">(越小越靠前)
          <button type="button" class="btn btn-default btn-sm btnHideModule up" style="float:right;"><span>收起模块</span> <i class="glyphicon glyphicon-arrow-down"></i></button>
          <button type="button" class="btn btn-danger btn-sm btnSaveModule" style="float:right;margin-right:10px;">保存模块信息 <i class="glyphicon glyphicon-ok"></i></button>
        </div>
        <div class="add-con-body">
          <div class="alert alert-danger moduleGoodsAllConTips" style="display:block;">注意：只有保存模块信息才可以添加商品哦~</div>
          <div class="moduleGoodsAllCon" style="display:none;">
              <div class="module-goods-con-header moduleGoodsConHeader">
                <label>添加商品：</label>
                <input type="text" class="require input-xxlarge form-control" name="add_goods" style="height:30px;" placeholder="多个id用,分隔">
                
                <button class="btn btn-primary btn-sm btnAddModuleGoods" type="button" data-shopId="0">添加</button>
                <button class="btn btn-primary btn-sm btnSaveEventGoodsSort" type="button">保存排序</button>
              </div>
              <div class="module-goods-con-body moduleGoodsConBody" style="padding-top:10px;">
                <ul class="weekessence rec_sku moduleGoodsCon">
                </ul>
              </div>
          </div>
        </div>
        <input type="hidden" name="module_type" value="img">
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
  /** 添加标签 */
  $(".btnAddTag").click(function(){
    $(this).closest(".control-group").find(".allTagCon").append($(".appendCon").find(".aappendOneTagCon").html());
    var conList = $(this).closest(".control-group").find(".oneTagCon");
    var nowlength = conList.length - 1;
    var obj = $(conList[nowlength]);
    obj.find("input[name='tag_name']").focus();
  });
  
  /** 保存标签 */
  $(".allTagCon").on("click", ".btnSaveTag", function(){
    if ($(this).hasClass("disabled")) return false;
    var thisObj    = $(this);
    var eventId    = $("#eventId").val();
    var tagObj    = thisObj.closest(".oneTagCon");
    var tagId     = tagObj.find("input[name='tag_id']").val();
    var tagName   = tagObj.find("input[name='tag_name']").val();
    var tagSort   = tagObj.find("input[name='tag_sort']").val();
    
    if (!tagName) {
      alert('请填写标签名称');
      return false;
    }
    
    var postData = {'event_id':eventId, 'tag_name':tagName, 'tag_sort':tagSort}
    if (tagId != 'undefined') {
      postData['tag_id'] = tagId;
    }
    //console.log(postData);return false;
    var objInHtml = thisObj.html();
    setBtnStatus(thisObj, '提交中...', 'disabled');
    
    $.post('/event/saveSupriseTag', postData, function(json){
      console.log(json)
      if (json.succ == 1) {
        // 设置shopId
        if (tagObj.find("input[name='tag_id']").length >= 1) {
          tagObj.find("input[name='tag_id']").val(json.tag_id);
        } else {
          var tagIdCon = '<input type="hidden" name="tag_id" value="'+json.tag_id+'">';
          tagObj.append(tagIdCon);
        }
        
        tagObj.find(".tagModuleConTips").remove();
        tagObj.find(".btnAddModuleCon").show();
      } else {
        alert(json.msg);
      }
      setBtnStatus(thisObj, objInHtml, 'succ');
    }, 'json').error(function(code, data){
      alert('服务器繁忙,请稍后再试');
      setBtnStatus(thisObj, objInHtml, 'succ');
    });
  });
  
  /** 添加模块 */
  $(".allTagCon").on("click", ".btnAddModule", function(e){
    e.preventDefault();
    var moduleType = $(this).attr("data-module_type");
    var conObj = $(this).closest(".oneTagCon").find(".tagModuleCon");
    
    if (moduleType == 'text') {
      conObj.append($(".appendCon").find(".aappendTextAddCon").html());
    } else if (moduleType == 'img') {
      conObj.append($(".appendCon").find(".aappendImgAddCon").html());
    }
    var conList = conObj.find(".moduleAddCon");
    var nowlength = conList.length - 1;
    var obj = $(conList[nowlength]);
    if (moduleType == 'img') {
      imgLength = $(".imgAddCon").length - 1;
      var inHtml = '<input type="file" class="uploadImgInput" name="uploaod_img" id="module_img_uploaod_'+imgLength+'" onchange="javascript:bannerUpload(\'module_img_uploaod_'+imgLength+'\', \'module_img\', $(this).parent().prev())">上传';
      obj.find(".btnUploadImg").html(inHtml);
    }
    obj.find("input[name='module_sort']").focus();
    
    // 排序
    // new Sortable(obj.find(".moduleGoodsCon")[0]);
    new Sortable(obj.find(".moduleGoodsCon")[0], {
      handle: 'img'
    });
  });
  
  /** 保存模块 */
  $(".allTagCon").on("click", ".btnSaveModule", function(){
    if ($(this).hasClass("disabled")) return false;
    var thisObj    = $(this);
    var tagObj     = thisObj.closest(".oneTagCon");
    var moduleObj  = thisObj.closest(".moduleAddCon");
    var eventId    = $("#eventId").val();
    var tagId      = tagObj.find("input[name='tag_id']").val();
    
    var moduleId   = moduleObj.find("input[name='module_id']").val();
    var moduleName = moduleObj.find("input[name='module_name']").val();
    var moduleImg  = moduleObj.find("input[name='module_img']").val();
    var moduleSort = moduleObj.find("input[name='module_sort']").val();
    var moduleType = moduleObj.find("input[name='module_type']").val();
    
    if (tagId == 'undefined') {
      alert('请先保存标签吧~');
      return false;
    }
    
    var postData = {'event_id':eventId, 'tag_id':tagId, 'module_name':moduleName, 'module_img':moduleImg, 'module_sort':moduleSort, 'module_type':moduleType};
    if (moduleId != 'undefined') {
      postData['module_id'] = moduleId;
    }
    if (moduleName == 'undefined') {
      postData['module_name'] = '';
    }
    if (moduleImg == 'undefined') {
      postData['module_img'] = '';
    }
    
    // console.log(postData);return false;
    var objInHtml = thisObj.html();
    setBtnStatus(thisObj, '提交中...', 'disabled');
    
    $.post('/event/saveSupriseModule', postData, function(json){
      console.log(json)
      if (json.succ == 1) {
        // 设置shopId
        if (moduleObj.find("input[name='module_id']").length >= 1) {
          moduleObj.find("input[name='module_id']").val(json.module_id);
        } else {
          var moduleIdCon = '<input type="hidden" name="module_id" value="'+json.module_id+'">';
          moduleObj.append(moduleIdCon);
        }
        
        moduleObj.find(".moduleGoodsAllConTips").hide();
        moduleObj.find(".moduleGoodsAllCon").show();
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
  $(".allTagCon").on("click", ".btnAddModuleGoods", function(){
    if ($(this).hasClass("disabled")) return false;
    var thisObj = $(this);
    var moduleObj = thisObj.closest(".moduleAddCon");
    var tagObj    = thisObj.closest(".oneTagCon");
    
    var twitterId = thisObj.siblings("input[name='add_goods']").val();
    var eventId   = $("#eventId").val();
    var tagId     = tagObj.find("input[name='tag_id']").val();
    var moduleId  = moduleObj.find("input[name='module_id']").val();
    // var repertory = thisObj.siblings("input[name='add_goods_repertory']").val();
    
    if (!twitterId) {
      alert('请输入twitter_id');
      return false;
    }
    if (!eventId) {
      alert('活动信息不存在');
      return false;
    }
    if (!tagId) {
      alert('标签id不存在');
      return false;
    }
    if (!moduleId) {
      alert('模块id不存在');
      return false;
    }
    
    /*if (!repertory) {
      alert('请输入库存');
      return false;
    }*/
    
    var objInHtml = thisObj.html();
    setBtnStatus(thisObj, '提交中...', 'disabled');
    $.post('/eventGoods/saveEventGoods', {'twitter_id':twitterId, 'event_id':eventId, 'area_id':tagId, 'area_sub':moduleId}, function(json){
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
          thisObj.closest(".moduleGoodsAllCon").find(".moduleGoodsCon").append(inHtml);
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
  
  
  /** 隐藏显示模块 */
  $(".allTagCon").on("click", ".btnHideModule", function(){
    if ($(this).hasClass("up")) {
      $(this).find("span").html("显示店铺");
      $(this).find(".glyphicon").addClass("glyphicon-arrow-up").removeClass("glyphicon-arrow-down");
      $(this).removeClass("up").addClass("down");
      $(this).closest(".moduleAddCon").find(".add-con-body").stop().slideUp();
    } else {
      $(this).find("span").html("隐藏店铺");
      $(this).find(".glyphicon").addClass("glyphicon-arrow-down").removeClass("glyphicon-arrow-up");
      $(this).removeClass("down").addClass("up");
      $(this).closest(".moduleAddCon").find(".add-con-body").stop().slideDown();
    }
  });
  
  /** 隐藏显示标签 */
  $(".allTagCon").on("click", ".btnHideTag", function(){
    if ($(this).hasClass("up")) {
      $(this).find("span").html("显示标签");
      $(this).find(".glyphicon").addClass("glyphicon-arrow-up").removeClass("glyphicon-arrow-down");
      $(this).removeClass("up").addClass("down");
      $(this).closest(".oneTagCon").find(".tagModuleCon").stop().slideUp();
    } else {
      $(this).find("span").html("隐藏店铺");
      $(this).find(".glyphicon").addClass("glyphicon-arrow-down").removeClass("glyphicon-arrow-up");
      $(this).removeClass("down").addClass("up");
      $(this).closest(".oneTagCon").find(".tagModuleCon").stop().slideDown();
    }
  });
  
  /** 排序 */
  $.each($(".allTagCon").find(".moduleGoodsCon"), function(k, v){
    // @fixme 请注意，这里要取第一个
    // new Sortable($(v)[0]);
    new Sortable($(v)[0], {
      handle: 'img'
    });
  });
  
  /** 保存排序字段 */
  $(".allTagCon").on("click", ".btnSaveEventGoodsSort", function(){
    if ($(this).hasClass("disabled")) return false;
    var thisObj = $(this);
    var moduleObj = thisObj.closest(".moduleAddCon");
    var tagObj    = thisObj.closest(".oneTagCon");
    var eventId   = $("#eventId").val();
    var areaId    = tagObj.find("input[name='tag_id']").val();
    var areaSub   = moduleObj.find("input[name='module_id']").val();
    
    
    var ids = [];
    moduleObj.find(".shopGoodsList").each(function(){
        var recommendId = $(this).attr("data-Id");
        if (recommendId) {
            ids.push(recommendId);
        }
    });
    if (ids.length < 1) return false;
    
    idsStr = ids.join(",");
    
    //console.log(idsStr);return;
    
    var objInHtml = thisObj.html();
    setBtnStatus(thisObj, '提交中...', 'disabled');
    $.post('/event/saveEventGoodsSort', {'ids':idsStr, 'area':areaId, 'event_id':eventId, 'area_sub':areaSub}, function(json){
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
  
  /** 商品删除 {直接操作为排期失败} */
  $(".allTagCon").on("click", ".deleteGoods", function(e){
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
