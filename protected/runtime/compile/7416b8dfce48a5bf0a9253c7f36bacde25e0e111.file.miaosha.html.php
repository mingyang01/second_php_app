<?php /* Smarty version Smarty-3.1.18, created on 2015-08-18 17:21:40
         compiled from "/home/work/websites/tuan/protected/views/everyDay/miaosha.html" */ ?>
<?php /*%%SmartyHeaderCode:32943437455d2f924452d74-34800367%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '7416b8dfce48a5bf0a9253c7f36bacde25e0e111' => 
    array (
      0 => '/home/work/websites/tuan/protected/views/everyDay/miaosha.html',
      1 => 1439522157,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '32943437455d2f924452d74-34800367',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'to' => 0,
    'syncShowInfo' => 0,
    'area' => 0,
    'item' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.18',
  'unifunc' => 'content_55d2f9244a6266_49842977',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_55d2f9244a6266_49842977')) {function content_55d2f9244a6266_49842977($_smarty_tpl) {?><?php echo $_smarty_tpl->getSubTemplate ("layouts/header.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, null, array(), 0);?>

<title>秒杀-营销产品</title>
<style type="text/css">
td, tr {
	vertical-align: middle !important;
}

.btn-primary {
    background-color: #474760;
}

</style>
<div class="container">
	<div class="row">
		<div class="col-md-12">
			<ol class="breadcrumb">
				<li><a href="/">Home</a></li>
				<li><a href="/event">营销产品</a></li>
				<li class="active">秒杀</li>
			</ol>
		<div id="well" class="well pinned">
                <form class="form-inline" role="form" id="form">
                    <div class="form-group">
                        <label>日期：</label>
                        <input class="picker form-control" id="date" name="to"  type="text" value="<?php echo $_smarty_tpl->tpl_vars['to']->value;?>
"  data-date-format="yyyy-mm-dd">
                    </div>
                    <button type="submit" class="btn btn-default">查看</button>
                    <button type="button" id="save" class="btn btn-primary">保存</button>
                    <a href="/marketing/editMarketingConfig?key=marketing_miaosha_config" class="btn btn-warning" style="margin-left:20px;">编辑配置信息</a>
                    
                    <label style="float:right;">秒杀列表是否同步显示这些商品：
                        <span style="color:red;" class="content"><?php if ($_smarty_tpl->tpl_vars['syncShowInfo']->value['value']==1) {?>是<?php } else { ?>否<?php }?></span> 
                        <a href="javascript:;" class="btn btn-warning btnSetSyncShow" style="margin-left:5px;" data-value="<?php if ($_smarty_tpl->tpl_vars['syncShowInfo']->value['value']==1) {?>0<?php } else { ?>1<?php }?>"><?php if ($_smarty_tpl->tpl_vars['syncShowInfo']->value['value']==1) {?>设置为否<?php } else { ?>设置为是<?php }?></a>
                    </label>
                </form>
        </div>
	</div>
</div>
	<div class="row">
		<div class="col-md-12">
			<table class="table">
				<tbody>
					<tr>
						<td>开始时间</td>
						<td>结束时间</td>
						<td style="text-align:center">1元秒杀</td>
						<td style="text-align:center" colspan="2">精选</td>
					</tr>
					<?php  $_smarty_tpl->tpl_vars['item'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['item']->_loop = false;
 $_smarty_tpl->tpl_vars['key'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['area']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['item']->key => $_smarty_tpl->tpl_vars['item']->value) {
$_smarty_tpl->tpl_vars['item']->_loop = true;
 $_smarty_tpl->tpl_vars['key']->value = $_smarty_tpl->tpl_vars['item']->key;
?>
					<tr>						
						<td><?php echo $_smarty_tpl->tpl_vars['item']->value['stime'];?>
</td>
						<td><?php echo $_smarty_tpl->tpl_vars['item']->value['etime'];?>
</td>
                        
                        <td><input readonly type="text" class="form-control" placeholder="" value="<?php echo $_smarty_tpl->tpl_vars['item']->value['fengqiang'][0];?>
"></td>
                        <td><input readonly type="text" class="form-control" placeholder="" value="<?php echo $_smarty_tpl->tpl_vars['item']->value['jingxuan'][0];?>
"></td>
                        <td><input readonly type="text" class="form-control" placeholder="" value="<?php echo $_smarty_tpl->tpl_vars['item']->value['jingxuan'][1];?>
"></td>
						<td><a class="save">保存</a><a style="margin-left:15px;" class="edit">编辑</a><a style="margin-left:15px;" href="javascript:;" class="clearData">清空</a></td>
					</tr>
					<?php } ?>
				</tbody>
			</table>
		</div>
	</div>
</div>
<script type="text/javascript">
    $('.picker').datepicker({
        format: "yyyy-mm-dd",
        autoclose: true
    }).on('changeDate', function(ev){
        $(this).datepicker('hide');
    });

    $('.edit').on('click', function(e){
    	$(this).closest('tr').find('input').removeAttr('readonly')
    });

    $('.save').on('click', function(e){
      if ($(this).hasClass("disabled")) return false;
      var thisObj = $(this);
    	// alert();
    	var $item = $(this).closest('tr'),
    		$inputs = $item.find('input'),
    		$tds = $item.find('td');

    	if ($inputs.eq(0).val() && $inputs.eq(1).val() && $inputs.eq(2).val()) {
	    	var params = {
	    		'stime': $tds.eq(0).text().trim(),
	    		'etime': $tds.eq(1).text().trim(),
	    		'fengqiang': $inputs.eq(0).val(),
	    		'jingxuan_1' : $inputs.eq(1).val(),
	    		'jingxuan_2' : $inputs.eq(2).val()
	    	}
	       
	    	var objInHtml = thisObj.html();
	       setBtnStatus(thisObj, '提交中...', 'disabled');
	    	$.post('/marketing/saveMiaosha', params, function(data){
	    	  console.log(data);
              if (data.code == 0) {
                thisObj.closest('tr').find('input').attr('readonly', true);
              } else {
                alert(data.msg);
              }
              setBtnStatus(thisObj, objInHtml, 'succ');
	    	}, 'json').error(function(code, data){
	          alert('遇到服务器错误');
	          setBtnStatus(thisObj, objInHtml, 'succ');
	        });
    	} else {
    		alert('请完整填写!')
    	}
    })

    
$(function(){
  $(".clearData").click(function(){
    if ($(this).hasClass("disabled")) return false;
    var thisObj = $(this);
    
    var $item = $(this).closest('tr'),
    $inputs = $item.find('input'),
    $tds = $item.find('td');
    
    if (confirm('您确定要清空'+$tds.eq(0).text().trim()+'的商品吗？') == false) {
      return false;
    }
    
    var params = {
        'stime': $tds.eq(0).text().trim(),
        'etime': $tds.eq(1).text().trim(),
        'fengqiang': $inputs.eq(0).val(),
        'jingxuan_1' : $inputs.eq(1).val(),
        'jingxuan_2' : $inputs.eq(2).val()
    }
    
    var objInHtml = thisObj.html();
    setBtnStatus(thisObj, '提交中...', 'disabled');
     $.post('/marketing/clearMiaosha', params, function(data){
       console.log(data);
       if (data.code == 0) {
         alert('清除成功~');
         window.location.reload();
       } else {
         alert(data.msg);
       }
       setBtnStatus(thisObj, objInHtml, 'succ');
     }, 'json').error(function(code, data){
       alert('遇到服务器错误');
       setBtnStatus(thisObj, objInHtml, 'succ');
     });
  });
  
  
  // 设置是否同步显示
  $(".btnSetSyncShow").click(function(){
    if ($(this).hasClass("disabled")) return false;
    var thisObj = $(this);
    
    var value = thisObj.attr('data-value');
    var valueInfo = '否';
    if (value == 1) {
      valueInfo = '是';
    }
    
    if (confirm('秒杀列表是否同步显示这些商品改为： '+valueInfo+' ？') == false) {
      return false;
    }
    
    var params = {'value':value};
    var objInHtml = thisObj.html();
    setBtnStatus(thisObj, '提交中...', 'disabled');
    $.post('/marketing/changeMiaoshaSyncShow', params, function(data){
       console.log(data);
       if (data.succ == 1) {
         alert('设置成功~');
         window.location.reload();
       } else {
         alert(data.msg);
       }
       setBtnStatus(thisObj, objInHtml, 'succ');
     }, 'json').error(function(code, data){
       alert('遇到服务器错误');
       setBtnStatus(thisObj, objInHtml, 'succ');
     });
    
  });
});
</script><?php }} ?>
