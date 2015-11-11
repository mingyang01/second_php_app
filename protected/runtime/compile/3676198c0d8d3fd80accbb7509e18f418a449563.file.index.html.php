<?php /* Smarty version Smarty-3.1.18, created on 2015-09-28 12:25:03
         compiled from "/home/work/websites/tuan/protected/views/suprise/index.html" */ ?>
<?php /*%%SmartyHeaderCode:154806048255dd456fbe62b3-12840413%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '3676198c0d8d3fd80accbb7509e18f418a449563' => 
    array (
      0 => '/home/work/websites/tuan/protected/views/suprise/index.html',
      1 => 1442830795,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '154806048255dd456fbe62b3-12840413',
  'function' => 
  array (
  ),
  'version' => 'Smarty-3.1.18',
  'unifunc' => 'content_55dd456fd00d31_54921510',
  'variables' => 
  array (
    'area' => 0,
    'item' => 0,
    'count' => 0,
    'data' => 0,
    'append' => 0,
    'from' => 0,
  ),
  'has_nocache_code' => false,
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_55dd456fd00d31_54921510')) {function content_55dd456fd00d31_54921510($_smarty_tpl) {?><?php echo $_smarty_tpl->getSubTemplate ("layouts/header.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, null, array(), 0);?>

<script type="text/javascript" src="/assets/lib/bootstrap-datepicker.js"></script>
<style type="text/css" src="/assets/css/datepicker.css"></style>
<script src="/assets/lib/bufferview.js"></script>
<script src="/assets/lib/jquery.pin.js"></script>
<script src='/assets/lib/js/Sortable.js'></script>
<title>秒杀预览</title>
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
    z-index:999;
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
.nav-tabs a {
    color: black;
}
.tool a, .active a {
    border: 0px !important;
    color: white !important;
    background: #f46 !important;
    border-radius: 0px !important;
}

</style>
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <ol class="breadcrumb">
                <li><a href="/">Home</a></li>
                <li><a href="/">惊喜秒杀</a></li>
                <li class="active">秒杀预览</li>
            </ol>
            <?php echo $_smarty_tpl->getSubTemplate ("suprise/search.html", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, null, array(), 0);?>

        </div>
    </div>
    <div class="row">
        <div class="col-md-12 pinned ">
            <div role="tabpanel">
                <!-- Nav tabs -->
                <ul class="nav nav-tabs" role="tablist">
                    <?php  $_smarty_tpl->tpl_vars['item'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['item']->_loop = false;
 $_smarty_tpl->tpl_vars['key'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['area']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['item']->key => $_smarty_tpl->tpl_vars['item']->value) {
$_smarty_tpl->tpl_vars['item']->_loop = true;
 $_smarty_tpl->tpl_vars['key']->value = $_smarty_tpl->tpl_vars['item']->key;
?>
                    <li data-start="<?php echo $_smarty_tpl->tpl_vars['item']->value['stime'];?>
" data-end="<?php echo $_smarty_tpl->tpl_vars['item']->value['etime'];?>
" role="presentation" class="supriseTimeList">
                        <a href="#whole" aria-controls="whole" role="tab" data-toggle="tab"><?php echo $_smarty_tpl->tpl_vars['item']->value['area'];?>
</a>
                    </li>
                    <?php } ?>
                    <li role="presentation" class="tool pull-right">
                        <a id="schedule" href="javascript:;">保存</a>
                    </li>

                    <li role="presentation" class="tool pull-right">
                        <a id="search" href="javascript:;">查看</a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
    <div  class="row">
        <div class="col-md-12">
            <h4 id="tool-tip-count" style="color:#fd6699">商品数目:<?php echo $_smarty_tpl->tpl_vars['count']->value;?>
</h4>
            <p>提示：本页面主要提供预览功能，以及排序修改</p>
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
        <div class="col-md-3 col-sm-4">
            <div class="thumbnail" data-shop=<?php echo $_smarty_tpl->tpl_vars['item']->value['shop'];?>
 data-gid=<?php echo $_smarty_tpl->tpl_vars['item']->value['gid'];?>
 data-tid=<?php echo $_smarty_tpl->tpl_vars['item']->value['tid'];?>
>
                <div class="img" style="position: relative;">
                    <a target="_blank" href="http://www.meilishuo.com/share/item/<?php echo $_smarty_tpl->tpl_vars['item']->value['tid'];?>
">
                        <img data-src="holder.js/100%x200" alt="100%x200" src="<?php echo Yii::app()->image->getWebsiteImageUrl($_smarty_tpl->tpl_vars['item']->value['img']);?>
" data-holder-rendered="true" style="height: 100%; width: 100%; display: block;">
                    </a>
                    <?php if (in_array($_smarty_tpl->tpl_vars['append']->value[$_smarty_tpl->tpl_vars['item']->value['gid']]['category'],array(1,10,12,13))) {?>
                      <div style="position: absolute;top: 0px;left: 0px;"><img src="/assets/images/miao_<?php echo $_smarty_tpl->tpl_vars['append']->value[$_smarty_tpl->tpl_vars['item']->value['gid']]['category'];?>
.png" style="width:64px;"></div>
                    <?php }?>
                </div>
                <div class="caption">
                    <h3 id="thumbnail-label"><?php echo $_smarty_tpl->tpl_vars['item']->value['name'];?>
<a class="anchorjs-link" href="#thumbnail-label"><span class="anchorjs-icon"></span></a><span class="price_red pull-right" style="color: #f69;"><?php echo $_smarty_tpl->tpl_vars['item']->value['level'];?>
</span></h3>
                    <p><span>店铺：</span><?php echo $_smarty_tpl->tpl_vars['item']->value['shop_nick'];?>
</p>
                    <p><?php echo $_smarty_tpl->tpl_vars['item']->value['tid'];?>
@<?php echo $_smarty_tpl->tpl_vars['item']->value['shop'];?>
</p>
                    <p style=" white-space: nowrap;"><?php echo $_smarty_tpl->tpl_vars['item']->value['goods_first_catalog'];?>
—<?php echo $_smarty_tpl->tpl_vars['item']->value['goods_second_catalog'];?>
—<?php echo $_smarty_tpl->tpl_vars['item']->value['goods_three_catalog'];?>
</p>
                    <p>销量：<?php echo $_smarty_tpl->tpl_vars['item']->value['sale_num'];?>
&nbsp;&nbsp;库存：<?php echo $_smarty_tpl->tpl_vars['item']->value['repertory'];?>
</p>
                    <p>
                        报名时间：<?php echo $_smarty_tpl->tpl_vars['item']->value['createTime'];?>

                    </p>
                    <p>电话:<span class="partner_tel"><?php echo $_smarty_tpl->tpl_vars['item']->value['partner_tel'];?>
</span> &nbsp;QQ:<span class="partner_qq"><?php echo $_smarty_tpl->tpl_vars['item']->value['partner_qq'];?>
</span></p>
                    <p>
                        库存限制：<span style="color: red;font-size:20px"><?php echo $_smarty_tpl->tpl_vars['append']->value[$_smarty_tpl->tpl_vars['item']->value['gid']]['limit'];?>
</span>
                    </p>
                    <p class="rec_sku">
                        <span class="price_red"><?php echo $_smarty_tpl->tpl_vars['item']->value['price'];?>
</span>
                        <span class="price"><?php echo $_smarty_tpl->tpl_vars['item']->value['origin'];?>
</span>
                        <span class="price_red"><?php echo $_smarty_tpl->tpl_vars['item']->value['rate']*10;?>
折</span>
                        <span class="pull-right">
                          <a href="/goods/editGoods?gid=<?php echo $_smarty_tpl->tpl_vars['item']->value['gid'];?>
" target="_blank" class="btn btn-default btn-sm">编辑</a>
                          <button data-gid="<?php echo $_smarty_tpl->tpl_vars['item']->value['gid'];?>
" role="button" class="btn btn-warning btnCancelGoodsOne btn-sm">退回</button>
                        </span>
                    </p>
                </div>
            </div>
        </div>
        <?php } ?>
    </div>
</div>
<script type="text/javascript">
    $('[data-start="<?php echo $_smarty_tpl->tpl_vars['from']->value;?>
"]').addClass('active');

    $('#tool-tip-count').text($('#tool-tip-count').text() + "(" + $('[name="major"] option:selected').text() + "类目)"
    );
    $(".pinned").pin({'activeClass': 'pinActive'})
    
    $(window).resize(function(e){
        $(".pinned").pin({'activeClass': 'pinActive'})
    });
    // $('a[data-toggle="tab"]').on('shown.bs.tab', function (e) {
    //     e.target // newly activated tab
    //     e.relatedTarget // previous active tab
    //     $('#search').click()
    // })
    $('#search').click(function(e){
        e.preventDefault();
        var url = '/suprise/index?'
        if (0 == dateChange) {
            url += 'start=' + 
            $('.nav-tabs .active').data('start') + '&end=' +
            $('.nav-tabs .active').data('end') + '&'
            + $('#form').serialize();
        } else {
          url += $('#form').serialize();
        }

        window.location = url;
        return false;
    });
    var dateChange = 0;
    $('#date').change(function(e) {
        dateChange = 1;
    });
$(function(){
  /** 推荐位移动 */
  var moveRecommend = document.getElementById("box-contailer");
  new Sortable(moveRecommend, {
    handle: '.img'
  });
  $("#schedule").click(function(){
    if ($(this).hasClass("disabled")) return false;
    var thisObj = $(this);
    
    var startTime = $(".supriseTimeList[class*='active']").attr("data-start");
    var endTime   = $(".supriseTimeList[class*='active']").attr("data-end");

    var selectedList = $(".thumbnail");
    var idsArr = [];
    $.each(selectedList, function(k, v){
      idsArr.push($(v).closest(".thumbnail").attr("data-gid"));
    });
    if (idsArr.length < 1) {
      alert('请选择商品');
      return false;
    }
    var ids = idsArr.join(",");
    
    if (confirm("您确定要将 "+idsArr.length+"个商品重新排序吗？") == false) {
      return false;
    }
    
    var postData = {'tuan_id':ids, 'start_time':startTime, 'end_time':endTime};
    var objInHtml = thisObj.html();
    setBtnStatus(thisObj, '提交中...', 'disabled');
    $.post('/suprise/saveZhengdianSort', postData, function(json){
      if (json.succ == 1) {
        //alert('成功操作： '+json.succ_num+" 个商品，操作失败： "+json.err_num+" 个商品");
        window.location.reload();
        setBtnStatus(thisObj, objInHtml, 'succ');
      } else {
        alert(json.msg);
        setBtnStatus(thisObj, objInHtml, 'succ');
      }
    },'json').error(function(code, data){
      alert('遇到服务器错误');
      setBtnStatus(thisObj, objInHtml, 'succ');
    });
  });
  
  /**
   * 取消排期
   */
  $(".btnCancelGoodsOne").click(function(){
    if ($(this).hasClass("disabled")) return false;
    var thisObj = $(this);
    var tuanId  = thisObj.attr("data-gid");
    if (!tuanId) {
      alert('商品id不存在');
      return false;
    }
    if (confirm("您确定要取消排期吗？") == false) {
      return false;
    }
    
    var postData = {'tuan_id':tuanId};
    var objInHtml = thisObj.html();
    setBtnStatus(thisObj, '提交中...', 'disabled');
    $.post('/schedule/cancelSchedule', postData, function(json){
      if (json.succ == 1) {
        setBtnStatus(thisObj, objInHtml, 'succ');
        thisObj.closest(".thumbnail").parent(".col-md-3").fadeOut(function(){
          $(this).remove();
        });
      } else {
        alert(json.msg);
        setBtnStatus(thisObj, objInHtml, 'succ');
      }
    },'json').error(function(code, data){
      alert('遇到服务器错误');
      setBtnStatus(thisObj, objInHtml, 'succ');
    });
  });
});
</script>
<?php echo $_smarty_tpl->getSubTemplate ("layouts/footer.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, null, array(), 0);?>
<?php }} ?>
