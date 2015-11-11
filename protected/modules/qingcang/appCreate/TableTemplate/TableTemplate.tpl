<!--===== 搜索区 start =====-->
<div class="row-fluid" style="margin-top: 20px;">
    <div class='well' style="height: 100px;padding: 10px;margin-bottom:0px;">
        <form action="#" method="get" class="form-horizontal" id="search-form">
            <div class="row">
                <div class='span4'>
                  <div class="control-group">
                    <label class="control-label">菜单 ID:</label>
                    <div class="controls">
                      <textarea style='height:75px; width:100%; margin-bottom:3px' name ='menu_ids' class='menu_ids' placeholder="菜单ID，以半角逗号','分隔"></textarea>
                    </div>
                  </div>
                    
                </div>
                <div class="span4">
                  <div class="control-group">
                    <label class="control-label">买家ID :</label>
                    <div class="controls">
                      <textarea style='height:75px; width:100%; margin-bottom:3px' name ='user_ids' class='user_ids' placeholder="用户ID，以半角逗号','分隔"></textarea>
                    </div>
                  </div>
                </div>
                <div class="span4">
                      <div class="controls-group">
                          <div class="controls">
                            <button type="submit" class="btn btn-success">查  询</button>
                          </div>
                      </div>
                      <div class="controls-group">
                      </div>                              
                </div>
            </div>
        </form>
    </div>
</div>

<script>
$(function() {
	//搜索
	$("#search-form").submit(function(){
		$('#tableList').datagrid({
			url: '/risk/TableTemplate/getData?'+ $("#search-form").serialize() 
		});
		return false;
	});
});
</script>

<!--===== 搜索区 end =====-->




<!--===== 内容区 start =====-->
<div class="row-fluid" style="margin-top: 20px;">
	<div class="widget-box">
		<div class="widget-title">
			<div>
				<div class="btn-group pull-right" style="margin:3px">
					<button class="btn btn-default btn-xs" id="button1">
						<i class="icon-wrench"></i> 直接操作
					</button>          
					<button class="btn btn-default btn-xs" id="button2">
						<i class="icon-wrench"></i> 弹层操作
					</button>          
				</div>
			</div>
			<ul class="nav nav-tabs" id="nav">
				<li class="active">
					<a href="#">Table List</a>
				</li>
			</ul>
		</div>
		<div class="widget-content tab-content">
			<table id="tableList" style="height:600px"
				data-options="
					view:bufferview,
					pageSize:50,
					autoRowHeight:false,
					rownumbers:true,
					singleSelect:false,
					method:'get'">
				<thead>
					<tr>
						<th data-options="field:'ck',checkbox:true,align:'left'"></th>
						<th data-options="field:'menu_id',align:'left',width:110"  >菜单ID</a></th>
						<th data-options="field:'menu_title',align:'left',width:200" >菜单名称</a></th>
						<th data-options="field:'menu_url',align:'left',width:200"  >菜单地址</a></th>
					</tr>
				</thead>
			</table>
		</div>
	</div>
</div>

<script>
$(function() {
	//表格初始化
	var datagridUrl = '/risk/TableTemplate/getData';
	$('#tableList').datagrid({
		url: datagridUrl
	});
});
</script>

<script>
$("#button1").click(function(){
	//处理所选数据
	var ids = getIds();
	if (ids != ''){
		if (confirm("是否需要进行操作？")){
			$.post('/risk/TableTemplate/other', {
				ids : ids 
			}, function(data) {
				alert(data.msg);
				if (data.code == 1){
					//处理成功后刷新表格数据
					reloadTableData();
				}
			}, 'json');
		}
	}
});

$("#button2").click(function(){
	$('#modalPage').modal('show');
});

//获取所选id
function getIds(){
	var ids = [];
	var rows = $('#tableList').datagrid('getSelections');
	for(var i = 0; i < rows.length; i++) {
		ids.push(rows[i].menu_id);
	}
	var idsStr = ids.join(',');
	return idsStr;
}

//刷新table数据
function reloadTableData(){
	$('#tableList').datagrid({
		url: '/risk/TableTemplate/getData?'+ $("#search-form").serialize() 
	});
	return false;
}
</script>
<!--===== 内容区 end =====-->



<!--===== Modal弹出层 start =====-->
<div id="modalPage" class="modal fade" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
				<h3 id="myModalLabel">
					Title
				</h3>
			</div>
			<div class="modal-body">
				内容区
			</div>
			<input type="hidden" id="timeGroup_add" name="timeGroup_add" value="" />
			<div class="modal-footer">
				<button id="saveButton" class="btn btn-primary">保存</button>
			</div>
		</div>
	</div>
</div>

<script>
$("#modalPage").find("#saveButton").click(function(){
	alert('save button');
	$('#modalPage').modal('hide');

	//处理后刷新表格数据
	reloadTableData();
});
</script>
<!--===== Modal弹出层 end =====-->







