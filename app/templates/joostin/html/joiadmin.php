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

class joiadminHTML {

	public static function _listing() {
		
	}

	public static function _edit() {
		
	}

	public static function header($header, array $extra = array()) {

		$return = '<h1 class="title"><span>' . $header . '</span>';

		//Toolbar
		require_once (JPATH_BASE_ADMIN . '/includes/menubar.html.php');
		if ($path = joosMainframe::instance()->getPath('toolbar')) {
			ob_start();
			include_once ($path);
			$return .= ob_get_clean();
		}
		$return .= '</h1>';

		ob_start();
		mosLoadAdminModule('mosmsg');
		$return .= ob_get_clean();

		$return .= adminHtml::controller_header(false, 'config', $extra);

		return $return;
	}

	public static function footer() {
		return '';
	}

	public static function header_menu($headers_menu) {

		if (count($headers_menu) < 1) {
			return false;
		}

		$result = array();
		foreach ($headers_menu as $href) {
			$result[] = $href['active'] == false ? joosHtml::anchor($href['href'], $href['name']) : $href['name'];
		}

		return implode(' | ', $result);
	}

	public static function submenu($obj) {

		if (is_array($obj::$_submenu)) {
			$return = array();
			/** @var $_submenu array */
			foreach ($obj::$_submenu as $href) {
				$return[] = '<li>' . (!isset($href['active']) ? joosHtml::anchor($href['href'], $href['title']) : '<span>' . $href['title'] . '</span>') . '</li>';
			}
			return '<div class="submenu"><ul class="listreset nav-horizontal">' . implode('', $return) . '</ul></div>';
		}
	}

}