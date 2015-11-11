$(function(){
  /**
   * 批量保存排期
   */
  $(".btnEditDate").click(function(){
    if ($(this).hasClass("disabled")) return false;
    var thisObj = $(this);
    
    var startTime   = $(".editDateCon").find("input[name='start_time']").val();
    var isshowTag   = 0;
    if ($(".editDateCon").find("input[name='isshow_tag']").prop("checked") == true) {
      isshowTag = 1;
    }
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
    $.post('/online/saveData', { 'start_time':startTime, 'isshow_tag':isshowTag, 'tuan_ids':ids }, function(json){
      if (json.succ == 1) {
        alert('成功操作： '+json.succ_num+" 个商品，操作失败： "+json.err_num+" 个商品");
        if (json.err_num > 0) {
          alert(json.err_resault);
        }
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
   * 保存单个排期,重新排期
   */
  $('#box-container').on('click', ".btnSaveDataOne, .btnSaveDataAgainOne", function(e){
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
    
    if (thisObj.hasClass('btnSaveDataOne')) {
      if (confirm("您要将该商品排期到"+startTime+"团购中，确定要这样操作码？") == false) {
        return false;
      }
    } else {
      if (confirm("您确定要将该商品重新排期到"+startTime+"团购中吗？") == false) {
        return false;
      }
    }
    
    var objInHtml = thisObj.html();
    setBtnStatus(thisObj, '提交中...', 'disabled');
    $.post('/online/saveDataOne', { 'start_time':startTime, 'tuan_id':tuanId }, function(json){
      if (json.succ == 1) {
        //window.location.reload();
        alert('排期成功');
        if (thisObj.hasClass('btnSaveDataOne')) {
          thisObj.closest(".thumbnail").parent(".col-md-6").fadeOut(function(){
            $(this).remove();
          });
        } else {
          thisObj.closest(".thumbnail").find(".recommendDateCon").html(startTime);
        }
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
   * 退回单个推荐排期
   */
  $('#box-container').on('click', ".btnCancelDataOne", function(e){
    // 阻止冒泡
    e.preventDefault();
    e.stopPropagation();
    
    if ($(this).hasClass("disabled")) return false;
    var thisObj     = $(this);
    var tuanId      = thisObj.attr("data-gid");
    
    if (confirm("您确认要退回吗？该商品将会回到推荐排期列表") == false) {
      return false;
    }
    
    var objInHtml = thisObj.html();
    setBtnStatus(thisObj, '提交中...', 'disabled');
    $.post('/online/cancelData', { 'tuan_id':tuanId }, function(json){
      if (json.succ == 1) {
        thisObj.closest(".thumbnail").parent(".col-md-6").fadeOut(function(){
          $(this).remove();
        });
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
   * 批量退回排期推荐
   */
  $("#cancelDateMore").click(function(){
    if ($(this).hasClass("disabled")) return false;
    var thisObj     = $(this);
    // var tuanId      = thisObj.attr("data-gid");
    
    if (confirm("您确认要退回吗？该商品将会回到推荐排期列表") == false) {
      return false;
    }
    
    var ids = getGids();
    if (!ids) {
      alert('请选择要排期的商品');
      return false;
    }
    
    var objInHtml = thisObj.html();
    setBtnStatus(thisObj, '提交中...', 'disabled');
    $.post('/online/cancelData', { 'tuan_id':ids }, function(json){
      if (json.succ == 1) {
        alert('成功操作： '+json.succ_num+" 个商品，操作失败： "+json.err_num+" 个商品");
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
   * 取消排期
   */
  $('#box-container').on('click', ".btnCancelScheduleOne", function(e){
    // 阻止冒泡
    e.preventDefault();
    e.stopPropagation();
    
    if ($(this).hasClass("disabled")) return false;
    var thisObj     = $(this);
    var tuanId      = thisObj.attr("data-gid");
    
    if (confirm("您确定要取消排期吗？取消后将被添加到排期失败的列表中") == false) {
      return false;
    }
    
    var objInHtml = thisObj.html();
    setBtnStatus(thisObj, '提交中...', 'disabled');
    $.post('/online/cancelSchedule', { 'tuan_id':tuanId }, function(json){
      if (json.succ == 1) {
        thisObj.closest(".thumbnail").parent(".col-md-6").fadeOut(function(){
          $(this).remove();
        });
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
   * 展示标签弹框
   */
  $('#addTagMore').click(function(e){
    e.stopPropagation()
    $selected = $("#box-container").find(".thumbnail[class*='selected']");
    if ($selected.length == 0) {
        alert('请先点击选定,然后再批量操作!');
    } else {
        $('#tagModal').find(".selected-count").html($selected.length);
        $('#tagModal').modal('show');
    }
  });
  
  
  /**
   * 批量保存标签
   */
  $("#saveTagInfo").click(function(){
    if ($(this).hasClass("disabled")) return false;
    var thisObj = $(this);
    
    var tagType     = $("#tagModal").find("select[name='tag_type']").val();
    
    var ids = getGids();
    if (!ids) {
      alert('请选择要排期的商品...');
      return false;
    }
    
    var objInHtml = thisObj.html();
    setBtnStatus(thisObj, '提交中...', 'disabled');
    $.post('/online/saveTag', { 'tag_type':tagType, 'tuan_ids':ids }, function(json){
      if (json.succ == 1) {
        alert('操作成功');
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
});
