<?php

/**
 * @package Joostina
 * @copyright Авторские права (C) 2007-2010 Joostina team. Все права защищены.
 * @license Лицензия http://www.gnu.org/licenses/gpl-2.0.htm GNU/GPL, или help/license.php
 * Joostina! - свободное программное обеспечение распространяемое по условиям лицензии GNU/GPL
 * Для получения информации о используемых расширениях и замечаний об авторском праве, смотрите файл help/copyright.php.
 */
// запрет прямого доступа
defined('_JOOS_CORE') or die();

class actionsContacts extends joosController {

	public static function index() {

		joosDocument::instance()->add_js_file(JPATH_SITE . '/media/js/jquery.plugins/jquery.validate.js');
		joosBreadcrumbs::instance()->add('Обратная связь');

		session_name(md5(JPATH_SITE));
		session_start();

		if ($_POST) {

			$captcha = joosRequest::post('captcha');
			$captcha_keystring = joosRequest::session('captcha_keystring');
			if ($captcha_keystring != $captcha) {
				unset($_SESSION['captcha_keystring']);
				return joosRoute::redirect('/contacts', 'Неправильный код проверки');
			}

			self::send_email();
		}

		return array();
	}

	private static function send_email() {
		joosLoader::lib('mail', 'utils');

		$fields = array(
			'usermail' => 'Email',
			'username' => 'Имя',
			//'phone' => 'Телефон',
			'subject' => 'Тема',
			'body' => 'Сообщение'
		);

		$from = joosRequest::post('usermail');
		$fromname = joosRequest::post('username');
		$recipient = joosConfig::get2('mail', 'from');
		$subject = joosRequest::post('subject') ? joosRequest::post('subject') : 'Сообщение с сайта ' . JPATH_SITE;

		$file_path = '';

		//Прикреплённый файл
		if (joosRequest::file('qqfile')) {
			joosLoader::lib('valumsfileuploader', 'files');
			$file = ValumsfileUploader::upload(false, 'contacts', false, false);
			$file_path = JPATH_SITE . $file['livename'];
		}

		$body = '';
		foreach ($fields as $key => $label) {
			$body .= $label . ': ' . joosRequest::post($key) . "\n";
		}

		if ($file_path) {
			$body .= 'Прикреплённый файл: ' . $file_path;
		}

		$r = mosMail($from, $fromname, $recipient, $subject, $body);

		return $r ? joosRoute::redirect(joosRoute::href('contacts'), 'Сообщение отправлено') : joosRoute::redirect(joosRoute::href('contacts'), 'Ошибка при отправке');
	}

}