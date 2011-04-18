<?php

// запрет прямого доступа
defined('_JOOS_CORE') or die();

class autoadminEditStartPane {

	public static function render($element_param, $key, $value, $obj_data, $params, $tabs) {
		$element = array();

		$element[] = $params['tab_wrap_begin'];
		$element[] = $tabs->startPane($key, 1);

		return implode("\n",$element);
	}

}