<?php

// запрет прямого доступа
defined('_JOOS_CORE') or die();

class autoadminEditStartTab {

	public static function render($element_param, $key, $value, $obj_data, $params, $tabs) {
		$element = array();

		$element[] = $tabs->startTab($element_param['name'], $key, 1);
		$element[] = $params['wrap_begin'];

		return implode("\n",$element);
	}

}