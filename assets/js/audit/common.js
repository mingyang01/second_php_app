$(function(){
    $(".pinned").pin({'activeClass': 'pinActive'})
    
    $(window).resize(function(e){
        $(".pinned").pin({'activeClass': 'pinActive'})
    });

    $('.picker').datepicker({
        format: "yyyy-mm-dd",
        autoclose: true
    }).on('changeDate', function(ev){
        $(this).datepicker('hide');
    });

    $('#tool-tip-count').text(
        $('[name="step"] option:selected').text() + $('[name="status"] option:selected').text() + '-'
        + $('#tool-tip-count').text() + "(" + $('[name="major"] option:selected').text() + "类目)"
    );

    /**
     * 全选
     */
    $("#checkedAll").click(function(){
      if ($(this).prop("checked") == true) {
        $(".thumbnail").addClass('selected');
        $("#selected-count,.selected-count").text($(".thumbnail[class*='selected']").length);
      } else {
        $(".thumbnail").removeClass('selected');
        $("#selected-count,.selected-count").text(0);
      }
    });
    
    // 加载更多
    var page = 1;
    $('#load').click(function(e){
        e.preventDefault();
        e.stopPropagation();
        if ($(this).hasClass("disabled")) return false;
        var thisObj = $(this);
        
        if (window.location.href.indexOf('?') != -1) {
            var url = window.location.href + "&data=1&page=" + page;
        } else {
            var url = window.location.href + "?data=1&page=" + page;
        }
        var objInHtml = thisObj.html();
        setBtnStatus(thisObj, '<img src="/assets/images/loading.gif"> 加载中...', 'disabled');
        $.get(url, {}, function(data) {
            if (data.code == 1 && data.data.total) {
                $('#box-container').append($(data.data.html)[2].innerHTML)
                page +=1;
                // 临时方案, 解决工具条固定问题
                setInterval('$(".pinned").pin({"activeClass": "pinActive"})', 1000)
            }
            setBtnStatus(thisObj, objInHtml, 'succ');
            loadSameInfo();
        }, 'json');
    });

    $('#box-container').on('click', '.thumbnail', function(){
        $(this).toggleClass('selected');
        $('#selected-count').text($('.selected').length);
        $('.selected-count').text($('.selected').length);
    });
    
    // 阻止thumbnail中的a标签冒泡
    $('#box-container').on('click', '.thumbnail a', function(e){
      e.stopPropagation();
    });

    $('[name="passRadios"]').change(function(e){
        $('#passRadiosReason').val(this.value.trim())
    });

    $('[name="failRadios"]').change(function(e){
        $('#failRadiosReason').val(this.value.trim())
    });
    
    /**
     * 查看历史记录
     */
    $("#box-container").on("click", ".showHistoryBtn,.showAddressBtn,.ComparePrice", function(e){
      // 阻止冒泡
      e.preventDefault();
      e.stopPropagation();
      if ($(this).hasClass("disabled")) return false;
      var thisObj = $(this);
      
      /**
       * 如果已经家再过直接显示，如果未加载过先去ajax加载
       */
      if (thisObj.hasClass("hasShow")) {
        thisObj.popover('hide');
        thisObj.removeClass("hasShow");
      } else {
        if(thisObj.attr("data-content")) {
          thisObj.popover('show');
          thisObj.addClass("hasShow");
        } else {
          var gid = thisObj.attr('data-gid');
          if (!gid) return false;
          var objInHtml = thisObj.html();
          setBtnStatus(thisObj, objInHtml, 'disabled');
          if (thisObj.hasClass('showHistoryBtn')) {
            var postUrl = '/goods/getAuditHistory?gid='+gid;
          } else if(thisObj.hasClass('ComparePrice')){
            var postUrl = '/goods/ComparePrice?gid='+gid;
          }else
          {
            var postUrl = '/goods/getAuditAddress?gid='+gid;
          }
          $.post(postUrl, {'gid':gid}, function(json){
            if (json.succ == 1) {
              setBtnStatus(thisObj, objInHtml, 'succ');
              console.log(json.data);
              thisObj.attr("data-content", json.data);
              thisObj.popover("show", {'html':true} );
              thisObj.addClass("hasShow");
            } else {
              alert('遇到服务器错误~');
              setBtnStatus(thisObj, objInHtml, 'succ');
            }
          },'json').error(function(code, data){
            alert('遇到服务器错误');
            setBtnStatus(thisObj, objInHtml, 'succ');
          });
        }
      }
    }).on("mouseleave", ".historyCon,.addressCon", function(e){
      $(this).find("button").popover('hide');
      $(this).find("button").removeClass("hasShow");
    });
    //获取比价信息
    loadSameInfo();
    function loadSameInfo()
    {
        var step = $('.thumbnail').attr('data-step');
        if(step!=2) return;
        $('.thumbnail').each(function(){
            var _this = $(this);
            if(_this.attr('data-flag')=='true')
            {
                return;
            }
            else
            {
                _this.attr('data-flag','true');
                var twid = _this.attr('data-twitter');
                $.get('/goods/SysComparePriceInfo',{tid:twid},function(json){
                    if(json)
                    {
                        var flag = json.flag;
                        var data = json.data;
                        if(flag == '1')
                        {
                            var nowPrice = _this.find('.price_red').html();
                            _this.find('.ComparePrice').show('fast');
                            if(parseFloat(nowPrice) > parseFloat(data.price))
                            {
                                _this.find('.sameInfo').html('<span style="color:red;">(否)</span>');
                            }
                            else
                            {
                                _this.find('.sameInfo').html('<span style="color:red;">(是)</span>');
                            }
                        }
                        else
                        {
                            _this.find('.sameInfo').html('<span style="color:red;">(是)</span>');
                        }
                    }
                },'json')
            }
        })
    }

})