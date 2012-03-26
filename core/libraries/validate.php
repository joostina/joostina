<?php

// запрет прямого доступа
defined('_JOOS_CORE') or die();

/**
  * Библиотека валидациии данных
 *
 * @version    1.0
 * @package    Libraries
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
	 * @tutorial joosValidate::is_email('admin@joostina.ru')
	 * @tutorial joosValidate::is_email('noooo@mail.ru')
	 *
	 * @param string $value email адрес для проверки
	 * @return bool результат проверки соответсвия
	 */
	public static function is_email($value) {
		return is_string($value) && filter_var($value, FILTER_VALIDATE_EMAIL);
	}

	/**
	 * Проверка строки на наличие в ней только цифр, либо сама строка - число
	 *
	 * @param string|int $value значение для проверки
	 *
	 * @return bool результат проверки соответсвия
	 */
	public static function is_digital($value) {
		return filter_var($value, FILTER_VALIDATE_INT) !== false;
	}

	/**
	 * Проверяет, что значение переменной больше чем указанное число min
	 *
	 * @param type $value значение для проверки
	 * @param type $min
	 * @return bool результат проверки соответсвия
	 */
	public static function is_digital_value_min($value, $min) {
		return ( static::is_digital($value) && (int) $value > $min );
	}

	/**
	 * Проверяет, что значение переменной меньше чем указанное число max
	 *
	 * @param type $value значение для проверки
	 * @param type $max
	 * @return bool результат проверки соответсвия
	 */
	public static function is_digital_value_max($value, $max) {
		return ( static::is_digital($value) && (int) $value < $max );
	}

	/**
	 * Проверка вхождения цифрового значения переменной в указанный диапазон min и max
	 *
	 * @param type $value значение для проверки
	 * @param type $min
	 * @param type $max
	 * @return bool результат проверки соответсвия
	 */
	public static function is_digital_value_between($value, $min, $max) {
		$int_options = array("options" => array("min_range" => $min, "max_range" => $max));
		return ( static::is_digital($value) && filter_var($value, FILTER_VALIDATE_INT, $int_options));
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

	/**
	 * Проверка переменной на то что она является массивом
	 *
	 * @param type $value
	 * @return bool результат проверки соответсвия
	 */
	public static function is_array($value) {
		return is_array($value) || ($value instanceof ArrayAccess && $value instanceof Traversable && $value instanceof Countable);
	}

	/**
	 * Проверка на булевый тип
	 *
	 * @param type $value
	 * @return bool результат проверки соответсвия
	 */
	public static function is_bool($value) {
		return filter_var($value, FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE);
	}

	/**
	 * Проверка на тип float
	 *
	 * @param type $value
	 * @return bool результат проверки соответсвия
	 */
	public static function is_float($value) {
		return filter_var($value, FILTER_VALIDATE_FLOAT) !== false;
	}

	/**
	 * Проверка что переменная является валидным IP адресом
	 *
	 * @param type $value
	 * @param type $flags
	 * @return bool результат проверки соответсвия
	 */
	public static function is_ip($value, $flags = null) {
		return (boolean) filter_var($value, FILTER_VALIDATE_IP, array('flags' => $flags)
		);
	}

	/**
	 * Проверяет, что длина строки переменной меньше чем указанное число max
	 *
	 * @param type $value
	 * @return bool результат проверки соответсвия
	 */
	public static function is_json($value) {
		return (bool) (json_decode($value));
	}

	/**
	 * Проверяет, что длина строки переменной меньше чем указанное число max в символах
	 *
	 * @param type $value
	 * @param type $min
	 * @param type $inclusive проверять учитывая равенство величине
	 * @return bool результат проверки соответсвия
	 */
	public static function is_length_min($value, $min, $inclusive = true) {
		$length = joosString::strlen($value);
		return $inclusive ? ($length >= $min) : ($length > $min);
	}

	/**
	 * Проверяет, что длина строки переменной больше чем указанное число max в символах
	 *
	 * @param type $value
	 * @param type $max
	 * @param type $inclusive проверять учитывая равенство величине
	 * @return bool результат проверки соответсвия
	 */
	public static function is_length_max($value, $max, $inclusive = true) {
		$length = joosString::strlen($value);
		return $inclusive ? ( $length <= $max) : ( $length < $max);
	}

	/**
	 * Проверяет что длина строки переменной входит в указанный диапазон min и max
	 *
	 * @param type $value
	 * @param type $min
	 * @param type $max
	 * @param type $inclusive проверять учитывая равенство величине
	 * @return bool результат проверки соответсвия
	 */
	public static function is_length_between($value, $min, $max, $inclusive = true) {
		$length = joosString::strlen($value);
		return $inclusive ? ($length >= $min && $length <= $max) : ($length > $min && $length < $max);
	}

	/**
	 * Проверяет что все символы строки в нижнем регистре
	 *
	 * @param type $value
	 * @return bool результат проверки соответсвия
	 */
	public static function is_lower($value) {
		return $value === joosString::strtolower($value);
	}

	/**
	 * Проверяет что все символы строки в верхнем регистре
	 *
	 * @param type $value
	 * @return bool результат проверки соответсвия
	 */
	public static function is_upper($value) {
		return $value === joosString::strtoupper($value);
	}

	/**
	 * Проверка строки на соответсвие любому регулярному выражению
	 *
	 * @param type $value
	 * @param type $regex
	 * @return bool результат проверки соответсвия
	 */
	public static function is_regex_check($value, $regex) {
		return (bool) preg_match($regex, $value);
	}

	/**
	 * Проверяет что строка является пустотой или состояит из непечатаемых символов
	 *
	 * @param type $value
	 * @return bool результат проверки соответсвия
	 */
	public static function is_blank($value) {
		return !static::is_regex_check($value, '/[^\\s]/');
	}

	/**
	 * Проверяет что строка имеет хоть один видимый сивол
	 *
	 * @param type $value
	 * @return bool результат проверки соответсвия
	 */
	public static function is_not_blank($value) {
		return !static::is_blank($value);
	}

	/**
	 * Проверяет что строка не null
	 *
	 * @param type $value
	 * @return bool результат проверки соответсвия
	 */
	public static function is_not_null($value) {
		return !is_null($value);
	}

}

