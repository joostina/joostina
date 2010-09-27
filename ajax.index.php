<?php
/**
 * @package Joostina
 * @copyright Авторские права (C) 2007-2010 Joostina team. Все права защищены.
 * @license Лицензия http://www.gnu.org/licenses/gpl-2.0.htm GNU/GPL, или help/license.php
 * Joostina! - свободное программное обеспечение распространяемое по условиям лицензии GNU/GPL
 * Для получения информации о используемых расширениях и замечаний об авторском праве, смотрите файл help/copyright.php.
 */

// Установка флага, что это - родительский файл
define('_JOOS_CORE', 1);
// корень файлов
define('JPATH_BASE', dirname(__file__));
// разделитель каталогов
define('DS', DIRECTORY_SEPARATOR);

require_once ('./configuration.php');
// live_site
define('JPATH_SITE', $mosConfig_live_site);
// для совместимости
$mosConfig_absolute_path = JPATH_BASE;

require_once (JPATH_BASE . DS . 'includes' . DS . 'joostina.php');
require_once (JPATH_BASE . DS . 'includes' . DS . 'route.php');

// отображение состояния выключенного сайта
if ($mosConfig_offline == 1) {
	echo 'syte-offline';
	exit();
}


// автоматическая перекодировка в юникод, по умолчанию активно
$option = strval(strtolower(mosGetParam($_REQUEST, 'option', '')));
$task = strval(mosGetParam($_REQUEST, 'task', ''));

$commponent = str_replace('com_', '', $option);

// mainframe - основная рабочая среда API, осуществляет взаимодействие с 'ядром'
$mainframe = mosMainFrame::getInstance();
$mainframe->initSession();

// загрузка файла русского языка по умолчанию
if ($mosConfig_lang == '') {
	$mosConfig_lang = 'russian';
}
$mainframe->set('lang', $mosConfig_lang);
include_once ($mainframe->getLangFile());

header("Content-type: text/html; charset=utf-8");
header("Cache-Control: no-cache, must-revalidate ");

// проверяем, какой файл необходимо подключить, данные берутся из пришедшего GET запроса
if (file_exists(JPATH_BASE . "/components/$commponent/$commponent.ajax.php")) {
	include_once (JPATH_BASE . "/components/$commponent/$commponent.ajax.php");
	Jcontroller::run();
} else {
	die('error-1');
}
