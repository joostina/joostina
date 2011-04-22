<?php

// запрет прямого доступа
defined('_JOOS_CORE') or die();

/**
 * Users - Модель пользователей
 * Модель для работы сайта
 *
 * @version 1.0
 * @package Joostina.Models
 * @subpackage Users
 * @author Joostina Team <info@joostina.ru>
 * @copyright (C) 2008-2011 Joostina Team
 * @license MIT License http://www.opensource.org/licenses/mit-license.php
 * Информация об авторах и лицензиях стороннего кода в составе Joostina CMS: docs/copyrights
 *
 * */
class Users extends joosModel {

	public $id;
	public $username;
	public $username_canonikal;
	public $realname;
	public $email;
	public $openid;
	public $password;
	public $state;
	public $gid;
	public $groupname;
	public $registerDate;
	public $lastvisitDate;
	public $activation;
	public $bad_auth_count;
	private static $user_instance;

	function __construct() {
		$this->joosDBModel('#__users', 'id');
	}

	// получение инстанции ТЕКУЩЕГО АВТОРИЗОВАННОГО пользователя
	public static function instance() {
		if (self::$user_instance === NULL) {
			$sessionCookieName = joosSession::sessionCookieName();
			$sessioncookie = (string) joosRequest::cookies($sessionCookieName);
			$session = new joldSession;
			if ($sessioncookie && strlen($sessioncookie) == 32 && $sessioncookie != '-' && $session->load(joosSession::sessionCookieValue($sessioncookie))) {
				if ($session->userid > 0) {
					$user = new self;
					$user->load($session->userid);
					self::$user_instance = $user;
				} else {
					self::$user_instance = self::get_guest();
				}
			} else {
				self::$user_instance = new self;
			}
		}
		return self::$user_instance;
	}

	private static function get_guest() {
		$guest = new stdClass();
		$guest->id = 0;
		$guest->username = _GUEST_USER;
		return $guest;
	}

	public static function get_usergroup($null = false, $gid = false) {
		$groups = new UsersGroups();
		$group = $groups->get_selector(array('key' => 'id', 'value' => 'title'), array('select' => 'id, title'));
		return $gid ? $group[$gid] : $group;
	}

	public static function get_usergroup_title() {
		$groups = new UsersGroups();
		return $groups->get_selector(array('key' => 'id', 'value' => 'group_title'), array('select' => 'id, group_title'));
	}

	// добавление в таблицу расширенной информации и пользователях новой записи - для только что зарегистрированного пользователя
	public function after_insert() {

		$extra = new UsersExtra;
		$extra->user_id = $this->id;
		joosDatabase::instance()->insert_object('#__users_extra', $extra);
	}

	public function check($validator = null) {

		$this->filter();

		if ($validator && !$validator->ValidateForm()) {
			$error_hash = $validator->GetErrors();
			$this->_error = '';
			foreach ($error_hash as $inpname => $inp_err) {
				$this->_error .= $inp_err;
			}
			return false;
		}

		$this->_db = joosDatabase::instance();

		$query = "SELECT id FROM #__users WHERE username = " . $this->_db->quote($this->username) . " AND id != " . (int) $this->id;
		$xid = $this->_db->set_query($query)->load_result();
		if ($xid && $xid != $this->id) {
			$this->_error = addslashes(_REGWARN_INUSE);
			return false;
		}

		$query = "SELECT id FROM #__users WHERE email = " . $this->_db->quote($this->email) . " AND id != " . (int) $this->id;
		$xid = $this->_db->set_query($query)->load_result();
		if ($xid && $xid != $this->id) {
			$this->_error = _REGWARN_EMAIL_INUSE;
			return false;
		}

		// формируем дополнителньое каноничное имя
		$this->username_canonikal = UserHelper::get_canonikal($this->username);
		return true;
	}

	function check_edit($validator) {

		if (!$validator->ValidateForm()) {
			$this->_error = '<strong>Ошибки при заполнении формы:</strong><ul>';

			$error_hash = $validator->GetErrors();
			foreach ($error_hash as $inpname => $inp_err) {
				$this->_error .= '<li>' . $inp_err . '</li>';
			}
			$this->_error .= '</ul>';
			return false;
		}
		return true;
	}

