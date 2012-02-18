<?php

// запрет прямого доступа
defined('_JOOS_CORE') or die();

/**
 * modelUsers - Модель пользователей
 * Модель для работы сайта
 *
 * @package Joostina.Core.Components
 * @subpackage Users
 * @subpackage Core
 * @author JoostinaTeam
 * @copyright (C) 2007-2012 Joostina Team
 * @license MIT License http://www.opensource.org/licenses/mit-license.php
 * @version 1
 * @created 2011-11-16 22:03:25
 * Информация об авторах и лицензиях стороннего кода в составе Joostina CMS: docs/copyrights
 *
 */
class modelUsers extends joosModel {

	/**
	 * @field int(11) unsigned
	 * @type int
	 */
	public $id;

	/**
	 * @field varchar(50)
	 * @type string
	 */
	public $user_name;

	/**
	 * @field varchar(100)
	 * @type string
	 */
	public $user_name_canonikal;

	/**
	 * @field varchar(100)
	 * @type string
	 */
	public $real_name;

	/**
	 * @field varchar(100)
	 * @type string
	 */
	public $email;

	/**
	 * @field varchar(200)
	 * @type string
	 */
	public $openid;

	/**
	 * @field varchar(100)
	 * @type string
	 */
	public $password;

	/**
	 * @field tinyint(1) unsigned
	 * @type int
	 */
	public $state;

	/**
	 * @field tinyint(3) unsigned
	 * @type int
	 */
	public $group_id;

	/**
	 * @field varchar(25)
	 * @type string
	 */
	public $group_name;

	/**
	 * @field datetime
	 * @type datetime
	 */
	public $register_date;

	/**
	 * @field datetime
	 * @type datetime
	 */
	public $lastvisit_date;

	/**
	 * @field varchar(100)
	 * @type string
	 */
	public $activation;

	/**
	 * @field tinyint(2) unsigned
	 * @type int
	 */
	public $bad_auth_count;
	private static $user_instance;

	function __construct() {
		parent::__construct('#__users', 'id');
	}

