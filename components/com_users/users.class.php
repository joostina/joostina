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

class User extends mosDBTable {

	public $id;
	public $username;
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
	// инстанция для хранения расширенной информации о пользователе
	private static $extra_instance;

	function __construct() {
		$this->mosDBTable('#__users', 'id');
	}

	public function get_fieldinfo() {
		return array(
			'id' => array(
				'name' => 'ID',
				'editable' => false,
				'sortable' => false,
				'in_admintable' => true,
				'html_table_element' => 'value',
				'html_table_element_param' => array(
					'width' => '20px',
					'align' => 'center'
				)
			),
			'username' => array(
				'name' => 'Логин',
				'editable' => true,
				'sortable' => true,
				'in_admintable' => true,
				'html_edit_element' => 'edit',
				'html_table_element' => 'editlink',
			),
			'state' => array(
				'name' => 'Разрешен',
				'editable' => true,
				'sortable' => true,
				'in_admintable' => true,
				'editlink' => true,
				'html_edit_element' => 'checkbox',
				'html_table_element' => 'state_box',
				'html_edit_element_param' => array(
					'text' => 'Разрешён / Активирован',
				),
				'html_table_element' => 'statuschanger',
				'html_table_element_param' => array(
					'statuses' => array(
						0 => 'Разрешён',
						1 => 'Заблокирован',
					),
					'images' => array(
						0 => 'publish_g.png',
						1 => 'publish_x.png',
					),
					'align' => 'center',
					'class' => 'td-state-joiadmin',
				)
			),
			'email' => array(
				'name' => 'email адрес',
				'editable' => true,
				'in_admintable' => true,
				'html_edit_element' => 'edit',
				'html_table_element' => 'value',
			),
			'openid' => array(
				'name' => 'Адрес OpenID',
				'editable' => true,
				'in_admintable' => true,
				'html_edit_element' => 'edit',
				'html_table_element' => 'value',
			),
			'password' => array(
				'name' => 'Пароль',
				'editable' => true,
				'in_admintable' => true,
				'html_edit_element' => 'edit',
				'html_table_element' => 'value',
			),
			'gid' => array(
				'name' => 'Группа2',
				'editable' => true,
				'sortable' => true,
				'in_admintable' => true,
				'html_edit_element' => 'option',
				'html_edit_element_param' => array(
					'call_from' => 'mosUser::get_usergroup'
				),
				'html_table_element' => 'one_from_array',
				'html_table_element_param' => array(
					'call_from' => 'mosUser::get_usergroup'
				),
			),
			'registerDate' => array(
				'name' => 'Дата регистрации',
				'editable' => true,
				'in_admintable' => true,
				'html_edit_element' => 'edit',
				'html_table_element' => 'value',
			),
			'lastvisitDate' => array(
				'name' => 'Последнее посещение',
				'editable' => true,
				'sortable' => true,
				'in_admintable' => true,
				'html_edit_element' => 'edit',
				'html_table_element' => 'value',
			),
			'activation' => array(
				'name' => 'Код активации',
				'editable' => true,
				'in_admintable' => true,
				'html_edit_element' => 'edit',
				'html_table_element' => 'value',
			),
		);
	}

	public function get_tableinfo() {
		return array(
			'header_list' => 'Пользователи',
			'header_new' => 'Создание пользователя',
			'header_edit' => 'Редактирование данных пользователя'
		);
	}

	public static function get_usergroup($gid = false) {
		$games = new Usergroup;
		$groups = $games->get_selector(array('key' => 'id', 'value' => 'title'), array('select' => 'id, title'));
		return $gid ? $groups[$gid] : $groups;
	}

	// добавление в таблицу расширенной информации и пользователях новой записи - для только что зарегистрированного пользователя
	public function after_insert() {
		$extra = new UserExtra;
		$extra->user_id = $this->id;
		$extra->store();
	}

