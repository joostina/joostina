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
 * Component item link class
 * @package Joostina
 * @subpackage Menus
 */
class component_item_link_menu {

	function edit(&$uid, $menutype, $option, $menu) {
		global $database, $my, $mainframe;

		// fail if checked out not by 'me'
		if ($menu->checked_out && $menu->checked_out != $my->id) {
			mosErrorAlert($menu->title . " " . _MODULE_IS_EDITING_MY_ADMIN);
		}

		if (!$uid) {
			// load values for new entry
			$menu->type = 'component_item_link';
			$menu->menutype = $menutype;
			$menu->browserNav = 0;
			$menu->ordering = 9999;
			$menu->parent = intval(mosGetParam($_POST, 'parent', 0));
			$menu->published = 1;

			if ($uid) {
				$temp = explode('&Itemid=', $menu->link);
				$query = "SELECT a.name" . "\n FROM #__menu AS a" . "\n WHERE a.link = " . $database->Quote($temp[0]);
				$database->setQuery($query);
				$components = $database->loadResult();
				$lists['components'] = $components;
				$lists['components'] .= '<input type="hidden" name="link" value="' . $menu->link . '" />';
			} else {
				$query = "SELECT CONCAT( a.link, '&amp;Itemid=', a.id ) AS value, a.name AS text" .
						"\n FROM #__menu AS a" . "\n WHERE a.published = 1" . "\n AND a.type = 'components'" .
						"\n ORDER BY a.menutype, a.name";
				$database->setQuery($query);
				$components = $database->loadObjectList();

				//	Create a list of links
				$lists['components'] = mosHTML::selectList($components, 'link', 'ondblclick="jadd(\'name\',this.options[this.selectedIndex].text);" class="inputbox" size="10"', 'value', 'text', '');
			}

			// build html select list for target window
			$lists['target'] = mosAdminMenus::Target($menu);

			// build the html select list for ordering
			$lists['ordering'] = mosAdminMenus::Ordering($menu, $uid);
			// build the html select list for the group access
			$lists['access'] = mosAdminMenus::Access($menu);
			// build the html select list for paraent item
			$lists['parent'] = mosAdminMenus::Parent($menu);
			// build published button option
			$lists['published'] = mosAdminMenus::Published($menu);
			// build the url link output
			$lists['link'] = mosAdminMenus::Link($menu, $uid, 1);

			// get params definitions
			$params = new mosParameters($menu->params, $mainframe->getPath('menu_xml', $menu->type), 'menu');

			component_item_link_menu_html::edit($menu, $lists, $params, $option);
		}
	}
}