/**
 *  人工干预团购js
 */
$(function(){
    /** 商品列表点击事件️ */
    $(".twitterList").live('click', function(){
        var thisObj = $(this);
        var id = thisObj.attr("data-Id");
        
        // 判断是否已推荐
        if (thisObj.hasClass("hasRecommend")) {
            if (confirm('该商品已经推荐,确定要删除推荐吗？')) {
                $("#recommendBox").find(".recommendList"+id).remove();
                thisObj.removeClass("hasRecommend");
            };
            return false;
        }
        // 判断推荐容器中是否已存在
        if ($(".recommendList"+id).length > 0) {
            thisObj.addClass("hasRecommend");
            if (confirm('该商品已经推荐,确定要删除推荐吗？')) {
                $("#recommendBox").find(".recommendList"+id).remove();
                thisObj.removeClass("hasRecommend");
            };
            return false;
        }
        // 判断推荐位置是否已满
        if (checkRecommendBoxLength() >= 6) {
            alert('推荐位置已满!');
            return false;
        }
        
        var inHtml = '<li class="recommendList recommendList'+id+'" data-Id="'+id+'">';
            inHtml += thisObj.html();
            inHtml += '<a class="glyphicon glyphicon-trash deleteRecommend"></a>';
            inHtml += '</li>';
       // 推荐商品添加到推荐容器
       $("#recommendBox").append(inHtml);
       // 添加已推荐的类
       thisObj.addClass("hasRecommend");
    });
    
    /** 推荐商品 */
    $("#recommendBtn").click(function(){
        var thisObj = $(this);
        if (thisObj.hasClass("disabled")) return false;
        
        if (checkRecommendBoxLength() < 1) {
            alert('您还没有推荐商品哦～');
            return false;
        }
        
        // 获取被推荐都商品id
        var ids = getRecommendIdsStr();
        if (!ids || ids == "") {
            alert('请选择要推荐都商品～');
            return;
        }
        
        thisObj.addClass("disabled");
        $.post("/recommend/saveRecommend", { 'ids':ids }, function(json){
            console.log(json);
            if (json.succ == 1) {
                alert(json.msg);
                window.location.reload();
            } else {
                alert(json.msg);
            }
            thisObj.removeClass("disabled");
        }, 'json').error(function(code, info){
            console.log(code,info);
            alert(info+":服务器繁忙，请稍后重试～");
            thisObj.removeClass("disabled");
        });
    });
    
    /** 删除容器内的商品 */
    $(".deleteRecommend").live('click', function(e){
        e.preventDefault();
        var thisObj = $(this);
        if (thisObj.hasClass("disabled")) return false;
        
        var recommendObj = thisObj.closest(".recommendList");
        var id = recommendObj.attr("data-Id");
        
        // 如果数据库已存在,ajax操作删除数据库数据
        if (thisObj.hasClass("recommended")) {
            if (confirm('删除后该推荐不可恢复，确定要删除推荐吗？')) {
                thisObj.addClass("disabled");
                var twitterId = thisObj.attr("data-twitterId");
                $.post("/recommend/delete", { 'twitter_id':twitterId }, function(json){
                    console.log(json);
                    if (json.succ == 1) {
                        // 给下面列表把已推荐类删除
                        $(".twitterList"+id).removeClass('hasRecommend');
                        recommendObj.fadeOut(function(){
                            recommendObj.remove();
                        });
                    } else {
                        alert(json.msg);
                    }
                    thisObj.removeClass("disabled");
                }, 'json').error(function(code, info){
                    console.log(code,info);
                    alert(info+":服务器繁忙，请稍后重试～");
                    thisObj.removeClass("disabled");
                });
            } else {
                return false;
            }
        } else {
            // 给下面列表把已推荐类删除
            $(".twitterList"+id).removeClass('hasRecommend');
            recommendObj.fadeOut(function(){
                recommendObj.remove();
            });
        }
    });
    
    /** 图片鼠标放上去移动事件 */
    $(".s_picBox").live('mouseover', function(){
        var left      = 0;
        var top       = 0;
        var thisObj   = $(this);
        var imgWidth  = thisObj.find('img').width();
        var imgHeight = thisObj.find('img').height();
        var divWidth  = thisObj.width();
        var divHeight = thisObj.height();
        
        if (imgWidth < divWidth) {
            return false;
        }
        left = imgWidth - divWidth;
        $(this).find('img').stop().animate({marginLeft:-left}, "slow");
    }).live('mouseout', function(){
        var left      = 0;
        var top       = 0;
        var thisObj   = $(this);
        var imgWidth  = thisObj.find('img').width();
        var imgHeight = thisObj.find('img').height();
        var divWidth  = thisObj.width();
        var divHeight = thisObj.height();
        
        if (imgWidth < divWidth) {
            return false;
        }
        left = imgWidth - divWidth;
        $(this).find('img').stop().animate({marginLeft:0}, "slow");
    });
    
    
    /** 检测推荐容器里面的商品数量 */
    function checkRecommendBoxLength()
    {
        return $("#recommendBox").find("li").length;
    }
    
    /** 获取推荐容器中的id 返回对象 */
    function getRecommendIds()
    {
      var ids = [];
      $("#recommendBox").find("li").each(function(){
          var recommendId = $(this).attr("data-Id");
          if (recommendId) {
              ids.push(recommendId);
          }
      });
      return ids;
    }
    
    /** 获取推荐容器中的ID  返回字符串  "666440,677110,682352" */
    function getRecommendIdsStr()
    {
      var ids = getRecommendIds();
      if (ids) {
          return ids.join(",");
      } else {
          return "";
      }
    }
});

