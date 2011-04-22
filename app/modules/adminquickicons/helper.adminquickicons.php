<?php

/**

 */
// запрет прямого доступа
defined('_JOOS_CORE') or die();

class adminquickiconsHelper {

	public static function get_items() {
		$obj = new Quickicons;
		return $obj->get_list(array('where' => 'state = 1'));
	}

}