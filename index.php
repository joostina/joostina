<?php
/**
 * Frontend - точка входа
 *
 * @package Joostina.Core
 * @author JoostinaTeam
 * @copyright (C) 2008-2011 Joostina Team
 * @license MIT License http://www.opensource.org/licenses/mit-license.php
 * @version SVN: $Id: index.php 232 2011-03-12 12:20:47Z LeeHarvey $
 * Информация об авторах и лицензиях стороннего кода в составе Joostina CMS: docs/copyrights
 */

// Установка флага родительского файла
define('_JOOS_CORE', 1);

// корень файлов
define('JPATH_BASE', dirname(__file__));
// разделитель каталогов
define('DS', DIRECTORY_SEPARATOR);

// рассчет памяти
function_exists('memory_get_usage') ? define('_MEM_USAGE_START', memory_get_usage()) : null;

$mosConfig_time_generate = true;

// считаем время за которое сгенерирована страница
$mosConfig_time_generate ? $sysstart = microtime(true) : null;

// подключение главного файла - ядра системы
require_once (JPATH_BASE . DS . 'core' . DS . 'joostina.php');
// классы работы фронта сайта
require_once (JPATH_BASE . DS . 'core' . DS . 'front.root.php');

// загрузка файла русского языка по умолчанию
//TODO: это страшная вещь! на установку констант тратится тонннна времени. Языковые файлы стоит переписать на INI или массивы
joosLoader::lang('system');

joosController::init();
joosRoute::route();

// mainframe - основная рабочая среда API, осуществляет взаимодействие с 'ядром'
joosMainframe::instance();

// начало буферизации основного содержимого
ob_start();
joosController::run();
// главное содержимое - стек вывода компонента - mainbody
joosDocument::$data['page_body'] = ob_get_contents();
ob_end_clean();

ob_start();
// загрузка файла шаблона
require_once (JPATH_BASE . '/app/templates/' . JTEMPLATE . '/index.php');
joosDocument::$data['html_body'] = ob_get_contents();
ob_end_clean();

echo joosDocument::$data['html_body'];

// вывод лога отладки
if (JDEBUG) {

    // подсчет израсходованной памяти
    if (defined('_MEM_USAGE_START')) {
        $mem_usage = (memory_get_usage() - _MEM_USAGE_START);
        $mem_usage = sprintf('%0.4f', $mem_usage / 1048576) . ' MB';
    } else {
        $mem_usage = 'недоступно';
    }

    // подсчет времени генерации страницы
    joosDebug::add_top(sprintf('Завтрачено <b>времени</b>: %s, <b>памяти</b> %s ', round((microtime(true) - $sysstart), 5), $mem_usage));

    // вывод итогового лога отлатчика
	joosDebug::get();
}
