$(function(){
    $('#pass').click(function(e){
        e.stopPropagation()
        $selected = $('.selected')
        if ($selected.length == 0) {
            alert('请先点击选定,然后再批量操作!');
        } else {
            $('#passModelTitle').text('批量通过' + $selected.length + '个商品')
            $('#passModal').modal('show')
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
        $.post(url, {'shops': shops, 'checkResult': 40, "comment": comment, "ids": ids}, function(data){
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
        $.post(url, {'shops': shops, 'checkResult': 41, "comment": comment, "ids": ids}, function(data){
            console.log(data)

            if (data.code == 1) {
                $('#failModal').modal('hide')
                window.location.reload() 
            };

        }, 'json')
    });
})