<?php /* Smarty version Smarty-3.1.18, created on 2015-11-05 15:10:52
         compiled from "/home/work/websites/tuan/protected/views/event/eventList.html" */ ?>
<?php /*%%SmartyHeaderCode:76287628955cdc08ba24341-69347463%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '667fa7763d116bad649839434fe52deb5f02b21d' => 
    array (
      0 => '/home/work/websites/tuan/protected/views/event/eventList.html',
      1 => 1446699906,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '76287628955cdc08ba24341-69347463',
  'function' => 
  array (
  ),
  'version' => 'Smarty-3.1.18',
  'unifunc' => 'content_55cdc08bb65a35_95726830',
  'variables' => 
  array (
    'channel' => 0,
    'eventData' => 0,
    'searchFilter' => 0,
    'eventDeleteType' => 0,
    'eventType' => 0,
    'k' => 0,
    'v' => 0,
  ),
  'has_nocache_code' => false,
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_55cdc08bb65a35_95726830')) {function content_55cdc08bb65a35_95726830($_smarty_tpl) {?><?php if (!is_callable('smarty_function_html_options')) include '/home/work/framework/extensions/Smarty/plugins/function.html_options.php';
?><?php echo $_smarty_tpl->getSubTemplate ("layouts/header.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, null, array(), 0);?>

<style>
.container{
  /*font-size:12px;*/
}
</style>
<div class="container">
  <div class="row">
    <div class="col-md-12">
      <ol class="breadcrumb">
          <li><a href="/">Home</a></li>
          <li><a href="/event?channel=<?php echo $_smarty_tpl->tpl_vars['channel']->value;?>
"><?php if ($_smarty_tpl->tpl_vars['channel']->value) {?><?php echo EventManager::$channelMap[$_smarty_tpl->tpl_vars['channel']->value];?>
<?php } else { ?>活动<?php }?></a></li>
          <li class="active"><?php echo $_smarty_tpl->tpl_vars['eventData']->value['event_type_detail'];?>
</li>
      </ol>
      <?php if ($_smarty_tpl->tpl_vars['eventData']->value['event_declare_detail']) {?><div class="alert alert-danger" role="alert"><?php echo $_smarty_tpl->tpl_vars['eventData']->value['event_declare_detail'];?>
</div><?php }?>
      <div id="well" class="well">
        <form class="form-inline" role="form" id="form" action="/event" method="get">
          <div class="form-group">
            <label>活动名称：</label>
            <input type="text" class="form-control" name="event_name" value="<?php echo $_smarty_tpl->tpl_vars['searchFilter']->value['event_name'];?>
" placeholder="活动名称">
          </div>
          <div class="form-group">
            <label>活动标题：</label>
            <input type="text" class="form-control" name="title" value="<?php echo $_smarty_tpl->tpl_vars['searchFilter']->value['title'];?>
" placeholder="活动标题">
          </div>
          <div  class="form-group">
            <label>开始时间：</label>
            <input class="form-control date datepicker" name="start_time"  type="text" value="<?php echo $_smarty_tpl->tpl_vars['searchFilter']->value['start_time'];?>
"  data-date-format="yyyy-mm-dd">
          </div>
          <div  class="form-group">
            <input class="form-control date datepicker" name="end_time" type="text" value="<?php echo $_smarty_tpl->tpl_vars['searchFilter']->value['end_time'];?>
" data-date-format="yyyy-mm-dd">
          </div>
          <br/><br/>
          <div class="form-group">
            <label>状&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;态：</label>
            <select class="form-control" name="show_deletes_type" style="width:175px">
              <?php echo smarty_function_html_options(array('options'=>$_smarty_tpl->tpl_vars['eventDeleteType']->value,'selected'=>((string)$_smarty_tpl->tpl_vars['searchFilter']->value['show_deletes_type'])),$_smarty_tpl);?>

            </select>
          </div>
          <div class="form-group">
            <label>是否结束：</label>
            <select class="form-control" name="is_show_end" style="width:120px">
              <option value="">正在进行</option>
              <option value="1" <?php if ($_smarty_tpl->tpl_vars['searchFilter']->value['is_show_end']==1) {?>selected<?php }?>>显示全部</option>
            </select>
          </div>
          <input type="hidden" name="show_type" value="<?php echo $_smarty_tpl->tpl_vars['searchFilter']->value['show_type'];?>
">
          <input type="hidden" name="channel" value="<?php echo $_smarty_tpl->tpl_vars['channel']->value;?>
