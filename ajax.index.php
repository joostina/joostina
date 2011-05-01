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
define('JPATH_BASE', __DIR__);

// разделитель каталогов
define('DS', DIRECTORY_SEPARATOR);

require_once (JPATH_BASE . DS . 'core' . DS . 'joostina.php');
require_once (JPATH_BASE . DS . 'core' . DS . 'front.root.php');

// инициализация контроллера
joosController::init();

// заполняем некоторые полезные переменные
joosController::$controller = joosRequest::param('option');
joosController::$task = joosRequest::param('task', 'index');

// запускаем аяксовый контроллер
joosController::ajax_run();