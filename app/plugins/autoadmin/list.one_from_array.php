<?php

// запрет прямого доступа
defined('_JOOS_CORE') or die();

class autoadminListOneFromArray {

	private static $element_datas = array();

	public static function render(joosDBModel $obj, array $element_param, $key, $value, stdClass $values, $option) {
		$datas_for_select = array();

		// избавления из от множества запросов
		if (!isset(self::$element_datas[$key])) {
			// сохраняем полученные значения в статичном мессиве
			self::$element_datas[$key] = (isset($element_param['html_table_element_param']['call_from']) && is_callable($element_param['html_table_element_param']['call_from'])) ? call_user_func($element_param['html_table_element_param']['call_from']) : $datas_for_select;
		}

		$datas_for_select = self::$element_datas[$key];

		$datas_for_select = isset($element_param['html_table_element_param']['options']) ? $element_param['html_table_element_param']['options'] : $datas_for_select;
		return isset($datas_for_select[$value]) ? $datas_for_select[$value] : $value;
	}

}