<?php

// запрет прямого доступа
defined('_JOOS_CORE') or die();

/**
 * autoadminEditCurrentUserId - расширение joosAutoadmin для вывода скрытого элемента формы содержащего ID текущего вторизованного пользователя
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
class autoadminEditCurrentUserId {

	public static function render($element_param, $key, $value, $obj_data, $params, $tabs) {
		return forms::hidden($key, joosCore::user()->id);
	}

}