<?php

/**

 *
 * */
//Запрет прямого доступа
defined('_JOOS_CORE') or die();

class pollsHelper {

	public static function get_poll(array $params) {

		$id = isset($params['id']) ? $params['id'] : 1;

		$poll = new Polls;

		if (!$poll->load($id)) {
			return false;
		} else {
			return $poll;
		}
	}

}