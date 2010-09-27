/**
* @package Joostina
* @copyright Авторские права (C) 2007-2010 Joostina team. Все права защищены.
* @license Лицензия http://www.gnu.org/licenses/gpl-2.0.htm GNU/GPL, или LICENSE.php
* Joostina! - свободное программное обеспечение распространяемое по условиям лицензии GNU/GPL
*/

function jadd(elID,value){
	SRAX.get(elID).value=value;
	return;
}

// установка куков
setCookie = function ( sName, sValue, nDays ) {
	var expires = "";
	if ( nDays ) {
		var d = new Date();
		d.setTime( d.getTime() + nDays * 24 * 60 * 60 * 1000 );
		expires = "; expires=" + d.toGMTString();
	}
	document.cookie = sName + "=" + sValue + expires + "; path=/";
};
// получение куков
getCookie = function (sName) {
	var re = new RegExp( "(\;|^)[^;]*(" + sName + ")\=([^;]*)(;|$)" );
	var res = re.exec( document.cookie );
	return res != null ? res[3] : null;
};

// прорисовка информации о успешно выполненной операции
function mess_cool(mess){
	SRAX.replaceHtml('status-info',mess);
	SRAX.get('status-info').className = 'message';
	SRAX.get('status-info').style.display = 'block';
}
// прорисовка информации о неудавно выполненной операции
function mess_bad(mess){
	SRAX.replaceHtml('status-info',mess);
	SRAX.get('status-info').className = 'jwarning';
	SRAX.get('status-info').style.display = 'block';
}

// смена статуса публикации, elID - идентификатор объекта у которого меняется статус публикации
function ch_publ(elID,option){
	$('#img-pub-'+elID).attr('src','images/aload.gif');
	$.get('ajax.index.php?option='+option+'&task=publish&id='+elID, function(data) {
		$('#img-pub-'+elID).attr('src','images/'+data);
	});
	return false;
}
// смена группы доступа, elID - идентификатор элемента у котогоменяется доступ, aCC - группа доступа
function ch_access(elID,aCC,option){
	SRAX.replaceHtml('acc-id-'+elID,'<img src="images/aload.gif" />');
	dax({
		url: 'ajax.index.php?option='+option+'&task=access&id='+elID+'&chaccess='+aCC,
		id:'acc-id-'+elID,
		callback:
		function(resp, idTread, status, ops){
			if(SRAX.debug.responseText!=2) {
				SRAX.replaceHtml('acc-id-'+elID,resp.responseText);
			}else{
				SRAX.replaceHtml('acc-id'+elID,'<img src="images/error.png" />');
			}
		}
	});
	return false;
}

// TODO переписать на Jquery
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

// TODO переписать на Jquery
function addSelectedToList( frmName, srcListName, tgtListName ) {
	var form = eval( 'document.' + frmName );
	var srcList = eval( 'form.' + srcListName );
	var tgtList = eval( 'form.' + tgtListName );
	var srcLen = srcList.length;
	var tgtLen = tgtList.length;
	var tgt = "x";
	for (var i=tgtLen-1; i > -1; i--) {
		tgt += "," + tgtList.options[i].value + ","
	}
	for (var i=0; i < srcLen; i++) {
		if (srcList.options[i].selected && tgt.indexOf( "," + srcList.options[i].value + "," ) == -1) {
			opt = new Option( srcList.options[i].text, srcList.options[i].value );
			tgtList.options[tgtList.length] = opt;
		}
	}
}

// TODO переписать на Jquery
function delSelectedFromList( frmName, srcListName ) {
	var form = eval( 'document.' + frmName );
	var srcList = eval( 'form.' + srcListName );
	var srcLen = srcList.length;
	for (var i=srcLen-1; i > -1; i--) {
		if (srcList.options[i].selected) {
			srcList.options[i] = null;
		}
	}
}

// TODO переписать на Jquery
function moveInList( frmName, srcListName, index, to) {
	var form = eval( 'document.' + frmName );
	var srcList = eval( 'form.' + srcListName );
	var total = srcList.options.length-1;
	if (index == -1) {
		return false;
	}
	if (to == +1 && index == total) {
		return false;
	}
	if (to == -1 && index == 0) {
		return false;
	}
	var items = new Array;
	var values = new Array;
	for (i=total; i >= 0; i--) {
		items[i] = srcList.options[i].text;
		values[i] = srcList.options[i].value;
	}
	for (i = total; i >= 0; i--) {
		if (index == i) {
			srcList.options[i + to] = new Option(items[i],values[i], 0, 1);
			srcList.options[i] = new Option(items[i+to], values[i+to]);
			i--;
		} else {
			srcList.options[i] = new Option(items[i], values[i]);
		}
	}
	srcList.focus();
}

// TODO переписать на Jquery
function getSelectedOption( frmName, srcListName ) {
	var form = eval( 'document.' + frmName );
	var srcList = eval( 'form.' + srcListName );

	i = srcList.selectedIndex;
	if (i != null && i > -1) {
		return srcList.options[i];
	} else {
		return null;
	}
}

// TODO переписать на Jquery
function setSelectedValue( frmName, srcListName, value ) {
	var form = eval( 'document.' + frmName );
	var srcList = eval( 'form.' + srcListName );

	var srcLen = srcList.length;

	for (var i=0; i < srcLen; i++) {
		srcList.options[i].selected = false;
		if (srcList.options[i].value == value) {
			srcList.options[i].selected = true;
		}
	}
}

// TODO переписать на Jquery
function getSelectedValue( frmName, srcListName ) {
	var form = eval( 'document.' + frmName );
	var srcList = eval( 'form.' + srcListName );
	i = srcList.selectedIndex;
	if (i != null && i > -1) {
		return srcList.options[i].value;
	} else {
		return null;
	}
}

