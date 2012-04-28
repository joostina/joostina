<?php

// запрет прямого доступа
defined('_JOOS_CORE') or exit();

/**
 * Для вывода форматированной строки даты/времени
 *
 * @version    1.0
 * @package    Plugins
 * @subpackage Autoadmin
 * @author     Joostina Team <info@joostina.ru>
 * @copyright  (C) 2007-2012 Joostina Team
 * @license    MIT License http://www.opensource.org/licenses/mit-license.php
 * Информация об авторах и лицензиях стороннего кода в составе Joostina CMS: docs/copyrights
 *
 * */
class pluginAutoadminTableDateFormat implements joosAutoadminPluginsTable{

	public static function render(joosModel $obj, array $element_param, $key, $value, stdClass $values, $option) {

		if (!isset($element_param['html_table_element_param']['date_format'])) {
			throw new joosException('Для поля не указана строка форматирования date_format');
		}

		$format = $element_param['html_table_element_param']['date_format'];

		$timestamp = strtotime($value);

		return joosDateTime::russian_date($format, $timestamp);
	}

}