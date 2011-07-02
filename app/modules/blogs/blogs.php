<?php

/**
 * Blogs - модуль вывода записей из блога
 * Основной исполняемый файл
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

$type = isset($params['type']) ? $params['type'] : 'frontpage';

switch ($type) {

	case 'frontpage':
	default:
		$items = modulesHelperBlogs::get_latest(5);
		break;

	case 'by_user':
		$items = modulesHelperBlogs::get_by_user($params);
		break;
}
