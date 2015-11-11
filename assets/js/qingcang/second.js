$(function(){
	
	//店铺审核通过
    $('.pass').click(function(e){
    	console.log(111);
        e.stopPropagation();
        
        var thisObj = $(this);
        var shopObj = thisObj.closest("a");
        var shop_id = shopObj.attr("data-shopid");
        var event_id = $('.row').find('#eventid').val();
	  
        // @FIXME 兼容排期页面被选中的多少个商品
        $('#passModelTitle').text('店铺审核通过');
        $("#opt-event-id").val(event_id);
        $("#opt-shop-id").val(shop_id);
        $('#passModal').modal('show');
        
    });
    
    

    //店铺拒绝
    $('.refuse').click(function(e){
    	
    	e.stopPropagation();
        
        var thisObj = $(this);
	    var shopObj = thisObj.closest("a");
	    var shop_id = shopObj.attr("data-shopid");
	    var event_id = $('.row').find('#eventid').val();
	    
	    $('#failModelTitle').text('拒绝店铺审核')
        $("#opt-shop-id").val(shop_id);
	    $("#opt-event-id").val(event_id);
        $('#failModal').modal('show')
    });

    $('#submit-pass').click(function(e) {
    	
        var shop_id = $('#opt-shop-id').val();
        var event_id = $('#opt-event-id').val();
        var comment = $('#passRadiosReason').val();
        var ids = [];
        
        if (shop_id.length > 0) {
        	var url = '/qingcang/Qsecond/checkByShop';
        } else {
        	var url = '/qingcang/Qsecond/checkByGoods';
        	$selected = $('.selected');
        	$selected.each(function(k, v) {
                ids.push($(v).data('gid'));
            })
        }
        $.post(url, {'event':event_id,'checkResult': 40, "comment": comment, "shop_id": shop_id, "ids": ids}, function(data){
            console.log(data)
            if (data.errno == 1) {
                $('#passModal').modal('hide');
                alert(data.msg);
                window.location.reload();
            };

        }, 'json')
    });

    $('#submit-fail').click(function(e) {
        
    	var shop_id = $('#opt-shop-id').val();
    	var event_id = $('#opt-event-id').val();
        var comment = $('#failRadiosReason').val();
        var ids = [];
        
        if (shop_id.length > 0) {
        	var url = '/qingcang/Qsecond/checkByShop';
        } else {
        	var url = '/qingcang/Qsecond/checkByGoods';
        	$selected = $('.selected');
        	$selected.each(function(k, v) {
                ids.push($(v).data('gid'));
            })
        }
        $.post(url, {'event':event_id,'checkResult': 41, "comment": comment, "shop_id": shop_id, "ids": ids}, function(data){
            console.log(data)

            if (data.errno == 1) {
                $('#failModal').modal('hide')
                alert(data.msg);
                window.location.reload(); 
            };

        }, 'json')
    });
    
})