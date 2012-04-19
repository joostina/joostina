$(document).ready(function() {

    $("pre").snippet("php",{style:"zellner"});

    $('#coder_form input').live('click', function() {

        var $hhhh = $('#coder_form').serialize();

        $.ajax({
            url:"ajax.index.php?option=coder",
            type:"POST",
            dataType:'json',
            data: $hhhh,
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
            url:"ajax.index.php?option=coder&task=generate_code",
            type:"POST",
            cache:false,
            data:$('#componenter_form').serialize(),
            success:function(html) {
                $("#componenter_results").html(html);
            }
        });

    });


    $('#create_component_files').live('click', function() {

        $.ajax({
            url:"ajax.index.php?option=coder&task=generate_files",
            type:"POST",
            cache:false,
            data:$('#componenter_form').serialize(),
            success:function(html) {
                $("#componenter_results").html(html);
            }
        });

    });

});