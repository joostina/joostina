<?php

// запрет прямого доступа
defined( '_JOOS_CORE' ) or die();

/**
 * joosSpoof - Библиотека работы с защитой от межсайтового скриптинга
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
class joosCSRF {

	public static function hash( $seed ) {
		return md5( JSECRET_CODE . md5( $seed ) );
	}

	public static function get_code( $alt = null ) {
		if ( $alt ) {
			$random = $alt . date( 'Ymd' );
		} else {
			$random = date( 'dmY' );
		}

		return 'joosCSRF-' . self::hash( JPATH_BASE . $random . ( joosCore::user() ? joosCore::user()->id : 'null' ) );
	}

	public static function check_code( $alt = null , $method = 'post' ) {

		switch ( strtolower( $method ) ) {
			case 'get':
				$validate = joosRequest::get( self::get_code( $alt ) , 0 );
				break;

			case 'request':
				$validate = joosRequest::request( self::get_code( $alt ) , 0 );

				break;

			case 'post':
			default:
				$validate = joosRequest::post( self::get_code( $alt ) , 0 );
				break;
		}

		if ( !$validate ) {
			joosPages::page301();
		}

		if ( !isset( $_SERVER['HTTP_USER_AGENT'] ) ) {
			joosPages::page301();
		}

		if ( !$_SERVER['REQUEST_METHOD'] == 'POST' ) {
			joosPages::page301();
		}
	}

}
