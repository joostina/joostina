/*заготовки*/

$(document).ready(function() {

    $('#js-datepicker').datepicker();

    $('.js-login-modal-replace').on('click', function(){
        $('#modal-login_form').appendTo('#modal-output');
        $('#modal-login_form').modal()
        return false;
    })

    /*Автоматический вывод ошибок аяксовых запросов*/
    $(document).ajaxComplete(function(evt, request, settings){
        var data =  request.responseText;
        var _is_json = 0;

        if(settings.dataType == 'json'){
            _is_json = 1;
            data = $.parseJSON( data );
        }

        if (request.status == 500 || ( _is_json && (data.code!==undefined && data.code == 500)) ) {
            joosNotify(data.message, 'error');
            return;
        }

        if (request.status == 404 || ( _is_json && (data.code!==undefined && data.code == 500)) ) {
            joosNotify(data.message, 'error');
            return;
        }
        if (data !== null && data.success !== undefined) {
            if (data.success !== null && data.success == false) {
                joosNotify(data.message, 'success');
                return;
            }
        }
    });
    
});


/**
 * Функция обработки уведомления пользователя для панели управления
 */
function joosNotify($message, $type) {

    var noty_id = noty({
        text: $message,
        theme: 'noty_theme_twitter',
        type: $type,
        layout: 'topRight'
    });

    //alert( $type + ': ' + $message);
}