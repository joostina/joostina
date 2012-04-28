<?php defined('_JOOS_CORE') or exit();

/**
 * joosModule - Общий класс работы с модулями ( на фронте )
 *
 * @version    1.0
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
class joosModule  {

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
	 * @static
	 *
	 * @param string $controller текущий контроллер
	 * @param string $method     метод текущего контроллера
	 * @param array  $object_data
	 */
	public static function modules_by_page($controller, $method, $object_data = array()) {

		$modules_pages = new modelModulesPages;
		$modules = $modules_pages->get_list(array('select' => "mp.*,m.*",
			'join' => 'AS mp INNER JOIN #__modules AS m ON ( m.id = mp.moduleid AND m.state = 1 AND m.client_id = 0 )',
			'where' => sprintf("mp.controller = 'all' OR mp.controller ='%s'", $controller),
			'order' => 'm.position, m.ordering'));

		if (count($modules) > 0) {
			$by_position = array();
			$by_name = array();
			$by_id = array();

			foreach ($modules as $module) {
				if ($module->controller == 'all' || (!$module->method || ( $module->method == $method ) )) {
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
	}

    /**
   	 * Загрузка ВСЕХ модулей определённой позиции
   	 * @param string $name название позиции
   	 */
   	public static function load_by_position($name, array $extra = array('hide_frame' => false)) {
   		echo $extra['hide_frame'] == false ? '<span id="jpos_' . $name . '" class="jposition">' : null;
   		if (self::in_position($name)) {
   			foreach (self::$data[$name] as $position_name => $position_params) {
   				self::module($position_name, $position_params, array('hide_frame' => true));
   			}
   		}
   		echo $extra['hide_frame'] == false ? '</span>' : null;
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
	 *
	 * @param string $name название позиции
	 *
	 * @return int число модулей в выбранной позиции
	 */
	public static function count_by_position($name) {
		return isset(self::$data[$name]) ? count(self::$data[$name]) : false;
	}

	/**
	 * Проверка наличия модулей определённой позиции
	 *
	 * @param string $name название позиции
	 *
	 * @return bool наличие модулей в выбранной позиции
	 */
	public static function in_position($name) {
		return isset(self::$data[$name]);
	}

    /**
   	 * Подключение (вывод) модуля в тело страницы
   	 * @var name str Имя модуля
   	 * @var params array Массив параметров
   	 */
   	public static function module($name, array $params = array(), array $extra = array('hide_frame' => false)) {

   		$name = preg_replace('/\|/', '', $name);
   		$name = preg_replace('/[0-9][_]/', '', $name);

   		//Определяем имя главного исполняемого файла модуля
   		$file = JPATH_BASE . DS . 'app'. DS. 'modules' . DS . $name . DS . $name . '.php';

        //Определение шаблона
        $template = (isset($params) && isset($params['template'])) ? $params['template'] : 'default';
        $template_file = JPATH_BASE . DS .'app'. DS. 'modules' . DS . $name . DS . 'views' . DS . $template . '.php';
        $params['template_file'] =  joosFile::exists($template_file) ? $template_file : null;

        echo '<div class="module module-'.$name.'">';
             joosFile::exists($file) ? require($file) : null;
        echo '</div>';
   	}

    /**
   	 * Определение имени шаблона для вывода
   	 * @var name str Имя модуля
   	 * @var params array Массив параметров
     * @deprecated
   	 */
   	private static function module_template($name, $params = array()) {
   		$_tpl = (isset($params) && isset($params['template'])) ? $params['template'] : 'default';
   		$_tpl_file = JPATH_BASE . DS .'app'. DS. 'modules' . DS . $name . DS . 'views' . DS . $_tpl . '.php';
   		return  joosFile::exists($_tpl_file) ? $_tpl_file : null;
   	}

	public static function run($name) {
		$_tpl_file = JPATH_BASE . DS . 'app' . DS . 'modules' . DS . $name . DS . $name . '.php';
		if ( joosFile::exists($_tpl_file)) {
			require_once $_tpl_file;
		} else {
			throw new joosException('Файл :file для модуля :module_name не найден', array(':file' => $_tpl_file, ':module_name' => $name));
		}
	}

}

/**
 * Обработка исключение работы с модулями
 * 
 */
class joosModulesException extends joosException {

}
