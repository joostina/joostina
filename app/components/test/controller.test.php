<?php

// запрет прямого доступа
defined('_JOOS_CORE') or die();

/**
 * Test  - Компонент для тестирования нового функционала
 * Контроллер сайта
 *
 * @version    1.0
 * @package    Controllers
 * @subpackage Test
 * @author     Joostina Team <info@joostina.ru>
 * @copyright  (C) 2007-2012 Joostina Team
 * @license    MIT License http://www.opensource.org/licenses/mit-license.php
 * Информация об авторах и лицензиях стороннего кода в составе Joostina CMS: docs/copyrights
 *
 * */
class actionsTest extends joosController {

	/**
	 * Метод контроллера, запускаемый по умолчанию
	 *
	 * @static
	 * @return array
	 */
	public static function index() {

		echo joosInflector::camelize('blog');

		return array();
	}

	/**
	 * Тестирование загрузчика
	 */
	public static function upload() {
		return array();
	}

	/**
	 * Пример валидации модели
	 *
	 */
	public static function model_validation() {

		$v = new modelPost;
		$v->title = 'человеков!';
		if ($v->validate()) {
			echo 'Всё круто!';
		} else {
			echo 'Введённые данные формы невалидны';
			print_r($v->get_validation_error_messages());
		}
	}

	/**
	 * Для тестирования вёрстки
	 *
	 */
	public static function layouts() {
		$tpl = self::$param['tpl'];
		return array(
			'template' => $tpl
		);
	}

}

class modelPost extends joosModel {

	public $id;
	public $title;
	public $state;
	public $created_at;

	public function __construct() {

	}

	protected function get_validate_rules() {
		return array(
			array('title', 'required', 'message' => 'Заголовок надо!'),
			array('title', 'string:5..15', 'message' => 'Длина должна быть от :min до :max символов'),
			array('created_at', 'null', 'on' => 'update', 'message' => 'При измении записи оригинальную дату создания нельзя изменять!'), /* при измении записи created_at уже есть в базе и в моделе оно должно быть NULL */
		);
	}

}
