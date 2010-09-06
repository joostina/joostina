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


class QI_Toolbar {

	public static function _edit() {
		mosMenuBar::startTable();
		mosMenuBar::save('save');
		mosMenuBar::spacer();
		mosMenuBar::apply('apply');
		mosMenuBar::spacer();
		mosMenuBar::cancel('');
		mosMenuBar::endTable();
	}

	public static function _show() {
		mosMenuBar::startTable();
		mosMenuBar::publishList('publish');
		mosMenuBar::spacer();
		mosMenuBar::unpublishList('unpublish');
		mosMenuBar::spacer();
		mosMenuBar::addNew('new');
		mosMenuBar::spacer();
		mosMenuBar::editListX('editA');
		mosMenuBar::spacer();
		mosMenuBar::deleteList('','delete');
		mosMenuBar::endTable();
	}

	public static function _chooseIcon() {
		mosMenuBar::startTable();
		mosMenuBar::endTable();
	}
}