	function before_store() {
		if (!$this->id) {
			$this->password = self::prepare_password($this->password);
			$this->registerDate = _CURRENT_SERVER_TIME;
		} else {
			//$query = "SELECT password FROM #__users WHERE id = " . $this->id;
			//$db_password = $this->_db->setQuery($query)->loadResult();
			//$new_pas = self::prepare_password($this->password);
			//$this->password = ($new_pas==$db_password) ? $db_password : $new_pas;
		}
		$this->groupname = self::get_usergroup(false, $this->gid);
	}

	/**
	 * Users::check_password()
	 * Проверка введенного пароля на соответствие паролю в БД
	 *
	 * @param str $input_password
	 * @param str $real_password
	 * @return bool
	 */
	public static function check_password($input_password, $real_password) {
		// из хешированного значения пароля хранящегося в базе извлекаем соль
		list($hash, $salt) = explode(':', $real_password);
		// формируем хеш из введённого пользователм пароля и соли из базе
		$cryptpass = md5($input_password . $salt);

		// сравниваем хешированный пароль из базы и хеш от введённго пароля
		if ($hash != $cryptpass) {
			return false;
		}

		return true;
	}

	/**
	 * Users::prepare_password()
	 * Подготовка пароля для записи в БД
	 *
	 * @param str $password
	 * @return str
	 */
	public static function prepare_password($password) {
		$salt = self::mosMakePassword(16);
		$crypt = md5($password . $salt);
		return $crypt . ':' . $salt;
	}

	function get_link($user = false) {
		$user = $user ? $user : $this;

		$url = 'index.php?option=users&task=user&id=' . sprintf('%s:%s', $user->id, $user->username);
		return sefRelToAbs($url);
	}

	public static function profile_link($user) {
		return sefRelToAbs('index.php?option=com_users&id=' . sprintf('%s:%s', $user->user_id, $user->username));
	}

	function get_gender($user, $params = null) {

		switch ($user->user_extra->gender) {
			case 'female':
				$gender = _USERS_FEMALE_S;
				break;

			case 'male':
				$gender = _USERS_MALE_S;
				break;

			case 'no_gender':
			default:
				$gender = _GENDER_NONE;
				break;
		}

		if ($params->get('gender') == 1 || !$params) {
			return $gender;
		} else {
			$gender = '<img alt="" title="' . $gender . '" src="' . JPATH_SITE . '/images/system/' . $user->extra->gender . '.png" />';
		}
		return $gender;
	}

	public static function get_age($birthdate) {
		joosLoader::lib('text');
		joosLoader::lib('datetime', 'joostina');

		$delta = joosDateTime::get_delta(joosDateTime::mysql_to_unix($birthdate), joosDateTime::mysql_to_unix(_CURRENT_SERVER_TIME));
		$age = $delta['year'];
		return $age . ' ' . joosText::declension($age, array(_YEAR, _YEAR_, _YEARS));
	}

	/**
	 * Получение объекта текущего пользователя
	 * @global <type> $my
	 * @return User
	 */
	public static function current() {
		global $my;
		// TODO тут надо как-то унифицировать
		return joosMainframe::is_admin() ? $my : self::instance();
	}

	public static function avatar($id, $size = false) {

		$size = $size ? '_' . $size : false;

		joosLoader::lib('files');
		$file = Files::makefilename($id);
		//$file = $id;

		$base_file = JPATH_BASE . DS . 'attachments' . DS . 'avatars' . DS . $file . DS . 'avatar' . $size . '.png';
		return is_file($base_file) ? JPATH_SITE . '/attachments/avatars/' . $file . '/avatar' . $size . '.png' : JPATH_SITE . '/media/images/noavatar/avatar' . $size . '.png';
	}

	public static function avatar_check($id) {
		joosLoader::lib('files');
		$file = Files::makefilename($id);
		$base_file = JPATH_BASE . DS . 'attachments' . DS . 'avatars' . DS . $file . DS . 'avatar.png';
		return is_file($base_file) ? 1 : 0;
	}

