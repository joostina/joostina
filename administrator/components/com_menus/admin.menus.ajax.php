<?php
/**
 * @package Joostina
 * @copyright Авторские права (C) 2007-2010 Joostina team. Все права защищены.
 * @license Лицензия http://www.gnu.org/licenses/gpl-2.0.htm GNU/GPL, или help/license.php
 * Joostina! - свободное программное обеспечение распространяемое по условиям лицензии GNU/GPL
 * Для получения информации о используемых расширениях и замечаний об авторском праве, смотрите файл help/copyright.php.
 */

// запрет прямого доступа
defined('_VALID_MOS') or die();

Jacl::isDeny('menumanager','edit') ? ajax_acl_error() : null;

$task = mosGetParam($_REQUEST,'task','publish');
$id = intval(mosGetParam($_GET,'id','0'));

$obj_id = (int)mosGetParam($_POST, 'obj_id', 0);

switch($task) {

	case "publish":
		echo x_publish($obj_id);
		return;

	case "access":
		echo x_access($id);
		return;

	case "component_params":
		echo component_params($obj_id);
		return;

	default:
		echo 'error-task';
		return;
}


function x_access($id) {
	global $database;
	$access = mosGetParam($_GET,'chaccess','accessregistered');
	$option = strval(mosGetParam($_REQUEST,'option',''));
	switch($access) {
		case 'accesspublic':
			$access = 0;
			break;
		case 'accessregistered':
			$access = 1;
			break;
		case 'accessspecial':
			$access = 2;
			break;
		default:
			$access = 0;
			break;
	}
	$row = new mosMenu($database);
	$row->load((int)$id);
	$row->access = $access;

	if(!$row->check()) return 'error-check';
	if(!$row->store()) return 'error-store';

	if(!$row->access) {
		$color_access = 'style="color: green;"';
		$task_access = 'accessregistered';
		$text_href = _USER_GROUP_ALL;
	} elseif($row->access == 1) {
		$color_access = 'style="color: red;"';
		$task_access = 'accessspecial';
		$text_href = _USER_GROUP_REGISTERED;
	} else {
		$color_access = 'style="color: black;"';
		$task_access = 'accesspublic';
		$text_href = _USER_GROUP_SPECIAL;
	}
	// чистим кэш
	mosCache::cleanCache('com_content');
	return '<a href="#" onclick="ch_access('.$row->id.',\''.$task_access.'\',\''.$option.'\')" '.$color_access.'>'.$text_href.'</a>';
}

function x_publish($id = null) {
	$database = database::getInstance();

	if (!$id) return 'error-id';

	$cat = new mosMenu($database);
	$cat->load($id);

	// пустой объект для складирования результата
	$return_onj = new stdClass();

	// меняем состояние объекта на противоположное
	if ( $cat->changeState('published')) {
		// формируем ответ из противоположных элементов текущему состоянию
		$return_onj->image = $cat->published ?  'publish_x.png' : 'publish_g.png';
		$return_onj->mess = $cat->published ?  _UNPUBLISHED : _PUBLISHED;
		mosCache::cleanCache('com_content');
	} else {
		// формируем резульлтат с ошибкой
		$return_onj->image = 'error.png';
		$return_onj->mess = 'error-class';
	}
	return json_encode($return_onj);
}

// подкачка формы настройки параметров компонента
function component_params($id) {

	// подключаем класс работы с компонентами
	mosMainFrame::addClass('component');

	$mainframe = mosMainFrame::getInstance();
	$database = $mainframe->getDBO();

	$component = new mosComponent($database);
	$component->load( $id );

	$params = new mosParameters('',$mainframe->getPath('com_xml',$component->option),'component');

	return $params->render();
}