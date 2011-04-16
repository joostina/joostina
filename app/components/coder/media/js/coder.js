$(document).ready(function() {

    $('#coder_form input').live('click', function() {
        $.ajax({
            url: "ajax.index.php?option=coder",
            type: "POST",
            cache: false,
            data: $('#coder_form').serialize() ,
            success: function(html) {
                $("#coder_results").html(html);
            }
        });

    });

    // выделение всего текста при клике на текст модели
    $('.coder_model_area').live('click', function() {
        $(this).select();
    });

    $('#faker_form input').live('click', function() {
        //alert( $(this).val() );

        $.ajax({
            url: "ajax.index.php?option=coder&task=table_select",
            type: "POST",
            cache: false,
            data: {
                table: $(this).val()
            },
            success: function(html) {
                $("#faker_results").html(html);
            }
        });

    });


    $('#create_component').live('click', function() {

        $.ajax({
            url: "ajax.index.php?option=coder&task=generate_code",
            type: "POST",
            cache: false,
            data: $('#componenter_form').serialize() ,
            success: function(html) {
                $("#componenter_results").html(html);
            }
        });

    });


    $('#create_component_files').live('click', function() {

        $.ajax({
            url: "ajax.index.php?option=coder&task=generate_files",
            type: "POST",
            cache: false,
            data: $('#componenter_form').serialize() ,
            success: function(html) {
                $("#componenter_results").html(html);
            }
        });

    });

});