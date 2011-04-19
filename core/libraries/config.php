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
 * Класс работы с конфигурацией системы
 * @package Joostina
 * @subpackage Config
 */
class joosConfig {

	private static $data = array();

	public static function init() {
		$conf = require_once JPATH_BASE . DS . 'app' . DS . 'config.php';
		self::$data = $conf;
	}

	public static function get_all() {
		return self::$data;
	}

	public static function get($name, $default = null) {
		return isset(self::$data[$name]) ? self::$data[$name] : $default;
	}

	public static function get2($type, $name, $default = null) {
		return (isset(self::$data[$type]) && isset(self::$data[$type][$name])) ? self::$data[$type][$name] : $default;
	}

	public static function set($name, $value) {
		self::$data[$name] = $value;
	}

}