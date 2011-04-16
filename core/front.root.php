<?php

/**
 * @package Joostina
 * @copyright Авторские права (C) 2007-2010 Joostina team. Все права защищены.
 * @license Лицензия http://www.gnu.org/licenses/gpl-2.0.htm GNU/GPL, или help/license.php
 * Joostina! - свободное программное обеспечение распространяемое по условиям лицензии GNU/GPL
 * Для получения информации о используемых расширениях и замечаний об авторском праве, смотрите файл help/copyright.php.
 */
// запрет прямого доступа
defined('_JOOS_CORE') or die();

joosLoader::admin_model('modules');

function sefRelToAbs($string, $ext = false, $route_name = false) {

	JDEBUG ? joosDebug::add('Избавься наконец от sefRelToAbs!!!') : null;

	if (!$route_name) {
		return $string;
	}
	$string = str_replace('index.php?', '', $string);
	parse_str($string, $arr);
	$route = joosRoute::instance();

	return $route->url_for($route_name, $arr);
}

// TODO, неправильно, но пока тут
joosRoute::instance();