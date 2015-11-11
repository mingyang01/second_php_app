$(function(){
    var timer;
    var Showtimer= null;
    var event_id = $('#event_id').val();
    /** 推荐位移动 */
    var moveRecommend = document.getElementById("box-contailer");
    new Sortable(moveRecommend, {
        handle: '.img'
    });
    /** 删除排期商品*/
    $('.delete-btn').click(function(){
        $('#myModal').modal();
        var This = $(this);
        var _this = $('#myModal').find('.modal-body');
        _this.html('你确定要退回么');
        $('.btnSurebox').show();
        $('#myModal').on('click','.sure-btn',function(){
            var flag =  $(this).attr('data-flag');
            if(flag=='false'){
                return false;
            }
            var postData = {tuan_id:This.attr('data-tid')};
            var event_id = $('#event_id').val();
            $.post('/schedule/cancelSchedule', postData, function(json){
                if(json.succ == 1){
                    window.location.reload();
                }
            },'json')
        })
    })
    $('#schedule').click(function(){
        var tids = '';
        var event_id = $('#event_id').val();
        var area = $('#area').val();
        var time = $('.time-box').val();
        $('.thumbnail').each(function(){
            tids +=$(this).attr('data-tid')+',';
        })
        console.log(tids);
        console.log(event_id);
        console.log(area);
        console.log(time);
        showDot('正在保存，请稍等');
        $('#myModal').modal();
        $.post('/activity/sortEvent',{tids:tids,area:area,event_id:event_id,time:time},function(data){
            if(data){
                clearInterval(timer);
                $('#myModal').modal('hide');
                window.location.reload();
            }
        },'json')
    })
    /**条件排序*/
    $('.sort-btn').click(function(){
        var _this = $(this);
        sureFun(_this);
    });

    function showDot(msg){
        nhtml = msg;
        var _this = $('#myModal').find('.modal-body');
        _this.html(nhtml);
        timer = setInterval(function(){
            nhtml +='.';
            _this.html(nhtml);
        },1000);
    }
    //日期插件
    $('body').on('focus', '.picker',function(e){
        WdatePicker({
            dateFmt:'yyyy-MM-dd HH:00:00'
        });
    });
    // $('.picker').datepicker({
    //     format: "yyyy-mm-dd hh:ii",
    //     autoclose: true
    // }).on('changeDate', function(ev){
    //     $(this).datepicker('hide');
    // });
    //根据日期获得品
    $('.getByTime').click(function(){
        var time = $('.time-box').val();
        var area = $('#area').val();
        var event_id = $("#event_id").val();
        window.location='/activity/index?area='+area+'&event_id='+event_id+'&time='+time;
    })
    //导出
    $(".exportBtn").click(function(e){
        e.stopPropagation
        e.preventDefault();
        var area = $('#area').val();
        var time = $('.time-box').val();
        var event_id = $('#event_id').val();
        window.location='/activity/exportHtml?area='+area+'&event_id='+event_id+'&time='+time;
    });
    
    //自动划分区域
    $('.auto-divide').click(function(){
        showDot('正在为商品划分区域，请稍等');
        $('#myModal').modal();
        var time = $('.time-box').val();
        $.get('/activity/autoDivide',{event_id:event_id,time:time},function(data) {
            if(data.code==1)
            {
                clearInterval(timer);
                $('#myModal').modal('hide');
                window.location.reload();
            }
        },'json');
    })

    function showDot(msg){
    clearInterval(Showtimer);
    nhtml = msg;
    var _this = $('#myModal').find('.modal-body');
    _this.html(nhtml);
    Showtimer = setInterval(function(){
        nhtml +='.';
        _this.html(nhtml);
    },1000);
}

})