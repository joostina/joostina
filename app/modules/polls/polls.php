<?php

/**

 *
 * */
//Запрет прямого доступа
defined('_JOOS_CORE') or die();


//Подключение вспомогательной библиотеки
require_once joosCore::path('polls', 'module_helper');

$poll = pollsHelper::get_poll($params);

if (!$poll) {
    return false;
}

//Подключение шаблона модуля	
require $module->template_path;	