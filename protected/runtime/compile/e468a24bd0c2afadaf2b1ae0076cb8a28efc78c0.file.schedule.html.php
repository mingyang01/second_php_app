<?php /* Smarty version Smarty-3.1.18, created on 2015-09-28 12:23:14
         compiled from "/home/work/websites/tuan/protected/views/suprise/schedule.html" */ ?>
<?php /*%%SmartyHeaderCode:39269021655d3ebcf67d128-63511328%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'e468a24bd0c2afadaf2b1ae0076cb8a28efc78c0' => 
    array (
      0 => '/home/work/websites/tuan/protected/views/suprise/schedule.html',
      1 => 1442830795,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '39269021655d3ebcf67d128-63511328',
  'function' => 
  array (
  ),
  'version' => 'Smarty-3.1.18',
  'unifunc' => 'content_55d3ebcf6fb998_61168884',
  'variables' => 
  array (
    'area' => 0,
    'item' => 0,
    'count' => 0,
    'data' => 0,
    'date' => 0,
    'fail_reason' => 0,
    'from' => 0,
  ),
  'has_nocache_code' => false,
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_55d3ebcf6fb998_61168884')) {function content_55d3ebcf6fb998_61168884($_smarty_tpl) {?><?php if (!is_callable('smarty_function_html_options')) include '/home/work/framework/extensions/Smarty/plugins/function.html_options.php';
?><?php echo $_smarty_tpl->getSubTemplate ("layouts/header.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, null, array(), 0);?>

<script type="text/javascript" src="/assets/lib/bootstrap-datepicker.js"></script>
<style type="text/css" src="/assets/css/datepicker.css"></style>
<script src="/assets/lib/bufferview.js"></script>
<script src="/assets/lib/jquery.pin.js"></script>
<title>秒杀排期</title>
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
                <li class="active">秒杀排期</li>
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
                    <li class="tool pull-right">
                        <a id="showSchedule" href="javascript:;"  data-toggle="modal" data-target="scheduleModal">添加</a>
                        
                    </li>
                    <li class="tool pull-right">
                        <a id="showCancelSchedule" href="javascript:;"  data-toggle="modal" data-target="cancelScheduleModal" style="background-color: #f0ad4e !important;border-color: #eea236;">退回</a>
                    </li>
                    <li class="tool pull-right">
                        <a id="search" href="javascript:;" aria-controls="settings">查看</a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
    <div  class="row">
        <div class="col-md-12">
            <h4 id="tool-tip-count" style="color:#fd6699">商品数目:<?php echo $_smarty_tpl->tpl_vars['count']->value;?>
</h4>
            <p>提示：先点击选定, 然后保存将添加到对应日期的时间段</p>
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
>
              <div style="line-height:24px;height: 30px; border-bottom: solid 1px #ccc; margin-bottom:4px;background-color:#ddd;" class="col-md-12">
                  <label style="width: 100%;"><input type="checkbox" class="listSelect"> 选择</label>
              </div>
                <div class="img">
                    <a target="_blank" href="http://www.meilishuo.com/share/item/<?php echo $_smarty_tpl->tpl_vars['item']->value['tid'];?>
">
                        <img data-src="holder.js/100%x200" alt="100%x200" src="<?php echo Yii::app()->image->getWebsiteImageUrl($_smarty_tpl->tpl_vars['item']->value['img']);?>
" data-holder-rendered="true" style="height: 95%; width: 100%; display: block;">
                    </a>
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
                    <p class="rec_sku">
                        <span class="price_red"><?php echo $_smarty_tpl->tpl_vars['item']->value['price'];?>
</span>
                        <span class="price"><?php echo $_smarty_tpl->tpl_vars['item']->value['origin'];?>
</span>
                        <span class="price_red pull-right"><?php echo $_smarty_tpl->tpl_vars['item']->value['rate']*10;?>
折</span>
                    </p>
                </div>
            </div>
        </div>
        <?php } ?>
    </div>
</div>
<!-- Modal -->
<div class="modal fade" id="scheduleModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="passModalLabel">秒杀排期</h4>
      </div>
      <div class="modal-body modalCon" style="height:170px;">
          <div class="form-group" style="line-height:30px;">
              <label class="col-md-12 control-label">已选择 <span class="selected-count" style="color:red;">0</span> 个商品</label>
              <label class="col-md-2 control-label">排期时间</label>
              <div class="col-md-4">
                  <input readonly class="form-control" name="start_time"  type="text" value="<?php echo $_smarty_tpl->tpl_vars['date']->value;?>
" >
              </div>
              <label class="col-md-2 control-label">排期时间</label>
              <div class="col-md-4">
                  <input readonly class="form-control" name="end_time"  type="text" value="">
              </div>
          </div>
          <label class="col-md-12 control-label">&nbsp;</label>
          <div class="form-group" style="line-height:30px;">
             <label class="col-md-2 control-label">库存限制</label>
             <div class="col-md-4">
                 <input type="text" class="form-control" name="repertory" value="100" id="scheduleRepertory">
             </div>
             <label class="col-md-2 control-label">商品标签</label>
             <div class="col-md-4">
                <select style="height:34px;" name="zdq_type" class="form-control">
                  <?php echo smarty_function_html_options(array('options'=>SupriseManager::$zdqTypeInfo),$_smarty_tpl);?>

                </select>
             </div>
         </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">关闭</button>
        <button type="button" class="btn btn-primary" id="schedule">保存</button>
      </div>
    </div>
  </div>
</div>

<!-- Modal -->
<div class="modal fade" id="cancelScheduleModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="failModalLabel">全部退回</h4>
      </div>
      <div class="modal-body modalCon">
        <label class="col-md-12 control-label" style="padding-left: 0px;">已选择 <span class="selected-count" style="color:red;">0</span> 个商品</label>
        <?php  $_smarty_tpl->tpl_vars['item'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['item']->_loop = false;
 $_smarty_tpl->tpl_vars['key'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['fail_reason']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['item']->key => $_smarty_tpl->tpl_vars['item']->value) {
$_smarty_tpl->tpl_vars['item']->_loop = true;
 $_smarty_tpl->tpl_vars['key']->value = $_smarty_tpl->tpl_vars['item']->key;
?>
        <div class="radio">
            <label>
                <input type="radio" name="failRadios" value="<?php echo $_smarty_tpl->tpl_vars['item']->value['content'];?>
">
                <?php echo $_smarty_tpl->tpl_vars['item']->value['content'];?>

            </label>
        </div>
        <?php } ?>
        <textarea id="failRadiosReason" class="form-control" rows="3"></textarea>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">关闭</button>
        <button id="cancelSchedule" type="button" class="btn btn-primary">保存</button>
      </div>
    </div>
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

    $('#search').click(function(e){
        e.preventDefault();
        var url = '/suprise/schedule?'
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
</script>
<script src="/assets/js/suprise/suprise.js"></script>
<?php echo $_smarty_tpl->getSubTemplate ("layouts/footer.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, null, array(), 0);?>
<?php }} ?>
