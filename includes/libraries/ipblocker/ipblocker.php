<?php

/**
 * @package Joostina
 * @copyright Авторские права (C) 2007-2010 Joostina team. Все права защищены.
 * @license Лицензия http://www.gnu.org/licenses/gpl-2.0.htm GNU/GPL, или help/license.php
 * Joostina! - свободное программное обеспечение распространяемое по условиям лицензии GNU/GPL
 * Для получения информации о используемых расширениях и замечаний об авторском праве, смотрите файл help/copyright.php.
 */
/**
 * Основано на WP-Ban (c)
 * === WP-Ban ===
 * Contributors: GamerZ
 * Donate link: http://lesterchan.net/wordpress
 * Tags: banned, ban, deny, denied, permission, ip, hostname, host, spam, bots, bot, exclude, referer, url, referral, range
 * Requires at least: 2.8
 * Stable tag: 1.50
 */
// запрет прямого доступа
defined('_JOOS_CORE') or die();

// сами проверки банов поочерёдно
function banned() {
	$ip = get_IP();
	if ($ip == 'unknown') {
		return;
	}
	$banned_ips = get_option('banned_ips');
	$banned_ips_range = get_option('banned_ips_range');
	$banned_hosts = get_option('banned_hosts');
	$banned_referers = get_option('banned_referers');
	$banned_user_agents = get_option('banned_user_agents');
	$banned_exclude_ips = get_option('banned_exclude_ips');
	$is_excluded = false;
	if (!empty($banned_exclude_ips)) {
		foreach ($banned_exclude_ips as $banned_exclude_ip) {
			if ($ip == $banned_exclude_ip) {
				$is_excluded = true;
				break;
			}
		}
	}
	// текущий IP пользователя в списке разрешённых - дальнейшие проверки не выполняем
	if (!$is_excluded) {
		process_ban($banned_ips, $ip);
		process_ban_ip_range($banned_ips_range);
		process_ban($banned_hosts, @gethostbyaddr($ip));
		process_ban($banned_referers, $_SERVER['HTTP_REFERER']);
		process_ban($banned_user_agents, $_SERVER['HTTP_USER_AGENT']);
	}
}

// проверка на бан
function process_ban($banarray, $against) {
	if (!empty($banarray) && !empty($against)) {
		foreach ($banarray as $cban) {
			$regexp = str_replace('.', '\\.', $cban);
			$regexp = str_replace('*', '.+', $regexp);
			if (ereg("^$regexp$", $against)) {
				print_banned_message();
			}
		}
	}
	return;
}

function check_ip_within_range($ip, $range_start, $range_end) {
	$range_start = ip2long($range_start);
	$range_end = ip2long($range_end);
	$ip = ip2long($ip);
	if ($ip !== false && $ip >= $range_start && $ip <= $range_end) {
		return true;
	}
	return false;
}

// вырезки
$uip = '127.0.0.2';

// бан по IP и маске
$banarray = array('127.111.236.113', '132.*.2*6.11*', '127.0.*.*');
foreach ($banarray as $cban) {
	$regexp = str_replace('.', '\\.', $cban);
	$regexp = str_replace('*', '.+', $regexp);
	if (ereg("^$regexp$", $uip)) {
		echo 'Баня!';
	}
}

// бан по IP диапазону
$banned_ips_range = array('127.0.0.0-127.0.0.5');
foreach ($banned_ips_range as $banned_ip_range) {
	$range = explode('-', $banned_ip_range);
	$range_start = trim($range[0]);
	$range_end = trim($range[1]);
	if (check_ip_within_range($uip, $range_start, $range_end)) {
		echo 'Баняяяя2';
	}
}

echo 'Не баня!';