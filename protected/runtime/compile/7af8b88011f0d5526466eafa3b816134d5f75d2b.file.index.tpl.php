<?php /* Smarty version Smarty-3.1.18, created on 2015-10-15 19:06:37
         compiled from "/home/work/websites/tuan/protected/views/auth/index.tpl" */ ?>
<?php /*%%SmartyHeaderCode:79404338155d2f91d4b3ad5-96733961%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '7af8b88011f0d5526466eafa3b816134d5f75d2b' => 
    array (
      0 => '/home/work/websites/tuan/protected/views/auth/index.tpl',
      1 => 1444907196,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '79404338155d2f91d4b3ad5-96733961',
  'function' => 
  array (
  ),
  'version' => 'Smarty-3.1.18',
  'unifunc' => 'content_55d2f91d4f9621_25597860',
  'variables' => 
  array (
    'auth' => 0,
  ),
  'has_nocache_code' => false,
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_55d2f91d4f9621_25597860')) {function content_55d2f91d4f9621_25597860($_smarty_tpl) {?><!DOCTYPE html>
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
<style>
    .extra{margin-left:100px;}
    .tuan{
        display: block;
        width:124px;
        height: 94px;
        cursor:pointer;
        background: url('/assets/images/authimg/tuan.png');
    }
    .tuan:hover{
        display: block;
        cursor: pointer;
        position:relative;
        width:140px;
        left:-8px;
        top:-6px;
        height: 106px;
        background: url('/assets/images/authimg/tuan_01.png');
    }
    .tuan-active{
        pointer-events:none;
        display: block;
        width:124px;
        height: 94px;
        background: url('/assets/images/tuan_n.png');
    }
    .miao{
        display: block;
        cursor: pointer;
        width:124px;
        height: 94px;
        background: url('/assets/images/authimg/miao.png');
    }
    .miao:hover{
        position:relative;
        cursor: pointer;
        width:140px;
        left:-8px;
        top:-6px;
        height: 106px;
        background: url('/assets/images/authimg/miao_01.png');
    }
    .miao-active{
        pointer-events:none;
        display: block;
        width:124px;
        height: 94px;
        background: url('/assets/images/miao_n.png');
    }
    .qing{
        display: block;
        cursor: pointer;
        width:124px;
        height: 94px;
        background: url('/assets/images/authimg/qingcang.png');
    }
    .qing:hover{
        position:relative;
        cursor: pointer;
        width:140px;
        left:-8px;
        top:-6px;
        height: 106px;
        background: url('/assets/images/authimg/qingcang_01.png');
    }
    .qing-active{
        pointer-events:none;
        display: block;
        width:124px;
        height: 94px;
        background: url('/assets/images/qingcang_n.png');
    }

    .jinbi{
        display: block;
        width:124px;
        height: 94px;
        background: url('/assets/images/authimg/jinbi.png');
    }
    .jinbi:hover{
        position:relative;
        width:140px;
        left:-8px;
        top:-6px;
        height: 106px;
        background: url('/assets/images/authimg/jinbi_01.png');
    }

    .desire{
        display: block;
        width:140px;
        height: 94px;
        background: url('/assets/images/authimg/desire.png');
    }
    .desire:hover{
        position:relative;
        width:140px;
        left:-8px;
        top:-6px;
        height: 106px;
        background: url('/assets/images/authimg/desire_01.png');
    }

    .jinbi-active{
        pointer-events:none;
        display: block;
        width:124px;
        height: 94px;
        background: url('/assets/images/jinbi.png');
    }
</style>
<script>
    console.log(<?php echo json_encode($_smarty_tpl->tpl_vars['auth']->value);?>
)
</script>

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

<div class="container">
    <div class="row" style="margin-top:200px;height:100px;">
        <div  class="col-md-4" style="text-align:center">
            <a class="tuan skip-btn extra"  data-item="tuan" ></a>
        </div>
        <div  class="col-md-4" style="text-align:center">
            <a class="miao skip-btn extra"  data-item="miao" ></a>
        </div>
        <div  class="col-md-4" style="text-align:center">
            <a class="qing skip-btn extra"  data-item="qing" ></a>
        </div>
    </div>
    <div class="row" style="margin-top:50px;">
        <div  class="col-md-4" style="text-align:center;">
            <a class="jinbi skip-btn extra"  data-item="jinbi" ></a>
        </div>
        <div  class="col-md-4" style="text-align:center;">
            <a class="desire skip-btn extra"  data-item="desire" ></a>
        </div>
    </div>
</div>
<script>
    $('.skip-btn').click(function(){
        var item = $(this).attr('data-item');
        window.location = "/auth/CreateMenu?item="+item;
       
    })
</script><?php }} ?>
