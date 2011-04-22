<?php

// запрет прямого доступа
defined('_JOOS_CORE') or die();

/**
 * joosFlashMessage - Библиотека работы с системными уведомлениями
 * Системная библиотека
 *
 * @version 1.0
 * @package Joostina.Libraries
 * @subpackage Libraries
 * @subpackage joosSession
 * @author Joostina Team <info@joostina.ru>
 * @copyright (C) 2008-2011 Joostina Team
 * @license MIT License http://www.opensource.org/licenses/mit-license.php
 * Информация об авторах и лицензиях стороннего кода в составе Joostina CMS: docs/copyrights
 *
 * */
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