<?php

// запрет прямого доступа
defined('_JOOS_CORE') or die();

/**
 * Компонент управления пользователями
 * Контроллер сайта
 *
 * @version    1.0
 * @package    Controllers
 * @subpackage Users
 * @author     Joostina Team <info@joostina.ru>
 * @copyright  (C) 2007-2012 Joostina Team
 * @license    MIT License http://www.opensource.org/licenses/mit-license.php
 * Информация об авторах и лицензиях стороннего кода в составе Joostina CMS: docs/copyrights
 *
 * */
class actionsUsers extends joosController {

	public static function action_before() {
		joosBreadcrumbs::instance()
				->add('Пользователи');
	}

	//Список пользователей сайта
	public static function index() {

		$page = isset(self::$param['page']) ? self::$param['page'] : 0;

		$users = new modelUsers;
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
	public static function view() {

		$user_name = self::$param['user_name'];

		$user = new modelUsers;
		$user->load_by_field('user_name', $user_name);

		$user->id ? null : joosRoute::redirect(JPATH_SITE, 'Пользователь не найден');

		joosDocument::instance()->set_page_title($user->user_name);

		joosBreadcrumbs::instance()->add($user->user_name);

		return array('user' => $user,);
	}

	//редактирование
	public static function edit_user() {

		$user_name = self::$param['user_name'];

		$user = new modelUsers;
		$user->load_by_field('user_name', $user_name);

		if (joosCore::user()->id != $user->id) {
			joosRoute::redirect(JPATH_SITE, 'Ай, ай!');
		}

		$validator = UserValidations::edit();

		if ($_POST) {

			$user = new modelUsers;
			$user->load($_POST['id']);
			$user->id ? null : joosRoute::redirect(JPATH_SITE, 'Пользователь не найден');

			//смена пароля
			$old_password = joosRequest::post('password_old');
			$new_password = joosRequest::post('password_new');

			if ($old_password && $new_password) {
				if (modelUsers::check_password($old_password, $user->password)) {
					$_POST['password'] = modelUsers::prepare_password($new_password);
				} else {
					joosRoute::redirect(joosRoute::href('user_view', array('id' => $user->id,
								'user_name' => $user->user_name)), 'Неправильно введён пароль');
				}
			}

			$user->save($_POST);

			joosRoute::redirect(joosRoute::href('user_view', array('id' => $user->id,
						'user_name' => $user->user_name)), 'Данные успешно сохранены');

			return array('user' => $user,
				'validator' => $validator
			);
		} else {
			$user->id ? null : joosRoute::redirect(JPATH_SITE, 'Пользователь не найден');

			joosDocument::instance()->set_page_title($user_name);

			joosBreadcrumbs::instance()->add($user_name);

			joosHtml::make_safe($user);

			return array('user' => $user,
				'validator' => $validator
			);
		}
	}

	/**
	 * Авторизация пользователя
	 */
	public static function login() {

		joosCSRF::check_code(1);

		$user_name = joosRequest::post('user_name');
		$password = joosRequest::post('password');

		modelUsers::login($user_name, $password);
	}

	/**
	 * Разлогинивание епользователя
	 */
	public static function logout() {

		joosCSRF::check_code(1);

		modelUsers::logout();

		$return = joosRequest::param('return');
		if ($return && !( strpos($return, 'registration') || strpos($return, 'login') )) {
			joosRoute::redirect($return);
		} elseif (isset($_SERVER['HTTP_REFERER'])) {
			joosRoute::redirect($_SERVER['HTTP_REFERER']);
		} else {
			joosRoute::redirect(JPATH_SITE);
		}
	}

	public static function register() {

		joosDocument::instance()->set_page_title('Регистрация');

		joosDocument::$config['seotag'] = false;

		$validator = UserValidations::registration();

		if ($_POST) {
			self::save_register($validator);
		} else {
			return array('user' => new modelUsers,
				'validator' => $validator
			);
		}
	}

	public static function check() {

		$param = explode('?', $_SERVER['REQUEST_URI']);
		parse_str($param[1], $datas);

		if (isset($datas['user_name']) && joosString::trim($datas['user_name']) != '') {
			$user = new modelUsers;
			$user->user_name = $datas['user_name'];
			$ret = $user->find() ? 0 : 1;

			$ret = preg_match(JUSER_NAME_REGEX, $datas['user_name']) ? $ret : false;


			echo $ret ? 'true' : 'false';
			exit();
		}

		if (isset($datas['email']) && joosString::trim($datas['email']) != '') {
			$user = new modelUsers;
			$user->email = $datas['email'];
			echo $user->find() ? 'false' : 'true';
			exit();
		}
	}

	private static function save_register($validator) {

		$user = new modelUsers;
		$user->bind($_POST);

		if ($user->check($validator) && $user->save($_POST)) {
			modelUsers::login($user->user_name, $_POST['password']);
		} else {
			joosRoute::redirect(JPATH_SITE);
			//userHtml::register($user, $validator);
		}
	}

	/**
	 * Форма восстановления пароля
	 */
	public static function lostpassword() {
		$_POST ? self::send_new_pass() : self::lostpassword();
	}

	/**
	 * Процесс восстановления пароля
	 */
	// TODO обновить до актуального
	public static function send_new_pass() {

	}

}