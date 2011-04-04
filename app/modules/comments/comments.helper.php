<?php

/**
 * Comments - модуль "Комментарии"
 * Вспомагательный класс
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

joosLoader::model('comments');

class commentsHelper {

	//Получение последних комментариев   
	public static function get_latest($limit = 5) {
		$comments = new Comments;

		return $comments->get_list(array('group' => 'user_id', 'limit' => 5, 'order' => 'id DESC'), array('comments'), 600);
	}
}