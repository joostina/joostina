/*заготовки*/

$(document).ready(function() {

    $('#js-datepicker').datepicker();

    $('.js-login-modal-replace').on('click', function(){
        $('#modal-login_form').appendTo('#modal-output');
        $('#modal-login_form').modal()
        return false;
    })

});