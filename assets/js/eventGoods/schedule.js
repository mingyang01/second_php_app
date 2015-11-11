$(function(){
  /**
   * 展示排期弹框
   */
  $('#showSchedule').click(function(e){
    e.stopPropagation()
    $selected = $(".thumbnail[class*='selected']");
    if ($selected.length == 0) {
        alert('请先点击选定,然后再批量操作!');
    } else {
        $('#scheduleModal').find(".selected-count").html($selected.length);
        $('#scheduleModal').modal('show');
    }
  });
  
  /**
   * 取消排期
   */
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
   * 排期失败
   */
  $("#showFailGoods").click(function(e){
    e.stopPropagation();
    $selected = $(".thumbnail[class*='selected']");
    if ($selected.length == 0) {
        alert('请先选择商品,然后再批量操作!');
    } else {
        $('#failModal').find(".selected-count").html($selected.length);
        $('#failModal').modal('show');
    }
  });
  // 排期失败选择原因
  $('[name="failGoodsRadios"]').change(function(e){
    $('#failRadiosGoodsReason').val(this.value.trim())
  });
  
  /** 冒泡 **/
  $("#box-container").on("click", "input,a,button", function(e){
    e.stopPropagation();
  });
  
  /**
   * 单个排期
   */
  $("#box-container").on("click", ".btnSaveScheduleOne", function(e){
    e.preventDefault();
    e.stopPropagation();
    if ($(this).hasClass("disabled")) return false;
    var thisObj = $(this);
    var goodsBoxObj = $(this).closest(".thumbnail");
    var startTime   = goodsBoxObj.find("input[name='start_time']").val();
    var endTime     = goodsBoxObj.find("input[name='end_time']").val();
    var tuanId      = goodsBoxObj.attr("data-gid");
    var repertory   = 0;
    
    var timeRe = /^[0-9]{4}-[0-9]{2}-[0-9]{2} [0-9]{2}:[0-9]{2}:[0-9]{2}$/;
    if (!timeRe.test(startTime)) {
      alert('请选择正确的运行时间');
      return false;
    }
    if (!timeRe.test(endTime)) {
      alert('请选择正确的运行时间');
      return false;
    }
    if (!tuanId) {
      alert('团购id不存在');
      return false;
    }
    if (window.needRepertory) {
      var repertory   = goodsBoxObj.find("input[name='repertory']").val();
      if (!repertory) {
        alert('请选择库存');
        return false;
      }
    }
    if (!confirm('您确定要排期这个商品到'+startTime+"吗？")) {
      return false;
    }
    
    var postData = {'start_time':startTime, 'end_time':endTime, 'tuan_id':tuanId, 'event_id':eventId, 'repertory':repertory};
    console.log(postData);
    var objInHtml = thisObj.html();
    setBtnStatus(thisObj, '提交中...', 'disabled');
    $.post('/eventGoods/eventGoodsSchedule', postData, function(json){
      if (json.succ == 1) {
        setBtnStatus(thisObj, objInHtml, 'succ');
        if (json.err_num > 0) {
          alert(json.err_result);
          return false;
        } else {
          goodsBoxObj.parent().fadeOut(function(){
            $(this).remove();
          });
        }
      } else {
        alert(json.msg);
        setBtnStatus(thisObj, objInHtml, 'succ');
      }
    },'json').error(function(code, data){
      alert('遇到服务器错误');
      setBtnStatus(thisObj, objInHtml, 'succ');
    });
  });
  
  /**
   * 批量排期
   */
  $("#btnSaveSchedule").click(function(e){
    e.preventDefault();
    e.stopPropagation();
    if ($(this).hasClass("disabled")) return false;
    var thisObj = $(this);
    var modalObj = $('#scheduleModal');
    
    var startTime = modalObj.find(".modalCon").find("input[name='start_time']").val();
    var endTime   = modalObj.find(".modalCon").find("input[name='end_time']").val();
    var tuanIds   = getGids();
    var repertory = 0;
    var useOriginRepertory = 0;
    
    var timeRe = /^[0-9]{4}-[0-9]{2}-[0-9]{2} [0-9]{2}:[0-9]{2}:[0-9]{2}$/;
    if (!timeRe.test(startTime)) {
      alert('请选择正确的运行时间');
      return false;
    }
    if (!timeRe.test(endTime)) {
      alert('请选择正确的运行时间');
      return false;
    }
    if (!tuanIds) {
      alert('团购id不存在');
      return false;
    }
    if (window.needRepertory) {
      var repertory   = modalObj.find(".modalCon").find("input[name='repertory']").val();
      // 设置商品的原库存为库存
      var orifinRepertoryObj = modalObj.find(".modalCon").find("input[name='use_origin_repertory']");
      if (orifinRepertoryObj.prop("checked") == true) {
        useOriginRepertory = 1;
      }
      if (!repertory && !useOriginRepertory) {
        alert('请选择库存');
        return false;
      }
    }
    if (!confirm('您确定要排期 '+$("#box-container").find(".thumbnail[class*='selected']").length+' 个商品到 '+startTime+' - '+endTime+' 吗？')) {
      return false;
    }
    
    var postData = {'start_time':startTime, 'end_time':endTime, 'tuan_id':tuanIds, 'event_id':eventId, 'repertory':repertory, 'use_origin_repertory':useOriginRepertory};
    console.log(postData);
    var objInHtml = thisObj.html();
    setBtnStatus(thisObj, '提交中...', 'disabled');
    $.post('/eventGoods/eventGoodsSchedule', postData, function(json){
      if (json.succ == 1) {
        alert('成功操作： '+json.succ_num+" 个商品，操作失败： "+json.err_num+" 个商品");
        if (json.err_num > 0) {
          alert(json.err_result);
        }
        window.location.reload();
        setBtnStatus(thisObj, objInHtml, 'succ');
      } else {
        alert(json.msg);
        setBtnStatus(thisObj, objInHtml, 'succ');
      }
    },'json').error(function(code, data){
      alert('遇到服务器错误');
      setBtnStatus(thisObj, objInHtml, 'succ');
    });
  });
  
  /**
   * 单个取消排期
   */
  $("#box-container").on("click", ".btnCancelScheduleOne", function(e){
    e.preventDefault();
    e.stopPropagation();
    if ($(this).hasClass("disabled")) return false;
    var thisObj = $(this);
    
    var goodsBoxObj = $(this).closest(".thumbnail");
    var tuanId      = goodsBoxObj.attr("data-gid");
    
    if (!tuanId) {
      alert('团购id不存在');
      return false;
    }
    if (!confirm('您确定要取消排期这个商品到吗？取消排期后商品将到排期失败状态')) {
      return false;
    }
    
    var postData = {'tuan_id':tuanId};
    var objInHtml = thisObj.html();
    setBtnStatus(thisObj, '提交中...', 'disabled');
    $.post('/eventGoods/eventGoodsCancelSchedule', postData, function(json){
      if (json.succ == 1) {
        setBtnStatus(thisObj, objInHtml, 'succ');
        if (json.err_num > 0) {
          alert(json.err_result);
          return false;
        } else {
          goodsBoxObj.parent().fadeOut(function(){
            $(this).remove();
          });
        }
      } else {
        alert(json.msg);
        setBtnStatus(thisObj, objInHtml, 'succ');
      }
    },'json').error(function(code, data){
      alert('遇到服务器错误');
      setBtnStatus(thisObj, objInHtml, 'succ');
    });
  });
  
  /**
   * 批量取消排期
   */
  $("#btnCancelSchedule").click(function(e){
    e.preventDefault();
    e.stopPropagation();
    if ($(this).hasClass("disabled")) return false;
    var thisObj  = $(this);
    var modalObj = $('#cancelScheduleModal');
    
    var comments = modalObj.find(".modalCon").find("#failRadiosReason").val();
    var tuanIds  = getGids();
    
    if (!tuanIds) {
      alert('团购id不存在');
      return false;
    }
    if (!comments) {
      alert('请选择原因');
      return false;
    }
    
    if (!confirm('您确定要取消排期 '+$("#box-container").find(".thumbnail[class*='selected']").length+' 个商品吗？取消排期后商品状态为排期失败。')) {
      return false;
    }
    
    var postData = {'tuan_id':tuanIds, 'comments':comments};
    var objInHtml = thisObj.html();
    setBtnStatus(thisObj, '提交中...', 'disabled');
    $.post('/eventGoods/eventGoodsCancelSchedule', postData, function(json){
      if (json.succ == 1) {
        alert('成功操作： '+json.succ_num+" 个商品，操作失败： "+json.err_num+" 个商品");
        if (json.err_num > 0) {
          alert(json.err_result);
        }
        window.location.reload();
        setBtnStatus(thisObj, objInHtml, 'succ');
      } else {
        alert(json.msg);
        setBtnStatus(thisObj, objInHtml, 'succ');
      }
    },'json').error(function(code, data){
      alert('遇到服务器错误');
      setBtnStatus(thisObj, objInHtml, 'succ');
    });
  });
  
  
  /**
   * 退回排期（排期失败）
   */
  $("#btnFailGoods").click(function(){
    if ($(this).hasClass("disabled")) return false;
    var thisObj = $(this);
    var ids = [], comment, shops = [];
    
    var selectedList = $("#box-container").find(".thumbnail[class*='selected']");
    $.each(selectedList, function(k, v){
      ids.push($(v).attr("data-gid"));
      shops.push($(v).attr("data-shop"));
    });
    if (ids.length < 1) {
      alert('请选择商品');
      return false;
    }
    comment = $('#failRadiosGoodsReason').val();
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
          alert('取消排期成功');
           $('#failModal').modal('hide');
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


/** 获取推荐容器中的id 返回对象 */
function getGids()
{
  var ids = [];
  $("#box-container").find(".thumbnail[class*='selected']").each(function(){
      var gid = $(this).attr("data-gid");
      if (gid) {
          ids.push(gid);
      }
  });
  return ids.join(",");
}
