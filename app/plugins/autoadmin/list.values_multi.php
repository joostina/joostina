<?php

// запрет прямого доступа
defined('_JOOS_CORE') or die();

/**
 * autoadminListValuesMulti - расширение joosAutoadmin для вывода строки значений из нескольких элементов объекта
 * Базовый плагин
 *
 * @version    1.0
 * @package    Joostina.Plugins
 * @subpackage Plugins
 * @category   joosAutoadmin
 * @author     Joostina Team <info@joostina.ru>
 * @copyright  (C) 2007-2012 Joostina Team
 * @license    MIT License http://www.opensource.org/licenses/mit-license.php
 * Информация об авторах и лицензиях стороннего кода в составе Joostina CMS: docs/copyrights
 *
 * */
class autoadminListValuesMulti {

	public static function render(joosModel $obj, array $element_param, $key, $value, stdClass $values, $option) {
		
		if (!isset($element_param['html_table_element_param']['format'])) {
			throw new joosException('Для поля не указана строка форматирования вывода format');
		}

		$format = $element_param['html_table_element_param']['format'];

		return strtr($format,  (array) $values );
	}

}