	public function crypt_pass($pass) {

		$salt = self::mosMakePassword(16);
		$crypt = md5($pass . $salt);
		return $crypt . ':' . $salt;
	}

	/**
	 * Получение расширенной информации о пользователе
	 * @return UsersExtra
	 */
	public function extra() {

		if (!isset($this->extra) || $this->extra === NULL) {

			$extra = new UsersExtra;
			$extra->load($this->id);

			//кэш закладок декодируем из JSON
			$extra->cache_bookmarks = json_decode($extra->cache_bookmarks, true);

			// кэш  выставленного пользователм музыкального рейтинга из JSON
			$extra->cache_muzvotes = json_decode($extra->cache_muzvotes, true);

			// кэш  выставленного пользователм рейтинга материалов из JSON
			$extra->cache_votes = json_decode($extra->cache_votes, true);

			$this->extra = $extra;
		}

		return $this->extra;
	}

	private static function mosMakePassword($length = 8) {
		$salt = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
		$makepass = '';
		mt_srand(10000000 * (double) microtime());
		for ($i = 0; $i < $length; $i++) {
			$makepass .= $salt[mt_rand(0, 61)];
		}
		return $makepass;
	}

	public static function login($username, $password = false, array $params = array()) {

		$params += array(
			'redirect' => true
		);

		$return = (string) joosRequest::param('return');
		if ($return && !(strpos($return, 'com_registration') || strpos($return, 'com_login'))) {
			$return = $return;
		} elseif (isset($_SERVER['HTTP_REFERER'])) {
			$return = $_SERVER['HTTP_REFERER'];
		} else {
			$return = JPATH_SITE;
		}

		$user = new Users;
		$user->username = $username;
		$user->find();

		// если акаунт заблокирован
		if (!$user->id) {
			if (isset($params['return'])) {
				return json_encode(array('error' => 'Такого пользователя нет'));
			} else {
				joosRoute::redirect($return, 'Такого пользователя нет');
			}
		}

		// если акаунт заблокирован
		if ($user->state == 0) {
			if (isset($params['return'])) {
				return json_encode(array('error' => _LOGIN_BLOCKED));
			} else {
				joosRoute::redirect($return, _LOGIN_BLOCKED);
			}
		}

		//Проверям пароль
		if (!self::check_password($password, $user->password)) {
			if (isset($params['return'])) {
				return json_encode(array('error' => _LOGIN_INCORRECT));
			} else {
				joosRoute::redirect($return, _LOGIN_INCORRECT);
			}
		}

		// пароль проверили, теперь можно заводить сессиию и ставить куки авторизации
		$session = new joldSession;
		$session->time = time();
		$session->guest = 0;
		$session->username = $user->username;
		$session->userid = $user->id;
		$session->groupname = $user->groupname;
		$session->gid = $user->gid;
		$session->is_admin = 0;
		// сгенерием уникальный ID, захеширем его через sessionCookieValue и запишем в базу
		$session->generateId();
		// записываем в базу данные о авторизованном пользователе и его сессии
		if (!$session->insert()) {
			die($session->get_error());
		}

		// формируем и устанавливаем пользователю куку что он автоизован
		$sessionCookieName = joosSession::sessionCookieName();
		// в значении куки - НЕ хешированное session_id из базы
		setcookie($sessionCookieName, $session->getCookie(), false, '/', JCOOKIE_PACH);

		// очищаем базу от всех прежних сессий вновь авторизовавшегося пользователя
		$query = "DELETE FROM #__session WHERE  is_admin=0 AND session_id != " . $session->_db->quote($session->session_id) . " AND userid = " . (int) $user->id;
		joosDatabase::instance()->set_query($query)->query();

		// обновляем дату последнего визита авторизованного пользователя
		$user->lastvisitDate = _CURRENT_SERVER_TIME;
		$user->store();

		// а тут еще ставится кука на год если пользователь решил запоить авторизацию
		// не работает
		/**
		 *         $remember = joosRequest::param('remember',false);
		 *         if ( $remember ) {
		 *             // cookie lifetime of 365 days
		 *             $lifetime = time() + 365 * 24 * 60 * 60;
		 *             $remCookieName = joosMainframe::remCookieName_Users();
		 *             //а в конце - ID пользователя в базе
		 *             $remCookieValue = joosMainframe::remCookieValue_Users($row->username) . joosMainframe::remCookieValue_Pass($hash) . $row->id;
		 *             setcookie($remCookieName, $remCookieValue, $lifetime, '/');
		 *         }
		 */
		if (isset($params['return'])) {
			return json_encode(array('user' => $user));
		} else {
			joosRoute::redirect($return);
		}
	}

