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
require_once joosCore::path('forum_latest', 'module_helper');

$params['url'] = 'http://www.joostina.ru/rss.xml';

$items = forum_latestHelper::get_latest($params);

//Подключение шаблона вывода
$params['template'] ? require_once $params['template'] : null;