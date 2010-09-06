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

$task = mosGetParam($_REQUEST,'task','');

switch($task) {
	case 'new':
	case 'edit':
	case 'editA':
		QI_Toolbar::_edit();
		break;

	case 'chooseIcon':
		QI_Toolbar::_chooseIcon();
		break;

	default:
		QI_Toolbar::_show();
		break;
}