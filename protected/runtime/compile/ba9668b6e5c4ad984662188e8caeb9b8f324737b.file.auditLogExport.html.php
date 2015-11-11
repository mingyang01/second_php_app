<?php /* Smarty version Smarty-3.1.18, created on 2015-08-19 15:22:15
         compiled from "/home/work/websites/tuan/protected/views/audit/auditLogExport.html" */ ?>
<?php /*%%SmartyHeaderCode:177423479155d42ea7248d57-01491793%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'ba9668b6e5c4ad984662188e8caeb9b8f324737b' => 
    array (
      0 => '/home/work/websites/tuan/protected/views/audit/auditLogExport.html',
      1 => 1439522157,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '177423479155d42ea7248d57-01491793',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'date' => 0,
    'step' => 0,
    'statistics' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.18',
  'unifunc' => 'content_55d42ea728f5f3_32603095',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_55d42ea728f5f3_32603095')) {function content_55d42ea728f5f3_32603095($_smarty_tpl) {?><?php echo $_smarty_tpl->getSubTemplate ("layouts/header.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, null, array(), 0);?>


<script src="/assets/lib/bufferview.js"></script>
<div class="container">
<div class="row">
    <div class="col-md-12">
        <ol class="breadcrumb">
            <li><a href="/">Home</a></li>
            <li class="active">审核明细</li>
        </ol>
        <div id="well" class="well">
            <form class="form-inline" role="form" id="form">
                <div  class="form-group  date datepicker">
                    <input class="form-control" name="date" type="text" value="<?php echo $_smarty_tpl->tpl_vars['date']->value;?>
" data-date-format="yyyy-mm-dd">
                    <span class="glyphicon glyphicon-calendar add-on"></span>  
                </div>
                <div class="form-group">
                    <select style="width:170px;" name="step" class="form-control">
                        <option <?php if ($_smarty_tpl->tpl_vars['step']->value=='2') {?>selected<?php }?> value="2">初审阶段</option>

                        <option <?php if ($_smarty_tpl->tpl_vars['step']->value=='3') {?>selected<?php }?> value="3">复审阶段</option>

                        <option <?php if ($_smarty_tpl->tpl_vars['step']->value=='4') {?>selected<?php }?> value="4">样申阶段</option>
                    </select>
                </div>
                <button id="submit" class="btn btn-default">查看</button>
                <button id="export" class="btn btn-default">导出</button>
            </form>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-12"><div id="main" style="height:400px;"></div></div>
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

        $('#form').submit(function(e){
            e.preventDefault();
        });

        $('#export').click(function(e){
            e.stopPropagation
            e.preventDefault();
            window.open('/audit/ExportAuditLog?excel=1&' + $('#form').serialize());
        })

        $('#submit').click(function(e){
            e.stopPropagation
            e.preventDefault();
            window.location = '/audit/ExportAuditLog?' + $('#form').serialize();
        })
    });

</script>

<script src="/assets/lib/echarts/echarts.js"></script>
<script type="text/javascript">
    require.config({
        paths: {
            echarts: '/assets/lib/echarts'
        }
    });
    require(
        [
            'echarts',
            'echarts/chart/line',   // 按需加载所需图表，如需动态类型切换功能，别忘了同时加载相应图表
            'echarts/chart/bar'
        ],
        function (ec) {
            var myChart = ec.init(document.getElementById('main'));
            var option = option = {
                title : {
                    text: '审核明细',
                    subtext: ''
                },
                tooltip : {
                    trigger: 'axis'
                },
                legend: {
                    data:['成功','失败', '汇总']
                },
                toolbox: {
                    show : true,
                    feature : {
                        mark : {show: true},
                        dataView : {show: true, readOnly: false},
                        magicType : {show: true, type: ['line', 'bar']},
                        restore : {show: true},
                        saveAsImage : {show: true}
                    }
                },
                calculable : true,
                xAxis : [
                    {
                        type : 'category',
                        data : ['初审阶段','复审阶段','样申阶段','排期阶段']
                    }
                ],
                yAxis : [
                    {
                        type : 'value'
                    }
                ],
                series : [
                    {
                        name:'成功',
                        type:'bar',
                        data:[<?php echo implode(', ',$_smarty_tpl->tpl_vars['statistics']->value[0]);?>
],
                    },
                    {
                        name:'失败',
                        type:'bar',
                        data:[<?php echo implode(', ',$_smarty_tpl->tpl_vars['statistics']->value[1]);?>
],
                        
                    },
                    {
                        name:'汇总',
                        type:'bar',
                        data:[<?php echo implode(', ',$_smarty_tpl->tpl_vars['statistics']->value[2]);?>
],
                        
                    }
                ]
            };
                    
            myChart.setOption(option);
        }
    );
</script><?php }} ?>
