<?php

// запрет прямого доступа
defined('_JOOS_CORE') or die();

/**
 * joosFilter - Библиотека фильтрации данных
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
class joosFilter {

	/**
	 * Преобразует символы в соответствующие HTML сущности
	 *
	 * @param string $value      строка для
	 * @param string $quoteStyle - одно из доступных значений ENT_COMPAT, ENT_QUOTES, ENT_NOQUOTES
	 *
	 * @return string преобразованная строка
	 */
	public static function htmlentities($value, $quoteStyle = ENT_NOQUOTES) {
		return htmlentities($value, $quoteStyle, 'UTF-8');
	}

	/**
	 * Преобразует специальные символы в HTML сущности
	 *
	 * @param type   $value      строка для
	 * @param string $quoteStyle - одно из доступных значений ENT_COMPAT, ENT_QUOTES, ENT_NOQUOTES
	 *
	 * @return type преобразованная строка
	 */
	public static function htmlspecialchars($value, $quoteStyle = ENT_NOQUOTES) {
		return htmlspecialchars($value, $quoteStyle, 'UTF-8');
	}

	/**
	 * Преобразование имени файла в безопасное значение
	 * Имя транслитерируется с русского языка, переводится в нижний регистр и очищается от спецсимволов и пробелов
	 *
	 * @param string $file_name имя файла с расширением, или без
	 * @return type  очищенное имя файла
	 */
	public static function filename($file_name) {

		$file_name = joosText::russian_transliterate($file_name);
		$file_name = strtolower($file_name);
		$file_name = preg_replace('/[^-a-z0-9_.-]+/u', '-', $file_name);

		return trim(basename(stripslashes($file_name)), ".\x00..\x20");
	}

}
