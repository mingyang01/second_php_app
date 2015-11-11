<?php /* Smarty version Smarty-3.1.18, created on 2015-09-21 19:18:01
         compiled from "/home/work/websites/tuan/protected/views/activity/edit.tpl" */ ?>
<?php /*%%SmartyHeaderCode:97057488455cd5ea20ff0d9-04771740%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '903c9efcce342398e6bec4d044ba2e131123a22a' => 
    array (
      0 => '/home/work/websites/tuan/protected/views/activity/edit.tpl',
      1 => 1440565517,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '97057488455cd5ea20ff0d9-04771740',
  'function' => 
  array (
  ),
  'version' => 'Smarty-3.1.18',
  'unifunc' => 'content_55cd5ea218cdd3_31000846',
  'variables' => 
  array (
    'event_id' => 0,
    'eventInfo' => 0,
    'area' => 0,
    'item' => 0,
  ),
  'has_nocache_code' => false,
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_55cd5ea218cdd3_31000846')) {function content_55cd5ea218cdd3_31000846($_smarty_tpl) {?><?php echo $_smarty_tpl->getSubTemplate ("layouts/header.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, null, array(), 0);?>

<script src="/assets/lib/imgAjaxfileUp.js"></script>
<script src='/assets/lib/js/Sortable.js'></script>
<script type="text/javascript" src="/assets/lib/bootstrap-datepicker.js"></script>
<script src="/assets/lib/My97DatePicker/WdatePicker.js"></script>
<style>
blockquote {
    font-family: "微软雅黑";
    border-left: 5px solid #84B2F8;
    margin: 20px 0 5px 0;
    padding: 0px 10px;
    border-left: 5px solid #7687EA;
    background: #ccc;
}
img{
    border:1px solid #84B2F8;
}
.goods-photos {
  float:left;
}

.upload-img {
    position: relative;
    cursor: pointer;
    overflow: hidden;
    display: inline-block;
    *display: inline;
    *zoom: 1
}

#banner_mob  input {
    position: absolute;
    font-size: 100px;
    right: 0;
    top: 0;
    opacity: 0;
    filter: alpha(opacity=0);
    cursor: pointer;
    width:640px;
    height:340px;
}
#banner_pc  input {
    position: absolute;
    font-size: 100px;
    right: 0;
    top: 0;
    opacity: 0;
    filter: alpha(opacity=0);
    cursor: pointer;
    width:750px;
    height:150px;
}
.imgbackrgound{
    position: absolute;
}
#banner_mob{
    opacity: 0;
    filter: alpha(opacity=0);
    position: absolute;
    width:640px;
    height:340px;
}
#banner_pc{
    opacity: 0;
    filter: alpha(opacity=0);
    position: absolute;
    width:750px;
    height:150px;
}
#share_img{
    opacity: 0;
    filter: alpha(opacity=0);
    position: absolute;
    width:200px;
    height:200px;
}

#share_img  input {
    position: absolute;
    font-size: 100px;
    right: 0;
    top: 0;
    opacity: 0;
    filter: alpha(opacity=0);
    cursor: pointer;
    width:200px;
    height:200px;
}
</style>
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <ol class="breadcrumb">
                <li><a href="/">Home</a></li>
                <li><a href="/event">主题活动</a></li>
                <li class="active">活动信息修改</li>
            </ol>
        </div>
        <div class="col-md-12">
            <div class="alert alert-warning">
                主题活动修改还在迁移过程中，如果有不满的地方请在老的works进行
                <a href="http://works.meiliworks.com/tuanht/edit_event?event_id=<?php echo $_smarty_tpl->tpl_vars['event_id']->value;?>
">点我去works</a>
            </div>
        </div>
    </div>

    
    <div class="row">
        <div class="col-md-12">
            <div class="well clearfix">
                <div class="form-group">
                    <label class="col-md-1 control-label" style="padding-top:7px;">活动id</label>
                    <div class="col-md-3" >
                        <input id="event_id" type="hidden" type="text" value="<?php echo $_smarty_tpl->tpl_vars['event_id']->value;?>
">
                        <input id="input-event-id" type="text" class="form-control" value="<?php echo $_smarty_tpl->tpl_vars['eventInfo']->value['event_id'];?>
">
                    </div>
                    <div class="col-md-2">
                        <button id="btn-views" data-eventid="<?php echo $_smarty_tpl->tpl_vars['eventInfo']->value['event_id'];?>
" class="btn btn-success ">
                            查看
                        </button>
                    </div>
                    <?php if (!$_smarty_tpl->tpl_vars['eventInfo']->value['event_id']) {?>
                    <div class="col-md-6">
                        <div class="alert alert-danger">
                            貌似没有活动id为<?php echo $_smarty_tpl->tpl_vars['event_id']->value;?>
的活动呢！
                        </div>
                    </div>
                    <?php }?>
                </div>
            </div>
        </div>
    </div>
    
    <div class="row">
        <div class="col-md-12 form-inline">
            <div class="well clearfix">
                <div class="col-md-12" style="margin-bottom:20px;">
                    <h4>活动的基本信息 </h4>
                    <div class="alert alert-info">
                        活动时间&nbsp;&nbsp;<?php echo $_smarty_tpl->tpl_vars['eventInfo']->value['start_time'];?>
--<?php echo $_smarty_tpl->tpl_vars['eventInfo']->value['end_time'];?>

                    </div>
                </div>
                <div class="form-group">
                    <label class="col-md-3 control-label" style="padding-top:7px;">报名起止时间</label>
                    <div class="col-md-5" >
                        <input id="start_time" type="text" class="form-control myDatePickerHMS time-box" value="<?php echo $_smarty_tpl->tpl_vars['eventInfo']->value['join_start_time'];?>
">
                    </div>
                    <div class="col-md-4" >
                        <input id="end_time" type="text" class="form-control myDatePickerHMS time-box" value="<?php echo $_smarty_tpl->tpl_vars['eventInfo']->value['join_end_time'];?>
">
                    </div>
                </div>
                <div class="form-group">
                    <select name="" id="join_status" class="form-control">
                        <option <?php if ($_smarty_tpl->tpl_vars['eventInfo']->value['join_status']==0) {?>selected <?php }?>value="0">可报名</option>
                        <option <?php if ($_smarty_tpl->tpl_vars['eventInfo']->value['join_status']==1) {?>selected <?php }?> value="1">不可报名</option>
                    </select>
                </div>
                <div class="form-group">
                    <button class="btn btn-success save-event-time">保存时间</button>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <ul class="nav nav-tabs">
                <li role="presentation" class="active"><a href="#picture" aria-controls="picture" role="tab" data-toggle="tab">图片修改</a></li>
                <li role="presentation" ><a <a href="#area" aria-controls="area" role="tab" data-toggle="tab">区域修改</a></li>
            </ul>
        </div>
    </div>
</div>

<div class="tab-content">
    <!-- 修改活动名称，标题 banner图片等 -->
    <div role="tabpanel" class="tab-pane active" id="picture">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="control-group" style="width:270px;">
                        <blockquote><p>活动名称<small>必选</small></p></blockquote>
                        <input type="text" class="require input-xlarge form-control" name="event_name" id="event_name" value="<?php echo $_smarty_tpl->tpl_vars['eventInfo']->value['event_name'];?>
">
                        <span class="help-inline"></span>
                    </div>
                    
                    <!-- 活动标题 -->
                    <div class="control-group" style="width:270px;">
                        <blockquote><p>活动标题<small>选填</small></p></blockquote>
                        <input type="text" id="event_title" class="require input-xlarge form-control" name="title" id="title" value="<?php echo $_smarty_tpl->tpl_vars['eventInfo']->value['title'];?>
">
                        <span class="help-inline"></span>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <blockquote style="width:750px">
                        <p>顶部图片-PC</p>
                    </blockquote>
                </div>
                
                <div class="col-md-12" style="width:750px">
                    <div class="imgbackrgound imgCon" style="height: 150px;width: 750px;line-height:150px;">
                        <?php if ($_smarty_tpl->tpl_vars['eventInfo']->value['banner_pc']) {?>
                        <img  src="<?php echo getImageUrl($_smarty_tpl->tpl_vars['eventInfo']->value['banner_pc']);?>
" width=750 height=150 ></a>
                        <input  type="hidden" name="top_banner_pc" value="<?php echo $_smarty_tpl->tpl_vars['eventInfo']->value['banner_pc'];?>
">
                        <?php } else { ?>
                            请上传 2000*340的图片
                        <?php }?>
                    </div>
                    <span id="banner_pc" class="btn btn-default upload-img btnUploadImg">
                        <input type="file"  class="uploadImgInput" name="uploaod_img" id="top_banner_pc_uploaod" onchange="javascript:bannerUpload('top_banner_pc_uploaod', 'top_banner_pc',$(this).parent().prev())">上传
                    </span>
                </div>
                
                <div class="col-md-12" style="margin-top:150px;">
                    <blockquote style="width:640px">
                        <p>顶部图片-MOB</p>
                    </blockquote>
                </div>

                <div class="col-md-12" style="width:640px;height:340px;">
                    <div class="imgbackrgound imgCon" style="height: 340px;width: 640px;line-height:340px;">
                        <?php if ($_smarty_tpl->tpl_vars['eventInfo']->value['banner_mob']) {?>
                        <img  src="<?php ob_start();?><?php echo $_smarty_tpl->tpl_vars['eventInfo']->value['banner_mob'];?>
<?php $_tmp1=ob_get_clean();?><?php echo Yii::app()->image->getWebsiteImageUrl($_tmp1);?>
" width=640 height=340 ></a>
                        <input  type="hidden" name="top_banner_mob" value="<?php echo $_smarty_tpl->tpl_vars['eventInfo']->value['banner_mob'];?>
">
                        <?php } else { ?>
                            请上传 640*340的图片
                        <?php }?>
                    </div>
                    <span id="banner_mob" class="btn btn-default upload-img btnUploadImg">
                        <input type="file" class="uploadImgInput" name="uploaod_img" id="goods_img_uploaod" onchange="javascript:bannerUpload('goods_img_uploaod', 'top_banner_mob', $(this).parent().prev())">上传
                    </span>
                    <span class="help-inline"></span>
                </div>

                <div class="col-md-12" style="margin-top:10px;">
                    <blockquote style="width:200px">
                        <p>分享图片</p>
                    </blockquote>
                </div>

                <div class="col-md-12" style="width:200px;height:200px;">
                    <div class="imgbackrgound imgCon" style="height: 200px;width: 200px;line-height:200px;">
                        <?php if ($_smarty_tpl->tpl_vars['eventInfo']->value['detail']['share']['shareImage']) {?>
                        <img  src="<?php ob_start();?><?php echo $_smarty_tpl->tpl_vars['eventInfo']->value['detail']['share']['shareImage'];?>
<?php $_tmp2=ob_get_clean();?><?php echo Yii::app()->image->getWebsiteImageUrl($_tmp2);?>
" width=200 height=200 ></a>
                        <input  type="hidden" name="share_img" value="<?php echo $_smarty_tpl->tpl_vars['eventInfo']->value['detail']['share']['shareImage'];?>
">
                        <?php } else { ?>
                            请上传 200*200的图片
                        <?php }?>
                    </div>
                    <span id="share_img" class="btn btn-default upload-img btnUploadImg">
                        <input type="file" class="uploadImgInput" name="uploaod_img" id="share_img_upload" onchange="javascript:bannerUpload('share_img_upload', 'share_img', $(this).parent().prev())">上传
                    </span>
                    <span class="help-inline"></span>
                </div>
                <div class="col-md-12" style="margin-top:10px;">
                    <blockquote style="width:200px">
                        <p>分享文字描述</p>
                    </blockquote>
                </div>
                <div class="col-md-12" style="margin-top:10px;">
                    <input id="shareText" name="shareText" type="text" style="width:200px;" class="form-control" value="<?php echo $_smarty_tpl->tpl_vars['eventInfo']->value['detail']['share']['shareText'];?>
">
                </div>
            </div>
            
            <div class="row" style="margin-top:10px;margin-bottom:100px;">
                <div class="col-md-12">
                    <button id='save-btn' class="btn btn-success btn-block">保存活动信息</button>
                </div>
            </div>
        </div>
    </div>
    <?php if ($_smarty_tpl->tpl_vars['eventInfo']->value['detail']) {?>
        <?php $_smarty_tpl->tpl_vars['area'] = new Smarty_variable($_smarty_tpl->tpl_vars['eventInfo']->value['detail']['right_nav_info']['right_nav'], null, 0);?>
    <?php }?>
    <div role="tabpanel" class="tab-pane" id="area">
        <div class="container">
            <div id="box-contailer" class="row main">
                <?php if ($_smarty_tpl->tpl_vars['area']->value) {?>
                <?php  $_smarty_tpl->tpl_vars['item'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['item']->_loop = false;
 $_smarty_tpl->tpl_vars['key'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['area']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['item']->key => $_smarty_tpl->tpl_vars['item']->value) {
$_smarty_tpl->tpl_vars['item']->_loop = true;
 $_smarty_tpl->tpl_vars['key']->value = $_smarty_tpl->tpl_vars['item']->key;
?>
                <div class="col-md-6 area-content" style="margin-top:20px;padding-right:0px;">
                    <div class="panel panel-info" >
                        <div class="panel-heading clearfix"  style="padding-right:0px;">
                            <div class="col-md-4 name-box">
                                区域设置---<?php echo $_smarty_tpl->tpl_vars['item']->value['name'];?>

                            </div>
                            <div class="col-md-8 text-right">
                                <button class="btn btn-sm btn-default add-btn">
                                    <i class="glyphicon glyphicon-plus "></i>
                                </button>
                                <button class="btn btn-sm btn-default delete-btn">
                                    <i class="glyphicon glyphicon-minus"></i>
                                </button>
                                <button class="btn btn-sm btn-default ">
                                    <i class="glyphicon glyphicon-resize-small resize-btn"></i>
                                </button>
                            </div>
                        </div>
                        <div class="panel-body">
                            <table class="table">
                                 <tr>
                                    <td class="col-md-3"><h4>导航名称</h4></td>
                                    <td class="col-md-9"><input name="name" type="text" class="form-control" value="<?php echo $_smarty_tpl->tpl_vars['item']->value['name'];?>
"></td>
                                </tr>
                                <tr>
                                    <td class="col-md-3"><h4>导航链接</h4></td>
                                    <td class="col-md-9"><input name="link" type="text" class="form-control" value="<?php echo $_smarty_tpl->tpl_vars['item']->value['link'];?>
"></td>
                                </tr>
                                <tr>
                                    <td class="col-md-3"><h4>导航高度</h4></td>
                                    <td class="col-md-9"><input name="height" type="text" class="form-control" value="<?php echo $_smarty_tpl->tpl_vars['item']->value['height'];?>
"></td>
                                </tr>
                                <tr>
                                    <td class="col-md-3"><h4>新窗口打开?</h4></td>
                                    <td class="col-md-9">
                                        <select name='is_target' type="text" class="form-control">
                                            <option value="0">否</option>
                                            <option <?php if ($_smarty_tpl->tpl_vars['item']->value['height']) {?>selected<?php }?> value="1">是</option>
                                        </select>
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
                <?php } ?>
                <?php } else { ?>
                    <div class="col-md-6 area-content" style="margin-top:20px;padding-right:0px;">
                    <div class="panel panel-info" >
                        <div class="panel-heading clearfix"  style="padding-right:0px;">
                            <div class="col-md-4 name-box">
                                区域设置---<?php echo $_smarty_tpl->tpl_vars['item']->value['name'];?>

                            </div>
                            <div class="col-md-8 text-right">
                                <button class="btn btn-sm btn-default add-btn">
                                    <i class="glyphicon glyphicon-plus "></i>
                                </button>
                                <button class="btn btn-sm btn-default delete-btn">
                                    <i class="glyphicon glyphicon-minus"></i>
                                </button>
                                <button class="btn btn-sm btn-default ">
                                    <i class="glyphicon glyphicon-resize-small resize-btn"></i>
                                </button>
                            </div>
                        </div>
                        <div class="panel-body">
                            <table class="table">
                                 <tr>
                                    <td class="col-md-3"><h4>导航名称</h4></td>
                                    <td class="col-md-9"><input name="name" type="text" class="form-control" value=""></td>
                                </tr>
                                <tr>
                                    <td class="col-md-3"><h4>导航链接</h4></td>
                                    <td class="col-md-9"><input name="link" type="text" class="form-control" value="#pgtop"></td>
                                </tr>
                                <tr>
                                    <td class="col-md-3"><h4>导航高度</h4></td>
                                    <td class="col-md-9"><input name="height" type="text" class="form-control" value=""></td>
                                </tr>
                                <tr>
                                    <td class="col-md-3"><h4>新窗口打开?</h4></td>
                                    <td class="col-md-9">
                                        <select name='is_target' type="text" class="form-control">
                                            <option value="0">否</option>
                                            <option <?php if ($_smarty_tpl->tpl_vars['item']->value['height']) {?>selected<?php }?> value="1">是</option>
                                        </select>
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
                <?php }?>
                <div class="col-md-12" style="margin-top:20px;margin-bottom:50px;">
                    <button class="btn btn-success btn-block save-area-info">保存</button>
                </div>
            </div>
        </div>
    </div>
</div>

<div id='modal' class="modal fade bs-example-modal-sm" role="dialog" aria-labelledby="mySmallModalLabel">
  <div class="modal-dialog modal-sm">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title" id="myModalLabel">提示</h4>
        </div>
        <div class="modal-body">
            <div class="alert alert-warning">
                你确定要删除这个区域么?并且删除后别忘了保存啊！
            </div>
        </div>
        <div class="modal-footer">
        <button type="button" data-flag="false" class="btn btn-default submite-btn" data-dismiss="modal">我再想想</button>
        <button id="submit-area" type="button" data-flag="true" class="btn btn-primary submite-btn">我很确定</button>
        </div>
    </div>
  </div>
</div>
<script src="/assets/js/activity/activity.js"></script><?php }} ?>
