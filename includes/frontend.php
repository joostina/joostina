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

class Jroute extends RouteMap {

	/**
	 *
	 * @var RouteMap
	 */
	private static $instance;

	public static function getInstance() {
		if (self::$instance === NULL) {
			$map = new RouteMap(
							array('base_url' => JPATH_SITE, 'url_rewriting' => true)
			);
			$map->connect('default', '', 'mainpage', 'index');
			//$map->connect('controller', ':option', 'autocontroller', 'autotask');
			//$map->connect('controller_task', ':option/:task', 'autocontroller', 'autotask');
			//$map->connect('default-3', ':option/:task/:id', 'autocontroller', 'autotask');
			//$map->connect('default1', ':option/:task/:id/:param1', 'autocontroller', 'autotask');
			//$map->connect('default2', ':option/:task/:id/:param1/:param2', 'autocontroller', 'autotask');
			//$map->connect('default3', ':option/:task/:id/:param1/:param2/:param3', 'autocontroller', 'autotask');
			//$map->connect('default4', ':option/:task/:id/:param1/:param2/:param3/:param4', 'autocontroller', 'autotask');
			//$map->connect('default5', ':option/:task/:id/:param1/:param2/:param3/:param4/:param5', 'autocontroller', 'autotask');
			//$map->connect('default6', ':option/:task/:id/:param1/:param2/:param3/:param4/:param5/:param6', 'autocontroller', 'autotask');


			$map->connect('register', 'register', 'users', 'register');
			$map->connect('register_check', 'register/check/*', 'users', 'check');
			$map->connect('lostpassword', 'lostpassword', 'users', 'lostpassword');
			$map->connect('login', 'login', 'users', 'login');

			$map->connect('blog', 'blog', 'blog', 'index');
			$map->connect('blog_page_index', 'blog/page', 'blog', 'index');
			$map->connect('blog_page_num', 'blog/page/:page', 'blog', 'index',
					array('page' => '\d+')
			);
			$map->connect('blog_users', 'blog/users', 'blog', 'users_blog');
			$map->connect('blog_users_page_index', 'blog/users/page', 'blog', 'users_blog');
			$map->connect('blog_users_page_num', 'blog/users/page/:page', 'blog', 'users_blog',
					array('page' => '\d+')
			);
			$map->connect('blog_user', 'blog/users/:user_name', 'blog', 'users',
					array('user_name' => '[a-zа-я0-9]+')
			);
			$map->connect('blog_user_page_index', 'blog/users/:user_name/page', 'blog', 'users',
					array('user_name' => '[a-zа-я0-9]+')
			);
			$map->connect('blog_user_page_num', 'blog/users/:user_name/page/:page', 'blog', 'users',
					array('user_name' => '[a-zа-я0-9]+', 'page' => '\d+')
			);
			$map->connect('blog_cat', 'blog/:cat_name', 'blog', 'category',
					array('cat_name' => '[a-zа-я0-9]+')
			);
			$map->connect('blog_cat_page_index', 'blog/:cat_name/page', 'blog', 'category',
					array('cat_name' => '[a-zа-я0-9]+')
			);
			$map->connect('blog_cat_page_num', 'blog/:cat_name/page/:page', 'blog', 'category',
					array('cat_name' => '[a-zа-я0-9]+', 'page' => '\d+')
			);
			$map->connect('blog_view', 'blog/:cat_name/:id', 'blog', 'view',
					array('cat_name' => '[a-zа-я0-9]+', 'id' => '\d+')
			);
			$map->connect('blog_users', 'users', 'users', 'index');
			$map->connect('blog_users_page_index', 'users/page', 'users', 'index');
			$map->connect('blog_users_page_num', 'users/page/:page', 'users', 'index',
					array('page' => '\d+')
			);
			$map->connect('blog_users_userpage', 'users/:username', 'users', 'showuser',
					array('username' => '[a-zа-я0-9]+')
			);

			$map->connect('tags', 'tags', 'tags', 'cloud');
			$map->connect('tags_view', 'tags/:tag', 'tags', 'index',
					array('tag' => '[a-zа-я0-9\-,]+')
			);
			$map->connect('tags_view_page_index', 'tags/:tag/page', 'tags', 'index',
					array('tag' => '[a-zа-я0-9\-,]+')
			);
			$map->connect('tags_view_page_num', 'tags/:tag/page/:page', 'tags', 'index',
					array('tag' => '[a-zа-я0-9\-,]+', 'page' => '\d+')
			);

			$map->connect('news', 'news', 'news', 'index');
			$map->connect('news_page_index', 'news/page', 'news', 'index');
			$map->connect('news_page_num', 'news/page/:page', 'news', 'index',
					array('page' => '\d+')
			);
			$map->connect('news_view', 'news/:id', 'news', 'view',
					array('id' => '\d+')
			);

			$map->connect('pages_view', ':page_name', 'pages', 'view_slug',
					array('page_name' => '[a-z]+' )
			);

			self::$instance = $map;
		}

		return self::$instance;
	}

	public static function route() {

		$_SERVER['QUERY_STRING'] = rtrim($_SERVER['QUERY_STRING'], '/');
		$routs = self::$instance->dispatch($_SERVER['QUERY_STRING']);

		Jcontroller::$activroute = $routs['route'];
		Jcontroller::$controller = $routs['action'][0] == 'autocontroller' ? $routs['args']['option'] : $routs['action'][0];
		Jcontroller::$task = $routs['action'][1] == 'autotask' ? (isset($routs['args']['task']) ? $routs['args']['task'] : 'index') : $routs['action'][1];
		Jcontroller::$param = $routs['args'];
	}

	public static function href($route_name, array $params = array()) {
		return self::$instance->url_for($route_name, $params);
	}

}

//echo sefRelToAbs('index.php?option=test&task=browser&id=123&slugasd=жопь+ебрило');
//echo '<br />';
Jroute::getInstance();
Jroute::route();

//die();



function sefRelToAbs($string, $ext = false, $route_name = false) {

	if (!$route_name) {
		return $string;
	}

	$string = str_replace('index.php?', '', $string);
	parse_str($string, $arr);
	$route = Jroute::getInstance();

	return $route->url_for($route_name, $arr);
}