<?php

// запрет прямого доступа
defined('_JOOS_CORE') or die();

/**
 * joosBenchmark - Библиотека профилирования системы
 * Системная библиотека
 *
 * @version 1.0
 * @package Joostina.Libraries
 * @subpackage Libraries
 * @category Libraries
 * @author Joostina Team <info@joostina.ru>
 * @copyright (C) 2008-2011 Joostina Team
 * @license MIT License http://www.opensource.org/licenses/mit-license.php
 * Информация об авторах и лицензиях стороннего кода в составе Joostina CMS: docs/copyrights
 *
 * */
class joosBenchmark {

	/**
	 * Внутренний массив хранения временных меток
	 * @var array
	 */
	private static $markers = array();

	/**
	 * Установка точки рассчета времени
	 * @param string $name название точки рассчера
	 */
	public static function mark($name) {
		self::$markers[$name] = microtime();
	}

	/**
	 * Рассчет времени между двёмя точками рассчета
	 * @param string $mark1 название первой точки рассчета
	 * @param string $mark2 название второй точки рассчета
	 * @return float разница во времени между первой и второй точками рассчета в милисекундах
	 */
	public static function elapsed_time($mark1, $mark2) {
		return self::$markers[$mark2] - self::$markers[$mark1];
	}

	/**
	 * Очистка всех данных о внутренних точках рассчате и их значениях
	 */
	public static function clear() {
		self::$markers = array();
	}

}
