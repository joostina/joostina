<?php

// запрет прямого доступа
defined( '_JOOS_CORE' ) or die();

/**
 * joosModule - Общий класс работы с модулями ( на фронте )
 * Системная библиотека
 *
 * @version    1.0
 * @package    Joostina.Libraries
 * @subpackage Libraries
 * @category   Libraries
 * @category   modelModules
 * @category   joosModule
 * @author     Joostina Team <info@joostina.ru>
 * @copyright  (C) 2007-2011 Joostina Team
 * @license    MIT License http://www.opensource.org/licenses/mit-license.php
 * Информация об авторах и лицензиях стороннего кода в составе Joostina CMS: docs/copyrights
 *
 * */
class joosModule extends modelModules {

	private static $data = array ();
	private static $_object_data = array ();

	public static function get_data() {
		return self::$data;
	}

	public static function add_array( array $modules ) {
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
	public static function modules_by_page( $controller , $method , $object_data = array () ) {

		$modules_pages = new modelModulesPages;
		$modules       = $modules_pages->get_list( array ( 'select' => "mp.*,m.*" ,
		                                                   'join'   => 'AS mp INNER JOIN #__modules AS m ON ( m.id = mp.moduleid AND m.state = 1 AND m.client_id = 0 )' ,
		                                                   'where'  => sprintf( "mp.controller = 'all' OR mp.controller ='%s'" , $controller ) ,
		                                                   'order'  => 'm.position, m.ordering' ) );

		if ( count( $modules ) > 0 ) {
			$by_position = array ();
			$by_name     = array ();
			$by_id       = array ();

			foreach ( $modules as $module ) {
				if ( $module->controller == 'all' || ( !$module->method || ( $module->method == $method ) ) ) {
					$by_position[$module->position][$module->id] = $module;
					$by_name[$module->module]                    = $module;
					$by_id[$module->id]                          = $module;
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
	 *
	 * @param string $name название позиции
	 */
	public static function load_by_position( $name ) {
		if ( self::in_position( $name ) ) {
			foreach ( self::$data[$name] as $position_name => $module ) {
				self::module( $module );
			}
		}
	}

	public static function load_by_name( $name , $add_params = array () ) {
		if ( isset( self::$data[$name] ) ) {
			self::module( self::$data[$name] , $add_params );
		}
	}

	public static function load_by_id( $id , $add_params = array () ) {
		if ( isset( self::$data[$id] ) ) {
			self::module( self::$data[$id] , $add_params );
		}
	}

	/**
	 * Получение числа модулей расположенных в определённой позиции
	 *
	 * @param string $name название позиции
	 *
	 * @return int число модулей в выбранной позиции
	 */
	public static function count_by_position( $name ) {
		return isset( self::$data[$name] ) ? count( self::$data[$name] ) : false;
	}

	/**
	 * Проверка наличия модулей определённой позиции
	 *
	 * @param string $name название позиции
	 *
	 * @return bool наличие модулей в выбранной позиции
	 */
	public static function in_position( $name ) {
		return isset( self::$data[$name] );
	}

	/**
	 * Подключение (вывод) модуля в тело страницы
	 *
	 * @var module stdClass Объект модуля
	 */
	public static function module( $module = null , $add_params = array () ) {

		if ( !$module ) {
			return;
		}

		//Определяем имя главного исполняемого файла модуля
		$name = $module->module ? $module->module : 'custom';
		$file = JPATH_BASE . DS . 'app' . DS . 'modules' . DS . $name . DS . $name . '.php';

		//Пытаемся сразу определить шаблон для вывода
		$module->template_path = self::module_template( $module );

		// проверяем доступность основного файла модуля
		if ( !is_file( $file ) ) {
			throw new joosModulesException( 'Файл модуля :module_name не обнаружен в ожидаемом месте :module_location' , array ( ':module_name'     => $name ,
			                                                                                                                     ':module_location' => $file ,
			                                                                                                                     ':error_code'      => 404 ) );
		}

		// параметры модуля по-умолчанию
		$params = array ( 'template'            => JPATH_BASE . DS . 'app' . DS . 'modules' . DS . $name . DS . 'views' . DS . 'default.php' ,
			// прямой режим работы модуля без использования внешнего шаблона
			              'modules_no_template' => false );

		//Разворачиваем параметры модуля
		$params_module = json_decode( $module->params , true );
		// параметры модуля перезапишут значения по-умолчанию
		if ( $params_module ) {
			$params = $params_module + $params;
		}
		// расширенные параметры модуля перезапишут значения настроек и по-умолчанию
		if ( $add_params ) {
			$params = $params + $add_params;
		}

		$object_data = self::$_object_data;

		require $file;

		// проверяем доступность файла шаблона модуля и подключаем его
		if ( $params['modules_no_template'] == false && !is_file( $params['template'] ) ) {
			throw new joosModulesException( 'Шаблон для модуля :module_name не обнаружен в ожидаемом месте :template_location' , array ( ':template_location' => $params['template'] ,
			                                                                                                                             ':module_name'       => $name ,
			                                                                                                                             ':module_location'   => $file ,
			                                                                                                                             ':error_code'        => 404 ) );
			// в шаблоне явно не указано что он может работать без внешнего шаблона
		} elseif ( $params['modules_no_template'] == false ) {
			require_once $params['template'];
		}
	}

	/**
	 * Определение имени шаблона для вывода
	 *
	 * @var name str Имя модуля
	 * @var params array Массив параметров
	 */
	private static function module_template( $module ) {
		$_tpl      = $module->template ? $module->template : 'default';
		$name      = $module->module ? $module->module : 'custom';
		$_tpl_file = JPATH_BASE . DS . 'app' . DS . 'modules' . DS . $name . DS . 'views' . DS . $_tpl . '.php';
		return is_file( $_tpl_file ) ? $_tpl_file : null;
	}

}

class joosModulesException extends joosException {

}