<?php

/**
 * @package   Joostina
 * @copyright Авторские права (C) 2007-2010 Joostina team. Все права защищены.
 * @license   Лицензия http://www.gnu.org/licenses/gpl-2.0.htm GNU/GPL, или help/license.php
 * Joostina! - свободное программное обеспечение распространяемое по условиям лицензии GNU/GPL
 * Для получения информации о используемых расширениях и замечаний об авторском праве, смотрите файл help/copyright.php.
 */
// запрет прямого доступа
DEFINED('_JOOS_CORE') or die();

// регистрация автолоадера
joosAutoloader::init();

// сначала инициализация базовой конфигурации
joosConfig::init();

// название шаблона сайта
DEFINE('JTEMPLATE', joosConfig::get('template'));
// название шаблона панели управления
DEFINE('JTEMPLATE_ADMIN', joosConfig::get('template_admin'));

// название каталога приложения пользователя
DEFINE('JAPP_DIR', 'app');

// http адрес сайта
DEFINE('JPATH_SITE', joosConfig::get('live_site'));

// http корень для изображений
DEFINE('JPATH_SITE_IMAGES', JPATH_SITE);

// http корень для файлов
DEFINE('JPATH_SITE_FILES', JPATH_SITE);

// http корень каталога администратора
DEFINE('JPATH_SITE_ADMIN', JPATH_SITE . '/admin');

// каталог файлов пользовательского приложения, app по умолчанию
DEFINE('JPATH_BASE_APP', JPATH_BASE . DS . JAPP_DIR);

//плагины - http-путь
DEFINE('JPATH_SITE_PLUGINS', JPATH_SITE . '/' . JAPP_DIR . '/' . 'plugins');
//плагины - base-путь
DEFINE('JPATH_BASE_PLUGINS', JPATH_BASE . DS . JAPP_DIR . DS . 'plugins');

// каталог кэша
DEFINE('JPATH_BASE_CACHE', JPATH_BASE . DS . 'cache');

// путь для установки кук
DEFINE('JPATH_COOKIE', str_replace(array('http://', 'https://', 'www'), '', JPATH_SITE));

// параметр активации отладки, активация работы в режиме отладки - осуществляется через конфиг, либо вручную установку в браузере куки с произвольным названием, по умолчанию - joostinadebugmode
DEFINE('JDEBUG', ( (bool) isset($_COOKIE['joostinadebugmode']) ) ? true : joosConfig::get('debug', 0) );

// режим работы окружения
DEFINE('JENVIRONMENT', JDEBUG ? 'development' : 'production' );

switch (JENVIRONMENT) {

	case 'development':
		// установка режима отображения ошибок
		//error_reporting(E_ALL & ~E_DEPRECATED ^ E_STRICT);
		error_reporting(E_ALL | E_NOTICE | E_STRICT);
		ini_set('display_errors', 1);
		break;

	//case 'testing':
	case 'production':
		error_reporting(0);
		ini_set('display_errors', 0);
		break;

	default:
		exit('Окружение работы выбрано некорректно.');
}

// установка часового пояса по умолчанию
( ini_get('date.timezone') != '' ) ? : date_default_timezone_set('Europe/Moscow');

// склеивать и кешировать js+css файлы
DEFINE('JSCSS_CACHE', false);
DEFINE('JFILE_ANTICACHE', '?v=1');

// текущее время сервера
DEFINE('JCURRENT_SERVER_TIME', date('Y-m-d H:i:s', time()));

// ГЛАВНОЕ регулярное выражение для проверки логина-имени пользователя
DEFINE('JUSER_NAME_REGEX', '/^[a-zA-Z0-9_-]{3,25}$/iu');

// секретная фраза для хеширования
DEFINE('JSECRET_CODE', 'i-love-joostina');

DEFINE('JADMIN_SESSION_NAME', md5(JPATH_BASE . md5(JSECRET_CODE) . joosRequest::server('HTTP_USER_AGENT')));

// формат для функций вывода времени на сайте
DEFINE('JDATE_FORMAT', '%d %B %Y г. %H:%M'); //Используйте формат PHP-функции strftime

// права доступа на создаваемые файлы и каталоги
DEFINE('JFILE_READ_MODE', 0644);
DEFINE('JFILE_WRITE_MODE', 0666);
DEFINE('JDIR_READ_MODE', 0755);
DEFINE('JDIR_WRITE_MODE', 0777);

// использовать ли системную работу с модулями
DEFINE('JUSE_MODULES', false);

require 'events.php';

/**
 *  Авто-загружаемые библиотеки
 * joosAutoloader::libraries_load_on_start( array('text', 'session') );
 **/
