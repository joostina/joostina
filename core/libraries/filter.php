<?php

// запрет прямого доступа
defined('_JOOS_CORE') or die();

/**
 * joosFilter - Библиотека фильтрации данных
 * Системная библиотека
 *
 * @version 1.0
 * @package Joostina.Libraries
 * @subpackage Libraries
 * @category Libraries
 * @author Joostina Team <info@joostina.ru>
 * @copyright (C) 2007-2011 Joostina Team
 * @license MIT License http://www.opensource.org/licenses/mit-license.php
 * Информация об авторах и лицензиях стороннего кода в составе Joostina CMS: docs/copyrights
 *
 * */
class joosFilter {

	/**
	 * Преобразует символы в соответствующие HTML сущности
	 *
	 * @param string $value строка для
	 * @param string $quoteStyle - одно из доступных значений ENT_COMPAT, ENT_QUOTES, ENT_NOQUOTES
	 * @return string преобразованная строка
	 */
	public static function htmlentities($value, $quoteStyle = ENT_NOQUOTES) {
		return htmlentities($value, $quoteStyle, 'UTF-8');
	}

	/**
	 * Преобразует специальные символы в HTML сущности
	 *
	 * @param type $value строка для
	 * @param string $quoteStyle - одно из доступных значений ENT_COMPAT, ENT_QUOTES, ENT_NOQUOTES
	 * @return type преобразованная строка
	 */
	public static function htmlspecialchars($value, $quoteStyle = ENT_NOQUOTES) {
		return htmlspecialchars($value, $quoteStyle, 'UTF-8');
	}

}