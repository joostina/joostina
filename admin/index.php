<?php

/**
 * @package Joostina
 * @copyright Авторские права (C) 2007-2010 Joostina team. Все права защищены.
 * @license Лицензия http://www.gnu.org/licenses/gpl-2.0.htm GNU/GPL, или help/license.php
 * Joostina! - свободное программное обеспечение распространяемое по условиям лицензии GNU/GPL
 * Для получения информации о используемых расширениях и замечаний об авторском праве, смотрите файл help/copyright.php.
 */
// Установка флага родительского файла
define('_JOOS_CORE', 1);

// корень файлов панели управления
define('JPATH_BASE_ADMIN', __DIR__);

require_once ( dirname(JPATH_BASE_ADMIN)  . DIRECTORY_SEPARATOR . 'core' . DIRECTORY_SEPARATOR . 'joostina.php');
require_once (JPATH_BASE . DS . 'core' . DS . 'admin.root.php');
require_once (JPATH_BASE . DS . 'app' . DS . 'bootstrap.php');

joosDocument::header();
joosCoreAdmin::start();

$my = new stdClass;
$my->id = (int) joosRequest::session('session_user_id');
$my->username = joosRequest::session('session_username');
$my->groupname = joosRequest::session('session_groupname');
$my->gid = (int) joosRequest::session('session_gid');
$session_id = joosRequest::session('session_id');
$logintime = joosRequest::session('session_logintime');

if ($session_id == md5($my->id . $my->username . $my->groupname . $logintime)) {
	joosRoute::redirect('index2.php');
	die();
}

if (joosRequest::is_post()) {

	joosCSRF::check_code('admin_login');

	$username = joosRequest::post('username');
	$password = joosRequest::post('password');

	if ($password == null) {
		joosRoute::redirect(JPATH_SITE_ADMIN . '/', __('Необходимо ввести пароль'));
		exit();
	}

	$database = joosDatabase::instance();

	$my = null;
	$query = 'SELECT * FROM #__users WHERE username =' . $database->quote($username) . ' AND state = 1';
	$database->set_query($query)->load_object($my);

	if (isset($my->id)) {
		joosAcl::init_admipanel();

		list($hash, $salt) = explode(':', $my->password);
		$cryptpass = md5($password . $salt);

		// TODO переделать логику с bad_auth (сбрасывать счетчик в таблице после успешного логина + $_SESSION['bad_auth'] не работает)
		// TODO сделать настраиваемым число неудачных авторизаций перед блокировкой
		$bad_auth = $my->bad_auth_count;

		if (strcmp($hash, $cryptpass) || !joosAcl::isAllowed('adminpanel')) {
			// ошибка авторизации
			$query = 'UPDATE #__users SET bad_auth_count = bad_auth_count + 1 WHERE id = ' . (int) $my->id;
			$database->set_query($query)->query();
			$_SESSION['bad_auth'] = $bad_auth + 1;

			if ($bad_auth >= 5) {
				$query = 'UPDATE #__users SET state = 0 WHERE id = ' . (int) $my->id;
				$database->set_query($query)->query();

				joosRoute::redirect(JPATH_SITE_ADMIN . '/index.php', 'Ваш аккаунт был заблокирован. Обратитесь к администратору сайта: ' . joosConfig::get2('mail', 'from'));
			}

			joosRoute::redirect(JPATH_SITE_ADMIN . '/index.php', 'Неправильный логин или пароль');
			exit();
		}

		// construct Session ID
		$logintime = time();
		$session_id = md5($my->id . $my->username . $my->groupname . $logintime);

		// чистим старые сессии
		session_destroy();
		session_unset();
		session_write_close();

		// запускаем новую сессию с нужным идентификатором и именем
		session_name(JADMIN_SESSION_NAME);
		session_id($session_id);
		session_start();

		// add Session ID entry to DB
		$query = "INSERT INTO #__session SET time = " . $database->quote($logintime) . ", session_id = " . $database->quote($session_id) . ", userid = " . (int) $my->id . ", groupname = " . $database->quote($my->groupname) . ", username = " . $database->quote($my->username) . ", gid=" . (int) $my->gid . ", guest=0, is_admin=1";
		$database->set_query($query)->query();

		$query = "DELETE FROM #__session WHERE  is_admin=1 AND session_id != " . $database->quote($session_id) . " AND userid = " . (int) $my->id;
		joosDatabase::instance()->set_query($query)->query();

		$_SESSION['session_id'] = $session_id;
		$_SESSION['session_user_id'] = $my->id;
		$_SESSION['session_username'] = $my->username;
		$_SESSION['session_gid'] = $my->gid;
		$_SESSION['session_groupname'] = $my->groupname;
		$_SESSION['session_logintime'] = $logintime;
		$_SESSION['session_bad_auth_count'] = $my->bad_auth_count;
		$_SESSION['session_userstate'] = array();

		session_write_close();

		$expired = JPATH_SITE_ADMIN . '/index2.php';

		// скидываем счетчик неудачных авторзаций в админке
		$query = 'UPDATE #__users SET bad_auth_count = 0 WHERE id = ' . $my->id;
		$database->set_query($query)->query();

		/** cannot using joosRoute::redirect as this stuffs up the cookie in IIS */
		// redirects page to admin homepage by default or expired page
		echo "<script>document.location.href='$expired';</script>\n";
		exit();
	} else {
		joosRoute::redirect(JPATH_SITE_ADMIN . '/index.php', 'Такой пользователь не существует или ваш аккаунт был заблокирован. Обратитесь к администратору сайта: ' . joosConfig::get2('mail', 'from'));
		exit();
	}
} else {
	$path = JPATH_BASE . DS . 'app' . DS . 'templates' . DS . JTEMPLATE_ADMIN . DS . 'login.php';
	require_once ($path);
}