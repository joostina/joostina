<?php

// запрет прямого доступа
defined('_JOOS_CORE') or die();

class autoadminEditEdit {

	public static function render($element_param, $key, $value, $obj_data, $params, $tabs) {

		$element = array();

		$element[] = $params['label_begin'];
		$element[] = forms::label(
						array(
					'for' => $key
						), $element_param['name']);
		$element[] = $params['label_end'];
		$element[] = $params['el_begin'];
		$element[] = forms::input(
						array(
					'name' => $key,
					'class' => 'text_area',
					'size' => 100,
					'style' => (isset($element_param['html_edit_element_param']['style']) ? $element_param['html_edit_element_param']['style'] : 'width:100%'),
						), $value);
		$element[] = $params['el_end'];

		return implode("\n",$element);
	}

}