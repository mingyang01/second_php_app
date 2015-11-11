{/include file="layouts/header.tpl"/}
<script type="text/javascript" src="/assets/lib/bootstrap-datepicker.js"></script>
<style type="text/css" src="/assets/css/datepicker.css"></style>
<script src="/assets/lib/My97DatePicker/WdatePicker.js"></script>
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <ol class="breadcrumb">
                <li><a href="/">Home</a></li>
                <li><a href="active">商品区域划分</a></li>
            </ol>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="well">
                <div class="form-inline">
                    <div class="form-group">
                        <label for="">活动名称</label>
                        <input type="text" disabled style="width:150px;" class="form-control" value="{/$activityInfo.event_name/}">
                    </div>
                    <div class="form-group">
                        <label for="">活动ID</label>
                        <input type="text" disabled style="width:100px;" class="form-control" value="{/$activityInfo.event_id/}">
                    </div>
                    <div class="form-group">
                        <label for="">区域</label>
                        <select name="" id="area" class="form-control">
                            {/if $tab/}
                                {/foreach from=$tab item=item key=key/}
                                    {/if $item/}
                                    <option {/if $item.id==$area/} selected {//if/} value="{/$item.id/}">{/$item.name/}</option>
                                    {//if/}
                                {//foreach/}
                            {//if/}
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="">是否为长期活动</label>
                        <select name="" id="activity-type" class="form-control">
                            <option {/if $time /} selected {//if/}value="1">是</option>
                            <option {/if !$time /} selected {//if/} value="0">否</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="">开始时间</label>
                        <input class="picker form-control time-box"  style="width:120px;" id="start_time" name="to"  type="text" {/if $time/}value="{/$time/}"{/else/}disabled{//if/}  date-format="yyyy-mm-dd">
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="alert alert-danger">
                该区域中的商品如下，将要修改区域商品的推id写在相应区域中即可，推id用英文的逗号隔开，并保存！
            </div>
        </div>
        <div class="col-md-12">
            <div class="well">
                <textarea name="" id="goodsIds" cols="30" rows="10" class="form-control">{/$goodsIds/}</textarea>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <button class="btn btn-success btn-block save-change-area">保存</button>
        </div>
    </div>
</div>
<script>
var event_id = {/$event_id/};
var goodsIds = '{/$goodsIds/}';
var flag = '0';
//日期插件
$('.picker').datepicker({
    format: "yyyy-mm-dd",
    autoclose: true
}).on('changeDate', function(ev){
    $(this).datepicker('hide');
    start_time = $("#start_time").val();
    var area = $("#area").val();
    window.location="/activity/areaDivide?event_id="+event_id+"&area="+area+"&start_time="+start_time;
    
});
$('#area').change(function(){
    var area = $("#area").val();
    var start_time = $("#start_time").val();
    window.location="/activity/areaDivide?event_id="+event_id+"&area="+area+"&start_time="+start_time;
})
$('.save-change-area').click(function(){
    var changeGoods = $('#goodsIds').val();
    var area = $("#area").val();
    if(changeGoods==goodsIds){
        alert("没有新增的商品")
    }
    var params = {'goodsIds':goodsIds,'changeGoods':changeGoods,'event_id':event_id,'area':area}
    $.post('/activity/saveDivideStatus',params, function(data) {
        window.location.reload();
    });
})
$('#activity-type').change(function() {
    flag = $(this).val();
    var area = $("#area").val();
    var start_time = '';
    if(flag=='0'){
        $("#start_time").attr('disabled', 'disabled');
        window.location="/activity/areaDivide?event_id="+event_id+"&area="+area+"&start_time="+start_time;
        flag='0'
    }else{
        $("#start_time").removeAttr('disabled');
        flag='1'
    }
});
</script>