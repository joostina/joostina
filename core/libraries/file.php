<?php

// запрет прямого доступа
defined('_JOOS_CORE') or die();

/**
 * joosFile - Библиотека работы с файлами
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
class joosFile {
	const LIST_FILE = 'file';
	const LIST_FOLDER = 'folder';

	/**
	 * Логическое представление размера файлов, памяти и прочив байтовых данных
	 * 
	 * @example joosFile::convert_size(123);
	 * @example joosFile::convert_size(123456);
	 * 
	 * @param string|num $num исходные строка или число для форматирования
	 * @return string форматированная строка размера
	 */
	public static function convert_size($num) {

		$num = (int) $num;

		if ($num >= 1073741824) {
			$num = round($num / 1073741824 * 100) / 100 . ' ' . __('gb');
		} else if ($num >= 1048576) {
			$num = round($num / 1048576 * 100) / 100 . ' ' . __('mb');
		} else if ($num >= 1024) {
			$num = round($num / 1024 * 100) / 100 . ' ' . __('kb');
		} else {
			$num .= ' ' . __('byte');
		}
		return $num;
	}

	/**
	 * Удаление файла
	 * 
	 * @example joosFile::delete( JPATH_BASE . DS. '_to_delete.php' );
	 * @example joosFile::delete( array( JPATH_BASE . DS. '_to_delete.php', JPATH_BASE . DS. '_to_delete_2.php', );
	 * 
	 * @param string|array $filename полный путь к файлу, либо массив полный путей к удаляемым файлам
	 * @return bool результат удаления
	 */
	public static function delete($filename) {

		if (is_array($filename)) {
			foreach ($filename as $file) {
				self::delete($file);
			}
		}

		// TODo тут надо разобраться с ошибками и исключениями
		try {
			unlink((string) $filename);
		} catch (joosFileLibrariesException $exc) {
			echo $exc->getTraceAsString();
		}

		return true;
	}

	/**
	 * Move/rename a file/folder
	 * @param string $from Original path of the folder/file
	 * @param string $to Destination path of the folder/file
	 * @return bool Returns true if file/folder created
	 */
	public static function move($from, $to, $chmod = null) {
		if (strpos($to, '/') !== false || strpos($to, '\\') !== false) {
			$path = str_replace('\\', '/', $to);
			$path = explode('/', $path);
			array_splice($path, sizeof($path) - 1);

			$path = implode('/', $path);
			if ($path[strlen($path) - 1] != '/') {
				$path .= '/';
			}
			if (!file_exists($path)) {
				mkdir($path, $chmod, true);
			}
		}

		return rename($from, $to);
	}

	/**
	 * Проверка существования файла
	 * 
	 * @example joosFile::exists( JPATH_BASE . DS. 'index.php' );
	 * 
	 * @param string $filename
	 * @return bool результат проверки 
	 */
	public static function exists($filename) {
		return (bool) (file_exists($filename) && is_file($filename));
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

	/**
	 * Получение MIME типа файла
	 * 
	 * @example  joosFile::mime_content_type( __FILE__ );
	 * @example  joosFile::mime_content_type( JPATH_BASE .DS. 'media' . DS . 'favicon.ico' );
	 * @example  joosFile::mime_content_type(JPATH_BASE . DS . 'media' . DS . 'js' . DS . 'jquery.js');
	 * 
	 * @param type $filename
	 * @return string 
	 */
	public static function mime_content_type($filename) {
		$mime_types = array(
			// all
			'txt' => 'text/plain',
			'htm' => 'text/html',
			'html' => 'text/html',
			'php' => 'text/html',
			'css' => 'text/css',
			'js' => 'application/javascript',
			'json' => 'application/json',
			'xml' => 'application/xml',
			'swf' => 'application/x-shockwave-flash',
			'flv' => 'video/x-flv',
			'sql' => 'text/x-sql',
			// images
			'png' => 'image/png',
			'jpe' => 'image/jpeg',
			'jpeg' => 'image/jpeg',
			'jpg' => 'image/jpeg',
			'gif' => 'image/gif',
			'bmp' => 'image/bmp',
			'ico' => 'image/vnd.microsoft.icon',
			'tiff' => 'image/tiff',
			'tif' => 'image/tiff',
			'svg' => 'image/svg+xml',
			'svgz' => 'image/svg+xml',
			'tga' => 'image/x-targa',
			'psd' => 'image/vnd.adobe.photoshop',
			// archives
			'zip' => 'application/zip',
			'rar' => 'application/x-rar-compressed',
			'exe' => 'application/x-msdownload',
			'msi' => 'application/x-msdownload',
			'cab' => 'application/vnd.ms-cab-compressed',
			'gz' => 'application/x-gzip',
			'tgz' => 'application/x-gzip',
			'bz' => 'application/x-bzip2',
			'bz2' => 'application/x-bzip2',
			'tbz' => 'application/x-bzip2',
			'zip' => 'application/zip',
			'rar' => 'application/x-rar',
			'tar' => 'application/x-tar',
			'7z' => 'application/x-7z-compressed',
			// audio/video
			'mp3' => 'audio/mpeg',
			'qt' => 'video/quicktime',
			'mov' => 'video/quicktime',
			'avi' => 'video/x-msvideo',
			'dv' => 'video/x-dv',
			'mp4' => 'video/mp4',
			'mpeg' => 'video/mpeg',
			'mpg' => 'video/mpeg',
			'wm' => 'video/x-ms-wmv',
			'flv' => 'video/x-flv',
			'mkv' => 'video/x-matroska',
			// adobe
			'pdf' => 'application/pdf',
			'psd' => 'image/vnd.adobe.photoshop',
			'ai' => 'application/postscript',
			'eps' => 'application/postscript',
			'ps' => 'application/postscript',
			// ms office
			'doc' => 'application/msword',
			'rtf' => 'application/rtf',
			'xls' => 'application/vnd.ms-excel',
			'ppt' => 'application/vnd.ms-powerpoint',
			// open office
			'odt' => 'application/vnd.oasis.opendocument.text',
			'ods' => 'application/vnd.oasis.opendocument.spreadsheet',
		);

		$file_info = pathinfo($filename);
		$ext = $file_info['extension'];
		if (isset($mime_types[$ext])) {
			return $mime_types[$ext];
		} elseif (function_exists('finfo_open')) {
			$finfo = finfo_open(FILEINFO_MIME);
			$mimetype = finfo_file($finfo, $filename);
			finfo_close($finfo);
			return $mimetype;
		} else {
			return 'application/octet-stream';
		}
	}

	/**
	 * Формирование вложенного пути к файлу с учетом разделения по каталогам
	 * 
	 * @example joosFile::make_file_location( 1 );
	 * @example joosFile::make_file_location( 123 );
	 * @example joosFile::make_file_location( 123456789123456789 );
	 * 
	 * @param integer $id - номер файла в БД
	 * @return string - путь к файлу в структуре подкаталогов
	 */
	public static function make_file_location($id) {

		if (!is_integer($id)) {
			throw new joosException('Параметр $id должен иметь цельночисленное значение');
		}

		$p = sprintf('%09d', $id);
		$h = str_split($p, 3);
		return implode('/', $h);
	}

	/**
	 * Получение полной информации о файле
	 * 
	 * @example joosFile::file_info( __FILE__ );
	 * @example joosFile::file_info( JPATH_BASE . DS. 'index.php'  );
	 * @example joosFile::file_info( 'index.html' );
	 * 
	 * @param string $filename абсолюютный или относительный путь до файла
	 * @return array массив информации о файле
	 * 		mime - mime тип файла
	 * 		size - размер файла в байтах
	 * 		ext - расширение файла
	 * 		name - имя файла с расширением
	 */
	public static function file_info($filename) {

		if (!joosFile::exists($filename)) {
			throw new joosException('Файл не существует');
		}

		$f = pathinfo($filename);

		$r = array();
		$r['mime'] = self::mime_content_type($filename);
		$r['size'] = filesize($filename);
		$r['ext'] = $f['extension'];
		$r['name'] = $f['basename'];

		return $r;
	}

}

/**
 * Обработчик ошибок для библиотеки joosFile
 */
class joosFileLibrariesException extends joosException {
	
}