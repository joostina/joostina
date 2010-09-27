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


// на основе http://www.proofsite.com.ua/article-1214.html
function timespan($time1, $time2 = NULL, $output = 'лет,месяц,недель,дней,часов,минут,секунд') {
	$output = preg_split('/[^a-z]+/', strtolower((string) $output));
	if (empty($output))
		return FALSE;
	extract(array_flip($output), EXTR_SKIP);
	$time1 = max(0, (int) $time1);
	$time2 = empty($time2) ? time() : max(0, (int) $time2);
	$timespan = abs($time1 - $time2);
	isset($years) and $timespan -= 31556926 * ($years = (int) floor($timespan / 31556926));
	isset($months) and $timespan -= 2629744 * ($months = (int) floor($timespan / 2629743.83));
	isset($weeks) and $timespan -= 604800 * ($weeks = (int) floor($timespan / 604800));
	isset($days) and $timespan -= 86400 * ($days = (int) floor($timespan / 86400));
	isset($hours) and $timespan -= 3600 * ($hours = (int) floor($timespan / 3600));
	isset($minutes) and $timespan -= 60 * ($minutes = (int) floor($timespan / 60));
	isset($seconds) and $seconds = $timespan;
	unset($timespan, $time1, $time2);
	$deny = array_flip(array('deny', 'key', 'difference', 'output'));
	$difference = array();
	foreach ($output as $key) {
		if (isset($$key) AND !isset($deny[$key])) {
			$difference[$key] = $$key;
		}
	}
	if (empty($difference))
		return FALSE;
	if (count($difference) === 1)
		return current($difference);
	return $difference;
}