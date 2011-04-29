<?php

/**
 */
// запрет прямого доступа
defined('_JOOS_CORE') or die();

//Получение перечня значков
$items = joosDatabase::models('adminQuickicons')->get_list(array('where' => 'state = 1'));

//Подключение шаблона вывода
joosModuleAdmin::render('adminquickicons', array('items' => $items));