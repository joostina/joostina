<?php

/**
 * @package Joostina
 * @copyright Авторские права (C) 2008-2009 Joostina team. Все права защищены.
 * @license Лицензия http://www.gnu.org/licenses/gpl-2.0.htm GNU/GPL, или help/license.php
 * Joostina! - свободное программное обеспечение распространяемое по условиям лицензии GNU/GPL
 * Для получения информации о используемых расширениях и замечаний об авторском праве, смотрите файл help/copyright.php.
 */
// запрет прямого доступа
defined('_JOOS_CORE') or die();

class contactsMap {
	
	public static function get_params(){
		return array();
	}	

	public static function get_mapdata_scheme($params = array()) {

		return array(
			//map_block
			array(
				'id' => 'index',
				'link' => joosRoute::href('contacts'),
				'title' => 'Контакты',
				'level' => 1,
				'type' => 'single',
				'priority' => 0.5,
				'changefreq' => 'daily'
			),

		);
		
	}


}