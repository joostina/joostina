<?php

// запрет прямого доступа
defined('_JOOS_CORE') or die();

/**
 * joosConfig - Библиотека управления параметрами
 * Системная библиотека
 *
 * @version    1.0
 * @package    Libraries
 * @subpackage Libraries
 * @subpackage Config
 * @category   Libraries
 * @author     Joostina Team <info@joostina.ru>
 * @copyright  (C) 2007-2012 Joostina Team
 * @license    MIT License http://www.opensource.org/licenses/mit-license.php
 * Информация об авторах и лицензиях стороннего кода в составе Joostina CMS: docs/copyrights
 *
 * @todo       рассмотреть возможность использования SPL ArrayObject
 *
 * */
class joosConfig {

	private static $data = array();

	public static function init() {
		if (empty(self::$data)) {
			$conf = require_once JPATH_APP_CONFIG . DS . 'site.php';
			self::$data = $conf;
		}
	}

	public static function get_all() {
		return self::$data;
	}

	public static function get($name, $default = null) {
		return isset(self::$data[$name]) ? self::$data[$name] : $default;
	}

	public static function get2($type, $name, $default = null) {
		return ( isset(self::$data[$type]) && isset(self::$data[$type][$name]) ) ? self::$data[$type][$name] : $default;
	}

	public static function set($name, $value) {
		self::$data[$name] = $value;
	}

}
