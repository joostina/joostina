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

require_once ($mainframe->getPath('toolbar_html'));
require_once ($mainframe->getPath('toolbar_default'));

switch($task) {
	case 'new':
		TOOLBAR_menus::_NEW();
		break;

	case 'movemenu':
		TOOLBAR_menus::_MOVEMENU();
		break;

	case 'copymenu':
		TOOLBAR_menus::_COPYMENU();
		break;

	case 'edit':
		$cid = josGetArrayInts('cid');
		$path = JPATH_BASE_ADMIN.'/components/com_menus/';

		if($cid[0]) {
			$query = "SELECT type FROM #__menu WHERE id = ".(int)$cid[0];
			$database->setQuery($query);
			$type = $database->loadResult();
			$item_path = $path.$type.'/'.$type.'.menubar.php';

			if($type) {
				if(file_exists($item_path)) {
					require_once ($item_path);
				} else {
					TOOLBAR_menus::_EDIT();
				}
			} else {
				echo $database->stderr();
			}
		} else {
			$type = strval(mosGetParam($_REQUEST,'type',null));
			$item_path = $path.$type.'/'.$type.'.menubar.php';

			if($type) {
				if(file_exists($item_path)) {
					require_once ($item_path);
				} else {
					TOOLBAR_menus::_EDIT();
				}
			} else {
				TOOLBAR_menus::_EDIT();
			}
		}
		break;

	default:
		$type = strval(mosGetParam($_REQUEST,'type'));
		$item_path = $path.$type.'/'.$type.'.menubar.php';

		if($type) {
			if(file_exists($item_path)) {
				require_once ($item_path);
			} else {
				TOOLBAR_menus::_DEFAULT();
			}
		} else {
			TOOLBAR_menus::_DEFAULT();
		}
		break;
}
?>
