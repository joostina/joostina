<?php

// запрет прямого доступа
defined('_JOOS_CORE') or die();

/**
 * joosConfig - Библиотека управления параметрами
 * Системная библиотека
 * 
 * @version 1.0
 * @package Joostina.Libraries
 * @subpackage Libraries
 * @subpackage Config
 * @author Joostina Team <info@joostina.ru>
 * @copyright (C) 2008-2011 Joostina Team
 * @license MIT License http://www.opensource.org/licenses/mit-license.php
 * Информация об авторах и лицензиях стороннего кода в составе Joostina CMS: docs/copyrights
 *
 * */
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