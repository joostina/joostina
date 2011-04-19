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
 * Класс работы с защитой от межсайтового скриптинга
 * @package Joostina
 * @subpackage Spoof
 */
class joosSpoof {

	public static function hash($seed) {
		return md5(_SECRET_CODE . md5($seed));
	}

	public static function get_code($alt = null) {
		if ($alt) {
			$random = ($alt == 1) ? $random = date('Ymd') : $alt . date('Ymd');
		} else {
			$random = date('dmY');
		}

		return 'j' . joosSpoof::hash(JPATH_BASE . $random . Users::current()->id);
	}

	public static function check_code($alt = null, $method = 'post') {

		switch (strtolower($method)) {
			case 'get':
				$validate = joosRequest::get(self::get_code($alt), 0);
				break;

			case 'request':
				$validate = joosRequest::request(self::get_code($alt), 0);

				break;

			case 'post':
			default:
				$validate = joosRequest::post(self::get_code($alt), 0);
				break;
		}

		if (!$validate) {
			header('HTTP/1.0 403 Forbidden');
			mosErrorAlert(_NOT_AUTH);
			return;
		}

		if (!isset($_SERVER['HTTP_USER_AGENT'])) {
			header('HTTP/1.0 403 Forbidden');
			mosErrorAlert(_NOT_AUTH);
			return;
		}

		if (!$_SERVER['REQUEST_METHOD'] == 'POST') {
			header('HTTP/1.0 403 Forbidden');
			mosErrorAlert(_NOT_AUTH);
			return;
		}
	}

}