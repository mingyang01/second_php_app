<?php /* Smarty version Smarty-3.1.18, created on 2015-08-14 18:18:51
         compiled from "/home/work/websites/tuan/protected/views/layouts/pager.html" */ ?>
<?php /*%%SmartyHeaderCode:194509250255cdc08be22b24-68202824%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '94cca9e397c55646d3483d58ae5e232c94af2fb1' => 
    array (
      0 => '/home/work/websites/tuan/protected/views/layouts/pager.html',
      1 => 1439522157,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '194509250255cdc08be22b24-68202824',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'pager' => 0,
    'v' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.18',
  'unifunc' => 'content_55cdc08be6ca90_70797111',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_55cdc08be6ca90_70797111')) {function content_55cdc08be6ca90_70797111($_smarty_tpl) {?><?php if (isset($_smarty_tpl->tpl_vars['pager']->value)&&$_smarty_tpl->tpl_vars['pager']->value&&$_smarty_tpl->tpl_vars['pager']->value->getPages()>1) {?>
<div class="pages">
    <div class="total" style="float:left; height:74px;line-height:74px;margin-right:100px;margin-left:20px;">共<strong><?php echo $_smarty_tpl->tpl_vars['pager']->value->getTotal();?>
</strong>记录, <strong> <?php echo $_smarty_tpl->tpl_vars['pager']->value->getPage();?>
/<?php echo $_smarty_tpl->tpl_vars['pager']->value->getPages();?>
</strong>页, <strong> <?php echo $_smarty_tpl->tpl_vars['pager']->value->getListRows();?>
</strong>篇每页</div>
    <ul class="pagination">
      <li><a href="<?php echo $_smarty_tpl->tpl_vars['pager']->value->link($_smarty_tpl->tpl_vars['pager']->value->begin());?>
">首页</a></li>
      <?php if ($_smarty_tpl->tpl_vars['pager']->value->getPage()>1) {?>
        <li><a href="<?php echo $_smarty_tpl->tpl_vars['pager']->value->link($_smarty_tpl->tpl_vars['pager']->value->prev());?>
">前一页</a></li>
      <?php }?>
      <?php  $_smarty_tpl->tpl_vars['v'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['v']->_loop = false;
 $_smarty_tpl->tpl_vars['k'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['pager']->value->pageListArr(); if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['v']->key => $_smarty_tpl->tpl_vars['v']->value) {
$_smarty_tpl->tpl_vars['v']->_loop = true;
 $_smarty_tpl->tpl_vars['k']->value = $_smarty_tpl->tpl_vars['v']->key;
?>
        <?php if ($_smarty_tpl->tpl_vars['pager']->value->getPage()==$_smarty_tpl->tpl_vars['v']->value) {?>
          <li class="active">
            <a href="javascript:void(0);"><?php echo $_smarty_tpl->tpl_vars['v']->value;?>
</a>
          </li> 
        <?php } else { ?>
          <li>
            <a href="<?php echo $_smarty_tpl->tpl_vars['pager']->value->link($_smarty_tpl->tpl_vars['v']->value);?>
"><?php echo $_smarty_tpl->tpl_vars['v']->value;?>
</a>
          </li>
        <?php }?>
      <?php } ?>
      <?php if ($_smarty_tpl->tpl_vars['pager']->value->getPage()<$_smarty_tpl->tpl_vars['pager']->value->end()) {?>
        <li><a href="<?php echo $_smarty_tpl->tpl_vars['pager']->value->link($_smarty_tpl->tpl_vars['pager']->value->next());?>
">后一页</a></li>
        <li><a href="<?php echo $_smarty_tpl->tpl_vars['pager']->value->link($_smarty_tpl->tpl_vars['pager']->value->end());?>
">末页</a></li>
      <?php }?>
    </ul>
</div>
<?php }?>
<?php }} ?>
