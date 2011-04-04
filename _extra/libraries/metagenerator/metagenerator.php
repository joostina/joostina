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
 * Полчение ключевых слов из текста
 */
class joosMetaGenerator {

	/**
	 * Массив слов для игнорирования
	 * @var array
	 */
	public static $ignore = array();
	/**
	 * Возвращать сгенерированный слова результаты в виде массива
	 * @var boolean
	 */
	public static $as_array = false;

	/**
	 * Формирование ключевых слов из текста
	 * @param string $text исходный текст
	 * @param int $count число слов желаемых в результате
	 * @param int $minlench минимальная длина слова считающего ключевым
	 * @return array|string результат возвращается в виде массива ил исразу сформированной строки, зависит от параметра $as_array родительского класса
	 */
	public static function genetare_from_text($text, $count = 25, $minlench = 4) {

		$text = Jstring::trim(strip_tags($text)); // чистим от тегов
		$remove = array('mosimage', 'nbsp', "rdquo", "laquo", "raquo", "quota", "quot", "ndash", "mdash", "«", "»", "\t", '\n', '\r', "\n", "\r", '\\', "'", ",", ".", "/", "¬", "#", ";", ":", "@", "~", "[", "]", "{", "}", "=", "-", "+", ")", "(", "*", "&", "^", "%", "$", "<", ">", "?", "!", '"');
		$text = str_replace($remove, ' ', $text); // чистим от спецсимволов
		$arr = explode(' ', $text); // делим текст на массив из слов

		$ignorefile = JPATH_BASE . DS . 'language' . DS . joosMainframe::instance()->get('lang') . DS . 'ignore.php';
		if (is_file($ignorefile)) {
			require_once $ignorefile;
			$arr = str_replace($bad_text, ' ', $arr); // чистим от языковых стоп-слов
		}

		$arr = str_replace(self::$ignore, ' ', $arr); // чистим от языковых стоп-слов

		$ret = array();
		foreach ($arr as $sl) {
			if (Jstring::strlen($sl) >= $minlench) {
				$ret[] = Jstring::strtolower($sl); // собираем в массив тока слова не меньше указанной длины
			}
		}
		$ret = array_count_values($ret); // собираем слова с количеством
		arsort($ret); // сортируем массив, чем чаще встречается слово - тем выше его ставим
		$ret = array_keys($ret);
		$ret = array_slice($ret, 0, $count); // берём первые значения массива
		return self::$as_array ? $ret : implode(', ', $ret); // собираем итог
	}

}