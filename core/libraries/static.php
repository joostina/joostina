<?php

//Запрет прямого доступа
defined('_JOOS_CORE') or die();

/**
 * Класс работы со статичными данными внутри приложения
 *
 */
class joosStatic {

	/**
	 * Статичная переменная для хранения данных
	 * @var array
	 */
	public static $data = array();

	/**
	 * Установка переменной
	 *
	 * @param string $name - название переменной
	 * @param mixed $value - значение переменной, любой тип
	 */
	public static function set($name, $value) {
		self::$data[$name] = $value;
	}

	/**
	 * Получение сохранённой переменной
	 *
	 * @param string $name - название переменной
	 * @param mixed $default - значение по умолчанию
	 */
	public static function get($name, $default = null) {
		return isset(self::$data[$name]) ? self::$data[$name] : $default;
	}

}
