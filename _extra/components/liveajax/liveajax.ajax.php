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

joosLoader::model('blog');

class actionsLiveajax extends joosController {

	public static function index() {
		
	}

	public static function reload_module() {
		
		$module = mosGetParam($_POST, 'module', '');
		$params = mosGetParam($_POST, 'params', array());

		$params['reload'] = 1;

		if (!$module) {
			return;
		}

		require_once(JPATH_BASE . DS . 'includes' . DS . 'frontend.php');
		echo joosModule::module($module, $params, array('hide_frame' => true));
	}
	

}