// TODO переписать на Jquery
function getSelectedText( frmName, srcListName ) {
	var form = eval( 'document.' + frmName );
	var srcList = eval( 'form.' + srcListName );
	i = srcList.selectedIndex;
	if (i != null && i > -1) {
		return srcList.options[i].text;
	} else {
		return null;
	}
}

// TODO переписать на Jquery
function chgSelectedValue( frmName, srcListName, value ) {
	var form = eval( 'document.' + frmName );
	var srcList = eval( 'form.' + srcListName );

	i = srcList.selectedIndex;
	if (i != null && i > -1) {
		srcList.options[i].value = value;
		return true;
	} else {
		return false;
	}
}

// TODO переписать на Jquery
function showImageProps(base_path) {
	form = document.adminForm;
	value = getSelectedValue( 'adminForm', 'imagelist' );
	parts = value.split( '|' );
	form._source.value = parts[0];
	setSelectedValue( 'adminForm', '_align', parts[1] || '' );
	form._alt.value = parts[2] || '';
	form._border.value = parts[3] || '0';
	form._caption.value = parts[4] || '';
	setSelectedValue( 'adminForm', '_caption_position', parts[5] || '' );
	setSelectedValue( 'adminForm', '_caption_align', parts[6] || '' );
	form._width.value = parts[7] || '';
	//previewImage( 'imagelist', 'view_imagelist', base_path );
	srcImage = eval( "document." + 'view_imagelist' );
	srcImage.src = base_path + parts[0];
}

// TODO переписать на Jquery
function applyImageProps() {
	form = document.adminForm;
	if (!getSelectedValue( 'adminForm', 'imagelist' )) {
		alert( "Выберите изображение из списка" );
		return;
	}
	value = form._source.value + '|'
	+ getSelectedValue( 'adminForm', '_align' ) + '|'
	+ form._alt.value + '|'
	+ parseInt( form._border.value ) + '|'
	+ form._caption.value + '|'
	+ getSelectedValue( 'adminForm', '_caption_position' ) + '|'
	+ getSelectedValue( 'adminForm', '_caption_align' ) + '|'
	+ form._width.value;
	chgSelectedValue( 'adminForm', 'imagelist', value );
}

// TODO переписать на Jquery
function previewImage( list, image, base_path ) {
	form = document.adminForm;
	srcList = eval( "form." + list );
	srcImage = eval( "document." + image );
	var srcOption = srcList.options[(srcList.selectedIndex < 0) ? 0 : srcList.selectedIndex];
	var fileName = srcOption.text;
	var fileName2 = srcOption.value;
	if (fileName.length == 0 || fileName2.length == 0) {
		srcImage.src = 'images/blank.gif';
	} else {
		srcImage.src = base_path + fileName2;
	}
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

// TODO переписать на Jquery
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

// TODO переписать на Jquery
function hideMainMenu(){
	document.adminForm.hidemainmenu.value=1;
}

// TODO переписать на Jquery
function isChecked(isitchecked){
	if (isitchecked == true){
		document.adminForm.boxchecked.value++;
	}else {
		document.adminForm.boxchecked.value--;
	}
}

function submitbutton(pressbutton) {
	submitform(pressbutton);
}

function submitform(pressbutton){
	document.adminForm.task.value=pressbutton;
	try {
		document.adminForm.onsubmit();
	}
	catch(e){}
	document.adminForm.submit();
}

function submitcpform(sectionid, id){
	document.adminForm.sectionid.value=sectionid;
	document.adminForm.id.value=id;
	submitbutton("edit");
}

function getSelected(allbuttons){
	for (i=0;i<allbuttons.length;i++) {
		if (allbuttons[i].checked) {
			return allbuttons[i].value
		}
	}
	return false;
}

// JS Calendar
var calendar = null;

function selected(cal, date) {
	cal.sel.value = date;
}

function closeHandler(cal) {
	cal.hide();
	Calendar.removeEvent(document, "mousedown", checkCalendar);
}

// TODO переписать на Jquery
function checkCalendar(ev) {
	var el = Calendar.is_ie ? Calendar.getElement(ev) : Calendar.getTargetElement(ev);
	for (; el != null; el = el.parentNode)
		if (el == calendar.element || el.tagName == "A") break;
	if (el == null) {
		calendar.callCloseHandler();
		Calendar.stopEvent(ev);
	}
}

// TODO переписать на Jquery
function showCalendar(id) {
	var el = document.getElementById(id);
	if (calendar != null) {
		calendar.hide();
		calendar.parseDate(el.value);
	} else {
		var cal = new Calendar(true, null, selected, closeHandler);
		calendar = cal;
		cal.setRange(1900, 2070);
		calendar.create();
		calendar.parseDate(el.value);
	}
	calendar.sel = el;
	calendar.showAtElement(el);
	Calendar.addEvent(document, "mousedown", checkCalendar);
	return false;
}

// TODO переписать на Jquery
function popupWindow(mypage, myname, w, h, scroll) {
	var winl = (screen.width - w) / 2;
	var wint = (screen.height - h) / 2;
	winprops = 'height='+h+',width='+w+',top='+wint+',left='+winl+',scrollbars='+scroll+',resizable'
	win = window.open(mypage, myname, winprops)
	if (parseInt(navigator.appVersion) >= 4) {
		win.window.focus();
	}
}

function saveorder( n ) {
	checkAll_button( n );
}
//needed by saveorder function
function checkAll_button( ) {
	checkAll( );
	submitform('saveorder');
}