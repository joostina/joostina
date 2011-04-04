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
require_once (JPATH_BASE . DS . 'app' . DS . 'bootstrap.php');
require_once (JPATH_BASE . DS . 'core' . DS . 'admin.root.php');

// класс работы с визуальным редактором
require_once (JPATH_BASE . DS . 'core' . DS . 'editor.php');

database::instance();

// работа с сессиями начинается до создания главного объекта взаимодействия с ядром
session_name(md5(JPATH_SITE));
session_start();

header('Content-type: text/html; charset=UTF-8');

// получение основных параметров
$option = strval(strtolower(mosGetParam($_REQUEST, 'option', '')));
$task = strval(mosGetParam($_REQUEST, 'task', ''));
$no_html = (int) mosGetParam($_REQUEST, 'no_html', 0);
$id = (int) mosGetParam($_REQUEST, 'id', 0);

// mainframe - основная рабочая среда API, осуществляет взаимодействие с 'ядром'
$mainframe = joosMainframe::instance(true);

require_once($mainframe->get_lang_path());
require_once($mainframe->get_lang_path('administrator'));

// запуск сессий панели управления
$my = joosCoreAdmin::init_session_admin($option, $task);

// класс работы с правами пользователей
joosLoader::lib('acl', 'system');
// загружаем набор прав для панели управления
Jacl::init_admipanel();
Jacl::isAllowed('adminpanel') ? null : mosRedirect(JPATH_SITE_ADMIN, 'В доступе отказано');

// страница панели управления по умолчанию
$option = $_REQUEST['option'] = ($option == '') ? 'admin' : $option;

ob_start();
if ($path = $mainframe->getPath('admin')) {
	//Подключаем язык компонента
	if ($mainframe->get_lang_path($option)) {
		include_once($mainframe->get_lang_path($option));
	}
	require_once ($path);

	joosLoader::lib('joiadmin', 'system');
	JoiAdmin::dispatch();
} else {
	?><img src="<?php echo joosConfig::get('admin_icons_path') ?>error.png" border="0" alt="Joostina!" /><?php
}

$_MOS_OPTION['buffer'] = ob_get_contents();
ob_end_clean();

ob_start();

// начало вывода html
if ($no_html == 0) {
	// загрузка файла шаблона

	if (!file_exists(JPATH_BASE . DS . 'app' . DS . 'templates' . DS . JTEMPLATE . DS . 'index.php')) {
		echo _TEMPLATE_NOT_FOUND . ': ' . JTEMPLATE;
	} else {
		//Подключаем язык шаблона
		if ($mainframe->get_lang_path('tmpl_' . JTEMPLATE)) {
			include_once($mainframe->get_lang_path('tmpl_' . JTEMPLATE));
		}
		require_once (JPATH_BASE . DS . 'app' . DS . 'templates' . DS . JTEMPLATE . DS . 'index.php');
	}
} else {
	admin_body();
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
	jd_log_top(sprintf('Завтрачено <b>времени</b>: %s, <b>памяти</b> %s ', round((microtime(true) - $sysstart), 5), $mem_usage));
}



// информация отладки, число запросов в БД
JDEBUG ? jd_get() : null;

// восстановление сессий
if ($task == 'save' || $task == 'apply' || $task == 'save_and_new') {
	$mainframe->initSessionAdmin($option, '');
}

ob_end_flush();