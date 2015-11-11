// chao
// 本周精选
$(function() {
    $(".pinned").pin({'activeClass': 'pinActive'})
    $('.picker').datepicker({
        format: "yyyy-mm-dd",
        autoclose: true
    }).on('changeDate', function(ev){
        $(this).datepicker('hide');
    });

    if ($('#type').val() == 1) {
        $( "#box-contailer" ).sortable();
        $( "#box-contailer" ).disableSelection();
    }
    
    $(".selecteBox").click(function(e){
      $(this).closest(".thumbnail").toggleClass("selected")
    });

    //选定，变色
    $(".toDoingClassPro").on('click',function(){
        var myBox = $(this).parent();
        if($(myBox).css('background-color') == 'rgb(204, 254, 224)'){
            $(myBox).attr('class','recommendMyBox');
            $(myBox).css('background-color','#ffffff');
            $(myBox).css("border-color","#cccccc");
        }else{
            $(myBox).attr('class','recommendMyBoxSelected');
            $(myBox).css("border-color","#267226");
            $(myBox).css('background','#CCFEE0');
        }
    });

});