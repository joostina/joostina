<?php

// запрет прямого доступа
defined('_JOOS_CORE') or die();

class autoadminEditEndTab {

	public static function render($element_param, $key, $value, $obj_data, $params, $tabs) {
		$element = array();

		$element[] = $params['wrap_end'];
		$element[] = $tabs->endTab();

		return implode("\n",$element);
	}

}