<?php

/**
 * @package   Core
 * @copyright Авторские права (C) 2007-2010 Joostina team. Все права защищены.
 * @license   Лицензия http://www.gnu.org/licenses/gpl-2.0.htm GNU/GPL, или help/license.php
 * Joostina! - свободное программное обеспечение распространяемое по условиям лицензии GNU/GPL
 * Для получения информации о используемых расширениях и замечаний об авторском праве, смотрите файл help/copyright.php.
 */
// Установка флага родительского файла
define('_JOOS_CORE', 1);

// рассчет памяти
function_exists('memory_get_usage') ? define('_MEM_USAGE_START', memory_get_usage()) : null;

// считаем время за которое сгенерирована страница
$sysstart = microtime(true);

// корень файлов панели управления
define('JPATH_BASE_ADMIN', __DIR__);

require_once ( dirname(JPATH_BASE_ADMIN) . DIRECTORY_SEPARATOR . 'core' . DIRECTORY_SEPARATOR . 'joostina.php' );
require_once ( JPATH_BASE . DS . 'core' . DS . 'admin.root.php' );

joosDocument::header();

// работа с сессиями начинается до создания главного объекта взаимодействия с ядром
joosCoreAdmin::start();

// стартуем пользователя
joosCoreAdmin::init_user();

// загружаем набор прав для панели управления
joosAcl::init_admipanel();

joosAcl::isAllowed('adminpanel') ? null : joosRoute::redirect(JPATH_SITE_ADMIN, __('В доступе отказано'));

// страница панели управления по умолчанию
$option = $_REQUEST['option'] = joosRequest::param('option', 'admin');

ob_start();

$file_controller = JPATH_BASE . '/app/components/' . $option . DS . 'controller.admin.' . $option . '.php';
if (is_file($file_controller)) {
	require_once ( $file_controller );
	joosAutoadmin::dispatch();
} else {
	throw new joosException('Не найден предполагаемый файл контроллера :controller', array(':controller' => $file_controller));
}

joosDocument::set_body(ob_get_clean());

ob_start();
// начало вывода html
// загрузка файла шаблона
$template_file = JPATH_BASE . DS . 'app' . DS . 'templates' . DS . JTEMPLATE_ADMIN . DS . 'index.php';
if (file_exists($template_file)) {
	require_once $template_file;
} else {
	throw new joosException('Файл index.php шаблона :template_name не найден', array(':template_name' => JTEMPLATE_ADMIN));
}

// подсчет времени генерации страницы
if (JDEBUG) {
	if (defined('_MEM_USAGE_START')) {
		$mem_usage = joosFile::convert_size(( memory_get_usage() - _MEM_USAGE_START));
	} else {
		$mem_usage = __('недоступно');
	}

	// подсчет времени генерации страницы
	joosDebug::add_top(round(( microtime(true) - $sysstart), 5));
	joosDebug::add_top($mem_usage);

	// информация отладки, число запросов в БД
	joosDebug::get();
}
ob_end_flush();