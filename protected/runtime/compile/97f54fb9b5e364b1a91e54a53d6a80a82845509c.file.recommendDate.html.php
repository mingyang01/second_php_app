<?php /* Smarty version Smarty-3.1.18, created on 2015-08-20 12:40:20
         compiled from "/home/work/websites/tuan/protected/views/online/recommendDate.html" */ ?>
<?php /*%%SmartyHeaderCode:81397686955d55a34470545-46710996%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '97f54fb9b5e364b1a91e54a53d6a80a82845509c' => 
    array (
      0 => '/home/work/websites/tuan/protected/views/online/recommendDate.html',
      1 => 1439522157,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '81397686955d55a34470545-46710996',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'count' => 0,
    'needTool' => 0,
    'event_start_time' => 0,
    'fail_reason' => 0,
    'item' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.18',
  'unifunc' => 'content_55d55a344b6b30_93557594',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_55d55a344b6b30_93557594')) {function content_55d55a344b6b30_93557594($_smarty_tpl) {?><?php echo $_smarty_tpl->getSubTemplate ("layouts/header.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, null, array(), 0);?>

<script type="text/javascript" src="/assets/lib/bootstrap-datepicker.js"></script>
<style type="text/css" src="/assets/css/datepicker.css"></style>
<script src="/assets/lib/bufferview.js"></script>
<script src="/assets/lib/jquery.pin.js"></script>
<script src="/assets/lib/My97DatePicker/WdatePicker.js"></script>
<title>推荐排期</title>
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
</style>
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <ol class="breadcrumb">
                <li><a href="/">Home</a></li>
                <li><a href="/online/recommendDate">推荐排期</a></li>
                <li class="active">推荐排期</li>
            </ol>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
          <div id="well" class="well">
              <form class="form-horizontal" method="get" action="/online/recommendDate" role="form" id="form">
                <?php echo $_smarty_tpl->getSubTemplate ("online/search.html", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, null, array(), 0);?>

              </form>
          </div>
        </div>
    </div>
    
    <div  class="row " >
        <div class="col-md-12 pinned saveDataOneCon" style="padding-bottom:10px;">
            <div style="padding-top:4px;color:#fd6699;font-size:18px">
               推荐排期 <span id="tool-tip-count">
                商品数目:<?php echo $_smarty_tpl->tpl_vars['count']->value;?>
</span>&nbsp;&nbsp;&nbsp;&nbsp;当前选定:
                <span id="selected-count">0</span>
            </div>
            <p>提示：点击选定，批量通过或者退回
             <?php if ($_smarty_tpl->tpl_vars['needTool']->value) {?>
                 <a id="pass" class="pull-right btn btn-default" data-toggle="modal" data-target="#passModal">批量推荐</a>
                 <a id="showCancelSchedule" class="pull-right btn btn-warning" data-toggle="modal" data-target="cancelScheduleModal" style="margin-right:5px;">批量退回</a>
             <?php }?>
            </p>
            <p style="margin:0;"><label style="color:red;margin:0;">全选 <input type="checkbox" id="checkedAll"></label></p>
        </div>
    </div>
    <?php echo $_smarty_tpl->getSubTemplate ("online/online-content-detail.html", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, null, array(), 0);?>

    <div  class="row" style="height:80px;"><div class="col-md-12" style="text-align:center;"><button id="load" class="col-md-12 btn btn-default">加载更多</button></div></div>
</div>


<!-- Modal -->
<div class="modal fade" id="passModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="passModalLabel">批量推荐</h4>
      </div>
      <div class="modal-body editDateCon">
          <div class="form-group" style="line-height:34px;min-height:34px;">
              <label class="col-md-12 control-label">已选择 <span class="selected-count" style="color:red;">0</span> 个商品</label>
              <label class="col-md-3 control-label">推荐排期时间</label>
              <div class="col-md-5">
                  <input class="myDatePicker form-control" name="start_time"  type="text" value="<?php if ($_smarty_tpl->tpl_vars['event_start_time']->value) {?><?php echo date("Y-m-d",$_smarty_tpl->tpl_vars['event_start_time']->value);?>
<?php }?>" >
              </div>
          </div>
          <div class="form-group" style="line-height:34px;min-height:34px;">
          </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">关闭</button>
        <button type="button" id="" class="btn btn-primary btnEditRecommendData">保存</button>
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
        <button id="cancelSchedule" type="button" class="btn btn-primary">保存</button>
      </div>
    </div>
  </div>
</div>

<script type="text/javascript" src="/assets/js/audit/common.js"></script>
<script type="text/javascript" src="/assets/js/audit/first.js"></script>
<script type="text/javascript" src="/assets/js/online/common.js"></script>
<script type="text/javascript" src="/assets/js/online/onlineRecommendDate.js"></script>
<script>
$(function(){
  $("select[name='recommend_status']").change(function(){
    if (this.value == '1') {
        $('.recommendDateCon').hide();
        $('input[name="date"]').val('')
    } else {
        $('.recommendDateCon').show();
    }
  });
});
</script>
<?php echo $_smarty_tpl->getSubTemplate ("layouts/footer.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, null, array(), 0);?>
<?php }} ?>
