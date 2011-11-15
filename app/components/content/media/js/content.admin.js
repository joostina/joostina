//Генератор ссылки для статьи
$('#content_slug_generator').live('click', function() {
    // объект по которому производится клик
    var _obj = $(this);

    $.ajax({
        url:'ajax.index.php?option=content&task=slug_generator',
        type:'post',
        data:{
            id:_obj.prop('obj_id'),
            obj_name:$('input[name=model]').val(),
            title:$('#title').val(),
            cat_name:$('#name').val(),
            cat_id:$('#category_id').val(),
        },
        dataType:'json',
        success:function(data) {
            if (data.error) {
                alert(data.error);
                return;
            }
            $('#slug').val(data.slug);
        }
    });
});

$('#add_pic').live('click', function() {
    var _obj = $(this);
    var counter = Number($('#img_counter').val());

    $.ajax({
        url:'ajax.index.php?option=content&task=add_pic',
        type:'post',
        data:{
            id:$('input[name=id]').val(),
            counter:counter,
            obj_name:$('input[name=model]').val(),
            cat_id:$('#category_id').val(),
        },
        dataType:'json',
        success:function(data) {
            if (data.error) {
                alert(data.error);
                return;
            }
            $('#content_uploader_area').append(data.content);
            eval(data.js);

            counter = counter + 1;
            $('#img_counter').val(counter);
        }
    });

    return false;

})

$('.content_delete_image').live('click', function() {
    var _obj = $(this);
    var img_id = _obj.prop('rel');

    $('#' + img_id + '_id').remove();
    $('#' + img_id + '_path').remove();

    $('#' + img_id + '_controls').empty().html('Будет удалено');

    return false;
})