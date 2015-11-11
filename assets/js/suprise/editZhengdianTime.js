if (!Date.prototype.yyyymmddhh){
  Date.prototype.yyyymmddhh=function(){
    var yyyy=this.getFullYear().toString();
    var mm=(this.getMonth()+1).toString();
    var dd=this.getDate().toString();
    var HH=this.getHours().toString();
    var MM=this.getMinutes().toString();
    var SS=this.getSeconds().toString();
    return yyyy + "-" + (mm[1]?mm:"0"+mm[0]) + "-" + (dd[1]?dd:"0"+dd[0]) + " " + (HH[1]?HH:"0"+HH[0]) + ":" + (MM[1]?MM:"0"+MM[0]);
  };
}
if (!Date.prototype.yyyymmdd){
  Date.prototype.yyyymmdd=function(){
    var yyyy=this.getFullYear().toString();
    var mm=(this.getMonth()+1).toString();
    var dd=this.getDate().toString();
    var HH=this.getHours().toString();
    var MM=this.getMinutes().toString();
    var SS=this.getSeconds().toString();
    return yyyy + "-" + (mm[1]?mm:"0"+mm[0]) + "-" + (dd[1]?dd:"0"+dd[0]) + " " + (HH[1]?HH:"0"+HH[0]);
  };
}

// 设置本地存储最后选中的时间
var zhengdianTime = function(){};
zhengdianTime = function(){
  if (typeof(zhengdianTime) == 'object') return false;
  this.init.call(this);
}


