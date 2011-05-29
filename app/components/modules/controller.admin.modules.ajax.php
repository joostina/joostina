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

class actionsAjaxModules {

	private static $implode_model = true;

	public static function statuschanger() {
		joosAutoAdmin::autoajax();
	}

	public static function index() {
		
	}

	public static function get_positions() {
		$positions = new TemplatePositions;
		$obj = new Modules;
	}

	public static function save_position() {
		$obj_id = joosRequest::int('obj_id', 0, $_POST);
		$obj = new Modules;
		$obj->load($obj_id);

		$obj->position = joosRequest::post('val');
		$obj->store();
	}

}