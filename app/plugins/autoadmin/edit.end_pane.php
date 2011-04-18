<?php

// запрет прямого доступа
defined('_JOOS_CORE') or die();

class autoadminEditEndPane {

	public static function render($element_param, $key, $value, $obj_data, $params, $tabs) {
		$element = array();

		$element[] = $tabs->endPane();
		$element[] = $params['tab_wrap_end'];

		return implode("\n",$element);
	}

}