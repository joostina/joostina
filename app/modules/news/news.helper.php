<?php

/**
 * News - модуль вывода новостей
 * Вспомогательный класс
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

class newsHelper {

	public static function get_latest(array $params) {

		$limit = isset($params['limit']) ? $params['limit'] : 3;

		$news = new News;
		return $news->get_list(
				array(
					'select' => "id, title, created_at",
					'where' => 'state=1',
					'limit' => $limit,
					'order' => 'id DESC'
				)
		);
	}

}