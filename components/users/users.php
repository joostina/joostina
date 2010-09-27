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

require_once joosCore::path('users', 'html');
require_once joosCore::path('users', 'class');

require_once joosCore::path('users', 'lang');

class actionsUsers extends Jcontroller {

	public static function index() {

		$page = isset(self::$param['page']) ? self::$param['page'] : 0;

		$users = new User;
		$users_count = $users->count('WHERE state=1');

		Jdocument::getInstance()
				->setPageTitle('Список пользователей нашего супер-сайта')
				->addMetaTag('description', 'Список пользователей сайта ' . JConfig::getInstance()->config_sitename);


		return array(
			'users_count' => $users_count,
			'page' => $page
		);
	}

	public static function showuser() {

		$username = self::$param['username'];

		Jdocument::getInstance()
				->setPageTitle($username . ' на нашем суперсайте');

		echo 'Страница пользователя: ' . $username;
	}

	/**
	 * Авторизация пользователя
	 */
	public static function login() {

		josSpoofCheck(null, 1);

		mosMainFrame::getInstance()->login();

		$return = strval(mosGetParam($_REQUEST, 'return', null));
		if ($return && !(strpos($return, 'com_registration') || strpos($return, 'com_login'))) {
			mosRedirect($return);
		} elseif (isset($_SERVER['HTTP_REFERER'])) {
			mosRedirect($_SERVER['HTTP_REFERER']);
		} else {
			mosRedirect(JPATH_SITE);
		}
	}

	/**
	 * Разлогинивани епользователя
	 */
	public static function logout() {

		josSpoofCheck(null, 1);

		mosMainFrame::getInstance()->logout();

		$return = strval(mosGetParam($_REQUEST, 'return', null));
		if ($return && !(strpos($return, 'com_registration') || strpos($return, 'com_login'))) {
			mosRedirect($return);
		} else {
			mosRedirect(JPATH_SITE);
		}
	}

