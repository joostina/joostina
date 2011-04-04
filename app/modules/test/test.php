<?php
/**
 * Test - тестовый модуль
 * Основной исполняемый файл
 * 
 * Доступны следующие переменные:
 * 		$module
 * 		$params
 * 		$object_data
 *
 * @version 1.0
 * @package Joostina CMS
 * @subpackage Modules
 * @author JoostinaTeam
 * @copyright (C) 2008-2010 Joostina Team
 * @license see license.txt
 *
 **/

//Запрет прямого доступа
defined('_JOOS_CORE') or die();

//Подключение вспомогательной библиотеки
require_once joosCore::path('test', 'module_helper');

	_xdump($module);
	_xdump($params);
	_xdump($object_data);
	
	require $module->template_path;	


