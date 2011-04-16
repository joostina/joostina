<?php

/**
 * @package Joostina
 * @subpackage Cache handler
 * @copyright Авторские права (C) 2007-2009 Joostina team. Все права защищены.
 * @license Лицензия http://www.gnu.org/licenses/gpl-2.0.htm GNU/GPL, или help/license.php
 * Joostina! - свободное программное обеспечение распространяемое по условиям лицензии GNU/GPL
 * Для получения информации о используемых расширениях и замечаний об авторском праве, смотрите файл help/copyright.php.
 */
// Check to ensure this file is within the rest of the framework
defined('_JOOS_CORE') or die();

// замер времени потраченного на определённые операции, сюда надо еще добавить просто start-stop
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