	public static function register() {

		Jdocument::getInstance()
				->setPageTitle('Регистрация');

		Jdocument::$config['seotag'] = false;

		mosMainFrame::addLib('formvalidator');
		$validator = new FormValidator();
		$validator
				->addValidation("username", "req", "Необходимо ввести имя пользователя")
				->addValidation("username", "minlen=2", "Минимум для имени пользователя - 2 символа")
				->addValidation("username", "maxlen=10", "Максимум для имени пользователя - 10 символов")
				->addValidation("username", "remote=register/check/user", "Выбранное имя уже зарегистрированно")
				->addValidation("email", "email", "Введён невалидный email")
				->addValidation("email", "req", "Не введён email-адрес")
				->addValidation("email", "remote=register/check/email", "Выбранные email уже используется")
				->addValidation("password", "req", "Необходимо ввести пароль")
				->addValidation("password", "minlen=3", "Минимум для пароль - 3 символа")
				->addValidation("password", "maxlen=15", "Максимум для пароля - 15 символов");

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

		if (!_IS_AJAX) {
			//return self::error404();
		}

		$param = explode('?', $_SERVER['REQUEST_URI']);
		parse_str($param[1], $datas);

		if (isset($datas['username']) && Jstring::trim($datas['username']) != '') {
			$user = new User;
			$user->username = $datas['username'];
			echo $user->find() ? 'false' : 'true';
			exit();
		}

		if (isset($datas['email']) && Jstring::trim($datas['email']) != '') {
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
			$user_extra = new UserExtra;
			$user_extra->insert($user->id);
			mosRedirect(sefRelToAbs('index.php', true), 'Аккаунт успешно создан. Можете входить');
		} else {
			userHTML::register($user, $validator);
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
	public static function send_new_pass() {

		josSpoofCheck();
		$config = JConfig::getInstance();

		$username = stripslashes(mosGetParam($_POST, 'username', ''));
		$email = stripslashes(mosGetParam($_POST, 'email', ''));

		if (!$email && !$username) {
			mosRedirect(sefRelToAbs('index.php?option=com_users&task=lostpassword', true), 'Введите Имя пользователя или Email');
		}

		$user = new User;

		if ($email && $username) {
			$query = 'SELECT id, username, email FROM #__users WHERE username = "' . $username . '" AND email = "' . $email;
		} else if ($email) {
			$query = 'SELECT id, username, email FROM #__users WHERE email = "' . $email . '"';
		} else {
			$query = 'SELECT id, username, email FROM #__users WHERE username = "' . $username . '"';
		}

		$user->_db->setQuery($query)->loadObject($user);

		if (!$user->id) {
			mosRedirect(sefRelToAbs('index.php?option=com_users&task=lostpassword', true), 'Пользователь с указанными параметрами не найден');
		}

		$confirmEmail = $user->email;
		$newpass = mosMakePassword();
		$message = _NEWPASS_MSG;
		eval("\$message = \"$message\";");
		$subject = _NEWPASS_SUB;
		eval("\$subject = \"$subject\";");

		if (mosMail($config->config_mailfrom, $config->config_fromname, $confirmEmail, $subject, $message)) {
			$user->password = $user->crypt_pass($newpass);
			$user->save();
			mosRedirect(sefRelToAbs('index.php?option=com_users&task=lostpassword', true), 'Новый пароль выслан вам на email');
		} else {
			mosRedirect(sefRelToAbs('index.php?option=com_users&task=lostpassword', true), 'Ошибка в процессе отправки сообщения. Попробуйте позже.');
		}
	}

	public static function view($option, $id) {

		$user = new User;
		$user->load($id);

		$user->id ? null : mosRedirect(JPATH_SITE, 'Такого пользователя у нас совсем нет. Ну, то есть, вообще (');

		$user_extra = new UserExtra;
		$user_extra->load($id);

		mosMainFrame::addLib('text');
		mosMainFrame::addLib('datetime');

		//Считаем возраст
		$user->age = 'Не указан';
		if ($user_extra->birthdate != '0000-00-00') {
			$delta = DateAndTime::getDelta(DateAndTime::mysql_to_unix($user_extra->birthdate), DateAndTime::mysql_to_unix(_CURRENT_SERVER_TIME));
			$age = $delta['year'];
			$user->age = $age . ' ' . Text::declension($age, array('год', 'года', 'лет'));
		}


		//Пол
		$user_extra->gender_text = '';
		if ($user_extra->gender) {
			$user_extra->gender_text = $user_extra->gender == 'female' ? 'прекрасный' : 'сильный';
		}

		//Период с момента регистрации
		$delta = DateAndTime::getDelta(DateAndTime::mysql_to_unix($user->registerDate), DateAndTime::mysql_to_unix(_CURRENT_SERVER_TIME));

		$period = $delta['mday'];
		$mon = $delta['mon'] > 0 ? $delta['mon'] . ' ' . Text::declension($delta['mon'], array('месяц', 'месяца', 'месяцев')) . ' ' : '';
		$user->period = $mon . $period . ' ' . Text::declension($period, array('день', 'дня', 'дней'));

		//Карма пользователя
		mosMainFrame::addLib('voter');
		$user->votes_count = Voter::getInstance('users')->get_count($user);
		// число голосов
		$voters_count = Voter::getInstance('users')->get_count_voters($user);
		mosMainFrame::addLib('text');
		$user->voters_count = $voters_count . ' ' . Text::declension((int) $voters_count, array('голос', 'голоса', 'голосов'));

		//Полный рейтинг пользователя
		$user->fullrate = UserRatings::get_full_rate($user->id);

		require_once mosMainFrame::getInstance()->getPath('class', 'com_games');
		$games_love = new Games;
		$games_love = $games_love->get_list(
						array(
							'select' => 'game.id, game.title',
							'join' => " AS game INNER JOIN #__bookmarks AS bookmarks ON (bookmarks.obj_id=game.id AND bookmarks.obj_option='games_love') ",
							'where' => 'bookmarks.user_id=' . $user->id
						)
		);

		userHTML::view($user, $user_extra, $games_love);

		// увеличиваем счетчик просомтров страницы текущего пользователя
		Jhit::add('user', $user->id, 'view');
	}

	public static function edit() {
		$my = User::current();

		$user = new User;
		$user->load($my->id);

		$user->id ? null : mosRedirect(JPATH_SITE, 'Такого пользователя у нас совсем нет');

		$user_extra = new UserExtra;
		$user_extra->load($my->id);

		//Правила валидации
		mosMainFrame::addLib('formvalidator');
		$validator = new FormValidator();
		$validator->addValidation("email", "email", "Введён невалидный email");
		$validator->addValidation("email", "req", "Не введён email");

		$_POST ? self::save_profile($user, $user_extra, $validator) : userHTML::edit($user, $user_extra, $validator);
	}

	private static function save_profile($user, $user_extra, $validator) {

		josSpoofCheck();

		//$user_extra->birthdate = $_POST['birthdate'] . ' 00:00:00';
		$user_extra->bind($_POST);

		$user->email = mosGetParam($_POST, 'email', $user->email);

		//смена пароля
		//$orig_password = $user->password;
		$old_password = mosGetParam($_POST, 'pass_old', '');
		$new_password = mosGetParam($_POST, 'pass_new', '');

		if ($old_password && $new_password) {
			if ($user->check_password($old_password)) {
				$salt = mosMakePassword(16);
				$crypt = md5($new_password . $salt);
				$user->password = $crypt . ':' . $salt;
			} else {
				mosRedirect(sefRelToAbs('index.php?option=com_users&task=edit', true), $user->getError());
			}
		}

		if ($user->check_edit($validator) && $user->store()) {
			$user_extra->save();

			//Манипуляции с паспортом
			require_once mosMainFrame::getInstance()->getPath('class', 'com_awards');
			$award = new AutoAwards;
			if (
					$user_extra->realname &&
					$user_extra->gender &&
					$user_extra->location &&
					$user_extra->birthdate != '0000-00-00' &&
					User::avatar_check($user->id)
			) {
				//Проверим, есть ли уже паспорт.
				$cur = database::getInstance()->setQuery("SELECT obj_id FROM #__awards_rewarding WHERE award_id = 1 AND obj_id = $user->id")->loadResult();
				if (!$cur) {
					//Если паспорта нет - вручим
					$award->give(1, $user->id);
				}
			} else {
				//отбираем паспорт
				$award->take(1, $user->id);
			}

			mosRedirect(sefRelToAbs('index.php?option=com_users&task=edit', true), 'Данные успешно обновлены');
		} else {
			userHTML::edit($user, $user_extra, $validator);
		}
	}

}
