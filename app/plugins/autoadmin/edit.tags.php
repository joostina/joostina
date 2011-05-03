<?php

// запрет прямого доступа
defined('_JOOS_CORE') or die();

/**
 * autoadminEditTags - расширение joosAutoAdmin для вывода элемента тегирования объекта
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
class autoadminEditTags {

	public static function render($element_param, $key, $value, $obj_data, $params, $tabs) {
		$element = array();

		$element[] = $params['label_begin'];
		$element[] = forms::label(
						array(
					'for' => $key
						), $element_param['name']);
		$element[] = $params['label_end'];
		$element[] = $params['el_begin'];

		$tags = new Tags;
		$element[] = $tags->display_object_tags_edit($obj_data);
		$element[] = $params['el_end'];

		return implode("\n", $element);
	}

}