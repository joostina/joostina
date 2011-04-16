<?php
/**
 */
// запрет прямого доступа
defined('_JOOS_CORE') or die();

//Подключение вспомаготельной библиотеки
require_once joosCore::path('adminquickicons', 'module_helper');

//Получение перечня значков
$items = adminquickiconsHelper::get_items();

//Подключение шаблона вывода
require_once $module->template_path;
