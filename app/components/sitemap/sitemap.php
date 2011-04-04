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

joosLoader::model('sitemap');
joosLoader::lib('metainfo', 'joostina');

class actionsSitemap {
	
	
	public static function on_start($active_task) {		
		//Хлебные крошки
		Jbreadcrumbs::instance()
				->add('Карта сайта');
				
		//Метаинформация страницы
		Metainfo::set_meta('sitemap', '', '', array('title'=>'Карта сайта'));
	}	
	
	
	public static function index(){
		
		$map = Sitemap::get_map();
		
		return array(
			'map' => $map
		);		
	}


}