">
          <div class="form-group">
            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            <button id="submit" class="btn btn-default">查看</button>
          </div>
        </form>
      </div>
    </div>
  </div>

  <div class="row">
    <div class="col-md-12">
      <div role="tabpanel">
        <ul class="nav nav-tabs" role="tablist">
          <li role="presentation" <?php if (!$_smarty_tpl->tpl_vars['searchFilter']->value['show_type']||!array_key_exists($_smarty_tpl->tpl_vars['searchFilter']->value['show_type'],$_smarty_tpl->tpl_vars['eventType']->value)) {?>class="active"<?php }?>><a href="/event?channel=<?php echo $_smarty_tpl->tpl_vars['channel']->value;?>
" >全部</a></li>
          <?php  $_smarty_tpl->tpl_vars['v'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['v']->_loop = false;
 $_smarty_tpl->tpl_vars['k'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['eventType']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['v']->key => $_smarty_tpl->tpl_vars['v']->value) {
$_smarty_tpl->tpl_vars['v']->_loop = true;
 $_smarty_tpl->tpl_vars['k']->value = $_smarty_tpl->tpl_vars['v']->key;
?>
            <li role="presentation" <?php if ($_smarty_tpl->tpl_vars['searchFilter']->value['show_type']==$_smarty_tpl->tpl_vars['k']->value) {?>class="active"<?php }?>>
              <a href="/event?show_type=<?php echo $_smarty_tpl->tpl_vars['k']->value;?>
&channel=<?php echo $_smarty_tpl->tpl_vars['channel']->value;?>
" ><?php echo $_smarty_tpl->tpl_vars['v']->value;?>
</a>
            </li>
          <?php } ?>
          <li style="float:right" class="active"><a class="btn btn-default btn-ms" href="/event/addEvent">创建活动</a></li>
        </ul>
      </div>
    </div>
  </div>
  
  <div class="row" style="margin-top:5px;">
  <div class="dataBox col-md-12">
    <table class="table table-striped table-striped center">
      <thead>
        <tr>
          
          <th class="text-center">活动ID</th>
          <th class="text-center">活动名称</th>
          <th class="text-center">活动标题</th>
          <th class="text-center" style="width:75px;">类型</th>
          
          <th class="text-center">开始时间</th>
          <th class="text-center">结束时间</th>
          <th>操作</th>
        </tr>
      </thead>
      <tbody>
        <?php  $_smarty_tpl->tpl_vars['v'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['v']->_loop = false;
 $_smarty_tpl->tpl_vars['k'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['eventData']->value['result']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['v']->key => $_smarty_tpl->tpl_vars['v']->value) {
$_smarty_tpl->tpl_vars['v']->_loop = true;
 $_smarty_tpl->tpl_vars['k']->value = $_smarty_tpl->tpl_vars['v']->key;
?>
          <tr id="dataList<?php echo $_smarty_tpl->tpl_vars['v']->value['event_id'];?>
">
            
            <td <?php if ($_smarty_tpl->tpl_vars['v']->value['event_status']=='ok') {?>style="background:#5cb85c;"<?php } else { ?>style="background:#d9534f;"<?php }?>><?php echo $_smarty_tpl->tpl_vars['v']->value['event_id'];?>
</td>
            <td><?php echo $_smarty_tpl->tpl_vars['v']->value['event_name'];?>
</td>
            <td><?php echo $_smarty_tpl->tpl_vars['v']->value['title'];?>
</td>
            <td><?php echo $_smarty_tpl->tpl_vars['v']->value['event_type'];?>
</td>
            
            <td><?php echo date("Y-m-d H:i",$_smarty_tpl->tpl_vars['v']->value['start_time']);?>
</td>
            <td><?php echo date("Y-m-d H:i",$_smarty_tpl->tpl_vars['v']->value['end_time']);?>
</td>
            <td class="txtcenter" style="width:190px;"> 
            <!-- Single button -->
            <div class="btn-group">
              <button type="button" class="btn btn-info btn-xs dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                操作 <span class="caret"></span>
              </button>
              <ul class="dropdown-menu" role="menu">
                <?php if (!$_smarty_tpl->tpl_vars['searchFilter']->value['show_deletes_type']) {?>
                  
                  <?php if ($_smarty_tpl->tpl_vars['v']->value['event_id']==1065) {?>
                    <li role="presentation"><a target="_blank" tabindex="-1" href="http://works.meiliworks.com/tuanht/edit_event?event_id=<?php echo $_smarty_tpl->tpl_vars['v']->value['event_id'];?>
">修改</a></li>
                  <?php } else { ?>
                    <li role="presentation"><a role="menuitem" href="/event/editBasicInfo?event_id=<?php echo $_smarty_tpl->tpl_vars['v']->value['event_id'];?>
">修改</a></li>
                  <?php }?>
                  
                  <li role="presentation"><a role="menuitem" href="/eventRule/editEventRule?event_id=<?php echo $_smarty_tpl->tpl_vars['v']->value['event_id'];?>
">修改活动规则</a></li>
                  
                  <?php if ($_smarty_tpl->tpl_vars['v']->value['status']<20) {?>
                    <li role="presentation"><a target="_blank"  tabindex="-1"href="http://works.meiliworks.com/tuanht/activity_goods?act_id=<?php echo $_smarty_tpl->tpl_vars['v']->value['event_id'];?>
">排期主题活动</a></li>
                  <?php }?>
                  <li role="presentation"><a tabindex="-1" href="/event/editEvent?event_id=<?php echo $_smarty_tpl->tpl_vars['v']->value['event_id'];?>
">增删商品</a></li>
                  <li role="presentation" class="divider"></li>
                  <?php if ($_smarty_tpl->tpl_vars['v']->value['status']<20) {?>
                    <li role="presentation"><a target="_blank" tabindex="-1" href="http://mapp.meilishuo.com/activity/tuan/hd/<?php echo $_smarty_tpl->tpl_vars['v']->value['event_id'];?>
">MOB</a></li>
                    <li role="presentation"><a target="_blank" tabindex="-1" href="http://www.meilishuo.com/activity/tuan/tg512/?event_id=<?php echo $_smarty_tpl->tpl_vars['v']->value['event_id'];?>
">PC</a></li>
                  <?php }?>
                  <?php if ($_smarty_tpl->tpl_vars['v']->value['status']>=20&&$_smarty_tpl->tpl_vars['v']->value['status']<30) {?>
                    <li role="presentation"><a target="_blank" tabindex="-1" href="http://mapp.meilishuo.com/tuan/list/<?php echo $_smarty_tpl->tpl_vars['v']->value['event_id'];?>
">MOB</a></li>
                    <li role="presentation"><a target="_blank" tabindex="-1" href="http://www.meilishuo.com/tuan/list/<?php echo $_smarty_tpl->tpl_vars['v']->value['event_id'];?>
">PC</a></li>
                  <?php }?>
                  <?php if (empty($_smarty_tpl->tpl_vars['v']->value['notice_id'])) {?>
                    <li class="notice_new"><a href="/event/editNotice?event_id=<?php echo $_smarty_tpl->tpl_vars['v']->value['event_id'];?>
">创建公告</a></li>
                  <?php } else { ?>
                    <li class="notice_edit"><a href="/event/editNotice?event_id=<?php echo $_smarty_tpl->tpl_vars['v']->value['event_id'];?>
">编辑公告</a></li>
                  <?php }?>
                  <li class=""  value="<?php echo $_smarty_tpl->tpl_vars['v']->value['event_id'];?>
"><a class="deleteOne" href="/event/deleteEvent?event_id=<?php echo $_smarty_tpl->tpl_vars['v']->value['event_id'];?>
" >删除活动</a></li>
                <?php }?>
                <?php if ($_smarty_tpl->tpl_vars['searchFilter']->value['show_deletes_type']==1) {?>
                  
                <?php }?>
              </ul>
            </div>
            <div class="btn-group">
              <button type="button" class="btn btn-danger btn-xs dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                审核 <span class="caret"></span>
              </button>
              <ul class="dropdown-menu" role="menu">
              <?php if ($_smarty_tpl->tpl_vars['v']->value['status']>=80&&$_smarty_tpl->tpl_vars['v']->value['status']<90) {?>
              	   <li role="presentation"><a target="_blank" href="/qingcang/qfirst/list?event=<?php echo $_smarty_tpl->tpl_vars['v']->value['event_id'];?>
&realStatus=10&channel=<?php echo $_smarty_tpl->tpl_vars['v']->value['channel'];?>
&business_type=<?php echo $_smarty_tpl->tpl_vars['v']->value['business_type'];?>
" >初审</a></li>
                   <li role="presentation"><a target="_blank" href="/qingcang/qsecond/list?event=<?php echo $_smarty_tpl->tpl_vars['v']->value['event_id'];?>
&realStatus=20&channel=<?php echo $_smarty_tpl->tpl_vars['v']->value['channel'];?>
&business_type=<?php echo $_smarty_tpl->tpl_vars['v']->value['business_type'];?>
" >复审</a></li>
              <?php } else { ?>
                <?php if (!$_smarty_tpl->tpl_vars['searchFilter']->value['show_deletes_type']) {?>
                   <li role="presentation"><a target="_blank" href="/audit/first?type=1&event=<?php echo $_smarty_tpl->tpl_vars['v']->value['event_id'];?>
&business=3&channel=<?php echo $_smarty_tpl->tpl_vars['v']->value['channel'];?>
&business_type=<?php echo $_smarty_tpl->tpl_vars['v']->value['business_type'];?>
" >初审</a></li>
                   <li role="presentation"><a target="_blank" href="/audit/second?type=1&event=<?php echo $_smarty_tpl->tpl_vars['v']->value['event_id'];?>
&business=3&channel=<?php echo $_smarty_tpl->tpl_vars['v']->value['channel'];?>
&business_type=<?php echo $_smarty_tpl->tpl_vars['v']->value['business_type'];?>
" >复审</a></li>
                   <li role="presentation"><a target="_blank" href="/audit/sample?type=1&event=<?php echo $_smarty_tpl->tpl_vars['v']->value['event_id'];?>
&business=3&channel=<?php echo $_smarty_tpl->tpl_vars['v']->value['channel'];?>
&business_type=<?php echo $_smarty_tpl->tpl_vars['v']->value['business_type'];?>
" >样审</a></li>
                  <?php if ($_smarty_tpl->tpl_vars['v']->value['event_id']!=1065&&$_smarty_tpl->tpl_vars['v']->value['event_id']!=2005) {?>
                    
                  <?php }?>
                <?php }?>
             <?php }?>
              </ul>
            </div>
            <?php if ($_smarty_tpl->tpl_vars['v']->value['status']>=80&&$_smarty_tpl->tpl_vars['v']->value['status']<90) {?>
            	<a class="btn btn-success btn-xs" target="_blank" href="/qingcang/Qschedule/list?event=<?php echo $_smarty_tpl->tpl_vars['v']->value['event_id'];?>
&realStatus=40&channel=<?php echo $_smarty_tpl->tpl_vars['v']->value['channel'];?>
&business_type=<?php echo $_smarty_tpl->tpl_vars['v']->value['business_type'];?>
" >商品排期</a>
            	<a class="btn btn-success btn-xs" target="_blank" href="/qingcang/QensureSchedule/List?type=0&event=<?php echo $_smarty_tpl->tpl_vars['v']->value['event_id'];?>
&realStatus=50&channel=<?php echo $_smarty_tpl->tpl_vars['v']->value['channel'];?>
&business_type=<?php echo $_smarty_tpl->tpl_vars['v']->value['business_type'];?>
">已排期商品</a>
            <?php } elseif ($_smarty_tpl->tpl_vars['v']->value['event_id']!=1065&&$_smarty_tpl->tpl_vars['v']->value['event_id']!=2005) {?>
               <a class="btn btn-success btn-xs" target="_blank" href="/eventGoods/scheduleGoods?event_id=<?php echo $_smarty_tpl->tpl_vars['v']->value['event_id'];?>
&channel=<?php echo $_smarty_tpl->tpl_vars['v']->value['channel'];?>
&business_type=<?php echo $_smarty_tpl->tpl_vars['v']->value['business_type'];?>
" >商品排期</a>
            <?php }?>
            </td>
          </tr>
        <?php }
if (!$_smarty_tpl->tpl_vars['v']->_loop) {
?>
          <tr><td colspan="13">暂无相关信息</td></tr>
        <?php } ?>
      </tbody>
    </table>
    <?php echo $_smarty_tpl->getSubTemplate ("layouts/pager.html", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, null, array(), 0);?>

  </div>
  </div>
</div>
<script>
$(function(){
  $('.datepicker').datepicker({
      format: "yyyy-mm-dd",
      autoclose: true
  }).on('changeDate', function(ev){
      $(this).datepicker('hide');
  });
});
</script>
<?php echo $_smarty_tpl->getSubTemplate ("layouts/footer.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, null, array(), 0);?>
<?php }} ?>
