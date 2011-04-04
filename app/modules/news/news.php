<?php

/**
 * News - модуль вывода новостей
 * Основной исполняемый файл
 *
 * @version 1.0
 * @package Joostina CMS
 * @subpackage Modules
 * @author JoostinaTeam
 * @copyright (C) 2008-2010 Joostina Team
 * @license see license.txt
 *
 * */
//Запрет прямого доступа
defined('_JOOS_CORE') or die();


//Подключение вспомогательной библиотеки
require_once joosCore::path('news', 'module_helper');

$items = newsHelper::get_latest($params);

//Подключение шаблона модуля	
require $module->template_path;	