<?php /* Smarty version Smarty-3.1.18, created on 2015-10-15 18:57:09
         compiled from "/home/work/websites/tuan/protected/views/online/index.html" */ ?>
<?php /*%%SmartyHeaderCode:16262153325608e124e3eae5-67529595%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '6f16d1875221c25f9f8eeee9481e462f9649012a' => 
    array (
      0 => '/home/work/websites/tuan/protected/views/online/index.html',
      1 => 1444794404,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '16262153325608e124e3eae5-67529595',
  'function' => 
  array (
  ),
  'version' => 'Smarty-3.1.18',
  'unifunc' => 'content_5608e12503b2a2_78626828',
  'variables' => 
  array (
    'audit_status' => 0,
    'count' => 0,
    'needTool' => 0,
    'date' => 0,
    'stat' => 0,
    'v' => 0,
    'key' => 0,
    'val' => 0,
  ),
  'has_nocache_code' => false,
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_5608e12503b2a2_78626828')) {function content_5608e12503b2a2_78626828($_smarty_tpl) {?><?php if (!is_callable('smarty_function_html_options')) include '/home/work/framework/extensions/Smarty/plugins/function.html_options.php';
?><?php echo $_smarty_tpl->getSubTemplate ("layouts/header.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, null, array(), 0);?>

<script type="text/javascript" src="/assets/lib/bootstrap-datepicker.js"></script>
<style type="text/css" src="/assets/css/datepicker.css"></style>
<script src="/assets/lib/bufferview.js"></script>
<script src="/assets/lib/jquery.pin.js"></script>
<script src="/assets/lib/My97DatePicker/WdatePicker.js"></script>
<title>排期</title>
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
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <ol class="breadcrumb">
                <li><a href="/">Home</a></li>
                <li><a href="/"><?php if ($_smarty_tpl->tpl_vars['audit_status']->value==40) {?>宝贝排期<?php } else { ?>排期成功<?php }?></a></li>
                <li class="active">排期</li>
            </ol>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
          <div id="well" class="well">
              <form class="form-horizontal" method="get" action="/online" role="form" id="form">
                  <?php echo $_smarty_tpl->getSubTemplate ("online/search.html", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, null, array(), 0);?>

              </form>
          </div>
        </div>
    </div>
    
    <div  class="row " >
        <div class="col-md-12 pinned saveDataOneCon" style="padding-bottom:10px;">
            <div style="padding-top:4px;color:#fd6699;font-size:18px">
               <?php if ($_smarty_tpl->tpl_vars['audit_status']->value==40) {?>宝贝排期<?php } else { ?>排期成功<?php }?> <span id="tool-tip-count">
                商品数目:<?php echo $_smarty_tpl->tpl_vars['count']->value;?>
</span>&nbsp;&nbsp;&nbsp;&nbsp;当前选定:
                <span id="selected-count">0</span>
            </div>
            <p>提示：点击选定，批量通过或者退回
             <?php if ($_smarty_tpl->tpl_vars['needTool']->value) {?>
                 <a id="pass" class="pull-right btn btn-default" data-toggle="modal" data-target="#passModal">批量排期</a>
                 <a id="cancelDateMore" class="pull-right btn btn-warning"  style="margin-right:10px;">批量退回</a>
             <?php }?>
             <?php if ($_smarty_tpl->tpl_vars['audit_status']->value==50) {?>
               <a id="addTagMore" class="pull-right btn btn-warning"  style="margin-right:10px;">批量打标</a>
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
        <h4 class="modal-title" id="passModalLabel">批量排期</h4>
      </div>
      <div class="modal-body editDateCon">
          <div class="form-group" style="line-height:34px;min-height:34px;">
              <label class="col-md-12 control-label">已选择 <span class="selected-count" style="color:red;">0</span> 个商品</label>
              <label class="col-md-2 control-label">排期时间</label>
              <div class="col-md-5">
                  <input readonly class="form-control" name="start_time"  type="text" value="<?php echo $_smarty_tpl->tpl_vars['date']->value;?>
" >
              </div>
              
          </div>
          <div class="form-group" style="line-height:34px;min-height:34px;">
          </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">关闭</button>
        <button type="button" id="" class="btn btn-primary btnEditDate">保存</button>
      </div>
    </div>
  </div>
</div>
<div class="bs-callout bs-callout-info" id="statCon">
    <div class="show-stat-btn"><a class="btnShowStat" href="javascript:;">展开统计信息</a></div>
    <p>日期：<span style="color:#fd6699"><?php echo $_smarty_tpl->tpl_vars['date']->value;?>
</span> &nbsp; 排期成功商品数：<span style="color:#fd6699"><?php echo $_smarty_tpl->tpl_vars['stat']->value['total'];?>
</span></p>
    <div style="height: 400px;overflow: scroll;overflow-x:hidden;">
    <div class="table-responsive">
      <table class="table table-bordered">
        <thead>
          <tr>
            <?php  $_smarty_tpl->tpl_vars['v'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['v']->_loop = false;
 $_smarty_tpl->tpl_vars['k'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['stat']->value['price_stat']['title']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['v']->key => $_smarty_tpl->tpl_vars['v']->value) {
$_smarty_tpl->tpl_vars['v']->_loop = true;
 $_smarty_tpl->tpl_vars['k']->value = $_smarty_tpl->tpl_vars['v']->key;
?>
              <th><?php echo $_smarty_tpl->tpl_vars['v']->value;?>
</th>
            <?php } ?>
          </tr>
        </thead>
        <tbody>
          <?php  $_smarty_tpl->tpl_vars['v'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['v']->_loop = false;
 $_smarty_tpl->tpl_vars['k'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['stat']->value['price_stat']['data']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['v']->key => $_smarty_tpl->tpl_vars['v']->value) {
$_smarty_tpl->tpl_vars['v']->_loop = true;
 $_smarty_tpl->tpl_vars['k']->value = $_smarty_tpl->tpl_vars['v']->key;
?>
            <tr>
              <?php  $_smarty_tpl->tpl_vars['val'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['val']->_loop = false;
 $_smarty_tpl->tpl_vars['key'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['v']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['val']->key => $_smarty_tpl->tpl_vars['val']->value) {
$_smarty_tpl->tpl_vars['val']->_loop = true;
 $_smarty_tpl->tpl_vars['key']->value = $_smarty_tpl->tpl_vars['val']->key;
?>
                <?php if (array_key_exists($_smarty_tpl->tpl_vars['key']->value,$_smarty_tpl->tpl_vars['stat']->value['price_stat']['title'])) {?>
                  <td <?php if ($_smarty_tpl->tpl_vars['key']->value=='header') {?>scope="row"<?php }?>><?php echo $_smarty_tpl->tpl_vars['val']->value;?>
</td>
                <?php }?>
              <?php } ?>
            </tr>
          <?php } ?>
        </tbody>
      </table>
    </div>
    
    <div class="table-responsive">
      <table class="table table-bordered">
        <thead>
          <tr>
            <?php  $_smarty_tpl->tpl_vars['v'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['v']->_loop = false;
 $_smarty_tpl->tpl_vars['k'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['stat']->value['category_stat']['title']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['v']->key => $_smarty_tpl->tpl_vars['v']->value) {
$_smarty_tpl->tpl_vars['v']->_loop = true;
 $_smarty_tpl->tpl_vars['k']->value = $_smarty_tpl->tpl_vars['v']->key;
?>
              <th><?php echo $_smarty_tpl->tpl_vars['v']->value;?>
</th>
            <?php } ?>
          </tr>
        </thead>
        <tbody>
          <?php  $_smarty_tpl->tpl_vars['v'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['v']->_loop = false;
 $_smarty_tpl->tpl_vars['k'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['stat']->value['category_stat']['data']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['v']->key => $_smarty_tpl->tpl_vars['v']->value) {
$_smarty_tpl->tpl_vars['v']->_loop = true;
 $_smarty_tpl->tpl_vars['k']->value = $_smarty_tpl->tpl_vars['v']->key;
?>
            <tr>
              <?php  $_smarty_tpl->tpl_vars['val'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['val']->_loop = false;
 $_smarty_tpl->tpl_vars['key'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['v']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['val']->key => $_smarty_tpl->tpl_vars['val']->value) {
$_smarty_tpl->tpl_vars['val']->_loop = true;
 $_smarty_tpl->tpl_vars['key']->value = $_smarty_tpl->tpl_vars['val']->key;
?>
                <?php if (array_key_exists($_smarty_tpl->tpl_vars['key']->value,$_smarty_tpl->tpl_vars['stat']->value['category_stat']['title'])) {?>
                  <td <?php if ($_smarty_tpl->tpl_vars['key']->value=='header') {?>scope="row"<?php }?>><?php echo $_smarty_tpl->tpl_vars['val']->value;?>
</td>
                <?php }?>
              <?php } ?>
            </tr>
          <?php } ?>
        </tbody>
      </table>
    </div>
    <div class="table-responsive">
      <table class="table table-bordered">
        <thead>
          <tr>
            <?php  $_smarty_tpl->tpl_vars['v'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['v']->_loop = false;
 $_smarty_tpl->tpl_vars['k'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['stat']->value['popularity_score_stat']['title']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['v']->key => $_smarty_tpl->tpl_vars['v']->value) {
$_smarty_tpl->tpl_vars['v']->_loop = true;
 $_smarty_tpl->tpl_vars['k']->value = $_smarty_tpl->tpl_vars['v']->key;
?>
              <th><?php echo $_smarty_tpl->tpl_vars['v']->value;?>
</th>
            <?php } ?>
          </tr>
        </thead>
        <tbody>
          <?php  $_smarty_tpl->tpl_vars['v'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['v']->_loop = false;
 $_smarty_tpl->tpl_vars['k'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['stat']->value['popularity_score_stat']['data']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['v']->key => $_smarty_tpl->tpl_vars['v']->value) {
$_smarty_tpl->tpl_vars['v']->_loop = true;
 $_smarty_tpl->tpl_vars['k']->value = $_smarty_tpl->tpl_vars['v']->key;
?>
            <tr>
              <?php  $_smarty_tpl->tpl_vars['val'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['val']->_loop = false;
 $_smarty_tpl->tpl_vars['key'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['v']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['val']->key => $_smarty_tpl->tpl_vars['val']->value) {
$_smarty_tpl->tpl_vars['val']->_loop = true;
 $_smarty_tpl->tpl_vars['key']->value = $_smarty_tpl->tpl_vars['val']->key;
?>
                <?php if (array_key_exists($_smarty_tpl->tpl_vars['key']->value,$_smarty_tpl->tpl_vars['stat']->value['popularity_score_stat']['title'])) {?>
                  <td <?php if ($_smarty_tpl->tpl_vars['key']->value=='header') {?>scope="row"<?php }?>><?php echo $_smarty_tpl->tpl_vars['val']->value;?>
</td>
                <?php }?>
              <?php } ?>
            </tr>
          <?php } ?>
        </tbody>
      </table>
    </div>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="tagModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="passModalLabel">商品打标</h4>
      </div>
      <div class="modal-body modalCon" style="height:110px;">
          <div class="form-group" style="line-height:30px;">
              <label class="col-md-12 control-label">已选择 <span class="selected-count" style="color:red;">0</span> 个商品</label>
             <label class="col-md-2 control-label">商品标签</label>
             <div class="col-md-4">
                <select style="height:34px;" name="tag_type" class="form-control">
                  <?php echo smarty_function_html_options(array('options'=>OnlineManager::$tuangouTagMap),$_smarty_tpl);?>

                </select>
             </div>
          </div>
          <label class="col-md-12 control-label">&nbsp;</label>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">关闭</button>
        <button type="button" class="btn btn-primary" id="saveTagInfo">保存</button>
      </div>
    </div>
  </div>
</div>

<script>
$(function(){
  $(".btnShowStat").click(function(e){
    e.preventDefault();
    var thisObj = $(this);
    if (thisObj.hasClass("show")) {
      $("#statCon").animate({
        right:'-740'
      },'slow');
      thisObj.html("展开统计信息");
      thisObj.removeClass('show');
    } else {
      $("#statCon").animate({
        right:'0'
      },'slow');
      thisObj.html("收起统计信息");
      thisObj.addClass('show');
    }
  });
});
</script>
<script type="text/javascript" src="/assets/js/audit/common.js"></script>
<script type="text/javascript" src="/assets/js/audit/first.js"></script>
<script type="text/javascript" src="/assets/js/online/common.js"></script>
<script type="text/javascript" src="/assets/js/online/online.js"></script>
<?php echo $_smarty_tpl->getSubTemplate ("layouts/footer.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, null, array(), 0);?>
<?php }} ?>
