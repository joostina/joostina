/*заготовки. Будет безжалостно переписано*/

$(document).ready(function() {

    $('.js-select').on('change', function(){
        group_select_toggler()
    })

    $('.js-select_all').on('change', function(){
        if($(this).is(':checked')){
            $('.js-select').attr('checked', 'checked');
        }
        else{
            $('.js-select').removeAttr('checked');
        }
        group_select_toggler()
    })

    function group_select_toggler(){
        if($('.js-select').is(':checked')){
            $('.js-btn-group-for_select li').removeClass('disabled');
        }
        else{
            $('.js-btn-group-for_select li').addClass('disabled');
        }
    }


    $('.js-search-by-field').on('click', function(){
        $(this).parents('.search-by-field_state1').hide();
        $(this).parents('th').find('.search-by-field_state2').show();
    })

});