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

// разделитель каталогов
define('DS', DIRECTORY_SEPARATOR );
// корень файлов
define('JPATH_BASE', dirname(dirname(dirname(__FILE__))) );
// корень файлов админкиы
define('JPATH_BASE_ADMIN', dirname(dirname(__FILE__)) );

require_once (JPATH_BASE.DS.'configuration.php');

require(JPATH_BASE.DS.'includes/joostina.php');
// подключаем расширенные административные функции
require_once (JPATH_BASE_ADMIN.DS.'includes'.DS.'admin.php');

global $my;

session_name(md5($mosConfig_live_site));
session_start();

header('Content-type: text/html; charset=UTF-8');

$database = database::getInstance();

// restore some session variables
if(!isset($my)) {
	$my = new User($database);
}

$my->id = intval(mosGetParam($_SESSION,'session_user_id',''));
$my->username = strval(mosGetParam($_SESSION,'session_USER',''));
$my->groupname = strval(mosGetParam($_SESSION,'session_groupname',''));
$my->gid = intval(mosGetParam($_SESSION,'session_gid',''));
$session_id = strval(mosGetParam($_SESSION,'session_id',''));
$logintime = strval(mosGetParam($_SESSION,'session_logintime',''));

if($session_id != md5($my->id.$my->username.$my->groupname.$logintime)) {
	mosRedirect('index.php');
	die;
}