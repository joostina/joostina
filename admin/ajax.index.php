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

// корень файлов панели управления
define('JPATH_BASE_ADMIN', __DIR__);

require_once ( dirname(JPATH_BASE_ADMIN) . DIRECTORY_SEPARATOR . 'core' . DIRECTORY_SEPARATOR . 'joostina.php');
require_once (JPATH_BASE . DS . 'app' . DS . 'bootstrap.php');
require_once (JPATH_BASE . DS . 'core' . DS . 'admin.root.php');

joosDocument::header();

// работа с сессиями начинается до создания главного объекта взаимодействия с ядром
joosCoreAdmin::start();

// стартуем пользователя
joosCoreAdmin::init_user();

// загружаем набор прав для панели управления
joosAcl::init_admipanel();

$my = joosCore::user();

if (joosAcl::isDeny('adminpanel')) {
	echo json_encode(array('code' => 500, 'message' => 'Ошибка прав доступа'));
	die();
}

if (!$my->id) {
	echo json_encode(array('code' => 500, 'message' => 'Ошибка авторизации'));
	die();
}

$option = joosRequest::param('option');

ob_start();

// файл обработки Ajax запросов конкретного компонента
$file_com = JPATH_BASE . DS . 'app' . DS . 'components' . DS . $option . DS . 'controller.admin.' . $option . '.ajax.php';

// проверяем, какой файл необходимо подключить, данные берутся из пришедшего GET запроса
if (file_exists($file_com)) {
	include_once ($file_com);
	joosAutoadmin::dispatch_ajax();
} else {
	echo json_encode(array('code' => 500, 'message' => sprintf('Файл контроллера для %s не найден', $file_com)));
	die();
}

ob_end_flush();