<?php

/**
 * @package Joostina
 * @copyright Авторские права (C) 2007-2010 Joostina team. Все права защищены.
 * @license Лицензия http://www.gnu.org/licenses/gpl-2.0.htm GNU/GPL, или help/license.php
 * Joostina! - свободное программное обеспечение распространяемое по условиям лицензии GNU/GPL
 * Для получения информации о используемых расширениях и замечаний об авторском праве, смотрите файл help/copyright.php.
 */
// запрет прямого доступа
defined('_VALID_MOS') or die();

//Europe/Moscow // GMT0
function_exists('date_default_timezone_set') ? date_default_timezone_set(date_default_timezone_get()) : null;

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
// параметр активации отладки
define('JDEBUG', (bool) true);
// формат даты
DEFINE('_CURRENT_SERVER_TIME_FORMAT', '%Y-%m-%d %H:%M:%S');
// текущее время сервера
DEFINE('_CURRENT_SERVER_TIME', date('Y-m-d H:i:s', time()));
// схемы не http/https протоколов
DEFINE('_URL_SCHEMES', 'data:, file:, ftp:, gopher:, imap:, ldap:, mailto:, news:, nntp:, telnet:, javascript:, irc:, mms:');

// пробуем устанавить более удобный режим работы
@set_magic_quotes_runtime(0);

// установка режима отображения ошибок
($mosConfig_error_reporting == 0) ? error_reporting(0) : error_reporting($mosConfig_error_reporting);

/* библиотека отладчика */
JDEBUG ? mosMainFrame::addLib('debug') : null;
/* библиотека для работы с юникодом */
mosMainFrame::addLib('utf8');
/* библиотека фильтрации данных */
mosMainFrame::addLib('inputfilter');
/* библиотека работы с базой данных */
mosMainFrame::addLib('database');
/* класс парсинга параметров и работы с XML */
//mosMainFrame::addClass('parameters');

/**
 * Статический класс для хранения и обмена данными внутри приложения
 */
class Jstatic {

	public static $data = array();

}

/**
 * Joostina! Mainframe class
 *
 * Provide many supporting API functions
 * @package Joostina
 */
class mosMainFrame {

	private static $_instance;
	/**
	  @var database Internal database class pointer */
	public $_db = null;
	/**
	  @var object An object of configuration variables */
	//private $_config = null;
	public $config = null;
	/**
	  @var object An object of path variables */
	private $_path = null;
	/**
	  @var mosSession The current session */
	private $_session = null;
	/**
	  @var string The current template */
	private $_template = null;
	/**
	  @var array An array to hold global user state within a session */
	private $_userstate = null;
	/**
	  @var array An array of page meta information */
	private $_head = null;
	/**
	  @var string Custom html string to append to the pathway */
	private $_custom_pathway = null;
	/**
	  @var boolean True if in the admin client */
	private $_isAdmin = false;
	/**
	  @var массив данных выводящися в нижней части страницы */
	protected $_footer = null;
	/**
	 * системное сообщение
	 */
	protected $mosmsg = '';
	/**
	 * текущий язык
	 */
	public static $is_admin = false;

	/**
	 * Заглушка для запрета клонирования объекта
	 */
	private function __clone() {
		
	}

