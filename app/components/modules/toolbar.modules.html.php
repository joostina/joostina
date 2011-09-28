<?php
/**
 * @package   Joostina
 * @copyright Авторские права (C) 2007-2010 Joostina team. Все права защищены.
 * @license   Лицензия http://www.gnu.org/licenses/gpl-2.0.htm GNU/GPL, или help/license.php
 * Joostina! - свободное программное обеспечение распространяемое по условиям лицензии GNU/GPL
 * Для получения информации о используемых расширениях и замечаний об авторском праве, смотрите файл help/copyright.php.
 */

// запрет прямого доступа
defined( '_JOOS_CORE' ) or die();

class TOOLBAR_modules {

	public static function _NEW() {
		mosMenuBar::startTable();
		mosMenuBar::preview( 'modulewindow' );
		mosMenuBar::spacer();
		mosMenuBar::save();
		mosMenuBar::spacer();
		mosMenuBar::apply();
		mosMenuBar::spacer();
		mosMenuBar::cancel();
		mosMenuBar::spacer();
		mosMenuBar::help( 'screen.modules.new' );
		mosMenuBar::endTable();
	}

	public static function _EDIT( $cur_template , $publish ) {
		global $id;
		mosMenuBar::startTable();
		mosMenuBar::ext( _PREVIEW , '#' , '-preview' , " onclick=\"if (typeof document.adminForm.content == 'undefined') { alert('" . _PREVIEW_ONLY_CREATED_MODULES . "');} else { var content = document.adminForm.content.value; content = content.replace('#', ''); var title = document.adminForm.title.value; title = title.replace('#', ''); window.open('popups/modulewindow.php?title=' + title + '&amp;content=' + content + '&amp;t=$cur_template', 'win1', 'status=no,toolbar=no,scrollbars=auto,titlebar=no,menubar=no,resizable=yes,width=600,height=500,directories=no,location=no');}\"" );
		mosMenuBar::save();
		mosMenuBar::spacer();
		// кнопка "Применить" с Ajax
		mosMenuBar::ext( _APPLY , '#' , '-apply' , 'id="tb-apply" onclick="ch_apply();return;"' );

		mosMenuBar::spacer();
		if ( $id ) {
			// for existing content items the button is renamed `close`
			mosMenuBar::cancel( 'cancel' , _CLOSE );
		} else {
			mosMenuBar::cancel();
		}
		mosMenuBar::spacer();
		mosMenuBar::help( 'screen.modules.edit' );
		mosMenuBar::endTable();
	}

	public static function _DEFAULT() {
		mosMenuBar::startTable();
		mosMenuBar::publishList();
		mosMenuBar::spacer();
		mosMenuBar::unpublishList();
		mosMenuBar::spacer();
		mosMenuBar::custom( 'copy' , '-copy' , '' , _COPY , true );
		mosMenuBar::spacer();
		mosMenuBar::deleteList();
		mosMenuBar::spacer();
		mosMenuBar::editListX();
		mosMenuBar::spacer();
		mosMenuBar::addNewX();
		mosMenuBar::spacer();
		mosMenuBar::help( 'screen.modules' );
		mosMenuBar::endTable();
	}
}