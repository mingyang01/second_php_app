{/include file="layouts/header.tpl"/}
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
.class-time {
	width: auto;
	background-color: #fff;
    background-image: none;
    border: 1px solid #ccc;
    border-radius: 4px;
    box-shadow: 0 1px 1px rgba(0, 0, 0, 0.075) inset;
    color: #555;
    font-size: 14px;
    height: 34px;
    line-height: 1.42857;
    padding: 6px 12px;
    transition: border-color 0.15s ease-in-out 0s, box-shadow 0.15s ease-in-out 0s;
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
                <a href="http://works.meiliworks.com/tuanht/edit_event?event_id={/$event_id/}">点我去works</a>
            </div>
        </div>
    </div>

    
    <div class="row">
        <div class="col-md-12">
            <div class="well clearfix">
                <div class="form-group">
                    <label class="col-md-1 control-label" style="padding-top:7px;">活动id</label>
                    <div class="col-md-3" >
                        <input id="event_id" type="hidden" type="text" value="{/$event_id/}">
                        <input id="input-event-id" type="text" class="form-control" value="{/$eventInfo.event_id/}">
                    </div>
                    <div class="col-md-2">
                        <button id="btn-views" data-eventid="{/$eventInfo.event_id/}" class="btn btn-success ">
                            查看
                        </button>
                    </div>
                    {/if !$eventInfo.event_id/}
                    <div class="col-md-6">
                        <div class="alert alert-danger">
                            貌似没有活动id为{/$event_id/}的活动呢！
                        </div>
                    </div>
                    {//if/}
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
                        活动时间&nbsp;&nbsp;{/$eventInfo.start_time/}--{/$eventInfo.end_time/}
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-md-3 control-label" style="padding-top:7px;">报名起止时间</label>
                    <div class="col-md-5" >
                        <input id="start_time" type="text" class="form-control myDatePickerHMS time-box" value="{/$eventInfo.join_start_time/}">
                    </div>
                    <div class="col-md-4" >
                        <input id="end_time" type="text" class="form-control myDatePickerHMS time-box" value="{/$eventInfo.join_end_time/}">
                    </div>
                </div>
                <div class="form-group">
                    <select name="" id="join_status" class="form-control">
                        <option {/if $eventInfo.join_status==0/}selected {//if/}value="0">可报名</option>
                        <option {/if $eventInfo.join_status==1/}selected {//if/} value="1">不可报名</option>
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
                        <input type="text" class="require input-xlarge form-control" name="event_name" id="event_name" value="{/$eventInfo['event_name']/}">
                        <span class="help-inline"></span>
                    </div>
                    
                    <!-- 活动标题 -->
                    <div class="control-group" style="width:270px;">
                        <blockquote><p>活动标题<small>选填</small></p></blockquote>
                        <input type="text" id="event_title" class="require input-xlarge form-control" name="title" id="title" value="{/$eventInfo['title']/}">
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
                        {/if $eventInfo.banner_pc/}
                        <img  src="{/getImageUrl($eventInfo.banner_pc)/}" width=750 height=150 ></a>
                        <input  type="hidden" name="top_banner_pc" value="{/$eventInfo.banner_pc/}">
                        {/else/}
                            请上传 2000*340的图片
                        {//if/}
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
                        {/if $eventInfo.banner_mob/}
                        <img  src="{/Yii::app()->image->getWebsiteImageUrl({/$eventInfo.banner_mob/})/}" width=640 height=340 ></a>
                        <input  type="hidden" name="top_banner_mob" value="{/$eventInfo.banner_mob/}">
                        {/else/}
                            请上传 640*340的图片
                        {//if/}
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
                        {/if $eventInfo.detail.share.shareImage/}
                        <img  src="{/Yii::app()->image->getWebsiteImageUrl({/$eventInfo.detail.share.shareImage/})/}" width=200 height=200 ></a>
                        <input  type="hidden" name="share_img" value="{/$eventInfo.detail.share.shareImage/}">
                        {/else/}
                            请上传 200*200的图片
                        {//if/}
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
                    <input id="shareText" name="shareText" type="text" style="width:200px;" class="form-control" value="{/$eventInfo.detail.share.shareText/}">
                </div>
            </div>
            
            <div class="row" style="margin-top:10px;margin-bottom:100px;">
                <div class="col-md-12">
                    <button id='save-btn' class="btn btn-success btn-block">保存活动信息</button>
                </div>
            </div>
        </div>
    </div>
    {/if $eventInfo.detail/}
        {/assign var=area value=$eventInfo.detail.right_nav_info.right_nav/}
    {//if/}
    <div role="tabpanel" class="tab-pane" id="area">
        <div class="container">
            <div id="box-contailer" class="row main">
                {/if $area/}
                {/foreach from=$area key=key item=item /}
                <div class="col-md-6 area-content" style="margin-top:20px;padding-right:0px;">
                    <div class="panel panel-info" >
                        <div class="panel-heading clearfix"  style="padding-right:0px;">
                            <div class="col-md-4 name-box">
                                区域设置---{/$item.name/}
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
                                    <td class="col-md-9"><input name="name" type="text" class="form-control" value="{/$item.name/}"></td>
                                </tr>
                                <tr>
                                    <td class="col-md-3"><h4>导航链接</h4></td>
                                    <td class="col-md-9"><input name="link" type="text" class="form-control" value="{/$item.link/}" {/if {/$item.link/}/}disabled{//if/}></td>
                                </tr>
                                <tr>
                                    <td class="col-md-3"><h4>导航有效时间</h4></td>
                                    <td class="col-md-3"><input name="starttime" type="text" class="class-time myDatePickerHMS time-box" value="{/$item.starttime/}">-
                                    <input name="endtime" type="text" class="class-time myDatePickerHMS time-box" value="{/$item.endtime/}"></td>
                                </tr>
                                <tr>
                                    <td class="col-md-3"><h4>价格</h4></td>
                                    <td class="col-md-9"><input name="price" type="text" class="form-control" value="{/$item.price/}"></td>
                                </tr>
                                <tr>
                                    <td class="col-md-3"><h4>导航高度</h4></td>
                                    <td class="col-md-9"><input name="height" type="text" class="form-control" value="{/$item.height/}"></td>
                                </tr>
                                <tr>
                                    <td class="col-md-3"><h4>新窗口打开?</h4></td>
                                    <td class="col-md-9">
                                        <select name='is_target' type="text" class="form-control">
                                            <option value="0">否</option>
                                            <option {/if $item.height/}selected{//if/} value="1">是</option>
                                        </select>
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
                {//foreach/}
                {/else/}
                    <div class="col-md-6 area-content" style="margin-top:20px;padding-right:0px;">
                    <div class="panel panel-info" >
                        <div class="panel-heading clearfix"  style="padding-right:0px;">
                            <div class="col-md-4 name-box">
                                区域设置---{/$item.name/}
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
                                    <td class="col-md-3"><h4>导航有效时间</h4></td>
                                    <td class="col-md-3"><input name="starttime" type="text" class="class-time myDatePickerHMS time-box" value="{/$item.starttime/}">-
                                    <input name="endtime" type="text" class="class-time myDatePickerHMS time-box" value="{/$item.endtime/}"></td>
                                </tr>
                                <tr>
                                    <td class="col-md-3"><h4>条件</h4></td>
                                    <td>
                                        <select name='where_type' type="text" class="col-md-3">
                                            <option value="0">价格</option>
                                        </select>
                                        <input name="where_name" type="text" class="col-md-3" value="">
                                    </td>
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
                                            <option {/if $item.height/}selected{//if/} value="1">是</option>
                                        </select>
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
                {//if/}
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
<script src="/assets/js/activity/activity.js"></script>