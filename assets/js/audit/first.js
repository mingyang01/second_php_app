$(function(){
    $('#pass').click(function(e){
        e.stopPropagation()
        $selected = $('.selected')
        if ($selected.length == 0) {
            alert('请先点击选定,然后再批量操作!');
        } else {
            $('#passModelTitle').text('批量通过' + $selected.length + '个商品');
            // @FIXME 兼容排期页面被选中的多少个商品
            $('#passModal').find(".selected-count").text($selected.length);
            $('#passModal').modal('show');
        }
    });

    $('#refuse').click(function(e){
        e.stopPropagation()
        $selected = $('.selected')
        if ($selected.length == 0) {
            alert('请先点击选定,然后再批量操作!');
        } else {
            $('#failModelTitle').text('批量退回' + $selected.length + '个商品')
            $('#failModal').modal('show')
        }
    });

    $('#submit-pass').click(function(e) {
        var ids = [], comment, shops = []
        $selected.each(function(k, v) {
            ids.push($(v).data('gid'))
            shops.push($(v).data('shop'))
        })
        console.log(ids);
        comment = $('#passRadiosReason').val()
        var url = '/audit/saveAudit'
        $.post(url, {'shops': shops, 'checkResult': window.checkResult, "comment": comment, "ids": ids}, function(data){
            console.log(data)
            if (data.code == 1) {
                $('#passModal').modal('hide')
                window.location.reload()  
            };

        }, 'json')
    });

    $('#submit-fail').click(function(e) {
        var ids = [], comment, shops = []
        $selected.each(function(k, v) {
            ids.push($(v).data('gid'))
            shops.push($(v).data('shop'))
        })
        console.log(ids);
        comment = $('#failRadiosReason').val()
        var url = '/audit/saveAudit'
        $.post(url, {'shops': shops, 'checkResult': 21, "comment": comment, "ids": ids}, function(data){
            console.log(data)

            if (data.code == 1) {
                $('#failModal').modal('hide')
                window.location.reload() 
            };

        }, 'json')
    });
    
    $("#box-container").on("click", ".changePriceBtn", function(e){
      var thisObj = $(this);
      var goodsObj = thisObj.closest(".thumbnail");
      $('#changePriceModal').find(".selectedTitle").html(goodsObj.find(".thumbnailTitle").html());
      $('#changePriceModal').find(".selectedTwitter").html(goodsObj.attr("data-twitter"));
      $('#changePriceModal').find(".selectedShop").val(goodsObj.attr("data-shop"));
      $('#changePriceModal').find(".selectedGid").val(goodsObj.attr("data-gid"));
      $('#changePriceModal').modal('show');
    });
    
    $("#saveChangePriceReason").click(function(e){
      if ($(this).hasClass("disabled")) return false;
      var thisObj = $(this);
      var priceBoxObj = thisObj.closest("#changePriceModal");
      var gid=[],shop=[],comment;
      gid.push(priceBoxObj.find(".selectedGid").val());
      shop.push(priceBoxObj.find(".selectedShop").val());
      comment = priceBoxObj.find(".changePriceReasonPrev").text() + priceBoxObj.find(".changePriceReason").val() + priceBoxObj.find(".changePriceReasonNext").text();
      
      if (gid.length < 1 || shop.length < 1) {
        alert('商品不存在');
        return false;
      }
      
      var objInHtml = thisObj.html();
      setBtnStatus(thisObj, '提交中...', 'disabled');
      var url = '/audit/saveAudit';
      $.post(url, {'shops': shop, 'checkResult': 20, "comment": comment, "ids": gid}, function(data){
          if (data.code == 1) {
            setBtnStatus(thisObj, objInHtml, 'succ');
            priceBoxObj.find(".changePriceReason").val("");
            $('#changePriceModal').modal('hide');
            $(".thumbnail[data-gid='"+gid[0]+"']").parent().fadeOut(function(){
              $(this).remove();
            });
          } else {
             alert(json.data);
             setBtnStatus(thisObj, objInHtml, 'succ');
          }
      }, 'json').error(function(code,data){
          alert('系统出错了~');
          setBtnStatus(thisObj, objInHtml, 'succ');
      });
      
    })
    
})