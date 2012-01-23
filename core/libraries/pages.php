<?php

// запрет прямого доступа
defined( '_JOOS_CORE' ) or die();

/**
 * joosPages - Библиотека работы со страницами системных сообщений
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
 * @todo       добавить подключение шаблонов и другие базовые страницы
 * */
class joosPages {

	private static function init( $code ) {
		joosRequest::send_headers_by_code( $code );
		if ( ob_get_level() ) {
			ob_end_clean();
		}
	}

	public static function page404( $message = 'Не найдено' ) {
		self::init( 404 );

		echo $message;
		die();
	}

	public static function page301( $message = 'В доступе отказано' ) {
		self::init( 301 );

		echo $message;
		die();
	}

}