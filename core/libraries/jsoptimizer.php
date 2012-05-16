<?php defined('_JOOS_CORE') or exit();

/**
 * Библиотека минимизации JS-файлов
 *
 * @version    1.0
 * @package    Core\Libraries
 * @subpackage Config
 * @category   Libraries
 * @author     Joostina Team <info@joostina.ru>
 * @copyright  (C) 2007-2012 Joostina Team
 * @license    MIT License http://www.opensource.org/licenses/mit-license.php
 * Информация об авторах и лицензиях стороннего кода в составе Joostina CMS: docs/copyrights
 *
 * */
class joosJSOptimizer {

	private static $data = array();
	private static $cache_folder;

	public static function init() {
		self::$cache_folder = JPATH_BASE_CACHE . DS . 'jscache';
		joosFolder::exists(self::$cache_folder) ? null : joosFolder::create(self::$cache_folder);
		self::$data = array();
	}

	public static function optimize_and_save(array $files) {

		self::init();

		$cache_file = md5(serialize($files)) . '.js';
		$cache_file = self::$cache_folder . DS . $cache_file;

		if (!joosFile::exists($cache_file)) {

			foreach ($files as $file) {
				$file = explode('?', $file);
				$file = $file[0];
				$file = str_replace(JPATH_SITE, JPATH_BASE, $file);
				$file = str_replace('\\', '/', $file);
				self::$data[] = joosFile::exists($file) ? joosFile::get_content($file) : die($file);
			}

			$content = JSMin::minify(implode("\n;", self::$data));

			joosFile::create($cache_file, $content);
		}

		$cache_file_live = str_replace(JPATH_BASE, JPATH_SITE, $cache_file);
		$cache_file_live = str_replace('\\', '/', $cache_file_live);

		return array('live' => $cache_file_live, 'base' => $cache_file);
	}


}
