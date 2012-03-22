<?php

// запрет прямого доступа
defined('_JOOS_CORE') or die();

/**
 * Расширение joosAutoadmin для вывода выпадающего списка
 *
 * @version    1.0
 * @package    Plugins
 * @subpackage joosAutoadmin
 * @category   joosAutoadmin
 * @author     Joostina Team <info@joostina.ru>
 * @copyright  (C) 2007-2012 Joostina Team
 * @license    MIT License http://www.opensource.org/licenses/mit-license.php
 * Информация об авторах и лицензиях стороннего кода в составе Joostina CMS: docs/copyrights
 *
 * */
class autoadminEditOption {

	public static function render($element_param, $key, $value, $obj_data, $params, $tabs) {
		$element = array();

		$element[] = $params['label_begin'];
		$element[] = forms::label(array('for' => $key), ( isset($element_param['html_edit_element_param']['text']) ? $element_param['html_edit_element_param']['text'] : $element_param['name']));

		$element[] = $params['label_end'];
		$element[] = $params['el_begin'];
		$datas_for_select = array();
		$datas_for_select = ( isset($element_param['html_edit_element_param']['call_from']) && is_callable($element_param['html_edit_element_param']['call_from']) ) ? call_user_func($element_param['html_edit_element_param']['call_from'], $value, $obj_data) : $datas_for_select;
		$datas_for_select = isset($element_param['html_edit_element_param']['options']) ? $element_param['html_edit_element_param']['options'] : $datas_for_select;

		$element[] = forms::dropdown(array('name' => $key,
					'options' => $datas_for_select,
					'selected' => $value));

		$element[] = $params['el_end'];

		return implode("\n", $element);
	}

}