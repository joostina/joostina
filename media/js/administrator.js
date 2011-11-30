// JS функции панели управления
$(document).ready(function() {
	// скрываем индиктор загрузки
	$('#ajax_status').hide();

	// клики на ячейки и значки смены статуса
	$('.adminlist .td-state').live('click', function() {
		// объект по которому производится клик
		var current_obj = $(this);
		var option = $('img', this).prop('obj_option') ? $('img', this).prop('obj_option') : _option;
		$.ajax({
			url:'ajax.index.php?option=' + option,
			type:'post',
			data:{
				obj_id:$('img', this).prop('obj_id'),
				task:$('img', this).prop('obj_task')
			},
			dataType:'json',
			// обрабатываем результат
			success:function(data) {
				$('img', current_obj).prop('src', image_path + data.image);
				$('img', current_obj).prop('alt', data.mess);
				$('img', current_obj).prop('title', data.mess);
			}
		});
	});

	$(".quickicon").tipTip({
		attribute:'alt'
	});

	// все формы прогоняем через валидатор
	$('#adminForm .required').length ? $("#adminForm").validate() : null;

	// постраничная навигация умеет работать через клавиатуру
	$(document).bind('keydown', 'ctrl+right', function() {
		$('a#pagenav_next').length ? $('#pagenav_next').trigger('click') : null;
	});

	$(document).bind('keydown', 'ctrl+left', function() {
		$('a#pagenav_prev').length ? $('#pagenav_prev').trigger('click') : null;
	});

	$(document).ajaxComplete(function(evt, request, settings){

		if (request.status== 500) {
			joosNotify(request.message, 'error-500');
			return;
		}

		if (request.status==404 ) {
			joosNotify(request.message, 'error-404');
			return;
		}

		var data = jQuery.parseJSON( request.responseText );

		if( data !== null && data.success !== undefined){

			if ( data.success !== null &&  data.success == false) {
				joosNotify(request.message, 'error-success');
				return;
			}
		}

	});

});

function submitbutton(pressbutton) {
	submitform(pressbutton);
}

function submitform(pressbutton) {
	var form = $("#adminForm");
	$('input[name=task]').val(pressbutton);

	try {
		form.onsubmit();
	}
	catch (e) {
	}
	//document.adminForm.submit();

	form.submit();
}

function listItemTask(id, task) {
	var f = document.adminForm;
	cb = eval('f.' + id);
	if (cb) {
		for (i = 0; true; i++) {
			cbx = eval('f.cb' + i);
			if (!cbx) break;
			cbx.checked = false;
		} // for
		cb.checked = true;
		f.boxchecked.value = 1;
		submitbutton(task);
	}
	return false;
}

var idbox_checked = false;
function checkAll() {
	var allCheckboxes = $("input[boxtype=idbox]");

	if (idbox_checked) {
		allCheckboxes.removeAttr('checked');
		idbox_checked = false;
	} else {
		allCheckboxes.prop('checked', true);
		idbox_checked = true;
	}

	document.adminForm.boxchecked.value = !document.adminForm.boxchecked.value;
}

// TODO переписать на Jquery и изменить логику проверки на поиск зачекенного чеккера
function isChecked(isitchecked) {
	if (isitchecked == true) {
		document.adminForm.boxchecked.value++;
	} else {
		document.adminForm.boxchecked.value--;
	}
}

function getSelectedValueById(srcListId) {
	return $('#' + srcListId + ' option:selected').val();
}


/**
 * Функция обработки уведомления пользователя для панели управления
 */
function joosNotify($message, $type) {
	alert( $type + ': ' + $message);
}