<?php defined('_JOOS_CORE') or die();

/**
 * Библиотека фильтрации данных
 *
 * @version    1.0
 * @package    Core\Libraries
 * @subpackage Filter
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
	 * @param string $value строка для
	 * @param string $quoteStyle одно из доступных значений ENT_COMPAT, ENT_QUOTES, ENT_NOQUOTES
	 *
	 * @return string преобразованная строка
	 */
	public static function htmlentities($value, $quoteStyle = ENT_NOQUOTES) {
		return htmlentities($value, $quoteStyle, 'UTF-8');
	}

	/**
	 * Преобразует специальные символы в HTML сущности
	 *
	 * @param string   $value      строка для
	 * @param string $quoteStyle - одно из доступных значений ENT_COMPAT, ENT_QUOTES, ENT_NOQUOTES
	 *
	 * @return string преобразованная строка
	 */
	public static function htmlspecialchars($value, $quoteStyle = ENT_NOQUOTES) {
		return htmlspecialchars($value, $quoteStyle, 'UTF-8');
	}

}