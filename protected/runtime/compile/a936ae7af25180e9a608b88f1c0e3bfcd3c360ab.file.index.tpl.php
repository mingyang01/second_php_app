<?php /* Smarty version Smarty-3.1.18, created on 2015-11-10 12:31:33
         compiled from "/home/work/websites/tuan/protected/views/activity/index.tpl" */ ?>
<?php /*%%SmartyHeaderCode:191272514055cdc0680562b2-44645792%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'a936ae7af25180e9a608b88f1c0e3bfcd3c360ab' => 
    array (
      0 => '/home/work/websites/tuan/protected/views/activity/index.tpl',
      1 => 1447129888,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '191272514055cdc0680562b2-44645792',
  'function' => 
  array (
  ),
  'version' => 'Smarty-3.1.18',
  'unifunc' => 'content_55cdc068166c65_31681186',
  'variables' => 
  array (
    'event_id' => 0,
    'time' => 0,
    'area' => 0,
    'tab' => 0,
    'item' => 0,
    'data' => 0,
  ),
  'has_nocache_code' => false,
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_55cdc068166c65_31681186')) {function content_55cdc068166c65_31681186($_smarty_tpl) {?><?php echo $_smarty_tpl->getSubTemplate ("layouts/header.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, null, array(), 0);?>

<script type="text/javascript" src="/assets/lib/bootstrap-datepicker.js"></script>
<style type="text/css" src="/assets/css/datepicker.css"></style>
<script src="/assets/lib/My97DatePicker/WdatePicker.js"></script>
<style>
.pinActive {
    box-shadow: 0 10px 6px rgba(0,0,0,.12),0 1px 6px rgba(0,0,0,.12) !important;
    /*width: 100%!important;*/
    background-color: white;
    z-index:999;
}

.tool a, .active a {
    border: 0px !important;
    color: white !important;
    background: #f46 !important;
    border-radius: 0px !important;
}

.btn-succ{
    background: #f46 !important;
    color:#fff;
}
.btn-succ:hover{
    color:#fff;
}

</style>
<script src='/assets/lib/js/Sortable.js'></script>
<div class="container">
    <!-- 面包屑 -->
    <div class="row">
        <div class="col-md-12">
            <ol class="breadcrumb">
                <li><a href="/">Home</a></li>
                <li><a href="active">主题活动</a></li>
            </ol>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="well clearfix" style="padding-left: 0;">
                <div class="col-md-6" style="padding-left: 0;">
                    <div class="col-md-2">
                        <input id="event_id" class="form-control" value="<?php echo $_smarty_tpl->tpl_vars['event_id']->value;?>
