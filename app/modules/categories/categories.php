<?php

/**
 * News - модуль вывода новостей
 * Основной исполняемый файл
 *
 * @version    1.0
 * @package    Joostina CMS
 * @subpackage modelModules
 * @author     JoostinaTeam
 * @copyright  (C) 2007-2012 Joostina Team
 * @license    see license.txt
 *
 * */
//Запрет прямого доступа
defined( '_JOOS_CORE' ) or die();


//Подключение вспомогательной библиотеки
require_once joosCore::path( 'categories' , 'module_helper' );

$items = helperCategories::get_categories( $params );

//Подключение шаблона модуля
require $module->template_path;