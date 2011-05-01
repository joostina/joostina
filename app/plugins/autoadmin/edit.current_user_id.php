<?php

// запрет прямого доступа
defined('_JOOS_CORE') or die();

class autoadminEditCurrentUserId {

	public static function render($element_param, $key, $value, $obj_data, $params, $tabs) {
		return forms::hidden($key, joosCore::user()->id);
	}

}