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
// разделитель каталогов
define('DS', DIRECTORY_SEPARATOR);
// корень файлов
define('JPATH_BASE', dirname(dirname(__FILE__)));
// корень файлов админкиы
define('JPATH_BASE_ADMIN', dirname(__FILE__));

//require_once (JPATH_BASE . DS . 'configuration.php');
// для совместимости
$mosConfig_absolute_path = JPATH_BASE;
 
require_once (JPATH_BASE . DS . 'core' . DS . 'joostina.php');
require_once (JPATH_BASE . DS . 'app' . DS . 'bootstrap.php');
require_once (JPATH_BASE . DS . 'core' . DS . 'admin.root.php');


joosDocument::header();

$mainframe = joosMainframe::instance(true);

$mainframe->set('lang', 'russian');
include_once($mainframe->get_lang_path());

session_name(md5(JPATH_SITE));
session_start();


$my = new stdClass;
$my->id = intval(mosGetParam($_SESSION, 'session_user_id', ''));
$my->username = strval(mosGetParam($_SESSION, 'session_USER', ''));
$my->groupname = strval(mosGetParam($_SESSION, 'session_groupname', ''));
$my->gid = intval(mosGetParam($_SESSION, 'session_gid', ''));
$session_id = strval(mosGetParam($_SESSION, 'session_id', ''));
$logintime = strval(mosGetParam($_SESSION, 'session_logintime', ''));

if ($session_id == md5($my->id . $my->username . $my->groupname . $logintime)) {
	joosRoute::redirect('index2.php');
	die();
}


if (isset($_POST['submit'])) {

	$usrname =  joosRequest::post('usrname', null);
	$pass = joosRequest::post('pass', null);

	if ($pass == null) {
		joosRoute::redirect(JPATH_SITE_ADMIN . '/', _PLEASE_ENTER_PASSWORDWORD);
		exit();
	}

	$database = database::instance();

	$my = null;
	$query = 'SELECT * FROM #__users WHERE username =' . $database->quote($usrname) . ' AND state = 1';
	$database->set_query($query)->load_object($my);

	if (isset($my->id)) {
		joosLoader::lib('acl', 'system');
		Jacl::init_admipanel();

		list($hash, $salt) = explode(':', $my->password);
		$cryptpass = md5($pass . $salt);

		if (strcmp($hash, $cryptpass) || !Jacl::isAllowed('adminpanel')) {
			// ошибка авторизации
			$query = 'UPDATE #__users SET bad_auth_count = bad_auth_count + 1 WHERE id = ' . (int) $my->id;
			$database->set_query($query)->query();
			$_SESSION['bad_auth'] = $bad_auth_count + 1;

			// TODO сделать настраиваемым число неудачных авторизаций перед блокировкой
			if ($_SESSION['bad_auth'] >= 5) {
				$query = 'UPDATE #__users SET state = 0 WHERE id = ' . (int) $my->id;
				$database->set_query($query)->query();
			}

			joosRoute::redirect(JPATH_SITE_ADMIN . '/index.php', _BAD_USERNAME_OR_PASSWORDWORD);
			exit();
		}

		session_destroy();
		session_unset();
		session_write_close();

		// construct Session ID
		$logintime = time();
		$session_id = md5($my->id . $my->username . $my->groupname . $logintime);

		session_name(md5(JPATH_SITE));
		session_id($session_id);
		session_start();

		// add Session ID entry to DB
		$query = "INSERT INTO #__session SET time = " . $database->quote($logintime) . ", session_id = " . $database->quote($session_id) . ", userid = " . (int) $my->id . ", groupname = " . $database->quote($my->groupname) . ", username = " . $database->quote($my->username) . ", gid=" . (int) $my->gid . ", is_admin=1";
		$database->set_query($query)->query();

		$query = "DELETE FROM #__session WHERE  is_admin=1 AND session_id != " . $database->quote($session_id) . " AND userid = " . (int) $my->id;
		database::instance()->set_query($query)->query();

		$_SESSION['session_id'] = $session_id;
		$_SESSION['session_user_id'] = $my->id;
		$_SESSION['session_USER'] = $my->username;
		$_SESSION['session_gid'] = $my->gid;
		$_SESSION['session_groupname'] = $my->groupname;
		$_SESSION['session_logintime'] = $logintime;
		$_SESSION['session_bad_auth_count'] = $my->bad_auth_count;
		$_SESSION['session_userstate'] = array();

		session_write_close();

		$expired = JPATH_SITE_ADMIN .'/index2.php';

		// скидываем счетчик неудачных авторзаций в админке
		$query = 'UPDATE #__users SET bad_auth_count = 0 WHERE id = ' . $my->id;
		$database->set_query($query)->query();

		/** cannot using joosRoute::redirect as this stuffs up the cookie in IIS */
		// redirects page to admin homepage by default or expired page
		echo "<script>document.location.href='$expired';</script>\n";
		exit();
	} else {
		joosRoute::redirect(JPATH_SITE_ADMIN . '/index.php?' . $config->config_admin_secure_code, _BAD_USERNAME_OR_PASSWORDWORD);
		exit();
	}
} else {
	$path = JPATH_BASE . DS . 'app' . DS . 'templates' . DS . JTEMPLATE . DS . 'login.php';
	require_once ($path);
}