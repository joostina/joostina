<?php
/**
 * Comments - модуль "Комментарии"
 * Главный исполняемый файл
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
require_once joosCore::path('comments', 'module_helper');

$type = isset($params['type']) ?  $params['type'] : 'latest';

switch($type) {    
    case 'latest':
    default:
    	$comments = commentsHelper::get_latest();
    	$module_title = 'Мнения';
        break;
        
    case 'from_blogs':
    	$comments = commentsHelper::get_latest_from_blogs();
    	$module_title = 'Комментарии';
        break;
        
    case 'from_news':
    	$comments = commentsHelper::get_latest_from_news();
    	$module_title = 'Мнения';
        break;
}   


//Подключение шаблона вывода
$params['template'] ? require_once $params['template'] : null;