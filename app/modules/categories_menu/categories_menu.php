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

//Текущая категория
$current_category = $object_data['category'];

//Текущая родительская категория (с level=1)
$branch_parent = $current_category->level == 1 ? $current_category : $object_data['path'][1];

$items         = $current_category->get_branch( $branch_parent->lft , $branch_parent->rgt );

//Подключение шаблона модуля
require $module->template_path;
