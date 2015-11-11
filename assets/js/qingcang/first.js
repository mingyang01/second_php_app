function pass(event, shop_id){
    	
        $("#opt-event-id").val(event);
        $("#opt-shop-id").val(shop_id);
        $('#passModelTitle').text('店铺审核通过');
        // @FIXME 兼容排期页面被选中的多少个商品
        $('#passModal').modal('show');
    }

    function refuse(event, shop_id){
    	
        $("#opt-event-id").val(event);
        $("#opt-shop-id").val(shop_id);
        $('#failModelTitle').text('拒绝店铺审核')
        $('#failModal').modal('show')
    }

    $('#submit-pass').click(function(e) {
    	
        var shop_id = $('#opt-shop-id').val();
        var event_id = $('#opt-event-id').val();
        console.log(shop_id);
        comment = $('#passRadiosReason').val()
        var url = '/qingcang/Qfirst/Check'
        $.post(url, {'event':event_id,'checkResult': 20, "comment": comment, "shop_id": shop_id}, function(data){
            console.log(data)
            if (data.errno == 1) {
                $('#passModal').modal('hide');
                alert(data.msg);
                window.location.reload();
            };

        }, 'json')
    });

$(function(){

    $('#submit-fail').click(function(e) {
        
    	var shop_id = $('#opt-shop-id').val();
        var event_id = $('#opt-event-id').val();
        console.log(shop_id);
        comment = $('#failRadiosReason').val()
        var url = '/qingcang/Qfirst/Check'
        $.post(url, {'event':event_id, 'checkResult': 21, "comment": comment, "shop_id": shop_id}, function(data){
            console.log(data)

            if (data.errno == 1) {
                $('#failModal').modal('hide')
                alert(data.msg);
                window.location.reload(); 
            };

        }, 'json')
    });
    
})