$(function(){
  /**
   * 展示排期弹框
   */
  $('#showSchedule').click(function(e){
    e.stopPropagation()
    $selected = $(".listSelect:checked");
    if ($selected.length == 0) {
        alert('请先点击选定,然后再批量操作!');
    } else {
        $('#scheduleModal').find(".modalCon").find("input[name='start_time']").val($(".supriseTimeList[class*='active']").attr("data-start"));
        $('#scheduleModal').find(".modalCon").find("input[name='end_time']").val($(".supriseTimeList[class*='active']").attr("data-end"));
        $('#scheduleModal').find(".selected-count").html($selected.length);
        $('#scheduleModal').modal('show');
    }
  });
  
  /**
   * 排期
   */
  $("#schedule").click(function(){
    if ($(this).hasClass("disabled")) return false;
    var thisObj = $(this);
    
    //var startTime = $(".supriseTimeList[class*='active']").attr("data-start");
    //var endTime   = $(".supriseTimeList[class*='active']").attr("data-end");
    //var repertory = $("#scheduleRepertory").val();
    var startTime = $('#scheduleModal').find(".modalCon").find("input[name='start_time']").val();
    var endTime   = $('#scheduleModal').find(".modalCon").find("input[name='end_time']").val();
    var repertory = $('#scheduleModal').find(".modalCon").find("input[name='repertory']").val();
    var zdqType   = $('#scheduleModal').find(".modalCon").find("select[name='zdq_type']").val();

    var selectedList = $(".listSelect:checked");
    var idsArr = [];
    $.each(selectedList, function(k, v){
      idsArr.push($(v).closest(".thumbnail").attr("data-gid"));
    });
    if (idsArr.length < 1) {
      alert('请选择商品');
      return false;
    }
    var ids = idsArr.join(",");
    
    if (confirm("您确定要将 "+idsArr.length+" 个商品排期到"+startTime+"团购中吗？") == false) {
      return false;
    }
    
    var postData = {'tuan_id':ids, 'start_time':startTime, 'end_time':endTime, 'repertory':repertory, 'zdq_type':zdqType};
    var objInHtml = thisObj.html();
    setBtnStatus(thisObj, '提交中...', 'disabled');
    $.post('/suprise/saveZhengdianSechedule', postData, function(json){
      if (json.succ == 1) {
        alert('成功操作： '+json.succ_num+" 个商品，操作失败： "+json.err_num+" 个商品");
        if (json.err_num > 0) {
          alert(json.err_resault);
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
   * 取消排期弹框
   */
  $('[name="failRadios"]').change(function(e){
    $('#failRadiosReason').val(this.value.trim())
  });
  $('#showCancelSchedule').click(function(e){
    e.stopPropagation()
    $selected = $(".listSelect:checked");
    if ($selected.length == 0) {
        alert('请先点击选定,然后再批量操作!');
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
    
    var selectedList = $(".listSelect:checked");
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
  
  // tab点击后将时间存储到localStorage
  $(".supriseTimeList").click(function(){
    // 这里只存储时分
    supriseLocalStorage.setClickScheduleTime($(this).attr("data-start").split(" ")[1]);
  });
});

// 设置本地存储最后选中的时间
var supriseLocalStorage = function(){};
supriseLocalStorage = function(){
  if (typeof(supriseLocalStorage) == 'object') return false;
  this.init.call(this);
}

supriseLocalStorage.prototype = {
    // 是否支持storage
    localStorage : false,
    // 设置时间的key
    itemKey      : 'clickScheduleTime',
    
    init : function()
    {
      this.isLocalStorage();
      this.initScheduleTime();
    },
    
    // 判断是否支持storage
    isLocalStorage : function()
    {
      if (window.localStorage) {
        this.localStorage = true;
        return true;
      } else {
        this.localStorage = false;
        return false;
      }
    },
    
    // 设置值
    setClickScheduleTime : function(startTime)
    {
      var self = this;
      
      if (self.localStorage) {
        localStorage.setItem(self.itemKey, startTime);
      }
    },
    
    // 获取指
    getClickScheduleTime : function(key)
    {
      var self = this;
      
      if (self.localStorage) {
        return localStorage.getItem(key);
      } else {
        return null;
      }
    },
    
    // 初始化上一次被选中的时间
    initScheduleTime : function()
    {
      var self = this;
      if (!self.localStorage) return false;
      
      var startTime = self.getClickScheduleTime(self.itemKey);
      if (startTime) {
        var supriseTimeListObj = $(".supriseTimeList");
        $.each(supriseTimeListObj, function(k, v){
          if ($(v).attr("data-start").split(" ")[1] == startTime) {
            $(v).addClass("active").siblings().removeClass("active");
            $(v).find("a").attr("aria-expanded", true).siblings().find("a").attr("aria-expanded", false);
          }
        });
      }
    }
}

$(function(){
  window.supriseLocalStorage = new supriseLocalStorage();
});
