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

class TOOLBAR_menus {

	public static function _NEW() {
		mosMenuBar::startTable();
		mosMenuBar::customX('edit','-next','',_MENU_NEXT,true);
		mosMenuBar::spacer();
		mosMenuBar::cancel();
		mosMenuBar::spacer();
		mosMenuBar::help('screen.menus.new');
		mosMenuBar::endTable();
	}

	public static function _MOVEMENU() {
		mosMenuBar::startTable();
		mosMenuBar::custom('movemenusave','-x-move','',_MOVE,false);
		mosMenuBar::spacer();
		mosMenuBar::cancel('cancelmovemenu');
		mosMenuBar::spacer();
		mosMenuBar::endTable();
	}

	public static function _COPYMENU() {
		mosMenuBar::startTable();
		mosMenuBar::custom('copymenusave','-copy','',_COPY,false);
		mosMenuBar::spacer();
		mosMenuBar::cancel('cancelcopymenu');
		mosMenuBar::spacer();
		mosMenuBar::endTable();
	}

	public static function _EDIT() {
		global $id;

		if(!$id) {
			$cid = josGetArrayInts('cid');
			$id = $cid[0];
		}
		$menutype = strval(mosGetParam($_REQUEST,'menutype','mainmenu'));

		mosMenuBar::startTable();
		if(!$id) {
			$link = 'index2.php?option=com_menus&menutype='.$menutype.'&task=new&hidemainmenu=1';
			mosMenuBar::back(_MENU_BACK,$link);
			mosMenuBar::spacer();
		}
		mosMenuBar::custom('save_and_new','-save-and-new','',_SAVE_AND_ADD,false);
		mosMenuBar::save();
		mosMenuBar::spacer();
		mosMenuBar::apply();
		mosMenuBar::spacer();
		if($id) {
			// for existing content items the button is renamed `close`
			mosMenuBar::cancel('cancel',_CLOSE);
		} else {
			mosMenuBar::cancel();
		}
		mosMenuBar::spacer();
		mosMenuBar::endTable();
	}

	public static function _DEFAULT() {
		mosMenuBar::startTable();
		mosMenuBar::publishList();
		mosMenuBar::spacer();
		mosMenuBar::unpublishList();
		mosMenuBar::spacer();
		mosMenuBar::customX('movemenu','-move','',_MOVE,true);
		mosMenuBar::spacer();
		mosMenuBar::customX('copymenu','-copy','',_COPY,true);
		mosMenuBar::spacer();
		mosMenuBar::trash();
		mosMenuBar::spacer();
		mosMenuBar::editListX();
		mosMenuBar::spacer();
		mosMenuBar::addNewX();
		mosMenuBar::spacer();
		mosMenuBar::endTable();
	}
}