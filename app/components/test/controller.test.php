<?php

// запрет прямого доступа
defined('_JOOS_CORE') or die();

/**
 * Test  - Компонент для тестирования нового функционала
 * Контроллер сайта
 *
 * @version    1.0
 * @package    Joostina.Components.Controllers
 * @subpackage Tags
 * @author     Joostina Team <info@joostina.ru>
 * @copyright  (C) 2007-2012 Joostina Team
 * @license    MIT License http://www.opensource.org/licenses/mit-license.php
 * Информация об авторах и лицензиях стороннего кода в составе Joostina CMS: docs/copyrights
 *
 * */
class actionsTest {

	/**
	 * Метод контроллера, запускаемый по умолчанию
	 *
	 * @static
	 * @return array
	 */
	public static function index() {



		$r = array();

		/*
		  $r[] = jValid::valid(11, 'int:0..10', 'Нет, в диапазон не попадает');
		  $r[] = jValid::valid('Netа', 'string:5..15', 'Не попадает в диапазон от :min до :max');
		  $r[] = jValid::valid('Nette', 'string:8..', 'В строке должно быть не меньше :min символов');
		  $r[] = jValid::valid('Nette Framework', 'string:8..');
		  $r[] = jValid::valid('Nette Framework', 'string:..32');
		  $r[] = jValid::valid('Nette Framework', 'string:8..32');
		  $r[] = jValid::valid(23, 'int:0..10', 'Число не подходит, оно должно быть больше :min и меньше :max');
		  $r[] = jValid::valid(1, 'int|float');

		  $r[] = jValid::valid('sdfds@asds.ru', 'email');
		  $r[] = jValid::valid('192.168.0.256', 'ip', 'Это не Ip!!!');
		  $r[] = jValid::valid('А это уже на регистр', 'lower', 'Надо всё маленькими');
		  $r[] = jValid::valid('234', 'int');
		  $r[] = jValid::valid("	\n \r \t", 'required', 'Поле не должно быть пустым!!!');

		  _xdump($r);
		 */

		$v = new modelPost;
		$v->title = 'Убить всех человеков!';
		$v->validate();

		return array('asd' => crc32('Alanis Morissette - Crazy'));
	}

	/**
	 * Тестирование загрузчика
	 */
	public static function upload() {
		return array();
	}

	/**
	 * Примеры работы с базой данных
	 */
	public static function db() {

		$user_1 = joosDatabase::instance()->set_query('select * from #__users where id=1')->load_result();

		_xdump($user_1);
	}

}

class modelPost{

	public $id;
	public $title;
	public $state;
	public $created_at;

	public function get_validate_rules() {
		return array(
			array('title', 'required', 'message' => 'Заголовок надо!'),
			array('title', 'string:5..15', 'message' => 'Длина должна быть от :min до :max символов'),
			array('created_at', 'null', 'on' => 'update', 'message' => 'При измении записи оригинальную дату создания нельзя изменять!'), /* при измении записи created_at уже есть в базе и в моделе оно должно быть NULL */
		);
	}

	public function validate() {

		$rules = $this->get_validate_rules();

		$r = array();
		foreach ($rules as $rule) {
			$r[] = jValid::valid($this->$rule[0], $rule[1], ( isset($rule['message']) ? $rule['message'] : false ) );
		}

		_xdump($r);
	}

}

class jValid {

	// правила валидации через системный класс
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