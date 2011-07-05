<?php

// запрет прямого доступа
defined('_JOOS_CORE') or die();

/**
 * autoadminEditH3 - расширение joosAutoadmin для вывода заголовка в H3
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
class autoadminEditH3 {

	public static function render($element_param, $key, $value, $obj_data, $params, $tabs) {
		$element = array();

		$element[] = $params['label_begin'];
		$element[] = $params['label_end'];
		$element[] = $params['el_begin'];
		$element[] = '<h3>' . $element_param['name'] . '</h3>';
		$element[] = $params['el_end'];

		return implode("\n", $element);
	}

}