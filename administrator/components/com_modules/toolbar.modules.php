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
switch($task) {

	case 'editA':
	case 'edit':
		$cid = mosGetParam($_POST,'cid',0);
		if(!is_array($cid)) {
			$mid = mosGetParam($_POST,'id',0);
		} else {
			$mid = $cid[0];
		}

		$published = 0;
		if($mid) {
			$query = "SELECT published FROM #__modules WHERE id = ".(int)$mid;
			$published = $database->setQuery($query)->loadResult();
		}
		$cur_template = JTEMPLATE;
		TOOLBAR_modules::_EDIT($cur_template,$published);
		break;

	case 'new':
		TOOLBAR_modules::_NEW();
		break;

	default:
		TOOLBAR_modules::_DEFAULT();
		break;
}