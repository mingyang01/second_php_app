<?php /* Smarty version Smarty-3.1.18, created on 2015-08-20 12:35:51
         compiled from "/home/work/websites/tuan/protected/views/search/index.html" */ ?>
<?php /*%%SmartyHeaderCode:119980485055d55927232c53-70161178%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '30201747fa755962cb6c11c4668e2f8ed766d209' => 
    array (
      0 => '/home/work/websites/tuan/protected/views/search/index.html',
      1 => 1439522157,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '119980485055d55927232c53-70161178',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'count' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.18',
  'unifunc' => 'content_55d559272679c5_07423772',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_55d559272679c5_07423772')) {function content_55d559272679c5_07423772($_smarty_tpl) {?><?php echo $_smarty_tpl->getSubTemplate ("layouts/header.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, null, array(), 0);?>

<script type="text/javascript" src="/assets/lib/bootstrap-datepicker.js"></script>
<style type="text/css" src="/assets/css/datepicker.css"></style>
<script src="/assets/lib/bufferview.js"></script>
<script src="/assets/lib/jquery.pin.js"></script>
<title>搜索</title>
<style type="text/css">
.rec_sku .price {
    color: #999;
    text-decoration: line-through;
    padding-left: 10px
}
.rec_sku .price_red {
    color: #f69;
    font-size: 16px
}
#thumbnail-label {
    font-size: 16px
}
.img {
    height: 300px;
}
.pinActive {
    box-shadow: 0 10px 6px rgba(0,0,0,.12),0 1px 6px rgba(0,0,0,.12) !important;
    /*width: 100%!important;*/
    background-color: white;
    z-index:999
}

.caption > p {
    white-space: nowrap;
    /*background-color: lavenderblush;*/
}
.select2-selection {
    height: 34px !important;
}
.select2-selection__rendered {
    height: 34px !important;
    line-height: 32px !important;
}

</style>
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <ol class="breadcrumb">
                <li><a href="/">Home</a></li>
                <li class="active">搜索</li>
            </ol>
            <?php echo $_smarty_tpl->getSubTemplate ("search/search.html", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, null, array(), 0);?>

        </div>
    </div>
    <div  class="row">
        <div class="pinned col-md-12">
            <h4 id="tool-tip-count" style="color:#fd6699">商品数目:<?php echo $_smarty_tpl->tpl_vars['count']->value;?>
</h4>
            <p>提示：本页面主要提供搜索预览功能，并支持下载分析。 知道么，当时间条件为空时，将不根据时间范围删选哦。</p>
        </div>
    </div>
    <?php echo $_smarty_tpl->getSubTemplate ("audit/first-content-detail.html", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, null, array(), 0);?>


    <div  class="row" style="height:80px;"><div class="col-md-12" style="text-align:center;"><button id="load" class="col-md-12 btn btn-default">加载更多</button></div></div>
</div>

<script type="text/javascript">
    $('#tool-tip-count').text(
        $('[name="step"] option:selected').text() + $('[name="status"] option:selected').text() + '-'
        + $('#tool-tip-count').text() + "(" + $('[name="major"] option:selected').text() + "类目)"
    );
    $(".pinned").pin({'activeClass': 'pinActive'})
    
    $(window).resize(function(e){
        $(".pinned").pin({'activeClass': 'pinActive'})
    });
    
    var page = 1;
    $('#load').click(function(e){
        if (window.location.href.indexOf('?') != -1) {
            var url = window.location.href + "&data=1&page=" + page;
        } else {
            var url = window.location.href + "?data=1&page=" + page;
        }

        $.get(url, {}, function(data) {
            if (data.code == 1 && data.data.total) {
                $('#box-container').append($(data.data.html)[2].innerHTML)
                page +=1;
                setTimeout('$(".pinned").pin({"activeClass": "pinActive"})', 500)
            }
        }, 'json');
    });

</script>
<script type="text/javascript" src="/assets/js/audit/common.js"></script>
<script type="text/javascript" src="/assets/js/audit/first.js"></script>
<?php echo $_smarty_tpl->getSubTemplate ("layouts/footer.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, null, array(), 0);?>
<?php }} ?>
