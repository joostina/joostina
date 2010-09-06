<?php

/**
 * @package Joostina
 * @copyright Авторские права (C) 2007-2010 Joostina team. Все права защищены.
 * @license Лицензия http://www.gnu.org/licenses/gpl-2.0.htm GNU/GPL, или help/license.php
 * Joostina! - свободное программное обеспечение распространяемое по условиям лицензии GNU/GPL
 * Для получения информации о используемых расширениях и замечаний об авторском праве, смотрите файл help/copyright.php.
 */
// Установка флага родительского файла
define('_VALID_MOS', 1);
// разделитель каталогов
define('DS', DIRECTORY_SEPARATOR);
// корень файлов
define('JPATH_BASE', dirname(dirname(__FILE__)));
// корень файлов админкиы
define('JPATH_BASE_ADMIN', dirname(__FILE__));

require_once (JPATH_BASE . DS . 'configuration.php');

// для совместимости
$mosConfig_absolute_path = JPATH_BASE;

// live_site
define('JPATH_SITE', $mosConfig_live_site);

require_once (JPATH_BASE . DS . 'includes/joostina.php');
require_once (JPATH_BASE_ADMIN . DS . 'includes' . DS . 'admin.php');

$mainframe = mosMainFrame::getInstance(true);
$database = $mainframe->getDBO();
$config = $mainframe->config;

$mainframe->set('lang', 'russian');
include_once($mainframe->getLangFile());

$option = strtolower(strval(mosGetParam($_REQUEST, 'option', null)));

session_name(md5($mosConfig_live_site));
session_start();

header('Content-type: text/html; charset=UTF-8');

$bad_auth_count = intval(mosGetParam($_SESSION, 'bad_auth', 0));

if (isset($_POST['submit'])) {
	$usrname = stripslashes(mosGetParam($_POST, 'usrname', null));
	$pass = stripslashes(mosGetParam($_POST, 'pass', null));

	if ($pass == null) {
		mosRedirect(JPATH_SITE . '/' . JADMIN_BASE . '/', _PLEASE_ENTER_PASSWORDWORD);
		exit();
	}

	if ($config->config_captcha OR ((int) $config->config_admin_bad_auth >= 0 && $config->config_admin_bad_auth <= $bad_auth_count)) {
		$captcha = mosGetParam($_POST, 'captcha', '');
		$captcha_keystring = mosGetParam($_SESSION, 'captcha_keystring', '');
		if ($captcha_keystring != $captcha) {
			mosRedirect(JPATH_SITE . '/' . JADMIN_BASE . '/', _BAD_CAPTCHA_STRING);
			unset($_SESSION['captcha_keystring']);
			exit;
		}
	}

	$my = null;
	$query = 'SELECT * FROM #__users WHERE username =' . $database->Quote($usrname) . ' AND state = 1';
	$database->setQuery($query)->loadObject($my);

	if (isset($my->id)) {

		mosMainFrame::addLib('acl');
		Jacl::init_admipanel();

		list($hash, $salt) = explode(':', $my->password);
		$cryptpass = md5($pass . $salt);

		if (strcmp($hash, $cryptpass) || !Jacl::isAllowed('adminpanel')) {
			// ошибка авторизации
			$query = 'UPDATE #__users SET bad_auth_count = bad_auth_count + 1 WHERE id = ' . (int) $my->id;
			$database->setQuery($query)->query();
			$_SESSION['bad_auth'] = $bad_auth_count + 1;

			if ($_SESSION['bad_auth'] >= $config->config_count_for_user_block) {
				$query = 'UPDATE #__users SET state = 0 WHERE id = ' . (int) $my->id;
				$database->setQuery($query)->query();
			}

			mosRedirect(JPATH_SITE . '/' . JADMIN_BASE . '/index.php?' . $config->config_admin_secure_code, _BAD_USERNAME_OR_PASSWORDWORD);
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
		$query = "INSERT INTO #__session SET time = " . $database->Quote($logintime) . ", session_id = " . $database->Quote($session_id) . ", userid = " . (int) $my->id . ", groupname = " . $database->Quote($my->groupname) . ", username = " . $database->Quote($my->username);
		$database->setQuery($query)->query();

		// delete other open admin sessions for same account
		$query = "DELETE FROM #__session WHERE userid = " . (int) $my->id . " AND username = " . $database->Quote($my->username) . "\n AND groupname = " . $database->Quote($my->groupname) . "\n AND session_id != " . $database->Quote($session_id) . "\n AND guest = 1" . "\n AND gid = 0";
		$database->setQuery($query)->query();

		$_SESSION['session_id'] = $session_id;
		$_SESSION['session_user_id'] = $my->id;
		$_SESSION['session_USER'] = $my->username;
		$_SESSION['session_gid'] = $my->gid;
		$_SESSION['session_groupname'] = $my->groupname;
		$_SESSION['session_logintime'] = $logintime;
		$_SESSION['session_bad_auth_count'] = $my->bad_auth_count;
		$_SESSION['session_userstate'] = array();

		session_write_close();

		$expired = 'index2.php';

		// скидываем счетчик неудачных авторзаций в админке
		$query = 'UPDATE #__users SET bad_auth_count = 0 WHERE id = ' . $my->id;
		$database->setQuery($query)->query();

		/** cannot using mosredirect as this stuffs up the cookie in IIS */
		// redirects page to admin homepage by default or expired page
		echo "<script>document.location.href='$expired';</script>\n";
		exit();
	} else {
		mosRedirect(JPATH_SITE . '/' . JADMIN_BASE . '/index.php?' . $config->config_admin_secure_code, _BAD_USERNAME_OR_PASSWORDWORD);
		exit();
	}
} else {
	initGzip();
	header('Content-type: text/html; charset=UTF-8');
	if ($config->config_admin_bad_auth <= $bad_auth_count && (int) $config->config_admin_bad_auth >= 0) {
		$config->config_captcha = 1;
	}
	$path = JPATH_BASE . DS . JADMIN_BASE . DS . 'templates' . DS . JTEMPLATE . DS . 'login.php';
	require_once ($path);
	doGzip();
}