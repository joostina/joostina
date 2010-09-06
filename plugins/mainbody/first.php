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

// очистка кода всего шаблона
$_MAMBOTS->registerFunction('onTemplate','body_clear');

/* функция производит очистку от спецсимволов*/
function body_clear( &$body ) {
	mosMainFrame::addLib('html_optimize');
	$body = html_optimize::optimize($body);
	return true;
}