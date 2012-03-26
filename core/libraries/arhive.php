<?php

// запрет прямого доступа
defined( '_JOOS_CORE' ) or die();

/**
  * Библиотека работы с архивами
 * Системная библиотека
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
 * v 1.0 поддерживает извлечение zip архивов в указанный каталог
 * */
class joosArhive {

	/**
	 * Извлечение архива
	 *
	 * @tutorial joosArhive::extract( '123.zip' , JPATH_BASE.'/cache/tmp/');
	 *
	 * @param string $from_arhive_file полный путь к файлу архива
	 * @param string $extract_to       каталог для извлечения файлов из архива
	 *
 	 * @static
	 * @return bool
	 */
	public static function extract( $from_arhive_file , $extract_to ) {
		$zip = new ZipArchive;
		if ( $zip->open( $from_arhive_file ) === TRUE ) {
			$zip->extractTo( $extract_to );
			$zip->close();
			$result = true;
		} else {
			$result = false;
		}

		return $result;
	}

}
