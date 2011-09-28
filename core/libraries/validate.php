<?php

// запрет прямого доступа
defined( '_JOOS_CORE' ) or die();

/**
 * joosValidate - Библиотека валидациии данных
 * Системная библиотека
 *
 * @version    1.0
 * @package    Joostina.Libraries
 * @subpackage Libraries
 * @category   Libraries
 * @author     Joostina Team <info@joostina.ru>
 * @copyright  (C) 2007-2011 Joostina Team
 * @license    MIT License http://www.opensource.org/licenses/mit-license.php
 * Информация об авторах и лицензиях стороннего кода в составе Joostina CMS: docs/copyrights
 *
 * */
class joosValidate {

	/**
	 * Проверка строка на соответствие email - формату
	 *
	 * @param string $value
	 *
	 * @return bool результат проверки соответсвия
	 */
	public static function is_email( $value ) {
		return ( filter_var( (string) $value , FILTER_VALIDATE_EMAIL ) === false ) ? false : true;
	}

	/**
	 * Проверка строка на наличие в ней только цифр, либо чсама строка - число
	 *
	 * @param string $value
	 *
	 * @return bool результат проверки соответсвия
	 */
	public static function is_digital( $value ) {
		return is_numeric( (string) $value );
	}

	/**
	 * Проверка строка на наличие в ней только цифр алфавита, русского и английского а-я a-z
	 *
	 * @param string $value
	 *
	 * @return bool результат проверки соответсвия
	 */
	public static function is_alfa( $value ) {
		return (bool) preg_match( '/^[a-zа-яё]+$/iu' , (string) $value );
	}

	/**
	 * Проверка строка на соответствие url - формату
	 *
	 * @param string $value
	 *
	 * @return bool результат проверки соответсвия
	 */
	function is_url( $value ) {
		return ( filter_var( $value , FILTER_VALIDATE_URL ) === false ) ? false : true;
	}

}