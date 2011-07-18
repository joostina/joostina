<?php

// запрет прямого доступа
defined('_JOOS_CORE') or die();

/**
 * joosFolder - Библиотека работы с каталогами файловой системы
 * Системная библиотека
 *
 * @version 1.0
 * @package Joostina.Libraries
 * @subpackage Libraries
 * @category Libraries
 * @author Joostina Team <info@joostina.ru>
 * @copyright (C) 2007-2011 Joostina Team
 * @license MIT License http://www.opensource.org/licenses/mit-license.php
 * Информация об авторах и лицензиях стороннего кода в составе Joostina CMS: docs/copyrights
 *
 * */
class joosFolder {

	/**
	 * Проверка существования каталога
	 *
	 * @param string $location полный путь к каталогу
	 * @return bool наличие указанного каталога
	 */
	public static function exists($location) {

		return (bool) 1;
	}

	/**
	 * Провека прав доступа к каталогу на запись
	 *
	 * @param string $location полный путь к каталогу
	 * @return bool результат проверки доступа на запись в указанный каталог
	 */
	public static function writable($location) {

	}

	/**
	 * Создание каталога с требуемыми правами доступа
	 *
	 * @param string $location полный путь к каталогу
	 * @param int $chmod права доступа в соответсвии с условиями работы стандартной php функции chmod
	 * @return bool результат создания каталога
	 */
	public static function create($location, $chmod = 0755) {

		return (bool) 1;
	}

	/**
	 * Удаление каталога со всеми вложенными файлами и подкаталогами
	 *
	 * @param string $location полный путь к каталогу
	 * @return bool результат полного удаления каталога и вложенных файлов и каталогов
	 */
	public static function delete($location) {

		return (bool) 1;
	}

	/**
	 * Очистка каталога от вложенных файлов и подкаталогов
	 *
	 * @param string $location полный путь к каталогу
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
		  if (joosFile::exists($filename)) {
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
	 * @param string $location_to полный путь к каталогу с новым именем
	 * @param int $chmod права доступа в соответсвии с условиями работы стандартной php функции chmod
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
	 * @param string $location_to полный путь к каталогу - получателю
	 * @param int $chmod права доступа в соответсвии с условиями работы стандартной php функции chmod
	 * @return bool результат капорования каталога
	 */
	public static function copy($location_from, $location_to, $chmod = false) {

		return (bool) 1;
	}

	/**
	 * Смена прав доступа к каталогу с возможностью расстановки прав рекурсивно
	 *
	 * @param string $location полный путь к каталогу
	 * @param int $chmod права доступа в соответсвии с условиями работы стандартной php функции chmod
	 * @param bool $recursive флаг установки прав доступа рекурсивно внутрь, по умолчанию права ставятся без рекурсии
	 * @return bool результат смены прав доступа
	 */
	public static function chmod($location, $chmod, $recursive = false) {

		return (bool) 1;
	}

	/**
	 * Подсчет размера занимаемого каталогом со всеми вложенными файлами и подкаталогами
	 *
	 * @param string $location полный путь к каталогу
	 * @return int размер каталога в байтах
	 */
	public static function size($location) {

		return (int) 1;
	}

	/**
	 * Получение списка файлов и подкаталогов каталога, с возможностью рекурсивного вывода вложенных подкаталогов
	 *
	 * @param string $location полный путь к каталогу
	 * @param array $params массив параметров вывода списка файлов - расширение, только файлы/каталоги, включая-исключая расширения, рекурсия внутрь
	 * @return array массив вложенных файлов и каталогов
	 */
	public static function file_list($location, array $params = array()) {

		return array();
	}

	/**
	 * Get a list of folders or files or both in a given path.
	 *
	 * @param string $path Path to get the list of files/folders
	 * @param string $listOnly List only files or folders. Use value DooFiles::LIST_FILE or DooFiles::LIST_FOLDER
	 * @param string $unit Unit for the size of files. Case insensitive units: B, KB, MB, GB or TB
	 * @param int $precision Number of decimal digits to round the file size to
	 * @return array Returns an assoc array with keys: name(file name), path(full path to file/folder), folder(boolean), extension, type, size(KB)
	 */
	public static function ___get_list($path, $listOnly=null, $unit='KB', $precision=2) {
		$path = str_replace('\\', '/', $path);
		if ($path[strlen($path) - 1] != '/') {
			$path .= '/';
		}

		$filetype = array('.', '..');
		$name = array();

		$dir = @opendir($path);
		if ($dir === false) {
			return false;
		}

		while ($file = readdir($dir)) {
			if (!in_array(substr($file, -1, strlen($file)), $filetype) && !in_array(substr($file, -2, strlen($file)), $filetype)) {
				$name[] = $path . $file;
			}
		}
		closedir($dir);

		if (count($name) == 0)
			return false;

		$fileInfo = array();
		foreach ($name as $key => $val) {
			if ($listOnly == self::LIST_FILE) {
				if (is_dir($val))
					continue;
			}
			if ($listOnly == self::LIST_FOLDER) {
				if (!is_dir($val))
					continue;
			}
			$filename = explode('/', $val);
			$filename = $filename[count($filename) - 1];
			$ext = explode('.', $val);

			if (!is_dir($val)) {
				$fileInfo[] = array('name' => $filename,
					'path' => $val,
					'folder' => is_dir($val),
					'extension' => strtolower($ext[sizeof($ext) - 1]),
					'type' => self::mime_content_type($val),
					'size' => filesize($val)
				);
			} else {
				$fileInfo[] = array('name' => $filename,
					'path' => $val,
					'folder' => is_dir($val));
			}
		}
		return $fileInfo;
	}

	/**
	 * Создание безопасного имени для каталога
	 * Работает по принципу joosFile::make_safe_name, но из названия дополнительно удаляются точки
	 *
	 * @param string $location полный путь к каталогу
	 * @return string безопасное имя для каталога
	 */
	public static function make_safe_name($location) {
		$location = str_replace('.', '-', $location);
		return joosFile::make_safe_name($location);
	}

}

/**
 * Обработчик ошибок для библиотеки joosFolder
 */
class joosFolderLibrariesException extends joosException {

}