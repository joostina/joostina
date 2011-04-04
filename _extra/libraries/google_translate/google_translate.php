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

/**
 * Перевод строки с использованием сервиса Google Translate
 * @param type $str строка для перевода
 * @param type $from_lang обозначние исходного языка (ru)
 * @param type $to_lang обозначение языка перевода ( en )
 * @return string переведённая строка или false в случае ошибки
 */
function google_translate($str, $from_lang, $to_lang) {
	$url = "http://ajax.googleapis.com/ajax/services/language/translate?v=1.0&q=" . rawurlencode($str) . "&langpair=" . urlencode($from_lang . '|' . $to_lang);
	$curl = curl_init();
	curl_setopt($curl, CURLOPT_URL, $url);
	curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
	$result = curl_exec($curl);
	curl_close($curl);
	$json = json_decode($result, true);
	return (isset($json['responseStatus']) && $json['responseStatus']) == 200 ? $json['responseData']['translatedText'] : false;
}

// пример
// echo google_translate('Управление пользователями','ru','en');