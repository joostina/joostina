<?php

// запрет прямого доступа
defined('_JOOS_CORE') or die();

class autoadminEditH3 {

	public static function render($element_param, $key, $value, $obj_data, $params, $tabs) {
		$element = array();

		$element[] = $params['label_begin'];
		$element[] = $params['label_end'];
		$element[] = $params['el_begin'];
		$element[] = '<h3>' . $element_param['name'] . '</h3>';
		$element[] = $params['el_end'];

		return implode("\n", $element);
	}

}