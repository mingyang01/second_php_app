$(function(){
  /** 添加标签 */
  $(".btnAddTag").click(function(){
    $(this).closest(".control-group").find(".allTagCon").append($(".appendCon").find(".aappendOneTagCon").html());
    var conList = $(this).closest(".control-group").find(".oneTagCon");
    var nowlength = conList.length - 1;
    var obj = $(conList[nowlength]);
    obj.find("input[name='tag_name']").focus();
  });
  
  /** 保存标签 */
  $(".allTagCon").on("click", ".btnSaveTag", function(){
    if ($(this).hasClass("disabled")) return false;
    var thisObj    = $(this);
    var eventId    = $("#eventId").val();
    var tagObj    = thisObj.closest(".oneTagCon");
    var tagId     = tagObj.find("input[name='tag_id']").val();
    var tagName   = tagObj.find("input[name='tag_name']").val();
    var tagSort   = tagObj.find("input[name='tag_sort']").val();
    
    if (!tagName) {
      alert('请填写标签名称');
      return false;
    }
    
    var postData = {'event_id':eventId, 'tag_name':tagName, 'tag_sort':tagSort}
    if (tagId != 'undefined') {
      postData['tag_id'] = tagId;
    }
    //console.log(postData);return false;
    var objInHtml = thisObj.html();
    setBtnStatus(thisObj, '提交中...', 'disabled');
    
    $.post('/qingcang/Qevent/saveSupriseTag', postData, function(json){
      console.log(json)
      if (json.succ == 1) {
        // 设置shopId
        if (tagObj.find("input[name='tag_id']").length >= 1) {
          tagObj.find("input[name='tag_id']").val(json.tag_id);
        } else {
          var tagIdCon = '<input type="hidden" name="tag_id" value="'+json.tag_id+'">';
          tagObj.append(tagIdCon);
        }
        
        tagObj.find(".moduleGoodsAllConTips").remove();
        tagObj.find(".moduleGoodsAllCon").show();
      } else {
        alert(json.msg);
      }
      setBtnStatus(thisObj, objInHtml, 'succ');
    }, 'json').error(function(code, data){
      alert('服务器繁忙,请稍后再试');
      setBtnStatus(thisObj, objInHtml, 'succ');
    });
  });
  
  /** 添加店铺 */
  $(".allTagCon").on("click", ".btnAddModuleGoods", function(){
    if ($(this).hasClass("disabled")) return false;
    var thisObj = $(this);
    var moduleObj = thisObj.closest(".moduleAddCon");
    var tagObj    = thisObj.closest(".oneTagCon");
    
    var shopId = thisObj.siblings("input[name='add_goods']").val();
    var eventId   = $("#eventId").val();
    var tagId     = tagObj.find("input[name='tag_id']").val();
    var moduleId  = moduleObj.find("input[name='module_id']").val();
    // var repertory = thisObj.siblings("input[name='add_goods_repertory']").val();
    
    if (!shopId) {
      alert('请输入店铺id');
      return false;
    }
    if (!eventId) {
      alert('活动信息不存在');
      return false;
    }
    if (!tagId) {
      alert('标签id不存在');
      return false;
    }
    
    var objInHtml = thisObj.html();
    setBtnStatus(thisObj, '提交中...', 'disabled');
    $.post('/qingcang/Qevent/saveEventGoods', {'shop_id':shopId, 'event_id':eventId, 'area_id':tagId}, function(json){
      if (json.succ == "1") {
        alert('成功添加：'+json.succ_num+'个商品，添加失败：'+json.err_num+"个商品");
        if (json.succ_num > 0) {
        	window.location.reload(); 
        }
      } else {
        console.log(json)
        alert(json.msg);
      }
      setBtnStatus(thisObj, objInHtml, 'succ');
    },'json').error(function(data,code){
      alert('服务器繁忙，请稍后重试');
      setBtnStatus(thisObj, objInHtml, 'succ');
    });
  });
  
  /** 隐藏显示标签 */
  $(".allTagCon").on("click", ".btnHideTag", function(){
    if ($(this).hasClass("up")) {
      $(this).find("span").html("显示标签");
      $(this).find(".glyphicon").addClass("glyphicon-arrow-up").removeClass("glyphicon-arrow-down");
      $(this).removeClass("up").addClass("down");
      $(this).closest(".oneTagCon").find(".tagModuleCon").stop().slideUp();
    } else {
      $(this).find("span").html("隐藏店铺");
      $(this).find(".glyphicon").addClass("glyphicon-arrow-down").removeClass("glyphicon-arrow-up");
      $(this).removeClass("down").addClass("up");
      $(this).closest(".oneTagCon").find(".tagModuleCon").stop().slideDown();
    }
  });
  
  /**
   * 批量保存排期
   */
	var byId = function (id) { 
		
		return document.getElementById(id); 
	};
	var obj = $('.shop_list');
	 $.each(obj, function(k, v){
		 
		 // @fixme 请注意，这里要取第一个
		 new Sortable($(v)[0], {
		    	animation: 150,
				draggable: '.sort_shop_drg',
				handle: '.thumbnail-shop',
				cancel: 'a',
		  });
		});

  
  /** 保存排序字段 */
  $(".allTagCon").on("click", ".btnSaveEventGoodsSort", function(){
    if ($(this).hasClass("disabled")) return false;
    var thisObj = $(this);
    var tagObj    = thisObj.closest(".oneTagCon");
    var eventId   = $("#eventId").val();
    var areaId    = tagObj.find("input[name='tag_id']").val();
    
    
    var ids = [];
    tagObj.find(".shopGoodsList").each(function(){
        var recommendId = $(this).attr("data-Id");
        if (recommendId) {
            ids.push(recommendId);
        }
    });
    if (ids.length < 1) return false;
    
    idsStr = ids.join(",");
    
    
    
    var objInHtml = thisObj.html();
    setBtnStatus(thisObj, '提交中...', 'disabled');
    $.post('/qingcang/Qevent/saveEventGoodsSort', {'ids':idsStr, 'area':areaId, 'event_id':eventId}, function(json){
      if (json.succ == 1) {
        alert('排序成功，成功操作 '+json.succ_num+' 个商品，操作失败 '+json.err_num+' 个商品');
        if (json.succ_num <=0) {
        	window.location.reload(); 
        }
      } else {
        alert(json.msg);
      }
      setBtnStatus(thisObj, objInHtml, 'succ');
    },'json').error(function(code, data){
      alert('服务器繁忙，请稍后重试');
      setBtnStatus(thisObj, objInHtml, 'succ');
    });
  });
  
  /** 商品删除 {直接操作为排期失败} */
  $(".allTagCon").on("click", ".deleteGoods", function(e){
    e.preventDefault();
    if ($(this).hasClass("disabled")) return false;
    var thisObj = $(this);
    var goodsObj = thisObj.closest(".shopGoodsList");
    var grouponId = goodsObj.attr("data-grouponId");
    if (!grouponId) {
      alter('商品不存在');
      return false;
    }
    if (!confirm('您确定要删除该商品吗？删除后该商品会进入排期失败状态')) {
      return false;
    }
    
    var objInHtml = thisObj.html();
    setBtnStatus(thisObj, objInHtml, 'disabled');
    $.post('/schedule/cancelSchedule', {'tuan_id':grouponId}, function(json){
      if (json.succ == 1) {
        setBtnStatus(thisObj, objInHtml, 'succ');
        goodsObj.fadeOut(function(){
          $(this).remove();
        });
      } else {
        alert(json.msg);
        setBtnStatus(thisObj, objInHtml, 'succ');
      }
    },'json').error(function(code, data){
      alert('服务器繁忙，请稍后重试');
      setBtnStatus(thisObj, objInHtml, 'succ');
    });
  });
  
});

