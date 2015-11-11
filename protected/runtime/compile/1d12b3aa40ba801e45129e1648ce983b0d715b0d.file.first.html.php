<?php /* Smarty version Smarty-3.1.18, created on 2015-11-05 15:10:05
         compiled from "/home/work/websites/tuan/protected/views/audit/first.html" */ ?>
<?php /*%%SmartyHeaderCode:86078738755cd5e04a30f08-94775022%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '1d12b3aa40ba801e45129e1648ce983b0d715b0d' => 
    array (
      0 => '/home/work/websites/tuan/protected/views/audit/first.html',
      1 => 1446699906,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '86078738755cd5e04a30f08-94775022',
  'function' => 
  array (
  ),
  'version' => 'Smarty-3.1.18',
  'unifunc' => 'content_55cd5e04a8f230_38828551',
  'variables' => 
  array (
    'checkResult' => 0,
    'count' => 0,
    'needTool' => 0,
    'pass_reason' => 0,
    'item' => 0,
    'fail_reason' => 0,
  ),
  'has_nocache_code' => false,
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_55cd5e04a8f230_38828551')) {function content_55cd5e04a8f230_38828551($_smarty_tpl) {?><?php echo $_smarty_tpl->getSubTemplate ("layouts/header.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, null, array(), 0);?>

<script type="text/javascript" src="/assets/lib/bootstrap-datepicker.js"></script>
<style type="text/css" src="/assets/css/datepicker.css"></style>
<script src="/assets/lib/bufferview.js"></script>
<script src="/assets/lib/jquery.pin.js"></script>
<title>初审</title>
<style type="text/css">
.rec_sku .price {
    color: #999;
    text-decoration: line-through;
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

</style>
<script>
window.checkResult = <?php echo $_smarty_tpl->tpl_vars['checkResult']->value;?>

</script>
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <ol class="breadcrumb">
                <li><a href="/">Home</a></li>
                <li><a href="/">宝贝审核</a></li>
                <li class="active">初审</li>
            </ol>
            <?php echo $_smarty_tpl->getSubTemplate ("search/search.html", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, null, array(), 0);?>

        </div>
    </div>
    <div  class="row " >
        <div class="col-md-12 pinned" style="padding-bottom:10px;">
            <div style="padding-top:4px;color:#fd6699;font-size:18px">
                <span id="tool-tip-count">
                商品数目:<?php echo $_smarty_tpl->tpl_vars['count']->value;?>
</span>&nbsp;&nbsp;&nbsp;&nbsp;当前选定:
                <span id="selected-count">0</span>
            </div>
            <p>提示：点击选定，批量通过或者退回
            <?php if ($_smarty_tpl->tpl_vars['needTool']->value==1) {?>
            <a id="refuse" style="margin-left:10px" class="pull-right btn btn-default" data-toggle="modal" data-target="#failModal">全部退回</a>
            <a id="pass" class="pull-right btn btn-default" data-toggle="modal" data-target="#passModal">全部通过</a>
            <?php }?>
            </p>
            <p style="margin:0;"><label style="color:red;margin:0;">全选 <input type="checkbox" id="checkedAll"></label></p>
        </div>
    </div>
    <?php echo $_smarty_tpl->getSubTemplate ("audit/first-content-detail.html", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, null, array(), 0);?>

    <div  class="row" style="height:80px;"><div class="col-md-12" style="text-align:center;"><button id="load" class="col-md-12 btn btn-default">加载更多</button></div></div>
</div>


<!-- Modal -->
<div class="modal fade" id="passModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="passModalLabel">全部通过</h4>
      </div>
      <div class="modal-body">
        <?php  $_smarty_tpl->tpl_vars['item'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['item']->_loop = false;
 $_smarty_tpl->tpl_vars['key'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['pass_reason']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['item']->key => $_smarty_tpl->tpl_vars['item']->value) {
$_smarty_tpl->tpl_vars['item']->_loop = true;
 $_smarty_tpl->tpl_vars['key']->value = $_smarty_tpl->tpl_vars['item']->key;
?>
        <div class="radio">
            <label>
                <input type="radio" name="passRadios" value="<?php echo $_smarty_tpl->tpl_vars['item']->value['content'];?>
">
                <?php echo $_smarty_tpl->tpl_vars['item']->value['content'];?>

            </label>
        </div>
        <?php } ?>
        <textarea id="passRadiosReason" class="form-control" rows="3"></textarea>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        <button type="button" id="submit-pass" class="btn btn-primary">Save changes</button>
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
      <div class="modal-body">
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
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        <button id="submit-fail" type="button" class="btn btn-primary">Save changes</button>
      </div>
    </div>
  </div>
</div>

<!-- Modal -->
<div class="modal fade" id="changePriceModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="failModalLabel">改价</h4>
      </div>
      <div class="modal-body" style="height:125px;">
        <label class="control-label col-md-12"><span class="selectedTitle"></span> -- <span class="selectedTwitter"></span></label>
        <input type="hidden" class="selectedGid" name="gid" value="">
        <input type="hidden" class="selectedShop" name="shop" value="">
        <p>&nbsp;</p>
        <p class="col-md-12"><span class="changePriceReasonPrev">建议复审改价</span><input type="text" class="form-control input-mini changePriceReason" style="display: inline-flex;"><span class="changePriceReasonNext">，1个自然日内修改有效，否则驳回处理，请知晓。</span></p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">关闭</button>
        <button id="saveChangePriceReason" type="button" class="btn btn-primary">保存</button>
      </div>
    </div>
  </div>
</div>

<script type="text/javascript" src="/assets/js/audit/common.js"></script>
<script type="text/javascript" src="/assets/js/audit/first.js"></script>
<?php echo $_smarty_tpl->getSubTemplate ("layouts/footer.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, null, array(), 0);?>
<?php }} ?>
