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
    console.log({/json_encode($auth)/})
</script>

<header class="navbar-inverse" id="top" role="banner" style="margin-bottom:20px;">
    <div class="navbar-header">
        <a class="navbar-brand" href="/auth/CleanRedirect" style="color:white">运营平台</a>
    </div>

    <nav class="collapse  navbar-collapse" role="navigation">
        <ul class="nav navbar-nav navbar-right">
            {/*<li class=""><a title="" href="#"><i class="icon icon-share-alt"></i> <span class="text">今日：{/date('Y-m-d')/}</span></a></li>*/}
            <li  class="dropdown" id="profile-messages" ><a title="" href="#" data-toggle="dropdown" data-target="#profile-messages" class="dropdown-toggle"><i class="icon icon-user"></i>  <span class="text">{/Yii::app()->user->name/}</span><b class="caret"></b></a>
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
</script>