	public static function logout() {
		// получаем название куки ктоторая должна быть у пользователя
		$sessionCookieName = joosSession::sessionCookieName();
		// из куки пробуем получить ХЕШ - значение
		$sessioncookie = (string) joosRequest::cookies($sessionCookieName);

		// в базе хранится еще рах хешированное значение куки, повторим его что бы получить нужное
		$sessionValueCheck = joosSession::sessionCookieValue($sessioncookie);

		$lifetime = time() - 86400;
		setcookie($sessionCookieName, ' ', $lifetime, '/', JCOOKIE_PACH);
		//session_start();
		//@session_destroy();

		$query = "DELETE FROM #__session WHERE session_id = " . joosDatabase::instance()->quote($sessionValueCheck);
		return joosDatabase::instance()->set_query($query)->query();
	}

	// проверка что пользователь уже авторизован
	public static function login_check() {
		// получаем название куки ктоторая должна быть у пользователя
		$sessionCookieName = joosSession::sessionCookieName();
		// из куки пробуем получить ХЕШ - значение
		$sessioncookie = (string) joosRequest::cookies($sessionCookieName);

		// в базе хранится еще рах хешированное значение куки, повторим его что бы получить нужное
		$sessionValueCheck = joosSession::sessionCookieValue($sessioncookie);
		// объект сессий
		$session = new joldSession;
		// проверяем что кука есть, длина в норме и по ней есть запись в базе
		if ($sessioncookie && strlen($sessioncookie) == 32 && $sessioncookie != '-' && $session->load($sessionValueCheck)) {
			echo 'всё пучкоме';
			// всё нормально - обновляем время действия сессии в базе
			$session->time = time();
			$session->update();
		}
	}

	// быстрая проверка авторизации пользователя
	public static function is_loged() {
		$sessionCookieName = joosSession::sessionCookieName();
		$sessioncookie = (string) joosRequest::cookies($sessionCookieName);
		$session = new joldSession;
		if ($sessioncookie && strlen($sessioncookie) == 32 && $sessioncookie != '-' && $session->load(joosSession::sessionCookieValue($sessioncookie))) {
			return true;
		}
		return false;
	}

}

/**
 * UsersExtra - Модель расширенной информации о пользователях
 * Модель для работы сайта
 *
 * @version 1.0
 * @package Joostina.Models
 * @subpackage Users
 * @author Joostina Team <info@joostina.ru>
 * @copyright (C) 2008-2011 Joostina Team
 * @license MIT License http://www.opensource.org/licenses/mit-license.php
 * Информация об авторах и лицензиях стороннего кода в составе Joostina CMS: docs/copyrights
 *
 * */
class UsersExtra extends joosModel {

	/**
	 * @var int(11)
	 */
	public $user_id;
	/**
	 * @var varchar(10)
	 */
	public $gender;
	/**
	 * @var tinytext (json)
	 */
	public $about;
	/**
	 * @var varchar(255)
	 */
	public $location;
	/**
	 * @var text (json)
	 */
	public $contacts;
	/**
	 * @var date
	 */
	public $birthdate;
	/**
	 * @var text (json)
	 */
	public $interests;
	/**
	 * @var text (json)
	 */
	public $cache_bookmarks;
	/**
	 * @var text (json)
	 */
	public $cache_votes;
	public $cache_muzvotes;

	public function __construct() {
		$this->joosDBModel('#__users_extra', 'user_id');
	}

	public function check() {
		$this->filter(array('about'));
		return true;
	}

	public function before_store() {
		
	}

