<?php

// запрет прямого доступа
defined('_JOOS_CORE') or die();

class autoadminListExtra {

	private static $datas_for_select = array();


	public static function render(joosDBModel $obj, array $element_param, $key, $value, stdClass $values, $option) {
		return ( isset($element_param['html_table_element_param']['call_from']) && is_callable($element_param['html_table_element_param']['call_from'])) ? call_user_func($element_param['html_table_element_param']['call_from'], $values) : self::$datas_for_select;
	}

}