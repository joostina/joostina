<?php

// запрет прямого доступа
defined('_JOOS_CORE') or die();

/**
  * Библиотека работы со входящими пользовательскими данными и информацией
 * Системная библиотека
 *
 * @version    1.0
 * @package    Libraries
 * @subpackage Libraries
 * @category   Libraries
 * @author     Joostina Team <info@joostina.ru>
 * @copyright  (C) 2007-2012 Joostina Team
 * @license    MIT License http://www.opensource.org/licenses/mit-license.php
 * Информация об авторах и лицензиях стороннего кода в составе Joostina CMS: docs/copyrights
 *
 * @todo       в mosGetParams была очистка данных. Может, стоит и сюда прикрутить?
 * joosInputFilter::instance()->process($return)
 *
 * */
class joosRequest {

	/**
	 * Получение параметра
	 *
	 * @param string $name    название параметра
	 * @param string $default значение для параметра по умолчани
	 * @param array  $vars    массив переменных из которого необходимо получить параметр $name, по умолчанию используется суперглобальный $_REQUEST
	 *
	 * @return mixed результат, массив или строка
	 *
	 * @todo проанализировать необходимоть проверки наличия с empty (isset($vars[$name]) && !empty( $vars[$name] ) )
	 */
	public static function param($name, $default = null, $vars = false) {
		$vars = $vars ? $vars : $_REQUEST;
		return ( isset($vars[$name]) ) ? $vars[$name] : $default;
	}

	/**
	 * Получение параметров из суперглобального массива $_GET
	 *
	 * @param string $name    название параметра $name
	 * @param string $default значение для параметра, используемое по умолчанию
	 *
	 * @return mixed результат, массив или строка
	 */
	public static function get($name, $default = null) {
		return self::param($name, $default, $_GET);
	}

	/**
	 * Получение параметров из суперглобального массива $_POST
	 *
	 * @param string $name    название параметра $name
	 * @param string $default значение для параметра, используемое по умолчанию
	 *
	 * @return mixed результат, массив или строка
	 */
	public static function post($name, $default = null) {
		return self::param($name, $default, $_POST);
	}

	/**
	 * Получение параметров из суперглобального массива $_REQUEST
	 *
	 * @param string $name    название параметра $name
	 * @param string $default значение для параметра, используемое по умолчанию
	 *
	 * @return mixed результат, массив или строка
	 */
	public static function request($name, $default = null) {
		return self::param($name, $default, $_REQUEST);
	}

	/**
	 * Получение параметров загруженного файла из суперглобального массива $_FILES
	 *
	 * @param string $name название параметра $name
	 *
	 * @return mixed массив с информацией о файле, либо FALSE в случае отсутствия данных
	 */
	public static function file($name) {
		return self::param($name, false, $_FILES);
	}

	/**
	 * Получение параметров из суперглобального массива $_SERVER
	 *
	 * @param string $name    название параметра $name
	 * @param string $default значение для параметра, используемое по умолчанию
	 *
	 * @return mixed результат, массив или строка
	 */
	public static function server($name, $default = null) {
		return self::param($name, $default, $_SERVER);
	}

	/**
	 * Получение параметров из суперглобального массива $_ENV
	 *
	 * @param string $name    название параметра $name
	 * @param string $default значение для параметра, используемое по умолчанию
	 *
	 * @return mixed результат, массив или строка
	 */
	public static function env($name, $default = null) {
		return self::param($name, $default, $_ENV);
	}

	/**
	 * Получение параметров из суперглобального массива $_SESSION
	 *
	 * @param string $name    название параметра $name
	 * @param string $default значение для параметра, используемое по умолчанию
	 *
	 * @return mixed результат, массив или строка
	 */
	public static function session($name, $default = null) {
		return self::param($name, $default, $_SESSION);
	}

	/**
	 * Получение параметров из суперглобального массива $_COOKIE
	 *
	 * @param string $name    название параметра $name
	 * @param string $default значение для параметра, используемое по умолчанию
	 *
	 * @return mixed результат, массив или строка
	 */
	public static function cookies($name, $default = null) {
		return self::param($name, $default, $_COOKIE);
	}

	/**
	 * Получение параметра из заголовков сервера или клиенского браузера
	 *
	 * @param string $name    название параметра
	 * @param string $default значение для параметра, используемое по умолчанию
	 *
	 * @return mixed результат, массив или строка, либо false если параметр не обнаружен ( по умолчанию )
	 */
	public static function header($name, $default = false) {

		$name_ = 'HTTP_' . strtoupper(str_replace('-', '_', $name));
		if (isset($_SERVER[$name_])) {
			return $_SERVER[$name_];
		}

		if (function_exists('apache_request_headers')) {
			$headers = apache_request_headers();
			if (isset($headers[$name])) {
				return $headers[$name];
			}
		}

		return $default;
	}

	/**
	 * Получение параметра с принудительным приведением к цельночисленному (йифровому) варианту
	 *
	 * @param string $name    название параметра
	 * @param string $default значение для параметра по умолчани
	 * @param array  $vars    массив переменных из которого необходимо получить параметр $name, по умолчанию используется суперглобальный $_REQUEST
	 *
	 * @return mixed результат, массив или строка
	 */
	public static function int($name = null, $default = null, $vars = null) {
		return (int) self::param($name, $default, $vars);
	}

