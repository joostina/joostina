<?php
/**

 */
// запрет прямого доступа
defined('_JOOS_CORE') or die();

//Подклчение вспомагательной библиотеки
require_once joosCore::path('adminmenu', 'module_helper');

//Получение дерева пунктов меню
$menu_items = adminmenuHelper::get_items();

//Подключение шаблона вывода
require_once $module->template_path;