<?php /* Smarty version Smarty-3.1.18, created on 2015-09-06 12:42:16
         compiled from "/home/work/websites/tuan/protected/views/notice/noticeList.html" */ ?>
<?php /*%%SmartyHeaderCode:92287292355ebc428ed11e0-85912495%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'd5fdf7df4760dc3f6fbc33dabe526c8ab084bdd2' => 
    array (
      0 => '/home/work/websites/tuan/protected/views/notice/noticeList.html',
      1 => 1440995332,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '92287292355ebc428ed11e0-85912495',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'searchFilter' => 0,
    'k' => 0,
    'v' => 0,
    'noticeList' => 0,
    'notice_cate_id_map' => 0,
    'notice_status_map' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.18',
  'unifunc' => 'content_55ebc429096f65_07482202',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_55ebc429096f65_07482202')) {function content_55ebc429096f65_07482202($_smarty_tpl) {?><?php echo $_smarty_tpl->getSubTemplate ("layouts/header.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, null, array(), 0);?>

<title>公告管理</title>
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
          <li><a href="/notice">公告</a></li>
          <li class="active">全部</li>
      </ol>
    </div>
  </div>
  <div class="row">
    <div class="col-md-12">
      <div id="well" class="well">
        <?php  $_smarty_tpl->tpl_vars['v'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['v']->_loop = false;
 $_smarty_tpl->tpl_vars['k'] = new Smarty_Variable;
 $_from = NoticeManager::$notice_status_map; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['v']->key => $_smarty_tpl->tpl_vars['v']->value) {
$_smarty_tpl->tpl_vars['v']->_loop = true;
 $_smarty_tpl->tpl_vars['k']->value = $_smarty_tpl->tpl_vars['v']->key;
?>
          <a class="label <?php if ($_smarty_tpl->tpl_vars['searchFilter']->value['status']==$_smarty_tpl->tpl_vars['k']->value) {?>label-primary<?php } else { ?>label-default<?php }?>" href="/notice?status=<?php echo $_smarty_tpl->tpl_vars['k']->value;?>
&cate_id=<?php if (isset($_smarty_tpl->tpl_vars['searchFilter']->value['cate_id'])) {?><?php echo $_smarty_tpl->tpl_vars['searchFilter']->value['cate_id'];?>
<?php }?>"><?php if ($_smarty_tpl->tpl_vars['v']->value=='展示') {?>全部<?php } else { ?><?php echo $_smarty_tpl->tpl_vars['v']->value;?>
<?php }?></a>
        <?php } ?>
      </div>
    </div>
  </div>
  
  
  <div class="row">
    <div class="col-md-12">
      <div role="tabpanel">
        <ul class="nav nav-tabs" role="tablist">
        <?php if (!$_smarty_tpl->tpl_vars['searchFilter']->value['cate_id']) {?>
          <li role="presentation" <?php if (!$_smarty_tpl->tpl_vars['searchFilter']->value['cate_id']) {?>class="active"<?php }?>><a href="/notice" >全部</a></li>
          <?php  $_smarty_tpl->tpl_vars['v'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['v']->_loop = false;
 $_smarty_tpl->tpl_vars['k'] = new Smarty_Variable;
 $_from = NoticeManager::$notice_cate_id_map; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['v']->key => $_smarty_tpl->tpl_vars['v']->value) {
$_smarty_tpl->tpl_vars['v']->_loop = true;
 $_smarty_tpl->tpl_vars['k']->value = $_smarty_tpl->tpl_vars['v']->key;
?>
            <li role="presentation" <?php if ($_smarty_tpl->tpl_vars['searchFilter']->value['cate_id']==$_smarty_tpl->tpl_vars['k']->value) {?>class="active"<?php }?>>
              <a href="/notice?cate_id=<?php echo $_smarty_tpl->tpl_vars['k']->value;?>
" ><?php echo $_smarty_tpl->tpl_vars['v']->value;?>
</a>
            </li>
          <?php } ?>
        <?php }?>
          <li style="float:right" class="active"><a class="btn btn-default btn-ms" href="/notice/addNotice">添加</a></li>
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
          <th class="text-center" style="width:300px;">标题</th>
          <th class="text-center" >作者</th>
          <th class="text-center" >时间</th>
          <th class="text-center">状态</th>
          <th>操作</th>
        </tr>
      </thead>
      <?php $_smarty_tpl->tpl_vars['notice_cate_id_map'] = new Smarty_variable(NoticeManager::$notice_cate_id_map, null, 0);?>
      <?php $_smarty_tpl->tpl_vars['notice_status_map'] = new Smarty_variable(NoticeManager::$notice_status_map, null, 0);?>
      <tbody>
        <?php  $_smarty_tpl->tpl_vars['v'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['v']->_loop = false;
 $_smarty_tpl->tpl_vars['k'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['noticeList']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['v']->key => $_smarty_tpl->tpl_vars['v']->value) {
$_smarty_tpl->tpl_vars['v']->_loop = true;
 $_smarty_tpl->tpl_vars['k']->value = $_smarty_tpl->tpl_vars['v']->key;
?>
          <tr id="dataList<?php echo $_smarty_tpl->tpl_vars['v']->value['id'];?>
">
            
            <td><?php echo $_smarty_tpl->tpl_vars['v']->value['id'];?>
</td>
            <td><?php echo $_smarty_tpl->tpl_vars['notice_cate_id_map']->value[$_smarty_tpl->tpl_vars['v']->value['cate_id']];?>
</td>
            <td><?php echo $_smarty_tpl->tpl_vars['v']->value['title'];?>
</td>
            <td><?php echo $_smarty_tpl->tpl_vars['v']->value['author'];?>
</td>
            <td><?php echo $_smarty_tpl->tpl_vars['v']->value['ctime'];?>
</td>
            <td><?php echo $_smarty_tpl->tpl_vars['notice_status_map']->value[$_smarty_tpl->tpl_vars['v']->value['status']];?>
</td>
            <td class="txtcenter"> 
            <!-- Single button -->
              <div class="btn-group">
                <button type="button" class="btn btn-info btn-xs dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                  操作 <span class="caret"></span>
                </button>
                <ul class="dropdown-menu" role="menu">
                  <li role="presentation"><a role="menuitem" tabindex="-1" href="/notice/edit?id=<?php echo $_smarty_tpl->tpl_vars['v']->value['id'];?>
">修改</a></li>
                  <?php if ($_smarty_tpl->tpl_vars['v']->value['status']==0) {?>
                    <li class=""  value="<?php echo $_smarty_tpl->tpl_vars['v']->value['id'];?>
"><a class="deleteOne" href="/notice/delete?id=<?php echo $_smarty_tpl->tpl_vars['v']->value['id'];?>
" >删除</a></li>
                  <?php } elseif ($_smarty_tpl->tpl_vars['v']->value['status']==1) {?>
                    <li class="" value="<?php echo $_smarty_tpl->tpl_vars['v']->value['id'];?>
"><a class="recoverOne" href="/notice/recover?id=<?php echo $_smarty_tpl->tpl_vars['v']->value['id'];?>
">展示</a></li>
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
