<?php

/**

 *
 * */
//Запрет прямого доступа
defined('_JOOS_CORE') or die();

joosLoader::model('faq');

//Подключение шаблона модуля	
require $module->template_path;	