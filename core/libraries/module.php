<?php defined('_JOOS_CORE') or exit();

/**
 * joosModule - Общий класс работы с модулями ( на фронте )
 *
 * @version    2.0
 * @package    Core\Libraries
 * @subpackage Module
 * @category   Libraries
 * @category   joosModule
 * @author     Joostina Team <info@joostina.ru>
 * @copyright  (C) 2007-2012 Joostina Team
 * @license    MIT License http://www.opensource.org/licenses/mit-license.php
 * Информация об авторах и лицензиях стороннего кода в составе Joostina CMS: docs/copyrights
 *
 * */
class joosModule {

	/**
	 * Весь конфиг modules.php
	 */
	private static $data = array();

	/**
	 * Конфиг, пересортированный по ключу "position"
	 */
	private static $modules_by_positions = array();

	/**
	 * Конфиг, пересортированный по ключу "route"
	 */
	private static $modules_by_routes = array();

	/**
	 * Результаты выполнения текущего метода контроллера
	 */
	private static $controller_data = array();


	/**
	 * Инициализация модулей. На этом этапе пробегаемся по массиву параметров
	 */
	public static function init() {
		if (empty(self::$data)) {
			self::$data = require_once JPATH_APP_CONFIG . DS . 'modules.php';

			foreach (self::$data as $name => $m) {
				$modules_by_name[$name] = $m;
				foreach ($m as $m_r) {
					self::$modules_by_routes[$m_r['route']][$name] = $m_r;
					self::$modules_by_positions[$m_r['position']][$name][] = $m_r;
				}
			}
		}
	}

	/**
	 * Передача данных выполнения метода текущего контроллера
	 * Данные передаются из joosController::views()
	 * @var $controller_data array
	 */
	public static function set_controller_data($controller_data) {
		self::$controller_data = $controller_data;
	}

	/**
	 * Получение данных выполнения метода текущего контроллера
	 * @return $controller_data array
	 */
	public static function get_controller_data() {
		return self::$controller_data;
	}


	/**
	 * Получение всего содержимого конфига modules.php
	 * @return array
	 */
	public static function get_data() {
		return self::$data;
	}

	/**
	 * Запуск подключения модулей по заданной позиции
	 * Учитывается текущее правило роутинга
	 * если $name == '__all' - модуль выводится на всех страницах
	 * @var name str Имя позиции
	 */
	public static function load_by_position($name) {

		$modules = '';

		//@todo Рефакторинг
		if (isset(self::$modules_by_positions[$name])) {
			foreach (self::$modules_by_positions[$name] as $name => $modules_array) {

				foreach ($modules_array as $params) {

					$params['action'] = isset($params['action']) ? $params['action'] : 'default_action';

					if (joosController::$activroute == $params['route'] || $params['route'] == '__all') {
						$params['params'] = isset($params['params']) ? $params['params'] : array();
						$modules .= self::execute($name, $params['action'], $params['params']);
					}
					elseif ($params['route'] == '__exept') {
						if (!in_array(joosController::$activroute, $params['__exept_routes'])) {
							$params['params'] = isset($params['params']) ? $params['params'] : array();
							$modules .= self::execute($name, $params['action'], $params['params']);
						}
					}
				}
			}
		}

		return $modules;
	}

	/**
	 * Получение массива модулей для заданного правила роутинга
	 * @var route_name str Правило роутинга
	 * @return array Массив массивов модулей, где ключом является имя позиции, в которой выводится модуль
	 */
	public static function get_array_by_route($route_name) {

		$modules = array();

		//@todo Рефакторинг

		if (isset(self::$modules_by_routes[$route_name])) {
			foreach (self::$modules_by_routes[$route_name] as $name => $params) {
				$params['action'] = isset($params['action']) ? $params['action'] : 'default_action';
				$params['params'] = isset($params['params']) ? $params['params'] : array();
				$modules[$params['position']][] = self::execute($name, $params['action'], $params['params']);
			}
		}

		//для модулей, которые должны выводиться на всех страницах
		foreach (self::$modules_by_routes['__all'] as $name => $params) {
			$params['action'] = isset($params['action']) ? $params['action'] : 'default_action';
			$params['params'] = isset($params['params']) ? $params['params'] : array();
			$modules[$params['position']][] = self::execute($name, $params['action'], $params['params']);
		}

		//для модулей, которые должны выводиться на всех страницах, кроме заданных
		foreach (self::$modules_by_routes['__exept'] as $name => $params) {
			if (!in_array($route_name, $params['__exept_routes'])) {
				$params['action'] = isset($params['action']) ? $params['action'] : 'default_action';
				$params['params'] = isset($params['params']) ? $params['params'] : array();
				$modules[$params['position']][] = self::execute($name, $params['action'], $params['params']);
			}

		}

		return $modules;
	}


	/**
	 * Исполнение модуля и его метода с указанными параметрами
	 * @var name str Имя модуля
	 * @var action str исполняемый метод модуля
	 * @var $config array Массив параметров
	 */
	public static function execute($name, $action = 'default_action', $config = array()) {

		$file = JPATH_BASE . DS . 'app' . DS . 'modules' . DS . $name . DS . $name . '.php';
		require_once($file);

		$class = 'moduleActions' . ucfirst($name);
		if (!method_exists($class, $action)) {
			throw new joosModulesException("Метод $action в модуле $name не обнаружен");
		}

		//устанавливаем параметры вызова
		$class::$config = $config;
		$class::$task = $action;

		//вызов метода и его оберток
		if (method_exists($class, 'action_before')) {
			call_user_func_array($class . '::action_before', array());
		}
		$results = call_user_func($class . '::' . $action);
		if (method_exists($class, 'action_after')) {
			call_user_func_array($class . '::action_after', array($results));
		}

		moduleActions::set_current_module_action_results($results);

		//вывод результатов
		if (is_array($results)) {
			return self::as_html($results, $name, $action, $config);
		}
		elseif (is_string($results)) {
			return $results;
		}
	}

	/**
	 * Вывод шаблона модуля в зависимости от того, что нагенерил класс модуля
	 */
	private static function as_html($params, $module_name, $method, $config) {

		//сначала смотрим в настройки конфига
		$template = isset($config['template']) ? $config['template'] : 'default';
		//но то, что пришло по результатм выполнения метода считаем более приоритетным
		$template = isset($params['template']) ? $params['template'] : $template;

		$view = isset($params['view']) ? $params['view'] : $method;

		extract($params, EXTR_OVERWRITE);

		$viewfile = JPATH_BASE . DS . 'app' . DS . 'modules' . DS . $module_name . DS . 'views' . DS . $view . DS . $template . '.php';

		$template_engine = joosTemplater::instance(isset($params['template_engine']) ? $params['template_engine'] : 'default');

		$output = '<div class="module module-' . $module_name . '">';
		$output .= $template_engine->render_file($viewfile, $params);
		$output .= '</div>';

		return $output;
	}
}

/**
 * Родительский класс-заглушка для модулей
 */
class moduleActions {

	//параметры вызова модуля
	public static $config = array();

	//вызываемый метод модуля
	public static $task = '';

	//сохраняем результаты выполнения методов (для благодарных потомков)
	private static $current_module_action_results;

	public static function set_current_module_action_results($results) {
		self::$current_module_action_results = $results;
	}

	public static function get_current_module_action_results() {
		return self::$current_module_action_results;
	}

}

/**
 * Обработка исключений работы с модулями
 */
class joosModulesException extends joosException {

}