<?php

// запрет прямого доступа
defined('_JOOS_CORE') or die();

class autoadminListValue {

	public static function render(joosModel $obj, array $element_param, $key, $value, stdClass $values, $option) {
		return $value;
	}

}