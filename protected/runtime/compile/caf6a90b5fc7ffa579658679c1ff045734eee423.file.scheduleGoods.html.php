<?php /* Smarty version Smarty-3.1.18, created on 2015-08-19 15:16:38
         compiled from "/home/work/websites/tuan/protected/views/eventGoods/scheduleGoods.html" */ ?>
<?php /*%%SmartyHeaderCode:86939508555d42d5663ba51-58199891%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'caf6a90b5fc7ffa579658679c1ff045734eee423' => 
    array (
      0 => '/home/work/websites/tuan/protected/views/eventGoods/scheduleGoods.html',
      1 => 1439522157,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '86939508555d42d5663ba51-58199891',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'eventInfo' => 0,
    'event' => 0,
    'count' => 0,
    'needTool' => 0,
    'needCancelTool' => 0,
    'schedule_start_time' => 0,
    'schedule_end_time' => 0,
    'fail_reason' => 0,
    'item' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.18',
  'unifunc' => 'content_55d42d566bb1e1_53406228',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_55d42d566bb1e1_53406228')) {function content_55d42d566bb1e1_53406228($_smarty_tpl) {?><?php echo $_smarty_tpl->getSubTemplate ("layouts/header.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, null, array(), 0);?>

<script type="text/javascript" src="/assets/lib/bootstrap-datepicker.js"></script>
<style type="text/css" src="/assets/css/datepicker.css"></style>
<script src="/assets/lib/bufferview.js"></script>
<script src="/assets/lib/jquery.pin.js"></script>
<script src="/assets/lib/My97DatePicker/WdatePicker.js"></script>
<title>活动商品排期</title>
<style type="text/css">
.rec_sku .price {
    color: #999;
    text-decoration: line-through;
    padding-left: 10px
}
.rec_sku .price_red {
    color: #f69;
    font-size: 16px
}
#thumbnail-label {
    font-size: 16px
}

.pinActive {
    box-shadow: 0 10px 6px rgba(0,0,0,.12),0 1px 6px rgba(0,0,0,.12) !important;
    /*width: 100%!important;*/
    background-color: white;
    z-index:999
}

.caption {
    /*background-color: lavenderblush;*/
}
.select2-selection {
    height: 34px !important;
}
.select2-selection__rendered {
    height: 34px !important;
    line-height: 32px !important;
}

.selected {
    background-color: #eee;
}

.img {
height: 200px;
}

.level {
    color: #f69;
    font-size: 18px;
}

.tool {
    position: relative;
    top: 20px;
    left: 40px;
}

.btn-danger {
    background-color: #f46;
}


