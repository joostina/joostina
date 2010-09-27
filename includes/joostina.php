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

// отлаживаем по максимуму
error_reporting(E_ALL & ~E_DEPRECATED);
ini_set('display_errors', 1);

//Europe/Moscow // GMT0
function_exists('date_default_timezone_set') ? date_default_timezone_set(date_default_timezone_get()) : null;

// запрос идёт через Ajax
DEFINE('_IS_AJAX', ( isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest') ? true : false);

// язык сайта
DEFINE('JLANG', 'russian');

// http корень для изображений
DEFINE('JPATH_SITE_IMAGES', JPATH_SITE);
// http корень для файлов
DEFINE('JPATH_SITE_FILES', JPATH_SITE);

// путь для установки кук
define('_COOKIE_PACH', str_replace(array('http://', 'https://', 'www'), '', JPATH_SITE));

// мягкое удаление - создание полных копий удаляемых данных в общесистемную корзину
DEFINE('_DB_SOFTDELETE', true);

// каталог администратора
DEFINE('JADMIN_BASE', 'administrator');

// активация работы в режиме отладки - осуществляется через ручную установку в браузере куки с произвольным названием, по умолчанию - joostinadebugmode
define('JDEBUG_TEST_MODE', (bool) isset($_COOKIE['joostinadebugmode']));

// параметр активации отладки, можно совмещать с JDEBUG_TEST_MODE
define('JDEBUG', (bool) true);

// формат даты
DEFINE('_CURRENT_SERVER_TIME_FORMAT', '%Y-%m-%d %H:%M:%S');

// текущее время сервера
DEFINE('_CURRENT_SERVER_TIME', date('Y-m-d H:i:s', time()));

// пробуем устанавить более удобный режим работы
@set_magic_quotes_runtime(0);

// установка режима отображения ошибок
($mosConfig_error_reporting == 0 && !JDEBUG) ? error_reporting(0) : error_reporting($mosConfig_error_reporting);

JDEBUG ? error_reporting(E_ALL | E_NOTICE | E_STRICT) : null;

// при активном полном или тестовом режиме отладки подключим дополнительную библиотеку отладки
(JDEBUG || JDEBUG_TEST_MODE) ? mosMainFrame::addLib('debug') : null;

/* библиотека для работы с юникодом */
mosMainFrame::addLib('jstring');

/* библиотека фильтрации данных */
mosMainFrame::addLib('inputfilter');

/* библиотека работы с базой данных */
mosMainFrame::addLib('database');

/**
 * Статический класс для хранения и обмена данными внутри приложения
 */
class Jstatic {

	/**
	 * Статичная переменная для хранения данных
	 * @var array
	 */
	public static $data = array();

}

/**
 * Корневой клас ядра Joostina!
 * @package Joostina
 */
class mosMainFrame {

	private static $_instance;
	/**
	  @var database Internal database class pointer */
	public $_db;
	/**
	  @var object An object of path variables */
	private $_path;
	/**
	  @var mosSession The current session */
	private $_session;
	/**
	  @var array An array to hold global user state within a session */
	private $_userstate;
	/**
	 * системное сообщение
	 */
	protected $mosmsg;
	public static $is_admin = false;

	/**
	 * Инициализация ядра
	 * @param boolen $isAdmin - инициализация в пространстве панели управления
	 */
	function __construct($isAdmin = false) {
		// объект конфигурации системы
		$this->config = Jconfig::getInstance();

		// делаем первое подключение к базе
		database::getInstance();

		if ($isAdmin) {
			// указываем параметр работы в админ-панели напрямую
			self::$is_admin = true;
			define('JTEMPLATE', 'joostfree');
			$option = mosGetParam($_REQUEST, 'option');
			$this->_setAdminPaths($option);
		} else {
			define('JTEMPLATE', 'games');
			//define('JTEMPLATE', 'simple-organization-website-template');
		}

		if (isset($_SESSION['session_userstate'])) {
			$this->_userstate = &$_SESSION['session_userstate'];
		} else {
			$this->_userstate = null;
		}
	}

	/**
	 * Получение прямой ссылки на объект ядра
	 * @param boolen $isAdmin - инициализация ядра в пространстве панели управления
	 * @return mosMainFrame - объект ядра
	 */
	public static function getInstance($isAdmin = false) {

		JDEBUG ? jd_inc('mosMainFrame::getInstance()') : null;

		if (self::$_instance === NULL) {
			self::$_instance = new self($isAdmin);
		}

		return self::$_instance;
	}

	// подключение представление для компонентов панели управления
	public function adminView($target) {
		global $option;

		$default = 'administrator' . DS . 'components' . DS . $option . DS . 'view' . DS . $target . '.php';
		$from_template = 'administrator' . DS . 'templates' . DS . JTEMPLATE . DS . 'html' . DS . $option . DS . $target . '.php';

		if (is_file($return = JPATH_BASE . DS . $from_template)) {
			return $return;
		} elseif (is_file($return = JPATH_BASE . DS . $default)) {
			return $return;
		} else {
			return false;
		}
	}

	function set($property, $value = null) {
		$this->$property = $value;
	}

	function get($property, $default = null) {
		if (isset($this->$property)) {
			return $this->$property;
		} else {
			return $default;
		}
	}

	// получение объекта базы данных из текущего объекта
	public function getDBO() {
		jd_log_top('УБЕРИ getDBO ёкарнабаай!');
		return database::getInstance();
	}

	/**
	 * Подключение библиотеки
	 * @param string $lib Название библиотеки. Может быть сформировано как: `lib_name`, `lib_name/lib_name.php`, `lib_name.php`
	 * @param string $dir Директория библиотеки. Необязательный параметр. По умолчанию, поиск файла осуществляется в 'includes/libraries'
	 */
	public static function addLib($lib, $dir = null) {
		$dir = $dir ? $dir : 'includes/libraries';

		(JDEBUG && $lib != 'debug' ) ? jd_inc('addLib::' . $lib) : null;

		$file_lib = JPATH_BASE . DS . $dir . DS . $lib . DS . $lib . '.php';
		is_file($file_lib) ? require_once($file_lib) : null;
	}

	/**
	 * Подключение классов
	 * @param string $lib Название класа. Может быть сформировано как: `class_name`, `class_name/class_name.php`, `class_name.php`
	 * @param string $dir Директория класа. Необязательный параметр. По умолчанию, поиск файла осуществляется в 'includes/classes'
	 */
	public static function addClass($class, $dir = null) {
		$dir = $dir ? $dir : 'includes/classes';

		$file_class = JPATH_BASE . DS . $dir . DS . $class . '.class.php';
		is_file($file_class) ? require_once($file_class) : null;
	}

	public function getLangFile($name = '') {

		if (!$name) {
			return JPATH_BASE . DS . 'language' . DS . JLANG . DS . 'system.php';
		} else {
			$file = $name;
		}

		if (self::$is_admin == true) {
			if (is_file(JPATH_BASE . DS . 'language' . DS . JLANG . DS . 'administrator' . DS . $file . '.php')) {
				return JPATH_BASE . DS . 'language' . DS . JLANG . DS . 'administrator' . DS . $file . '.php';
			} else {
				if (is_file(JPATH_BASE . DS . 'language' . DS . JLANG . DS . 'frontend' . DS . $file . '.php')) {
					return JPATH_BASE . DS . 'language' . DS . JLANG . DS . 'frontend' . DS . $file . '.php';
				}
			}
		} else {
			if (is_file(JPATH_BASE . DS . 'language' . DS . JLANG . DS . 'frontend' . DS . $file . '.php')) {
				return JPATH_BASE . DS . 'language' . DS . JLANG . DS . 'frontend' . DS . $file . '.php';
			}
		}

		return false;
	}

	function getUserState($var_name) {
		return is_array($this->_userstate) ? mosGetParam($this->_userstate, $var_name, null) : null;
	}

	function getUserStateFromRequest($var_name, $req_name, $var_default = null) {
		if (is_array($this->_userstate)) {
			if (isset($_REQUEST[$req_name])) {
				$this->setUserState($var_name, $_REQUEST[$req_name]);
			} else
			if (!isset($this->_userstate[$var_name])) {
				$this->setUserState($var_name, $var_default);
			}

			$this->_userstate[$var_name] = InputFilter::getInstance()->process($this->_userstate[$var_name]);
			return $this->_userstate[$var_name];
		} else {
			return null;
		}
	}

	/**
	 * Устанавливает переменную в сессию пользователя
	 * @param string названи е переменной
	 * @param string значение переменнной
	 */
	function setUserState($var_name, $var_value) {
		if (is_array($this->_userstate)) {
			$this->_userstate[$var_name] = $var_value;
		}
	}

	function initSession() {

		// initailize session variables
		//$session = &$this->_session;

		$session = new mosSession();
		// очистка сессий один раз 2х, случайно
		(rand(0, 2) == 1) ? $session->purge('core', '', $this->config->config_lifetime) : null;

		// Session Cookie `name`
		$sessionCookieName = mosMainFrame::sessionCookieName();


		// Get Session Cookie `value`
		$sessioncookie = strval(mosGetParam($_COOKIE, $sessionCookieName, null));

		// Session ID / `value`
		$sessionValueCheck = mosMainFrame::sessionCookieValue($sessioncookie);

		// Check if existing session exists in db corresponding to Session cookie `value`
		// extra check added in 1.0.8 to test sessioncookie value is of correct length
		if ($sessioncookie && strlen($sessioncookie) == 32 && $sessioncookie != '-' && $session->load($sessionValueCheck)) {
			// update time in session table
			$session->time = time();
			$session->update();
		} else {
			// Remember Me Cookie `name`
			$remCookieName = mosMainFrame::remCookieName_User();

			// test if cookie found
			$cookie_found = false;
			if (isset($_COOKIE[$sessionCookieName]) || isset($_COOKIE[$remCookieName]) || isset($_POST['force_session'])) {
				$cookie_found = true;
			}

			// check if neither remembermecookie or sessioncookie found
			if (!$cookie_found) {
				// create sessioncookie and set it to a test value set to expire on session end
				setcookie($sessionCookieName, '-', false, '/', _COOKIE_PACH);
			} else {
				// otherwise, sessioncookie was found, but set to test val or the session expired, prepare for session registration and register the session
				$url = strval(mosGetParam($_SERVER, 'REQUEST_URI', null));
				// stop sessions being created for requests to syndicated feeds
				if (strpos($url, 'option=rss') === false && strpos($url, 'feed=') === false) {
					$session->guest = 1;
					$session->username = '';
					$session->time = time();
					$session->gid = 0;
					// Generate Session Cookie `value`
					$session->generateId();
					if (!$session->insert()) {
						die($session->getError());
					}
					// create Session Tracking Cookie set to expire on session end
					setcookie($sessionCookieName, $session->getCookie(), false, '/', _COOKIE_PACH);
				}
			}
			// Cookie used by Remember me functionality
			$remCookieValue = strval(mosGetParam($_COOKIE, $remCookieName, null));

			// test if cookie is correct length
			if (strlen($remCookieValue) > 64) {
				// Separate Values from Remember Me Cookie
				$remUser = substr($remCookieValue, 0, 32);
				$remPass = substr($remCookieValue, 32, 32);
				$remID = intval(substr($remCookieValue, 64));

				// check if Remember me cookie exists. Login with usercookie info.
				if (strlen($remUser) == 32 && strlen($remPass) == 32) {
					$this->login($remUser, $remPass, 1, $remID);
				}
			}
		}
	}

	function initSessionAdmin($option, $task) {

		// logout check
		if ($option == 'logout') {
			require JPATH_BASE_ADMIN . DS . 'logout.php';
			exit();
		}

		// check if session name corresponds to correct format
		if (session_name() != md5(JPATH_SITE)) {
			echo "<script>document.location.href='index.php'</script>\n";
			mosRedirect(JPATH_SITE . '/' . JADMIN_BASE . '/');
			exit();
		}

		// restore some session variables
		$my = new User();
		$my->id = intval(mosGetParam($_SESSION, 'session_user_id', ''));
		$my->username = strval(mosGetParam($_SESSION, 'session_USER', ''));
		$my->groupname = strval(mosGetParam($_SESSION, 'session_groupname', ''));
		$my->gid = intval(mosGetParam($_SESSION, 'session_gid', ''));
		$my->params = mosGetParam($_SESSION, 'session_user_params', '');
		$my->bad_auth_count = mosGetParam($_SESSION, 'session_bad_auth_count', '');

		$session_id = mosGetParam($_SESSION, 'session_id', '');
		$logintime = mosGetParam($_SESSION, 'session_logintime', '');

		if ($session_id != session_id()) {
			// session id does not correspond to required session format
			mosRedirect(JPATH_SITE . '/' . JADMIN_BASE . '/', _YOU_NEED_TO_AUTH);
		}

		// check to see if session id corresponds with correct format
		if ($session_id == md5($my->id . $my->username . $my->groupname . $logintime)) {

			// if task action is to `save` or `apply` complete action before doing session checks.
			if ($task != 'save' && $task != 'apply') {

				$database = database::getInstance();

				$_config = $this->get('config');
				// test for session_life_admin
				if ($_config->config_session_life_admin) {
					$session_life_admin = $_config->config_session_life_admin;
				} else {
					$session_life_admin = 1800;
				}
				// если в настройка не указано что сессии админки не уничтожаются - выполняем запрос по очистке сессий
				if ($_config->config_admin_autologout == 1) {
					// purge expired admin sessions only
					$past = time() - $session_life_admin;
					$query = "DELETE FROM #__session WHERE time < '" . (int) $past . "' AND guest = 1 AND gid = 0 AND userid <> 0";
					$database->setQuery($query)->query();
				}

				$current_time = time();

				// update session timestamp
				$query = "UPDATE #__session SET time = " . $database->Quote($current_time) . " WHERE session_id = " . $database->Quote($session_id);
				$_config->config_admin_autologout == 1 ? $database->setQuery($query)->query() : null;

				// set garbage cleaning timeout
				$this->setSessionGarbageClean();

				// check against db record of session
				$query = "SELECT COUNT( session_id ) FROM #__session WHERE session_id = " . $database->Quote($session_id) . " AND username = " . $database->Quote($my->username) . " AND userid = " . intval($my->id);
				$count = ($_config->config_admin_autologout == 1) ? $database->setQuery($query)->loadResult() : 1;

				// если в таблице нет информации о текущей сессии - она устарела
				if ($count == 0) {
					// TODO тут можно сделать нормальную запоминалку последней активной страницы, и разных данных с неё. И записывать всё это как параметры пользователя в JSON
					mosRedirect(JPATH_SITE . '/' . JADMIN_BASE . '/', _ADMIN_SESSION_ENDED);
				} else {
					// load variables into session, used to help secure /popups/ functionality
					$_SESSION['option'] = $option;
					$_SESSION['task'] = $task;
				}
			}
		} elseif ($session_id == '') {
			// no session_id as user has not attempted to login, or session.auto_start is switched on
			if (ini_get('session.auto_start') || !ini_get('session.use_cookies')) {
				mosRedirect(JPATH_SITE, _YOU_NEED_TO_AUTH_AND_FIX_PHP_INI);
			} else {
				mosRedirect(JPATH_SITE, _YOU_NEED_TO_AUTH);
			}
		} else {
			mosRedirect(JPATH_SITE, _WRONG_USER_SESSION);
			exit();
		}

		return $my;
	}

	function setSessionGarbageClean() {
		/** ensure that funciton is only called once */
		if (!defined('_JOS_GARBAGECLEAN')) {
			define('_JOS_GARBAGECLEAN', 1);

			$garbage_timeout = $this->getCfg('session_life_admin') + 600;
			@ini_set('session.gc_maxlifetime', $garbage_timeout);
		}
	}

	public static function sessionCookieName($site_name = '') {

		if (!$site_name) {
			$site_name = JPATH_SITE;
		}

		if (substr($site_name, 0, 7) == 'http://') {
			$hash = md5('site' . substr($site_name, 7));
		} elseif (substr($site_name, 0, 8) == 'https://') {
			$hash = md5('site' . substr($site_name, 8));
		} else {
			$hash = md5('site' . $site_name);
		}

		return $hash;
	}

	public static function sessionCookieValue($id = null) {
		$config = Jconfig::getInstance();
		$type = $config->config_session_type;
		$browser = @$_SERVER['HTTP_USER_AGENT'];

		switch ($type) {
			case 2:
				// 1.0.0 to 1.0.7 Compatibility
				// lowest level security
				$value = md5($id . $_SERVER['REMOTE_ADDR']);
				break;

			case 1:
				// slightly reduced security - 3rd level IP authentication for those behind IP Proxy
				$remote_addr = explode('.', $_SERVER['REMOTE_ADDR']);
				$ip = $remote_addr[0] . '.' . $remote_addr[1] . '.' . $remote_addr[2];
				$value = mosHash($id . $ip . $browser);
				break;

			default:
				// Highest security level - new default for 1.0.8 and beyond
				$ip = $_SERVER['REMOTE_ADDR'];
				$value = mosHash($id . $ip . $browser);
				break;
		}

		return $value;
	}

	public static function remCookieName_User() {
		return mosHash('remembermecookieusername' . mosMainFrame::sessionCookieName());
	}

	function remCookieName_Pass() {
		return mosHash('remembermecookiepassword' . mosMainFrame::sessionCookieName());
	}

	function remCookieValue_User($username) {
		return md5($username . mosHash(@$_SERVER['HTTP_USER_AGENT']));
	}

	function remCookieValue_Pass($passwd) {
		return md5($passwd . mosHash(@$_SERVER['HTTP_USER_AGENT']));
	}

	/**
	 * Функция авторизации пользователя
	 */
	public function login($username = null, $passwd = null, $remember = 0, $userid = null) {

		$return = strval(mosGetParam($_REQUEST, 'return', false));

		$return = $return ? $return : strval(mosGetParam($_SERVER, 'HTTP_REFERER', null));

		$bypost = 0;
		$valid_remember = false;

		// if no username and password passed from function, then function is being called from login module/component
		if (!$username || !$passwd) {
			$username = stripslashes(strval(mosGetParam($_POST, 'username', '')));
			$passwd = stripslashes(strval(mosGetParam($_POST, 'password', '')));

			$bypost = 1;

			// extra check to ensure that Joomla! sessioncookie exists
			if (!$this->_session->session_id) {
				//mosErrorAlert(_ALERT_ENABLED);
				//mosRedirect( JPATH_SITE , _ALERT_ENABLED);
				//return;
			}

			if ($this->_session->session_id) {
				josSpoofCheck(null, 1);
			}

			//josSpoofCheck(null, 1);
		}

		$row = null;
		if (!$username || !$passwd) {
			mosRedirect($return, _LOGIN_INCOMPLETE);
			exit();
		} else {
			$database = database::getInstance();
			if ($remember && strlen($username) == 32 && $userid) {

				// query used for remember me cookie
				$harden = mosHash(@$_SERVER['HTTP_USER_AGENT']);

				$query = "SELECT id, username, password, state, gid,groupname FROM #__users WHERE id = " . (int) $userid;
				$user = null;

				$database->setQuery($query)->loadObject($user);

				list($hash, $salt) = explode(':', $user->password);

				$check_USER = md5($user->username . $harden);
				$check_password = md5($hash . $harden);

				if ($check_USER == $username && $check_password == $passwd) {
					$row = $user;
					$valid_remember = true;
				}
			} else {
				// query used for login via login module
				$query = "SELECT id,  username, password, state, gid,groupname FROM #__users WHERE username = " . $database->Quote($username);
				$database->setQuery($query)->loadObject($row);
			}

			if (is_object($row)) {

				// если акаунт заблокирован
				if ($row->state == 0) {
					mosRedirect($return, _LOGIN_BLOCKED);
				}

				if (!$valid_remember) {
					list($hash, $salt) = explode(':', $row->password);
					$cryptpass = md5($passwd . $salt);

					if ($hash != $cryptpass) {
						if ($bypost) {
							mosRedirect($return, _LOGIN_INCORRECT);
						} else {
							$this->logout();
							mosRedirect(JPATH_SITE);
						}
					}
				}

				// initialize session data
				$session = &$this->_session;
				$session->guest = 0;
				$session->username = $row->username;
				$session->userid = $row->id;
				$session->groupname = $row->groupname;
				$session->gid = $row->gid;
				$session->update();
				//$session->store();

				$query = "DELETE FROM #__session WHERE session_id != " . $database->Quote($session->session_id) . " AND username = " . $database->Quote($row->username) . " AND userid = " . (int) $row->id . " AND gid = " . (int) $row->gid . " AND guest = 0";
				$database->setQuery($query)->query();

				// update user visit data
				$query = "UPDATE #__users SET lastvisitDate = " . $database->Quote(_CURRENT_SERVER_TIME) . " WHERE id = " . (int) $session->userid;
				$database->setQuery($query)->query();

				// set remember me cookie if selected
				$remember = (int) mosGetParam($_POST, 'remember', 0);
				if ($remember) {
					// cookie lifetime of 365 days
					$lifetime = time() + 365 * 24 * 60 * 60;
					$remCookieName = mosMainFrame::remCookieName_User();
					$remCookieValue = mosMainFrame::remCookieValue_User($row->username) . mosMainFrame::remCookieValue_Pass($hash) . $row->id;
					setcookie($remCookieName, $remCookieValue, $lifetime, '/', _COOKIE_PACH);
				}
			} else {
				if ($bypost) {
					mosRedirect($return, _LOGIN_INCORRECT);
				} else {
					$this->logout();
					mosRedirect('index.php');
				}
			}
		}
	}

	/**
	 * Разлогинивание пользователя
	 * Записывает в текущию сесиию гостевые параметры
	 */
	public function logout() {
		$session = &$this->_session;
		$session->guest = 1;
		$session->username = '';
		$session->userid = '';
		$session->groupname = '';
		$session->gid = 0;
		$session->update();
		// kill remember me cookie
		$lifetime = time() - 86400;
		$remCookieName = mosMainFrame::remCookieName_User();
		setcookie($remCookieName, ' ', $lifetime, '/', _COOKIE_PACH);
		@session_destroy();
	}

	/**
	 * @return User возвращает объект пользовательской сессии
	 */
	public function getUser() {
		$user = new User();

		$user->id = (int) $this->_session->userid;
		$user->username = $this->_session->username;
		$user->groupname = $this->_session->groupname;
		$user->gid = (int) $this->_session->gid;
		if ($user->id) {
			$query = "SELECT  u.id, u.username, u.email, u.state, u.gid, u.registerDate, u.lastvisitDate, ue.level
			FROM #__users  AS u
			LEFT JOIN #__users_extra AS ue ON ue.user_id=u.id
			WHERE u.id = " . $user->id;
			database::getInstance()->setQuery($query, 0, 1)->loadObject($my);

			$user->username = $my->username;
			$user->email = $my->email;
			$user->state = $my->state;
			$user->registerDate = $my->registerDate;
			$user->lastvisitDate = $my->lastvisitDate;
			$user->gid = $my->gid;
			$user->level = $my->level;
		} else {
			$user->name = _GUEST_USER;
			$user->username = _GUEST_USER;
			$user->gid = 0;
			$user->groupname = 'guest';
		}
		return $user;
	}

	public function getCfg($varname) {
		$varname = 'config_' . $varname;

		$config = $this->get('config');
		return (isset($config->$varname)) ? $config->$varname : null;
	}

	/**
	 * Установка переменных окружения для путей
	 * @param string $name - название переменной пути
	 * @param string $path  - непосредственно сам путь
	 */
	public function setPath($name, $path) {
		if (is_file($path)) {
			$this->_path->$name = $path;
		}
	}

	private function _setAdminPaths($option, $basePath = JPATH_BASE) {
		$option = strtolower($option);

		$this->_path = new stdClass();

		// security check to disable use of `/`, `\\` and `:` in $options variable
		if (strpos($option, '/') !== false || strpos($option, '\\') !== false || strpos($option, ':') !== false) {
			mosErrorAlert(_ACCESS_DENIED);
			return;
		}

		$prefix = substr($option, 0, 4);
		if ($prefix != 'mod_') {
			// ensure backward compatibility with existing links
			$name = $option;
			$option = $option;
		} else {
			$name = substr($option, 4);
		}

		$template = JTEMPLATE;

		// components
		if (file_exists("$basePath/templates/$template/components/$name.html.php")) {
			$this->_path->front = "$basePath/components/$option/$name.php";
			$this->_path->front_html = "$basePath/templates/$template/components/$name.html.php";
		} elseif (file_exists("$basePath/components/$option/$name.php")) {
			$this->_path->front = "$basePath/components/$option/$name.php";
			$this->_path->front_html = "$basePath/components/$option/$name.html.php";
		}

		if (file_exists("$basePath/" . JADMIN_BASE . "/components/$option/admin.$name.php")) {
			$this->_path->admin = "$basePath/" . JADMIN_BASE . "/components/$option/admin.$name.php";
			$this->_path->admin_html = "$basePath/" . JADMIN_BASE . "/components/$option/admin.$name.html.php";
		}

		if (file_exists("$basePath/administrator/components/$option/toolbar.$name.php")) {
			$this->_path->toolbar = "$basePath/" . JADMIN_BASE . "/components/$option/toolbar.$name.php";
			$this->_path->toolbar_html = "$basePath/" . JADMIN_BASE . "/components/$option/toolbar.$name.html.php";
			$this->_path->toolbar_default = "$basePath/" . JADMIN_BASE . "/includes/toolbar.html.php";
		}

		if (file_exists("$basePath/components/$option/$name.class.php")) {
			$this->_path->class = "$basePath/components/$option/$name.class.php";
		} elseif (file_exists("$basePath/" . JADMIN_BASE . "/components/$option/$name.class.php")) {
			$this->_path->class = "$basePath/" . JADMIN_BASE . "/components/$option/$name.class.php";
		} elseif (file_exists("$basePath/includes/$name.php")) {
			$this->_path->class = "$basePath/includes/$name.php";
		}

		if ($prefix == 'mod_' && file_exists("$basePath/" . JADMIN_BASE . "/modules/$option.php")) {
			$this->_path->admin = "$basePath/" . JADMIN_BASE . "/modules/$option.php";
			$this->_path->admin_html = "$basePath/" . JADMIN_BASE . "/modules/mod_$name.html.php";
		} elseif (file_exists("$basePath/" . JADMIN_BASE . "/components/$option/admin.$name.php")) {
			$this->_path->admin = "$basePath/" . JADMIN_BASE . "/components/$option/admin.$name.php";
			$this->_path->admin_html = "$basePath/" . JADMIN_BASE . "/components/$option/admin.$name.html.php";
		} else {
			$this->_path->admin = "$basePath/" . JADMIN_BASE . "/components/admin/admin.admin.php";
			$this->_path->admin_html = "$basePath/" . JADMIN_BASE . "/components/admin/admin.admin.html.php";
		}
	}

	/**
	 * Получение пути окружения
	 * @param string $varname - название переменной
	 * @param string $option - название компонента дял которого получается переменные окружения
	 * @return string путь
	 */
	public function getPath($varname, $option = '') {

		if ($option) {
			$temp = $this->_path;
			$this->_setAdminPaths($option, JPATH_BASE);
		}

		$result = null;
		if (isset($this->_path->$varname)) {
			$result = $this->_path->$varname;
		} else {
			switch ($varname) {
				case 'xml':
					//$name = substr($option, 4);
					$path = JPATH_BASE . DS . JADMIN_BASE . "/components/$option/$name.xml";
					if (file_exists($path)) {
						$result = $path;
					} else {
						$path = JPATH_BASE . "/components/$option/$name.xml";
						$result = file_exists($path) ? $path : $result;
					}
					break;

				case 'mod0_xml':
					// Site modules
					if ($option == '') {
						$path = JPATH_BASE . '/modules/mod_custom/mod_custom.xml';
					} else {
						$path = JPATH_BASE . "/modules/$option/$option.xml";
					}
					$result = file_exists($path) ? $path : $result;
					break;

				case 'mod1_xml':
					// admin modules
					if ($option == '') {
						$path = JPATH_BASE . DS . JADMIN_BASE . '/modules/mod_custom/mod_custom.xml';
					} else {
						$path = JPATH_BASE . DS . JADMIN_BASE . "/modules/$option/$option.xml";
					}
					$result = file_exists($path) ? $path : $result;
					break;

				case 'menu_xml':
					$path = JPATH_BASE . DS . JADMIN_BASE . "/components/menus/$option/$option.xml";
					$result = file_exists($path) ? $path : $result;
					break;
			}
		}
		if ($option) {
			$this->_path = $temp;
		}
		return $result;
	}

	public function isAdmin() {
		return self::$is_admin;
	}

	/**
	 * Установка системного сообщения
	 * @param string $msg - текст сообщения
	 */
	public static function set_mosmsg($msg='') {
		$msg = Jstring::trim($msg);

		if ($msg != '') {
			if (mosMainFrame::$is_admin) {
				$_s = session_id();
				if (empty($_s)) {
					session_name(md5(JPATH_SITE));
					session_start();
				}
			} else {
				session_name(mosMainFrame::sessionCookieName());
				session_start();
			}

			$_SESSION['joostina.mosmsg'] = $msg;
		}
	}

	/**
	 * Получение системного сообщения
	 * @return string - текст сообщения
	 */
	public function get_mosmsg() {

		$_s = session_id();

		if (!self::$is_admin && empty($_s)) {
			session_name(mosMainFrame::sessionCookieName());
			session_start();
		}

		$mosmsg_ss = trim(stripslashes(strval(mosGetParam($_SESSION, 'joostina.mosmsg', ''))));
		$mosmsg_rq = stripslashes(strval(mosGetParam($_REQUEST, 'mosmsg', '')));

		$mosmsg = ($mosmsg_ss != '') ? $mosmsg_ss : $mosmsg_rq;

		if ($mosmsg != '' && Jstring::strlen($mosmsg) > 300) { // выводим сообщения не длинее 300 символов
			$mosmsg = Jstring::substr($mosmsg, 0, 300);
		}

		unset($_SESSION['joostina.mosmsg']);
		return $mosmsg;
	}

}

// главный класс конфигурации системы
class JConfig {

	// закрытая переменная для хранения текущий инстанции
	private static $_instance;
	/** @public int */
	public $config_offline = null;
	/** @public string */
	public $config_offline_message = null;
	/** @public string */
	public $config_error_message = null;
	/** @public string */
	public $config_sitename = null;
	/** @public string */
	public $config_editor = 'none';
	/** @public int */
	public $config_list_limit = 30;
	/** @public string */
	public $config_favicon = null;
	/** @public string */
	public $config_frontend_login = 1;
	/** @public int */
	public $config_debug = 0;
	/** @public string */
	public $config_host = null;
	/** @public string */
	public $config_user = null;
	/** @public string */
	public $config_password = null;
	/** @public string */
	public $config_db = null;
	/** @public string */
	public $config_dbprefix = null;
	/** @public string */
	public $config_absolute_path = null;
	/** @public string */
	public $config_live_site = null;
	/** @public string */
	public $config_secret = null;
	/** @public int */
	public $config_gzip = 0;
	/** @public int */
	public $config_lifetime = 900;
	/** @public int */
	public $config_session_life_admin = 1800;
	/** @public int */
	public $config_admin_expired = 1;
	/** @public int */
	public $config_session_type = 0;
	/** @public int */
	public $config_error_reporting = 0;
	/** @public string */
	public $config_fileperms = '0644';
	/** @public string */
	public $config_dirperms = '0755';
	/** @public string */
	public $config_locale = null;
	/** @public string */
	public $config_lang = null;
	/** @public int */
	public $config_offset = null;
	/** @public int */
	public $config_offset_user = null;
	/** @public string */
	public $config_mailer = null;
	/** @public string */
	public $config_mailfrom = null;
	/** @public string */
	public $config_fromname = null;
	/** @public string */
	public $config_sendmail = '/usr/sbin/sendmail';
	/** @public string */
	public $config_smtpauth = 0;
	/** @public string */
	public $config_smtpuser = null;
	/** @public string */
	public $config_smtppass = null;
	/** @public string */
	public $config_smtphost = null;
	/** @public int */
	public $config_caching = 0;
	/** @public string */
	public $config_cachepath = null;
	/** @public string */
	public $config_cachetime = null;
	/** @public int */
	public $config_allowUserRegistration = 0;
	/** @public int */
	public $config_useractivation = null;
	/** @public int */
	public $config_frontend_userparams = 1;
	/** @public string */
	public $config_MetaDesc = null;
	/** @public string */
	public $config_MetaKeys = null;
	/** @public int */
	public $config_MetaTitle = null;
	/** @public int */
	public $config_MetaAuthor = null;
	/** @public int */
	public $config_sef = 0;
	/** @public int */
	public $config_pagetitles = 1;
	/** @public int */
	public $config_multilingual_support = 0;
	/** @public int отключение ведения сессий на фронте */
	public $config_no_session_front = 0;
	/** @public int отключение тега Generator */
	public $config_generator_off = 0;
	/** @public str использование одного шаблона на весь сайт */
	public $config_one_template = '...';
	/** @public int подсчет времени генерации страницы */
	public $config_time_generate = 0;
	/** @public int индексация страницы печати */
	public $config_index_print = 0;
	/** @public int расширенные теги индексации */
	public $config_index_tag = 0;
	/** @public int использование ежесуточной оптимизации таблиц базы данных */
	public $config_optimizetables = 1;
	/** @public int кэширование меню панели управления */
	public $config_adm_menu_cache = 0;
	/** @public int расположение элементов title */
	public $config_pagetitles_first = 1;
	/** @public string разделитель "заголовок страницы - Название сайта " */
	public $config_tseparator = ' - ';
	/** @int отключение captcha */
	public $config_captcha = 1;
	/** @str корень для компонента управления медиа содержимым */
	public $config_media_dir = 'media/images';
	/** @str формат даты */
	public $config_form_date = '%d.%m.%Y г.';
	/** @str полный формат даты и времени */
	public $config_form_date_full = '%d.%m.%Y г. %H:%M';
	/** @int не показывать "Главная" на первой странице */
	public $config_pathway_clean = 1;
	/** @int автоматические разлогинивание в панели управления после окончания жизни сессии */
	public $config_admin_autologout = 1;
	/** @int отключение favicon */
	public $config_disable_favicon = 1;
	/** @str смещение для rss */
	public $config_feed_timeoffset = null;
	/** @int использовать расширенную отладку на фронте */
	public $config_front_debug = 0;
	/** @public int автоматическая авторизация после подтверждения регистрации */
	public $config_auto_activ_login = 0;
	/** @public int включение оптимизации функции кэширования */
	public $config_cache_opt = 0;
	/** @public int вывод мета-тега base */
	public $config_mtage_base = 1;
	/** @public int вывод мета-тега revisit в днях */
	public $config_mtage_revisit = 10;
	/** @public int использование страницы печати из каталога текущего шаблона */
	public $config_custom_print = 0;
	/** @public int отключение предпросмотра шаблонов через &tp=1 */
	public $config_disable_tpreview = 0;
	/** @int включение кода безопасности для доступа к панели управления */
	public $config_enable_admin_secure_code = 0;
	/** @int включение кода безопасности для доступа к панели управления */
	public $config_admin_secure_code = 'admin';
	/** @int режим редиректа при включенном коде безопасноти */
	public $config_admin_redirect_options = 0;
	/** @int адрес редиректа при включенном коде безопасноти */
	public $config_admin_redirect_path = '404.html';
	/** @public int число попыток автооизации для входа в админку */
	public $config_admin_bad_auth = 5;
	/** @public int обработчик кэширования */
	public $config_cache_handler = 'none';
	/** @public int ключ для кэш файлов */
	public $config_cache_key = '';
	/** @public array настройки memCached */
	public $config_memcache_persistent = 0;
	/** @public array настройки memCached */
	public $config_memcache_compression = 0;
	/** @public array настройки memCached */
	public $config_memcache_host = 'localhost';
	/** @public array настройки memCached */
	public $config_memcache_port = '11211';
	/** @public str название шаблона панели управления */
	public $config_admin_template = '...';
	/** @public int активация блокировок компонентов */
	public $config_components_access = 0;
	/** @public int чисто неудачный авторизаций для блокировки аккаунта */
	public $config_count_for_user_block = 10;

	// инициализация класса конфигурации - собираем переменные конфигурации
	private function __construct() {
		$this->bindGlobals();
	}

	/**
	 * Запрет клонирования объекта
	 * @return  JConfig
	 */
	private function __clone() {

	}

	// получение инстанции конфигурации системы
	public static function getInstance() {

		JDEBUG ? jd_inc('JConfig::getInstance()') : null;

		if (self::$_instance === NULL) {
			self::$_instance = new self;
		}

		return self::$_instance;
	}

	/**
	 * @return array An array of the public vars in the class
	 */
	function getPublicVars() {
		$public = array();
		$vars = array_keys(get_class_vars('JConfig'));
		sort($vars);
		foreach ($vars as $v) {
			if ($v{0} != '_') {
				$public[] = $v;
			}
		}
		return $public;
	}

	/**
	 * 	binds a named array/hash to this object
	 * 	@param array $hash named array
	 * 	@return null|string	null is operation was satisfactory, otherwise returns an error
	 */
	function bind($array, $ignore = '') {
		if (!is_array($array)) {
			$this->_error = strtolower(get_class($this)) . '::' . _CONSTRUCT_ERROR;
			return false;
		} else {
			return mosBindArrayToObject($array, $this, $ignore);
		}
	}

	/**
	 * Writes the configuration file line for a particular variable
	 * @return string
	 */
	function getVarText() {
		$txt = '';
		$vars = $this->getPublicVars();
		foreach ($vars as $v) {
			$k = str_replace('config_', 'mosConfig_', $v);
			$txt .= "\$$k = '" . addslashes($this->$v) . "';\n";
		}
		return $txt;
	}

	/**
	 * заполнение данных класса данными из глобальных перменных
	 */
	function bindGlobals() {
		$vars = array_keys(get_class_vars('JConfig'));
		foreach ($vars as $v) {
			$k = str_replace('config_', 'mosConfig_', $v);
			if (isset($GLOBALS[$k]))
				$this->$v = $GLOBALS[$k];
		}
	}

}

/**
 * Utility function to return a value from a named array or a specified default
 * @param array A named array
 * @param string The key to search for
 * @param mixed The default value to give if no key found
 * @param int An options mask: _MOS_NOTRIM prevents trim, _MOS_ALLOWHTML allows safe html, _MOS_ALLOWRAW allows raw input
 */
define("_NOTRIM", 0x0001);
define("_ALLOWHTML", 0x0002);

function mosGetParam(&$arr, $name, $def = null, $mask = 0) {

	$return = null;
	if (isset($arr[$name])) {
		$return = $arr[$name];

		if (is_string($return)) {
			$return = (!($mask & _NOTRIM)) ? trim($return) : $return;

			$return = ($mask & _ALLOWHTML) ? InputFilter::getInstance()->process($return) : $return;

			// account for magic quotes setting
			$return = (!get_magic_quotes_gpc()) ? addslashes($return) : $return;
		}

		return $return;
	} else {
		return $def;
	}
}

/**
 * Strip slashes from strings or arrays of strings
 * @param mixed The input string or array
 * @return mixed String or array stripped of slashes
 */
function mosStripslashes(&$value) {
	$ret = '';
	if (is_string($value)) {
		$ret = stripslashes($value);
	} else {
		if (is_array($value)) {
			$ret = array();
			foreach ($value as $key => $val) {
				$ret[$key] = mosStripslashes($val);
			}
		} else {
			$ret = $value;
		}
	}
	return $ret;
}

/**
 * Copy the named array content into the object as properties
 * only existing properties of object are filled. when undefined in hash, properties wont be deleted
 * @param array the input array
 * @param obj byref the object to fill of any class
 * @param string
 * @param boolean
 */
function mosBindArrayToObject($array, &$obj, $ignore = '', $prefix = null, $checkSlashes = true) {
	if (!is_array($array) || !is_object($obj)) {
		return (false);
	}
	$ignore = ' ' . $ignore . ' ';
	foreach (get_object_vars($obj) as $k => $v) {
		if (substr($k, 0, 1) != '_') { // internal attributes of an object are ignored
			if (strpos($ignore, ' ' . $k . ' ') === false) {
				if ($prefix) {
					$ak = $prefix . $k;
				} else {
					$ak = $k;
				}
				if (isset($array[$ak])) {
					$obj->$k = ($checkSlashes && get_magic_quotes_gpc()) ? mosStripslashes($array[$ak]) : $array[$ak];
				}
			}
		}
	}
	return true;
}

/**
 * Utility function redirect the browser location to another url
 *
 * Can optionally provide a message.
 * @param string The file system path
 * @param string A filter for the names
 */
function mosRedirect($url, $msg = '') {
	// specific filters
	$iFilter = InputFilter::getInstance();
	$url = $iFilter->process($url);

	empty($msg) ? null : mosMainFrame::set_mosmsg($iFilter->process($msg));

	// Strip out any line breaks and throw away the rest
	$url = preg_split("/[\r\n]/", $url);
	$url = $url[0];
	if ($iFilter->badAttributeValue(array('href', $url))) {
		$url = JPATH_SITE;
	}

	if (headers_sent ()) {
		echo "<script>document.location.href='$url';</script>\n";
	} else {
		@ob_end_clean(); // clear output buffer
		header('HTTP/1.1 301 Moved Permanently');
		header("Location: " . $url);
	}
	exit();
}

function mosErrorAlert($text, $action = 'window.history.go(-1);', $mode = 1) {
	$text = nl2br($text);
	$text = addslashes($text);
	$text = strip_tags($text);

	switch ($mode) {
		case 2:
			echo "<script>$action</script> \n";
			break;

		case 1:
		default:
			echo "<meta http-equiv=\"Content-Type\" content=\"text/html; charset=UTF-8\" />";
			echo "<script>alert('$text'); $action</script> \n";
			break;
	}

	exit;
}

function mosFormatDate($date, $format = '', $offset = null) {
	static $config_offset;

	if (!isset($config_offset)) {
		$config_offset = Jconfig::getInstance()->config_offset;
	}

	if ($date == '0000-00-00 00:00:00')
		return $date; //database::$_nullDate - при ошибках парсера

		if ($format == '') {
		// %Y-%m-%d %H:%M:%S
		$format = _DATE_FORMAT_LC;
	}
	if (is_null($offset)) {
		$offset = $config_offset;
	}
	if ($date && preg_match("/^([0-9]{4})-([0-9]{2})-([0-9]{2})[ ]([0-9]{2}):([0-9]{2}):([0-9]{2})/", $date, $regs)) {
		$date = mktime($regs[4], $regs[5], $regs[6], $regs[2], $regs[3], $regs[1]);
		$date = strftime($format, $date + ($offset * 60 * 60));
	}

	return $date;
}

function mosCurrentDate($format = "") {
	static $config_offset;

	if (!isset($config_offset)) {
		$config_offset = Jconfig::getInstance()->config_offset;
	}

	if ($format == '') {
		$format = _DATE_FORMAT_LC;
	}

	$date = strftime($format, time() + ($config_offset * 60 * 60));
	return $date;
}

class JHTML {

	/**
	 * Массив хранения подключенных расширений Jquery
	 * @var array
	 */
	private static $jqueryplugins;

	/**
	 * Подключение JS файла в тело страницы
	 * @param string $file путь до js файла
	 * @return string код включение js файла
	 */
	public static function js_file($file) {
		$file = ( (strpos($file, '://') === FALSE) ) ? JPATH_SITE . $file : $file;
		return '<script type="text/javascript" src="' . $file . '"></script>';
	}

	/**
	 * Вывод JS кодя в тело страницы
	 * @param string $code текст js кода
	 * @return string
	 */
	public static function js_code($code) {
		return '<script type="text/javascript" charset="utf-8">;' . $code . ';</script>';
	}

	public static function loadJqueryPlugins($name, $ret = false, $css = false) {

		// формируем константу-флаг для исключения повтороной загрузки

		if (!isset(self::$jqueryplugins[$name])) {
			// отмечаем плагин в массиве уже подключенных
			self::$jqueryplugins[$name] = true;
			if ($ret) {
				echo JHTML::js_file(JPATH_SITE . '/media/js/jquery.plugins/' . $name . '.js');
				echo ($css) ? JHTML::css_file(JPATH_SITE . '/media/js/jquery.plugins/' . $name . '/' . $name . '.css') : '';
			} else {
				Jdocument::getInstance()->addJS(JPATH_SITE . '/media/js/jquery.plugins/' . $name . '.js');
				$css ? Jdocument::getInstance()->addCSS(JPATH_SITE . '/media/js/jquery.plugins/' . $name . '/' . $name . '.css') : null;
			}
		}
	}

	/**
	 * Подключение CSS файла в тело страницы
	 * @param string $file путь до js файла
	 * @param string $media парматр media для css файла
	 * @return string код включение js файла
	 */
	public static function css_file($file, $media = 'all') {
		$file = ( (strpos($file, '://') === FALSE) ) ? JPATH_SITE . $file : $file;
		return '<link rel="stylesheet" type="text/css" media="' . $media . '" href="' . $file . '" />';
	}

}

/**
 * Displays a not authorised message
 *
 * If the user is not logged in then an addition message is displayed.
 */
function mosNotAuth() {
	require_once JPATH_BASE . '/templates/system/notauth.php';
}

/**
 * Replaces &amp; with & for xhtml compliance
 *
 * Needed to handle unicode conflicts due to unicode conflicts
 */
function ampReplace($text) {
	$text = str_replace('&&', '*--*', $text);
	$text = str_replace('&#', '*-*', $text);
	$text = str_replace('&amp;', '&', $text);
	$text = preg_replace('|&(?![\w]+;)|', '&amp;', $text);
	$text = str_replace('*-*', '&#', $text);
	$text = str_replace('*--*', '&&', $text);
	return $text;
}

/**
 * Function to convert array to integer values
 * @param array
 * @param int A default value to assign if $array is not an array
 * @return array
 */
function mosArrayToInts(&$array, $default = null) {
	if (is_array($array)) {
		foreach ($array as $key => $value) {
			$array[$key] = (int) $value;
		}
	} else {
		if (is_null($default)) {
			$array = array();
			return array(); // Kept for backwards compatibility
		} else {
			$array = array((int) $default);
			return array($default); // Kept for backwards compatibility
		}
	}
}

/*
 * Получение массива значений
 * $name - название переменной
 */

function josGetArrayInts($name, $type = null) {
	if ($type == null) {
		$type = $_POST;
	}

	$array = mosGetParam($type, $name, array(0));

	mosArrayToInts($array);

	if (!is_array($array)) {
		$array = array(0);
	}

	return $array;
}

/**
 * Provides a secure hash based on a seed
 * @param string Seed string
 * @return string
 */
function mosHash($seed) {
	return md5($GLOBALS['mosConfig_secret'] . md5($seed));
}

function josSpoofCheck($header=NULL, $alt=NULL, $method = 'post') {

	switch (strtolower($method)) {
		case 'get':
			$validate = mosGetParam($_GET, josSpoofValue($alt), 0);
			break;

		case 'request':
			$validate = mosGetParam($_REQUEST, josSpoofValue($alt), 0);
			break;

		case 'post':
		default:
			$validate = mosGetParam($_POST, josSpoofValue($alt), 0);
			break;
	}

	// probably a spoofing attack
	if (!$validate) {
		header('HTTP/1.0 403 Forbidden');
		mosErrorAlert(_NOT_AUTH);
		return;
	}

	// First, make sure the form was posted from a browser.
	// For basic web-forms, we don't care about anything
	// other than requests from a browser:
	if (!isset($_SERVER['HTTP_USER_AGENT'])) {
		header('HTTP/1.0 403 Forbidden');
		mosErrorAlert(_NOT_AUTH);
		return;
	}

	// Make sure the form was indeed POST'ed:
	//  (requires your html form to use: action="post")
	if (!$_SERVER['REQUEST_METHOD'] == 'POST') {
		header('HTTP/1.0 403 Forbidden');
		mosErrorAlert(_NOT_AUTH);
		return;
	}

	if ($header) {
		// Attempt to defend against header injections:
		$badStrings = array(
			'Content-Type:',
			'MIME-Version:',
			'Content-Transfer-Encoding:',
			'bcc:',
			'cc:'
		);

		// Loop through each POST'ed value and test if it contains
		// one of the $badStrings:
		_josSpoofCheck($_POST, $badStrings);
	}
}

function _josSpoofCheck($array, $badStrings) {
	// Loop through each $array value and test if it contains
	// one of the $badStrings
	foreach ($array as $v) {
		if (is_array($v)) {
			_josSpoofCheck($v, $badStrings);
		} else {
			foreach ($badStrings as $v2) {
				if (stripos($v, $v2) !== false) {
					header('HTTP/1.0 403 Forbidden');
					mosErrorAlert(_NOT_AUTH);
					exit(); // mosErrorAlert dies anyway, double check just to make sure
				}
			}
		}
	}
}

/**
 * Method to determine a hash for anti-spoofing variable names
 *
 * @return	string	Hashed var name
 * @static
 */
function josSpoofValue($alt=NULL) {
	if ($alt) {
		$random = ( $alt == 1 ) ? $random = date('Ymd') : $alt . date('Ymd');
	} else {
		$random = date('dmY');
	}

	return 'j' . mosHash(JPATH_BASE . $random . User::current()->id);
}

/**
 * Объединение расширений системы в одно пространство имён
 *
 */
// отладка определённой переменной
function _xdump($var, $text='<pre>') {
	echo $text;
	print_r($var);
	echo "\n";
}

// класс работы с пользователями
require_once(JPATH_BASE . '/components/users/users.class.php');

class Jdocument {

	private static $instance;
	private static $title_separator = ' - ';
	public static $page_body;
	public static $data = array(
		'title' => false,
		'meta' => array(),
		'custom' => array(),
		'js' => array(),
		'css' => array(),
		'pathway' => array(),
		'pagetitle' => false,
		'page_body' => false,
		'html_body' => false,
		'footer' => array()
	);
	public static $config = array(
		'favicon' => true,
		'seotag' => true,
	);
	public static $seotag = array(
		'distribution' => 'global',
		'rating' => 'General',
		'document-state' => 'Dynamic',
		'documentType' => 'WebDocument',
		'audience' => 'all',
		'revisit' => '5 days',
		'revisit-after' => '5 days',
		'allow-search' => 'yes',
		'language' => 'russian',
		'robots' => 'index, follow',
	);
	// время кэширования страницы браузером, в секундах
	public static $cache_header_time = false;

	/**
	 *
	 * @return Jdocument
	 */
	public static function getInstance() {
		if (self::$instance === NULL) {
			self::$instance = new self;
			self::$data['title'] = JConfig::getInstance()->config_sitename;
		}
		return self::$instance;
	}

	public static function getData($name) {
		return isset(self::$data[$name]) ? self::$data[$name] : false;
	}

	public function setPageTitle($title = false, $pagetitle = false) {

		// title страницы
		self::$data['title'] = $title ? JConfig::getInstance()->config_sitename . self::$title_separator . $title : JConfig::getInstance()->config_sitename;

		// название страницы, не title!
		self::$data['pagetitle'] = $pagetitle ? $pagetitle : $title;

		return $this;
	}

	public function addMetaTag($name, $content) {
		$name = Jstring::trim(htmlspecialchars($name, ENT_QUOTES, 'UTF-8'));
		$content = Jstring::trim(htmlspecialchars($content, ENT_QUOTES, 'UTF-8'));
		self::$data['meta'][] = array($name, $content);

		return $this;
	}

	public function appendMetaTag($name, $content) {
		$n = count(self::$data['meta']);
		for ($i = 0; $i < $n; $i++) {
			if (self::$data['meta'][$i][0] == $name) {
				$content = Jstring::trim(htmlspecialchars($content, ENT_QUOTES, 'UTF-8'));
				if ($content != '' & self::$data['meta'][$i][1] == "") {
					self::$data['meta'][$i][1] .= ' ' . $content;
				};
				return;
			}
		}

		$this->addMetaTag($name, $content);
	}

	function prependMetaTag($name, $content) {
		$name = Jstring::trim(htmlspecialchars($name, ENT_QUOTES, 'UTF-8'));
		$n = count(self::$data['meta']);
		for ($i = 0; $i < $n; $i++) {
			if (self::$data['meta'][$i][0] == $name) {
				$content = Jstring::trim(htmlspecialchars($content, ENT_QUOTES, 'UTF-8'));
				self::$data['meta'][$i][1] = $content . self::$data['meta'][$i][1];
				return;
			}
		}
		self::getInstance()->addMetaTag($name, $content);
	}

	function addCustomHeadTag($html) {
		self::$data['custom'][] = trim($html);

		return $this;
	}

	function addCustomFooterTag($html) {
		self::$data['custom'][] = trim($html);

		return $this;
	}

	public function getHead() {

		$head = array();
		$head[] = isset(self::$data['title']) ? "\t" . '<title>' . self::$data['title'] . '</title>' : false;

		foreach (self::$data['meta'] as $meta) {
			$head[] = '<meta name="' . $meta[0] . '" content="' . trim($meta[1]) . '" />';
		}

		foreach (self::$data['custom'] as $html) {
			$head[] = $html;
		}

		return implode("\n\t", $head) . "\n";
	}

	public function getHeadData($name) {
		return isset($this->_head[$name]) ? $this->_head[$name] : array();
	}

	public function addJS($path, $params = array('first' => false)) {

		if (isset($params['first']) && $params['first'] == TRUE) {
			array_unshift(self::$data['js'], $path);
		} else {
			self::$data['js'][] = $path;
		}

		return $this;
	}

	public function addCSS($path, $params = array('media' => 'all')) {
		self::$data['css'][] = array($path, $params);

		return $this;
	}

	public function seotag($name, $value) {
		self::$seotag[$name] = $value;

		return $this;
	}

	public static function javascript() {
		$result = array();

		foreach (self::$data['js'] as $js_file) {
			$result[] = JHTML::js_file($js_file);
		}

		return implode("\n\t", $result) . "\n";
	}

	public static function stylesheet() {
		$result = array();

		foreach (self::$data['css'] as $css_file) {
			$result[] = JHTML::css_file($css_file[0], $css_file[1]['media']);
		}

		return implode("\n\t", $result) . "\n";
	}

	public static function head() {

		$jdocument = self::getInstance();

		$meta = Jdocument::getData('meta');
		$n = count($meta);

		$description = $keywords = false;

		for ($i = 0; $i < $n; $i++) {
			if ($meta[$i][0] == 'keywords') {
				$_meta_keys_index = $i;
				$keywords = $meta[$i][1];
			} else {
				if ($meta[$i][0] == 'description') {
					$_meta_desc_index = $i;
					$description = $meta[$i][1];
				}
			}
		}

		$description ? null : $jdocument->appendMetaTag('description', mosMainFrame::getInstance()->getCfg('MetaDesc'));
		$keywords ? null : $jdocument->appendMetaTag('keywords', mosMainFrame::getInstance()->getCfg('MetaKeys'));

		if (Jdocument::$config['seotag'] == TRUE) {
			foreach (self::$seotag as $key => $value) {
				$value != FALSE ? $jdocument->addMetaTag($key, $value) : null;
			}
		}

		echo $jdocument->getHead();


		// favourites icon
		if (self::$config['favicon'] == true) {
			$icon = JPATH_SITE . '/media/favicon.ico';
			echo "\t" . '<link rel="shortcut icon" href="' . $icon . '" />' . "\n\t";
		}
	}

	public static function body() {
		echo self::$data['page_body'];
	}

	public static function footer() {
		return implode("\n", self::$data['footer']);
	}

	public static function header() {
		if (!headers_sent()) {
			if (self::$cache_header_time) {
				header_remove('Pragma');
				header('Cache-Control: max-age=' . self::$cache_header_time);
				header('Expires: ' . gmdate('r', time() + self::$cache_header_time));
			} else {
				header('Pragma: no-cache');
				header('Cache-Control: no-cache, must-revalidate');
			}
			header('X-Powered-By: Joostina CMS');
		}
		header('Content-type: text/html; charset=UTF-8');
	}

	public static function templatemodule($name) {

		$file = JPATH_BASE . DS . 'templates' . DS . JTEMPLATE . DS . 'html/modules' . DS . $name . DS . $name . '.php';

		is_file($file) ? require_once $file : null;
	}

}

class joosCore {

	/**
	 *
	 * @var array массив внутренних путей переменных окружения
	 */
	private static $path = array();

	public static function path($name, $type) {

		if (isset(self::$path[$type][$name])) {
			return self::$path[$type][$name];
		}

		switch ($type) {
			case 'controller':
				$file = JPATH_BASE . DS . 'components' . DS . $name . DS . $name . '.php';
				break;

			case 'class':
				$file = JPATH_BASE . DS . 'components' . DS . $name . DS . $name . '.class.php';
				break;

			case 'admin_class':
				$file = JPATH_BASE . DS . JADMIN_BASE . DS . 'components' . DS . $name . DS . 'admin.' . $name . '.class.php';
				break;

			case 'html':
				$file = JPATH_BASE . DS . 'components' . DS . $name . DS . 'admin.' . $name . '.html.php';
				break;

			case 'admin_html':
				$file = JPATH_BASE . DS . JADMIN_BASE . DS . 'components' . DS . $name . DS . 'admin.' . $name . '.html.php';
				break;

			case 'lang':
				$file = JPATH_BASE . DS . 'language' . DS . JLANG . DS . 'frontend' . DS . $name . '.php';
				break;

			default:
				break;
		}

		if (is_file($file)) {
			self::$path[$type][$name] = $file;
			$return = $file;
		} else {
			$return = false;
		}

		return $return;
	}

}

class Jcontroller {

	public static $activroute;
	public static $controller;
	public static $task;
	public static $param;
	public static $error = false;

	public static function init() {
		// 
		Jdocument::header();
		// инициализируем соединение с базой
		database::getInstance();
	}

	/**
	 * Автоматическое определение и запуск метода действия
	 */
	public static function run() {

		$class = 'actions' . ucfirst(self::$controller);

		JDEBUG ? jd_log($class . '::' . self::$task) : null;

		$path = joosCore::path(self::$controller, 'controller');

		if (!is_file($path) || self::$activroute == '404') {
			return self::error404();
		} else {
			require_once ($path);
		}

		if (method_exists($class, self::$task)) {
			$results = call_user_func_array($class . '::' . self::$task, array());
		} else {
			//  в контроллере нет запрашиваемого метода
			return self::error404();
			//$results = call_user_func_array($class . '::index', array(self::$param));
			//$task = 'index';
		}
		if (is_array($results)) {
			self::views($results, self::$controller, self::$task);
		} elseif (is_string($results)) {
			echo $results;
		}
	}

	private static function views(array $params, $option, $task) {
		(isset($params['as_json']) && $params['as_json'] == TRUE ) ? self::as_json($params) : self::as_html($params, $option, $task);
	}

	private static function as_html(array $params, $option, $task) {
		$template = 'default';
		extract($params, EXTR_OVERWRITE);
		$viewfile = JPATH_BASE . DS . 'components' . DS . $option . DS . 'views' . DS . $task . DS . $template . '.php';

		unset($params, $option, $task);

		is_file($viewfile) ? require ($viewfile) : null;
	}

	private static function as_json(array $params) {
		unset($params['as_json']);
		echo json_encode($params);
		exit();
	}

	public static function error404() {
		header('HTTP/1.0 404 Not Found');
		echo _NOT_EXIST;
		//require_once (JPATH_BASE . '/templates/system/404.php');
		//exit(404);

		self::$error = 404;

		return;
	}

	/**
	 * Статичный запуск проитзвольной задачи из произвольного контроллера
	 * @param string $controller название контроллера
	 * @param string $task выполняемая задача
	 * @param array $params массив парамеьтров передаваемых задаче
	 */
	public static function staticrun($controller, $task, array $params) {

		self::$controller = $controller;
		self::$task = $task;
		self::$param = $params;
		self::$activroute = 'staticrun';


		self::run();
	}

}

