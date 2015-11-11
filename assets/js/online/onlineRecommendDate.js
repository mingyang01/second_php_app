$(function(){
  /**
   * 批量保存排期
   */
  $(".btnEditRecommendData").click(function(){
    if ($(this).hasClass("disabled")) return false;
    var thisObj = $(this);
    
    var startTime   = $(".editDateCon").find("input[name='start_time']").val();
    var ids = getGids();
    
    if (!ids) {
      alert('请选择要排期的商品');
      return false;
    }
    var timeRe = /^[0-9]{4}-[0-9]{2}-[0-9]{2}$/;
    if (!timeRe.test(startTime)) {
      alert('请选择正确的运行时间');
      return false;
    }
    var objInHtml = thisObj.html();
    setBtnStatus(thisObj, '提交中...', 'disabled');
    $.post('/online/saveRecommendData', { 'start_time':startTime, 'tuan_id':ids }, function(json){
      if (json.succ == 1) {
        window.location.reload();
      } else {
        alert(json.msg);
      }
      setBtnStatus(thisObj, objInHtml, 'succ');
    },'json').error(function(code,data){
      alert('遇到服务器错误');
      setBtnStatus(thisObj, objInHtml, 'succ');
    });
  });
  
  /**
   * 保存单个排期
   */
  $('#box-container').on('click', ".btnSaveRecommendDataOne", function(e){
    // 阻止冒泡
    e.preventDefault();
    e.stopPropagation();
    
    if ($(this).hasClass("disabled")) return false;
    var thisObj = $(this);
    var startTime   = thisObj.prev("input[name='start_time']").val();
    var tuanId      = thisObj.attr("data-gid");
    var timeRe = /^[0-9]{4}-[0-9]{2}-[0-9]{2}$/;
    if (!timeRe.test(startTime)) {
      alert('请选择正确的运行时间');
      return false;
    }
    
    var objInHtml = thisObj.html();
    setBtnStatus(thisObj, '提交中...', 'disabled');
    $.post('/online/saveRecommendData', { 'start_time':startTime, 'tuan_id':tuanId }, function(json){
      if (json.succ == 1) {
        alert('排期成功');
        setBtnStatus(thisObj, objInHtml, 'succ');
        thisObj.html("重新推荐");
        thisObj.addClass("btn-success").removeClass("btn-danger");
        thisObj.closest(".thumbnail").find(".recommendDateCon").html(startTime);
      } else {
        alert(json.msg);
        setBtnStatus(thisObj, objInHtml, 'succ');
      }
    },'json').error(function(code,data){
      alert('遇到服务器错误');
      setBtnStatus(thisObj, objInHtml, 'succ');
    });
  });
  
  /**
   * 取消排期弹框
   */
  $('[name="failRadios"]').change(function(e){
    $('#failRadiosReason').val(this.value.trim())
  });
  $('#showCancelSchedule').click(function(e){
    e.stopPropagation();
    $selected = $(".thumbnail[class*='selected']");
    if ($selected.length == 0) {
        alert('请先选择商品,然后再批量操作!');
    } else {
        $('#cancelScheduleModal').find(".selected-count").html($selected.length);
        $('#cancelScheduleModal').modal('show');
    }
  });
  
  /**
   * 退回排期
   */
  $("#cancelSchedule").click(function(){
    if ($(this).hasClass("disabled")) return false;
    var thisObj = $(this);
    var ids = [], comment, shops = [];
    
    var selectedList = $(".thumbnail[class*='selected']");
    $.each(selectedList, function(k, v){
      ids.push($(v).closest(".thumbnail").attr("data-gid"));
      shops.push($(v).closest(".thumbnail").attr("data-shop"));
    });
    if (ids.length < 1) {
      alert('请选择商品');
      return false;
    }
    comment = $('#failRadiosReason').val();
    if (!comment || comment == '') {
      alert('请填写原因');
      return false;
    }
    if (confirm("您确定要将 "+ids.length+" 个商品退回吗？") == false) {
      return false;
    }
    
    var objInHtml = thisObj.html();
    setBtnStatus(thisObj, '提交中...', 'disabled');
    var url = '/audit/saveAudit';
    $.post(url, {'shops': shops, 'checkResult': 51, "comment": comment, "ids": ids}, function(data){
        if (data.code == 1) {
           $('#cancelScheduleModal').modal('hide');
           window.location.reload();
        } else {
           alert(json.data);
        }
        setBtnStatus(thisObj, objInHtml, 'succ');
    }, 'json').error(function(code,data){
        alert('系统出错了~');
        setBtnStatus(thisObj, objInHtml, 'succ');
    });
  });
  
});
