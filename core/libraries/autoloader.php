<?php

/**
 * Автозагрузчик классов
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
// запрет прямого доступа
defined('_JOOS_CORE') or die();

/**
 * Автозагрузчик классов
 * @sinletone
 */
class joosAutoloader {

	/**
	 * Массив предустановленных системных библиотек
	 *
	 * @var array
	 */
	private static $_static_files = array(// проверенные библиотеки
		'joosArray' => 'core/libraries/array.php',
		'joosAttached' => 'core/libraries/attached.php',
		'joosBenchmark' => 'core/libraries/benchmark.php',
		'joosBreadcrumbs' => 'core/libraries/breadcrumbs.php',
		'joosCache' => 'core/libraries/cache.php',
		'joosConfig' => 'core/libraries/config.php',
		'joosDatabase' => 'core/libraries/database.php',
		'joosModel' => 'core/libraries/database.php',
		'joosDateTime' => 'core/libraries/datetime.php',
		'joosDebug' => 'core/libraries/debug.php',
		'joosEditor' => 'core/libraries/editor.php',
		'joosEvents' => 'core/libraries/events.php',
		'joosFile' => 'core/libraries/file.php',
		'joosFilter' => 'core/libraries/filter.php',
		'joosFlashMessage' => 'core/libraries/flashmessage.php',
		'joosHit' => 'core/libraries/hit.php',
		'joosHTML' => 'core/libraries/html.php',
		'joosImage' => 'core/libraries/image.php',
		'joosInflector' => 'core/libraries/inflector.php',
		'joosInputFilter' => 'core/libraries/inputfilter.php',
		'joosAutoadmin' => 'core/libraries/autoadmin.php',
		'joosMetainfo' => 'core/libraries/metainfo.php',
		'joosNestedSet' => 'core/libraries/nestedset.php',
		'joosPager' => 'core/libraries/pager.php',
		'joosParams' => 'core/libraries/params.php',
		'joosRandomizer' => 'core/libraries/randomizer.php',
		'joosRequest' => 'core/libraries/request.php',
		'joosRoute' => 'core/libraries/route.php',
		'joosSession' => 'core/libraries/session.php',
		'joosSpoof' => 'core/libraries/spoof.php',
		'joosString' => 'core/libraries/string.php',
		'joosText' => 'core/libraries/text.php',
		'joosTrash' => 'core/libraries/trash.php',
		'joosValidate' => 'core/libraries/validate.php',
		'joosValidateHelper' => 'core/libraries/validate.php',
		'joosVersion' => 'core/libraries/version.php',
		'joosLogging' => 'app/vendors/logging/logging.php',
        'modelAclGroups' => 'app/components/acls/models/model.acls.php',
        'modelAdminAclGroups'=>'app/components/acls/models/model.admin.acls.php',
        'modelAdminAclList'=>'app/components/acls/models/model.admin.acls.php',
        'helperAcl'=> 'app/components/acls/models/model.acls.php',
        // Это старьё, надо переписать либо удалить
		'htmlTabs' => 'core/libraries/html.php',
		'forms' => 'app/vendors/forms/forms.php',
		// Библиотеки сторонних вендоров
		'JJevix' => 'app/vendors/text/jevix/jevix.php',
		// пока не адаптированные библиотеки
		'Thumbnail' => 'core/libraries/image.php',
	);
	private static $_debug = true;

	public static function init() {
		$app_autoload_files = require_once JPATH_APP_CONFIG . DS . 'autoload.php';

		self::$_static_files = array_merge($app_autoload_files, self::$_static_files);

		spl_autoload_register(array(new self, 'autoload'));
	}

	private function __clone() {

	}

	public static function autoload($class) {

		// первый шаг - ищем класс в жестко прописанных параметрах
		if (isset(self::$_static_files[$class])) {
			$file = JPATH_BASE . DS . self::$_static_files[$class];
		} else {
			$file = JPATH_BASE . DS . self::get_class_dinamic_path($class);
		}

		if (!is_file($file)) {

			throw new joosAutoloaderFileNotFoundException(sprintf(__('Автозагрузчик классов не смог обнаружить предпологаемый файл %s файл для класса %s'), $file, $class));
		}

		require_once $file;

		if (!class_exists($class, false)) {
			
			throw new joosAutoloaderClassNotFoundException(sprintf(__('Автозагрузчик классов не смог найти требуемый класс %s в предпологаемом файле %s'), $class, $file));
		}
		!self::$_debug ? : joosDebug::add(sprintf(__('Автозагрузка класса %s из файла %s'), $class, $file));

		unset($file);
	}

