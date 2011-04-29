<?php

/**

 */
// запрет прямого доступа
defined('_JOOS_CORE') or die();

//Подклчение вспомагательной библиотеки
require_once joosCore::path('adminmenu', 'module_helper');

//Получение дерева пунктов меню
$menu_items = adminmenuHelper::get_items();

// рендер
joosModuleAdmin::render('adminmenu', array('menu_items' => $menu_items));