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

joosLoader::model('content');
joosLoader::admin_model('categories');

class contentMap {
	
	public static function get_params(){
		$params = array('xml_show_articles'=>false);
		return $params;
	}

	public static function get_mapdata_scheme($params = array()) {		

		return array(
			//map_block
			array(
				'id' => 'index',
				'link' => '',
				'title' => 'Бренды',
				'level' => 1,
				'type' => 'single',
				'priority' => 0.5,
				'changefreq' => 'daily'
			),

			//map_block	
			array(
				'id' => 'categories',
				'link' => '',
				'title' => '',
				'level' => 2,
				'type' => 'list',
				'call_from' => 'contentMap::lists',
				'call_params' => $params,
				'priority' => 0.5,
				'changefreq' => 'daily'
			),
		);
	}

	public static function lists($params = array()) {
		
		$cats = new Categories;
		$cats = $cats->get_list(array('where' => '`group` = "content" AND state = 1', 'key'=>'id', 'order' => 'lft ASC'));		
		
		$cats_ids = array_keys($cats);
			
		
		if($params['xml']){
			$items = new Content;
			$items = $items->get_list(array(
					'where' => 'state = 1 AND category_id IN ('.implode(', ', $cats_ids).')'
			));
			
			$items_by_cats = array();
			foreach($items as $item){
				$item->loc = joosRoute::href('content_view', array('slug' => $item->slug));
				$item->lastmod = $item->created_at;
				
				$items_by_cats[$item->category_id][] = $item;	
			}			
		}	


		$results = array();
		
		foreach($cats as $cat){
			$cat->loc = joosRoute::href('category_view', array('id' => $cat->id, 'slug' => $cat->slug));
			$cat->lastmod = date('Y-m-d');
			$cat->title = $cat->name;
			$cat->level = $cat->level+1;
			$results[] = $cat;	
			
			if($params['xml']){
				if(isset($items_by_cats[$cat->id]))	{
					foreach($items_by_cats[$cat->id] as $item){
						$item->level = $cat->level +1;
						$results[] = $item;
					}					
				}
			}	
		}


		return $results;
	}

}