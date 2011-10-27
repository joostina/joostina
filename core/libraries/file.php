<?php

// запрет прямого доступа
defined('_JOOS_CORE') or die();

/**
 * joosFile - Библиотека работы с файлами
 * Системная библиотека
 *
 * @version    1.0
 * @package    Joostina.Libraries
 * @subpackage Libraries
 * @category   Libraries
 * @author     Joostina Team <info@joostina.ru>
 * @copyright  (C) 2007-2011 Joostina Team
 * @license    MIT License http://www.opensource.org/licenses/mit-license.php
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
	 * @static
	 * @param string $num исходные строка или число для форматирования
	 * @return string|num
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
	 *
	 * @param string $filename полный путь к файлу, либо массив полный путей к удаляемым файлам
	 *
	 * @return bool результат удаления
	 */
	public static function delete($filename) {

		if (!joosFile::exists($filename)) {
			throw new joosException('Файл :file не существует', array(':file' => $filename));
		}

		return unlink((string) $filename);
	}

	/**
	 * Проверка существования файла
	 *
	 * @example joosFile::exists( JPATH_BASE . DS. 'index.php' );
	 *
	 * @param string $filename
	 *
	 * @return bool результат проверки
	 */
	public static function exists($filename) {
		return (bool) ( file_exists($filename) && is_file($filename) );
	}

	/**
	 * Получение MIME типа файла
	 *
	 * @example  joosFile::mime_content_type( __FILE__ );
	 * @example  joosFile::mime_content_type( JPATH_BASE . DS . 'media' . DS . 'favicon.ico' );
	 * @example  joosFile::mime_content_type( JPATH_BASE . DS . 'media' . DS . 'js' . DS . 'jquery.js');
	 *
	 * @param type $filename
	 *
	 * @return string
	 */
	public static function mime_content_type($filename) {
		$mime_types = array(// all
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
			'ods' => 'application/vnd.oasis.opendocument.spreadsheet'
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
	 *
	 * @return string - путь к файлу в структуре подкаталогов
	 * 
	 * @todo задокументировать новые параметры
	 */
	public static function make_file_location($id, $split_by = 3, $capacity = 9) {

		if (!is_integer($id)) {
			throw new joosException('Параметр $id должен иметь цельночисленное значение');
		}

		if (!is_integer($split_by)) {
			throw new joosException('Параметр $split_by должен иметь цельночисленное значение');
		}

		if (!is_integer($capacity)) {
			throw new joosException('Параметр $capacity должен иметь цельночисленное значение');
		}

		$p = sprintf('%0' . $capacity . 'd', $id);
		$h = str_split($p, $split_by);
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
	 *
	 * @return array массив информации о файле
	 * 		 mime - mime тип файла
	 * 		 size - размер файла в байтах
	 * 		 ext - расширение файла
	 * 		 name - имя файла с расширением
	 */
	public static function file_info($filename) {

		if (!joosFile::exists($filename)) {
			throw new joosException('Файл :file не существует', array(':file' => $filename));
		}

		$f = pathinfo($filename);

		$r = array();
		$r['mime'] = self::mime_content_type($filename);
		$r['size'] = filesize($filename);
		$r['ext'] = $f['extension'];
		$r['name'] = $f['basename'];

		return $r;
	}

	/**
	 * Преобразование имени файла к безопасному для файлвоой системы виду
	 * Из строки удаляются все спецсимволы, кирилические символы транслитерируются
	 *
	 * @example  joosFile::get_safe_name('имя файла номер 1 - ( раз)');
	 * @example  joosFile::get_safe_name(' eminem feat dr.dre i need a doctor.mp3 ');
	 *
	 * @param type $filename
	 *
	 * @return type
	 */
	public static function make_safe_name($filename) {
		// убираем непроизносимые русские мязкие звуки
		$filename = str_ireplace(array('ь', 'ъ'), '', $filename);
		// переводим в транслит
		$filename = joosText::russian_transliterate($filename);
		// в нижний регистр
		$filename = strtolower($filename);
		// заменям все ненужное нам на "-"
		$filename = str_replace(array("'", '-'), ' ', $filename);
		$filename = preg_replace('/[^-a-z0-9._]+/u', '-', $filename);
		return trim($filename, '-');
	}

}

/**
 * Обработчик ошибок для библиотеки joosFile
 */
class joosFileLibrariesException extends joosException {
	
}