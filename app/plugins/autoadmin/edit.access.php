<?php

// запрет прямого доступа
defined('_JOOS_CORE') or die();

class autoadminEditAccess {

	public static function render($element_param, $key, $value, $obj_data, $params, $tabs) {
		$element = array();
		$data = (isset($element_param['html_edit_element_param']['call_from']) && is_callable($element_param['html_edit_element_param']['call_from'])) ? call_user_func($element_param['html_edit_element_param']['call_from'], $obj_data) : null;

		if (!$data) {
			break;
		}

		$access = new Access;
		$access->fill_rights($data['section'], $data['subsection']);


		$element[] = $params['label_begin'];
		$element[] = forms::label(
						array(
					'for' => $key
						), (isset($element_param['html_edit_element_param']['text']) ? $element_param['html_edit_element_param']['text'] : $element_param['name']));

		$element[] = $params['label_end'];
		$element[] = $params['el_begin'];
		$element[] = $access->draw_config_table();
		$element[] = $params['el_end'];

		return implode("\n",$element);
	}

}