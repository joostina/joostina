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

require_once (JPATH_BASE . DS . 'configuration.php');

// предстартовые конфигурации
require_once (JPATH_BASE . DS . 'bootstrap' . DS . 'bootstrap.php');
require_once (JPATH_BASE . DS . 'includes' . DS . 'joostina.php');
require_once (JPATH_BASE . DS . 'includes' . DS . 'route.php');

// загрузка файла русского языка по умолчанию
joosLoader::lang('system');

// инициализация контроллера
joosController::init();

// заполняем некоторые полезные переменные
joosController::$controller = joosRequest::param('option');
joosController::$task = joosRequest::param('task', 'index');

// запускаем аяксовый контроллер
joosController::ajax_run();