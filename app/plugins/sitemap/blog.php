<?php

/**
 * @package   Joostina
 * @copyright Авторские права (C) 2008-2009 Joostina team. Все права защищены.
 * @license   Лицензия http://www.gnu.org/licenses/gpl-2.0.htm GNU/GPL, или help/license.php
 * Joostina! - свободное программное обеспечение распространяемое по условиям лицензии GNU/GPL
 * Для получения информации о используемых расширениях и замечаний об авторском праве, смотрите файл help/copyright.php.
 */
// запрет прямого доступа
defined('_JOOS_CORE') or die();

/**
 * Формирует карту блока
 *
 * @version    1.0
 * @package    Plugins
 * @subpackage Sitemap
 * @category   Sitemap
 * @author     Joostina Team <info@joostina.ru>
 * @copyright  (C) 2007-2012 Joostina Team
 * @license    MIT License http://www.opensource.org/licenses/mit-license.php
 * Информация об авторах и лицензиях стороннего кода в составе Joostina CMS: docs/copyrights
 *
 * */
class sitemapBlog {

	public static function get_mapdata_scheme() {

		return array(//map_block
			array('id' => 'index',
				'link' => joosRoute::href('blog'),
				'title' => 'Блоги',
				'level' => 1,
				'type' => 'single',
				'priority' => 0.5,
				'changefreq' => 'daily'), //map_block
			array('id' => 'dj',
				'link' => joosRoute::href('blog_cat', array('cat_slug' => 'dj')),
				'title' => 'Блоги крутых',
				'level' => 2,
				'type' => 'single',
				'priority' => 0.5,
				'changefreq' => 'daily'), //map_block
			array('id' => 'dj',
				'link' => '',
				'title' => '',
				'level' => 3,
				'type' => 'list',
				'call_from' => 'blogMap::lists',
				'call_param' => array('cat_id' => 1,),
				'priority' => 0.5,
				'changefreq' => 'daily'), //map_block
			array('id' => 'progs',
				'link' => joosRoute::href('blog_cat', array('cat_slug' => 'progs')),
				'title' => 'Блоги программ',
				'level' => 2,
				'type' => 'single',
				'priority' => 0.5,
				'changefreq' => 'daily'), //map_block
			array('id' => 'progs',
				'link' => '',
				'title' => '',
				'level' => 3,
				'type' => 'list',
				'call_from' => 'blogMap::lists',
				'call_param' => array('cat_id' => 2,),
				'priority' => 0.5,
				'changefreq' => 'daily'), //map_block
			array('id' => 'peoples',
				'link' => joosRoute::href('blog_cat', array('cat_slug' => 'peoples')),
				'title' => 'Блоги пользователей',
				'level' => 2,
				'type' => 'single',
				'priority' => 0.5,
				'changefreq' => 'daily'), //map_block
			array('id' => 'peoples',
				'link' => '',
				'title' => '',
				'level' => 3,
				'type' => 'list',
				'call_from' => 'blogMap::lists',
				'call_param' => array('cat_id' => 3,),
				'priority' => 0.5,
				'changefreq' => 'daily'), //map_block
			array('id' => 'interviev',
				'link' => joosRoute::href('blog_cat', array('cat_slug' => 'interviev')),
				'title' => 'Интервью со звёздами',
				'level' => 2,
				'type' => 'single',
				'priority' => 0.5,
				'changefreq' => 'daily'), //map_block
			array('id' => 'interviev',
				'link' => '',
				'title' => '',
				'level' => 3,
				'type' => 'list',
				'call_from' => 'blogMap::lists',
				'call_param' => array('cat_id' => 4,),
				'priority' => 0.5,
				'changefreq' => 'daily'),);
	}

	public static function lists($param) {

		$cat_id = $param['cat_id'];

		$sql = sprintf("SELECT b.id, b.title, b.created_at AS lastmod, c.slug as cat_slug FROM #__blog AS b
				INNER JOIN #__blog_category AS c ON ( c.id=b.category_id AND c.state=1 AND c.id=%s )
				WHERE b.state=1 ORDER BY b.id DESC", $cat_id);
		$objs = joosDatabase::instance()->set_query($sql)->load_object_list();

		foreach ($objs as $obj) {
			$obj->loc = joosRoute::href('blog_view', array('id' => $obj->id,
						'cat_slug' => $obj->cat_slug));
		}

		return $objs;
	}

}