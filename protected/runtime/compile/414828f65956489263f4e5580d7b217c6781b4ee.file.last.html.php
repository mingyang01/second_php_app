<?php /* Smarty version Smarty-3.1.18, created on 2015-09-28 14:41:46
         compiled from "/home/work/websites/tuan/protected/views/manual/last.html" */ ?>
<?php /*%%SmartyHeaderCode:6923936855608e12a942644-10886925%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '414828f65956489263f4e5580d7b217c6781b4ee' => 
    array (
      0 => '/home/work/websites/tuan/protected/views/manual/last.html',
      1 => 1439888338,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '6923936855608e12a942644-10886925',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'date' => 0,
    'type' => 0,
    'count' => 0,
    'data' => 0,
    'item' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.18',
  'unifunc' => 'content_5608e12a9a85a6_83242714',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_5608e12a9a85a6_83242714')) {function content_5608e12a9a85a6_83242714($_smarty_tpl) {?><?php echo $_smarty_tpl->getSubTemplate ("layouts/header.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, null, array(), 0);?>

<title>最后疯抢</title>
<link rel="stylesheet" href="/assets/lib/jquery-ui/jquery-ui.theme.min.css" />
<link rel="stylesheet" href="/assets/css/manual/manual.css" />
<script src='/assets/lib/js/Sortable.js'></script>
<style>
.selected{background-color:#ddd;}
.col-md-2,.col-md-3 {
    padding-right: 3px;
    padding-left: 3px;
}
</style>
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <ol class="breadcrumb">
                <li><a href="/">Home</a></li>
                <li class="active">宝贝推荐</li>
                <li class="active">最后疯抢</li>
            </ol>
            <div id="well" class="well pinned">
                <form class="form-inline" role="form" id="form">
                    <div class="form-group">
                        <label>日期：</label>
                        <input class="picker form-control" id="date" name="date"  type="text" value="<?php echo $_smarty_tpl->tpl_vars['date']->value;?>
"  data-date-format="yyyy-mm-dd">
                    </div>
                    <div class="form-group">
                        <label>类型：</label>
                        <select style="width:172px" class="form-control" name="type" id="type">
                            <option value='1' <?php if ($_smarty_tpl->tpl_vars['type']->value==1) {?>selected<?php }?>>已到最后疯抢</option>
                            <option value='2' <?php if ($_smarty_tpl->tpl_vars['type']->value==2) {?>selected<?php }?>>未到最后疯抢</option>
                        </select>
                    </div>
                    <button id="submit" class="btn btn-default">查看</button>
                    <?php if ($_smarty_tpl->tpl_vars['type']->value==1) {?>
                      <button id="saveGoodsSort" data-db='last' type="button" class="pull-right btn btn-default">保存排序</button>
                    <?php } else { ?>
                      <button id="saveGoods" data-db='last' type="button" class="pull-right btn btn-default">保存</button>
                    <?php }?>
                </form>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <h4 style="color:#fd6699">商品数目:<?php echo $_smarty_tpl->tpl_vars['count']->value;?>
</h4>
            <?php if ($_smarty_tpl->tpl_vars['type']->value==1) {?>
            <p>提示：拖拽排序</p>
            <?php }?>
            <?php if ($_smarty_tpl->tpl_vars['type']->value==2) {?>
            <p>提示：批量选定，添加到本周精选</p>
            <?php }?>
        </div>
    </div>
    <div class="row" id="box-contailer">
        <?php  $_smarty_tpl->tpl_vars['item'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['item']->_loop = false;
 $_smarty_tpl->tpl_vars['key'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['data']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['item']->key => $_smarty_tpl->tpl_vars['item']->value) {
$_smarty_tpl->tpl_vars['item']->_loop = true;
 $_smarty_tpl->tpl_vars['key']->value = $_smarty_tpl->tpl_vars['item']->key;
?>
        <div class="col-md-2">
            <div class="thumbnail"  data-gid="<?php echo $_smarty_tpl->tpl_vars['item']->value['gid'];?>
">
                <div class="img">
                    <img data-src="holder.js/100%x200" alt="100%x200" src="http://d04.res.meilishuo.net<?php echo $_smarty_tpl->tpl_vars['item']->value['img'];?>
" data-holder-rendered="true" style="height: 100%; width: 100%; display: block;">
                </div>
                <div class="caption">
                    <h3 id="thumbnail-label"><?php echo $_smarty_tpl->tpl_vars['item']->value['tid'];?>
<a class="anchorjs-link" href="#thumbnail-label"><span class="anchorjs-icon"></span></a></h3>
                    <p>
                        起始时间：<?php echo $_smarty_tpl->tpl_vars['item']->value['start'];?>
<br>结束时间：<?php echo $_smarty_tpl->tpl_vars['item']->value['end'];?>

                    </p>
                    <p class="rec_sku">
                        <span class="price_red"><?php echo $_smarty_tpl->tpl_vars['item']->value['price'];?>
</span>
                        <span class="price"><?php echo $_smarty_tpl->tpl_vars['item']->value['origin'];?>
</span>
                        <span class="price_red pull-right"><?php echo $_smarty_tpl->tpl_vars['item']->value['rate']*10;?>
折</span>
                    </p>
                    <p>
                        <a class="btn btn-default" role="button" target="_blank" href="http://www.meilishuo.com/share/item/<?php echo $_smarty_tpl->tpl_vars['item']->value['tid'];?>
">详情</a>
                        <?php if ($_smarty_tpl->tpl_vars['type']->value==2) {?>
                        <a data-gid=<?php echo $_smarty_tpl->tpl_vars['item']->value['gid'];?>
 role="button" class="selecteBox select-box btn btn-default pull-right">选定</a>
                        <?php }?>
                        <?php if ($_smarty_tpl->tpl_vars['type']->value==1) {?>
                        <a data-gid=<?php echo $_smarty_tpl->tpl_vars['item']->value['gid'];?>
 data-db='last' role="button" class="deleteBox delete-box btn btn-danger pull-right">删除</a>
                        <?php }?>
                    </p>
                </div>
            </div>
        </div>
        <?php } ?>
    </div>
</div>
<script type="text/javascript">
    var type = $('#type').val();
    var date = $('#date').val();
</script>
<script type="text/javascript" src="/assets/js/manual/common.js"></script>
<?php echo $_smarty_tpl->getSubTemplate ("layouts/footer.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, null, array(), 0);?>
<?php }} ?>
