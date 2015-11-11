	//店铺审核通过
    function pass(shop_id, event_id){
console.log(event_id);
    	// @FIXME 兼容排期页面被选中的多少个商品
        $('#passModelTitle').text('店铺商品排期');
        
    	if (event_id!=2005) {
    		$.post('/qingcang/Qactivity/List', {'id':event_id}, function(json){
    	          if (json.errno == 1) {
    	        	  var data = json.result;
    	        	  $(".editDateCon").find("input[name='start_time']").val(data.preheat_time);
    	              $(".editDateCon").find("input[name='end_time']").val(data.end_time);
    	              $(".editDateCon").find("input[name='start_time']").removeClass("myDatePicker");
    	              $(".editDateCon").find("input[name='end_time']").removeClass("myDatePicker");
    	              $('#end_time').attr("disabled",true);
//    	              
//    	              $(".editDateCon").attr("start_time",'disabled');
//    	              $(".editDateCon").getElementById("end_time").disabled=true;
    	          } else {
    	            alert(json.msg);
    	          }
    	        },'json').error(function(code,data){
    	          alert('遇到服务器错误');
    	        });
    	}
    	 
        $("#opt-shop-id").val(shop_id);
        $("#opt-event-id").val(event_id);
        $('#passModal').modal('show');
        
    }


    //店铺拒绝
    function refuse(shop_id, event_id){

	    $('#failModelTitle').text('店铺商品驳回排期')
        $("#opt-shop-id").val(shop_id);
	    $("#opt-event-id").val(event_id);
        $('#failModal').modal('show')
    }

$(function(){
    
    /** 获取推荐容器中的id 返回对象 */
    function getGids() {
      
      var ids = [];
      $("#box-container").find(".thumbnail[class*='selected']").each(function(){
          var gid = $(this).attr("data-gid");
          if (gid) {
              ids.push(gid);
          }
      });
      return ids.join(",");
    }
    
    
    $(".btnEditRecommendData").click(function(){
        if ($(this).hasClass("disabled")) return false;
        var thisObj = $(this);
        var shop_id = $(".editDateCon").find("input[id='opt-shop-id']").val();
        var startTime   = $(".editDateCon").find("input[name='start_time']").val();
        var endTime   = $(".editDateCon").find("input[name='end_time']").val();
        var title   = $(".editDateCon").find("input[name='title']").val();
        var event_id   = $(".editDateCon").find("input[id='opt-event-id']").val();

        var timeRe = /^[0-9]{4}-[0-9]{2}-[0-9]{2} [0-9]{2}:[0-9]{2}:[0-9]{2}$/;
        if (!timeRe.test(startTime)) {
          alert('请选择正确的开始时间');
          return false;
        }
        if (!timeRe.test(endTime)) {
            alert('请选择正确的结束时间');
            return false;
          }
        var objInHtml = thisObj.html();
        setBtnStatus(thisObj, '提交中...', 'disabled');
        $.post('/qingcang/Qschedule/CanSchedule', {'event':event_id, 'start_time':startTime, 'end_time':endTime, 'title':title, 'shop_id':shop_id}, function(json){
          if (json.errno == 1) {
        	  if (json.result.err_num >=1) {
        		  var a=confirm("排期检测失败"+json.result.err_num +"个,失败原因是："+json.result.msg+",是否继续排期？");
        		  if (a == true){
        		  	pass(shop_id, startTime, endTime, title);
        		  } else {
        			  $('#passModal').modal('hide');
        		  }
        	  } else {
        		  pass(shop_id, startTime, endTime, title, event_id);
        	  }
            
          } else {
            alert(json.msg);
          }
        },'json').error(function(code,data){
          alert('遇到服务器错误');
        });
      });
    
    function pass(shop_id, startTime, endTime, title, event) {
    	$.post('/qingcang/Qschedule/schedule', {'event':event, 'start_time':startTime, 'end_time':endTime, 'title':title, 'shop_id':shop_id}, function(data){
    		
    		result = eval( "(" + data + ")" );
    		if (result.errno = 1) {
				  
				  alert('排期成功,成功商品数为：'+ result.result.succ_num);
				  window.location.reload(); 
			  } else {
				  alert(result.msg);
			  }
		  });
    }

    $('#submit-fail').click(function(e) {
        
    	var shop_id = $('#opt-shop-id').val();
    	var event_id = $('#opt-event-id').val();
        var comment = $('#failRadiosReason').val();
 		var ids = getGids();
        
        if (shop_id.length > 0) {
        	var url = '/qingcang/Qschedule/checkByShop';
        }
        $.post(url, {'event':event_id, 'checkResult': 51, "comment": comment, "shop_id": shop_id, "ids": ids}, function(data){
            console.log(data)

            if (data.errno == 1) {
                $('#failModal').modal('hide');
                alert(data.msg);
                window.location.reload(); 
            };

        }, 'json')
    });
    
})