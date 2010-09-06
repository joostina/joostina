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

require_once ($mainframe->getPath('admin_html'));

mosMainFrame::addLib('joiadmin');

JoiAdmin::dispatch();

class actionsAdmin {

	public static function index() {
		HTML_admin_misc::controlPanel();
	}

	public static function cpanel() {
		self::index();
	}

	// очистка кэша содержимого
	public static function clean_cache() {
		mosCache::cleanCache('com_content');
		mosRedirect('index2.php',_CACHE_CLEAR_CONTENT);
	}

	// очистка всего кэша
	public static function clean_all_cache() {
		mosCache::cleanCache();
		mosCache::cleanCache('page');
		mosRedirect('index2.php',_CACHE_CLEAR_ALL);
	}

	public static function redirect() {
		$goto = strval(strtolower(mosGetParam($_REQUEST,'link')));
		if($goto == 'null') {
			$msg = _COM_ADMIN_NON_LINK_OBJ;
			mosRedirect('index2.php?option=com_admin&task=listcomponents',$msg);
			exit();
		}
		$goto = str_replace("'",'',$goto);
		mosRedirect($goto);
	}

	public static function listcomponents() {
		HTML_admin_misc::ListComponents();
	}

	public static function sysinfo() {
		$version = new coreVersion();
		HTML_admin_misc::system_info($version);
	}

	public static function changelog() {
		HTML_admin_misc::changelog();
	}

	public static function help() {
		HTML_admin_misc::help();
	}

	public static function version() {
		HTML_admin_misc::version();
	}

	public static function preview() {
		HTML_admin_misc::preview();
	}

	public static function preview2() {
		HTML_admin_misc::preview(1);
	}
}