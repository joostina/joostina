<?php

// запрет прямого доступа
defined('_JOOS_CORE') or die();

class autoadminEditTags {

	public static function render($element_param, $key, $value, $obj_data, $params, $tabs) {
		$element = array();

		$tags = new Tags;

		$element[] = $params['label_begin'];
		$element[] = forms::label(
						array(
					'for' => $key
						), $element_param['name']);
		$element[] = $params['label_end'];
		$element[] = $params['el_begin'];
		$element[] = $tags->display_object_tags_edit($obj_data);
		$element[] = $params['el_end'];


		return implode("\n",$element);
	}

}