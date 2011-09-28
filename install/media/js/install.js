// JS функции процедуры установки
$(document).ready(function() {
    // проверка парамтеров подключения к БД
    $('#check_mysql_connect').live('click', function() {
        $.ajax({
            url:'installer.php',
            type:'post',
            data:{
                form_params:$('#db_data').serialize(),
                task:'check_db'
            },
            dataType:'json',
            // обрабатываем результат
            success:function(data) {
                alert(data.message);
                if (data.success == true) {
                    $('#install_sql').show();
                    $('#check_mysql_connect').hide();
                }
            }
        });
        return false;
    });

    // установка БД
    $('#install_sql').live('click', function() {
        $.ajax({
            url:'installer.php',
            type:'post',
            data:{
                form_params:$('#db_data').serialize(),
                task:'install_db'
            },
            dataType:'json',
            // обрабатываем результат
            success:function(data) {
                alert(data.message);
            }
        });
        return false;
    });

});