<?php

// запрет прямого доступа
defined('_JOOS_CORE') or die();

class autoadminListStatuschanger {

	public static function render(joosModel $obj, array $element_param, $key, $value, stdClass $values, $option) {

		$images = isset($element_param['html_table_element_param']['images'][$value]) ? $element_param['html_table_element_param']['images'][$value] : 'error.png';
		$text = isset($element_param['html_table_element_param']['statuses'][$value]) ? $element_param['html_table_element_param']['statuses'][$value] : 'ERROR';

		return '<img class="img-mini-state" src="' . joosConfig::get('admin_icons_path') . $images . '" id="img-pub-' . $values->id . '" obj_id="' . $values->id . '" obj_key="' . $key . '" alt="' . $text . '" />';
	}

}