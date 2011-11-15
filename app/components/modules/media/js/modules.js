$(document).ready(function() {


    //Копирование позиции
    var for_copy = $('.fields:first');
    $('.module_page_copy').live('click', function() {

        //Сколько наборов полей сейчас присутствует в форме
        var _count = $('#fields_count').val();

        //Номер набора полей, выступающего доннором
        var _curr_number = for_copy.prop('title');
        //Порядковый номер, который нужно будет присвоить клону
        var _new_number = Number(_count);

        //создали клона
        var new_fieldset = for_copy.clone();

        //корректируем
        new_fieldset.prop("title", _new_number);
        new_fieldset.find('input[name="pages[' + _curr_number + '][controller]"]').prop('name', 'pages[' + _new_number + '][controller]').prop('value', '');
        new_fieldset.find('input[name="pages[' + _curr_number + '][method]"]').prop('name', 'pages[' + _new_number + '][method]').prop('value', '');
        new_fieldset.find('input[name="pages[' + _curr_number + '][rule]"]').prop('name', 'pages[' + _new_number + '][rule]').prop('value', '');

        //Выводим
        $('#modules_pages').append(new_fieldset);

        //наращиваем счетчик полей
        $('#fields_count').val(Number(_count) + 1);
    })

    //Удаление позиции
    $('.module_page_del').live('click', function() {
        var _count = $('#modules_pages').children('div.fields').length;
        console.log(_count);
        if (_count > 1) {
            $(this).parent().parent().remove();
            //уменьшаем значение  счетчика полей
            $('#fields_count').val(Number(_count) - 1);
        }
    });


});	