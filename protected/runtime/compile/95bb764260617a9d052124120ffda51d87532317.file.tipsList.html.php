<?php /* Smarty version Smarty-3.1.18, created on 2015-08-20 12:22:31
         compiled from "/home/work/websites/tuan/protected/views/qingcang/checktips/tipsList.html" */ ?>
<?php /*%%SmartyHeaderCode:177805033955d5560796bdc1-41650505%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '95bb764260617a9d052124120ffda51d87532317' => 
    array (
      0 => '/home/work/websites/tuan/protected/views/qingcang/checktips/tipsList.html',
      1 => 1440043262,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '177805033955d5560796bdc1-41650505',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'tipsStatusEnum' => 0,
    'searchFilter' => 0,
    'k' => 0,
    'v' => 0,
    'tipsTypeEnum' => 0,
    'checkTipsData' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.18',
  'unifunc' => 'content_55d556079e3ed7_78303140',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_55d556079e3ed7_78303140')) {function content_55d556079e3ed7_78303140($_smarty_tpl) {?><?php echo $_smarty_tpl->getSubTemplate ("layouts/header.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, null, array(), 0);?>

<title>审核原因</title>
<style>
.container{
  /*font-size:12px;*/
}
</style>
<?php $_smarty_tpl->tpl_vars['tipsTypeEnum'] = new Smarty_variable(QcheckTipsManager::$tipsTypeEnum, null, 0);?>
<?php $_smarty_tpl->tpl_vars['tipsStatusEnum'] = new Smarty_variable(QcheckTipsManager::$tipsStatusEnum, null, 0);?>
<div class="container">
  <div class="row">
    <div class="col-md-12">
      <ol class="breadcrumb">
          <li><a href="/">Home</a></li>
          <li><a href="/event">审核原因</a></li>
          <li class="active">全部</li>
      </ol>
    </div>
  </div>
  <div class="row">
    <div class="col-md-12">
      <div id="well" class="well">
        <?php  $_smarty_tpl->tpl_vars['v'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['v']->_loop = false;
 $_smarty_tpl->tpl_vars['k'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['tipsStatusEnum']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['v']->key => $_smarty_tpl->tpl_vars['v']->value) {
$_smarty_tpl->tpl_vars['v']->_loop = true;
 $_smarty_tpl->tpl_vars['k']->value = $_smarty_tpl->tpl_vars['v']->key;
?>
          <a class="label <?php if ($_smarty_tpl->tpl_vars['searchFilter']->value['status']==$_smarty_tpl->tpl_vars['k']->value) {?>label-primary<?php } else { ?>label-default<?php }?>" href="/qingcang/QcheckTips?status=<?php echo $_smarty_tpl->tpl_vars['k']->value;?>
&type=<?php if (isset($_smarty_tpl->tpl_vars['searchFilter']->value['type'])) {?><?php echo $_smarty_tpl->tpl_vars['searchFilter']->value['type'];?>
<?php }?>"><?php echo $_smarty_tpl->tpl_vars['v']->value;?>
</a>
        <?php } ?>
      </div>
    </div>
  </div>
  
  <div class="row">
    <div class="col-md-12">
      <div role="tabpanel">
        <ul class="nav nav-tabs" role="tablist">
          <li role="presentation" <?php if (!$_smarty_tpl->tpl_vars['searchFilter']->value['type']) {?>class="active"<?php }?>><a href="/qingcang/QcheckTips" >全部</a></li>
          <?php  $_smarty_tpl->tpl_vars['v'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['v']->_loop = false;
 $_smarty_tpl->tpl_vars['k'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['tipsTypeEnum']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['v']->key => $_smarty_tpl->tpl_vars['v']->value) {
$_smarty_tpl->tpl_vars['v']->_loop = true;
 $_smarty_tpl->tpl_vars['k']->value = $_smarty_tpl->tpl_vars['v']->key;
?>
            <li role="presentation" <?php if ($_smarty_tpl->tpl_vars['searchFilter']->value['type']==$_smarty_tpl->tpl_vars['k']->value) {?>class="active"<?php }?>>
              <a href="/qingcang/QcheckTips?type=<?php echo $_smarty_tpl->tpl_vars['k']->value;?>
" ><?php echo $_smarty_tpl->tpl_vars['v']->value;?>
</a>
            </li>
          <?php } ?>
          <li style="float:right" class="active"><a class="btn btn-default btn-ms" href="/qingcang/QcheckTips/add">添加</a></li>
        </ul>
      </div>
    </div>
  </div>
  
  <div class="row" style="margin-top:5px;">
  <div class="dataBox col-md-12">
    <table class="table table-striped table-striped center">
      <thead>
        <tr>
          
          <th class="text-center">ID</th>
          <th class="text-center">类型</th>
          <th class="text-center" style="width:800px;">内容</th>
          <th class="text-center">状态</th>
          <th>操作</th>
        </tr>
      </thead>
      <tbody>
        <?php  $_smarty_tpl->tpl_vars['v'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['v']->_loop = false;
 $_smarty_tpl->tpl_vars['k'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['checkTipsData']->value['result']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['v']->key => $_smarty_tpl->tpl_vars['v']->value) {
$_smarty_tpl->tpl_vars['v']->_loop = true;
 $_smarty_tpl->tpl_vars['k']->value = $_smarty_tpl->tpl_vars['v']->key;
?>
          <tr id="dataList<?php echo $_smarty_tpl->tpl_vars['v']->value['id'];?>
">
            
            <td><?php echo $_smarty_tpl->tpl_vars['v']->value['id'];?>
</td>
            <td><?php echo $_smarty_tpl->tpl_vars['tipsTypeEnum']->value[$_smarty_tpl->tpl_vars['v']->value['type']];?>
</td>
            <td><?php echo $_smarty_tpl->tpl_vars['v']->value['content'];?>
</td>
            <td><?php echo $_smarty_tpl->tpl_vars['tipsStatusEnum']->value[$_smarty_tpl->tpl_vars['v']->value['status']];?>
</td>
            <td class="txtcenter"> 
            <!-- Single button -->
              <div class="btn-group">
                <button type="button" class="btn btn-info btn-xs dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                  操作 <span class="caret"></span>
                </button>
                <ul class="dropdown-menu" role="menu">
                  <li role="presentation"><a role="menuitem" tabindex="-1" href="/qingcang/QcheckTips/edit?id=<?php echo $_smarty_tpl->tpl_vars['v']->value['id'];?>
">修改</a></li>
                  <?php if ($_smarty_tpl->tpl_vars['v']->value['status']==1) {?>
                    <li class=""  value="<?php echo $_smarty_tpl->tpl_vars['v']->value['id'];?>
"><a class="deleteOne" href="/checkTips/delete?id=<?php echo $_smarty_tpl->tpl_vars['v']->value['id'];?>
" >删除</a></li>
                  <?php } else { ?>
                    <li class="" value="<?php echo $_smarty_tpl->tpl_vars['v']->value['id'];?>
"><a class="recoverOne" href="/checkTips/recover?id=<?php echo $_smarty_tpl->tpl_vars['v']->value['id'];?>
">恢复</a></li>
                  <?php }?>
                </ul>
              </div>
            </td>
          </tr>
        <?php }
if (!$_smarty_tpl->tpl_vars['v']->_loop) {
?>
          <tr><td colspan="13">暂无相关信息</td></tr>
        <?php } ?>
      </tbody>
    </table>
    <?php echo $_smarty_tpl->getSubTemplate ("layouts/pager.html", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, null, array(), 0);?>

  </div>
  </div>
</div>
<script>
$(function(){
  $('.datepicker').datepicker({
      format: "yyyy-mm-dd",
      autoclose: true
  }).on('changeDate', function(ev){
      $(this).datepicker('hide');
  });
});
</script>
<?php echo $_smarty_tpl->getSubTemplate ("layouts/footer.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, null, array(), 0);?>
<?php }} ?>
