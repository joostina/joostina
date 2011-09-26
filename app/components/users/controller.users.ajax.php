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

	public static function login() {

		//joosSpoof::check_code(null, 1);

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
		joosCSRF::check_code(1);
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
			foreach ($error_hash as $inp_err) {
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
				return true;
			}
		} else {
			//userHtml::register($user, $validator);
			echo json_encode(array('error' => 'Что-то не так с данными для регистрации'));
			return false;
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
