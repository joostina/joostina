<?php defined('_JOOS_CORE') or die();

/**
 * Работа с куками
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
 * */
class joosCookie {

	/**
	 * Установка куки
	 *
	 * @tutorial joosCookie::set('cookie_name', 'cookie_value'); // просто установка куки с названием cookie_name и значением cookie_value
	 * @tutorial joosCookie::set('cookie_name', 'cookie_value', array( 'expires'=>'next day' ) ); // установка куки со временем жизни - 1 день
	 * @tutorial joosCookie::set('cookie_name', 'cookie_value', array( 'expires' => '+1 ', 'secure' => true, 'httponly' => true));
	 *
	 * @param string $name   название куки
	 * @param string $value  значение куки
	 * @param array  $params массив необязательных параметров, имеет ключи:
	 * 	 expires - время жизни куки, помимо указания точного времени в секундах, можно использовать логические обозначения принимаемые стандартной php-функцией strtotime
	 * 	 path - путь установки куки, по умолчанию кука ставится в / - корень сайта
	 * 	 domain - адрес домена на который ставится кука, по умолчанию - на текущий
	 * 	 secure - показывает, что кука должна быть передана через защищенное HTTPS соединение
	 * 	 httponly - означает, что кука не будет доступна на скриптовых языках, таких как JavaScript. Предпологается что данный параметр может помочь уменьшить кражи личных данных через XSS-атаки (хотя это не поддерживается всеми браузерами)
	 */
	public static function set($name, $value, array $params = array()) {

		$expires = isset($params['expires']) ? $params['expires'] : NULL;
		$path = isset($params['path']) ? $params['path'] : '/';
		$domain = isset($params['domain']) ? $params['domain'] : JPATH_COOKIE;
		$secure = isset($params['secure']) ? $params['secure'] : false;
		$httponly = isset($params['httponly']) ? $params['httponly'] : false;

		if ($expires && !is_numeric($expires)) {
			$expires = strtotime($expires);
		}

		setcookie($name, $value, $expires, $path, $domain, $secure, ( $httponly ? true : null));
	}

	/**
	 * Получение значения куки по ключу
	 *
	 * @tutorial joosCookie::get('cookie_name'); // получение куки с названием cookie_name
	 * @tutorial joosCookie::get('cookie_name','default_data');  // получение куки с названием cookie_name, если она отсутствует - то будет использовано значение default_data
	 *
	 * @param string $name          название куки
	 * @param string $default_value значение куки по умолчанию
	 *
	 * @return string имеющееся значение куки, либо значение переданное в функцию по умолчанию
	 */
	public static function get($name, $default_value = NULL) {
		return isset($_COOKIE[$name]) ? $_COOKIE[$name] : $default_value;
	}

	/**
	 * Удаление куки
	 *
	 * @tutorial joosCookie::delete( 'cookie_name' ); // удалит куку с названием cookie_name
	 *
	 * @param string $name   название куки для удаления
	 * @param array  $params массив необязательных параметров, имеет ключи полностью аналогичные необязательным параметрам joosCookie::set
	 */
	public static function delete($name, array $params = array()) {

		$params['expires'] = isset($params['expires']) ? $params['expires'] : time() - 3600;
		self::set($name, false, $params);
	}

}
