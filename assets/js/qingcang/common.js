$(function(){
	
	$(function(){
		  // 导出
		  $("#exportBtn").click(function(e){
		    e.stopPropagation
		    e.preventDefault();
		    var excelStr = window.location.href.indexOf('?') == -1 ? '?' : '&';
		    window.open(window.location.href + excelStr + 'excel=1');
		  });
		});
	
	  $('body').on('focus', '.myDatePicker',function(e){
		    WdatePicker({
		    	dateFmt:"yyyy-MM-dd HH:mm:ss",  
		    	realDateFmt:"yyyy-MM-dd HH:mm:ss",  
		    	realTimeFmt:"HH:mm:ss HH:mm:ss",
		        minDate:'%y-%M-%{%d}',
		        maxDate:'%y-%M-%{%d+30}'
		    });
		  });
	 
	  
	$(".pinned").pin({'activeClass': 'pinActive'})
    
    $(window).resize(function(e){
        $(".pinned").pin({'activeClass': 'pinActive'})
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
    
  //店铺选中
	$('#box-container').on('click', '.thumbnail-shop .btn', function(e){
        e.stopPropagation();
      });
    
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
    
    $('#tool-tip-count').text(
            $('[name="step"] option:selected').text() + $('[name="status"] option:selected').text() + '-'
            + $('#tool-tip-count').text() + "(" + $('[name="major"] option:selected').text() + "类目)"
        );
    
    $('[name="passRadios"]').change(function(e){
        $('#passRadiosReason').val(this.value.trim())
    });

    $('[name="failRadios"]').change(function(e){
        $('#failRadiosReason').val(this.value.trim())
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
                $('#box-container').append($(data.data.html)[0].innerHTML)
                page +=1;
                // 临时方案, 解决工具条固定问题
                setInterval('$(".pinned").pin({"activeClass": "pinActive"})', 1000)
            }
            setBtnStatus(thisObj, objInHtml, 'succ');
        }, 'json');
    });

})