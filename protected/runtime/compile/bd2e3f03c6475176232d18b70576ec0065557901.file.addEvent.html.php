<?php /* Smarty version Smarty-3.1.18, created on 2015-09-21 18:15:39
         compiled from "/home/work/websites/tuan/protected/views/event/addEvent.html" */ ?>
<?php /*%%SmartyHeaderCode:173911927955dc1d974cbf04-84348108%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'bd2e3f03c6475176232d18b70576ec0065557901' => 
    array (
      0 => '/home/work/websites/tuan/protected/views/event/addEvent.html',
      1 => 1440560805,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '173911927955dc1d974cbf04-84348108',
  'function' => 
  array (
  ),
  'version' => 'Smarty-3.1.18',
  'unifunc' => 'content_55dc1d9762d162_89866796',
  'variables' => 
  array (
    'tipInfo' => 0,
    'channel' => 0,
    'scoreEnum' => 0,
  ),
  'has_nocache_code' => false,
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_55dc1d9762d162_89866796')) {function content_55dc1d9762d162_89866796($_smarty_tpl) {?><?php if (!is_callable('smarty_function_html_options')) include '/home/work/framework/extensions/Smarty/plugins/function.html_options.php';
if (!is_callable('smarty_function_html_checkboxes')) include '/home/work/framework/extensions/Smarty/plugins/function.html_checkboxes.php';
?><?php echo $_smarty_tpl->getSubTemplate ("layouts/header.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, null, array(), 0);?>

<title>添加活动</title>
<script src="/assets/lib/My97DatePicker/WdatePicker.js"></script>

<div class="container">
  <div class="row">
    <div class="col-md-12">
      <ol class="breadcrumb">
          <li><a href="/">Home</a></li>
          <li><a href="/event">主题活动</a></li>
          <li class="active">添加活动</li>
      </ol>
    </div>
  </div>

  <div class="row">
    <div class="col-md-12">
      <form id="createForm" class="form-inline" enctype="multipart/form-data"  action="/event/saveAddEvent" method="post">
        
        <input type="hidden" name="id" value="<?php echo $_smarty_tpl->tpl_vars['tipInfo']->value['id'];?>
">
        
        <!-- 活动名称 -->
        <div class="control-group">
          <blockquote><p>活动名称<small>必选</small></p></blockquote>
          <input type="text" class="require input-xlarge form-control" name="event_name" id="event_name">
          <span class="help-inline"></span>
        </div>
        
        <!-- 开始时间 -->
        <div class="control-group">
          <blockquote><p>开始时间 - 结束时间<small>必选</small></p></blockquote>
          <input type="text" class="require input-medium form-control myDatePicker" name="start_time" id="start_time"> - 
          <input type="text" class="require input-medium form-control myDatePicker" name="end_time" id="end_time">
          <span class="help-inline"></span>
        </div>
        <!-- 时间  -->
        <div class="control-group">
          <blockquote><p>预热时间<small>可选-如果不填默认是开始时间的前一天</small></p></blockquote>
          <input type="text" class="require input-medium form-control myDatePicker" name="preheat_time" id="preheat_time">
          <span class="help-inline"></span>
        </div>
        
        <!-- 所属频道 -->
        <div class="control-group">
          <blockquote><p>活动所属频道<small>必选，<span style="color:red;">注意:选在哪个频道将会在商家后台的哪个频道中展示</span></small></p></blockquote>
          <?php if ($_smarty_tpl->tpl_vars['channel']->value&&array_key_exists($_smarty_tpl->tpl_vars['channel']->value,EventManager::$channelMap)) {?>
            <input type="text" class="input-medium form-control" readonly value="<?php echo EventManager::$channelMap[$_smarty_tpl->tpl_vars['channel']->value];?>
">
            <input type="hidden" value="<?php echo $_smarty_tpl->tpl_vars['channel']->value;?>
" name="channel">
          <?php } else { ?>
            <select class="require input-medium form-control" name="channel" id="channel">
             <?php echo smarty_function_html_options(array('options'=>EventManager::$channelMap),$_smarty_tpl);?>

            </select>
          <?php }?>
          <span class="help-inline"></span>
        </div>
        
        <!-- 活动类型 -->
        <div class="control-group">
          <blockquote><p>活动类型<small>必选</small></p></blockquote>
          <select class="require input-medium form-control" name="tuan_event_type" id="tuan_event_type">
           <?php echo smarty_function_html_options(array('options'=>EventManager::$evnetTypeEnum),$_smarty_tpl);?>

          </select>
          <span class="help-inline"></span>
        </div>
        
        <!-- 定向报名 -->
        <div class="control-group">
          <blockquote><p>定向报名<small>设置报名要求：批量输入店铺ID，定向邀约商家</small></p></blockquote>
          <textarea class="require input-xxlarge form-control" name="shop_ids" id="shop_ids" style="height:100px;"><?php echo $_smarty_tpl->tpl_vars['tipInfo']->value['content'];?>
</textarea>
          <span class="help-inline"></span>
        </div>
        
        <!-- 定向报名 -->
        <div class="control-group">
          <blockquote><p>品类定向报名<small>选填</small></p></blockquote>
          <?php echo smarty_function_html_checkboxes(array('name'=>"product_type",'options'=>EventManager::$evnetCateEnum,'separator'=>"&nbsp;&nbsp;"),$_smarty_tpl);?>

          <span class="help-inline"></span>
        </div>
        
        <!-- 商家评分 -->
        <div class="control-group">
          <blockquote><p>商家评分<small>必选</small></p></blockquote>
          <select class="require input-medium form-control" name="shop_score" id="shop_score">
           <?php echo smarty_function_html_options(array('values'=>$_smarty_tpl->tpl_vars['scoreEnum']->value,'output'=>$_smarty_tpl->tpl_vars['scoreEnum']->value),$_smarty_tpl);?>

          </select>
          <span class="help-inline"></span>
        </div>
        
        <!-- 商品评分 -->
        <div class="control-group">
          <blockquote><p>商品评分<small>必选</small></p></blockquote>
          <input type="text" class="require input-xlarge form-control" name="product_score" id="product_score">
          <span class="help-inline"></span>
        </div>
        
        <!-- 产品销量需求 -->
        <div class="control-group">
          <blockquote><p>产品销量需求<small>必选</small></p></blockquote>
          <input type="text" class="require input-xlarge form-control" name="product_num" id="product_num">
          <span class="help-inline"></span>
        </div>
        
        <!-- 产品库存需求 -->
        <div class="control-group">
          <blockquote><p>产品库存需求<small>必选</small></p></blockquote>
          <input type="text" class="require input-xlarge form-control" name="repertory_limit" id="repertory_limit">
          <span class="help-inline"></span>
        </div>
        
        <!-- 商品必须有晒单 -->
        <div class="control-group">
          <blockquote><p>商品必须有晒单<small>数量自选限制</small></p></blockquote>
          <input type="text" class="require input-xlarge form-control" name="product_pic" id="product_pic">
          <span class="help-inline"></span>
        </div>
        
        <!-- 价格区间 -->
        <div class="control-group">
          <blockquote><p>价格区间<small>选填</small></p></blockquote>
          <input type="text" class="require input-medium form-control" name="price_range_1" id="price_range_1"> - 
          <input type="text" class="require input-medium form-control" name="price_range_2" id="price_range_2">
          <span class="help-inline"></span>
        </div>
        
        <!-- 折扣区间 -->
        <div class="control-group">
          <blockquote><p>折扣区间<small>选填</small></p></blockquote>
          <input type="text" class="require input-medium form-control" name="discount_range_1" id="discount_range_1"> - 
          <input type="text" class="require input-medium form-control" name="discount_range_2" id="discount_range_2">
          <span class="help-inline"></span>
        </div>
        
        <!-- 内容 -->
        <div class="control-group">
          <p></p>
          <input type="submit" class="btn btn-primary Sub createBtn" value="提交">
        </div>
      </form>
    </div>
  </div>
  

</div>
<script>
$(function(){
    /** 选择时间 */
    $('.myDatePicker').on('focus',function(){
      WdatePicker({
          dateFmt:'yyyy-MM-dd HH:mm'
      });
    });
});


