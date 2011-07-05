<?php

// запрет прямого доступа
defined('_JOOS_CORE') or die();

/**
 * autoadminListEditlink - расширение joosAutoadmin для вывода значения через вызов сторонней функции
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
class autoadminListExtra {

	private static $datas_for_select = array();

	public static function render(joosModel $obj, array $element_param, $key, $value, stdClass $values, $option) {
		return ( isset($element_param['html_table_element_param']['call_from']) && is_callable($element_param['html_table_element_param']['call_from'])) ? call_user_func($element_param['html_table_element_param']['call_from'], $values) : self::$datas_for_select;
	}

}