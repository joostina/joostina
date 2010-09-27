<?php
/**
 * @package Joostina
 * @copyright Авторские права (C) 2007-2010 Joostina team. Все права защищены.
 * @license Лицензия http://www.gnu.org/licenses/gpl-2.0.htm GNU/GPL, или help/license.php
 * Joostina! - свободное программное обеспечение распространяемое по условиям лицензии GNU/GPL
 * Для получения информации о используемых расширениях и замечаний об авторском праве, смотрите файл help/copyright.php.
 */

// запрет прямого доступа
defined('_JOOS_CORE') or die();

$id = mosGetParam($_REQUEST, 'id', false);

mosMenuBar::startTable();
mosMenuBar::save();
mosMenuBar::custom('save_and_new','-save-and-new','',_SAVE_AND_ADD,false);
$id ? mosMenuBar::ext(_APPLY,'#','-apply','id="tb-apply" onclick="return ch_apply();"') : mosMenuBar::apply();
$id ? mosMenuBar::cancel('cancel',_CLOSE) : mosMenuBar::cancel();
mosMenuBar::endTable();
