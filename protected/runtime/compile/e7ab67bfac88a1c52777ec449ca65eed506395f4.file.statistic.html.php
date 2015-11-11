<?php /* Smarty version Smarty-3.1.18, created on 2015-08-19 15:22:17
         compiled from "/home/work/websites/tuan/protected/views/audit/statistic.html" */ ?>
<?php /*%%SmartyHeaderCode:43963853355d42ea9ab0029-61620707%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'e7ab67bfac88a1c52777ec449ca65eed506395f4' => 
    array (
      0 => '/home/work/websites/tuan/protected/views/audit/statistic.html',
      1 => 1439522157,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '43963853355d42ea9ab0029-61620707',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'from' => 0,
    'to' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.18',
  'unifunc' => 'content_55d42ea9ae2412_49341443',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_55d42ea9ae2412_49341443')) {function content_55d42ea9ae2412_49341443($_smarty_tpl) {?><?php echo $_smarty_tpl->getSubTemplate ("layouts/header.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, null, array(), 0);?>


<script src="/assets/lib/bufferview.js"></script>
<div class="container">
<div class="row">
    <div class="col-md-12">
        <ol class="breadcrumb">
            <li><a href="/">Home</a></li>
            <li class="active">用户审计</li>
        </ol>
        <div id="well" class="well">
            <form class="form-inline" role="form" id="form">
                <div  class="form-group  date datepicker">
                    <input class="form-control" name="from"  type="text" value="<?php echo $_smarty_tpl->tpl_vars['from']->value;?>
"  data-date-format="yyyy-mm-dd">
                    <span class="glyphicon glyphicon-calendar add-on"></span>  
                </div>
                <div  class="form-group  date datepicker">
                    <input class="form-control" name="to" type="text" value="<?php echo $_smarty_tpl->tpl_vars['to']->value;?>
" data-date-format="yyyy-mm-dd">
                    <span class="glyphicon glyphicon-calendar add-on"></span>  
                </div>

                <button id="submit" class="btn btn-default">查看</button>
            </form>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-12">
        <table id="tt" title="" style="height:400px"
                data-options="view:bufferview,rownumbers:true,singleSelect:true,collapsible:false,
                    method:'get',
                    remoteSort:false,
                    multiSort:false,
                    autoRowHeight:false
                ">
            <thead data-options="frozen:true">
                <tr>
                    <th data-options="field:'date',width:100">日期</th>
                    <th data-options="field:'shop'">报名商家</th>
                    <th data-options="field:'twitter'">报名商品</th>
                    <th data-options="field:'10'">等待初审</th>
                    <th data-options="field:'20'">初审成功</th>
                    <th data-options="field:'21'">初审失败</th>
                    <th data-options="field:'20'">等待复审</th>
                    <th data-options="field:'30'">复审成功</th>
                    <th data-options="field:'31'">复审失败</th>
                    <th data-options="field:'30'">等待样申</th>
                    <th data-options="field:'41'">样申失败</th>
                    <th data-options="field:'40'">等待排期</th>
                    <th data-options="field:'50'">排期成功</th>
                    <th data-options="field:'51'">排期失败</th>
                </tr>
            </thead>
        </table>
    </div>
</div>

</div>

<script type="text/javascript">

</script>
<script>
    $(function(){
        $('.datepicker').datepicker({
            format: "yyyy-mm-dd",
            autoclose: true
        }).on('changeDate', function(ev){
            $(this).datepicker('hide');
        });

        var url = "/audit/statistic?data=1&from=" + $('[name="from"]').val() + "&to=" + $('[name="to"]').val();
        $('#tt').datagrid({
            url: url
            // , onLoadSuccess: function(){

            // }
        });
        // $('#submit').click();
        $('#form').submit(function(e){
            e.preventDefault();
        });

        $('#submit').click(function(e){
            var url = "/audit/statistic?data=1&from=" + $('[name="from"]').val() + "&to=" + $('[name="to"]').val();
            e.preventDefault();
            // $.ajax({
            //     url: url,
            //     type: "GET",
            //     dataType : "json",
            //     success: function(data) {
            //         $('#tt').datagrid('loadData', {rows:data, length:data.length});
            //     },
            //     async: true
            // });
            $('#tt').datagrid({
                url: url
                // , onLoadSuccess: function(){

                // }
            });
            return false;

        });
    });

</script><?php }} ?>
