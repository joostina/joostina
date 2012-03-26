<?php

// запрет прямого доступа
defined('_JOOS_CORE') or die();

/**
  * Работа с системными уведомлениями
 *
 * @version    1.0
 * @package    Libraries
 * @subpackage Libraries
 * @subpackage joosSession
 * @category   Libraries
 * @author     Joostina Team <info@joostina.ru>
 * @copyright  (C) 2007-2012 Joostina Team
 * @license    MIT License http://www.opensource.org/licenses/mit-license.php
 * Информация об авторах и лицензиях стороннего кода в составе Joostina CMS: docs/copyrights
 *
 * */
class joosFlashMessage {

	/**
	 * Установка системного сообщения
	 * @static
	 *
	 * @param  $msg  текст сообщения
	 *
	 * @return void
	 */
	public static function add($msg) {
		$msg = joosString::trim($msg);

		if ($msg != '') {
			if (joosCore::is_admin()) {
				$_s = session_id();
				if (empty($_s)) {
					session_name(md5(JPATH_SITE));
					session_start();
				}
			} else {
				session_name(joosSession::session_cookie_name());
				session_start();
			}

			$_SESSION['joostina.mosmsg'] = $msg;
		}
	}

	/**
	 * Получение системного сообщения
	 * @return string - текст сообщения
	 */
	public static function get() {

		$_s = session_id();

		if (!joosCore::is_admin() && empty($_s)) {
			session_name(joosSession::session_cookie_name());
			session_start();
		}

		$mosmsg = joosRequest::session('joostina.mosmsg', false);

		if ($mosmsg != '' && joosString::strlen($mosmsg) > 300) { // выводим сообщения не длинее 300 символов
			$mosmsg = joosString::substr($mosmsg, 0, 300);
		}

		/**
		  @var $_SESSION array */
		unset($_SESSION['joostina.mosmsg']);
		return $mosmsg ? '<div class="b-system_message">' . $mosmsg . '</div>' : '';
	}

	/*
	  const SESSION_KEY = 'framework_flash';

	  private static $_previous = array(); // Data that prevous page left in the Flash

	  public static function get_message($var) {
	  return isset(self::$_previous[$var]) ? self::$_previous[$var] : null;
	  }

	  public static function set($var, $value) {
	  $_SESSION[self::SESSION_KEY][$var] = $value;
	  }


	  public static function clear() {
	  $_SESSION[self::SESSION_KEY] = array();
	  }

	  public static function init() {

	  if (!empty($_SESSION[self::SESSION_KEY]) && is_array($_SESSION[self::SESSION_KEY])) {
	  self::$_previous = $_SESSION[self::SESSION_KEY];
	  }
	  $_SESSION[self::SESSION_KEY] = array();
	  }
	 */
}
