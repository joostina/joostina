<?php

// запрет прямого доступа
defined('_JOOS_CORE') or die();

class autoadminListEditlink {

	public static function render(joosDBModel $obj, array $element_param, $key, $value, stdClass $values, $option) {
		return '<a href="index2.php?option=' . $option . (joosAutoAdmin::$model ? '&model=' . joosAutoAdmin::$model : '') . '&task=edit&' . $obj->get_key_field() . '=' . $values->{$obj->get_key_field()} . '">' . $value . '</a>';
	}

}