zhengdianTime.prototype = {
    
    init : function()
    {
      var self = this;
      self.initConfig();
      $(".btnAddZhengdianTimeOne").click(function(){
        self.setZhengdinTimeOne();
      });
      
      $(".btnAddZhengdianTimeAll").click(function(){
        var nowTimeObj = new Date(self.getLastStartTime());
        var tomorrowTimeObj = new Date(self.getLastStartTime());
        tomorrowTimeObj.setDate(nowTimeObj.getDate()+1);
        tomorrowTimeObj.setHours(0);
        self.setZhengdinTimeAll(tomorrowTimeObj.yyyymmddhh());
      });
    },
    
    /** 获取最后的开始时间 */
    getLastStartTime : function()
    {
      var self = this;
      var lastStartTime = self.config.zhengdianTimeConObj.find("input[name='ctime[]']:last").val();
      if (lastStartTime) {
        return lastStartTime;
      } else {
        return new Date().yyyymmddhh();
      }
    },
    
    /** 获取最后的结束时间 */
    getLastEndTime : function()
    {
      var self = this;
      var lastEndTime = self.config.zhengdianTimeConObj.find("input[name='etime[]']:last").val();
      if (lastEndTime) {
        return lastEndTime;
      } else {
        return new Date().yyyymmddhh();
      }
    },
    
    /** 获取整点抢时间 */
    getZhengdianTime : function(lastStartTime)
    {
      var self = this;
      if (!lastStartTime) {
        lastStartTime = self.getLastEndTime();
      }
      var lastStartTimeObj = new Date(lastStartTime);
      var nowStartTimeObj  = new Date(lastStartTime);
      var nowEndTimeObj    = new Date(lastStartTime);
      if(lastStartTimeObj.getHours() == 0){
        nowStartTimeObj.setHours(00);
        nowEndTimeObj.setHours(10);
      } else if(lastStartTimeObj.getHours() == 10){
        nowStartTimeObj.setHours(10);
        nowEndTimeObj.setHours(12);
      }else if(lastStartTimeObj.getHours() == 12){
        nowStartTimeObj.setHours(12);
        nowEndTimeObj.setHours(16);
      }else if(lastStartTimeObj.getHours() == 16){
        nowStartTimeObj.setHours(16);
        nowEndTimeObj.setHours(20);
      }else if(lastStartTimeObj.getHours() == 20){
        nowStartTimeObj.setHours(20);
        nowEndTimeObj.setHours(22);
      }else if(lastStartTimeObj.getHours() == 22){
        nowStartTimeObj.setHours(22);
        nowEndTimeObj.setDate(nowEndTimeObj.getDate()+1);
        nowEndTimeObj.setHours(00);
      }else{
        nowStartTimeObj.setDate(lastStartTimeObj.getDate()+1);
        nowStartTimeObj.setHours(0);
        nowEndTimeObj.setDate(nowEndTimeObj.getDate()+1);
        nowEndTimeObj.setHours(10);
      }
      
      return {'start_time':nowStartTimeObj.yyyymmddhh(),'end_time':nowEndTimeObj.yyyymmddhh()}
    },
    
    /** 设置单个时间 */
    setZhengdinTimeOne : function(timeObj)
    {
      var self = this;
      if (!timeObj) {
        timeObj = self.getZhengdianTime();
      }
      
      var inHtml = self.config.zhengdianTimeHtml;
      // 替换开始时间和结束时间
      inHtml = inHtml.replace('$startTime$', timeObj.start_time);
      inHtml = inHtml.replace('$endTime$', timeObj.end_time);
      self.config.zhengdianTimeConObj.append(inHtml);
      self.config.zhengdianTimeConObj.find("input[name='stime_note[]']:last").focus();
      // 重新加载日期插件
      $('.myDatePicker').on('focus',function(){
        WdatePicker({
            dateFmt:'yyyy-MM-dd HH:00'
        });
      });
    },
    
    /** 设置多个时间 */
    setZhengdinTimeAll : function(nowTime)
    {
      if (!nowTime) return false;
      var oldNowTime = nowTime;
      var self = this;
      var newHtml = '';
      for (var i=0; i<6; i++) {
        timeObj = self.getZhengdianTime(nowTime);
        // nowTime = timeObj.start_time;
        nowTime = timeObj.end_time;
        // 判断小时
        if (new Date(oldNowTime).getDay() < new Date(timeObj.start_time).getDay()) {
          break;
        }
        var inHtml = self.config.zhengdianTimeHtml;
        // 替换开始时间和结束时间
        inHtml = inHtml.replace('$startTime$', timeObj.start_time);
        inHtml = inHtml.replace('$endTime$', timeObj.end_time);
        newHtml += inHtml;
      }
      
      self.config.zhengdianTimeConObj.append(newHtml);
      self.config.zhengdianTimeConObj.find("input[name='stime_note[]']:last").focus();
      // 重新加载日期插件
      $('.myDatePicker').on('focus',function(){
        WdatePicker({
            dateFmt:'yyyy-MM-dd HH:00'
        });
      });
    },
    
    /** 默认配置文件 */
    initConfig: function()
    {
        this.config = {
          'zhengdianTimeConObj'  : $(".zhengdianTimeCon"),  // 时间容器对象
          'zhengdianTimeHtml'  : '<div class="form-group" style="margin:3px 0;">'
            + '<label>说明：</label><input class="require input-medium form-control" type="text" name="stime_note[]" id="stime_note" value=""> '
            + '<label>开始时间：</label><input class="require input-medium form-control myDatePicker" type="text" name="ctime[]" id="ctime" value="$startTime$"> '
            + '<label>结束时间：</label><input class="require input-medium form-control myDatePicker" type="text" name="etime[]" id="etime" value="$endTime$"> '
            + '<button type="button" class="btn btn-danger btn-xs btnDeleteTime">删除</button>'
            + '</div>',
          'con' : ''
        };
    }
}

$(function(){
  window.zhengdianTime = new zhengdianTime();
  
  /**
   * 删除已保存的记录
   */
  $(".btnDeleteTimeOnline").click(function(){
    if ($(this).hasClass("disabled")) return false;
    var thisObj = $(this);
    
    var startTime = thisObj.siblings("input[name='ctime[]']").val();
    var endTime   = thisObj.siblings("input[name='etime[]']").val();
    if (!startTime || !endTime) {
      alert('非法操作');
      return false;
    }
    
    var objInHtml = thisObj.html();
    setBtnStatus(thisObj, '提交中...', 'disabled');
    $.post('/suprise/checkTimeHasGoods', {'start_time':startTime, 'end_time':endTime}, function(json){
      setBtnStatus(thisObj, objInHtml, 'succ');
      if (json.succ == 1) {
         thisObj.closest(".form-group").fadeOut(function(){
           $(this).remove();
         });
      } else {
        alert(json.msg);
      }
    }, 'json').error(function(code,data){
      alert('服务器遇到错误了');
      var objInHtml = thisObj.html();
      setBtnStatus(thisObj, objInHtml, 'succ');
    });
  });
  
  
  /** 删除时间 */
  $(".zhengdianTimeCon").on("click", ".btnDeleteTime", function(){
    $(this).closest(".form-group").fadeOut(function(){
      $(this).remove();
    });
  });
});