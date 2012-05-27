// JS функции системной корзины
$(document).ready(function() {
    
    // клик по кнопке добавления в корзину
    $('.joosbasket').click(function(){
       var $current = $(this);
        
        alert( $current.data('obj') );

        $.ajax({
            url: "/ajax.index.php",
            type: 'post',
            data:{
                option: 'site',
                task : 'add_to_basket',
                obj: $current.data('obj'),
                obj_id: $current.data('obj-id')
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
        
    });
});