	/**
	 * Получение массива параметров
	 *
	 * @param string $name    название параметра
	 * @param string $default значение для параметра по умолчани
	 * @param array  $vars    массив переменных из которого необходимо получить параметр $name, по умолчанию используется суперглобальный $_REQUEST
	 *
	 * @return mixed результат, массив или строка
	 */
	public static function array_param($name = null, array $default = null, $vars = null) {
		return (array) self::param($name, $default, $vars);
	}

	/**
	 * Получение протокола активного соединения, http или https в случае защищенного соединения
	 * @return string http или https
	 */
	public static function protocol() {
		return ( 'on' == self::server('HTTPS') || 443 == self::server('SERVER_PORT') ) ? 'https' : 'http';
	}

	/**
	 * Проверка на работу методом GET
	 * @return bool результат проверки
	 */
	public static function is_get() {
		return 'GET' === strtoupper(self::server('REQUEST_METHOD'));
	}

	/**
	 * Проверка на работу методом POST
	 * @return bool результат проверки
	 */
	public static function is_post() {
		return 'POST' == strtoupper(self::server('REQUEST_METHOD'));
	}

	/**
	 * Проверка на работу методом PUT
	 * @return bool результат проверки
	 */
	public static function is_put() {
		return 'PUT' == strtoupper(self::server('REQUEST_METHOD'));
	}

	/**
	 * Проверка на работу методом DELETE
	 * @return bool результат проверки
	 */
	public static function is_delete() {
		return 'DELETE' == strtoupper(self::server('REQUEST_METHOD'));
	}

	/**
	 * Проверка работы через Ajax-соединение
	 * @return bool результат проверки
	 */
	public static function is_ajax() {
		return 'xmlhttprequest' == strtolower(self::header('X_REQUESTED_WITH'));
	}

	/**
	 * Проверка работы через через безопасное https соединение
	 * @return bool результат проверки
	 */
	public static function is_https() {
		return 'https' == self::protocol();
	}

	/**
	 * Получение IP адреса активного пользователя
	 * @return string IP адрес пользователя, либо FALSE в случае если IP адрес не получен
	 */
	public static function user_ip() {
		$keys = array('HTTP_X_FORWARDED_FOR', 'HTTP_CLIENT_IP', 'REMOTE_ADDR');

		foreach ($keys as $key) {
			if (!isset($_SERVER[$key])) {
				continue;
			}
			$ips = explode(',', $_SERVER[$key], 1);
			$ip = $ips[0];
			if (false != ip2long($ip) && long2ip(ip2long($ip) === $ip)) {
				return $ips[0];
			}
		}

		return false;
	}

	/**
	 * Установка кода в заголовок страницы
	 *
	 * @tutorial joosCore::send_headers_by_code(200);
	 * @tutorial joosCore::send_headers_by_code(301);
	 * @tutorial joosCore::send_headers_by_code(404);
	 * @tutorial joosCore::send_headers_by_code(504);
	 *
	 * @param int $code номер кода
	 */
	public static function send_headers_by_code($code = 200) {

		$code_array = array(
			// Информационные 1xx
			100 => 'Continue',
			101 => 'Switching Protocols',
			// Успешный 2xx
			200 => 'OK',
			201 => 'Created',
			202 => 'Accepted',
			203 => 'Non-Authoritative Information',
			204 => 'No Content',
			205 => 'Reset Content',
			206 => 'Partial Content',
			// Редиректы 3xx
			300 => 'Multiple Choices',
			301 => 'Moved Permanently',
			302 => 'Found', // 1.1
			303 => 'See Other',
			304 => 'Not Modified',
			305 => 'Use Proxy',
			// 306 зарезервирован
			307 => 'Temporary Redirect',
			// ошибки клиента 4xx
			400 => 'Bad Request',
			401 => 'Unauthorized',
			402 => 'Payment Required',
			403 => 'Forbidden',
			404 => 'Not Found',
			405 => 'Method Not Allowed',
			406 => 'Not Acceptable',
			407 => 'Proxy Authentication Required',
			408 => 'Request Timeout',
			409 => 'Conflict',
			410 => 'Gone',
			411 => 'Length Required',
			412 => 'Precondition Failed',
			413 => 'Request Entity Too Large',
			414 => 'Request-URI Too Long',
			415 => 'Unsupported Media Type',
			416 => 'Requested Range Not Satisfiable',
			417 => 'Expectation Failed',
			// ошибки сервера 5xx
			500 => 'Internal Server Error',
			501 => 'Not Implemented',
			502 => 'Bad Gateway',
			503 => 'Service Unavailable',
			504 => 'Gateway Timeout',
			505 => 'HTTP Version Not Supported',
			509 => 'Bandwidth Limit Exceeded'
		);

		// проверяем наличие кода ошибки
		$code_string = isset($code_array[$code]) ? (int) $code : $code_array[$code];

		// версия HTTP протокола
		if (isset($_SERVER['SERVER_PROTOCOL'])) {
			$protocol = $_SERVER['SERVER_PROTOCOL'];
		} else {
			$protocol = 'HTTP/1.1';
		}

		header($protocol . ' ' . $code_string);
	}

	/**
	 * Проверка на запуск скрипта через консаоль
	 *
	 * @return bool
	 */
	public static function is_console() {
		return PHP_SAPI === 'cli';
	}

}