.bs-callout {
  background-color: #fff;
  padding: 20px;
  padding-left:60px;
  border: 1px solid #1b809e;
  border-left-width: 5px;
  border-radius: 3px;
  border-left-color: #1b809e;
  width:800px;
  position: fixed;
  top: 70px;
  right: -740px;
}
.show-stat-btn {
  position: absolute;
  left: 20px;
  width: 10px;
  font-size: 18px;
  top: 150px;
  text-decoration:none;
  color:#fd6699;
}
.show-stat-btn a:hover{ text-decoration:none;color:#fd6699;}
.show-stat-btn a{ text-decoration:none;color:#fd6699;}
.table>thead>tr>th, .table>tbody>tr>th, .table>tfoot>tr>th, .table>thead>tr>td, .table>tbody>tr>td, .table>tfoot>tr>td {padding: 2px;}
</style>
<script>
<?php if ($_smarty_tpl->tpl_vars['eventInfo']->value['status']>=30&&$_smarty_tpl->tpl_vars['eventInfo']->value['status']<40) {?>
window.needRepertory = 1;
<?php } else { ?>
window.needRepertory = 0;
<?php }?>
window.eventId = <?php echo $_smarty_tpl->tpl_vars['eventInfo']->value['event_id'];?>
;
</script>
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <ol class="breadcrumb">
                <li><a href="/">Home</a></li>
                <li><a href="/eventGoods/scheduleGoods?event_id=<?php echo $_smarty_tpl->tpl_vars['event']->value;?>
"><?php echo $_smarty_tpl->tpl_vars['eventInfo']->value['event_name'];?>
 - 排期</a></li>
                <li class="active">排期</li>
            </ol>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
          <div id="well" class="well">
              <form class="form-horizontal" method="get" action="/eventGoods/scheduleGoods" role="form" id="form">
                  <?php echo $_smarty_tpl->getSubTemplate ("eventGoods/search.html", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, null, array(), 0);?>

              </form>
          </div>
        </div>
    </div>
    
    <div  class="row " >
        <div class="col-md-12 pinned saveDataOneCon" style="padding-bottom:10px;">
            <div style="padding-top:4px;color:#fd6699;font-size:18px">
               <?php echo $_smarty_tpl->tpl_vars['eventInfo']->value['event_name'];?>
 <span id="tool-tip-count">
                商品数目:<?php echo $_smarty_tpl->tpl_vars['count']->value;?>
</span>&nbsp;&nbsp;&nbsp;&nbsp;当前选定:
                <span id="selected-count">0</span>
            </div>
            <p>提示：点击选定，批量通过或者退回
             <?php if ($_smarty_tpl->tpl_vars['needTool']->value) {?>
                 <button id="showSchedule" class="pull-right btn btn-default" data-toggle="modal" data-target="scheduleModal">批量排期</button>
                 <button id="showFailGoods" class="pull-right btn btn-danger" data-toggle="modal" data-target="scheduleModal" style='margin-right:10px;'>批量退回</button>
             <?php } elseif ($_smarty_tpl->tpl_vars['needCancelTool']->value) {?>
                 <button id="showCancelSchedule" class="pull-right btn btn-warning" data-toggle="modal" data-target="scheduleModal">批量取消排期</button>
             <?php }?>
            </p>
            <p style="margin:0;"><label style="color:red;margin:0;">全选 <input type="checkbox" id="checkedAll"></label></p>
        </div>
    </div>
    <?php echo $_smarty_tpl->getSubTemplate ("eventGoods/content-detail.html", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, null, array(), 0);?>

    
</div>


<!-- Modal -->
<div class="modal fade" id="scheduleModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" >
  <div class="modal-dialog" style="width: 650px;">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="passModalLabel">秒杀排期</h4>
      </div>
      <?php if ($_smarty_tpl->tpl_vars['eventInfo']->value['status']>=30&&$_smarty_tpl->tpl_vars['eventInfo']->value['status']<40) {?>
        <div class="modal-body modalCon" style="height:220px;">
      <?php } else { ?>
        <div class="modal-body modalCon" style="height:150px;">
      <?php }?>
          <div class="form-group" style="line-height:30px;">
              <label class="col-md-12 control-label">已选择 <span class="selected-count" style="color:red;">0</span> 个商品</label>
              <label class="col-md-2 control-label">排期时间</label>
              <div class="col-md-4">
                  <input class="form-control myDatePickerHMS" name="start_time"  type="text" value="<?php if ($_smarty_tpl->tpl_vars['schedule_start_time']->value) {?><?php echo $_smarty_tpl->tpl_vars['schedule_start_time']->value;?>
<?php } else { ?><?php echo date("Y-m-d H:i:s",$_smarty_tpl->tpl_vars['eventInfo']->value['start_time']);?>
<?php }?>" >
              </div>
              <label class="col-md-2 control-label">排期时间</label>
              <div class="col-md-4">
                  <input class="form-control myDatePickerHMS" name="end_time"  type="text" value="<?php if ($_smarty_tpl->tpl_vars['schedule_end_time']->value) {?><?php echo $_smarty_tpl->tpl_vars['schedule_end_time']->value;?>
<?php } else { ?><?php echo date("Y-m-d H:i:s",$_smarty_tpl->tpl_vars['eventInfo']->value['end_time']);?>
<?php }?>">
              </div>
          </div>
          <label class="col-md-12 control-label">&nbsp;</label>
          <div class="form-group" style="line-height:30px;">
             <?php if ($_smarty_tpl->tpl_vars['eventInfo']->value['status']>=30&&$_smarty_tpl->tpl_vars['eventInfo']->value['status']<40) {?>
             <label class="col-md-2 control-label">库存限制</label>
             <div class="col-md-4">
                 <input type="text" class="form-control" name="repertory" value="100" id="scheduleRepertory">
             </div>
             <label class="col-md-6 control-label">设置库存为商品实际库存 <input type="checkbox" name="use_origin_repertory"></label>
             <?php }?>
         </div>
         <?php if ($_smarty_tpl->tpl_vars['eventInfo']->value['status']>=30&&$_smarty_tpl->tpl_vars['eventInfo']->value['status']<40) {?>
           <label class="col-md-12 control-label">&nbsp;</label>
           <label class="col-md-12 control-label"><span style="color:red;">注意：秒杀类型的的活动必须选择库存，手动填写库存和设置商品实际库存为库存选一即可，设置商品实际库存优先级要高于手动填写库存</span></label>
         <?php }?>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">关闭</button>
        <button type="button" class="btn btn-primary" id="btnSaveSchedule">保存</button>
      </div>
    </div>
  </div>
