// chao
// 最后疯抢
$(function() {
    $(".pinned").pin({'activeClass': 'pinActive'})
    
    $(window).resize(functioln(e){
        $(".pinned").pin({'activeClass': 'pinActive'})
    });
    $('.picker').datepicker({
        format: "yyyy-mm-dd",
        autoclose: true
    }).on('changeDate', function(ev){
        $(this).datepicker('hide');
    });

    if ($('#type').val() == 1) {
        $( "#box-contailer" ).sortable();
        $( "#box-contailer" ).disableSelection();
    }

    $('.delete-box').click(function() {
        if (confirm("确认要删除?")) {
            $.get("/manual/deleteFromWeek", {'gid': $(this).data('gid')}, function(date){
                if(data.succ == 1){
                    alert('操作成功');
                    location.reload();
                    return false;
                }else{
                    alert('操作失败');
                    location.reload();
                }
                return false;
            }, 'json');
        }
    });

    //选定，变色
    $(".toDoingClassPro").on('click',function(){
        var myBox = $(this).parent();
        if($(myBox).css('background-color') == 'rgb(204, 254, 224)'){
            $(myBox).attr('class','recommendMyBox');
            $(myBox).css('background-color','#ffffff');
            $(myBox).css("border-color","#cccccc");
        }else{
            $(myBox).attr('class','recommendMyBoxSelected');
            $(myBox).css("border-color","#267226");
            $(myBox).css('background','#CCFEE0');
        }
    });

    var rungingButton = false;
    $("#addToWeekSelect").on('click',function(){
        if(rungingButton == true){
            alert('操作进行中，请耐心等候');
            return false;
        }
        var checkOk = [];
        var checkResult = [];
        var checkResultSource = [];
        var keyVal = 0;
        var tuanIds = [];
        var twitterIds = [];
        var startTimes = [];
        var endTimes = [];
        $("#recommendWorkBox .recommendMyBoxSelected").each(function() {
            tuanIds[keyVal] = $(this).find("[name='tuan_id']").val();
            twitterIds[keyVal] = $(this).find("[name='twitter_id']").val();
            startTimes[keyVal] = $(this).find("[name='start_time']").val();
            endTimes[keyVal] = $(this).find("[name='end_time']").val();
            keyVal = keyVal + 1;
        });
        rungingButton = true;
        $.ajax({
            type:"POST",
            url: "/tuanht/ajax_addToWeekSelect",
            dataType: "json",
            data: {tuanIds: tuanIds, twitterIds:twitterIds, startTimes:startTimes, endTimes:endTimes },
            success: function(data){
                if(data.succ == 1){
                    alert('操作成功');
                    location.reload();
                    return false;
                }else{
                    alert(data.msg);
                    location.reload();
                }
                return false;
            },
            error: function (data, status, e){
                alert(data.responseText);
                location.reload();
            }
        });
    });
    rungingButton = false;
    $("#beginToSaveFrom").on('click',function(){
        if(rungingButton == true){
            alert('操作进行中，请耐心等候');
            return false;
        }
        var keyVal = 0;
        var tuanIds = []
        $("#recommendWorkBox .recommendMyBox").each(function() {
            var tuan_id = $(this).find("[name='tuan_id']").val();
            tuanIds.push(tuan_id);
        });
        rungingButton = true;
        $.ajax({
            type:"POST",
            url: "/tuanht/ajax_saveWeekSelectTuanWeight",
            dataType: "json",
            data: {'tuanIds': tuanIds},
            success: function(data){
                if(data.succ == 1){
                    alert('操作成功');
                    location.reload();
                    return false;
                }else{
                    alert(data.msg);
                    location.reload();
                }
                return false;
            },
            error: function (data, status, e){
                alert(data.responseText);
                location.reload();
            }
        });
    });
});