">
                    </div>
                    <div class="col-md-4" style="padding-right:0px;">
                        <input class="picker form-control time-box" id="date" name="to"  type="text" <?php if ($_smarty_tpl->tpl_vars['time']->value) {?>value="<?php echo $_smarty_tpl->tpl_vars['time']->value;?>
"<?php }?>  data-date-format="yyyy-mm-dd hh:ii:ss">
                        <input id="area" type="hidden" value="<?php echo $_smarty_tpl->tpl_vars['area']->value;?>
">
                        <input id="event_time" type="hidden" value="<?php echo $_smarty_tpl->tpl_vars['time']->value;?>
">
                    </div>
                    <div class="col-md-6">
                        <button class="btn btn-default btn-succ getByTime" >查看</button>
                        <button class="btn btn-primary exportBtn">导出</button>
                    </div>
                </div>
                <div class="col-md-6 text-right" style="padding-right:0px;">
                    <button data-sort="price_desc" class="btn btn-default sort-btn">价格升序</button>
                    <button data-sort="price_asc" class="btn btn-default sort-btn">价格降序</button>
                    <button data-sort="sales_desc" class="btn btn-default sort-btn">销量升序</button>
                    <button data-sort="sales_asc" class="btn btn-default sort-btn">销量降序</button>
                    <?php if ($_smarty_tpl->tpl_vars['event_id']->value=='2052') {?>
                    <button class="btn btn-default auto-divide">划分区域</button>
                    <?php } else { ?>
                    <a class="btn btn-default" href="/activity/areaDivide?event_id=<?php echo $_smarty_tpl->tpl_vars['event_id']->value;?>
">划分区域</a>
                    <?php }?>
                </div>
            </div>
        </div>
    </div>
    <!-- 顶部标签页 -->
    <div class="row" >
        <div class="col-md-12 pinned ">
            <div role="tabpanel">
                <!-- Nav tabs -->
                <ul class="nav nav-tabs" role="tablist">
                    <?php  $_smarty_tpl->tpl_vars['item'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['item']->_loop = false;
 $_smarty_tpl->tpl_vars['key'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['tab']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['item']->key => $_smarty_tpl->tpl_vars['item']->value) {
$_smarty_tpl->tpl_vars['item']->_loop = true;
 $_smarty_tpl->tpl_vars['key']->value = $_smarty_tpl->tpl_vars['item']->key;
?>
                    <li <?php if ($_smarty_tpl->tpl_vars['area']->value==$_smarty_tpl->tpl_vars['item']->value['id']) {?>class="tool"<?php }?> >
                        <a data-area="<?php echo $_smarty_tpl->tpl_vars['item']->value['id'];?>
" href="/activity/index?event_id=<?php echo $_smarty_tpl->tpl_vars['event_id']->value;?>
&area=<?php echo $_smarty_tpl->tpl_vars['item']->value['id'];?>
&time=<?php echo $_smarty_tpl->tpl_vars['time']->value;?>
">
                            <?php echo $_smarty_tpl->tpl_vars['item']->value['name'];?>

                        </a>
                    </li>
                    <?php } ?>
                    <li role="presentation" class="tool pull-right">
                        <a id="schedule">保存</a>
                    </li>
                    <li role="presentation" class="tool pull-right">
                        <a class="btn btn-primary" href="/eventGoods/scheduleGoods?event_id=<?php echo $_smarty_tpl->tpl_vars['event_id']->value;?>
&schedule_start_time=<?php echo date("Y-m-d H:i:s",strtotime($_smarty_tpl->tpl_vars['time']->value));?>
&schedule_end_time=<?php echo date("Y-m-d H:i:s",strtotime("+3days",strtotime($_smarty_tpl->tpl_vars['time']->value)));?>
" >排期</a>
                    </li>
                    <li role="presentation" class="tool pull-right">
                        <a class="btn btn-primary" href="/audit/sample?type=1&business=3&event=<?php echo $_smarty_tpl->tpl_vars['event_id']->value;?>
" >样核</a>
                    </li>
                    <li role="presentation" class="tool pull-right">
                        <a class="btn btn-primary" href="/audit/second?type=1&business=3&event=<?php echo $_smarty_tpl->tpl_vars['event_id']->value;?>
" >复审</a>
                    </li>
                    <li role="presentation" class="tool pull-right">
                        <a class="btn btn-primary" href="/audit/first?type=1&business=3&event=<?php echo $_smarty_tpl->tpl_vars['event_id']->value;?>
" >初审</a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
    <!-- 提示 -->
    <div  class="row">
        <div class="col-md-12">
            <h4 id="tool-tip-count" style="color:#fd6699">商品数目:<?php echo count($_smarty_tpl->tpl_vars['data']->value[$_smarty_tpl->tpl_vars['area']->value]['goods_list']);?>
</h4>
            <p>提示：所有排序都要保存才能生效呢！!</p>
        </div>
    </div>
    <!-- 品展示 -->
    <div class="row goods_box" id="box-contailer">
        <?php  $_smarty_tpl->tpl_vars['item'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['item']->_loop = false;
 $_smarty_tpl->tpl_vars['key'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['data']->value[$_smarty_tpl->tpl_vars['area']->value]['goods_list']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['item']->key => $_smarty_tpl->tpl_vars['item']->value) {
$_smarty_tpl->tpl_vars['item']->_loop = true;
 $_smarty_tpl->tpl_vars['key']->value = $_smarty_tpl->tpl_vars['item']->key;
?>
        <div class="col-md-3 col-sm-4 clearfix singleGoods" >
            <div class="thumbnail" data-tid=<?php echo $_smarty_tpl->tpl_vars['item']->value['twitter_id'];?>
>
                <div class="img" style="position: relative;">
                    <a target="_blank" href="http://www.meilishuo.com/share/item/<?php echo $_smarty_tpl->tpl_vars['item']->value['twitter_id'];?>
">
                        <img data-src="holder.js/100%x200" alt="100%x200" src="<?php echo Yii::app()->image->getWebsiteImageUrl($_smarty_tpl->tpl_vars['item']->value['goods_image']);?>
" data-holder-rendered="true" style="height: 330px; width: 100%; display: block;">
                    </a>
                </div>
                <div class="caption">
                    <p><span>名称：<?php echo $_smarty_tpl->tpl_vars['item']->value['goods_name'];?>
</span></p>
                    <p>推id：<?php echo $_smarty_tpl->tpl_vars['item']->value['twitter_id'];?>
</p>
                    <p>商品id：<?php echo $_smarty_tpl->tpl_vars['item']->value['goods_id'];?>
</p>
                    <p style=" white-space: nowrap;"></p>
                    <p>价格：<?php echo $_smarty_tpl->tpl_vars['item']->value['off_price'];?>
</p>
                    <p>销量：<?php echo $_smarty_tpl->tpl_vars['item']->value['sales'];?>
</p>
                    <p>时间：<?php echo date("Y-m-d H:i:s",$_smarty_tpl->tpl_vars['item']->value['time']);?>
</p>
                    <p class="text-right">
                          <a href="/goods/editGoods?gid=<?php echo $_smarty_tpl->tpl_vars['item']->value['gid'];?>
" target="_blank" class="btn btn-default btn-sm">编辑</a>
                          <button data-tid="<?php echo $_smarty_tpl->tpl_vars['item']->value['tuanid'];?>
" role="button" class="btn btn-danger btn-sm delete-btn">退回</button>
                    </p>
                </div>
            </div>
        </div>
        <?php } ?>
    </div>
</div>
<div id="myModal" class="modal fade bs-example-modal-sm" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">提示</h4>
            </div>
            <div class="modal-body">
                <div class="alert alert-warning">
                    你这样做可能破坏了你手动排序哦！按完确定按钮，排序就生效并保存了，你确定要这样做么
                </div>
            </div>
            <div class="modal-footer text-right" >
                <div class="btnSurebox" style="display:none">
                    <button class="btn btn-default sure-btn" data-flag="false">我再想想</button>
                    <button class="btn btn-success sure-btn" data-flag="true">我很确定</button>
                </div>
            </div>
        </div>
    </div>
</div>
<?php echo $_smarty_tpl->getSubTemplate ("layouts/go_top.html", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, null, array(), 0);?>

<script>
var timer =null;
function showDot(msg){
    clearInterval(timer);
    nhtml = msg;
    var _this = $('#myModal').find('.modal-body');
    _this.html(nhtml);
    timer = setInterval(function(){
        nhtml +='.';
        _this.html(nhtml);
    },1000);
}
function sureFun(_this){
    var condition = _this.attr('data-sort');
    var event_id = $('#event_id').val();
    var area = $('#area').val();
    var time = $('#event_time').val();
    showDot('正在排序，请稍等');
    $('#myModal').modal();
    $.post('/activity/SortByCondition',{event_id:event_id,area:area,condition:condition,time:time},function(data){
        if(data.code==1)
        {
            $('#myModal').modal('hide');
            var results = data.data;
            var htmlArr = [];
            for (var i = results.length - 1; i >= 0; i--) {
            var img = results[i].goods_image;
            var startTime = results[i].start_time;
            var nHtml= '<div class="col-md-3 col-sm-4 clearfix singleGoods" >'+
                        '<div class="thumbnail" data-tid='+results[i].twitter_id+'>'+
                        '<div class="img" style="position: relative;">'+
                        '<a target="_blank" href="">'+
                            '<img data-src="holder.js/100%x200" alt="100%x200" src="<?php echo Yii::app()->image->getWebsiteImageUrl("'+img+'");?>
" data-holder-rendered="true" style="height: 330px; width: 100%; display: block;">'+
                        '</a></div>'+
                        '<div class="caption">'+
                        '<p><span>名称：'+results[i].goods_name+'</span></p>'+
                        '<p>推id：'+results[i].twitter_id+'</p>'+
                        '<p>商品id：'+results[i].goods_id+'</p>'+
                        '<p style=" white-space: nowrap;"></p>'+
                        '<p>价格：'+results[i].off_price+'</p>'+
                        '<p>销量：'+results[i].sales+'</p>'+
                        '<p>时间：'+startTime+'</p>'+
                        '<p class="text-right">'+
                            '<button data-tid='+results[i].tuanid+' role="button" class="btn btn-danger btn-sm delete-btn">退回</button>'+
                        '</p></div></div></div>';
            htmlArr.push(nHtml);
            };
            $('#box-contailer').html(htmlArr.join(' '));
        }
    },'json');
    
}
</script>
<script src="/assets/js/activity/index.js"></script>
<?php }} ?>
