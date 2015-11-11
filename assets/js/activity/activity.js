$(function(){
    var moveRecommend = document.getElementById("box-contailer");
        new Sortable(moveRecommend, {
        handle: '.area-content'
    });
    $('body').on('focus', '.myDatePickerHMS',function(e){
        WdatePicker({
            dateFmt:'yyyy-MM-dd HH:m:s',
        });
    });
    
})
$('.show-btn').click(function(){
    var parent= $(this).parent().parent().parent();
    parent.find('.content-box').toggle('fast');
    $(this).removeClass('glyphicon-resize-full');
    $(this).addClass('glyphicon-resize-small');
})
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
$('#btn-views').click(function(){
    var event_id = $('#input-event-id').val();
    window.location="/activity/edit?event_id="+event_id;
})
$('#save-btn').click(function(){
    var event_id = $('#event_id').val();
    var event_name = $('#event_name').val();
    var event_title = $('#event_title').val();
    var shareText = $('#shareText').val();
    var top_banner_pc = $('input[name="top_banner_pc"]').val();
    var top_banner_mob = $('input[name="top_banner_mob"]').val();
    var share_img = $('input[name="share_img"]').val();
    var params = {'event_id':event_id,'event_name':event_name,'title':event_title,'shareText':shareText,'top_banner_pc':top_banner_pc,'top_banner_mob':top_banner_mob,'share_img':share_img};
    $.post('/activity/SaveActivityInfo', params, function(data) {
        if(data.succ==1){
            window.location.reload();
        }
    },'json');
})
$('.resize-btn').click(function(){
    var flag = $(this).hasClass('glyphicon-resize-small');
    var iparent = $(this).parents('.area-content');
    if(flag){
        iparent.find('.panel-body').hide();
        $(this).removeClass('glyphicon-resize-small');
        $(this).addClass('glyphicon-resize-full');
    }else{
        iparent.find('.panel-body').show();
        $(this).addClass('glyphicon-resize-small');
        $(this).removeClass('glyphicon-resize-full');
    }
    
})
$('.add-btn').click(function(){
    var topParent = $(this).parents('.main');
    var iparent = $(this).parents('.area-content');
    var newContent = iparent.clone(true);
    newContent.find('.panel-info').removeClass('panel-info').addClass('panel-warning')
    newContent.find('input').val('');
    newContent.prependTo(topParent);
})
$('.delete-btn').click(function(){
    var iparent = $(this).parents('.area-content');
    iparent.find('.panel-info').removeClass('panel-info').addClass('panel-danger');
    $('#modal').modal('show');
    $('.submite-btn').click(function(){
        var flag = $(this).attr('data-flag');
        if(flag=='true'){
            iparent.remove();
            $('#modal').modal('hide');
        }else{
            iparent.find('.panel-info').removeClass('panel-danger').addClass('panel-info');
            $('#modal').modal('hide');
        }
    })
})

$('.main').on('click','.save-area-info',function(){
    var data = [];
    var event_id = $('#event_id').val();
    $('.area-content').each(function(){
        var iparent = $(this).parents('.main');
        var lastIndex = iparent.find('.area-content').last().index();
        var nowLast = $(this).index();
        var This = $(this);
        var name = This.find('input[name="name"]').val();
        var link = This.find('input[name="link"]').val();
        var start = This.find('input[name="starttime"]').val();
        var end = This.find('input[name="endtime"]').val();
        var height = This.find('input[name="height"]').val();
        var is_target = This.find('select[name="is_target"]').val();
        var price = This.find('input[name="price"]').val();
        if((!name||!link)&&nowLast!=lastIndex){
            alert('导航名称或链接不能为空哦！');
        }else{
           data.push({'name':name,'link':link,'height':height,'is_target':is_target, 'starttime':start, 'endtime':end, 'price':price})
        }
    });  
    $.post('/activity/SaveAreaInfo', {data:data,event_id:event_id}, function(json) {
        if(json.errno==1){
            window.location.reload();
        }
        alert(json.msg)
    },'json');
    
})
//保存活动时间

$('.save-event-time').click(function(){
    var event_id = $('#event_id').val();
    var start_time = $('#start_time').val();
    var end_time = $('#end_time').val();
    var join_status = $('#join_status').val();
    var params = {'event_id':event_id,'start_time':start_time,'end_time':end_time,'join_status':join_status};
    $.post('/activity/updateEventTime', params, function(data) {
        if(data.succ==1){
            window.location.reload();
        }
    },'json');
})