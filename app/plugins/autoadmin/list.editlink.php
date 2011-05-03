<?php

// запрет прямого доступа
defined('_JOOS_CORE') or die();

/**
 * autoadminListEditlink - расширение joosAutoAdmin для вывода значения объекта как ссылки на его редактирование
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
class autoadminListEditlink {

	public static function render(joosModel $obj, array $element_param, $key, $value, stdClass $values, $option) {
		return '<a href="index2.php?option=' . $option . (joosAutoAdmin::$model ? '&model=' . joosAutoAdmin::$model : '') . '&task=edit&' . $obj->get_key_field() . '=' . $values->{$obj->get_key_field()} . '">' . $value . '</a>';
	}

}