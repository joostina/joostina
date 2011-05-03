<?php

// запрет прямого доступа
defined('_JOOS_CORE') or die();

class autoadminListStatuschanger {

	public static function render(joosModel $obj, array $element_param, $key, $value, stdClass $values, $option) {

		$element_param = array_merge_recursive($element_param, array('html_table_element_param' => array(
				'statuses' => array(
					0 => __('Скрыто'),
					1 => __('Опубликовано')
				),
				'images' => array(
					0 => 'publish_x.png',
					1 => 'publish_g.png',
				)
				)));

		$images = isset($element_param['html_table_element_param']['images'][$value]) ? $element_param['html_table_element_param']['images'][$value] : 'error.png';
		$text = isset($element_param['html_table_element_param']['statuses'][$value]) ? $element_param['html_table_element_param']['statuses'][$value] : 'ERROR';

		return '<img class="img-mini-state" src="' . joosConfig::get('admin_icons_path') . $images . '" id="img-pub-' . $values->id . '" obj_id="' . $values->id . '" obj_key="' . $key . '" alt="' . $text . '" />';
	}

}