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
// корень файлов
define('JPATH_BASE', dirname(__FILE__));
// разделитель каталогов
define('DS', DIRECTORY_SEPARATOR);

// рассчет памяти
function_exists('memory_get_usage') ? define('_MEM_USAGE_START', memory_get_usage()) : null;

// подключение файла конфигурации
require_once (JPATH_BASE . DS . 'configuration.php');

// считаем время за которое сгенерирована страница
$mosConfig_time_generate ? $sysstart = microtime(true) : null;

// live_site
define('JPATH_SITE', $mosConfig_live_site);

require_once (JPATH_BASE . DS . 'includes' . DS . 'extraroute.php');
// подключение главного файла - ядра системы
require_once (JPATH_BASE . DS . 'includes' . DS . 'joostina.php');
//require_once (JPATH_BASE . DS . 'includes' . DS . 'route.php');
require_once (JPATH_BASE . DS . 'includes' . DS . 'frontend.php');

Jcontroller::init();

// mainframe - основная рабочая среда API, осуществляет взаимодействие с 'ядром'
$mainframe = mosMainFrame::getInstance();
$mainframe->initSession();

// загрузка файла русского языка по умолчанию
include_once($mainframe->getLangFile('', JLANG));

//$my = $mainframe->getUser();
$my = new User();


// начало буферизации основного содержимого
ob_start();

Jcontroller::run();

Jdocument::$data['page_body'] = ob_get_contents(); // главное содержимое - стек вывода компонента - mainbody
ob_end_clean();

ob_start();


// отображение предупреждения о выключенном сайте, при входе админа
defined('_ADMIN_OFFLINE') ? include (JPATH_BASE . '/templates/system/offlinebar.php') : null;

ob_start();
// загрузка файла шаблона
require_once (JPATH_BASE . '/templates/' . JTEMPLATE . '/index.php');

Jdocument::$data['html_body'] = ob_get_contents();
ob_end_clean();

echo Jdocument::$data['html_body'];

unset($template_body, $mainframe, $my, $_MOS_OPTION, $database);

// вывод лога отладки
if (JDEBUG) {

    // подсчет времени генерации страницы
    echo $mosConfig_time_generate ? '<div id="time_gen">' . round((microtime(true) - $sysstart), 5) . '</div>' : null;

    if (defined('_MEM_USAGE_START')) {
        $mem_usage = (memory_get_usage() - _MEM_USAGE_START);
        jd_log_top('<b>' . _SCRIPT_MEMORY_USING . ':</b> ' . sprintf('%0.2f', $mem_usage / 1048576) . ' MB');
    }
    jd_get();
}

ob_end_flush();

// запускаем встроенный оптимизатор таблиц
($mosConfig_optimizetables == 1) ? joostina_api::optimizetables() : null;