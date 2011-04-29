<?php

// запрет прямого доступа
defined('_JOOS_CORE') or die();

/**
 * joosDateTime - Библиотека работы с датами и временем
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
class joosDateTime {

	/**
	 * Правила локализации результата работы функции date
	 * 
	 * @var array массив правил локализации
	 */
	private static $date_translation = array(
		'am' => 'дп',
		'pm' => 'пп',
		'AM' => 'ДП',
		'PM' => 'ПП',
		'Monday' => 'Понедельник',
		'Mon' => 'Пн',
		'Tuesday' => 'Вторник',
		'Tue' => 'Вт',
		'Wednesday' => 'Среда',
		'Wed' => 'Ср',
		'Thursday' => 'Четверг',
		'Thu' => 'Чт',
		'Friday' => 'Пятница',
		'Fri' => 'Пт',
		'Saturday' => 'Суббота',
		'Sat' => 'Сб',
		'Sunday' => 'Воскресенье',
		'Sun' => 'Вс',
		'January' => 'Января',
		'Jan' => 'Янв',
		'February' => 'Февраля',
		'Feb' => 'Фев',
		'March' => 'Марта',
		'Mar' => 'Мар',
		'April' => 'Апреля',
		'Apr' => 'Апр',
		'May' => 'Мая',
		'May' => 'Мая',
		'June' => 'Июня',
		'Jun' => 'Июн',
		'July' => 'Июля',
		'Jul' => 'Июл',
		'August' => 'Августа',
		'Aug' => 'Авг',
		'September' => 'Сентября',
		'Sep' => 'Сен',
		'October' => 'Октября',
		'Oct' => 'Окт',
		'November' => 'Ноября',
		'Nov' => 'Ноя',
		'December' => 'Декабря',
		'Dec' => 'Дек',
		'st' => 'ое',
		'nd' => 'ое',
		'rd' => 'е',
		'th' => 'ое',
	);

	/**
	 * Реализации функции date с учетом локализации
	 * Функция имет синтаксис аналогичный оригинальной функции date
	 * 
	 * @example joosDateTime::russian_date('d-M-Y') => 30-Апр-2011
	 * @example joosDateTime::russian_date('d F, l - h часов и i минут') => 30 Апреля, Суббота - 05 часов и 15 минут
	 * @example joosDateTime::russian_date('d F Y года, l', strtotime('31-10-1983') ) => 31 Октября 1983 года, Понедельник
	 * 
	 * @return string форматированная строка даты - времени
	 */
	public static function russian_date() {

		if (func_num_args() > 1) {
			$timestamp = func_get_arg(1);
			return strtr(date(func_get_arg(0), $timestamp), self::$date_translation);
		} else {
			return strtr(date(func_get_arg(0)), self::$date_translation);
		}
	}

	/**
	 * Получение локализованного названия месяца по его порядковому номеру
	 * 
	 * @example joosDateTime::month_name_from_index(1) => Январь
	 * @example joosDateTime::month_name_from_index(1) => Октябрь
	 * 
	 * @param int $month номер месяца, 1 - Январь, 2 -Февраль и т.д.
	 * @return string название месяца
	 */
	public static function month_name_from_index($month) {

		$all_month = array(
			1 => __('Январь'),
			2 => __('Февраль'),
			3 => __('Март'),
			4 => __('Апрель'),
			5 => __('Май'),
			6 => __('Июнь'),
			7 => __('Июль'),
			8 => __('Август'),
			9 => __('Сентябрь'),
			10 => __('Октябрь'),
			11 => __('Ноябрь'),
			12 => __('Декабрь')
		);
		return $all_month[$month];
	}

	/**
	 * Получение название дня недели по номеру дня
	 * Значение адаптирванно под Русские реалии, 1 - понедельник, 7 - воскресение
	 * 
	 * @example joosDateTime::day_name_from_index(1) => Понедельник
	 * @example joosDateTime::day_name_from_index(5) => Пятница
	 * 
	 * @param int $day_index номер дня, 1 - Понедельник, 2 - Вторник и т.д.
	 * @return string
	 */
	public static function day_name_from_index($day_index) {

		$day_name = array(
			1 => __('Понедельник'),
			2 => __('Вторник'),
			3 => __('Среда'),
			4 => __('Четверг'),
			5 => __('Пятница'),
			6 => __('Суббота'),
			7 => __('Воскресение'),
		);
		return $day_name[$day_index];
	}

	/**
	 * Форматирование и локализация даты. 
	 * Если не указан конкртеный формат $format, то используется общесистемное правило для форматирования дат JDATE_FORMAT
	 * 
	 * @example joosDateTime::format('1983-10-31 11:11:11') => 31 Октября 1983 г. 11:11
	 * @example joosDateTime::format('1983-10-31') => 31 Октября 1983 г. 00:00
	 * 
	 * @param string $date_time исходная строка даты, времени
	 * @param string $format правила форматирования даты и времени, как в функции strtr
	 * @return string
	 */
	public static function format($date_time, $format = false) {

		$format = $format ? $format : JDATE_FORMAT;
		$datetime = strftime($format, strtotime($date_time));

		return strtr($datetime, self::$date_translation);
	}

	/**
	 * Получение текущего времени в виде локализованной отформатированной строки
	 * Строка даты форматируется используя общесистемное правило для форматирования дат JDATE_FORMAT
	 * 
	 * @example joosDateTime::current() => 30 Апреля 2011 г. 04:35
	 * @example joosDateTime::current('%d %B %Y ') => 30 Апреля 2011
	 * @example joosDateTime::current( '%H:%M:%S' ) => 04:37:38
	 * @example joosDateTime::current('%d %h') => 30 Апр
	 * 
	 * @param type $format
	 * @return type 
	 */
	public static function current($format = false) {

		$format = $format ? $format : JDATE_FORMAT;
		$datetime = strftime($format, time());

		return strtr($datetime, self::$date_translation);
	}

}