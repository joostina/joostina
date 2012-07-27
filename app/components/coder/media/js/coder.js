$(document).ready(function() {

    $("pre").snippet("php",{style:"zellner"});

    $('#coder_form input').live('click', function() {

        var $coder_data = $('#coder_form').serialize();

        $.ajax({
            url:"ajax.index.php?option=coder",
            type:"POST",
            dataType:'json',
            data: $coder_data,
            success:function(data) {

                $("#coder_results_site").html(data.body_site);
                $("#coder_results_admin").html(data.body_admin);
                
                $("pre").snippet("php",{
                    style:"ide-codewarrior", clipboard:"/media/js/jquery.plugins/syntax/ZeroClipboard.swf"
                });

            }
        });

    });

    $('#faker_form input').live('click', function() {

        $.ajax({
            url:"ajax.index.php?option=coder&task=table_select",
            type:"POST",
            cache:false,
            data:{
                table:$(this).val()
            },
            success:function(html) {
                $("#faker_results").html(html);
            }
        });

    });


    $('#create_component').live('click', function() {

        $.ajax({
            url:"ajax.index.php?option=coder&task=codegenerator",
            type:"POST",
            dataType:'json',
            cache:false,
            data:$('#componenter_form').serialize(),
            success:function(html) {
                $("#componenter_results").html(html.body);
            }
        });

    });

    $('#create_fs').live('click', function() {

        $.ajax({
            url:"ajax.index.php?option=coder&task=filegenerator",
            type:"POST",
            dataType:'json',
            cache:false,
            data:$('#componenter_form').serialize(),
            success:function(html) {
                $("#componenter_results").html(html.body);
            }
        });

    });

});