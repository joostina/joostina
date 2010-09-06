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

$client = strval(mosGetParam($_REQUEST,'client',''));

switch($task) {

	case 'view':
		TOOLBAR_templates::_VIEW();
		break;

	case 'edit_source':
		TOOLBAR_templates::_EDIT_SOURCE();
		break;

	case 'edit_css':
		TOOLBAR_templates::_EDIT_CSS();
		break;

	case 'assign':
		TOOLBAR_templates::_ASSIGN();
		break;

	case 'positions':
		TOOLBAR_templates::_POSITIONS();
		break;

	default:
		TOOLBAR_templates::_DEFAULT($client);
		break;
}