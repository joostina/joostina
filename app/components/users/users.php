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

class actionsUsers extends joosController {

	public static function on_start() {
		joosBreadcrumbs::instance()
				->add('Пользователи');

		joosLoader::model('users');
		joosLoader::lang('frontend/users');
	}

	//Список пользователей сайта
	public static function index() {

		$page = isset(self::$param['page']) ? self::$param['page'] : 0;

		$users = new User;
		$users_count = $users->count('WHERE state=1');

		joosDocument::instance()
				->set_page_title('Список пользователей')
				->add_meta_tag('description', 'Список пользователей сайта');


		return array(
			'users_count' => $users_count,
			'page' => $page
		);
	}

	//профиль пользователя
	public static function showuser() {

		$username = self::$param['username'];

		$user = new User;
		$user->load_by_field('username', $username);

		$user->id ? null : joosRoute::redirect(JPATH_SITE, 'Такого пользователя у нас совсем нет. Ну, то есть, вообще (');

		joosDocument::instance()
				->set_page_title($user->username);

		joosBreadcrumbs::instance()
				->add($user->username);

		return array(
			'user' => $user,
		);
	}

	//редактирование
	public static function edituser() {

		$username = self::$param['username'];

		$user = new User;
		$user->load_by_field('username', $username);

		if (User::current()->id != $user->id) {
			joosRoute::redirect(JPATH_SITE, 'Ай, ай!');
		}

		$validator = UserValidations::edit();

		if ($_POST) {

			$user = new User;
			$user->load($_POST['id']);
			$user->id ? null : joosRoute::redirect(JPATH_SITE, 'Такого пользователя у нас совсем нет. Ну, то есть, вообще (');

			//смена пароля
			$old_password = joosRequest::post('password_old');
			$new_password = joosRequest::post('password_new');

			if ($old_password && $new_password) {
				if (User::check_password($old_password, $user->password)) {
					$_POST['password'] = User::prepare_password($new_password);
				} else {
					joosRoute::redirect(joosRoute::href('user_view', array('username' => $user->username)), 'Неправильно введён пароль от аккаунта');
				}
			}

			$user->save($_POST);

			$user_extra = new UserExtra;
			$user_extra->user_id = $user->id;

			$_POST['contacts'] = json_encode(joosRequest::post('contacts'));
			$_POST['about'] = json_encode(joosRequest::post('about'));
			$_POST['interests'] = json_encode(joosRequest::post('interests'));

			$user_extra->save($_POST);

			joosRoute::redirect(joosRoute::href('user_view', array('username' => $user->username)), 'Данные успешно сохранены');

			return array(
				'user' => $user,
				'user_e' => $user_extra,
				'validator' => $validator
			);
		} else {
			$user->id ? null : joosRoute::redirect(JPATH_SITE, 'Такого пользователя у нас совсем нет. Ну, то есть, вообще (');

			$user_extra = new UserExtra;
			$user_extra->load($user->id);

			joosDocument::instance()
					->set_page_title($username);

			joosBreadcrumbs::instance()
					->add($username);

			joosHTML::make_safe($user);

			return array(
				'user' => $user,
				'user_e' => $user_extra,
				'validator' => $validator
			);
		}
	}

	/**
	 * Авторизация пользователя
	 */
	public static function login() {
		//joosSpoof::check_code(null, 1);
		//joosMainframe::instance()->login();

		$username = joosRequest::post('username');
		$password = joosRequest::post('password');

		User::login($username, $password);
	}

	/**
	 * Разлогинивани епользователя
	 */
	public static function logout() {

		joosSpoof::check_code(1);

		User::logout();

		$return = joosRequest::param('return');
		if ($return && !(strpos($return, 'registration') || strpos($return, 'login'))) {
			joosRoute::redirect($return);
		} elseif (isset($_SERVER['HTTP_REFERER'])) {
			joosRoute::redirect($_SERVER['HTTP_REFERER']);
		} else {
			joosRoute::redirect(JPATH_SITE);
		}
	}

	public static function register() {

		joosDocument::instance()
				->set_page_title('Регистрация');

		joosDocument::$config['seotag'] = false;

		$validator = UserValidations::registration();

		if ($_POST) {
			self::save_register($validator);
		} else {
			return array(
				'user' => new User,
				'validator' => $validator
			);
		}
	}

	public static function check() {

		$param = explode('?', $_SERVER['REQUEST_URI']);
		parse_str($param[1], $datas);

		if (isset($datas['username']) && joosString::trim($datas['username']) != '') {
			$user = new User;
			$user->username = $datas['username'];
			$ret = $user->find() ? 0 : 1;

			$ret = preg_match(_USERNAME_REGEX, $datas['username']) ? $ret : false;


			echo $ret ? 'true' : 'false';
			exit();
		}

		if (isset($datas['email']) && joosString::trim($datas['email']) != '') {
			$user = new User;
			$user->email = $datas['email'];
			echo $user->find() ? 'false' : 'true';
			exit();
		}
	}

	private static function save_register($validator) {

		$user = new User;
		$user->bind($_POST);

		if ($user->check($validator) && $user->save($_POST)) {
			User::login($user->username, $_POST['password']);
		} else {
			joosRoute::redirect(JPATH_SITE);
			//userHTML::register($user, $validator);
		}
	}

	/**
	 * Форма восстановления пароля
	 */
	public static function lostpassword() {
		$_POST ? self::send_new_pass() : userHTML::lostpassword();
	}

	/**
	 * Процесс восстановления пароля
	 */
	// TODO обновить до актуального
	public static function send_new_pass() {

	}

}