	/**
	 * Инициализация ядра
	 * @param boolen $isAdmin - инициализация в пространстве панели управления
	 */
	function __construct($isAdmin = false) {
		// объект конфигурации системы
		$this->config = Jconfig::getInstance();
		// объект работы с базой данных
		$this->_db = database::getInstance();

		if (!$isAdmin) {
			$current = $this->get_option();
			$this->option = $option = $current['option'];
			$this->setTemplate($isAdmin);
		} else {// для панели управления работаем с меню напрямую
			$option = strval(strtolower(mosGetParam($_REQUEST, 'option')));
			// указываем параметр работы в админ-панели унапрямую
			self::$is_admin = true;
		}

		$this->_setAdminPaths($option, JPATH_BASE);

		$this->_isAdmin = (boolean) $isAdmin;

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
		/* ОТЛАДКА
		  if(JDEBUG) {
		  $d = debug_backtrace();
		  jd_log( 'mosMainFrame::getInstance  '.$d[0]['file'].'::'.$d[0]['line'] );
		  }
		 */
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

	/**
	 * @param string The name of the property
	 * @param mixed The value of the property to set
	 */
	function set($property, $value = null) {
		$this->$property = $value;
	}

	/**
	 * @param string The name of the property
	 * @param mixed  The default value
	 * @return mixed The value of the property
	 */
	function get($property, $default = null) {
		if (isset($this->$property)) {
			return $this->$property;
		} else {
			return $default;
		}
	}

	// получение объекта базы данных из текущего объекта
	public function getDBO() {
		return $this->_db;
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

	/**
	 *
	 * @global string $mosConfig_lang
	 * @param <type> $name
	 * @param <type> $mosConfig_lang
	 * @return <type>
	 */
	public function getLangFile($name = '', $mosConfig_lang='') {
		if (empty($mosConfig_lang)) {
			global $mosConfig_lang;
		}

		$lang = $mosConfig_lang;

		if (!$name) {
			return JPATH_BASE . DS . 'language' . DS . $lang . DS . 'system.php';
		} else {
			$file = $name;
		}

		if (self::$is_admin == true) {
			if (is_file(JPATH_BASE . DS . 'language' . DS . $lang . DS . 'administrator' . DS . $file . '.php')) {
				return JPATH_BASE . DS . 'language' . DS . $lang . DS . 'administrator' . DS . $file . '.php';
			} else {
				if (is_file(JPATH_BASE . DS . 'language' . DS . $lang . DS . 'frontend' . DS . $file . '.php')) {
					return JPATH_BASE . DS . 'language' . DS . $lang . DS . 'frontend' . DS . $file . '.php';
				}
			}
		} else {
			if (is_file(JPATH_BASE . DS . 'language' . DS . $lang . DS . 'frontend' . DS . $file . '.php')) {
				return JPATH_BASE . DS . 'language' . DS . $lang . DS . 'frontend' . DS . $file . '.php';
			}
		}

		return false;
	}

	/**
	 * Gets the value of a user state variable
	 * @param string The name of the variable
	 */
	function getUserState($var_name) {
		return is_array($this->_userstate) ? mosGetParam($this->_userstate, $var_name, null) : null;
	}

	/**
	 * Gets the value of a user state variable
	 * @param string The name of the user state variable
	 * @param string The name of the variable passed in a request
	 * @param string The default value for the variable if not found
	 */
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

	/**
	 *
	 * @return <type>
	 */
	function initSession() {
		if ($this->getCfg('no_session_front')) {
			return;
		}

		// initailize session variables
		$session = &$this->_session;
		$session = new mosSession($this->_db);
		// purge expired sessions
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
				if (strpos($url, 'option=com_rss') === false && strpos($url, 'feed=') === false) {
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

	/**
	 *
	 * @param <type> $option
	 * @param <type> $task
	 * @return <type>
	 */
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
		$my = new mosUser($this->_db);
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
			exit();
		}

		// check to see if session id corresponds with correct format
		if ($session_id == md5($my->id . $my->username . $my->groupname . $logintime)) {
			// if task action is to `save` or `apply` complete action before doing session checks.
			if ($task != 'save' && $task != 'apply') {
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
					$this->_db->setQuery($query)->query();
				}

				$current_time = time();

				// update session timestamp
				$query = "UPDATE #__session SET time = " . $this->_db->Quote($current_time) . " WHERE session_id = " . $this->_db->Quote($session_id);
				$this->_db->setQuery($query);
				$_config->config_admin_autologout == 1 ? $this->_db->query() : null;

				// set garbage cleaning timeout
				$this->setSessionGarbageClean();

				// check against db record of session
				$query = "SELECT COUNT( session_id ) FROM #__session WHERE session_id = " . $this->_db->Quote($session_id) . " AND username = " . $this->_db->Quote($my->username) . " AND userid = " . intval($my->id);
				$this->_db->setQuery($query);
				$count = ($_config->config_admin_autologout == 1) ? $this->_db->loadResult() : 1;

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
			exit();
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
			if ($remember && strlen($username) == 32 && $userid) {

				// query used for remember me cookie
				$harden = mosHash(@$_SERVER['HTTP_USER_AGENT']);

				$query = "SELECT id, username, password, state, gid,groupname FROM #__users WHERE id = " . (int) $userid;
				$user = null;

				$this->_db->setQuery($query)->loadObject($user);

				list($hash, $salt) = explode(':', $user->password);

				$check_USER = md5($user->username . $harden);
				$check_password = md5($hash . $harden);

				if ($check_USER == $username && $check_password == $passwd) {
					$row = $user;
					$valid_remember = true;
				}
			} else {
				// query used for login via login module
				$query = "SELECT id,  username, password, state, gid,groupname FROM #__users WHERE username = " . $this->_db->Quote($username);
				$this->_db->setQuery($query)->loadObject($row);
			}

			if (is_object($row)) {

				// если акаунт заблокирован
				if ($row->state == 0) {
					mosRedirect($return, _LOGIN_BLOCKED);
					exit();
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
						exit();
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

				$query = "DELETE FROM #__session WHERE session_id != " . $this->_db->Quote($session->session_id) . " AND username = " . $this->_db->Quote($row->username) . " AND userid = " . (int) $row->id . " AND gid = " . (int) $row->gid . " AND guest = 0";
				$this->_db->setQuery($query)->query();

				// update user visit data
				$query = "UPDATE #__users SET lastvisitDate = " . $this->_db->Quote(_CURRENT_SERVER_TIME) . " WHERE id = " . (int) $session->userid;

				if (!$this->_db->setQuery($query)->query()) {
					die($this->_db->stderr(true));
				}

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
				exit();
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
	 * @return mosUser возвращает объект пользовательской сессии
	 */
	public function getUser() {
		$user = new mosUser($this->_db);

		if ($this->get('config')->config_no_session_front == 1) {
			// параметры id и gid при инициализации объявляются как null - это вредит некоторым компонентам, проинициализируем их в нули
			$user->id = 0;
			$user->gid = 0;
			return $user; // если сессии (авторизация) на фронте отключены - возвращаем пустой объект
		}

		$user->id = intval($this->_session->userid);
		$user->username = $this->_session->username;
		$user->groupname = $this->_session->groupname;
		$user->gid = intval($this->_session->gid);
		if ($user->id) {
			$query = "SELECT  u.id, u.username, u.email, u.state, u.gid, u.registerDate, u.lastvisitDate, ue.level
			FROM #__users  AS u
			LEFT JOIN #__users_extra AS ue ON ue.user_id=u.id
			WHERE u.id = " . $user->id;
			$this->_db->setQuery($query, 0, 1)->loadObject($my);

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

	/**
	 * @param string The name of the variable (from configuration.php)
	 * @return mixed The value of the configuration variable or null if not found
	 */
	public function getCfg($varname) {
		$varname = 'config_' . $varname;

		$config = $this->get('config');
		return (isset($config->$varname)) ? $config->$varname : null;
	}

	/**  функция определения шаблона, если в панели управления указано что использовать один шаблон - сразу возвращаем его название, функцию не проводим до конца */
	public function setTemplate() {
		// если у нас в настройках указан шаблон и определение идёт не для панели управления - возвращаем название шаблона из глобальной конфигурации
		if ($this->getCfg('one_template') != '...') {
			$this->_template = $this->getCfg('one_template');
			return;
		}

		$Itemid = intval(mosGetParam($_REQUEST, 'Itemid', null));
		$assigned = (!empty($Itemid) ? ' OR menuid = ' . (int) $Itemid : '');

		$query = "SELECT template FROM #__templates_menu WHERE client_id = 0 AND ( menuid = 0 $assigned ) ORDER BY menuid DESC";
		$this->_template = $this->_db->setQuery($query, 0, 1)->loadResult();
	}

	/**
	 * Получение текущего шаблона
	 * @return string название шаблона
	 */
	public function getTemplate() {
		return $this->_template;
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

	/**
	 * Determines the paths for including engine and menu files
	 * @param string The current option used in the url
	 * @param string The base path from which to load the configuration file
	 */
	private function _setAdminPaths($option, $basePath = '.') {
		$option = strtolower($option);

		$this->_path = new stdClass();

		// security check to disable use of `/`, `\\` and `:` in $options variable
		if (strpos($option, '/') !== false || strpos($option, '\\') !== false || strpos($option, ':') !== false) {
			mosErrorAlert(_ACCESS_DENIED);
			return;
		}

		$prefix = substr($option, 0, 4);
		if ($prefix != 'com_' && $prefix != 'mod_') {
			// ensure backward compatibility with existing links
			$name = $option;
			$option = 'com_' . $option;
		} else {
			$name = substr($option, 4);
		}

		// components
		if (file_exists("$basePath/templates/$this->_template/components/$name.html.php")) {
			$this->_path->front = "$basePath/components/$option/$name.php";
			$this->_path->front_html = "$basePath/templates/$this->_template/components/$name.html.php";
		} elseif (file_exists("$basePath/components/$option/$name.php")) {
			$this->_path->front = "$basePath/components/$option/$name.php";
			$this->_path->front_html = "$basePath/components/$option/$name.html.php";
		}

		$this->_path->config = "$basePath/components/$option/$name.config.php";

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
			$this->_path->admin = "$basePath/" . JADMIN_BASE . "/components/com_admin/admin.admin.php";
			$this->_path->admin_html = "$basePath/" . JADMIN_BASE . "/components/com_admin/admin.admin.html.php";
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
				case 'com_xml':
					$name = substr($option, 4);
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
					$path = JPATH_BASE . DS . JADMIN_BASE . "/components/com_menus/$option/$option.xml";
					$result = file_exists($path) ? $path : $result;
					break;
			}
		}
		if ($option) {
			$this->_path = $temp;
		}
		return $result;
	}

	/** Is admin interface?
	 * @return boolean
	 */
	public function isAdmin() {
		return $this->_isAdmin;
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

		if (!$this->_isAdmin && empty($_s)) {
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

	/**
	 *
	 * @return <type>
	 */
	private function get_option() {

		$Itemid = intval(strtolower(mosGetParam($_REQUEST, 'Itemid', '')));
		$option = trim(strval(strtolower(mosGetParam($_REQUEST, 'option', ''))));

		if ($option != '' && $Itemid != '') {
			return array('option' => $option, 'Itemid' => $Itemid);
		}

		if ($option != '') {
			return array('option' => $option, 'Itemid' => 99999999);
		}

		if ($Itemid) {
			$query = "SELECT id, link"
					. "\n FROM #__menu"
					. "\n WHERE menutype = 'mainmenu'"
					. "\n AND id = " . (int) $Itemid
					. "\n AND published = 1";
			$menu = new mosMenu($database);
			$this->_db->setQuery($query)->loadObject($menu);
		} else {
			// получение пурвого элемента главного меню
			$menu = mosMenu::get_all();
			$menu = $menu['mainmenu'];
			$items = isset($menu) ? array_values($menu) : array();
			$menu = $items[0];
		}

		$Itemid = $menu->id;
		$link = $menu->link;

		//unset($menu);
		if (($pos = strpos($link, '?')) !== false) {
			$link = substr($link, $pos + 1) . '&Itemid=' . $Itemid;
		}

		parse_str($link, $temp);
		/** это путь, требуется переделать для лучшего управления глобальными переменными */
		foreach ($temp as $k => $v) {
			$GLOBALS[$k] = $v;
			$_REQUEST[$k] = $v;
			if ($k == 'option') {
				$option = $v;
			}
			if ($k == 'Itemid') {
				$Itemid = $v;
			}
		}

		return array('option' => $option, 'Itemid' => $Itemid);
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

class mosMenu extends mosDBTable {

	/**
	 * Инстанция хранения всех пунктов меню
	 */
	private static $_all_menus_instance;
	public $id;
	public $menutype;
	public $name;
	public $link_title;
	public $link;
	public $type;
	public $published;
	public $componentid;
	public $parent;
	public $sublevel;
	public $ordering;
	public $checked_out;
	public $checked_out_time;
	public $pollid;
	public $browserNav;
	public $access;
	public $utaccess;
	public $params;

	/**
	 * @param database A database connector object
	 */
	function mosMenu() {
		$this->mosDBTable('#__menu', 'id');
		$this->_menu = array();
	}

	// получение инстанции меню
	public static function get_all($menutype = false) {

		if (self::$_all_menus_instance === NULL) {
			$database = database::getInstance();
			// ведёргиваем из базы все пункты меню, они еще пригодяться несколько раз
			$sql = 'SELECT id,menutype,name,link_title,link,type,parent,params,access,browserNav FROM #__menu WHERE published=1 ORDER BY parent, ordering ASC';
			$menus = $database->setQuery($sql)->loadObjectList();

			$all_menus = array();
			foreach ($menus as $menu) {
				$all_menus[$menu->menutype][$menu->id] = $menu;
			}
			self::$_all_menus_instance = $all_menus;
		}

		return $menutype ? self::$_all_menus_instance[$menutype] : self::$_all_menus_instance;
	}

	/**
	 *
	 * @return array
	 */
	function all_menu() {
		// ведёргиваем из базы все пункты меню, они еще пригодяться несколько раз
		$sql = 'SELECT* FROM #__menu WHERE published=1 ORDER BY parent, ordering ASC';
		$menus = $this->_db->setQuery($sql)->loadObjectList();

		$m = array();
		foreach ($menus as $menu) {
			$m[$menu->menutype][$menu->id] = $menu;
		}

		return $m;
	}

	/**
	 *
	 * @return boolean
	 */
	function check() {
		$this->filter(array('link'));
		return true;
	}

	function getMenu($id = null, $type = '', $link = '') {

		$where = '';
		$and = array();
		if ($id || $type || $link) {
			$where .= ' WHERE ';
		}
		if ($id) {
			$and[] = ' menu.id = ' . $id;
		}
		if ($type) {
			$and[] = " menu.type = '" . $type . "'";
		}
		if ($link) {
			$and[] = "menu.link LIKE '%$link'";
		}
		$and = implode(' AND ', $and);

		$query = 'SELECT menu.* FROM #__menu AS menu ' . $where . $and;
		$r = null;
		$this->_db->setQuery($query)->loadObject($r);
		return $r;
	}

	// возвращает всё содержимое всех меню
	function get_menu() {
		return $this->_menu;
	}

	public static function get_menu_links() {
		$_all = self::get_all();

		$return = array();
		foreach ($_all as $menus) {
			foreach ($menus as $menu) {
				// TODO тут еще можно будет сделать красивые sef-ссылки на пункты меню
				//$return[$menu->link]=array('id'=>$menu->id,'name'=>$menu->name);
				$return[$menu->link] = array('id' => $menu->id, 'type' => $menu->type);
			}
		}
		unset($menu, $menuss);
		return $return;
	}

}

class mosModule extends mosDBTable {

	private static $_instance;
	public $id;
	public $title;
	public $showtitle;
	public $content;
	public $ordering;
	public $position;
	public $checked_out;
	public $checked_out_time;
	public $published;
	public $module;
	public $numnews;
	public $access;
	public $params;
	public $iscore;
	public $client_id;
	public $template;
	public $helper;
	public $cache_time;
	private $_all_modules = null;
	private $_view = null;
	private $_mainframe = null;

	public function mosModule($db, $mainframe = null) {
		$this->mosDBTable('#__modules', 'id', $db);
		if ($mainframe) {
			$this->_mainframe = $mainframe;
		}
	}

	public static function getInstance() {

		JDEBUG ? jd_inc('mosModule') : null;

		if (self::$_instance === null) {
			$mainframe = mosMainFrame::getInstance();

			$modules = new mosModule($mainframe->getDBO(), $mainframe);
			$modules->initModules();
			self::$_instance = $modules;
		}

		return self::$_instance;
	}

	public function check() {
		if (trim($this->title) == '') {
			$this->_error = _PLEASE_ENTER_MODULE_NAME;
			return false;
		}

		return true;
	}

	public static function convert_to_object($module, $mainframe) {
		$database = $mainframe->getDBO();

		$module_obj = new mosModule($database, $mainframe);
		$rows = get_object_vars($module_obj);
		foreach ($rows as $key => $value) {
			if (isset($module->$key)) {
				$module_obj->$key = $module->$key;
			}
		}
		unset($module_obj->_mainframe, $module_obj->_db);

		return $module_obj;
	}

	function set_template($params) {

		if ($params->get('template', '') == '') {
			return false;
		}

		$default_template = 'modules' . DS . $this->module . DS . 'view' . DS . 'default.php';

		if ($params->get('template_dir', 0) == 0) {
			$template_dir = 'modules' . DS . $this->module . DS . 'view';
		} else {
			$template_dir = 'templates' . DS . JTEMPLATE . DS . 'html' . DS . 'modules' . DS . $this->module;
		}

		if ($params->get('template')) {
			$file = JPATH_BASE . DS . $template_dir . DS . $params->get('template');
			if (is_file($file)) {
				$this->template = $file;
				return true;
			} elseif (is_file(JPATH_BASE . DS . $default_template)) {
				$this->template = JPATH_BASE . DS . $default_template;
				return true;
			}
		}

		return false;
	}

	function set_template_custom($template) {

		$template_file = JPATH_BASE . DS . 'templates' . DS . JTEMPLATE . DS . 'html' . DS . 'user_modules' . DS . $template;

		if (is_file($template_file)) {
			$this->template = $template_file;
			return true;
		}
		return false;
	}

	function get_helper($mainframe) {

		$file = JPATH_BASE . DS . 'modules' . DS . $this->module . DS . 'helper.php';

		if (is_file($file)) {
			require_once($file);
			$helper_class = $this->module . '_Helper';
			$this->helper = new $helper_class($mainframe);
			return true;
		}
		return false;
	}

	function load_module($name = '', $title = '') {
		$where = " m.module = '" . $name . "'";
		if (!$name || $title) {
			$where = " m.title = '" . $title . "'";
		}

		$query = 'SELECT * FROM #__modules AS m WHERE ' . $where . ' AND published=1';
		$this->_view->_mainframe->getDBO()->setQuery($query)->loadObject($this);
	}

	public function initModules() {
		global $my, $Itemid;
echo 555;
		$this->_all_modules = self::_initModules($Itemid, $my->gid);
		require (JPATH_BASE . '/includes/frontend.php');
		$this->_view = new modules_html($this->_mainframe);
	}

	public static function _initModules($Itemid, $my_gid) {
		$mainframe = mosMainFrame::getInstance();

		$all_modules = array();

		$Itemid = intval($Itemid);
		$check_Itemid = ($Itemid) ? "OR mm.menuid = " . $Itemid : '';

		$query = "SELECT id, title, module, position,showtitle,params,access,cache_time FROM #__modules AS m"
				. "\n INNER JOIN #__modules_menu AS mm ON mm.moduleid = m.id"
				. "\n WHERE m.published = 1"
				. "\n AND m.client_id != 1 AND ( mm.menuid = 0 $check_Itemid )"
				. "\n ORDER BY ordering";

		$modules = $mainframe->getDBO()->setQuery($query)->loadObjectList();

		foreach ($modules as $module) {
			if ($module->access == 3) {
				$my_gid == 0 ? $all_modules[$module->position][] = $module : null;
			} else {
				$all_modules[$module->position][] = $module;
			}
		}


		return $all_modules;
	}

	function mosCountModules($position = 'left') {
		if (intval(mosGetParam($_GET, 'tp', 0))) {
			return 1;
		}
		$allModules = $this->_all_modules;
		return (isset($allModules[$position])) ? count($allModules[$position]) : 0;
	}

	function mosLoadModules($position = 'left') {
		global $my, $Itemid;

		$tp = intval(mosGetParam($_GET, 'tp', 0));

		if ($tp && !$this->_view->_mainframe->config->config_disable_tpreview) {
			echo '<div style="height:50px;background-color:#eee;margin:2px;padding:10px;border:1px solid #f00;color:#700;">' . $position . '</div>';
			return;
		}

		$config_caching = $this->_view->_mainframe->config->config_caching;
		$allModules = $this->_all_modules;

		$modules = (isset($allModules[$position])) ? $modules = $allModules[$position] : array();

		foreach ($modules as $module) {
			if ((int) $module->cache_time > 0 && $config_caching == 1) {
				// кешируем модуль
				$cache = mosCache::getCache($module->module . '_' . $module->id, 'function', null, $module->cache_time, $this->_view);
				$cache->call('module', $module, $params, $Itemid, $my->gid);
			} else {
				// не кешируем модуль
				$this->_view->module($module, $params, $Itemid);
			}
		}
		return;
	}

	function mosLoadModule($name = '', $title = '', $style = 0, $noindex = 0, $inc_params = null) {
		global $my, $Itemid;

		$config = $this->_view->_mainframe->get('config');

		$tp = intval(mosGetParam($_GET, 'tp', 0));

		if ($tp && !$config->config_disable_tpreview) {
			echo '<div style="height:50px;background-color:#eee;margin:2px;padding:10px;border:1px solid #f00;color:#700;">' . $name . '</div>';
			return;
		}

		$this->load_module($name, $title);

		if (!$this->id) {
			echo JDEBUG ? '<!-- mosLoadModule::' . $name . ' - не найден -->' : '';
			return;
		}

		if ((int) $this->cache_time > 0 && $config_caching == 1) {
			// кешируем модуль
			$cache = mosCache::getCache($this->module . '_' . $this->id, 'function', null, $this->cache_time, $this->_view);
			$cache->call('module', $this, $params, $Itemid, $my->gid);
		} else {
			// не кешируем модуль
			$this->_view->module($this, $params, $Itemid);
		}

		return;
	}

}

/**
 * Class to support function caching
 * @package Joostina
 */
class mosCache {

	private static $_instance;

	/**
	 * @return object A function cache object
	 */
	public static function getCache($group = 'default', $handler = 'callback', $storage = null, $cachetime = null, $object = null) {

		JDEBUG ? jd_inc('cache') : null;

		if (self::$_instance === null) {
			$config = Jconfig::getInstance();

			self::$_instance = array();
			self::$_instance['config_caching'] = $config->config_caching;
			self::$_instance['config_cachetime'] = $config->config_cachetime;
			self::$_instance['config_cache_handler'] = $config->config_cache_handler;
			self::$_instance['config_cachepath'] = $config->config_cachepath;
			self::$_instance['config_lang'] = $config->config_lang;
			// подключаем библиотеку кэширования
			mosMainFrame::addLib('cache');
		}

		$handler = ($handler == 'function') ? 'callback' : $handler;

		$def_cachetime = (isset($cachetime)) ? $cachetime : self::$_instance['config_cachetime'];

		if (!isset($storage)) {
			$storage = (self::$_instance['config_cache_handler'] != '') ? self::$_instance['config_cache_handler'] : 'file';
		}

		$options = array(
			'defaultgroup' => $group,
			'cachebase' => self::$_instance['config_cachepath'] . DS,
			'lifetime' => $def_cachetime,
			'language' => self::$_instance['config_lang'],
			'storage' => $storage
		);

		$cache = JCache::getInstance($handler, $options, $object);

		if ($cache != NULL) {
			$cache->setCaching(self::$_instance['config_caching']);
		}
		return $cache;
	}

	public static function cleanCache($group = false) {
		$cache = mosCache::getCache($group);
		if ($cache != NULL) {
			$cache->clean($group);
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
 * Utility function to read the files in a directory
 * @param string The file system path
 * @param string A filter for the names
 * @param boolean Recurse search into sub-directories
 * @param boolean True if to prepend the full path to the file name
 */
function mosReadDirectory($path, $filter = '.', $recurse = false, $fullpath = false) {
	$arr = array();
	if (!@is_dir($path)) {
		return $arr;
	}
	$handle = opendir($path);

	while ($file = readdir($handle)) {
		$dir = mosPathName($path . '/' . $file, false);
		$isDir = is_dir($dir);
		if (($file != ".") && ($file != "..")) {
			if (preg_match("/$filter/", $file)) {
				if ($fullpath) {
					$arr[] = trim(mosPathName($path . '/' . $file, false));
				} else {
					$arr[] = trim($file);
				}
			}
			if ($recurse && $isDir) {
				$arr2 = mosReadDirectory($dir, $filter, $recurse, $fullpath);
				$arr = array_merge($arr, $arr2);
			}
		}
	}
	closedir($handle);
	asort($arr);
	return $arr;
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
			echo "<meta http-equiv=\"Content-Type\" content=\"text/html; " . _ISO . "\" />";
			echo "<script>alert('$text'); $action</script> \n";
			break;
	}

	exit;
}

/**
 *
 * @param <type> $id
 * @param <type> $indent
 * @param <type> $list
 * @param <type> $children
 * @param <type> $maxlevel
 * @param <type> $level
 * @param <type> $type
 * @return <type>
 */
function mosTreeRecurse($id, $indent, $list, &$children, $maxlevel = 9999, $level = 0, $type = 1) {

	if (@$children[$id] && $level <= $maxlevel) {
		foreach ($children[$id] as $v) {
			$id = $v->id;

			if ($type) {
				$pre = '<sup>L</sup>&nbsp;';
				$spacer = '.&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';
			} else {
				$pre = '- ';
				$spacer = '&nbsp;&nbsp;';
			}

			if ($v->parent == 0) {
				$txt = $v->name;
			} else {
				$txt = $pre . $v->name;
			}

			$list[$id] = $v;
			$list[$id]->treename = $indent . $txt;
			$list[$id]->children = count(@$children[$id]);

			$list = mosTreeRecurse($id, $indent . $spacer, $list, $children, $maxlevel, $level + 1, $type);
		}
	}
	return $list;
}

/**
 * Function to strip additional / or \ in a path name
 * @param string The path
 * @param boolean Add trailing slash
 */
function mosPathName($p_path, $p_addtrailingslash = true) {
	$retval = '';

	$isWin = (substr(PHP_OS, 0, 3) == 'WIN');

	if ($isWin) {
		$retval = str_replace('/', '\\', $p_path);
		if ($p_addtrailingslash) {
			if (substr($retval, -1) != '\\') {
				$retval .= '\\';
			}
		}

		// Check if UNC path
		$unc = substr($retval, 0, 2) == '\\\\' ? 1 : 0;

		// Remove double \\
		$retval = str_replace('\\\\', '\\', $retval);

		// If UNC path, we have to add one \ in front or everything breaks!
		if ($unc == 1) {
			$retval = '\\' . $retval;
		}
	} else {
		$retval = str_replace('\\', '/', $p_path);
		if ($p_addtrailingslash) {
			if (substr($retval, -1) != '/') {
				$retval .= '/';
			}
		}

		// Check if UNC path
		$unc = substr($retval, 0, 2) == '//' ? 1 : 0;

		// Remove double //
		$retval = str_replace('//', '/', $retval);

		// If UNC path, we have to add one / in front or everything breaks!
		if ($unc == 1) {
			$retval = '/' . $retval;
		}
	}

	return $retval;
}

function mosObjectToArray($p_obj) {
	$retarray = null;
	if (is_object($p_obj)) {
		$retarray = array();
		foreach (get_object_vars($p_obj) as $k => $v) {
			if (substr($k, 0, 1) != '_') {
				if (is_object($v))
					$retarray[$k] = mosObjectToArray($v);
				else
					$retarray[$k] = $v;
			}
		}
	}
	return $retarray;
}

function mosMakeHtmlSafe(&$mixed, $quote_style = ENT_QUOTES, $exclude_keys = '') {
	if (is_object($mixed)) {
		foreach (get_object_vars($mixed) as $k => $v) {
			if (is_array($v) || is_object($v) || $v == null || substr($k, 1, 1) == '_') {
				continue;
			}
			if (is_string($exclude_keys) && $k == $exclude_keys) {
				continue;
			} else
			if (is_array($exclude_keys) && in_array($k, $exclude_keys)) {
				continue;
			}
			$mixed->$k = htmlspecialchars($v, $quote_style, 'UTF-8');
		}
	}
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

function initGzip() {
	global $do_gzip_compress;

	$do_gzip_compress = false;

	if (Jconfig::getInstance()->config_gzip == 1) {
		$phpver = phpversion();
		$useragent = mosGetParam($_SERVER, 'HTTP_USER_AGENT', '');
		$canZip = mosGetParam($_SERVER, 'HTTP_ACCEPT_ENCODING', '');
		$gzip_check = 0;
		$zlib_check = 0;
		$gz_check = 0;
		$zlibO_check = 0;
		$sid_check = 0;
		if (strpos($canZip, 'gzip') !== false) {
			$gzip_check = 1;
		}
		if (extension_loaded('zlib')) {
			$zlib_check = 1;
		}
		if (function_exists('ob_gzhandler')) {
			$gz_check = 1;
		}
		if (ini_get('zlib.output_compression')) {
			$zlibO_check = 1;
		}
		if (ini_get('session.use_trans_sid')) {
			$sid_check = 1;
		}
		if ($phpver >= '4.0.4pl1' && (strpos($useragent, 'compatible') !== false || strpos($useragent, 'Gecko') !== false)) {
			if (($gzip_check || isset($_SERVER['---------------'])) && $zlib_check && $gz_check && !$zlibO_check && !$sid_check) {
				ob_start('ob_gzhandler');
				return;
			}
		} elseif ($phpver > '4.0') {
			if ($gzip_check) {
				if ($zlib_check) {
					$do_gzip_compress = true;
					ob_start();
					ob_implicit_flush(0);
					header('Content-Encoding: gzip');
					return;
				}
			}
		}
	}
	ob_start();
}

function doGzip() {
	global $do_gzip_compress;

	if ($do_gzip_compress) {
		$gzip_contents = ob_get_contents();
		ob_end_clean();
		$gzip_size = strlen($gzip_contents);
		$gzip_crc = crc32($gzip_contents);
		$gzip_contents = gzcompress($gzip_contents, 9);
		$gzip_contents = substr($gzip_contents, 0, strlen($gzip_contents) - 4);
		echo "\x1f\x8b\x08\x00\x00\x00\x00\x00";
		echo $gzip_contents;
		echo pack('V', $gzip_crc);
		echo pack('V', $gzip_size);
	} else {
		ob_end_flush();
	}
}

class JHTML {

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

/*
 * Includes pathway file
 */

function mosPathWay() {
	require_once (JPATH_BASE . '/includes/pathway.php');
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
require_once(JPATH_BASE . '/components/com_users/users.class.php');

/**
 * Проверка на наличие ключа массива и возвращение значения этого ключа или заранее определённого значения
 * @param array $array - исходный массив
 * @param string $name - название проверяемого ключа массива
 * @param string $def - значение по умолчанию
 */
function jisset(array $array, $name, $def) {
	return isset($array[$name]) ? $array[$name] : $def;
}

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
	);
	public static $config = array(
		'favicon' => true,
		'seotag' => true,
	);

	// время кэширования страницы браузером, в секундах
	public static $cache_header_time = false;


	public static function getInstance() {
		if (self::$instance === NULL) {
			self::$instance = new self;
		}
		return self::$instance;
	}

	public static function getData($name) {
		return isset(self::$data[$name]) ? self::$data[$name] : false;
	}

	public function setPageTitle($title = false, $pagetitle = false) {

		// title страницы
		self::$data['title'] = $title ? JConfig::getInstance()->config_sitename . self::$title_separator . $title : $sitename;

		// название страницы, не title!
		self::$data['pagetitle'] = $pagetitle ? $pagetitle : $title;

		return $this;
	}

	public function addMetaTag($name, $content) {
		$name = Jstring::trim(htmlspecialchars($name));
		$content = Jstring::trim(htmlspecialchars($content));
		self::$data['meta'][] = array($name, $content);

		return $this;
	}

	public function appendMetaTag($name, $content) {
		$n = count(self::$data['meta']);
		for ($i = 0; $i < $n; $i++) {
			if (self::$data['meta'][$i][0] == $name) {
				$content = Jstring::trim(htmlspecialchars($content));
				if ($content != '' & self::$data['meta'][$i][1] == "") {
					self::$data['meta'][$i][1] .= ' ' . $content;
				};
				return;
			}
		}

		$this->addMetaTag($name, $content);
	}

	function prependMetaTag($name, $content) {
		$name = trim(htmlspecialchars($name));
		$n = count(self::$data['meta']);
		for ($i = 0; $i < $n; $i++) {
			if (self::$data['meta'][$i][0] == $name) {
				$content = trim(htmlspecialchars($content));
				self::$data['meta'][$i][1] = $content . self::$data['meta'][$i][1];
				return;
			}
		}
		self::getInstance()->addMetaTag($name, $content);
	}

	function set_robot_metatag($robots) {
		($robots == 0) ? self::getInstance()->addMetaTag('robots', 'index, follow') : null;
		($robots == 1) ? self::getInstance()->addMetaTag('robots', 'index, nofollow') : null;
		($robots == 2) ? self::getInstance()->addMetaTag('robots', 'noindex, follow') : null;
		($robots == 3) ? self::getInstance()->addMetaTag('robots', 'noindex, nofollow') : null;
	}

	function addCustomHeadTag($html ) {
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

	public function addJS($path, $params = array('first'=>false)) {

		if($params['first']==true ){
			self::$data['js'] = array($path) + self::$data['js'];
		}else{
			self::$data['js'][] = $path;
		}

		return $this;
	}

	public function addCSS($path, $params = array('media' => 'all')) {
		self::$data['css'][] = array($path, $params);

		return $this;
	}

	/**
	 * @return string
	 */
	function getCustomPathWay() {
		return $this->_custom_pathway;
	}

	/**
	 *
	 * @param <type> $html
	 */
	function appendPathWay($html) {
		$this->_custom_pathway[] = $html;
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

		$description ? null : Jdocument::getInstance()->appendMetaTag('description', mosMainFrame::getInstance()->getCfg('MetaDesc'));
		$keywords ? null : Jdocument::getInstance()->appendMetaTag('keywords', mosMainFrame::getInstance()->getCfg('MetaKeys'));

		if (Jdocument::$config['seotag'] == true) {

			Jdocument::getInstance()
					->addMetaTag('distribution', 'global')
					->addMetaTag('rating', 'General')
					->addMetaTag('document-state', 'Dynamic')
					->addMetaTag('documentType', 'WebDocument')
					->addMetaTag('audience', 'all')
					->addMetaTag('revisit', mosMainFrame::getInstance()->getCfg('mtage_revisit') . ' days')
					->addMetaTag('revisit-after', mosMainFrame::getInstance()->getCfg('mtage_revisit') . ' days')
					->addMetaTag('allow-search', 'yes')
					->addMetaTag('language', mosMainFrame::getInstance()->getCfg('lang'));
		}

		echo Jdocument::getInstance()->getHead();


		// favourites icon
		if (Jdocument::$config['favicon'] == true) {
			$icon = JPATH_SITE . '/media/favicon.ico';
			echo "\t" . '<link rel="shortcut icon" href="' . $icon . '" />' . "\n\t";
		}
	}

	public static function body() {
		echo self::$data['page_body'];
	}

	public static function header() {
		if (!headers_sent()) {
			if ( self::$cache_header_time ) {
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

}