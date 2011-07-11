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

	public static function exist($location) {
		
	}

	public static function writable($location) {
		
	}

	public static function create($location, $chmod = 0755) {
		
	}

	public static function delete($location) {
		
	}

	public static function clear($location) {
		$iterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($location),
						RecursiveIteratorIterator::CHILD_FIRST);
		foreach ($iterator as $path) {
			if ($path->isDir()) {
				//rmdir($path->__toString());
				echo $path->__toString();
			} else {
				//unlink($path->__toString());
				echo $path->__toString();
			}
		}
//    rmdir($dir);
	}

	public static function rename($location_from, $location_to, $chmod = false) {
		
	}

	public static function copy($location_from, $location_to) {
		
	}

	public static function chmod($location, $chmod, $recursive = false) {
		
	}

	public static function size($location) {
		
	}

	public static function file_list($location, array $params = array()) {
		
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
	public static function get_list($path, $listOnly=null, $unit='KB', $precision=2) {
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

	public static function make_safe_name($location) {
		$location = str_replace('.', '-', $location);
		return joosFile::make_safe_name($location);
	}

}

/**
 * Обработчик ошибок для библиотеки joosFile
 */
class joosFolderLibrariesException extends joosException {
	
}