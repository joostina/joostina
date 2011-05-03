<?php

// запрет прямого доступа
defined('_JOOS_CORE') or die();

/**
 * autoadminEditStartTab - расширение joosAutoAdmin для вывода элемента начала таба
 * Базовый плагин
 *
 * @version 1.0
 * @package Joostina.Plugins
 * @subpackage Plugins
 * @category joosAutoAdmin
 * @author Joostina Team <info@joostina.ru>
 * @copyright (C) 2008-2011 Joostina Team
 * @license MIT License http://www.opensource.org/licenses/mit-license.php
 * Информация об авторах и лицензиях стороннего кода в составе Joostina CMS: docs/copyrights
 *
 * */
class autoadminEditStartTab {

	public static function render($element_param, $key, $value, $obj_data, $params, $tabs) {
		$element = array();

		$element[] = $tabs->startTab($element_param['name'], $key, 1);
		$element[] = $params['wrap_begin'];

		return implode("\n", $element);
	}

}