<?php /* Smarty version Smarty-3.1.18, created on 2015-09-28 12:24:03
         compiled from "/home/work/websites/tuan/protected/views/tuanRule/editMiaoshaNormalRule.html" */ ?>
<?php /*%%SmartyHeaderCode:3562874815608c0e34f03c2-06777065%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '03dbfdc39ec89d28debe6bd78e57ca8451c1c6bf' => 
    array (
      0 => '/home/work/websites/tuan/protected/views/tuanRule/editMiaoshaNormalRule.html',
      1 => 1442830795,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '3562874815608c0e34f03c2-06777065',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'rule_category_map' => 0,
    'category' => 0,
    'k' => 0,
    'v' => 0,
    'rule_list' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.18',
  'unifunc' => 'content_5608c0e3580c40_55633627',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_5608c0e3580c40_55633627')) {function content_5608c0e3580c40_55633627($_smarty_tpl) {?><?php echo $_smarty_tpl->getSubTemplate ("layouts/header.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, null, array(), 0);?>

<script src="/assets/lib/My97DatePicker/WdatePicker.js"></script>
<script src="/assets/lib/imgAjaxfileUp.js"></script>
<style>
.goods-photos {
  float:left;
}
.goods-photos li {
  width:107px;
  height:125px;
  border: 1px solid #ccc;
  padding:1px;
}

.goods-photos img {
  max-width:none;
}

.sku-error-label {
  margin-left: 5px;
  margin-top: 4px;
  color:#b94a48;
}
.upload-img {
    position: relative;
    cursor: pointer;
    overflow: hidden;
    display: inline-block;
    *display: inline;
    *zoom: 1
}

.upload-img  input {
    position: absolute;
    font-size: 100px;
    right: 0;
    top: 0;
    opacity: 0;
    filter: alpha(opacity=0);
    cursor: pointer
}
em {
    font-style: inherit;
}

</style>  
<div class="container">
  <div class="row">
    <div class="col-md-12">
      <ol class="breadcrumb">
          <li><a href="/">Home</a></li>
          <li class="active">编辑秒杀报名规则</li>
      </ol>
    </div>
  </div>
  <div class="row">
      <div class="col-md-12">
          <div role="tabpanel">
              <ul class="nav nav-tabs" role="tablist">
                  <?php  $_smarty_tpl->tpl_vars['v'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['v']->_loop = false;
 $_smarty_tpl->tpl_vars['k'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['rule_category_map']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['v']->key => $_smarty_tpl->tpl_vars['v']->value) {
$_smarty_tpl->tpl_vars['v']->_loop = true;
 $_smarty_tpl->tpl_vars['k']->value = $_smarty_tpl->tpl_vars['v']->key;
?>
                  <li role="presentation" <?php if ($_smarty_tpl->tpl_vars['category']->value==$_smarty_tpl->tpl_vars['k']->value) {?>class="active"<?php }?> data-date="{$k}">
                      <a href="/tuanRule/miaoshaRule?category=<?php echo $_smarty_tpl->tpl_vars['k']->value;?>
" ><?php echo $_smarty_tpl->tpl_vars['v']->value;?>
</a>
                  </li>
                  <?php } ?>
              </ul>
          </div>
      </div>
  </div>
  <div class="row">
    <div class="col-md-12">
      <form id="createForm" class="form-inline" enctype="multipart/form-data"  action="/goods/saveGoods" method="post">
        
        <?php  $_smarty_tpl->tpl_vars['v'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['v']->_loop = false;
 $_smarty_tpl->tpl_vars['k'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['rule_list']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['v']->key => $_smarty_tpl->tpl_vars['v']->value) {
$_smarty_tpl->tpl_vars['v']->_loop = true;
 $_smarty_tpl->tpl_vars['k']->value = $_smarty_tpl->tpl_vars['v']->key;
?>
          <div class="control-group">
            <blockquote><p><span class="ruleTip"><?php echo $_smarty_tpl->tpl_vars['v']->value['comment'];?>
</span><?php if ($_smarty_tpl->tpl_vars['v']->value['desc']) {?><small><?php echo $_smarty_tpl->tpl_vars['v']->value['desc'];?>
</small><?php }?></p></blockquote>
            <?php if ($_smarty_tpl->tpl_vars['v']->value['key']=='groupon_shop_ka_list'||$_smarty_tpl->tpl_vars['v']->value['key']=='miaosha_apply_shop_white_list') {?>
              <textarea class="require form-control ruleContent" type="text" name="<?php echo $_smarty_tpl->tpl_vars['v']->value['key'];?>
"  id="<?php echo $_smarty_tpl->tpl_vars['v']->value['key'];?>
" style="width:700px;height:200px;"><?php echo $_smarty_tpl->tpl_vars['v']->value['value'];?>
</textarea>
            <?php } else { ?>
              <input autocomplete ="off" class="require input-xlarge form-control ruleContent" type="text" name="<?php echo $_smarty_tpl->tpl_vars['v']->value['key'];?>
"  id="<?php echo $_smarty_tpl->tpl_vars['v']->value['key'];?>
" value="<?php echo $_smarty_tpl->tpl_vars['v']->value['value'];?>
">
            <?php }?>
            <button type="button" class="btn btn-primary Sub btnSaveRuleOne">修改</button>
            
            <label style="margin-left:50px;" class="invalidCon">（<span class="title"><?php if ($_smarty_tpl->tpl_vars['v']->value['invalid']==1) {?><em style='color:red;'>已生效</em><?php } else { ?><em>未生效</em><?php }?></span>） &nbsp;<button type="button" class="btn btn-warning btn-sm btnEditInvalid" data-key="<?php echo $_smarty_tpl->tpl_vars['v']->value['key'];?>
">修改</button></label>
            <span class="help-inline"></span>
          </div>
        <?php } ?>

        <p></p>
        <!-- 内容 -->
        <div class="control-group">
          
        </div>
      </form>
    </div>
  </div>
  

</div>
<script>
$(function(){
  $(".btnSaveRuleOne").click(function(){
    if ($(this).hasClass("disabled")) return false;
    var thisObj = $(this);
    var ruleObj = thisObj.closest(".control-group").find(".ruleContent");
    var ruleValue   = ruleObj.val();
    var ruleName    = ruleObj.attr("name");
    var ruleTips    = thisObj.closest(".control-group").find(".ruleTip").html();
    
    if (confirm("您确定要将 "+ruleTips+" 规则修改为 "+ruleValue+" ？请谨慎操作，修改后将直接影响商家后台团购报名。") == false) {
      return false;
    }
    
    var objInHtml = thisObj.html();
    setBtnStatus(thisObj, '提交中...', 'disabled');
    var url = '/tuanRule/saveNormalRule';
    $.post(url, {'rule_key': ruleName, 'rule_value': ruleValue}, function(json){
        if (json.succ == 1) {
           alert('修改成功')
        } else {
           alert(json.msg);
        }
        setBtnStatus(thisObj, objInHtml, 'succ');
    }, 'json').error(function(code,data){
        alert('系统出错了~');
        setBtnStatus(thisObj, objInHtml, 'succ');
    });
  });
  
  
  $(".btnEditInvalid").click(function(){
    if ($(this).hasClass("disabled")) return false;
    var thisObj = $(this);
    var ruleKey = thisObj.attr("data-key");
    var ruleObj = thisObj.closest(".invalidCon");
    var ruleTips = thisObj.closest(".control-group").find(".ruleTip").html();
    
    if (!ruleKey) {
      alert('规则不存在');
      return false;
    }
    
    if (confirm("您确定要修改 "+ruleTips+" 的状态吗？请谨慎操作!") === false) {
      return false;
    }
    
    var objInHtml = thisObj.html();
    setBtnStatus(thisObj, '提交中...', 'disabled');
    var url = '/tuanRule/editRuleInvalid';
    $.post(url, {'rule_key': ruleKey}, function(json){
        if (json.succ == 1) {
           // alert('修改成功');
           if (json.invalid == 1) {
             ruleObj.find(".title").html("<em style='color:red;'>已生效</em>");
           } else {
             ruleObj.find(".title").html("<em>未生效</em>");
           }
        } else {
           alert(json.msg);
        }
        setBtnStatus(thisObj, objInHtml, 'succ');
    }, 'json').error(function(code,data){
        alert('系统出错了~');
        setBtnStatus(thisObj, objInHtml, 'succ');
    });
  });
})

/**
 * 显示错误信息
 */
function showError(obj, msg)
{
  if (obj.length) {
    var parentObj = obj.closest('.control-group');
    parentObj.addClass('error').find('.help-inline').text(msg)
    obj.focus();
  }
  return obj;
}
</script>
<?php echo $_smarty_tpl->getSubTemplate ("layouts/footer.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, null, array(), 0);?>
<?php }} ?>