$(function(){
  var isEdit = false;
  // 表单提交
  $('#createForm').submit(function(e){
    e.preventDefault();
    
    var eventNameObj        = $('#createForm input[name="event_name"]');
    var startTimeObj        = $('#createForm input[name="start_time"]');
    var endTimeObj          = $('#createForm input[name="end_time"]');
    var tuanEventTypeObj    = $('#createForm select[name="tuan_event_type"]');
    var shopIdsObj          = $('#createForm textarea[name="shop_ids"]');
    var shopScoreeObj       = $('#createForm select[name="shop_score"]');
    var productScoreObj     = $('#createForm input[name="product_score"]');
    var productNumObj       = $('#createForm input[name="product_num"]');
    var repertoryLimitObj   = $('#createForm input[name="repertory_limit"]');
    var productPicObj       = $('#createForm input[name="product_pic"]');
    var priceRange1Obj      = $('#createForm input[name="price_range_1"]');
    var priceRange2Obj      = $('#createForm input[name="price_range_2"]');
    var discountRange1Obj   = $('#createForm input[name="discount_range_1"]');
    var discountRange2Obj   = $('#createForm input[name="discount_range_2"]');

    
    if ($('.createBtn').hasClass('disabled')) {
      alert('请稍等，正在保存');
      return false;
    }
    
    if (!$.trim(eventNameObj.val())) {
        showError(eventNameObj, '请填写标题');
        return false;
    }
    
    var timeRe = /^[0-9]{4}-[0-9]{2}-[0-9]{2} [0-9]{2}:[0-9]{2}$/;
    if (!timeRe.test($.trim(startTimeObj.val()))) {
      showError(startTimeObj, '请选择正确的时间');
      return false;
    }
    if (!timeRe.test($.trim(endTimeObj.val()))) {
        showError(endTimeObj, '请选择正确的时间');
        return false;
    }
    
    if (!$.trim(tuanEventTypeObj.val())) {
        showError(tuanEventTypeObj, '请选择活动类型');
        return false;
    }
    
    if (!$.trim(shopScoreeObj.val())) {
        showError(shopScoreeObj, '请选择商家评分');
        return false;
    }
    
    var price_range_1    = priceRange1Obj.val();
    var price_range_2    = priceRange2Obj.val();
    var discount_range_1 = discountRange1Obj.val();
    var discount_range_2 = discountRange2Obj.val();
    
    if((price_range_1 && price_range_2 && price_range_1 == price_range_2)){
        showError(priceRange1Obj, '价格区间不能相同!!中不中!!');
        return false;
    }
    if((discount_range_1 && discount_range_2 && discount_range_1 == discount_range_2)){
        showError(discountRange1Obj, '折扣区间不能相同!!中不中!!');
        return false;
    }
    
    if((discount_range_1 && discount_range_1 > 1) || (discount_range_2 && discount_range_2 >1) || (discount_range_1 && discount_range_1 <= 0) || (discount_range_2 && discount_range_2 <= 0)){
        showError(discountRange1Obj, '折扣不能大于1也不能为0!!中不中!!');
        return false;
    }

    if((price_range_1 && price_range_1<=0) ||(price_range_2 && price_range_2<=0)){
        showError(priceRange1Obj, '价格不能小于0!!中不中!!');
        return false;
    }
    
    if((price_range_1 && isNaN(price_range_1)) ||(price_range_2 && isNaN(price_range_2))){
        showError(priceRange1Obj, '价格区间请输入数字!!中不中!!');
        return false;
    }
    if((discount_range_1 && isNaN(discount_range_1)) ||(discount_range_2 && isNaN(discount_range_2))){
        showError(discountRange1Obj, '折扣区间请输入数字!!中不中!!');
        return false;
    }
    
    $('.createBtn').addClass('disabled');
    $('#createForm').unbind().submit();
  });
});

/**
 * 显示错误信息
 */
function showError(obj, msg)
{
  if (obj.length) {
    var parentObj = obj.closest('.control-group');
    parentObj.addClass('error').find('.help-inline').text(msg)
    //obj.siblings('.help-inline').text(msg).closest('.control-group').addClass('error');
    obj.focus();
  }
  return obj;
}
</script>
<?php echo $_smarty_tpl->getSubTemplate ("layouts/footer.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, null, array(), 0);?>
<?php }} ?>
