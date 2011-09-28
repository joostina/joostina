<?php
/**
 * Breadcrumbs - модуль вывода "хлебных крошек"
 * Основной исполняемый файл
 *
 * @version    1.0
 * @package    Joostina CMS
 * @subpackage Modules
 * @author     JoostinaTeam
 * @copyright  (C) 2008-2010 Joostina Team
 * @license    see license.txt
 *
 **/

//Запрет прямого доступа
defined( '_JOOS_CORE' ) or die();

//Получаем массив с элементами навигации
$items = joosBreadcrumbs::instance()->get_breadcrumbs_array();

( count( $items ) > 0 && $module->template_path ) ? require_once $module->template_path : null;