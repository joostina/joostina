<?php

// запрет прямого доступа
defined( '_JOOS_CORE' ) or die();

/**
 * Contacts  - Компонент контактов
 * Контроллер сайта
 *
 * @version    1.0
 * @package    Joostina.Components.Controllers
 * @subpackage Contacts
 * @author     Joostina Team <info@joostina.ru>
 * @copyright  (C) 2007-2011 Joostina Team
 * @license    MIT License http://www.opensource.org/licenses/mit-license.php
 * Информация об авторах и лицензиях стороннего кода в составе Joostina CMS: docs/copyrights
 *
 * */
class actionsContacts extends joosController {

	public static function index() {

		joosDocument::instance()->add_js_file( JPATH_SITE . '/media/js/jquery.plugins/jquery.validate.js' );
		joosBreadcrumbs::instance()->add( 'Обратная связь' );

		session_name( md5( JPATH_SITE ) );
		session_start();

		if ( $_POST ) {

			$captcha           = joosRequest::post( 'captcha' );
			$captcha_keystring = joosRequest::session( 'captcha_keystring' );
			if ( $captcha_keystring != $captcha ) {
				unset( $_SESSION['captcha_keystring'] );
				return joosRoute::redirect( '/contacts' , 'Неправильный код проверки' );
			}

			self::send_email();
		}

		return array ();
	}

	private static function send_email() {

		$fields    = array ( 'usermail' => 'Email' ,
		                     'username' => 'Имя' ,
			//'phone' => 'Телефон',
		                     'subject'  => 'Тема' ,
		                     'body'     => 'Сообщение' );

		$from      = joosRequest::post( 'usermail' );
		$fromname  = joosRequest::post( 'username' );
		$recipient = joosConfig::get2( 'mail' , 'from' );
		$subject   = joosRequest::post( 'subject' ) ? joosRequest::post( 'subject' ) : 'Сообщение с сайта ' . JPATH_SITE;

		$file_path = '';

		//Прикреплённый файл
		if ( joosRequest::file( 'qqfile' ) ) {
			joosLoader::lib( 'valumsfileuploader' , 'files' );
			$file      = ValumsfileUploader::upload( false , 'contacts' , false , false );
			$file_path = JPATH_SITE . $file['livename'];
		}

		$body = '';
		foreach ( $fields as $key => $label ) {
			$body .= $label . ': ' . joosRequest::post( $key ) . "\n";
		}
		/*
		if ($file_path) {
			$body .= 'Прикреплённый файл: ' . $file_path;
		}
*/
		// @todo переделать
		throw new joosException( 'Передалть!' );
		//$r = mosMail($from, $fromname, $recipient, $subject, $body);

		return $r ? joosRoute::redirect( joosRoute::href( 'contacts' ) , 'Сообщение отправлено' ) : joosRoute::redirect( joosRoute::href( 'contacts' ) , 'Ошибка при отправке' );
	}

}