<?php

/**
 * @package Joostina
 * @copyright Авторские права (C) 2007-2010 Joostina team. Все права защищены.
 * @license Лицензия http://www.gnu.org/licenses/gpl-2.0.htm GNU/GPL, или help/license.php
 * Joostina! - свободное программное обеспечение распространяемое по условиям лицензии GNU/GPL
 * Для получения информации о используемых расширениях и замечаний об авторском праве, смотрите файл help/copyright.php.
 */
// Установка флага родительского файла
define('_JOOS_CORE', 1);

// разделитель каталогов
define('DS', DIRECTORY_SEPARATOR);

// рассчет памяти
function_exists('memory_get_usage') ? define('_MEM_USAGE_START', memory_get_usage()) : null;


// считаем время за которое сгенерирована страница
$sysstart = microtime(true);

// корень файлов
define('JPATH_BASE', dirname(dirname(__FILE__)));
// корень файлов админкиы
define('JPATH_BASE_ADMIN', dirname(__FILE__));

// подключаем ядро
require_once (JPATH_BASE . DS . 'core' . DS . 'joostina.php');
require_once (JPATH_BASE . DS . 'core' . DS . 'admin.root.php');

joosDatabase::instance();

// работа с сессиями начинается до создания главного объекта взаимодействия с ядром
session_name(md5(JPATH_SITE));
session_start();

header('Content-type: text/html; charset=UTF-8');

// получение основных параметров
// mainframe - основная рабочая среда API, осуществляет взаимодействие с 'ядром'
$mainframe = joosMainframe::instance(true);

// запуск сессий панели управления
$my = joosCoreAdmin::init_session_admin();

// класс работы с правами пользователей
joosLoader::lib('acl', 'system');
// загружаем набор прав для панели управления
Jacl::init_admipanel();
Jacl::isAllowed('adminpanel') ? null : joosRoute::redirect(JPATH_SITE_ADMIN, 'В доступе отказано');

// страница панели управления по умолчанию
$option = $_REQUEST['option'] = joosRequest::param('option', 'admin');

ob_start();
$file_controller = JPATH_BASE . '/app/components/' . $option . DS . 'controller.admin.' . $option . '.php';
if (is_file($file_controller)) {
	require_once ($file_controller);
	joosAutoAdmin::dispatch();
} else {
	throw new joosException(sprintf(__('Не найден предполагаемый файл контроллера %s'), $file_controller));
}

joosCoreAdmin::set_body(ob_get_contents());
ob_end_clean();

ob_start();

// начало вывода html
// загрузка файла шаблона
if (!file_exists(JPATH_BASE . DS . 'app' . DS . 'templates' . DS . JTEMPLATE . DS . 'index.php')) {
	throw new joosException(sprintf(__('Файл index.php шаблона %s не найден'), JTEMPLATE));
} else {
	require_once (JPATH_BASE . DS . 'app' . DS . 'templates' . DS . JTEMPLATE . DS . 'index.php');
}


// подсчет израсходованной памяти
if (defined('_MEM_USAGE_START')) {
	$mem_usage = (memory_get_usage() - _MEM_USAGE_START);
	$mem_usage = sprintf('%0.2f', $mem_usage / 1048576) . ' MB';
} else {
	$mem_usage = 'недоступно';
}

// подсчет времени генерации страницы
if (JDEBUG) {
	joosDebug::add_top(sprintf('Завтрачено <b>времени</b>: %s, <b>памяти</b> %s ', round((microtime(true) - $sysstart), 5), $mem_usage));
}

// информация отладки, число запросов в БД
JDEBUG ? joosDebug::get() : null;

ob_end_flush();