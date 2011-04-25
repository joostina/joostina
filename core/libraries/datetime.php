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

	public static function get_delta($first, $last) {
		if ($last < $first)
			return false;

		$hms = ($last - $first) % (3600 * 24);
		$delta['seconds'] = $hms % 60;
		$delta['minutes'] = floor($hms / 60) % 60;
		$delta['hours'] = floor($hms / 3600) % 60;

		$last -= $hms;
		$f = getdate($first);
		$l = getdate($last);

		$dYear = $dMon = $dDay = 0;

		$dDay += $l['mday'] - $f['mday'];
		if ($dDay < 0) {
			$monlen = self::month_length(date("Y", $first), date("m", $first));
			$dDay += $monlen;
			$dMon--;
		}
		$delta['mday'] = $dDay;

		if ($delta['mday'] > 1) {
			$delta['mday'] = $delta['mday'] - 1;
		}

		$dMon += $l['mon'] - $f['mon'];
		if ($dMon < 0) {
			$dMon += 12;
			$dYear--;
		}
		$delta['mon'] = $dMon;

		$dYear += $l['year'] - $f['year'];
		$delta['year'] = $dYear;

		return $delta;
	}

	public static function month_length($year, $mon) {
		$l = 28;
		while (checkdate($mon, $l + 1, $year))
			$l++;
		return $l;
	}

	public static function mysql_to_unix($time = '') {

		// без велосипедов попробуем
		return strtotime($time);

		$time = str_replace('-', '', $time);
		$time = str_replace(':', '', $time);
		$time = str_replace(' ', '', $time);

		return mktime(
				substr($time, 8, 2), substr($time, 10, 2), substr($time, 12, 2), substr($time, 4, 2), substr($time, 6, 2), substr($time, 0, 4)
		);
	}

	public static function get_day_name_from_index($day_index) {
		$day_name = array(
			1 => 'Понедельник',
			2 => 'Вторник',
			3 => 'Среда',
			4 => 'Четверг',
			5 => 'Пятница',
			6 => 'Суббота',
			7 => 'Воскресение',
		);
		return $day_name[$day_index];
	}

	/**
	 * Реализация функции date() по русски
	 * На основе http://www.mitlex.ru/notes/php/date-po-russki/
	 * @return string
	 */
	public static function russian_date() {
		$translation = array(
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
		if (func_num_args() > 1) {
			$timestamp = func_get_arg(1);
			return strtr(date(func_get_arg(0), $timestamp), $translation);
		} else {
			return strtr(date(func_get_arg(0)), $translation);
		}
		;
	}

	/**
	 * Логическое отображение разницы дат
	 * на основе http://www.zachleat.com/web/2008/02/10/php-pretty-date/
	 * @param DateTime $date дата объекта
	 * @param DateTime $compareTo дата для сравнения, мо умолчанию используется текущая дата
	 * @return string строка, описывающая разницу дат
	 */
	public static function time_difference($date, $compareTo = NULL) {

		if (is_null($compareTo)) {
			$compareTo = new DateTime('now');
		}

		$date = new DateTime($date);

		$diff = $compareTo->format('U') - $date->format('U');
		$dayDiff = floor($diff / 86400);

		if (is_nan($dayDiff) || $dayDiff < 0) {
			return '';
		}

		if ($dayDiff == 0) {
			if ($diff < 60) {
				return 'Только что';
			} elseif ($diff < 120) {
				return '1 минуту назад';
			} elseif ($diff < 3600) {
				return floor($diff / 60) . ' минут назад';
			} elseif ($diff < 7200) {
				return '1 час назад';
			} elseif ($diff < 86400) {
				return floor($diff / 3600) . ' часов назад';
			}
		} elseif ($dayDiff == 1) {
			return 'Вчера';
		} elseif ($dayDiff < 7) {
			return $dayDiff . ' дней назад';
		} elseif ($dayDiff == 7) {
			return 'Неделю назад';
		} elseif ($dayDiff < (7 * 6)) { // Modifications Start Here
			// 6 weeks at most
			return ceil($dayDiff / 7) . ' недель назад';
		} elseif ($dayDiff < 365) {
			return ceil($dayDiff / (365 / 12)) . ' месяцев назад';
		} else {
			$years = round($dayDiff / 365);
			return $years . ' ' . ($years != 1 ? 'лет' : 'год') . ' назад';
		}
	}

	/**
	 * Получение названия месяца по его порядковому номеру
	 * @param int $month номер месяца, 1 - Январь, 2 -Февраль и т.д.
	 * @return string название месяца
	 */
	public static function month_name($month) {
		$all_month = array(
			1 => _JAN,
			2 => _FEB,
			3 => _MAR,
			4 => _APR,
			5 => _MAY,
			6 => _JUN,
			7 => _JUL,
			8 => _AUG,
			9 => _SEP,
			10 => _OCT,
			11 => _NOV,
			12 => _DEC
		);
		return $all_month[$month];
	}

}