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

Jacl::isDeny('quickicons','edit') ? ajax_acl_error() : null;

$task = mosGetParam($_GET,'task','publish');
$id = intval(mosGetParam($_GET,'id','0'));

// обрабатываем полученный параметр task
switch($task) {
	case "publish":
		echo x_publish($id);
		return;
	default:
		echo "task not found";
		return;
}


/* публикация объекта
* $id - идентификатор объекта
*/
function x_publish($id = null) {
	global $database;
	// id содержимого для обработки не получен - выдаём ошибку
	if(!$id) return _UNKNOWN_ID;

	$state = new stdClass();
	$query = "SELECT published FROM #__quickicons WHERE id = ".(int)$id;
	$database->setQuery($query);
	$state = $database->loadResult();
	if($state == '1') {
		$ret_img = 'publish_x.png';
		$state = '0';
	} else {
		$ret_img = 'publish_g.png';
		$state = '1';
	}
	$query = 'UPDATE #__quickicons SET published = '.$state.' WHERE id = '.(int)$id;
	$database->setQuery($query);
	if(!$database->query()) {
		return 'error!';
	} else {
		return $ret_img;
	}
}