/**
 * 取得页面滚动的高度
 * @returns int
 */
function getScrollTop()
{
  var srollTop = 0;
  if (document.documentElement && document.documentElement.scrollTop) {
    scrollTop = document.documentElement.scrollTop;
  } else if (document.body) {
    scrollTop = document.body.scrollTop;
  }

  return scrollTop;
}

/**
 * 点击跳到顶部
 * @param obj 点击的对象
 */
function goTopEx(obj){
  if (!obj) {
    return false;
  }
  function setScrollTop(value){
      if (document.documentElement && document.documentElement.scrollTop) {
        document.documentElement.scrollTop = value;
      } else if (document.body) {
        document.body.scrollTop = value;
      }
  }
  window.onscroll=function(){
    if (getScrollTop()>600) {
      obj.style.display = "block";
      obj.style.zIndex = 1000;
    } else {
      obj.style.display = "none";
      obj.style.zIndex = 0;
    }
  }
  obj.onclick=function(){
      var goTop=setInterval(scrollMove,10);
      function scrollMove(){
        setScrollTop(getScrollTop()/1.1);
        if (getScrollTop()<1) clearInterval(goTop);
      }
  }
}

/** 设置按钮样式 */
function setBtnStatus(obj, objInHtml, type)
{
  if (type == 'disabled') {
    obj.addClass("disabled");
    obj.attr("disabled", "disabled");
  } else {
    obj.removeClass("disabled");
    obj.removeAttr("disabled");
  }
  obj.html(objInHtml);
}

/**
 * Alltosun - admin.js 后台通用JS代码
 * Copyright (c) 2009-2011 Alltosun.INC - http://www.alltosun.com
 * Date: 2011/01/06
 * @author gaojj@alltosun.com
 * @requires jQuery v1.4.4+
 * @requires jQuery-ui v1.8.7+
 */
// 删除提示信息 为了兼容原有后台程序移植过来的 将来将要被废除
var prompt = {
  'prompt': "确定要删除记录吗?",
  'nochange': "您没有要删除的记录",
  'errors': "删除失败"
};
var predefineParam;

var resType = resType || '';
var resName = resName || '';
res_name = '';
$(function(){
  //点击列表选中checkbox
  $(".dataBox table tbody  tr").click(function(e){
    var clickTarget = $(e.target);
    // 当直接点击checkbox时，不做checked的切换
    if (clickTarget.is("input.listSelect")) {
      return;
    }
    var listCheckbox = $("input.listSelect", $(this));
    if (listCheckbox.is(":disabled")) {
      return;
    }
    if (listCheckbox.attr("checked")) {
      listCheckbox.removeAttr("checked");
    } else {
      listCheckbox.attr("checked", "checked");
    }
  });

  // 全选
  $("input.selectAll").click(function(){
    console.log($(this).attr("checked"));
    if ($(this).attr("checked")) {
      $("input.selectAll, input.listSelect").not(":disabled").attr("checked", "checked");
    } else {
      $("input.selectAll, input.listSelect").not(":disabled").removeAttr("checked");
    }
  });

  //操作警告
  $(".warningAction").click(function(e){
    e.preventDefault();
    e.stopPropagation();
    if (!confirm("确定要执行该操作吗？")) {
      return false;
    }

    var clickObj = $(this);
    var url = clickObj.attr("href");
    $.post(url, {}, function(json){
      if (json.succ != 1) {
        alert(json.msg);
      } else {
        clickObj.closest("tr").fadeOut(function(){
          $(this).remove();
          interLineColor();
        });
      }
    }, 'json');
    return false;
  });

  // 单个删除
  $(".deleteOne").click(function(e){
    e.preventDefault();
    e.stopPropagation();
    if (!confirm("确定要删除该条记录吗？")) {
      return false;
    }

    var clickObj = $(this);
    var url = clickObj.attr("href");
    $.post(url, {}, function(json){
      if (json.succ != 1) {
        alert(json.msg);
      } else {
        clickObj.closest("tr").fadeOut(function(){
          $(this).remove();
          interLineColor();
        });
      }
    }, 'json');
    return false;
  });
  
  //单个还原
  $(".recoverOne").click(function(e){
    e.preventDefault();
    e.stopPropagation();
    if (!confirm("确定要还原该条记录吗？")) {
      return false;
    }

    var clickObj = $(this);
    var url = clickObj.attr("href");
    $.post(url, {}, function(json){
      if (json.succ != 1) {
        alert(json.msg);
      } else {
        clickObj.closest("tr").fadeOut(function(){
          $(this).remove();
          interLineColor();
        });
      }
    }, 'json').error(function(code,info){
        alert('服务器繁忙请稍后重试');
    });
    return false;
  });

  // 批量删除
  $(".deleteAll").click(function(e){
    e.preventDefault();
    var url = $(this).attr("href");
    var ids = getCheckedIds();
    deleteAll(url, ids);
    $("input[name=selectAll]").not(":disabled").removeAttr("checked");
    return false;
  });

  // 单个审核
  $('.checkOne').click(function(e){
      e.preventDefault();
      e.stopPropagation();
      if (!confirm("确定要通过审核吗?")) {
        return false;
      }

      var clickObj = $(this);
      var url = clickObj.attr("href");
      $.post(url, {}, function(json){
        if (json.succ != 1) {
          alert(json.msg);
        } else {
          clickObj.closest("tr").fadeOut(function(){
            $(this).remove();
            interLineColor();
          });
        }
      }, 'json');
      return false;
  })
  
  // 批量审核
  $(".checkAll").click(function(e){
    e.preventDefault();
    
    if (!confirm("确定要全部通过审核吗?")) {
        return false;
      }
    
    var ids = getCheckedIds();
    console.log(ids);
    var clickObj = $(this);
    var url = clickObj.attr("href");
    
    $.post(url, {'id':ids.join(',')}, function(json){
      if (json.succ != 1) {
        alert(json.msg);
      } else {
          $.each(ids, function(k, v){
              $("#dataList"+v).fadeOut(function(){
                $(this).remove();
              });
            });
            interLineColor();
      }
    }, 'json');
    return false;
    
    $("input[name=selectAll]").not(":disabled").removeAttr("checked");
    
    return false;
  });
  
  // 批量操作
  $(".changeAll").click(function(e){
    e.preventDefault();
    
    if (!confirm("确定要批量操作这些数据吗?")) {
        return false;
      }
    
    var ids = getCheckedIds();
    console.log(ids);
    var clickObj = $(this);
    var url = clickObj.attr("href");
    
    $.post(url, {'id':ids.join(',')}, function(json){
      if (json.succ != 1) {
        alert(json.msg);
      } else {
          $.each(ids, function(k, v){
              $("#dataList"+v).fadeOut(function(){
                $(this).remove();
              });
            });
            interLineColor();
      }
    }, 'json');
    return false;
    
    $("input[name=selectAll]").not(":disabled").removeAttr("checked");
    
    return false;
  });
  
  // 点击列表选中checkbox
  $("tbody > tr", $("#AnTable")).click(function(e){
    var clickTarget = $(e.target);
    // 当直接点击checkbox时，不做checked的切换
    if (clickTarget.is("input[name=listSelect]")) {
      return;
    }
    var listCheckbox = $("input[name=listSelect]", $(this));
    if (listCheckbox.is(":disabled")) {
      return;
    }
    if (listCheckbox.attr("checked")) {
      listCheckbox.removeAttr("checked");
    } else {
      listCheckbox.attr("checked", "checked");
    }
  });
});

