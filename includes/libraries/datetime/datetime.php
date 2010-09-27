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

class DateAndTime {

	function getDelta($first, $last) {
		if ($last < $first) return false;

		$hms = ($last - $first) % (3600 * 24);
		$delta['seconds'] = $hms % 60;
		$delta['minutes'] = floor($hms/60) % 60;
		$delta['hours']   = floor($hms/3600) % 60;

		$last -= $hms;
		$f = getdate($first);
		$l = getdate($last);

		$dYear = $dMon = $dDay = 0;

		$dDay += $l['mday'] - $f['mday'];
		if ($dDay < 0) {
			$monlen = self::monthLength(date("Y", $first), date("m", $first));
			$dDay += $monlen;
			$dMon--;
		}
		$delta['mday'] = $dDay;

		if($delta['mday']>1) {
			$delta['mday'] = $delta['mday']- 1;
		}

		$dMon += $l['mon'] - $f['mon'];
		if ($dMon < 0) {
			$dMon += 12;
			$dYear --;
		}
		$delta['mon'] = $dMon;

		$dYear += $l['year'] - $f['year'];
		$delta['year'] = $dYear;

		return $delta;
	}

	function monthLength($year, $mon) {
		$l = 28;
		while (checkdate($mon, $l+1, $year)) $l++;
		return $l;
	}

	function mysql_to_unix($time = '') {

		// без велосипедов попробуем
		return strtotime($time);

		$time = str_replace('-', '', $time);
		$time = str_replace(':', '', $time);
		$time = str_replace(' ', '', $time);

		return  mktime(
				substr($time, 8, 2),
				substr($time, 10, 2),
				substr($time, 12, 2),
				substr($time, 4, 2),
				substr($time, 6, 2),
				substr($time, 0, 4)
		);
	}
}