	private static function get_class_dinamic_path($class) {

		//$file = '';
		// контроллеры панели управления
		if (strpos($class, 'actionsAdmin', 0) === 0) {
			$name = str_replace('actionsAdmin', '', $class);
			$name = strtolower($name);
			$file = 'app' . DS . 'components' . DS . $name . DS . 'controller.admin.' . $name . '.php';

			// аякс-контроллеры панели управления
		} elseif (strpos($class, 'actionsAjaxAdmin', 0) === 0) {
			$name = str_replace('actionsAjaxAdmin', '', $class);
			$name = strtolower($name);
			$file = 'app' . DS . 'components' . DS . $name . DS . 'controller.admin.' . $name . '.ajax.php';

			// аякс-контроллеры фронта
		} elseif (strpos($class, 'actionsAjax', 0) === 0) {
			$name = str_replace('actionsAjax', '', $class);
			$name = strtolower($name);
			$file = 'app' . DS . 'components' . DS . $name . DS . 'controller.' . $name . '.ajax.php';

			// контроллеры фронта
		} elseif (strpos($class, 'actions', 0) === 0) {
			$name = str_replace('actions', '', $class);
			$name = strtolower($name);
			$file = 'app' . DS . 'components' . DS . $name . DS . 'controller.' . $name . '.php';

			// системные библиотеки
		} elseif (strpos($class, 'joos', 0) === 0) {
			$name = str_replace('joos', '', $class);
			$name = strtolower($name);
			$file = 'core' . DS . 'libraries' . DS . $name . '.php';

			// модели панели управления
		} elseif (strpos($class, 'modelAdmin', 0) === 0) {
			$name = str_replace('modelAdmin', '', $class);
			$name = strtolower($name);
			$file = 'app' . DS . 'components' . DS . $name . DS . 'models' . DS . 'model.admin.' . $name . '.php';

			// модели сайта
		} elseif (strpos($class, 'model', 0) === 0) {
			$name = str_replace('model', '', $class);
			$name = strtolower($name);
			$file = 'app' . DS . 'components' . DS . $name . DS . 'models' . DS . 'model.' . $name . '.php';

			// хелперы модулей
		} elseif (strpos($class, 'modulesHelper', 0) === 0) {
			$name = str_replace('modulesHelper', '', $class);
			$name = strtolower($name);
			$file = 'app' . DS . 'modules' . DS . $name . DS . 'helper.' . $name . '.php';

			// модели фронта
		} else {
			throw new joosAutoloaderFileNotFoundException('Правило загрузки для класса :class_name не обнаружено', array(':class_name' => $class));
		}

		return $file;
	}
    
    /**
     *  Предстартовая загрузка необходимого списка
     * 
     * @tutorial joosAutoloader( array('text','array','acl') )
     * 
     * @static
     * @param array $names
     * @throws joosAutoloaderOnStartFileNotFoundException
     */
	public static function libraries_load_on_start(array $names = array()) {

		foreach ($names as $name) {

			$file = JPATH_BASE . DS . 'core' . DS . 'libraries' . DS . $name . '.php';

			if (! joosFile::exists($file)) {
				throw new joosAutoloaderOnStartFileNotFoundException(
                    sprintf(__('Автозагрузчки не смог найти файл %s для автозагружаемой библиотеки %'), $file, $name)
                );
			}

			require_once ( $file );
		}
	}

}

/**
 * Обработка исключений отсутствующих классов при работе автозагрузчика
 */
class joosAutoloaderFileNotFoundException extends joosException {

	public function __construct($message = '', array $params = array()) {

/*        
        $backtrace_error = debug_backtrace();

        if (isset($backtrace_error[1])) {
            $params[':error_file'] = $backtrace_error[3]['file'];
            $params[':error_line'] = $backtrace_error[3]['line'];
        }
*/
        
		parent::__construct($message, $params);
	}

}

/**
 * Обработка исключений отсутсвия нужных классов в найденных файлах автозагрузчика
 */
class joosAutoloaderClassNotFoundException extends joosException {

}

/**
 * Обработка исключений отсутствующих классов при работе автозагрузчика
 */
class joosAutoloaderOnStartFileNotFoundException extends joosException {

}
