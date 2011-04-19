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
 * Класс работы с динамическим сообщением выдаваемым пользователю
 * @package Joostina
 * @subpackage FlashMessage
 */
class joosFlashMessage {

	/**
	 * Установка системного сообщения
	 * @static
	 * @param  $msg  текст сообщения
	 * @return void
	 */
	public static function add($msg) {
		$msg = joosString::trim($msg);

		if ($msg != '') {
			if (joosMainframe::is_admin()) {
				$_s = session_id();
				if (empty($_s)) {
					session_name(md5(JPATH_SITE));
					session_start();
				}
			} else {
				session_name(joosSession::sessionCookieName());
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

		if (!joosMainframe::is_admin() && empty($_s)) {
			session_name(joosSession::sessionCookieName());
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

}