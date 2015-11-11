<?php /* Smarty version Smarty-3.1.18, created on 2015-09-28 12:24:33
         compiled from "/home/work/websites/tuan/protected/views/takepart/takepart.html" */ ?>
<?php /*%%SmartyHeaderCode:14107276275608c101dac2b5-56083551%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '1e91ae72375095fe484a89b7db3fb82d7cd78cac' => 
    array (
      0 => '/home/work/websites/tuan/protected/views/takepart/takepart.html',
      1 => 1439522157,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '14107276275608c101dac2b5-56083551',
  'function' => 
  array (
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.18',
  'unifunc' => 'content_5608c101df66a9_07623952',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_5608c101df66a9_07623952')) {function content_5608c101df66a9_07623952($_smarty_tpl) {?><?php echo $_smarty_tpl->getSubTemplate ("layouts/header.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, null, array(), 0);?>

<title>人工报名</title>
<div class="container">
    <div class="row">
    <div class="col-md-12">
      <div role="tabpanel">
        <ul class="nav nav-tabs" role="tablist">
          <li role="presentation" class="active">
            <a href="/takepart" >团购报名</a>
          </li>
          <li role="presentation">
            <a href="/takepart/newApply" >新版团购报名</a>
          </li>
        </ul>
      </div>
    </div>
  </div>
  <div class="row" style="margin-top:20px;">
    <div class="embed-responsive embed-responsive-16by9">
      <iframe class="embed-responsive-item" src="http://works.meiliworks.com/tuanht/takepart?is_show_header=1"></iframe>
    </div>
  </div>
</div>
<?php echo $_smarty_tpl->getSubTemplate ("layouts/footer.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, null, array(), 0);?>
<?php }} ?>
