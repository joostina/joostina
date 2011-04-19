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
 * Класс работы с сессиями
 * @package Joostina
 * @subpackage Session
 */
class joosSession {

	private static $_userstate;

	function get($key, $default = null) {
		return joosRequest::session($key, $default);
	}

	public static function set($key, $value) {
		return $_SESSION[$key] = $value;
	}

	public static function sessionCookieName() {
		$syte = str_replace(array('http://', 'https://'), '', JPATH_SITE);
		return md5('site' . $syte);
	}

	public static function sessionCookieValue($id = null) {
		$type = joosConfig::get2('session', 'type', 3);
		$browser = @$_SERVER['HTTP_USER_AGENT'];

		switch ($type) {
			case 2:
				// 1.0.0 to 1.0.7 Compatibility
				// lowest level security
				$value = md5($id . $_SERVER['REMOTE_ADDR']);
				break;

			case 1:
				// slightly reduced security - 3rd level IP authentication for those behind IP Proxy
				$remote_addr = explode('.', $_SERVER['REMOTE_ADDR']);
				$ip = $remote_addr[0] . '.' . $remote_addr[1] . '.' . $remote_addr[2];
				$value = joosSpoof::hash($id . $ip . $browser);
				break;

			default:
				// Highest security level - new default for 1.0.8 and beyond
				$ip = $_SERVER['REMOTE_ADDR'];
				$value = joosSpoof::hash($id . $ip . $browser);
				break;
		}

		return $value;
	}

	public static function init_user_state() {

		if (self::$_userstate === null && isset($_SESSION['session_userstate'])) {
			self::$_userstate = &$_SESSION['session_userstate'];
		} else {
			self::$_userstate = null;
		}
	}

	public static function get_user_state_from_request($var_name, $req_name, $var_default = null) {
		if (is_array(self::$_userstate)) {
			if (isset($_REQUEST[$req_name])) {
				self::set_user_state($var_name, $_REQUEST[$req_name]);
			} else
			if (!isset(self::$_userstate[$var_name])) {
				self::set_user_state($var_name, $var_default);
			}

			self::$_userstate[$var_name] = joosInputFilter::instance()->process(self::$_userstate[$var_name]);
			return self::$_userstate[$var_name];
		} else {
			return null;
		}
	}

	private static function set_user_state($var_name, $var_value) {
		// TODO сюда надо isset
		if (is_array(self::$_userstate)) {
			self::$_userstate[$var_name] = $var_value;
		}
	}

}