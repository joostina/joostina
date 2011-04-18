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
// корень файлов
define('JPATH_BASE', dirname(dirname(__FILE__)));
// корень файлов админкиы
define('JPATH_BASE_ADMIN', dirname(__FILE__));

require_once (JPATH_BASE . DS . 'core' . DS . 'joostina.php');
require_once (JPATH_BASE . DS . 'app' . DS . 'bootstrap.php');
require_once (JPATH_BASE . DS . 'core' . DS . 'admin.root.php');

// создаём сессии
session_name(md5(JPATH_SITE));
session_start();

header("Content-type: text/html; charset=utf-8");
header("Cache-Control: no-cache, must-revalidate ");

$option = joosRequest::param('option');
$task = joosRequest::param('task', 'index');


// mainframe - основная рабочая среда API, осуществляет взаимодействие с 'ядром'
$mainframe = joosMainframe::instance(true);
$my = joosCoreAdmin::init_session_admin($option, $task);

// класс работы с правами пользователей
joosLoader::lib('acl', 'system');
Jacl::init_admipanel();

if (Jacl::isDeny('adminpanel')) {
	echo json_encode(array('error' => 'acl'));
}

if (!$my->id) {
	die('error-my');
}

ob_start();
// файл обработки Ajax запрсоов конкретного компонента
$file_com = JPATH_BASE . DS . 'app' . DS . 'components' . DS . $option . DS . 'controller.admin.' . $option . '.ajax.php';

// проверяем, какой файл необходимо подключить, данные берутся из пришедшего GET запроса
if (file_exists($file_com)) {
	include_once ($file_com);
} else {
	die('error-inc-component');
}

ob_end_flush();