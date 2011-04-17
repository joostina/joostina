<?php

/**
 * Автозагрузчик классов
 *
 * @package Joostina.Core
 * @author JoostinaTeam
 * @copyright (C) 2008-2011 Joostina Team
 * @license MIT License http://www.opensource.org/licenses/mit-license.php
 * @version SVN: $Id: joostina.php 238 2011-03-13 13:24:57Z LeeHarvey $
 * Иинформация об авторах и лицензиях стороннего кода в составе Joostina CMS: docs/copyrights
 */
// запрет прямого доступа
defined('_JOOS_CORE') or die();

/**
 * Автозагрузчик классов
 * @sinletone
 */
class joosAutoloader {

	/**
	 * Скрытая интанция автозагрузчика
	 * @var @static joosAutoloader
	 */
	private static $_instance;
	private static $_static_files = array(
		// проверенные библиотеки
		'joosArray' => 'core/lib/array.php',
		'joosAttached' => 'core/lib/attached.php',
		'joosBenchmark' => 'core/lib/benchmark.php',
		'joosDatabase' => 'core/lib/database.php',
		'joosDBModel' => 'core/lib/database.php',
		'joosDateTime' => 'core/lib/datetime.php',
		'joosDebug' => 'core/lib/debug.php',
		'joosEditor' => 'core/lib/editor.php',
		'joosImage' => 'core/lib/image.php',
		'joosInputFilter' => 'core/lib/inputfilter.php',
		'joosAutoAdmin' => 'core/lib/autoadmin.php',
		'joosMetainfo' => 'core/lib/metainfo.php',
		'joosNestedSet' => 'core/lib/nestedset.php',
		'joosParams' => 'core/lib/params.php',
		'joosString' => 'core/lib/string.php',
		'joosText' => 'core/lib/text.php',
		'joosTrash' => 'core/lib/trash.php',
		// системные модели
		'User' => 'app/components/users/models/users.class.php',
		// Это старьё, надо переписать либо удалить
		'htmlTabs' => 'core/libraries/html/html.php'
	);

	public static function instance() {

		if (self::$_instance === NULL) {
			self::$_instance = new self;
		}
		return self::$_instance;
	}

	public static function init() {
		self::instance();
	}

	private function __construct() {
		spl_autoload_register(array($this, 'autoload'));
	}

	private function __clone() {

	}

	public static function autoload($class) {

		$file = $class . '.php';

		// первый шаг - ищем класс в жестко прописанных параметрах
		if (isset(self::$_static_files[$class])) {
			$file = JPATH_BASE . DS . self::$_static_files[$class];
		} else {
			$file = JPATH_BASE . DS . self::get_class_dinamic_path($class);
		}

		if (!is_file($file)) {
			throw new AutoloaderFileNotFoundException(sprintf(__('Автозагрузчик классов не смог обнаружить предпологаемый файл %s файл для класса %s'), $file, $class));
		}

		require $file;

		if (!class_exists($class, false)) {
			throw new AutoloaderClassNotFoundException(sprintf(__('Автозагрузчик классов не смог найти требуемый класс %s в предпологаемом файле %s'), $class, $file));
		}

		!JDEBUG ? : joosDebug::add(sprintf(__('Автозагрузка класса %s из файла %s'), $class, $file));

		unset($file);
	}

	private static function get_class_dinamic_path($class) {
		//echo $class.'<br />';

		$file = '';
		if (strpos($class, 'admin', 0) === 0) {
			$name = str_replace('admin', '', $class);
			$name = strtolower($name);
			$file = 'app' . DS . 'components/' . $name . DS . 'models' . DS . 'admin.' . $name . '.class.php';
		} else {
			$name = strtolower($class);
			$file = 'app' . DS . 'components/' . $name . DS . 'models' . DS . $name . '.class.php';
		}

		return $file;
		die();
	}

}

/**
 * Обработка исключений отсутствующих классов при работе автозагрузчика
 */
class AutoloaderFileNotFoundException extends joosException {

}

/**
 * Обработка исключений отсутсвия нужных классов в найденных файлах автозагрузчика
 */
class AutoloaderClassNotFoundException extends joosException {

}

