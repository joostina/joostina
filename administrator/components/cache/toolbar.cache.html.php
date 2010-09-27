<?php
/**
 * @version		$Id: toolbar.cache.html.php 11393 2009-05-05 02:11:06Z ian $
 * @package		Joostina
 * @subpackage	Cache
 * @copyright	Copyright (C) 2005 - 2009 Open Source Matters. All rights reserved.
 * @license		GNU/GPL, see LICENSE.php
 * Joomla! is free software. This version may have been modified pursuant
 * to the GNU General Public License, and as distributed it includes or
 * is derivative of works licensed under the GNU General Public License or
 * other free or open source software licenses.
 * See COPYRIGHT.php for copyright notices and details.
 */

// запрет прямого доступа
defined('_JOOS_CORE') or die();

class TOOLBAR_cache {

	public static function _DEFAULT() {
		mosMenuBar::startTable();
		mosMenuBar::deleteList('', 'delete');
		mosMenuBar::endTable();
	}

	public static function DEFAULT_MENU() {
		mosMenuBar::startTable();
		mosMenuBar::deleteList('', 'delete');
		mosMenuBar::endTable();
	}
}