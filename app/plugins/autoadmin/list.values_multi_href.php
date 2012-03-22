<?php

// запрет прямого доступа
defined('_JOOS_CORE') or die();

/**
 * Расширение joosAutoadmin для вывода строки значений из нескольких элементов объекта в виде ссылки на редактирование этого объекта
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
class autoadminListValuesMultiHref {

	public static function render(joosModel $obj, array $element_param, $key, $value, stdClass $values, $option) {

		if (!isset($element_param['html_table_element_param']['format'])) {
			throw new joosException('Для поля не указана строка форматирования вывода format');
		}

		$format = $element_param['html_table_element_param']['format'];

		$href_title = strtr($format, (array) $values);

		return '<a href="index2.php?option=' . $option . ( joosAutoadmin::$model ? '&model=' . joosAutoadmin::$model : '' ) . '&task=edit&' . $obj->get_key_field() . '=' . $values->{$obj->get_key_field()} . '">' . $href_title . '</a>';
	}

}