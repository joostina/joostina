// JS функции панели управления
$(document).ready(function() {

	$('.js-search-by-field').on('click', function(){
		$(this).parents('.search-by-field_state1').hide();
		$(this).parents('th').find('.search-by-field_state2').show();
	})


	// скрываем индиктор загрузки
	$('#ajax_status').hide();



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

	/*Автоматический вывод ошибок аяксовых запросов*/
	$(document).ajaxComplete(function(evt, request, settings){
		var data =  request.responseText;
		var _is_json = 0;

		if(settings.dataType == 'json'){
			_is_json = 1;
			data = $.parseJSON( data );
		}

		if (request.status == 500 || ( _is_json && (data.code!==undefined && data.code == 500)) ) {
			joosNotify(data.message, 'error');
			return;
		}

		if (request.status == 404 || ( _is_json && (data.code!==undefined && data.code == 500)) ) {
			joosNotify(data.message, 'error');
			return;
		}
		if (data !== null && data.success !== undefined) {
			if (data.success !== null && data.message!==null && data.message!==''  ) {
				joosNotify(data.message, ( data.success == false ? 'error' : 'success' ) );
				return;
			}
		}
	});

});



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

	var noty_id = noty({
		text: $message,
		theme: 'noty_theme_twitter',
		type: $type,
		layout: 'topRight'
	});

//alert( $type + ': ' + $message);
}