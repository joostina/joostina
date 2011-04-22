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

/**
 * @package Joostina
 * @subpackage Config
 */
class TOOLBAR_config {

	/**
	 * Меню для сохранялки параметров отдельных компонентов
	 */
	public static function _SAVE_EXT_CONFIG() {
		mosMenuBar::startTable();
		mosMenuBar::save('save_component_config');
		mosMenuBar::spacer();
		mosMenuBar::cancel();
		mosMenuBar::endTable();
	}

	public static function _DEFAULT() {
		mosMenuBar::startTable();
		mosMenuBar::save();
		mosMenuBar::spacer();
		mosMenuBar::ext(_APPLY, '#', '-apply', 'id="tb-apply" onclick="ch_apply();return;"');
		mosMenuBar::spacer();
		mosMenuBar::cancel();
		mosMenuBar::spacer();
		mosMenuBar::help('screen.config');
		mosMenuBar::endTable();
	}

}