//图片上传方法
function bannerUpload(id, imputImgName,imgObj) {
  $.ajaxFileUpload({
       url:'/event/uploadImage',
       secureuri:false,
       fileElementId:id,
       dataType:'json',
       data: {'filename':id},
       success:function(data){
           if(data.succ == 1){
               var height = imgObj.css("height");
               var width  = imgObj.css("width");
               var inImg = "<img src="+data.img+" style='height:"+height+";width:"+width+"'>";
               inImg += "<input type='hidden' name='"+imputImgName+"' value='"+data.path+"'>";
               imgObj.html(inImg);
               
           }else{
               alert(data.msg);
           }
       },
       error: function (data, status, e){
          alert(data.responseText);
       }
  });
};

$(function(){
    /** 选择时间 */
    $('.myDatePicker').on('focus',function(){
      WdatePicker({
          dateFmt:'yyyy-MM-dd HH:mm'
      });
    });
});


$(function(){
  var isEdit = false;
  // 表单提交
  $('#createForm').submit(function(e){
    e.preventDefault();
    var eventNameObj        = $('#createForm input[name="event_name"]');
    if ($('.createBtn').hasClass('disabled')) {
      alert('请稍等，正在保存');
      return false;
    }
    if (!$.trim(eventNameObj.val())) {
        showError(eventNameObj, '请填写标题');
        return false;
    }
    $('.createBtn').addClass('disabled');
    $('#createForm').unbind().submit();
  });
});

/**
 * 显示错误信息
 */
function showError(obj, msg)
{
  if (obj.length) {
    var parentObj = obj.closest('.control-group');
    parentObj.addClass('error').find('.help-inline').text(msg)
    //obj.siblings('.help-inline').text(msg).closest('.control-group').addClass('error');
    obj.focus();
  }
  return obj;
}