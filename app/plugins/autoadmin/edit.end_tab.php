<?php

// запрет прямого доступа
defined('_JOOS_CORE') or die();

/**
 * autoadminEditEndTab - расширение joosAutoAdmin для закрытия таба
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
class autoadminEditEndTab {

	public static function render($element_param, $key, $value, $obj_data, $params, $tabs) {
		$element = array();

		$element[] = $params['wrap_end'];
		$element[] = $tabs->endTab();

		return implode("\n", $element);
	}

}