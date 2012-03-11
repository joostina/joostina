<?php

// запрет прямого доступа
defined('_JOOS_CORE') or die();

/**
 * joosDateTime - Библиотека работы с датами и временем
 * Системная библиотека
 *
 * @version    1.0
 * @package    Joostina.Libraries
 * @subpackage Libraries
 * @category   Libraries
 * @author     Joostina Team <info@joostina.ru>
 * @copyright  (C) 2007-2012 Joostina Team
 * @license    MIT License http://www.opensource.org/licenses/mit-license.php
 * Информация об авторах и лицензиях стороннего кода в составе Joostina CMS: docs/copyrights
 *
 * */
class joosDateTime {

	/**
	 * Правила локализации результата работы функции date
	 *
	 * @var array массив правил локализации
	 */
	private static $date_translation = array('am' => 'дп',
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
		'th' => 'ое',);

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
	 *
	 * @return string название месяца
	 */
	public static function month_name_from_index($month) {

		$month = (int) $month;

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
	 *
	 * @return string
	 */
	public static function day_name_from_index($day_index) {

		$day_index = (int) $day_index;

		$day_name = array(
			1 => __('Понедельник'),
			2 => __('Вторник'),
			3 => __('Среда'),
			4 => __('Четверг'),
			5 => __('Пятница'),
			6 => __('Суббота'),
			7 => __('Воскресение')
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
	 * @param string $format    правила форматирования даты и времени, как в функции strtr
	 *
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
	 *
	 * @return type
	 */
	public static function current($format = false) {

		$format = $format ? $format : JDATE_FORMAT;
		$datetime = strftime($format, time());

		return strtr($datetime, self::$date_translation);
	}

	/**
	 * Рассчет и прописное представление прошедшего времени
	 *
	 * @param int $timestamp    первоначальный момент времени в секундах
	 * @param int $current_time конечный момент времени в секундах, по умолчанию - текущее время
	 *
	 * @return string логическое прописное обозначение времени
	 */
	public static function time_ago($timestamp, $current_time = false) {

		$timestamp = (int) $timestamp;
		$current_time = $current_time ? $current_time : time();

		// считаем число секунд между собыьтиями
		$seconds_between = $current_time - $timestamp;

		// года
		$years_ago = floor($seconds_between / ( 365.242199 * 24 * 60 * 60 ));
		if ($years_ago > 1) {
			return sprintf(__('%s %s назад'), $years_ago, joosText::declension($years_ago, array('год', 'года', 'лет')));
		}
		if ($years_ago == 1) {
			return __('год назад');
		}

		// месяцы
		$months_ago = floor($seconds_between / ( ( 365.242199 / 12 ) * 24 * 60 * 60 ));
		if ($months_ago > 1) {
			return sprintf(__('%s %s назад'), $months_ago, joosText::declension($months_ago, array('месяц', 'месяца', 'месяцев')));
		}
		if ($months_ago == 1) {
			return __('месяц назад');
		}

		// недели
		$weeks_ago = floor($seconds_between / ( 7 * 24 * 60 * 60 ));
		if ($weeks_ago > 1) {
			return sprintf(__('%s %s назад'), $weeks_ago, joosText::declension($weeks_ago, array('неделя', 'недели', 'недель')));
		}
		if ($weeks_ago == 1) {
			return __('неделю назад');
		}

		// дней
		$days_ago = floor($seconds_between / ( 24 * 60 * 60 ));
		if ($days_ago > 1) {
			return sprintf(__('%s %s назад'), $days_ago, joosText::declension($days_ago, array('день', 'дня', 'дней')));
		}
		if ($days_ago == 1) {
			return __('день назад');
		}

		// часов
		$hours_ago = floor($seconds_between / ( 60 * 60 ));
		if ($hours_ago > 1) {
			return sprintf(__('%s %s назад'), $hours_ago, joosText::declension($hours_ago, array('час', 'часа', 'часов')));
		}
		if ($hours_ago == 1) {
			return __('час назад');
		}

		// минут
		$minutes_ago = floor($seconds_between / 60);
		if ($minutes_ago > 1) {
			return sprintf('%s %s назад', $minutes_ago, joosText::declension($minutes_ago, array('минуту', 'минуты', 'минут')));
		}
		if ($minutes_ago == 1) {
			return __('минуту назад');
		}

		// секунд
		$seconds_ago = floor($seconds_between);
		if ($seconds_ago > 1) {
			return sprintf(__('%s %s назад'), $seconds_ago, joosText::declension($seconds_ago, array('секунда', 'секунд', 'секунды')));
		}
		if ($seconds_ago <= 1) {
			return __('секунда');
		}

		return __('Очень давно');
	}

	/**
	 * Получение логического представление строки времени
	 *
	 * @param int $time число секунд для анализа
	 * @return string
	 */
	public static function time_string($time) {

		$time = (int) $time;

		// года
		$years = floor($time / ( 365.242199 * 24 * 60 * 60 ));
		if ($years > 1) {
			return sprintf(__('%s %s'), $years, joosText::declension($years, array('год', 'года', 'лет')));
		}
		if ($years == 1) {
			return __('год');
		}

		// месяцы
		$months = floor($time / ( ( 365.242199 / 12 ) * 24 * 60 * 60 ));
		if ($months > 1) {
			return sprintf(__('%s %s'), $months, joosText::declension($months, array('месяц', 'месяца', 'месяцев')));
		}
		if ($months == 1) {
			return __('месяц');
		}

		// недели
		$weeks = floor($time / ( 7 * 24 * 60 * 60 ));
		if ($weeks > 1) {
			return sprintf(__('%s %s'), $weeks, joosText::declension($weeks, array('неделя', 'недели', 'недель')));
		}
		if ($weeks == 1) {
			return __('неделю');
		}

		// дней
		$days = floor($time / ( 24 * 60 * 60 ));
		if ($days > 1) {
			return sprintf(__('%s %s'), $days, joosText::declension($days, array('день', 'дня', 'дней')));
		}
		if ($days == 1) {
			return __('день');
		}

		// часов
		$hours = floor($time / ( 60 * 60 ));
		if ($hours > 1) {
			return sprintf(__('%s %s'), $hours, joosText::declension($hours, array('час', 'часа', 'часов')));
		}
		if ($hours == 1) {
			return __('час');
		}

		// минут
		$minutes = floor($time / 60);
		if ($minutes > 1) {
			return sprintf('%s %s', $minutes, joosText::declension($minutes, array('минуту', 'минуты', 'минут')));
		}
		if ($minutes == 1) {
			return __('минута');
		}
	}

}
