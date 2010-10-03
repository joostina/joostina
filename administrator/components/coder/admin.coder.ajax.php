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

mosMainFrame::addLib('joiadmin');
JoiAdmin::dispatch();

class actionsCoder {

	private static $implode_model = true;


	public static function on_start() {
		mosMainFrame::addLib('form');
		require joosCore::path('coder', 'admin_class');
	}

	public static function index() {
		$tables = mosGetParam($_POST, 'codertable', array());

		$ret = array();
		foreach ($tables as $table) {
			$ret[] = Coder::get_model($table, self::$implode_model );
		}

		return self::$implode_model ?  form::textarea(array('name' => 'all_models', 'value' => implode('', $ret), 'rows' => '25', 'class'=>'coder_model_area' )) : implode("\n", $ret);
	}

}