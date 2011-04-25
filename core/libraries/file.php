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
 * @copyright (C) 2008-2011 Joostina Team
 * @license MIT License http://www.opensource.org/licenses/mit-license.php
 * Информация об авторах и лицензиях стороннего кода в составе Joostina CMS: docs/copyrights
 *
 * */
class joosFile {

	/**
	 * Логическое представление размера файлов, памяти и прочив байтовых данных
	 * 
	 * @example joosFile::convert_size(123456)
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
			$num .= ' '. __('byte');
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
	 * Проверка существования файла
	 * 
	 * @example joosFile::exists( JPATH_BASE . DS. 'index.php'  )
	 * 
	 * @param string $filename
	 * @return bool результат проверки 
	 */
	public static function exists($filename) {
		return (file_exists((string) $filename) && is_file((string) $filename));
	}

}

/**
 * Обработчик ошибок для библиотеки joosFile
 */
class joosFileLibrariesException extends joosException {
	
}