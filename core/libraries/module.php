<?php

// запрет прямого доступа
defined('_JOOS_CORE') or die();

/**
 * joosModule - Общий класс работы с модулями ( на фронте )
 * Системная библиотека
 *
 * @version 1.0
 * @package Joostina.Libraries
 * @subpackage Libraries
 * @category Libraries
 * @category Modules
 * @category joosModule
 * @author Joostina Team <info@joostina.ru>
 * @copyright (C) 2008-2011 Joostina Team
 * @license MIT License http://www.opensource.org/licenses/mit-license.php
 * Информация об авторах и лицензиях стороннего кода в составе Joostina CMS: docs/copyrights
 *
 * */
class joosModule extends Modules {

	private static $data = array();
	private static $_object_data = array();

	public static function get_data() {
		return self::$data;
	}

	public static function add_array(array $modules) {
		self::$data += $modules;
	}

	/**
	 * Загрузка ВСЕХ модулей для текущей страницы
	 */
	public static function modules_by_page($controller, $method, $object_data = array()) {
		$modules_pages = new ModulesPages;

		$modules = $modules_pages->get_list(array('select' => "mp.*,m.*", 'join' =>
					'AS mp INNER JOIN #__modules AS m ON ( m.id = mp.moduleid AND m.state = 1 AND m.client_id = 0 )', 'where' =>
					'mp.controller = "all" OR mp.controller = "' . $controller . '"', 'order' => 'm.position, m.ordering',));


		$by_position = array();
		$by_name = array();
		$by_id = array();

		foreach ($modules as $module) {
			if ($module->controller == 'all' || (!$module->method || ($module->method == $method))) {
				$by_position[$module->position][$module->id] = $module;
				$by_name[$module->module] = $module;
				$by_id[$module->id] = $module;
			}
		}

		self::$data += $by_position;
		self::$data += $by_name;
		self::$data += $by_id;

		self::$_object_data = $object_data;
	}

	/**
	 * Загрузка ВСЕХ модулей определённой позиции
	 * @param string $name название позиции
	 */
	public static function load_by_position($name) {
		if (self::in_position($name)) {
			foreach (self::$data[$name] as $position_name => $module) {
				self::module($module);
			}
		}
	}

	public static function load_by_name($name, $add_params = array()) {
		if (isset(self::$data[$name])) {
			self::module(self::$data[$name], $add_params);
		}
	}

	public static function load_by_id($id, $add_params = array()) {
		if (isset(self::$data[$id])) {
			self::module(self::$data[$id], $add_params);
		}
	}

	/**
	 * Получение числа модулей расположенных в определённой позиции
	 * @param string $name название позиции
	 * @return int число модулей в выбранной позиции
	 */
	public static function count_by_position($name) {
		return isset(self::$data[$name]) ? count(self::$data[$name]) : false;
	}

	/**
	 * Проверка наличия модулей определённой позиции
	 * @param string $name название позиции
	 * @return bool наличие модулей в выбранной позиции
	 */
	public static function in_position($name) {
		return isset(self::$data[$name]);
	}

	/**
	 * Подключение (вывод) модуля в тело страницы
	 * @var module stdClass Объект модуля
	 */
	public static function module($module = null, $add_params = array()) {

		if (!$module) {
			return;
		}

		//Определяем имя главного исполняемого файла модуля
		$name = $module->module ? $module->module : 'custom';
		$file = JPATH_BASE . DS . 'app' . DS . 'modules' . DS . $name . DS . $name . '.php';

		//Пытаемся сразу определить шаблон для вывода
		$module->template_path = self::module_template($module);


		//Разворачиваем параметры модуля
		$params = json_decode($module->params, true);
		if ($add_params) {
			$params = array_merge($params, $add_params);
		}

		$object_data = self::$_object_data;

		//Подключаем модуль
		is_file($file) ? require ($file) : null;
	}

	/**
	 * Определение имени шаблона для вывода
	 * @var name str Имя модуля
	 * @var params array Массив параметров
	 */
	private static function module_template($module) {
		$_tpl = $module->template ? $module->template : 'default';
		$name = $module->module ? $module->module : 'custom';
		$_tpl_file = JPATH_BASE . DS . 'app' . DS . 'modules' . DS . $name . DS . 'views' . DS . $_tpl . '.php';
		return is_file($_tpl_file) ? $_tpl_file : null;
	}

}
