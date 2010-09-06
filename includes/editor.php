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

class jooEditor {

	public static $editor = 'none';

	public static function editor($field_name, $content, array $params) {

		$hiddenField = isset($params['hiddenField']) ? $params['hiddenField'] : $field_name;
		$width = isset($params['width']) ? $params['width'] : 400;
		$height = isset($params['height']) ? $params['height'] : 300;
		$col = isset($params['col']) ? $params['col'] : 10;
		$row = isset($params['row']) ? $params['row'] : 5;

		// попытаемся переопределить редактор если это указано в параметрах
		self::$editor = isset($params['editor']) ? $params['editor'] : self::$editor;

		// файл используемого визуального редактора
		$editor_file = JPATH_BASE . DS . 'plugins' . DS . 'editors' . DS . self::$editor . DS . self::$editor . '.php';

		if (is_file($editor_file)) {
			require_once $editor_file;
		} else {
			return '<!-- jooEditor::' . self::$editor . ' - none -->';
		}

		$editor_class = self::$editor . 'Editor';

		// инициализация редактора
		call_user_func_array("$editor_class::init", array($params));

		// непосредственно область редактора
		return call_user_func_array("$editor_class::getEditorArea", array($field_name, $content, $hiddenField, $width, $height, $col, $row, $params));
	}

	public static function getContents($field_name, $params = array()) {
		$editor_class = self::$editor . 'Editor';
		return call_user_func_array("$editor_class::getContents", array($field_name, $params));
	}

}