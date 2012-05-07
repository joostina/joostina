<?php defined('_JOOS_CORE') or die();
/**
 * Menu - модуль вывода главного меню сайта
 *
 */
class moduleActionsMenu extends moduleActions {

	/**
	 * Вывод меню
	 */
	public static function default_action() {
		$menu_items = require_once JPATH_APP_CONFIG . '/menu.php';
		
		return array(
			'menu_items' => $menu_items
		);
	}
}