$('#add_pic').live('click', function() {
    var _obj = $(this);
    var counter = Number($('#img_counter').val());

    $.ajax({
        url: 'ajax.index.php?option=categories&task=add_pic',
        type: 'post',
        data:{
            id: $('input[name=id]').val(),
            counter: counter,
            obj_name: $('input[name=model]').val()
        },
        dataType: 'json',
        success: function(data) {
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
    var img_id = _obj.attr('rel');

    $('#' + img_id + '_id').remove();
    $('#' + img_id + '_path').remove();

    $('#' + img_id + '_controls').empty().html('Будет удалено');

    return false;
})