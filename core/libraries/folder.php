<?php

// запрет прямого доступа
defined('_JOOS_CORE') or die();

/**
 * joosFolder - Библиотека работы с каталогами файловой системы
 * Системная библиотека
 *
 * @version    1.0
 * @package    Joostina.Libraries
 * @subpackage Libraries
 * @category   Libraries
 * @author     Joostina Team <info@joostina.ru>
 * @copyright  (C) 2007-2012 Joostina Team
 * @license    MIT License http://www.opensource.org/licenses/mit-license.php
 * Информация об авторах и лицензиях стороннего кода в составе Joostina CMS: docs/copyrights
 *
 * */
class joosFolder {

	/**
	 * Проверка существования каталога
	 *
	 * @param string $location полный путь к каталогу
	 *
	 * @return bool наличие указанного каталога
	 */
	public static function exists($location) {
		$location = (string) $location;

		if (file_exists($location) && is_dir($location)) {
			return true;
		}

		return false;
	}

	/**
	 * Провека прав доступа к каталогу на запись
	 *
	 * @param string $location полный путь к каталогу
	 *
	 * @return bool результат проверки доступа на запись в указанный каталог
	 */
	public static function is_writable($location) {
		return (bool) (self::exists && is_writable($location));
	}

	/**
	 * Создание каталога с требуемыми правами доступа
	 *
	 * @param string $location полный путь к каталогу
	 * @param int    $chmod    права доступа в соответсвии с условиями работы стандартной php функции chmod
	 *
	 * @return bool результат создания каталога
	 */
	public static function create($location, $chmod = 0755) {

		return (!self::exists($location)) ? mkdir((string) $location, $chmod, true) : true;
	}

	/**
	 * Удаление каталога со всеми вложенными файлами и подкаталогами
	 *
	 * @param string $location полный путь к каталогу
	 *
	 * @return bool результат полного удаления каталога и вложенных файлов и каталогов
	 */
	public static function delete($location) {

		return (bool) 1;
	}

	/**
	 * Очистка каталога от вложенных файлов и подкаталогов
	 *
	 * @param string $location полный путь к каталогу
	 *
	 * @return bool результат очистка каталога
	 */
	public static function clear($location) {
		/*
		  $iterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($location),
		  RecursiveIteratorIterator::CHILD_FIRST);
		  foreach ($iterator as $path) {
		  if ($path->isDir()) {
		  $location = $path->__toString();
		  self::delete($location);
		  } else {
		  $filename = $path->__toString();
		  if (joosFile::is_exists($filename)) {
		  joosFile::delete($filename);
		  }
		  }
		  }
		 */

		return (bool) 1;
	}

	/**
	 * Переименование/перемещение каталога
	 * На перемещенный каталог устанавливаются требуемые права доступа
	 *
	 * @param string $location_from полный путь к каталогу со старым именем
	 * @param string $location_to   полный путь к каталогу с новым именем
	 * @param int    $chmod         права доступа в соответсвии с условиями работы стандартной php функции chmod
	 *
	 * @return bool результат переименования каталога
	 */
	public static function rename($location_from, $location_to, $chmod = false) {

		return (bool) 1;
	}

	/**
	 * Копирование каталога со всеми вложенными файлами и подкаталогами
	 * Содержимое копируется в создаваемый каталог, на него так же можно сразу установить требуемые права доступа
	 *
	 * @param string $location_from полный путь к каталогу - источнику
	 * @param string $location_to   полный путь к каталогу - получателю
	 * @param int    $chmod         права доступа в соответсвии с условиями работы стандартной php функции chmod
	 *
	 * @return bool результат капорования каталога
	 */
	public static function copy($location_from, $location_to, $chmod = false) {

		return (bool) 1;
	}

	/**
	 * Смена прав доступа к каталогу с возможностью расстановки прав рекурсивно
	 *
	 * @param string $location  полный путь к каталогу
	 * @param int    $chmod     права доступа в соответсвии с условиями работы стандартной php функции chmod
	 * @param bool   $recursive флаг установки прав доступа рекурсивно внутрь, по умолчанию права ставятся без рекурсии
	 *
	 * @return bool результат смены прав доступа
	 */
	public static function set_chmod($location, $chmod, $recursive = false) {

		return (bool) 1;
	}

	/**
	 * Подсчет размера занимаемого каталогом со всеми вложенными файлами и подкаталогами
	 *
	 * @param string $location полный путь к каталогу
	 *
	 * @return int размер каталога в байтах
	 */
	public static function get_size($location) {

		return (int) 1;
	}

	/**
	 * Получение списка файлов и подкаталогов каталога, с возможностью рекурсивного вывода вложенных подкаталогов
	 *
	 * @param string $location полный путь к каталогу
	 * @param array  $params   массив параметров вывода списка файлов - расширение, только файлы/каталоги, включая-исключая расширения, рекурсия внутрь
	 *
	 * @return array массив вложенных файлов и каталогов
	 */
	public static function get_file_list($location, array $params = array()) {

		return array();
	}

	/**
	 * Создание безопасного имени для каталога
	 * Работает по принципу joosFile::get_safe_name, но из названия дополнительно удаляются точки
	 *
	 * @param string $location полный путь к каталогу
	 *
	 * @return string безопасное имя для каталога
	 */
	public static function get_safe_name($location) {
		$location = str_replace('.', '-', $location);
		return joosFile::make_safe_name($location);
	}

}

/**
 * Обработчик ошибок для библиотеки joosFolder
 */
class joosFolderLibrariesException extends joosException {

}
