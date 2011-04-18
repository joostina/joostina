<?php

// запрет прямого доступа
defined('_JOOS_CORE') or die();

class autoadminEditJson {

	public static function render($element_param, $key, $value, $obj_data, $params, $tabs) {
		$element = array();


		$_add_data = isset($element_param['html_edit_element_param']['call_params']) ? $element_param['html_edit_element_param']['call_params'] : null;
		$data = (isset($element_param['html_edit_element_param']['call_from']) && is_callable($element_param['html_edit_element_param']['call_from'])) ? call_user_func($element_param['html_edit_element_param']['call_from'], $obj_data, $_add_data) : null;

		if (!$data) {
			break;
		}

		$main_key = $key;
		$values = $obj_data->$main_key;

		foreach ($data as $key => $field) {
			if (isset($field['editable']) && $field['editable'] == true) {
				$v = isset($values[$key]) ? $values[$key] : '';
				$element[] = joosAutoAdmin::get_edit_html_element($field, $main_key . '[' . $key . ']', $v, $obj_data, $params, $tabs);
			}
		}

		return implode("\n", $element);
	}

}