<?php /* Smarty version Smarty-3.1.18, created on 2015-09-29 11:52:31
         compiled from "/home/work/websites/tuan/protected/views/takepart/index.html" */ ?>
<?php /*%%SmartyHeaderCode:1439665506560a0affe35263-37145242%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '63fa0b1346e48d9c70fa912d2955ecfceb666da4' => 
    array (
      0 => '/home/work/websites/tuan/protected/views/takepart/index.html',
      1 => 1439522157,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '1439665506560a0affe35263-37145242',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'events' => 0,
    'not_check_apply' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.18',
  'unifunc' => 'content_560a0afff01aa2_69087807',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_560a0afff01aa2_69087807')) {function content_560a0afff01aa2_69087807($_smarty_tpl) {?><?php if (!is_callable('smarty_function_html_options')) include '/home/work/framework/extensions/Smarty/plugins/function.html_options.php';
?><?php echo $_smarty_tpl->getSubTemplate ("layouts/header.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, null, array(), 0);?>

<title>团购报名</title>
<div class="container">
  <div class="row">
    <div class="col-md-12">
      <div role="tabpanel">
        <ul class="nav nav-tabs" role="tablist">
          <li role="presentation" >
            <a href="/takepart" >团购报名</a>
          </li>
          <li role="presentation" class="active">
            <a href="/takepart/newApply" >新版团购报名</a>
          </li>
        </ul>
      </div>
    </div>
  </div>
  <div class="row">
    <div class="col-md-4">
        <!-- 活动名称 -->
        <div class="control-group">
          <blockquote><p>推ID<small>必选</small></p></blockquote>
          <input type="text" class="require input-xlarge form-control" id="twitterInput" placeholder="推Id">
          <span class="help-inline"></span>
        </div>
        
        <!-- 活动标题 -->
        <div class="control-group">
          <blockquote><p>活动标题<small>选填</small></p></blockquote>
          <select class="form-control input-xlarge" id="eventIdInput">
              <option value="0">普通团购(慎用截图功能)</option>
              <?php echo smarty_function_html_options(array('options'=>$_smarty_tpl->tpl_vars['events']->value),$_smarty_tpl);?>

          </select>
          <span class="help-inline"></span>
        </div>
        
        <!-- 活动名称 -->
        <div class="control-group">
          <blockquote><p>折扣价<small>必选</small></p></blockquote>
          <input type="text" class="form-control input-medium" id="offPriceInput" placeholder="折扣价">
          <span class="help-inline"></span>
        </div>
        <!-- 内容 -->
        <div class="control-group">
          
          <input type="hidden" name="not_check_apply" id="notCheckApply" value=<?php echo $_smarty_tpl->tpl_vars['not_check_apply']->value;?>
>
          <p></p>
          <p id="errorMessageCon" style="color:red;"></p>
          <p></p>
          <input type="button" class="btn btn-primary Sub createBtn" id="createBtn" disabled value="报名">
        </div>
    </div>
    <div class="col-md-6 thumbnail twitterInfoCon" style="height:350px; width:500px;float:left;margin-top:10px;">

    </div>
  </div>
</div>
<script>
$(function(){
  $("#twitterInput").focusout(function(){
    var thisObj = $(this);
    var twitterId = thisObj.val();
    if (!twitterId) return false;
    
    $.post('/takepart/getTwitterInfo', { 'twitter_id':twitterId }, function(json){
      if (json.succ == 1) {
        $(".twitterInfoCon").html(json.data);
        $("#createBtn").removeAttr("disabled");
      } else {
        $("#errorMessageCon").html(json.msg);
      }
    }, 'json');
  });
  
  
  $("#createBtn").click(function(){
    if ($(this).hasClass("disabled")) return false;
    var thisObj = $(this);
    
    var twitterId = $("#twitterInput").val();
    var eventId   = $("#eventIdInput").val();
    var price     = $("#offPriceInput").val();
    var notCkeck  = $("#notCheckApply").val();
    /*if ($("#notCheckApply").prop("checked") === true) {
      notCkeck = 1;
    }*/
    
    if (!twitterId) {
      alert('请输入twitter_id');
      return false;
    }
    if (!price) {
      alert('请输入价格');
      return false;
    }
    
    var postData = { 'twitter_id':twitterId, 'event_id':eventId, 'not_check_apply':notCkeck, 'price':price };
    var objInHtml = thisObj.html();
    setBtnStatus(thisObj, '提交中...', 'disabled');
    $.post('/takepart/saveTwitterInfo', postData, function(json){
      if (json.succ == 1) {
        alert('报名成功');
        window.location.reload();
      } else {
        alert(json.msg);
        $("#errorMessageCon").html(json.msg);
      }
      setBtnStatus(thisObj, objInHtml, 'succ');
    },'json').error(function(code,data){
      alert('遇到服务器错误');
      setBtnStatus(thisObj, objInHtml, 'succ');
    });
  });
});
</script>
<?php echo $_smarty_tpl->getSubTemplate ("layouts/footer.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, null, array(), 0);?>
<?php }} ?>