/**
 * 批量删除
 * @param url
 * @return
 */
function deleteAll(url, ids){
  var idstr = ids.join(',');
  if (!idstr) {
    alert("请选择要删除的记录");
    return false;
  }
  if (!confirm("确定要删除这些记录吗？")) {
    return false;
  }
    var postData = { 'id': idstr };
    $.post(url, postData, function(json){
      if (json.succ != 1) {
        alert(json.msg);
      } else {
        $.each(ids, function(k, v){
          $("#dataList"+v).fadeOut(function(){
            $(this).remove();
          });
        });
        interLineColor();
      }
    }, 'json');
}

/**
 * 获取页面中选中的checkbox对应的ids
 * @requires checkbox上统一加name="listSelect"
 * @requires tr的class="dataList1"
 * @return Array 所有选中的id数组
 */
function getCheckedIds()
{
  var ids = [];
  $("input.listSelect:checked").not(":disabled").each(function(){
    var selectId = $(this).closest("tr").attr("id").substring(8);
    ids.push(selectId);
  });
  return ids;
}


/**
 * 为了兼容原有后台程序移植过来的 将来将要被废除
 * 获取页面中选中的checkbox的值
 * 本方法中获取页面选中的checkbox必须在checkbox上统一加class="listCheck"，并且tr的class="list_1"
 * @return Array 所有选中的id数组
 * @author gaojj@alltosun.com
 */
function getCheckedId()
{
  var id = [];
  // checkbox上统一加class="listSelect"
    $("input.listSelect:checked").each(function(){
      // tr的class="list_1"
        var selectId = $(this).closest("tr").attr("id").substring(8);        
        id.push(selectId);
    });
    return id;
}

/**
 * 表格隔行换色
 * @return
 */
function interLineColor()
{
    $("tr:odd").removeClass("even").addClass("odd");
    $("tr:even").removeClass("odd").addClass("even");
}

/**
 * 是否是中文
 */
function isChinese(str)
{
  return new RegExp("[\\u4e00-\\u9fa5]", "").test(str);
}

// 获取排序的view_order
function getViewOrder()
{
  var viewOrderArr = { };
  var list = $(".dataBox tbody tr");
  var total = list.length;
  $.each(list ,function(viewOrder, v){
    var key = $(this).attr('id').substring(8);
    if (!key) {
      return true;
    }
    viewOrderArr[key] = viewOrder + 1;
  });
  return viewOrderArr;
}

// 判断一个对象是否是同一个对象 (只判断了第一层)
function isSameObj(obj1, obj2)
{
  for(var i in obj1) {
    if (obj1[i] !== obj2[i]) {
      return false;
    }
  }
  for(var j in obj2) {
    if (obj2[j] !== obj1[j]) {
      return false;
    }
  }
  return true;
}

function autoassign(id,url){
    var cache = {};
    $( "#"+id ).autocomplete({
        minLength: 1,
        source: function( request, response ) {
            var term = request.term;
            if ( term in cache ) {
                response( cache[ term ] );
                return;
          }

        $.getJSON(url, request, function( data, status, xhr ) {
            cache[ term ] = data;
            response( data );
        });
     }
    });
}


function post_data(url,data,callback)
{
  $.post(url, data, function(json){
    if ( typeof callback == 'function' ) callback(json);
  }, 'json').error(function(code, info){
    if ( typeof callback == 'function' ) callback({'info':'服务器繁忙，请稍后再试~' ,'succ':0, 'msg':'服务器繁忙，请稍后再试~'});
  });
}

Array.prototype.in_array = function(e) 
{ 
    for(var i=0;i<this.length;i++)
    {
        if(this[i] == e)
        return true;
    }
    return false;
}