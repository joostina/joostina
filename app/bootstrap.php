<?php

/**
 * @package Joostina
 * @copyright Авторские права (C) 2007-2010 Joostina team. Все права защищены.
 * @license Лицензия http://www.gnu.org/licenses/gpl-2.0.htm GNU/GPL, или help/license.php
 * Joostina! - свободное программное обеспечение распространяемое по условиям лицензии GNU/GPL
 * Для получения информации о используемых расширениях и замечаний об авторском праве, смотрите файл help/copyright.php.
 */
// запрет прямого доступа
DEFINEd('_JOOS_CORE') or die();

//Europe/Moscow // GMT0
//function_exists('date_default_timezone_set') ? date_default_timezone_set(date_default_timezone_get()) : null;
// TODO это вообще надо либо настраиваемо, либо убрать либо через другое место сдлеать
//function_exists('date_default_timezone_set') ? date_default_timezone_set('Europe/Moscow') : null;
// язык сайта
DEFINE('JLANG', 'russian');

// регистрация автолоадера
spl_autoload_register(array(new joosAutoloader, 'autoload'));

// сначала инициализация базовой конфигурации
joosConfig::init();

// http адрес сайта
DEFINE('JPATH_SITE', joosConfig::get('live_site'));

DEFINE('JPATH_BASE_APP', __DIR__);

DEFINE('JTEMPLATE', joosConfig::get('template'));
DEFINE('JTEMPLATE_ADMIN', joosConfig::get('template_admin'));

// http корень для изображений
DEFINE('JPATH_SITE_IMAGES', JPATH_SITE);

// http корень для файлов
DEFINE('JPATH_SITE_FILES', JPATH_SITE);

// каталог кэша
DEFINE('JPATH_BASE_CACHE', JPATH_BASE . DS . 'cache');

// путь для установки кук
DEFINE('JCOOKIE_PACH', str_replace(array('http://', 'https://', 'www'), '', JPATH_SITE));

// каталог администратора
//TODO: одно из этого нужно ликвидировать
DEFINE('JADMIN_BASE', 'admin');
DEFINE('JPATH_SITE_ADMIN', '/admin');

//плагины - http-путь
DEFINE('JPATH_SITE_PLUGINS', JPATH_SITE . '/app/plugins');
//плагины - base-путь
DEFINE('JPATH_BASE_PLUGINS', JPATH_BASE . DS . 'app' . DS . 'plugins');

// активация работы в режиме отладки - осуществляется через ручную установку в браузере куки с произвольным названием, по умолчанию - joostinadebugmode
DEFINE('JDEBUG_TEST_MODE', (bool) isset($_COOKIE['joostinadebugmode']));

// параметр активации отладки, можно совмещать с JDEBUG_TEST_MODE
DEFINE('JDEBUG', true);

if (JDEBUG) {
	// отлаживаем по максимуму
	error_reporting(E_ALL & ~E_DEPRECATED ^ E_STRICT);
	//error_reporting((JDEBUG ? E_ALL ^ E_STRICT : 0));
	ini_set('display_errors', 1);
}

// склеивать и кешировать js+css файлы
DEFINE('JSCSS_CACHE', false);
DEFINE('JFILE_ANTICACHE', '?v=1');

// текущее время сервера
DEFINE('_CURRENT_SERVER_TIME', date('Y-m-d H:i:s', time()));

// установка режима отображения ошибок
JDEBUG ? error_reporting(E_ALL | E_NOTICE | E_STRICT) : null;

// ГЛАВНОЕ регулярное выражение для проверки логина-имени пользователя
DEFINE('_USERNAME_REGEX', '/^[a-zA-Z0-9_-]{3,25}$/iu');

// секретная фраза для хеширования
DEFINE('JSECRET_CODE', 'i-love-joostina');

DEFINE('JADMIN_SESSION_NAME', md5(JPATH_BASE . md5(JSECRET_CODE) . joosRequest::server('HTTP_USER_AGENT')));

//echo JADMIN_SESSION_NAME;
//die();
// формат для функций вывода времени на сайте
DEFINE('JDATE_FORMAT', '%d %B %Y г. %H:%M'); //Используйте формат PHP-функции strftime

require 'events.php';

/**
*  Авто-загружаемые библиотеки
* joosAutoloader::libraries_load_on_start( array('text', 'session') );
**/
