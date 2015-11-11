$(function(){
  /** 选择时间 */
  $('body').on('focus', '.myDatePicker',function(e){
    WdatePicker({
        dateFmt:'yyyy-MM-dd',
        minDate:'%y-%M-%{%d}',
        maxDate:'%y-%M-%{%d+30}'
    });
  });
  
  /**
   * 防止选择时间的时候冒泡导致商品被选中
   */
  $('#box-container').on('click', '.myDatePicker', function(e){
    // 阻止冒泡
    e.preventDefault();
    e.stopPropagation();
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
