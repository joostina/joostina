// JS функции панели управления
$(document).ready(function() {
	// скрываем индиктор загрузки
	$('#ajax_status').hide();

	// клики на ячейки и значки смены статуса
	$('.adminlist .td-state').live('click', function(){
		// объект по которому производится клик
		var current_obj = $(this);
		var option = $('img',this).attr('obj_option') ? $('img',this).attr('obj_option') : _option;
		$.ajax({
			url: 'ajax.index.php?option='+option,
			type: 'post',
			data:{
				obj_id:       $('img',this).attr('obj_id'),
				task:    $('img',this).attr('obj_task')
			},
			dataType: 'json',
			// обрабатываем результат
			success: function( data ){
				$( 'img' ,current_obj ).attr('src',image_path + data.image );
				$( 'img' ,current_obj ).attr('alt',data.mess );
				$( 'img' ,current_obj ).attr('title',data.mess );
			}
		});
	} );

	// все формы прогоняем через валидатор
	$('#adminForm .required').length ? $("#adminForm").validate() : null;

});

/**
* Включение - выключение визуального редактора
*/
function jtoggle_editor(){
	var jeimage = $('#jtoggle_editor');
	jeimage.attr('src','images/aload.gif');

	$.ajax({
		url: 'ajax.index.php?option=com_admin&task=toggle_editor',
		dataType: 'json',
		// обрабатываем результат
		success: function( data ){
			jeimage.attr('src', image_path + data.image ).attr('alt', data.text );
		}
	});

	return true;
}

// TODO переписать на Jquery
function writeDynaList( selectParams, source, key, orig_key, orig_val ) {
	var html = '<select ' + selectParams + '>';
	var i = 0;
	for (x in source) {
		if (source[x][0] == key) {
			var selected = '';
			if ((orig_key == key && orig_val == source[x][1]) || (i == 0 && orig_key != key)) {
				selected = 'selected="selected"';
			}
			html += '\n<option value="'+source[x][1]+'" '+selected+'>'+source[x][2]+'</option>';
		}
		i++;
	}
	html += '\n</select>';

	document.writeln( html );
}

function changeDynaList( listname, source, key, orig_key, orig_val ) {
	var list = eval( 'document.adminForm.' + listname );
	// empty the list
	for (i in list.options.length) {
		list.options[i] = null;
	}
	i = 0;
	for (x in source) {
		if (source[x][0] == key) {
			opt = new Option();
			opt.value = source[x][1];
			opt.text = source[x][2];
			if ((orig_key == key && orig_val == opt.value) || i == 0) {
				opt.selected = true;
			}
			list.options[i++] = opt;
		}
	}
	list.length = i;
}

function submitbutton(pressbutton) {
	submitform(pressbutton);
}

function submitform(pressbutton){
	var form = $("#adminForm");
	$('input[name=task]').val( pressbutton );

	try {
		form.onsubmit();
	}
	catch(e){}
	//document.adminForm.submit();

	form.submit();
}

function listItemTask( id, task ) {
	var f = document.adminForm;
	cb = eval( 'f.' + id );
	if (cb) {
		for (i = 0; true; i++) {
			cbx = eval('f.cb'+i);
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

	if( idbox_checked ){
		allCheckboxes.removeAttr('checked');
		idbox_checked = false;
	}else{
		allCheckboxes.attr('checked', 'checked');
		idbox_checked = true;
	}

	document.adminForm.boxchecked.value = !document.adminForm.boxchecked.value;
}

// TODO переписать на Jquery и изменить логику проверки на поиск зачекенного чеккера
function isChecked(isitchecked){
	if (isitchecked == true){
		document.adminForm.boxchecked.value++;
	}else {
		document.adminForm.boxchecked.value--;
	}
}

function getSelectedValueById( srcListId ) {
	return $('#'+srcListId+' option:selected').val();
}