<?php

// запрет прямого доступа
defined('_JOOS_CORE') or die();

/**
 * autoadminEditCurrentUserId - расширение joosAutoadmin для вывода элемента input формы
 * Базовый плагин
 *
 * @version 1.0
 * @package Joostina.Plugins
 * @subpackage Plugins
 * @category joosAutoadmin
 * @author Joostina Team <info@joostina.ru>
 * @copyright (C) 2008-2011 Joostina Team
 * @license MIT License http://www.opensource.org/licenses/mit-license.php
 * Информация об авторах и лицензиях стороннего кода в составе Joostina CMS: docs/copyrights
 *
 * */
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
					'style' => (isset($element_param['html_edit_element_param']['style']) ? $element_param['html_edit_element_param']['style'] : 'width:99%'),
						), $value);
		$element[] = $params['el_end'];

		return implode("\n", $element);
	}

}