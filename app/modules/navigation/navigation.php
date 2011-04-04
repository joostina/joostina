<?php
/**
 * Navigation - модуль меню
 * Основной исполняемый файл
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

//Подклчение вспомагательной библиотеки
require_once joosCore::path('navigation', 'module_helper');

//Получение дерева пунктов меню
$menu_items = navigationHelper::get_items();

//Подключение шаблона вывода
$module->template_path ? require_once $module->template_path : null;