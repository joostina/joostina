<?php
/**
 * @package Joostina
 * @copyright Авторские права (C) 2007-2010 Joostina team. Все права защищены.
 * @license Лицензия http://www.gnu.org/licenses/gpl-2.0.htm GNU/GPL, или help/license.php
 * Joostina! - свободное программное обеспечение распространяемое по условиям лицензии GNU/GPL
 * Для получения информации о используемых расширениях и замечаний об авторском праве, смотрите файл help/copyright.php.
 */

// запрет прямого доступа
defined('_VALID_MOS') or die();



/* @php at koterov dot ru #http://ru.php.net/manual/en/function.json-encode.php#73290 */
function php2js($a=false) {
	if (is_null($a)) return 'null';
	if ($a === false) return 'false';
	if ($a === true) return 'true';
	if (is_scalar($a)) {
		if (is_float($a)) {
			$a = str_replace(",", ".", strval($a));
		}

		static $jsonReplaces = array(array("\\", "/", "\n", "\t", "\r", "\b", "\f", '"'),
		array('\\\\', '\\/', '\\n', '\\t', '\\r', '\\b', '\\f', '\"'));
		return '"' . str_replace($jsonReplaces[0], $jsonReplaces[1], $a) . '"';
	}
	$isList = true;
	for ($i = 0, reset($a); $i < count($a); $i++, next($a)) {
		if (key($a) !== $i) {
			$isList = false;
			break;
		}
	}
	$result = array();
	if ($isList) {
		foreach ($a as $v) $result[] = php2js($v);
		return '[ ' . join(', ', $result) . ' ]';
	}else {
		foreach ($a as $k => $v) $result[] = php2js($k).': '.php2js($v);
		return '{ ' . join(', ', $result) . ' }';
	}
}