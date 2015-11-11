<?php /* Smarty version Smarty-3.1.18, created on 2015-09-28 14:42:27
         compiled from "/home/work/websites/tuan/protected/views/recommend/twitterList.html" */ ?>
<?php /*%%SmartyHeaderCode:14043479325608e15371f0e5-22388698%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'cad4e75980b3adf379a219782184add27ee45565' => 
    array (
      0 => '/home/work/websites/tuan/protected/views/recommend/twitterList.html',
      1 => 1439522157,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '14043479325608e15371f0e5-22388698',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'recommendTwitterList' => 0,
    'v' => 0,
    'cataArr' => 0,
    'k' => 0,
    'twitterList' => 0,
    'twitterNum' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.18',
  'unifunc' => 'content_5608e1538b1416_53209764',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_5608e1538b1416_53209764')) {function content_5608e1538b1416_53209764($_smarty_tpl) {?><?php if (!is_callable('smarty_modifier_date_format')) include '/home/work/framework/extensions/Smarty/plugins/modifier.date_format.php';
?><?php echo $_smarty_tpl->getSubTemplate ("layouts/header.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, null, array(), 0);?>

<link rel="stylesheet" href="/assets/css/recommend.css" />

<script src="/assets/lib/jquery-1.7.1.min.js"></script>
<script src="/assets/js/recommend.js"></script>
<script src="/assets/lib/js/jquery.lazyload.js"></script>
<script src='/assets/lib/js/Sortable.js'></script>
<script src='/assets/js/admin.js'></script>
<style>
.deleteRecommend {
    position: relative;
    top: -29px;
    right: -140px;
}
.navFixed {
  position: fixed;
  top: 0px;
}
</style>
<div class="container">
  <div class="row" style="width: 1170px;">
    <div class="col-md-12" style="padding:0;">
      <ol class="breadcrumb">
        <li><a href="/">Home</a></li>
        <li class="active">人工干预</li>
      </ol>
    </div>
  </div>
  
  <div class="container tab-pane active well" role="tabpanel" id="tableshow">
    <ul class="weekessence rec_sku" id="recommendBox">
      <?php if ($_smarty_tpl->tpl_vars['recommendTwitterList']->value) {?>
        <?php  $_smarty_tpl->tpl_vars['v'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['v']->_loop = false;
 $_smarty_tpl->tpl_vars['k'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['recommendTwitterList']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['v']->key => $_smarty_tpl->tpl_vars['v']->value) {
$_smarty_tpl->tpl_vars['v']->_loop = true;
 $_smarty_tpl->tpl_vars['k']->value = $_smarty_tpl->tpl_vars['v']->key;
?>
          <li class="recommendList recommendList<?php echo $_smarty_tpl->tpl_vars['v']->value['id'];?>
"  data-Id="<?php echo $_smarty_tpl->tpl_vars['v']->value['id'];?>
" data-twitterId="<?php echo $_smarty_tpl->tpl_vars['v']->value['twitter_id'];?>
" data-goodsId="<?php echo $_smarty_tpl->tpl_vars['v']->value['goods_id'];?>
">
            <div class="s_picBox">
              <img src="http://d06.res.meilishuo.net/<?php echo $_smarty_tpl->tpl_vars['v']->value['goods_image_pc'];?>
">
            </div> 
            <p class="txt"><?php echo $_smarty_tpl->tpl_vars['v']->value['goods_name'];?>
</p>
            <p class="price_box">
              <span class="price_red"><?php echo $_smarty_tpl->tpl_vars['v']->value['off_price'];?>
</span>
              <span class="price"><?php echo $_smarty_tpl->tpl_vars['v']->value['off_num'];?>
</span>
            </p>
            <p><?php echo smarty_modifier_date_format($_smarty_tpl->tpl_vars['v']->value['end_time'],"%Y-%m-%d %H:%M");?>
</p>
            <a class="glyphicon glyphicon-trash deleteRecommend recommended" data-twitterId="<?php echo $_smarty_tpl->tpl_vars['v']->value['twitter_id'];?>
"></a>
          </li>
        <?php } ?>
      <?php }?>
    </ul>
    <div class="recommendBtnBox">
      <a class="btn btn-small btn-info" id="recommendBtn" href="javascript:void(0);">一键推荐</a>
      <span style="margin-left:10px;"><em style="color:red;">注</em>：拖动图片可以排序哦</span>
    </div>
  </div>



<div id="m_ca" style="height: 50px;">
  <div class="f_container">
    <div class="c_content scrollout"> 
      <div class="classify"> 
        <ul class="c_menu"> 
          <?php  $_smarty_tpl->tpl_vars['v'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['v']->_loop = false;
 $_smarty_tpl->tpl_vars['k'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['cataArr']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['v']->key => $_smarty_tpl->tpl_vars['v']->value) {
$_smarty_tpl->tpl_vars['v']->_loop = true;
 $_smarty_tpl->tpl_vars['k']->value = $_smarty_tpl->tpl_vars['v']->key;
?>
            <li class="<?php if ($_smarty_tpl->tpl_vars['k']->value==0) {?>tab_active<?php }?>">
              <a class="link cicon <?php if ($_smarty_tpl->tpl_vars['k']->value==0) {?>light_<?php echo $_smarty_tpl->tpl_vars['v']->value['en_title'];?>
<?php } else { ?><?php echo $_smarty_tpl->tpl_vars['v']->value['en_title'];?>
<?php }?>" data-enTitle="<?php echo $_smarty_tpl->tpl_vars['v']->value['en_title'];?>
" data-cataId="<?php echo $_smarty_tpl->tpl_vars['k']->value;?>
" href="javascript:;">
                <span class="ctitle"><?php echo $_smarty_tpl->tpl_vars['v']->value['title'];?>
</span>
              </a>
            </li>
          <?php } ?>
        </ul>
      </div>
    </div>
    
  </div>
</div>


  <div class="container table-bordered" style=" margin-bottom:50px;"> 
    <div class="row" id="chartshow">
      <div class="dataBox">
        <ul class="weekessence rec_sku" id="twitterContent">
          <?php $_smarty_tpl->tpl_vars['twitterNum'] = new Smarty_variable(0, null, 0);?>
          <?php  $_smarty_tpl->tpl_vars['v'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['v']->_loop = false;
 $_smarty_tpl->tpl_vars['k'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['twitterList']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['v']->key => $_smarty_tpl->tpl_vars['v']->value) {
$_smarty_tpl->tpl_vars['v']->_loop = true;
 $_smarty_tpl->tpl_vars['k']->value = $_smarty_tpl->tpl_vars['v']->key;
?>
             <li class="twitterList twitterList<?php echo $_smarty_tpl->tpl_vars['v']->value['id'];?>
" data-Id="<?php echo $_smarty_tpl->tpl_vars['v']->value['id'];?>
" data-twitterId="<?php echo $_smarty_tpl->tpl_vars['v']->value['twitter_id'];?>
" data-goodsId="<?php echo $_smarty_tpl->tpl_vars['v']->value['goods_id'];?>
">
               <div class="s_picBox">
                 <?php if ($_smarty_tpl->tpl_vars['twitterNum']->value<10) {?>
                   <img src="http://d06.res.meilishuo.net/<?php echo $_smarty_tpl->tpl_vars['v']->value['goods_image_pc'];?>
">
                 <?php } else { ?>
                   <img class="lazy" src="/assets/images/gray.gif" data-original="http://d06.res.meilishuo.net/<?php echo $_smarty_tpl->tpl_vars['v']->value['goods_image_pc'];?>
" style="min-height:198px; max-height:267px;">
                 <?php }?>
               </div> 
               <p class="txt">
                 <?php echo $_smarty_tpl->tpl_vars['v']->value['goods_name'];?>

               </p>
               <p class="price_box">
                 <span class="price_red"><?php echo $_smarty_tpl->tpl_vars['v']->value['off_price'];?>
</span>
                 <span class="price"><?php echo $_smarty_tpl->tpl_vars['v']->value['off_num'];?>
</span>
               </p>
               <p><?php echo smarty_modifier_date_format($_smarty_tpl->tpl_vars['v']->value['end_time'],"%Y-%m-%d %H:%M");?>
</p>
             </li>
             <?php $_smarty_tpl->tpl_vars['twitterNum'] = new Smarty_variable($_smarty_tpl->tpl_vars['twitterNum']->value+1, null, 0);?>
          <?php } ?>
        </ul>
      </div>
    </div>
  </div>
</div>

<script>
$(function() {
    /* $('.datepicker').datepicker({
        format: "yyyy-mm-dd",
        autoclose: true
    }).on('changeDate', function(ev){
        $(this).datepicker('hide');
    }); */
    
    /** 延时加载图片 */
    $("img.lazy").lazyload({
        //effect : "fadeIn"
    });
    
    /** 推荐位移动 */
    var moveRecommend = document.getElementById("recommendBox");
    new Sortable(moveRecommend);
    
    /** 返回顶部 */
    //goTopEx($(".goTop")[0]);
});
</script>
<?php echo $_smarty_tpl->getSubTemplate ("layouts/footer.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, null, array(), 0);?>

<?php }} ?>