	public static function get_contacts_types() {
		return array(
			'icq' => 'ICQ',
			'jabber' => 'Jabber',
			'google' => 'GoogleTalk',
			'msn' => 'MSN (Live!)',
			'skype' => 'Skype',
			'twitter' => 'Twitter',
			'vkontakte' => 'Вконтакте',
			'site' => 'Сайт'
		);
	}

	public static function get_interests() {
		return array(
			'Создание сайтов',
			'Программирование',
			'Дизайн',
			'Вёрстка',
			'Интерфейсы',
			'Оптимизация'
		);
	}

	/**
	 * Обновление кеша закладок пользователя
	 * User $user - объект пользователя
	 */
	public static function update_cache_bookmarks(User $user) {

		$current_cache = joosDatabase::instance()->set_query('SELECT * FROM #__bookmarks WHERE user_id=' . (int) $user->id)->load_object_list();

		$new_cache = array();
		foreach ($current_cache as $cache) {
			$new_cache[$cache->obj_option][$cache->obj_task][$cache->obj_id] = $cache->created_at;
		}

		$userextra = new self;
		$userextra->user_id = $user->id;
		$userextra->cache_bookmarks = json_encode($new_cache);
		$userextra->check();
		return (bool) $userextra->store();
	}

	/**
	 * Обновление кеша голосований пользователя
	 * User $user - объект пользователя
	 */
	public static function update_votes_cache(User $user, $vote_type = '') {

		$new_cache = array();

		$current_cache = joosDatabase::instance()->set_query("SELECT * FROM #__votes_blog WHERE user_id=" . (int) $user->id)->load_object_list();
		foreach ($current_cache as $cache) {
			$new_cache['blog'][$cache->obj_id] = $cache->vote;
		}

		//$current_cache = joosDatabase::instance()->setQuery("SELECT * FROM #__votes_comment WHERE user_id=" . (int) $user->id)->loadObjectList();
		//foreach ($current_cache as $cache) {
		//$new_cache['comment'][$cache->obj_id] = $cache->vote;
		//}

		$userextra = new self;
		$userextra->user_id = $user->id;
		$userextra->cache_votes = json_encode($new_cache);
		$userextra->check();
		return (bool) $userextra->store();
	}

}

/**
 * UsersGroups - Модель пользовательских групп
 * Модель для работы сайта
 *
 * @version 1.0
 * @package Joostina.Models
 * @subpackage Users
 * @author Joostina Team <info@joostina.ru>
 * @copyright (C) 2008-2011 Joostina Team
 * @license MIT License http://www.opensource.org/licenses/mit-license.php
 * Информация об авторах и лицензиях стороннего кода в составе Joostina CMS: docs/copyrights
 *
 * */
class UsersGroups extends joosModel {

	/**
	 * @var int(10) unsigned
	 */
	public $id;
	/**
	 * @var int(10) unsigned
	 */
	public $parent_id;
	/**
	 * @var varchar(100)
	 */
	public $title;
	/**
	 * @var varchar(255)
	 */
	public $group_title;

	/*
	 * Constructor
	 */
	function __construct() {
		$this->joosDBModel('#__users_groups', 'id');
	}

}

class joldSession extends joosModel {

	public $session_id = null;
	public $time = null;
	public $userid = null;
	public $groupname = null;
	public $username = null;
	public $gid = null;
	public $guest = null;
	public $_session_cookie = null;

	function __construct() {
		$this->joosDBModel('#__session', 'session_id');
	}

	function insert() {
		$ret = $this->_db->insert_object($this->_tbl, $this);
		if (!$ret) {
			$this->_error = strtolower(get_class($this)) . "::store failed <br />" . $this->_db->stderr();
			return false;
		} else {
			return true;
		}
	}

	function update($updateNulls = false) {
		$ret = $this->_db->update_object($this->_tbl, $this, 'session_id', $updateNulls);
		if (!$ret) {
			$this->_error = strtolower(get_class($this)) . "::update error <br />" . $this->_db->stderr();
			return false;
		} else {
			return true;
		}
	}

