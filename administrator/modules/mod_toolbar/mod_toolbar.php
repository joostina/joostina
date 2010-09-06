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

global $cur_template,$mosConfig_old_toolbar;

if(!defined('_TOOLBAR_MODULE')) {
	define('_TOOLBAR_MODULE',1);

	$file = $mosConfig_old_toolbar ? 'menubar.html.old.php' : 'menubar.html.php';

	if(file_exists(JPATH_BASE_ADMIN.'/templates/'.$cur_template.'/html/'.$file)) {
		require_once (JPATH_BASE_ADMIN.'/templates/'.$cur_template.'/html/'.$file);
	} else {
		require_once (JPATH_BASE_ADMIN.'/includes/'.$file);
	}
}

if($path = $mainframe->getPath('toolbar')) {
	include_once ($path);
}