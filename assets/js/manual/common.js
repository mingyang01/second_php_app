$(function(){
  //$(".pinned").pin({'activeClass': 'pinActive'})
  $('.picker').datepicker({
      format: "yyyy-mm-dd",
      autoclose: true
  }).on('changeDate', function(ev){
      $(this).datepicker('hide');
  });

  if ($('#type').val() == 1) {
    var moveRecommend = document.getElementById("box-contailer");
    new Sortable(moveRecommend);
  }
  
  $(".selecteBox").click(function(e){
    $(this).closest(".thumbnail").toggleClass("selected")
  });
  
  // 保存商品
  $("#saveGoods").click(function(e){
    if ($(this).hasClass("disabled")) return false;
    var thisObj = $(this);
    
    var selectedList = $(".thumbnail[class*='selected']");
    var idsArr = [];
    $.each(selectedList, function(k, v){
      idsArr.push($(v).closest(".thumbnail").attr("data-gid"));
    });
    if (idsArr.length < 1) {
      alert('请选择商品');
      return false;
    }
    var ids = idsArr.join(",");
    var db  = thisObj.attr('data-db');
    var startTime = $("#date").val();
    
    if (confirm("您确定要将 "+idsArr.length+" 个商品添加到"+startTime+"时间中吗？") == false) {
      return false;
    }
    
    var postData = {'tuan_id':ids, 'db':db};
    var objInHtml = thisObj.html();
    setBtnStatus(thisObj, '提交中...', 'disabled');
    $.post('/manual/addToGoods', postData, function(json){
      if (json.succ == 1) {
        alert('成功操作： '+json.succ_num+" 个商品，操作失败： "+json.err_num+" 个商品");
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
   * 保存排序
   */
   $("#saveGoodsSort").click(function(e){
     if ($(this).hasClass("disabled")) return false;
     var thisObj = $(this);

     var selectedList = $(".thumbnail");
     var idsArr = [];
     $.each(selectedList, function(k, v){
       idsArr.push($(v).closest(".thumbnail").attr("data-gid"));
     });
     if (idsArr.length < 1) {
       alert('请选择商品');
       return false;
     }
     var ids = idsArr.join(",");
     var db  = thisObj.attr('data-db');
     
     if (confirm("您确定要将 "+idsArr.length+"个商品重新排序吗？") == false) {
       return false;
     }
     
     var postData = {'tuan_id':ids, 'db':db};
     var objInHtml = thisObj.html();
     setBtnStatus(thisObj, '提交中...', 'disabled');
     $.post('/manual/addToSort', postData, function(json){
       if (json.succ == 1) {
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

  $('.deleteBox').click(function() {
    if ($(this).hasClass("disabled")) return false;
    var thisObj = $(this);
    var tuanId  = thisObj.attr("data-gid");
    var db      = thisObj.attr('data-db');
    if (!tuanId) {
      alert('商品id不存在');
      return false;
    }
    if (confirm("您确定要删除吗？") == false) {
      return false;
    }
    
    var postData = {'tuan_id':tuanId, 'db':db};
    var objInHtml = thisObj.html();
    setBtnStatus(thisObj, '提交中...', 'disabled');
    $.post('/manual/deleteGoods', postData, function(json){
      if (json.succ == 1) {
        setBtnStatus(thisObj, objInHtml, 'succ');
        thisObj.closest(".thumbnail").parent(".col-md-2").fadeOut(function(){
          $(this).remove();
        });
      } else {
        alert(json.msg);
        setBtnStatus(thisObj, objInHtml, 'succ');
      }
    },'json').error(function(code, data){
      alert('遇到服务器错误');
      setBtnStatus(thisObj, objInHtml, 'succ');
    });
  });
});