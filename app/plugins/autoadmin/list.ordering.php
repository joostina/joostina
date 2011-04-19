<?php

// запрет прямого доступа
defined('_JOOS_CORE') or die();

class autoadminListOrdering {

	public static function render(joosDBModel $obj, array $element_param, $key, $value, stdClass $values, $option) {
		return '<img src="' . joosConfig::get('admin_icons_path') . '/cursor_drag_arrow.png" alt="'.__('Переместить').'" />';
	}

}