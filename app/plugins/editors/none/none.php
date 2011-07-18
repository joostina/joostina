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

class noneEditor {

	public static function init() {

		joosHtml::load_jquery();

		joosDocument::instance()
				->add_js_file(JPATH_SITE . '/media/js/jquery.plugins/jquery.insertatcaret.js');
	}

	/**
	 * Не визуальный редактор - отображение редактора
	 * @param string - Название области редактора
	 * @param string - Поле содержимого
	 * @param string - Название поля формы
	 * @param string - Ширина области редактора
	 * @param string - Высота области редактора
	 * @param int - Число столбцов области редактора
	 * @param int - Число строк области редактора
	 */
	public static function display($name, $content, $hiddenField, $width, $height, $col, $row) {
		return sprintf('<textarea name="%s" id="%s" cols="%s" rows="%s" style="width:%s;height:%s;">%s</textarea>', $hiddenField, $hiddenField, $col, $row, $width, $height, $content);
	}

	/**
	 * Не визуальный редактор - копирование содержимого редактора в поле формы
	 * @param string - Название области редактора
	 * @param string - Название поля формы
	 */
	public static function get_content($name, $hiddenField) {

	}

	public static function insert_content($field_name, $content) {
		return sprintf("$('#%s').insertAtCaret('%s');", $field_name, $content);
	}

}