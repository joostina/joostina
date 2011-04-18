<?php

// запрет прямого доступа
defined('_JOOS_CORE') or die();

class autoadminEditExtra {

	public static function render($element_param, $key, $value, $obj_data, $params, $tabs) {
		$element = array();

		// скрываем левую колонку с названием поля
		if (!isset($element_param['html_edit_element_param']['hidden_label'])) {
			$element[] = $params['label_begin'];
			$element[] = forms::label(
							array(
						'for' => $key
							), (isset($element_param['html_edit_element_param']['text']) ? $element_param['html_edit_element_param']['text'] : $element_param['name']));

			$element[] = $params['label_end'];
		}
		$element[] = $params['el_begin'];
		$element[] = ( isset($element_param['html_edit_element_param']['call_from']) && is_callable($element_param['html_edit_element_param']['call_from'])) ? call_user_func($element_param['html_edit_element_param']['call_from'], $obj_data) : $datas_for_select;
		$element[] = forms::hidden('extrafields[]', $key);
		$element[] = $params['el_end'];
		return implode("\n", $element);
	}

}