$(document).ready(function(){
    // клики на ячуйки и значки смены статуса
    $('.adminlist .td-state-joiadmin').live('click', function(){
        // объект по которому производится клик
        var current_obj = $(this);

        $.ajax({
            url: 'ajax.index.php?option='+_option+'&task=statuschanger',
            type: 'post',
            data:{
                obj_id:       $('img',this).attr('obj_id'),
                obj_key:    $('img',this).attr('obj_key'),
                obj_name: $('#adminForm [name=obj_name]').val()
            },
            dataType: 'json',
            success: function( data ){
                $( 'img' ,current_obj ).attr('src',image_path + data.image );
                $( 'img' ,current_obj ).attr('alt',image_path + data.mess );
            }
        });
    } )
});