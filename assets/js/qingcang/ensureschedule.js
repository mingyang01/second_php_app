$(function(){
	
	var loading = $("#loading");
	var shop_list = $('#shop_list');
	var count_op = $('#shop_count_op');
	var type = $('#tab_type').val();
	var shop = $('#tab_shop_id').val();
	var event = $('#tab_event_id').val();

	var callAjax = {
		request : function(type, url, data, callback) {
			url = '/qingcang/QensureSchedule/'+url;
			$.ajax({
				url : url,
				type : type,
				data : data,
				dataType : 'json',
				success : function(json) {
					if (json.errno != 1) {
						alert(json.msg);
						loading.hide();
					} else {
						result = json.result;
						return callAjax.callback(callback, result);
					}
				}
			});
		},
		callback : function(callback, result) {
			callback(result);
		}
	}
	
	var list = {};
	var formatShopList = function (result){
		
		var countHtml = '';
		countHtml += '<div style="padding-top:4px;color:#fd6699;font-size:18px" id="shop_cont">店铺数目:'+result.count+'</div>';
		if (result.opt) {
			countHtml += '<p><a href="#" onclick="javascript:saveShopSort('+result.type+')"><button type="button" class="pull-right btn btn-default">保存排序</button>';
		}
		
		var listHtml = '';
		if(result.data=='' || result.data==undefined) {
			listHtml += '<td class="col-md-12" colspan=8 style="line-height:50px;white-space:nowrap; ">没有纪录</td>';
		} else {
			$.each(result.data,function(i, item) {
				
				listHtml += '<tr up="'+item.create_time+'" id="' + item.id + '"><td class="col-md-1 shop_drag" style="line-height:50px;">'+(i+1)+'</td>';
				listHtml += '<td class="col-md-1" style="line-height:50px;">'+item.shop_id+'</td>';
				listHtml += '<td class="col-md-1" style="line-height:50px;white-space:nowrap; ">'+ item.shop_nick +'<br/>';
				if(item.bg_img == '' || item.bg_img == undefined){
					listHtml += '</td>';
				}else{
					var bg = item.bg_img;
					listHtml += '<img alt="'+item.title+'" src="'+bg+'" style="height:50px;width:200px"></td>';
				}
				
				listHtml += '<td class="col-md-1" style="line-height:50px;white-space:nowrap; ">'+item.start_time+'<br />'+item.end_time+'</td>';
				listHtml += '<td class="col-md-1" style="line-height:50px;white-space:nowrap; ">'+item.create_time+'</td>';
				listHtml += '<td class="col-md-1" style="line-height:50px;white-space:nowrap; ">'+item.updator+'</td>';
				listHtml += '<td class="col-md-1" style="line-height:50px;white-space:nowrap; "><img alt="'+item.title+'" src="'+item.mark+'" style="width:50px;height:50px"></td>';
				listHtml += '<td class="col-md-1" style="line-height:50px;white-space:nowrap; ">'+item.status+'</td>';
				
				if (result.type ==1 || result.type ==2 || result.type==3 || result.type==4){
					listHtml += '<td class="col-md-1" style="line-height:50px;white-space:nowrap; ">'+item.total_tid+'</td>';
				}
				listHtml += '<td class="col-md-1" style="line-height:50px;white-space:wrap; ">'+item.tab+'</td>';
				listHtml += '<td class="col-md-2" style="line-height:50px;">';
				listHtml += '<a href="/qingcang/QensureSchedule/onlineShopGoods?event='+ event +'&id='+item.id+'" target="_blank"><button type="button" style="margin-top:8px" class="btn btn-default">查看商品详情</button></a>&nbsp;&nbsp;';
				listHtml += '<a href="javascript:void(0);" onclick="editShopInfo('+item.id+')"><button type="button" style="margin-top:8px" class="btn btn-default">编辑</button></a>';
				if (event!=2005) {
					listHtml += '<a href="javascript:void(0);" onclick="addShopTab('+item.id+')"><button type="button" style="margin-top:8px" class="btn btn-default">添加tab</button></a>';
				}
				listHtml += '<a href="javascript:void(0)" onclick="delShopInfo('+item.id+')"><button type="button" style="margin-top:8px;margin-left:5px" class="btn btn-default">删除</button></a>';
				listHtml += '</td></tr>';
				list[item.id] = item;
			});
		}
		
		//loading.hide();
		count_op.html(countHtml);
		shop_list.html(listHtml);
		shop_list.sortable({
			revert: true,
			sortupdate: function() {}
		});
	}
	callAjax.request('POST','getShopList',{event:event, type:type, shop:shop},formatShopList);

	var callbackCommon = function (result){
		alert(result.msg);
	}
})
	//删除店铺信息
	function delShopInfo(id) {
		
		if(window.confirm('你确定要删除此店铺么？')){
			$.post('/qingcang/QensureSchedule/DelShop', {id:id}, function(data){
				alert(data.msg);
		        if (data.errno == 1){
		        	location.reload();
		        }
		    }, 'json');
		}else{
			
		}
	}

	//店铺排序
	function saveShopSort(type) {
		
		var itemBox = $('#shop_list');
		var boxChild = itemBox.children("tr");
		var uniq = '';
		$.each(boxChild, function(i, item) {
			uniq += item.id;
			uniq += ',';

		});
		console.log(uniq);
		console.log(type);
		
		$.ajax({
			url : '/qingcang/QensureSchedule/SortShop',
			type : 'post',
			data : {sort : uniq, type:type},
			dataType : 'json',
			success : function(json) {
				alert(json.msg);
				if (json.errno == 1) {
					window.location.reload(); 
				}
			}
		});

	}


	//编辑店铺信息编辑框展现
	function editShopInfo(id) {
	
		$("#editModal").modal('show');
		//获取店铺的详细信息
		$.ajax({
			url : '/qingcang/QensureSchedule/getShopInfo',
			type : 'get',
			data : {'id':id},
			dataType : 'json',
			success : function(json) {
				if (json.errno != 1) {
					alert(json.msg);
				} else {
					result = json.result;
					$("input[name='shop_id']").val(result.shop_id);
					$("input[name='start_time']").val(result.start_time);
					$("input[name='end_time']").val(result.end_time);
					$("input[name='title']").val(result.title);
					$("input[name='banner']").val(result.banner);
					$("input[name='id']").val(result.id);
					$("input[name='mark']").val(result.mark);
				}
			}
		});
	}

	//保存店铺修改
	function SaveUpdate() {
		
		var start_time = $("input[name='start_time']").val();
		var end_time = $("input[name='end_time']").val();
		var title = $("input[name='title']").val();
		var banner = $("input[name='banner']").val();
		var id = $("input[name='id']").val();
		var mark = $("input[name='mark']").val();
		
		
		if (!start_time || !end_time) {
			alert("开始时间和结束时间必须填");
		}
		if (!title || !banner || !mark) {
			alert("不能有设置为空");
		}
		//修改店铺信息
		$.ajax({
			url : '/qingcang/QensureSchedule/UpdateShopInfo',
			type : 'post',
			data : {'id':id, 'start_time':start_time, 'end_time':end_time, 'title':title, 'banner':banner, 'mark':mark},
			dataType : 'json',
			success : function(json) {
				alert(json.msg);
				if (json.errno == 1) {
					window.location.reload(); 
				}
			}
		});
	}
	
	//	添加和修改店铺的tab
	function addShopTab(id) {
	
		$("#shopTabModal").modal('show');
		//获取店铺的详细信息
		$.ajax({
			url : '/qingcang/QensureSchedule/getShopTab',
			type : 'get',
			data : {'id':id},
			dataType : 'json',
			success : function(json) {
				if (json.errno != 1) {
					alert(json.msg);
				} else {
					result = json.result;
					$("input[name='shop_id']").val(result.shop_id);
					$("textarea[name='shop_tab']").val(result.tab);
					$("input[name='id']").val(result.id);
				}
			}
		});
	}

	function SaveShopTab(){
		
		var id = $("input[name='id']").val();
		var tab = $("textarea[name='shop_tab']").val();
		
		//修改店铺信息
		$.ajax({
			url : '/qingcang/QensureSchedule/editShopTab',
			type : 'post',
			data : {'id':id, 'tab':tab},
			dataType : 'json',
			success : function(json) {
				alert(json.msg);
				if (json.errno == 1) {
					window.location.reload(); 
				}
			}
		});
	}