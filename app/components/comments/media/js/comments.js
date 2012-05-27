/**
 * Javascript обработчик фронтальной части компонента комментариев
 */
$(document).ready(function(){

    // сохранение комментария
    $('.comment_button').live('click', function(){
                
        var parent_id = $('#parent_id').val();
        
        $.ajax({
            url: "/ajax.index.php",
            type: 'post',
            data:{
                obj_option: $('#obj_option').val(),
                obj_id: $('#obj_id').val(),
                task : 'add_comment',
                option: 'comments',
                comment_text: $('#comment').val(),
                parent_id: parent_id
            },
            dataType: 'json',
            success: function( data ){
                if(!data){
                    alert('error');
                }else if(data.error){
                    alert('error');
                }else{
                    alert('ok');
                }
                return false;
            }
        });
        
        return false;
    })

    // ответ на комментарий
    $('.comment-reply-link').live('click', function(){
        var $current = $(this);
        
        $(this).after($('#comments-form'));
        
        var current_comment_id = $current.data('comment-id');
        $('#parent_id').val( current_comment_id );

        return false;
    });
});
