/**
 *  样审白名单管理
 */
$(function(){
	
	$('#add').click(function (e){
		
		var ids = $('#shop_id').val();
		console.log(ids);
		$.post('/WhiteShop/Add',{ 'ids_str':ids },function(result){
			alert(result.msg);
			if (result.errno == 1) {
            	window.location.reload();
			}
            
        },'json');
    });
	
	//查询
	$('#search_submit').click(function (e){
		
		var id = $('#search_id').val();
		var operate = $('#operate').val();
		
		window.location.href ="/WhiteShop/list?id="+id+"&operate="+operate;
		
    });
	
	
});

function delWihte(shop_id) {
	
	$.post('/WhiteShop/Del',{ 'id':shop_id },function(result){
		console.log(result);
		alert(result.msg);
		if (result.errno == 1) {
        	window.location.reload();
		}
    },'json');
}