/**
 * Помошник валидации, позволяет писать в правила проверки в упрощенном стиле
 *
 * @tutorial joosValidateHelper::valid(11, 'int:0..10', 'Нет, в диапазон не попадает');
 * @tutorial joosValidateHelper::valid('Hell', 'string:5..15', 'Не попадает в диапазон от :min до :max');
 * @tutorial joosValidateHelper::valid('Привет, человеки!', 'string:8..', 'В строке должно быть не меньше :min символов');
 * @tutorial joosValidateHelper::valid('Не..', 'string:8..');
 * @tutorial joosValidateHelper::valid('Убить всех человеков', 'string:..32');
 * @tutorial joosValidateHelper::valid('Быть или не быть?', 'string:8..32');
 * @tutorial joosValidateHelper::valid(23, 'int:0..10', 'Число не подходит, оно должно быть больше :min и меньше :max');
 * @tutorial joosValidateHelper::valid(1, 'int|float');
 * @tutorial joosValidateHelper::valid('sdfds@asds.ru', 'email');
 * @tutorial joosValidateHelper::valid('192.168.0.256', 'ip', 'Это не Ip!!!');
 * @tutorial joosValidateHelper::valid('А это уже на регистр', 'lower', 'Надо всё маленькими');
 * @tutorial joosValidateHelper::valid('234', 'int');
 * @tutorial joosValidateHelper::valid("	\n \r \t", 'required', 'Поле не должно быть пустым!!!');
 *
 * @copyright на основе оригинальной разработки Nette Framework (http://nette.org)
 */
class joosValidateHelper {

	/**
	 * Правила валидации через системный класс
	 * @var array
	 */
	protected static $validators = array(
		// pattern
		'required' => 'joosValidate::is_not_blank',
		'email' => 'joosValidate::is_email',
		'digital' => 'joosValidate::is_digital',
		'int' => 'joosValidate::is_digital',
		'alpha' => 'joosValidate::is_alfa',
		'url' => 'joosValidate::is_url',
		'array' => 'joosValidate::is_array',
		'list' => 'joosValidate::is_array',
		'bool' => 'joosValidate::is_bool',
		'boolean' => 'joosValidate::is_bool',
		'float' => 'joosValidate::is_float',
		'ip' => 'joosValidate::is_ip',
		'json' => 'joosValidate::is_json',
		'lower' => 'joosValidate::is_lower',
		'upper' => 'joosValidate::is_upper',
		'blank' => 'joosValidate::is_blank',
		'space' => 'joosValidate::is_blank',
		'not_blank' => 'joosValidate::is_not_blank',
		'not_space' => 'joosValidate::is_not_blank',
		'not_null' => 'joosValidate::is_not_null',
		// внутренние обработчики текущего класса
		'string' => 'is_string',
		'object' => 'is_object',
		'resource' => 'is_resource',
		'scalar' => 'is_scalar',
		'null' => 'is_null',
	);

	/**
	 * Правила рассчета длины переменной
	 * @var array
	 */
	protected static $counters = array(
		'string' => 'joosString::strlen',
		'array' => 'count',
		'list' => 'count',
		'alnum' => 'joosString::strlen',
		'alpha' => 'joosString::strlen',
		'digit' => 'joosString::strlen',
		'lower' => 'joosString::strlen',
		'space' => 'joosString::strlen',
		'upper' => 'joosString::strlen',
	);

	public static function valid($value, $expected, $message = false) {
		foreach (explode('|', $expected) as $item) {
			list($type) = $item = explode(':', $item, 2);
			// проверяем тип переменной
			if (isset(static::$validators[$type])) {
				if (!call_user_func(static::$validators[$type], $value)) {
					continue;
				}
			} elseif ($type === 'number') {
				if (!is_int($value) && !is_float($value)) {
					continue;
				}
				// проверка через произвольные регулярки
			} elseif ($type === 'pattern') {
				if (preg_match('|^' . (isset($item[1]) ? $item[1] : '') . '$|', $value)) {
					return TRUE;
				}
				continue;
			} elseif (!$value instanceof $type) {
				continue;
			}

			// проверяем вхождение в разрешённый диапазон
			if (isset($item[1])) {
				if (isset(static::$counters[$type])) {
					$value = call_user_func(static::$counters[$type], $value);
				}
				$range = explode('..', $item[1]);
				if (!isset($range[1])) {
					$range[1] = $range[0];
				}
				if (($range[0] !== '' && $value < $range[0]) || ($range[1] !== '' && $value > $range[1])) {
					//если передана строка для форматирования предупреждения - сформируем её
					$message = $message == false ? false : strtr($message, array(':min' => $range[0], ':max' => $range[1]));
					continue;
				}
			}
			return TRUE;
		}
		return $message;
	}

}