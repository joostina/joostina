<?php

// запрет прямого доступа
defined('_JOOS_CORE') or die();

class autoadminEditTextArea {

	public static function render($element_param, $key, $value, $obj_data, $params, $tabs) {
		$element = array();

		$element[] = $params['label_begin'];
		$element[] = forms::label(
						array(
					'for' => $key
						), $element_param['name']);
		$element[] = $params['label_end'];
		$element[] = $params['el_begin'];
		$element[] = forms::textarea(
						array(
					'name' => $key,
					'class' => 'text_area',
					'rows' => (isset($element_param['html_edit_element_param']['rows']) ? $element_param['html_edit_element_param']['rows'] : 10),
					'cols' => (isset($element_param['html_edit_element_param']['cols']) ? $element_param['html_edit_element_param']['cols'] : 40),
					'style' => (isset($element_param['html_edit_element_param']['style']) ? $element_param['html_edit_element_param']['style'] : 'width:100%'),
						), $value);
		$element[] = $params['el_end'];

		return implode("\n", $element);
	}

}