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

class actionsAjaxdemo extends Jcontroller {

	public static function index() {

		return array(
			'as_json'=>true,
			'.content'=>time(),
			'title'=>'Новый заголовок, йооооухууу',
			'.mod_menu'=>'А тут типа новый модуль, то-то'
		);

	}

}