var _recommendOperate = function(){};
_recommendOperate = function()
{
    if (typeof(_recommendOperate) == 'object') return false;
    this.init.call(this);
}

_recommendOperate.prototype = {
    init : function()
    {
        // 初始化配置文件
        this.initConfig();
        // 分类点击事件
        this.cataAction();
        // 排序
        this.baseAction();
        // 搜索
        this.searchAction();
        
    },
    
    /** 分类点击事件 */
    cataAction : function()
    {
        var self = this;
        var clickObj = $(".cicon");
        if (clickObj.data("events") && clickObj.data("events")['click']) return false;
        
        clickObj.on("click", function(){
            var thisObj  = $(this);
            if (thisObj.hasClass("disabled")) return false;
            
            var enTitle  = thisObj.attr("data-enTitle");
            var cataId   = thisObj.attr("data-cataId");
            
            // 设置分类选中样式
            self.setCataSelected(thisObj);
            // 重置排序
            self.setSbaseSelected($($(".sbaseBtn")[0]), 0);
            // 清空搜索框
            $("#searchTwitter").val("");
            
            self.setConfig({'cata':cataId, 'sbase':0, 'sort':0, 'gids':0});
            thisObj.addClass("disabled");
            self.setTwitterHtml('<li class="msg-li">玩命加载中.....</li>');
            self.post('/recommend/getList', self.config, function(json){
                console.log(json);
                thisObj.removeClass("disabled");
                if (json.succ == 1) {
                    self.initHtml(json.twitterList);
                    $(".twitterListTotalNum").html(json.totalNum);
                } else {
                    alert(json.msg);
                }
            });
        });
    },
    
    /** 排序操作 */
    baseAction : function()
    {
        var self = this;
        var clickObj = $(".sbaseBtn");
        if (clickObj.data("events") && clickObj.data("events")['click']) return false;
        
        clickObj.on("click", function(){
            var thisObj  = $(this);
            if (thisObj.hasClass("disabled")) return false;
            
            var sbaseId = thisObj.attr("data-sbaseId");
            var sortId  = 0; // 0-降序 1-升序
            
            // 如果第一次点击默认降序操作
            // 判断升序或降序  sbase ＝ 0，1 只有升序操作
            if (thisObj.hasClass("active")) {
                if (sbaseId == 0 || sbaseId == 1) {
                    sortId = 0;
                } else if (thisObj.find(".sortType").hasClass("arrow_d")) {
                    sortId = 1;
                    //thisObj.find(".sortType").addClass("arrow_t").removeClass("arrow_d");
                } else {
                    sortId = 0;
                    //thisObj.find(".sortType").addClass("arrow_d").removeClass("arrow_t");
                }
            } else {
                sortId = 0;
            }
            //thisObj.addClass("active").siblings().find(".sortType").removeClass("arrow_t").addClass("arrow_d");
            //thisObj.addClass("active").siblings().removeClass("active");
            
            // 设置排序按钮样式
            self.setSbaseSelected(thisObj, sortId);
            
            // @FIXME 这里待确认，排序是否支持搜索排序
            self.setConfig({'sbase':sbaseId, 'sort':sortId});
            thisObj.addClass("disabled");
            self.setTwitterHtml('<li class="msg-li">玩命加载中.....</li>')
            self.post('/recommend/getList', self.config, function(json){
                console.log(json);
                thisObj.removeClass("disabled");
                if (json.succ == 1) {
                    self.initHtml(json.twitterList);
                    $(".twitterListTotalNum").html(json.totalNum);
                } else {
                    alert(json.msg);
                }
            });
        });
    },
    
    /** 搜索操作 */
    searchAction : function()
    {
        var self = this;
        var clickObj = $("#searchTwitterBtn");
        if (clickObj.data("events") && clickObj.data("events")['click']) return false;
        
        clickObj.on("click", function(){
            var thisObj  = $(this);
            if (thisObj.hasClass("disabled")) return false;
            
            var valObj = $("#searchTwitter");
            var gids   = valObj.val();
            if (!gids) return false;
            
            // 重置分类
            self.setCataSelected($($(".cicon")[0]));
            // 重置排序
            self.setSbaseSelected($($(".sbaseBtn")[0]), 0);
            // 搜索的时候重置分类和排序
            self.setConfig({'cata':0, 'sbase':0, 'sort':0, 'gids':gids});
            thisObj.addClass("disabled");
            self.setTwitterHtml('<li class="msg-li">玩命加载中.....</li>')
            self.post('/recommend/getList', self.config, function(json){
                console.log(json);
                thisObj.removeClass("disabled");
                if (json.succ == 1) {
                    self.initHtml(json.twitterList);
                    $(".twitterListTotalNum").html(json.totalNum);
                } else {
                    alert(json.msg);
                }
            });
        });
    },
    
    /** 初始化结果集为html */
    initHtml : function(dataList)
    {
        var twitterObj = $("#twitterContent");
        if (!dataList || dataList.length < 1) {
            var inHtml = '<li class="msg-li">暂无数据哦～</li>';
            twitterObj.html(inHtml);
            return false;
        }
        
        var inHtml = '';
        var twitterNum = 0;
        $.each(dataList, function(k, v){
            // 判断是否被推荐
            var hasRecommend = '';
            if ($(".recommendList"+v.id).length > 0) {
                hasRecommend = 'hasRecommend';
            }
            inHtml += '<li class="twitterList twitterList'+v.id+' '+hasRecommend+'" data-id="'+v.id+'" data-twitterid="'+v.twitter_id+'" data-goodsid="'+v.goods_id+'">';
              inHtml += '<div class="s_picBox">';
                if (twitterNum < 10) {
                    inHtml += '<img src="http://d06.res.meilishuo.net'+v.goods_image_pc+'" style="margin-left: 0px;">';
                } else {
                    inHtml += '<img class="lazy" src="/assets/images/gray.gif" data-original="http://d06.res.meilishuo.net'+v.goods_image_pc+'" style="min-height:198px; max-height:267px;">'
                }
              inHtml += '</div>';
              inHtml += '<p class="txt">'+v.goods_name+'</p>';
              inHtml += '<p class="price_box">';
                inHtml += '<span class="price_red">'+v.off_price+'</span>';
                inHtml += '<span class="price">'+v.off_num+'</span>';
              inHtml += '</p>';
              inHtml += '<p>2015-04-20 10:00</p>';
            inHtml += '</li>';
            
            twitterNum++;
        });
        twitterObj.html(inHtml);
        /** 延时加载图片 */
        $("img.lazy").lazyload({
            //effect : "fadeIn"
        });
    },
    
    /** 列表的内容 */
    setTwitterHtml : function(inHtml)
    {
        $("#twitterContent").html(inHtml);
    },
    
    /** 设置排序按钮选中样式 */
    setSbaseSelected : function(obj, sort)
    {
        var thisObj = obj;
        thisObj.addClass("active").siblings().find(".sortType").removeClass("arrow_t").addClass("arrow_d");
        thisObj.addClass("active").siblings().removeClass("active");
        if (sort == 0) {
            thisObj.find(".sortType").addClass("arrow_d").removeClass("arrow_t");
        } else {
            thisObj.find(".sortType").addClass("arrow_t").removeClass("arrow_d");
        }
    },
    
    /** 初始化分类按钮选中样式 */
    setCataSelected : function(obj)
    {
        var thisObj = obj;
        // 设置按钮样式
        var enTitle  = thisObj.attr("data-enTitle");
        var prevEnTitle = thisObj.closest("ul").find(".tab_active").find(".cicon").attr("data-enTitle");
        thisObj.closest("ul").find(".tab_active").find(".cicon").addClass(prevEnTitle).removeClass("light_"+prevEnTitle);
        thisObj.closest("li").addClass("tab_active").siblings().removeClass("tab_active");
        thisObj.addClass("light_"+enTitle).removeClass(enTitle);
    },
    
    /** 发起一个post请求 */
    post : function (url, params, callback)
    {
        var self = this;
        //var params = typeof(params) == 'object' && params != null ? params : {};
        $.post(url, params, function(json){
            if (typeof(callback) == 'function') callback(json);
        },'json').error(function(code, info){
            if (typeof(callback) == 'function') callback(error+':服务器繁忙，请稍后重试～');
        });
    },
    
    /** 设置配置文件 */
    setConfig: function(params) {
        var self = this;
        if (typeof(self.config) == 'object') {
            if (typeof(params) == 'object') {
                $.each(params, function(key, value){
                    self.config[key] = value;
                });
                return true;
            }
            return false;
        }
        return false;
    },
    
    /** 默认配置文件 */
    initConfig: function()
    {
        this.config = {
          'cata'  : 0,  // 分类
          'sbase' : 0,  // 排序
          'sort'  : 0   // 排序类型
        };
    }
}

$(function(){
    window._recommendOperate = new _recommendOperate();
});
