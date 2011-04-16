$(document).ready(function() {

    $('.comment_reply').live('click', function() {
        //$('#first_comment_wrap').replaceWith('');
        $(this).after($('#first_comment_wrap'));
        $('#parent_id').val($(this)[0].href.split('#')[1]);

        return false;
    });

    function load_comments(into) {
        $.get(_live_site + "/ajax.index.php", {
            option: "com_comments",
            task: "comments_first_load",
            limit: _comments_limit,
            display: _comments_display,
            obj_option: _comments_objoption,
            obj_id: _comments_objid,
            into: into
        },
                function(data) {
                    $('.' + into + '').html(data);
                });
    }

    load_comments('comments');

    $('.comment_button').live('click', function() {

        $.ajax({
            url: _live_site + "/ajax.index.php",
            type: 'post',
            data:{
                obj_option: _comments_objoption,
                obj_id: _comments_objid,
                task : 'add_comment',
                option: 'com_comments',
                comment_text: $('#comment_input').val(),
                parent_id: $('#parent_id').val()
            },
            dataType: 'json',
            success: function(data) {
                if (!data) {
                    $.notifyBar({
                        cls: "error",
                        html: "Что-то пошло не так( Попробуйте оставить комментарий чуть позже"
                    });
                    return false;
                } else if (data.error) {
                    $.notifyBar({
                        cls: "error",
                        html: data.error
                    });
                    return false;
                }
                else {
                    load_comments('comments');
                }
            }
        });

        return false;
    })

    $('.comments_del').live('click', function() {
        if (!confirm('Правда-правда?')) {
            return false;
        }
        $.get(
                _live_site + "/ajax.index.php?option=com_comments&task=del_comment&id=" + (this).href.split('#')[1],
                function(data) {
                    if (!data) {
                        $.prompt('Что-то пошло не так( Попробуйте еще раз');
                        return false;
                    }
                    else if (data.error) {
                        $.prompt(data.error);
                        return false;
                    }
                    else {
                        load_comments('comments');
                    }
                },
                'json'
                );
        return false;
    })
});



