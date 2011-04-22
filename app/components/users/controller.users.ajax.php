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

class actionsAjaxUsers extends joosController {

	public static function index() {
		joosLoader::lib('valumsfileuploader', 'files');
		joosLoader::lib('images');

		parse_str($_SERVER['REQUEST_URI'], $r);
		if (isset($r['?name'])) {
			$_REQUEST['name'] = $r['?name'];
		}

		//Загружаем оригинальное изображение (original.png)
		$file = ValumsfileUploader::upload('original', 'avatars', Users::current()->id, false);

		//Путь к оригинальному изображению
		$img = dirname($file['basename']);

		//Сначала уменьшаем
		Thumbnail::output($file['basename'], $img . '/avatar.png', array('width' => 100, 'height' => 100));
		//а потом обрезаем
		Thumbnail::output($img . '/avatar.png', $img . '/avatar_75x75.png', array('width' => 75, 'height' => 75, 'method' => 1));
		Thumbnail::output($img . '/avatar_75x75.png', $img . '/avatar_40x40.png', array('width' => 40, 'height' => 40, 'method' => 1));

		echo json_encode(array('location' => $file['location'], 'file_id' => $file['file_id'], 'livename' => $file['livename'], 'success' => true));
	}

	public static function send_email() {

		$user_id = joosRequest::int('user_id', 0, $_POST);
		$subject = joosRequest::post('subject');
		$text = joosRequest::post('text');
		$text = strip_tags(trim($text));

		if (!Users::current()->id) {
			return json_encode(array('message' => 'Сначала авторизуйтесь'));
		}

		if (!$user_id) {
			return json_encode(array('message' => 'Не понятно, кому отправлять письмо'));
		}

		$message = '<strong>Пользователь сайта Megaplay.ru отправил Вам сообщение:</strong><br/>';
		if ($subject == '' || $text == '') {
			return json_encode(array('message' => 'Не хватает данных для отправки'));
		}
		$message .= $text;

		$message .= '<br/><br/><strong>Для просмотра информации об отправителе, перейдите в его профиль:</strong><br/>';
		$recipient = new Users;
		$recipient->load($user_id);
		$recipient->user_id = $recipient->id;
		$message .= '<a href="' . Users::profile_link($recipient) . '">' . Users::profile_link($recipient) . '</a>';


		if (mosMail(
						joosConfig::get2('mail', 'from'), //от кого - email
						joosConfig::get2('mail', 'name'), //от кого  - имя
						$recipient->email, //кому - email
						$subject, //тема
						$message, //сообщение
						1
        )
		) {
			return json_encode(array('message' => 'Сообщение успешно отправлено'));
		} else {
			return json_encode(array('message' => 'Не удалось отправить сообщение'));
		}
	}

	public static function login() {

		//joosSpoof::check_code(null, 1);
		//joosMainframe::instance()->login();
		$username = joosRequest::post('username');
		$password = joosRequest::post('password');

		$response = json_decode(Users::login($username, $password, array('return' => 1)), true);

		if (isset($response['error'])) {
			echo json_encode(array('error' => $response['error']));
		} else {
			echo json_encode(array('success' => 'всё пучком'));
		}
	}

	public static function logout() {
		joosSpoof::check_code(1);
		Users::logout();
		echo json_encode(array('success' => 'всё пучком'));
	}

	public static function register() {

		$validator = UserValidations::registration();

		$user = new Users;
		$user->bind($_POST);

		if (!$user->check($validator)) {
			$error_hash = $validator->GetErrors();
			$errors = '';
			foreach ($error_hash as $inpname => $inp_err) {
				$errors .= '' . $inp_err . ' |  ';
			}
			echo json_encode(array('error' => 'Ошибки: ' . $errors));
			return false;
		}

		if ($user->save($_POST)) {
			$password = joosRequest::post('password');
			$response = json_decode(Users::login($user->username, $password, array('return' => 1)), true);
			if (isset($response['error'])) {
				echo json_encode(array('error' => $response['error']));
				return false;
			} else {
				echo json_encode(array('success' => 'всё пучком'));
				return;
			}
		} else {
			//userHTML::register($user, $validator);
			echo json_encode(array('error' => 'Что-то не так с данными для регистрации'));
			return;
		}
	}

	public static function reload_login_area() {
		require_once(JPATH_BASE . DS . 'includes' . DS . 'frontend.php');
		echo joosModule::module('login');
	}

	public static function reload_menu() {
		require_once(JPATH_BASE . DS . 'includes' . DS . 'frontend.php');
		echo joosModule::module('navigation');
	}

}
