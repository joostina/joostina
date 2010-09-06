<?php

/**
 * @package Joostina
 * @copyright Авторские права (C) 2007-2010 Joostina team. Все права защищены.
 * @license Лицензия http://www.gnu.org/licenses/gpl-2.0.htm GNU/GPL, или help/license.php
 * Joostina! - свободное программное обеспечение распространяемое по условиям лицензии GNU/GPL
 * Для получения информации о используемых расширениях и замечаний об авторском праве, смотрите файл help/copyright.php.
 */
// Установка флага родительского файла 
define('_VALID_MOS', 1);
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

// подключение главного файла - ядра системы
require_once (JPATH_BASE . DS . 'includes' . DS . 'joostina.php');
require_once (JPATH_BASE . DS . 'components' . DS . 'com_sef' . DS . 'sef.php');
require_once (JPATH_BASE . DS . 'includes' . DS . 'frontend.php');

Jdocument::header();

// mainframe - основная рабочая среда API, осуществляет взаимодействие с 'ядром'
$mainframe = mosMainFrame::getInstance();

$option = $mainframe->option;

// отображение страницы выключенного сайта
if ($mosConfig_offline == 1) {
	require (JPATH_BASE . DS . 'templates' . DS . 'system' . DS . 'offline.php');
}

// отключение ведения сессий на фронте
$mainframe->initSession();

// загрузка файла русского языка по умолчанию
$mosConfig_lang = ($mosConfig_lang == '') ? 'russian' : $mosConfig_lang;
$mainframe->set('lang', $mosConfig_lang);
include_once($mainframe->getLangFile('', $mosConfig_lang));

$my = $mainframe->getUser();

// получение шаблона страницы
define('JTEMPLATE', $mainframe->getTemplate());

// начало буферизации основного содержимого
ob_start();

if ($path = $mainframe->getPath('front')) {

	//Подключаем язык компонента
	$mainframe->getLangFile($option) ? require_once($mainframe->getLangFile($option)) : null;

	require_once ($path);
	mosMainFrame::addLib('joiadmin');
	JoiAdmin::dispatch();
	
} else {
	header('HTTP/1.0 404 Not Found');
	echo _NOT_EXIST;
}

Jdocument::$data['page_body'] = ob_get_contents(); // главное содержимое - стек вывода компонента - mainbody
ob_end_clean();

initGzip();


// отображение предупреждения о выключенном сайте, при входе админа
defined('_ADMIN_OFFLINE') ? include (JPATH_BASE . '/templates/system/offlinebar.php') : null;

ob_start();
// загрузка файла шаблона
if (!file_exists(JPATH_BASE . '/templates/' . JTEMPLATE . '/index.php')) {
	echo _TEMPLATE_WARN . JTEMPLATE;
} else {
	require_once (JPATH_BASE . '/templates/' . JTEMPLATE . '/index.php');
}

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

doGzip();

// запускаем встроенный оптимизатор таблиц
($mosConfig_optimizetables == 1) ? joostina_api::optimizetables() : null;