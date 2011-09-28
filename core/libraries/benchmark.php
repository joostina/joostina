<?php

// запрет прямого доступа
defined( '_JOOS_CORE' ) or die();

/**
 * joosBenchmark - Библиотека профилирования системы
 * Системная библиотека
 *
 * @version    1.0
 * @package    Joostina.Libraries
 * @subpackage Libraries
 * @category   Libraries
 * @author     Joostina Team <info@joostina.ru>
 * @copyright  (C) 2007-2011 Joostina Team
 * @license    MIT License http://www.opensource.org/licenses/mit-license.php
 * Информация об авторах и лицензиях стороннего кода в составе Joostina CMS: docs/copyrights
 *
 * */
class joosBenchmark {

	/**
	 * Внутренний массив хранения временных меток
	 *
	 * @var array
	 */
	private static $markers = array ();
	/**
	 * Время установки первой точки рассчета
	 *
	 * @var float
	 */
	private static $start;

	public static function start() {
		if ( self::$start !== null ) {
			throw new joosException( 'Таймер уже запущен' );
		}
		self::$start = microtime( true );
	}

	/**
	 * Установка точки рассчета времени
	 *
	 * @param string $name название точки рассчера
	 *
	 * @return int время прошедшее с установки последней точки до текущей
	 */
	public static function mark( $name ) {

		if ( self::$start === null ) {
			throw new joosException( 'Таймер сначала должен быть запущен' );
		}

		$mark = array ();
		// название точки рассчета
		$mark['id'] = $name;
		// время на момент создания точки
		$mark['microtime'] = microtime( true );
		// время, прошедшее с начала рассчета
		$mark['since_start'] = $mark['microtime'] - self::$start;
		// время с момента установки последней точки
		$mark['since_last_mark'] = count( self::$markers ) ? ( $mark['microtime'] - self::$markers[count( self::$markers ) - 1]['microtime'] ) : $mark['since_start'];
		self::$markers[]         = $mark;

		return $mark['since_last_mark'];
	}

	/**
	 * Рассчет времени между двёмя точками рассчета
	 *
	 * @param string $mark1 название первой точки рассчета
	 * @param string $mark2 название второй точки рассчета
	 *
	 * @return float разница во времени между первой и второй точками рассчета в милисекундах
	 */
	public static function elapsed_time( $mark1 , $mark2 ) {
		return self::$markers[$mark2] - self::$markers[$mark1];
	}

	/**
	 * Очистка всех данных о внутренних точках рассчате и их значениях
	 *
	 */
	public static function clear() {
		self::$markers = array ();
	}

	/**
	 * Получение внутренней информации о точках рассчета времени
	 *
	 * @return array массив информации о стоп-точках рассчета времени
	 */
	public static function get_markers() {
		return self::$markers;
	}

	/**
	 * Получение полного времени с момента старта до текущего момента
	 *
	 * @return float затраченное время
	 */
	public static function get_time() {
		return round( ( microtime( true ) - self::$start ) , 5 );
	}

}