	function generateId() {
		$failsafe = 20;
		$randnum = 0;
		while ($failsafe--) {
			$randnum = md5(uniqid(microtime(), 1));
			$new_session_id = joosSession::sessionCookieValue($randnum);
			if ($randnum != '') {
				$query = "SELECT $this->_tbl_key FROM $this->_tbl WHERE $this->_tbl_key = " . $this->_db->quote($new_session_id);
				$this->_db->set_query($query);
				if (!$result = $this->_db->query()) {
					die($this->_db->stderr(true));
				}
				if ($this->_db->get_num_rows($result) == 0) {
					break;
				}
			}
		}
		$this->_session_cookie = $randnum;
		$this->session_id = $new_session_id;
	}

	function getCookie() {
		return $this->_session_cookie;
	}

	function purge($inc = 1800, $and = '', $lifetime = '') {

		if ($inc == 'core') {
			$past_logged = time() - $lifetime;
			$query = "DELETE FROM $this->_tbl WHERE time < '" . (int) $past_logged . "'";
		} else {
			// kept for backward compatability
			$past = time() - $inc;
			$query = "DELETE FROM $this->_tbl WHERE ( time < '" . (int) $past . "' )" . $and;
		}
		return $this->_db->set_query($query)->query();
	}

}

class UserValidations {

	public static function registration() {
		joosLoader::lib('formvalidator', 'forms');
		$validator = new FormValidator();
		$validator
				->addValidation("username", "req", "Введите логин")
				->addValidation("username", "minlen=2", "Минимум  2 символа")
				->addValidation("username", "maxlen=15", "Максимум 15 символов")
				->addValidation("username", "remote=/register/check/user", "Логин уже занят или запрещён")
				//->addValidation("username", "usernameRegex=^[A-Za-z0-9-_]", "В логине запрещенные символы")
				//->addValidation('username', 'usernameRegex=true', 'рас рас')
				->addValidation("email", "email", "Введён неправильный email")
				->addValidation("email", "req", "Не введён email-адрес")
				->addValidation("email", "remote=/register/check/email", "Такой email уже используется")
				->addValidation("password", "req", "Введите пароль")
				->addValidation("password", "minlen=3", "Минимум для пароль - 3 символа")
				->addValidation("password", "maxlen=15", "Максимум для пароля - 15 символов");

		return $validator;
	}

	public static function login() {
		
	}

	public static function edit() {
		joosLoader::lib('formvalidator', 'forms');
		$validator = new FormValidator();
		$validator
				->addValidation("email", "email", "Введён невалидный email")
				->addValidation("email", "req", "Не введён email-адрес")
				->addValidation("password_old", "minlen=3", "Минимум для пароль - 3 символа")
				->addValidation("password_old", "maxlen=15", "Максимум для пароля - 15 символов")
				->addValidation("password_new", "minlen=3", "Минимум для пароль - 3 символа")
				->addValidation("password_new", "maxlen=15", "Максимум для пароля - 15 символов");

		return $validator;
	}

}

class UserHelper {

	public static function get_canonikal($username) {
		// приводим к единому нижнему регистру
		$text = joosString:: strtolower($username);

		// убираем спецсимволы
		$to_del = array('~', '@', '#', '$', '%', '^', '&amp;', '*', '(', ')', '-', '_', '+', '=', '|', '?', ',', '.', '/', ';', ':', '"', "'", '№', ' ');
		$text = str_replace($to_del, '', $text);

		// приводим одинаковое начертание к единому тексту
		$a = array('о', 'o', 'l', 'L', '|', '!', 'i', 'х', 's', 'а', 'р', 'с', 'в', 'к', 'е', 'й', 'ё', 'ш', 'з', 'ъ', 'у', 'т', 'м', 'н');
		$b = array('0', '0', '1', '1', '1', '1', '1', 'x', '$', 'a', 'p', 'c', 'b', 'k', 'e', 'и', 'е', 'щ', '3', 'ь', 'y', 't', 'm', 'h');
		$text = str_replace($a, $b, $text);

		// убираем дуУубли символов
		$return = $o = '';
		$_l = joosString::strlen($text);
		for ($i = 0; $i < $_l; $i++) {
			$c = joosString::substr($text, $i, 1);
			if ($c != $o) {
				$return .= $c;
				$o = $c;
			}
		}
		$new[] = $return;
		return $return;
	}

}