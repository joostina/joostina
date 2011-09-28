<?php

/**
 * @package   Joostina
 * @copyright Авторские права (C) 2008-2009 Joostina team. Все права защищены.
 * @license   Лицензия http://www.gnu.org/licenses/gpl-2.0.htm GNU/GPL, или help/license.php
 * Joostina! - свободное программное обеспечение распространяемое по условиям лицензии GNU/GPL
 * Для получения информации о используемых расширениях и замечаний об авторском праве, смотрите файл help/copyright.php.
 */
// запрет прямого доступа
defined( '_JOOS_CORE' ) or die();

class newsMap {

	public static function get_params() {
		return array ( 'show_items' => false );
	}

	public static function get_mapdata_scheme( $params = array () ) {

		$scheme   = array ();

		$scheme[] = array ( 'id'         => 'index' ,
		                    'link'       => joosRoute::href( 'news' ) ,
		                    'title'      => 'Новости' ,
		                    'level'      => 1 ,
		                    'type'       => 'single' ,
		                    'priority'   => 0.5 ,
		                    'changefreq' => 'daily' );

		if ( $params['xml'] ) {
			$scheme[] = array ( 'id'         => 'news' ,
			                    'link'       => '' ,
			                    'title'      => '' ,
			                    'level'      => 2 ,
			                    'type'       => 'list' ,
			                    'call_from'  => 'newsMap::lists' ,
			                    'priority'   => 0.5 ,
			                    'changefreq' => 'daily' );
		}

		return $scheme;
	}

	public static function lists( $params = array () ) {

		$news = new News;
		$news = $news->get_list( array ( 'where' => 'state=1' ) );

		foreach ( $news as $obj ) {
			$obj->loc     = joosRoute::href( 'news_view' , array ( 'id' => $obj->id ) );
			$obj->lastmod = $obj->created_at;
		}

		return $news;
	}

}