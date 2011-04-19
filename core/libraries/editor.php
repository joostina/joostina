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

/**
 * Общий класс подключения редакторов
 */
class joosEditor {

	/**
	 * Название редактора
	 * Должно совпадать с каталогом plugins/editors/$editor/$editor.php
	 * @var string
	 */
	private static $editor = 'none';
	/**
	 * Массив данных о проинициализированных редакторах
	 * Исключает повторную инициализацию скриптов и подключения файлов
	 * @var array
	 */
	private static $init = array();

	/**
	 * Инициализация визуального редактора
	 * В классе могут быть объявлены подключения JS и CSS файлов, инициализация переменных
	 */
	public static function init() {}

	/**
	 * Инициализация редактора
	 * @param string $field_name название поля с текстом, на котором должен быть инициализирован редактоор
	 * @param string $content текст содержимого редактора
	 * @param array $params массив настроек редактора
	 * @return mixed возвращает код инициализации редактора, либо полный код инициализации и комплекта необходимого HTML кода
	 */
	public static function display($field_name, $content, array $params = array()) {

		$hiddenField = isset($params['hiddenField']) ? $params['hiddenField'] : $field_name;
		$width = isset($params['width']) ? $params['width'] : '100%';
		$height = isset($params['height']) ? $params['height'] : 300;
		$col = isset($params['col']) ? $params['col'] : 10;
		$row = isset($params['row']) ? $params['row'] : 5;

		// попытаемся переопределить редактор если это указано в параметрах
		self::$editor = isset($params['editor']) ? $params['editor'] : self::$editor;

		// файл используемого визуального редактора
		$editor_file = JPATH_BASE . DS . 'app' . DS . 'plugins' . DS . 'editors' . DS . self::$editor . DS . self::$editor . '.php';

		if (is_file($editor_file)) {
			require_once $editor_file;
		} else {
			return sprintf('<!-- %s jooEditor::' . self::$editor . ' -->', __('Не найден редактор:'));
		}

		$editor_class = self::$editor . 'Editor';

		// инициализация редактора
		(!isset(self::$init[self::$editor])) ? call_user_func_array("$editor_class::init", array($params)) : null;

		self::$init[self::$editor] = true;

		// непосредственно область редактора
		return call_user_func_array("$editor_class::display", array($field_name, $content, $hiddenField, $width, $height, $col, $row, $params));
	}

	/**
	 * Получение содержимого редактора
	 * @param string $field_name название поля редактора
	 * @param array $params массив дополнительных параметров получения содержимого
	 * @return mixed js код получения содержмиого, либо js код и комплект необходимого HTML кода
	 */
	public static function get_content($field_name, array $params = array()) {
		$editor_class = self::$editor . 'Editor';
		return call_user_func_array("$editor_class::get_content", array($field_name, $params));
	}

	/**
	 * Установка полного содержимого редактора через JS.
	 * Необходимо для полнойенной работы Ajax функций
	 * @param string $field_name название поля на котором инициализирован редактор
	 * @param string $content текст, устанавливаемый в редактор
	 * @return mixed js код получения содержмиого, либо js код и комплект необходимого HTML кода
	 */
	public static function set_content($field_name, $content) {
		$editor_class = self::$editor . 'Editor';
		return call_user_func_array("$editor_class::set_content", array($field_name, $content));
	}

	/**
	 * Установка содержимого редактора в определённое место (выделеноое или под курсором)
	 * @param string $field_name название поля на котором инициализирован редактор
	 * @param string $content текст, устанавливаемый в редактор
	 * @return mixed js код получения содержмиого, либо js код и комплект необходимого HTML кода
	 */
	public static function insert_content($field_name, $content) {
		$editor_class = self::$editor . 'Editor';
		return call_user_func_array("$editor_class::set_content", array($field_name, $content));
	}

}