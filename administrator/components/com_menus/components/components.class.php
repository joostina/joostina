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

class components_menu {

	public static function edit($uid,$menutype,$option,$menu) {
		global $my;

		$mainframe = mosMainFrame::getInstance(true);
		$database = $mainframe->getDBO();

		// подключаем класс работы с компонентами
		mosMainFrame::addClass('component');

		$row = new mosComponent($database);
		$row->load((int)$menu->componentid);

		if($menu->checked_out && $menu->checked_out != $my->id) {
			mosErrorAlert($menu->title." "._MODULE_IS_EDITING_MY_ADMIN);
		}

		if( !$uid) {
			$menu->type = 'components';
			$menu->menutype = $menutype;
			$menu->browserNav = 0;
			$menu->ordering = 9999;
			$menu->parent = intval(mosGetParam($_POST,'parent',0));
			$menu->published = 1;
		}

		$query = "SELECT c.id AS value, c.name AS text, c.link FROM #__components AS c WHERE c.link != '' ORDER BY c.name";
		$components = $database->setQuery($query)->loadObjectList();
		$lists['componentid'] = mosAdminMenus::Component($menu,$uid,$components,'onclick="update_params();"');
		$lists['componentname'] = mosAdminMenus::ComponentName($menu,$components);
		$lists['ordering'] = mosAdminMenus::Ordering($menu,$uid);
		$lists['access'] = mosAdminMenus::Access($menu);
		$lists['parent'] = mosAdminMenus::Parent($menu);
		$lists['published'] = mosAdminMenus::Published($menu);
		$lists['link'] = mosAdminMenus::Link($menu,$uid);

		// get params definitions
		$params = new mosParameters($menu->params,$mainframe->getPath('com_xml',$row->option),'component');
		components_menu_html::edit($menu,$components,$lists,$params,$option);
	}
}