	/**
	 * Получение инстанции ТЕКУЩЕГО АВТОРИЗОВАННОГО пользователя
	 *
	 * @return modelUsers
	 */
	public static function instance() {

		if (self::$user_instance === NULL) {

			$sessionCookieName = joosSession::sessionCookieName();
			$sessioncookie = (string) joosRequest::cookies($sessionCookieName);

			$session = new modelUsersSession;
			if ($sessioncookie && strlen($sessioncookie) == 32 && $sessioncookie != '-' && $session->load(joosSession::sessionCookieValue($sessioncookie))) {
				if ($session->user_id > 0) {
					$user = new self;
					$user->load($session->user_id);
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

	/**
	 * Получение объекта неавторизованного пользователя - гостя
	 * @return stdClass
	 */
	private static function get_guest() {
		$guest = new stdClass();
		$guest->id = 0;
		$guest->user_name = _GUEST_USER;
		return $guest;
	}

	public function check() {

		$this->filter();

		$query = "SELECT id FROM #__users WHERE user_name = " . $this->_db->quote($this->user_name) . " AND id != " . (int) $this->id;
		$xid = $this->_db->set_query($query)->load_result();
		if ($xid && $xid != $this->id) {
			$this->_error = __('Логин уже зарегистрирован');
			return false;
		}

		$query = "SELECT id FROM #__users WHERE email = " . $this->_db->quote($this->email) . " AND id != " . (int) $this->id;
		$xid = $this->_db->set_query($query)->load_result();
		if ($xid && $xid != $this->id) {
			$this->_error = __('Email уже зарегистрирован');
			return false;
		}

		return true;
	}

	protected function before_store() {

		if (!$this->id) {
			$this->password = self::prepare_password($this->password);
			$this->register_date = JCURRENT_SERVER_TIME;
		} else {
			if (( $new_password = joosRequest::post('new_password', false))) {
				$this->password = self::prepare_password($new_password);
			}
		}

		// получаем название группы пользователя
		$groups = new modelUsersGroups();
		$groups->load($this->group_id);

		// название группы пользователя
		$this->group_name = $groups->title;

		// формируем дополнительно каноничное имя
		$this->user_name_canonikal = joosText::to_canonikal($this->user_name);

		// сохраняем группы пользователя
		$this->save_one_to_many('#__acl_users_groups', 'user_id', 'group_id', $this->id, joosRequest::array_param('user_groups'));
	}

	/**
	 * После создания нового пользователя
	 *
	 * @return bool результат работы
	 */
	protected function after_insert() {

		// Добавление в таблицу расширенной информации и пользователях новой записи - для только что зарегистрированного пользователя
		$extra = new modelUsersExtra;
		$extra->user_id = $this->id;
		$this->_db->insert_object('#__users_extra', $extra);

		return true;
	}

	/**
	 * modelUsers::check_password()
	 * Проверка введенного пароля на соответствие паролю в БД
	 *
	 * @param str $input_password
	 * @param str $real_password
	 *
	 * @return bool
	 */
	public static function check_password($input_password, $real_password) {
		// из хешированного значения пароля хранящегося в базе извлекаем соль
		list( $hash, $salt ) = explode(':', $real_password);
		// формируем хеш из введённого пользователм пароля и соли из базе
		$cryptpass = md5($input_password . $salt);

		// сравниваем хешированный пароль из базы и хеш от введённго пароля
		if ($hash != $cryptpass) {
			return false;
		}

		return true;
	}

	/**
	 * modelUsers::prepare_password()
	 * Подготовка пароля для записи в БД
	 *
	 * @param str $password
	 *
	 * @return str
	 */
	public static function prepare_password($password) {

		$salt = joosRandomizer::hash(16);
		$crypt = md5($password . $salt);

		return $crypt . ':' . $salt;
	}

	/**
	 * Получение объекта текущего пользователя
	 * @return modelUsers
	 */
	public static function current() {
		// TODO тут надо как-то унифицировать
		return joosCore::is_admin() ? joosCoreAdmin::user() : self::instance();
	}

	public static function login($user_name, $password = false, array $params = array()) {

		$params += array('redirect' => true);

		$return = (string) joosRequest::param('return');
		if ($return && !( strpos($return, 'com_registration') || strpos($return, 'com_login') )) {
			//$return = $return;
		} elseif (isset($_SERVER['HTTP_REFERER'])) {
			$return = $_SERVER['HTTP_REFERER'];
		} else {
			$return = JPATH_SITE;
		}

		$user = new modelUsers;
		$user->user_name = $user_name;
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
		$session = new modelUsersSession;
		$session->time = time();
		$session->guest = 0;
		$session->user_name = $user->user_name;
		$session->user_id = $user->id;
		$session->group_name = $user->group_name;
		$session->group_id = $user->group_id;
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
		$query = "DELETE FROM #__users_session WHERE  is_admin=0 AND session_id != " . $session->_db->quote($session->session_id) . " AND user_id = " . (int) $user->id;
		joosDatabase::instance()->set_query($query)->query();

		// обновляем дату последнего визита авторизованного пользователя
		$user->lastvisit_date = JCURRENT_SERVER_TIME;
		$user->store();

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

		$query = "DELETE FROM #__users_session WHERE session_id = " . joosDatabase::instance()->quote($sessionValueCheck);
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
		$session = new modelUsersSession;
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
		$session = new modelUsersSession;
		if ($sessioncookie && strlen($sessioncookie) == 32 && $sessioncookie != '-' && $session->load(joosSession::sessionCookieValue($sessioncookie))) {
			return true;
		}
		return false;
	}

}

/**
 * modelUsersExtra - Модель расширенной информации о пользователях
 * Модель для работы сайта
 *
 * @version    1.0
 * @package    Joostina.Models
 * @subpackage modelUsers
 * @author     Joostina Team <info@joostina.ru>
 * @copyright  (C) 2007-2012 Joostina Team
 * @license    MIT License http://www.opensource.org/licenses/mit-license.php
 * Информация об авторах и лицензиях стороннего кода в составе Joostina CMS: docs/copyrights
 *
 * */
class modelUsersExtra extends joosModel {

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
	public $birth_date;

	/**
	 * @var text (json)
	 */
	public $interests;

	public function __construct() {
		parent::__construct('#__users_extra', 'user_id');
	}

	public function check() {
		$this->filter(array('about'));
		return true;
	}

}

/**
 * modelUsersGroups - Модель пользовательских групп
 * Модель для работы сайта
 *
 * @version    1.0
 * @package    Joostina.Models
 * @subpackage modelUsers
 * @author     Joostina Team <info@joostina.ru>
 * @copyright  (C) 2007-2012 Joostina Team
 * @license    MIT License http://www.opensource.org/licenses/mit-license.php
 * Информация об авторах и лицензиях стороннего кода в составе Joostina CMS: docs/copyrights
 *
 * */
class modelUsersGroups extends joosModel {

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
		parent::__construct('#__users_groups', 'id');
	}

}

class modelUsersSession extends joosModel {

	public $session_id = null;
	public $time = null;
	public $user_id = null;
	public $group_name = null;
	public $user_name = null;
	public $group_id = null;
	public $guest = null;
	public $_session_cookie = null;

	function __construct() {
		parent::__construct('#__users_session', 'session_id');
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