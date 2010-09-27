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


class TOOLBAR_templates {

	public static function _DEFAULT($client) {
		mosMenuBar::startTable();
		if($client == "admin") {
			mosMenuBar::makeDefault();
			mosMenuBar::spacer();
		} else {
			mosMenuBar::makeDefault();
			mosMenuBar::spacer();
			mosMenuBar::assign();
			mosMenuBar::spacer();
		}
		mosMenuBar::deleteList();
		mosMenuBar::spacer();
		mosMenuBar::editHtmlX('edit_source');
		mosMenuBar::spacer();
		mosMenuBar::editCssX('edit_css');
		mosMenuBar::spacer();
		mosMenuBar::addNew();
		mosMenuBar::spacer();
		mosMenuBar::help('screen.templates');
		mosMenuBar::endTable();
	}

	public static function _VIEW() {
		mosMenuBar::startTable();
		mosMenuBar::back();
		mosMenuBar::endTable();
	}

	public static function _EDIT_SOURCE() {
		mosMenuBar::startTable();
		mosMenuBar::save('save_source');
		mosMenuBar::ext(_APPLY,'#','-apply','id="tb-apply" onclick="ch_apply();return;"');
		mosMenuBar::spacer();
		mosMenuBar::cancel();
		mosMenuBar::endTable();
	}

	public static function _EDIT_CSS() {
		mosMenuBar::startTable();
		mosMenuBar::save('save_css');
		mosMenuBar::ext(_APPLY,'#','-apply','id="tb-apply" onclick="ch_apply();return;"');
		mosMenuBar::spacer();
		mosMenuBar::cancel();
		mosMenuBar::endTable();
	}

	public static function _ASSIGN() {
		mosMenuBar::startTable();
		mosMenuBar::save('save_assign',_SAVE);
		mosMenuBar::spacer();
		mosMenuBar::cancel();
		mosMenuBar::spacer();
		mosMenuBar::help('screen.templates.assign');
		mosMenuBar::endTable();
	}

	public static function _POSITIONS() {
		mosMenuBar::startTable();
		mosMenuBar::save('save_positions');
		mosMenuBar::spacer();
		mosMenuBar::cancel();
		mosMenuBar::spacer();
		mosMenuBar::help('screen.templates.modules');
		mosMenuBar::endTable();
	}
}