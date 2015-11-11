$(function(){
  /**
   * 批量保存排期
   */
	var byId = function (id) { return document.getElementById(id); };
	Sortable.create(byId('good_info'), {
		animation: 150,
		draggable: '.good_info',
		handle: '.thumbnail'
	});
});

function saveShopSort(shop_id, id) {
	
	var itemBox = $('#good_info');
	var boxChild = itemBox.children(".good_info").children(".thumbnail");
	var uniq = '';
	$.each(boxChild, function(i, item) {
		tid= item.attributes["data-twitter"].value;
		uniq += tid+','+i+';';

	});
	$.ajax({
		url : '/qingcang/QensureSchedule/SortShoptid',
		type : 'post',
		data : {sort : uniq, shop_id:shop_id, id:id},
		dataType : 'json',
		success : function(json) {
			alert(json.msg);
			if (json.errno == 1) {
				window.location.reload(); 
			}
		}
	});
}

