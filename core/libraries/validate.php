<?php

// запрет прямого доступа
defined('_JOOS_CORE') or die();

/**
 * joosValidate - Библиотека валидациии данных
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
class joosValidate {

	/**
	 * Проверка строка на соответствие email - формату
	 *
	 * @example joosValidate::is_email('admin@joostina.ru')
	 *
	 * @param string $value
	 * @return bool результат проверки соответсвия
	 */
	public static function is_email($value) {
		return is_string($value) && filter_var($value, FILTER_VALIDATE_EMAIL);
	}

	/**
	 * Проверка строки на наличие в ней только цифр, либо сама строка - число
	 *
	 * @param string $value
	 *
	 * @return bool результат проверки соответсвия
	 */
	public static function is_digital($value) {
		return filter_var($value, FILTER_VALIDATE_INT) !== false;
	}

	public static function is_digital_value_min($value, $min) {
		return filter_var($value, FILTER_VALIDATE_INT) !== false;
	}

	public static function is_digital_value_max($value, $max) {
		return filter_var($value, FILTER_VALIDATE_INT) !== false;
	}

	public static function is_digital_value_between($value, $min, $max) {
		return filter_var($value, FILTER_VALIDATE_INT) !== false;
	}

	/**
	 * Проверка строка на наличие в ней только цифр алфавита, русского и английского а-я a-z
	 *
	 * @param string $value
	 *
	 * @return bool результат проверки соответсвия
	 */
	public static function is_alfa($value) {
		return (bool) preg_match('/^[a-zа-яё]+$/iu', (string) $value);
	}

	/**
	 * Проверка строка на соответствие url - формату
	 *
	 * @param string $value
	 *
	 * @return bool результат проверки соответсвия
	 */
	public static function is_url($value) {
		return ( filter_var($value, FILTER_VALIDATE_URL) === false ) ? false : true;
	}

	public static function is_array($value) {
		return is_array($value) || ($value instanceof ArrayAccess && $value instanceof Traversable && $value instanceof Countable);
	}

	public static function is_bool($value) {
		return filter_var($value, FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE);
	}

	public static function is_float($value) {
		return filter_var($value, FILTER_VALIDATE_FLOAT) !== false;
	}

	public static function is_ip($value, $flags = null) {
		return (boolean) filter_var($value, FILTER_VALIDATE_IP, array('flags' => $flags)
		);
	}

	public static function is_json($value) {
		return (bool) (json_decode($value));
	}

	// длина не меньше
	public static function is_length_min($value, $min, $inclusive = true) {
		$length = joosString::strlen($value);
		return $inclusive ? ($length >= $min) : ($length > $min);
	}

	// длина строки не больше
	public static function is_length_max($value, $max, $inclusive = true) {
		$length = joosString::strlen($value);
		return $inclusive ? ( $length <= $max) : ( $length < $max);
	}

	public static function is_length_between($value, $min, $max) {
		$length = joosString::strlen($value);
		return ($length >= $min && $length <= $max);
	}

	public static function is_lower($value) {
		return $value === joosString::strtolower($value);
	}

	public static function is_upper($value) {
		return $value === joosString::strtoupper($value);
	}

	public static function is_regex_check($value, $regex) {
		return (bool) preg_match($regex, $value);
	}

	public static function is_blank($value) {
		return !static::is_regex_check($value, '/[^\\s]/');
	}

	public static function is_not_blank($value) {
		return !static::is_blank($value);
	}

}