</div>

<!-- Modal -->
<div class="modal fade" id="cancelScheduleModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="failModalLabel">全部退回</h4>
      </div>
      <div class="modal-body modalCon">
        <label class="col-md-12 control-label" style="padding-left: 0px;">已选择 <span class="selected-count" style="color:red;">0</span> 个商品</label>
        <?php  $_smarty_tpl->tpl_vars['item'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['item']->_loop = false;
 $_smarty_tpl->tpl_vars['key'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['fail_reason']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['item']->key => $_smarty_tpl->tpl_vars['item']->value) {
$_smarty_tpl->tpl_vars['item']->_loop = true;
 $_smarty_tpl->tpl_vars['key']->value = $_smarty_tpl->tpl_vars['item']->key;
?>
        <div class="radio">
            <label>
                <input type="radio" name="failRadios" value="<?php echo $_smarty_tpl->tpl_vars['item']->value['content'];?>
">
                <?php echo $_smarty_tpl->tpl_vars['item']->value['content'];?>

            </label>
        </div>
        <?php } ?>
        <textarea id="failRadiosReason" class="form-control" rows="3"></textarea>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">关闭</button>
        <button id="btnCancelSchedule" type="button" class="btn btn-primary">保存</button>
      </div>
    </div>
  </div>
</div>


<!-- Modal -->

<div class="modal fade" id="failModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="failModalLabel">全部退回</h4>
      </div>
      <div class="modal-body modalCon">
        <label class="col-md-12 control-label" style="padding-left: 0px;">已选择 <span class="selected-count" style="color:red;">0</span> 个商品</label>
        <?php  $_smarty_tpl->tpl_vars['item'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['item']->_loop = false;
 $_smarty_tpl->tpl_vars['key'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['fail_reason']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['item']->key => $_smarty_tpl->tpl_vars['item']->value) {
$_smarty_tpl->tpl_vars['item']->_loop = true;
 $_smarty_tpl->tpl_vars['key']->value = $_smarty_tpl->tpl_vars['item']->key;
?>
        <div class="radio">
            <label>
                <input type="radio" name="failGoodsRadios" value="<?php echo $_smarty_tpl->tpl_vars['item']->value['content'];?>
">
                <?php echo $_smarty_tpl->tpl_vars['item']->value['content'];?>

            </label>
        </div>
        <?php } ?>
        <textarea id="failRadiosGoodsReason" class="form-control" rows="3"></textarea>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">关闭</button>
        <button id="btnFailGoods" type="button" class="btn btn-primary">保存</button>
      </div>
    </div>
  </div>
</div>


<script type="text/javascript" src="/assets/js/audit/common.js"></script>
<script type="text/javascript" src="/assets/js/eventGoods/schedule.js"></script>
<script>
$(function(){
  /** 选择时间 */
  $('body').on('focus', '.myDatePickerHMS',function(e){
    WdatePicker({
        dateFmt:'yyyy-MM-dd HH:00:00',
        maxDate:'%y-%M-%{%d+30}'
    });
  });
});
</script>
<?php echo $_smarty_tpl->getSubTemplate ("layouts/footer.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, null, array(), 0);?>
<?php }} ?>
