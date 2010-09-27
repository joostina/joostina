$(document).ready(function(){
    $('.to_bookmarks').live('click', function(){

        if( !_current_uid ){
            $.notifyBar({
                cls: "error",
                html: 'О как, авторизоваться забыл'
            });
            return false;
        }

        var obj = $(this);
        $.ajax({
            url: _live_site + "/ajax.index.php",
            type: 'post',
            data:{
                obj_option: $(this).attr('obj_option'),
                obj_id: $(this).attr('obj_id'),
                task : 'add',
                option: 'com_bookmarks'
            },
            dataType: 'json',
            success: function( data ){
                if(!data){
                    $.notifyBar({
                        cls: "error",
                        html: "Что-то пошло не так, совсем не так"
                    });
                    return false;
                }else if(data.error){
                    $.notifyBar({
                        cls: "error",
                        html: data.error
                    });
                    return false;
                }
                else{
                    $.notifyBar({
                        cls: "success",
                        html: data.message
                    });
                    obj.children('small').html( '(' + data.count + ')' ); 
                    
					if(data.task=='unactive'){
						obj.removeClass('active');
						
						if(data.label){
							obj.text(data.label); 	
						}
							
					} 
					else{
						obj.addClass('active');
						if(data.label){
							obj.text(data.label); 	
						}	
					}                  

                    
                    //var _new_count = Number($('.user_panel_fav span').text()) + 1;
                    $('.'+data.option+' span').text(' '+data.current_count);
                }
            }
        });

        return false;
    })
})