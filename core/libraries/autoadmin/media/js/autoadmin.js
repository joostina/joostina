$(document).ready(function() {

    // клики на ячуйки и значки смены статуса
    $('.adminlist .td-state-joiadmin').live('click', function() {
        // объект по которому производится клик
        var current_obj = $(this);

        $.ajax({
            url:'ajax.index.php?option=' + _option + '&task=status_change',
            type:'post',
            data:{
                obj_id:$('img', this).data('obj-id'),
                obj_key:$('img', this).data('obj-key'),
                obj_name:$('input[name=obj_name]').val()
            },
            dataType:'json',
            success:function(data) {
                if (data.code == 500) {
                    joosNotify(data.message, 'error');
                    return;
                }
                $('img', current_obj).prop('src', image_path + data.image);
                $('img', current_obj).prop('alt', image_path + data.mess);
            }
        });
    });

/*
    //Сортировка
    $('.edit_ordering000').editable('ajax.index.php?option=' + _option + '&task=ordering', {
        id:'elementid',
        name:'newvalue'
    });


    $('.edit_ordering').editable(function(value, settings) {

        console.log(this);
        console.log(value);
        console.log(settings);

        var _obj = $(this);
        //var action = _obj.prop('href').split('#')[1];
        var scope = _obj.prop('rel');
        $.ajax({
            url:'ajax.index.php?option=' + _option + '&task=ordering',
            type:'post',
            data:{
                obj_id:_obj.prop('obj_id'),
                obj_name:$('input[name=obj_name]').val(),
                //action: action,
                scope:scope,
                val:value
            },
            dataType:'json',
            // обрабатываем результат
            success:function(data) {
                //alert('111');
            }
        });
        return value;


    }, {
        style:"inherit"

    });


    $('.edit_module_position').editable(function(value, settings) {
        console.log(this);
        console.log(value);
        console.log(settings);

        var _obj = $(this);
        //var action = _obj.prop('href').split('#')[1];
        var scope = _obj.prop('rel');
        $.ajax({
            url:'ajax.index.php?option=modules&task=save_position',
            type:'post',
            data:{
                obj_id:_obj.prop('obj_id'),
                val:value
            },
            dataType:'json',
            // обрабатываем результат
            success:function(data) {
                //alert('111');
            }
        });
        return value;


    }, {
        loadurl:'ajax.index.php?option=modules&task=get_positions',
        type:'select'


    });


    $(".drag").tableDnD({
        dragHandle:'ordering',
        onDrop:function(table, row) {
            var rows = table.tBodies[0].rows;
            var debugStr = new Array();

            var ids = new Array();

            for (var i = 0; i < rows.length; i++) {
                if ($(rows[i]).prop('rel') == $(row).prop('rel')) {
                    debugStr.push($(rows[i]).prop('obj_id') + ":" + $(rows[i]).prop('obj_ordering') + ":" + i);
                    ids.push($(rows[i]).prop('obj_id'));
                }
            }

            console.log(debugStr);

            $.ajax({
                url:'ajax.index.php?option=' + _option + '&task=reorder',
                type:'post',
                data:{
                    objs:debugStr,
                    obj_name:$('input[name=obj_name]').val()
                },
                dataType:'json',
                // обрабатываем результат
                success:function(data) {
                    //console.log(data.mess);

                    //for(var i=0; i<ids.length; i++) {
                    //var o = Number( i +  Number(data.min));
                    //$('#adminlist-row-' + ids[i] + ' span.order').text(o);
                    //}


                }
            });

            //console.log(rows);

        }

    });

    /*	$('.order_this').live('click', function(){
     var _obj = $(this);
     var action = _obj.prop('href').split('#')[1];
     var scope = _obj.prop('rel');
     $.ajax({
     url: 'ajax.index.php?option='+_option+'&task=ordering',
     type: 'post',
     data:{
     obj_id: _obj.prop('obj_id'),
     obj_name: $('input[name=obj_name]').val(),
     action: action,
     scope: scope
     },
     dataType: 'json',
     // обрабатываем результат
     success: function( data ){
     //alert('111');
     }
     });
     return false;
     });	*/


    $('.filter_elements').live('change', function() {
        var $current = $(this);
        $('input[name=' + $current.prop('obj_name') + ']').val($current.val());
        $('#adminForm').submit();
        return false;
    });

    $('#search_elements').keyup(function(event) {
        if (event.keyCode == '13') {
            var $current = $(this);
            $('input[name=search]').val($current.val());
            $('#adminForm').submit();
        }
        return false;
    });

    $('#search_elements').dblclick(function() {
        $('input[name=search]').val('');
        $('#adminForm').submit();
    });

    //Генератор ссылки для категории
    $('#category_slug_generator').live('click', function() {

        // объект по которому производится клик
        var _obj = $(this);

        $.ajax({
            url:'ajax.index.php?option=categories&task=slug_generator',
            type:'post',
            data:{
                cat_id:_obj.prop('obj_id'),
                cat_name:$('#name').val(),
                parent_id:$('#category_id').val(),
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


    //табы-табы-табы
    $('#tabs_list li:first').addClass('g-active');
    $('#tabs_list li span').click(function() {

        var _el = $(this);
        var _target = _el.prop('rel');

        if (!_el.hasClass('g-active')) {
            $('.tab_area').hide();
            $('#' + _target).show();

            $('#tabs_list li').removeClass('g-active');
            _el.parent().addClass('g-active');
        }


    })

});