	public function check($validator = null) {

		$this->filter();

		if ($validator && !$validator->ValidateForm()) {
			$this->_error = '<strong>Ошибки при заполнении формы:</strong><ul>';

			$error_hash = $validator->GetErrors();
			foreach ($error_hash as $inpname => $inp_err) {
				$this->_error .= '<li>' . $inp_err . '</li>';
			}
			$this->_error .= '</ul>';
			return false;
		}

		$query = "SELECT id FROM #__users WHERE username = " . $this->_db->Quote($this->username) . " AND id != " . (int) $this->id;
		$xid = $this->_db->setQuery($query)->loadResult();
		if ($xid && $xid != $this->id) {
			$this->_error = addslashes(_REGWARN_INUSE);
			return false;
		}

		$query = "SELECT id FROM #__users WHERE email = " . $this->_db->Quote($this->email) . " AND id != " . (int) $this->id;
		$xid = $this->_db->setQuery($query)->loadResult();
		if ($xid && $xid != $this->id) {
			$this->_error = addslashes(_REGWARN_EMAIL_INUSE);
			return false;
		}

//		if( !$this->id && $this->password != mosgetParam( $_POST, 'password2' ) ) {
//			$this->_error = 'А пароли то - разные!';
//			return false;
//		}

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
			$salt = self::mosMakePassword(16);
			$crypt = md5($this->password . $salt);
			$this->password = $crypt . ':' . $salt;
			$this->registerDate = _CURRENT_SERVER_TIME;
		}
		$this->groupname = self::get_usergroup($this->gid);
	}

	function check_password($input_password) {

		list($hash, $salt) = explode(':', $this->password);
		$check_password = md5($input_password . $salt);

		if ($hash == $check_password) {
			return true;
		} else {
			$this->_error = 'Введите правильный текущий пароль от аккаунта';
			return false;
		}
	}

	function get_link($user = false) {
		$user = $user ? $user : $this;

		$url = 'index.php?option=users&task=user&id=' . sprintf('%s:%s', $user->id, $user->username);
		return sefRelToAbs($url);
	}

	function profile_link($user) {
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

	function get_birthdate($user, $params = null) {
		mosMainFrame::addLib('text');
		mosMainFrame::addLib('datetime');

		if ($params->get('show_birthdate') == 1) {
			return mosFormatDate($user->user_extra->birthdate, '%d-%m-%Y', 0);
		} else {
			$delta = DateAndTime::getDelta(DateAndTime::mysql_to_unix($user->user_extra->birthdate), DateAndTime::mysql_to_unix(_CURRENT_SERVER_TIME));
			$age = $delta['year'];
			return $age . ' ' . Text::_declension($age, array(_YEAR, _YEAR_, _YEARS));
		}
	}

	/**
	 * Получение объекта текущего пользователя
	 * @global <type> $my
	 * @return User
	 */
	public static function current() {
		global $my;
		return $my;
	}

	public function avatar($size='', $id = false) {
		mosMainFrame::addLib('files');
		$file = File::makefilename(($id !== false) ? $id : $this->id);
		$base_file = JPATH_BASE . DS . 'attachments' . DS . 'avatars' . DS . $file . DS . 'avatar' . $size . '.png';
		return is_file($base_file) ? JPATH_SITE . '/attachments/avatars/' . $file . '/avatar' . $size . '.png' : JPATH_SITE . '/media/images/noavatar/avatar' . $size . '.png';
	}

	public static function avatar_check($id) {
		mosMainFrame::addLib('files');
		$file = File::makefilename($id);
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
	 * @return UserExtra
	 */
	public function extra($uid = false) {

		if (self::$extra_instance === NULL) {
			$extra = new UserExtra;
			$extra->load($uid ? $uid : $this->id);

			// кэш закладок декодируем из JSON
			$extra->bookmarks_cache = json_decode($extra->bookmarks_cache, true);

			// кэш  выставленного пользователм рейтинга из JSON
			$extra->votes_cache = json_decode($extra->votes_cache, true);

			self::$extra_instance = $extra;
		}

		return self::$extra_instance;
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

}

/* расширенная информация о пользователе */

class UserExtra extends mosDBTable {

	public $user_id;
	public $realname;
	public $gender;
	public $about;
	public $location;
	public $site;
	public $icq;
	public $skype;
	public $jabber;
	public $twitter;
	public $birthdate;
	// JSON сериализованный кеш закладок
	public $bookmarks_cache;
	// JSON сериализованный кеш результатов голосов-рейтинга пользователя
	public $votes_cache;
	// JSON сериализованный массив результатов рейтингования игр
	public $game_votes_cache;
	//"Уровень" пользователя
	public $level;

	function __construct() {
		$this->mosDBTable('#__users_extra', 'user_id');
	}

	function insert($id) {
		$this->user_id = $id;
		return $this->_db->insertObject('#__users_extra', $this, 'user_id');
	}

	/**
	 * Обновление кеша закладок пользователя
	 * User $user - объект пользователя
	 */
	public static function update_bookmarks_cache(User $user) {

		$current_cache = database::getInstance()->setQuery('SELECT * FROM #__bookmarks WHERE user_id=' . (int) $user->id)->loadObjectList();

		$new_cache = array();
		foreach ($current_cache as $cache) {
			$new_cache[$cache->obj_option][$cache->obj_task][$cache->obj_id] = $cache->created_at;
		}

		$userextra = new self;
		$userextra->user_id = $user->id;
		$userextra->bookmarks_cache = json_encode($new_cache);
		return (bool) $userextra->store();
	}

	/**
	 * Обновление кеша голосований пользователя
	 * User $user - объект пользователя
	 */
	public static function update_votes_cache(User $user, $vote_type = '') {

		$new_cache = array();

		// тут должно быть с $vote_type, но нету...
		$current_cache = database::getInstance()->setQuery("SELECT * FROM #__votes_topic WHERE user_id=" . (int) $user->id)->loadObjectList();
		foreach ($current_cache as $cache) {
			$new_cache['topic'][$cache->obj_id] = $cache->vote;
		}

		$current_cache = database::getInstance()->setQuery("SELECT * FROM #__votes_comment WHERE user_id=" . (int) $user->id)->loadObjectList();
		foreach ($current_cache as $cache) {
			$new_cache['comment'][$cache->obj_id] = $cache->vote;
		}

		$current_cache = database::getInstance()->setQuery("SELECT * FROM #__votes_users WHERE user_id=" . (int) $user->id)->loadObjectList();
		foreach ($current_cache as $cache) {
			$new_cache['users'][$cache->obj_id] = $cache->vote;
		}

		$current_cache = database::getInstance()->setQuery("SELECT * FROM #__votes_game WHERE user_id=" . (int) $user->id)->loadObjectList();
		foreach ($current_cache as $cache) {
			$new_cache['game'][$cache->obj_id] = $cache->vote;
		}

		$userextra = new self;
		$userextra->user_id = $user->id;
		$userextra->votes_cache = json_encode($new_cache);
		return (bool) $userextra->store();
	}

}

class mosSession extends mosDBTable {

	public $session_id = null;
	public $time = null;
	public $userid = null;
	public $groupname = null;
	public $username = null;
	public $gid = null;
	public $guest = null;
	public $_session_cookie = null;

	function mosSession() {
		$this->mosDBTable('#__session', 'session_id');
	}

	function get($key, $default = null) {
		return mosGetParam($_SESSION, $key, $default);
	}

	function set($key, $value) {
		$_SESSION[$key] = $value;
		return $value;
	}

	function setFromRequest($key, $varName, $default = null) {
		if (isset($_REQUEST[$varName])) {
			return mosSession::set($key, $_REQUEST[$varName]);
		} elseif (isset($_SESSION[$key])) {
			return $_SESSION[$key];
		} else {
			return mosSession::set($key, $default);
		}
	}

	function insert() {
		$ret = $this->_db->insertObject($this->_tbl, $this);
		if (!$ret) {
			$this->_error = strtolower(get_class($this)) . "::store failed <br />" . $this->_db->stderr();
			return false;
		} else {
			return true;
		}
	}

	function update($updateNulls = false) {
		$ret = $this->_db->updateObject($this->_tbl, $this, 'session_id', $updateNulls);
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
			$new_session_id = mosMainFrame::sessionCookieValue($randnum);
			if ($randnum != '') {
				$query = "SELECT $this->_tbl_key FROM $this->_tbl WHERE $this->_tbl_key = " . $this->_db->Quote($new_session_id);
				$this->_db->setQuery($query);
				if (!$result = $this->_db->query()) {
					die($this->_db->stderr(true));
				}
				if ($this->_db->getNumRows($result) == 0) {
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

	function purge($inc = 1800, $and = '', $lifetime='') {

		if ($inc == 'core') {
			$past_logged = time() - $lifetime;
			$query = "DELETE FROM $this->_tbl WHERE time < '" . (int) $past_logged . "'";
		} else {
			// kept for backward compatability
			$past = time() - $inc;
			$query = "DELETE FROM $this->_tbl WHERE ( time < '" . (int) $past . "' )" . $and;
		}
		return $this->_db->setQuery($query)->query();
	}

}

class Usergroup extends mosDBTable {

	public $id;
	public $parent_id;
	public $title;

	function __construct() {
		$this->mosDBTable('#__users_groups', 'id');
	}

}

class mosUser extends User {

}

/**
 * Сумарныйе рейтинги пользователя
 * Каждый рейтинг хранится в своём поле, суммарный рейтинг рассчитывается после каждого изменения рейтинга
 * Объединять функции не надо, под обновление каждого типа рейтинга могут быть в дальнейшем добавлены свои типы событий
 */
class UserRatings extends mosDBTable {

	public $user_id;
	public $user_rate;
	public $games_rate;
	public $topics_rate;
	public $comments_rate;
	public $activity_rate;
	public $full_rate;

	function __construct() {
		$this->mosDBTable('#__users_ratings', 'user_id');
	}

	function __destruct() {
		self::update_full_rate($user);
	}

	//уровень  -  очки
	public static function levelup_matrix() {
		return
		array(
			0 => 0,
			1 => 20,
			2 => 40,
			3 => 80,
			4 => 160,
			5 => 320,
			6 => 640,
			7 => 1280,
			8 => 2560
		);
	}

	private static function update_full_rate(User $user) {
		$sql = sprintf("INSERT INTO `#__users_ratings` (`user_id`) VALUES (%s)
			       ON DUPLICATE KEY UPDATE full_rate = user_rate + games_rate + topics_rate + comments_rate + activity_rate + awards_rate;",
						(int) $user->id);
		database::getInstance()->setQuery($sql)->query();


		//---------------------------переводим пользователя на новый уровень (при необходимости)
		//Узнаём текущий рейтинг пользователя и его уровень
		$cur_user = null;
		database::getInstance()->setQuery("
						SELECT rate.full_rate, ue.level
						FROM #__users_ratings AS rate
						LEFT JOIN #__users_extra AS ue ON ue.user_id = " . (int) $user->id . "
						WHERE rate.user_id = " . (int) $user->id
		)->loadObject($cur_user);

		$levelup_matrix = self::levelup_matrix();

		//определяем границы уровня
		$min = $levelup_matrix[$cur_user->level];
		$max = $levelup_matrix[$cur_user->level + 1];


		//если рейтинг пользователя перевалил за верхнюю границу
		if ($cur_user->full_rate >= $max) {
			$level_up = 1; //поднимаем уровень	
		}
		//иначе, если рейтинг пользователя стал меньше требуемого для текущего уровня
		elseif ($cur_user->full_rate < $min && $cur_user->level > 0) {
			$level_up = -1; //понижаем уровень	
		} else {
			$level_up = 0;
		}

		if ($level_up != 0) {
			database::getInstance()->setQuery("UPDATE #__users_extra SET `level` = $cur_user->level + $level_up WHERE user_id=" . (int) $user->id)->query();

			//при переходе на 5-ый уровень даём 50 очков
			if ($level_up == 1 && ($cur_user->level + $level_up) == 5) {
				self::add_activity($user, 50);
			}
			//если пользователь слетел с 5-го уровня - отбираем 50 очков
			else if ($level_up == -1 && $cur_user->level == 5) {
				self::add_activity($user, -50);
			}
		}
	}

	public static function get_full_rate($user_id) {
		$query = "SELECT full_rate FROM #__users_ratings WHERE user_id = " . (int) $user_id;
		return database::getInstance()->setQuery($query)->loadResult();
	}

	// рейтинг самого пользователя выставленный другими пользователями - респекты
	public static function add_user(User $user, $sum) {
		$sql = sprintf("INSERT INTO `#__users_ratings` (`user_id`, `user_rate`) VALUES (%s, '%s')
			       ON DUPLICATE KEY UPDATE user_rate=user_rate+%s;",
						(int) $user->id, $sum, $sum);
		database::getInstance()->setQuery($sql)->query();
		self::update_full_rate($user);
	}

	// рейтинг за информацию по игре
	public static function add_game(User $user, $sum) {
		$sql = sprintf("INSERT INTO `#__users_ratings` (`user_id`, `games_rate`) VALUES (%s, '%s')
			       ON DUPLICATE KEY UPDATE games_rate=games_rate+%s",
						(int) $user->id, $sum, $sum);
		database::getInstance()->setQuery($sql)->query();
		self::update_full_rate($user);
	}

	// рейтинг за добавленные материалы
	public static function add_topic(User $user, $sum) {
		$sql = sprintf("INSERT INTO `#__users_ratings` (`user_id`, `topics_rate`) VALUES (%s, '%s')
			       ON DUPLICATE KEY UPDATE topics_rate=topics_rate+%s;",
						(int) $user->id, $sum, $sum);
		database::getInstance()->setQuery($sql)->query();
		self::update_full_rate($user);
	}

	// рейтинг за комментарии к материалам
	public static function add_comments(User $user, $sum) {
		$sql = sprintf("INSERT INTO `#__users_ratings` (`user_id`, `comments_rate`) VALUES (%s, '%s')
			       ON DUPLICATE KEY UPDATE comments_rate=comments_rate+%s;",
						(int) $user->id, $sum, $sum);
		database::getInstance()->setQuery($sql)->query();
		self::update_full_rate($user);
	}

	// рейтинг активности на сайте
	public static function add_activity(User $user, $sum) {
		$sql = sprintf("INSERT INTO `#__users_ratings` (`user_id`, `activity_rate`) VALUES (%s, '%s')
			       ON DUPLICATE KEY UPDATE activity_rate=activity_rate+%s;",
						(int) $user->id, $sum, $sum);
		database::getInstance()->setQuery($sql)->query();
		self::update_full_rate($user);
	}

	// рейтинг активности на сайте
	public static function add_awards(User $user, $sum) {
		$sql = sprintf("INSERT INTO `#__users_ratings` (`user_id`, `awards_rate`) VALUES (%s, '%s')
			       ON DUPLICATE KEY UPDATE awards_rate=awards_rate+%s;",
						(int) $user->id, $sum, $sum);
		database::getInstance()->setQuery($sql)->query();
		self::update_full_rate($user);
	}

}