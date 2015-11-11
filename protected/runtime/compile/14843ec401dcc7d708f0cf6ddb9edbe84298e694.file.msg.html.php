<?php /* Smarty version Smarty-3.1.18, created on 2015-09-28 13:25:31
         compiled from "/home/work/websites/tuan/protected/views/layouts/msg.html" */ ?>
<?php /*%%SmartyHeaderCode:55628045055cd9a4158b505-13127916%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '14843ec401dcc7d708f0cf6ddb9edbe84298e694' => 
    array (
      0 => '/home/work/websites/tuan/protected/views/layouts/msg.html',
      1 => 1443416516,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '55628045055cd9a4158b505-13127916',
  'function' => 
  array (
  ),
  'version' => 'Smarty-3.1.18',
  'unifunc' => 'content_55cd9a415e40d6_14320705',
  'variables' => 
  array (
    'type' => 0,
    'msg' => 0,
    'redirect_url' => 0,
  ),
  'has_nocache_code' => false,
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_55cd9a415e40d6_14320705')) {function content_55cd9a415e40d6_14320705($_smarty_tpl) {?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8"/>
    <link rel="stylesheet" href="/assets/css/bootstrap.min.css" />
    <link rel="stylesheet" href="/assets/css/dropdown-submenu.css" />
    <link rel="stylesheet" href="/assets/css/datepicker.css" />
    <link rel="stylesheet" href="/assets/css/bootstrap-responsive.min.css" />
    <link rel="stylesheet" href="/assets/lib/easyui/themes/metro/easyui.css" />
    <link rel="stylesheet" href="/assets/css/main.css" />
    <link rel="stylesheet" href="/assets/css/admin.css" />
    <link rel="shortcut icon" href="/assets/images/favicon.ico" />
    <script src="/assets/lib/jquery-2.1.1.js"></script>
    <script src="/assets/lib/bootstrap.min.js"></script>
    <script src="/assets/lib/jquery.easyui.min.js"></script>
    <script type="text/javascript" src="/assets/lib/bootstrap-datepicker.js"></script>
    <script src="/assets/js/admin.js"></script>
</head>
<header class="navbar-inverse" id="top" role="banner" style="margin-bottom:20px;">
    <div class="navbar-header">
        <a class="navbar-brand" href="/auth/CleanRedirect" style="color:white">运营平台</a>
    </div>
    <nav class="collapse  navbar-collapse" role="navigation">
        <ul class="nav navbar-nav navbar-right">
            
            <li  class="dropdown" id="profile-messages" ><a title="" href="#" data-toggle="dropdown" data-target="#profile-messages" class="dropdown-toggle"><i class="icon icon-user"></i>  <span class="text"><?php echo Yii::app()->user->name;?>
</span><b class="caret"></b></a>
                <ul class="dropdown-menu">
                    <li><a target="_blank" href="http://speed.meilishuo.com/user/profile/"><i class="icon-user"></i> 个人信息</a></li>
                    <li class="divider"></li>
                    <li><a target="_blank" href="http://speed.meilishuo.com/time/time_manage"><i class="icon-check"></i> 我的日程</a></li>
                    <li class="divider"></li>
                    <li><a href="/site/logout"><i class="icon-key"></i> 退出登录</a></li>
                </ul>
            </li>
            <li class=""><a title="" href="/site/logout"><i class="icon icon-share-alt"></i> <span class="text">退出登录</span></a></li>
        </ul>
    </nav>
</header>
<div id="content" class="span10" style="margin-left:100px;">
  <div class="pageTit page-header">
    <h1>提示消息</h1>
  </div>
  <div class="msgBox">
    <div class="message alert alert-<?php echo $_smarty_tpl->tpl_vars['type']->value;?>
" style="height:150px;">
      <div class="span1" style="padding-top:20px">
         <img alt="<?php echo $_smarty_tpl->tpl_vars['type']->value;?>
" src="/assets/images/<?php echo $_smarty_tpl->tpl_vars['type']->value;?>
.png">
      </div>
      <div class="span8">
        <div style="margin-left:10px;">
          <h1>
            <?php echo $_smarty_tpl->tpl_vars['msg']->value;?>

          </h1>
          <span>
          <?php if ($_smarty_tpl->tpl_vars['redirect_url']->value) {?>
            <a href="<?php echo $_smarty_tpl->tpl_vars['redirect_url']->value;?>
">点击此处跳转</a>（<em id="jumpTime">5</em>秒后自动跳转）
          <?php } else { ?>
            <a href="javascript:history.back();">返回上一页</a>
          <?php }?>
          </span>
          <span>
              &nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp; <a href="/">返回主页</a>
          </span>
        </div>
      </div>
    </div>
  </div>
</div><!-- /content -->
<?php if ($_smarty_tpl->tpl_vars['redirect_url']->value) {?>
<script type="text/javascript">
var time = 5;
var jumpInterval = setInterval(function(){
  time--;
  if (time < 0) time = 0;
  $("#jumpTime").text(time);
  if (time == 0) {
      clearInterval(jumpInterval);
      location.href = "<?php echo $_smarty_tpl->tpl_vars['redirect_url']->value;?>
";
  }
}, 1000);
</script>
<?php }?>
<?php echo $_smarty_tpl->getSubTemplate ("layouts/footer.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, null, array(), 0);?>

<?php }} ?>
