<?php

/**
 * @package Joostina
 * @copyright Авторские права (C) 2008-2010 Joostina team. Все права защищены.
 * @license Лицензия http://www.gnu.org/licenses/gpl-2.0.htm GNU/GPL, или help/license.php
 * Joostina! - свободное программное обеспечение распространяемое по условиям лицензии GNU/GPL
 * Для получения информации о используемых расширениях и замечаний об авторском праве, смотрите файл help/copyright.php.
 */
// запрет прямого доступа
defined('_JOOS_CORE') or die();

class joosRoute extends RouteMap {

	/**
	 *
	 * @var RouteMap
	 */
	private static $instance;

	public static function instance() {
		if (self::$instance === NULL) {
			$map = new RouteMap(
							array('base_url' => JPATH_SITE, 'url_rewriting' => true)
			);

			// корневой роутер
			$map->connect('default', '', 'mainpage', 'index');

			// -----------------------------------------------------------------------------------Статьи
			$map->connect('content', 'content', 'content', 'index');
			$map->connect('content_view', 'view/catalog/*slug', 'content', 'view', array('slug' => '\S+'));
			$map->connect('category_view', 'catalog/*slug', 'content', 'category', array('slug' => '\S+'));

			$map->connect('content_cats_paginate', 'catalog/:slug', 'content', 'category', array('slug' => '\S+'));
			$map->connect('content_cats_paginate_page_index', 'catalog/:slug/page', 'content', 'category', array('slug' => '\S+'));
			$map->connect('content_cats_paginate_page_num', 'catalog/:slug/page/:page', 'content', 'category', array('slug' => '\S+', 'page' => '\d+'));


			// --------------------------------------------------------------------------Работа с пользователями
			$map->connect('register', 'register', 'users', 'register');
			$map->connect('register_check', 'register/check/*', 'users', 'check');
			$map->connect('lostpassword', 'lostpassword', 'users', 'lostpassword');
			$map->connect('login', 'login', 'users', 'login');
			$map->connect('logout', 'logout', 'users', 'logout');
			$map->connect('user_view', 'user/:username', 'users', 'showuser', array('username' => '[a-zA-Zа-яА-Я0-9-_*ё@).]+'));
			$map->connect('user_edit', 'user/:username/edit', 'users', 'edituser', array('username' => '[a-zA-Zа-яА-Я0-9-_*ё@).]+'));

			//------------------------------------------------------------------------------------------Контакты
			$map->connect('contacts', 'feedback', 'contacts', 'index');

			//------------------------------------------------------------------------------------------Карта сайта
			$map->connect('sitemap', 'sitemap', 'sitemap', 'index');

			//------------------------------------------------------------------------------------------опросы
			$map->connect('polls', 'polls', 'polls', 'index');
			$map->connect('poll_view', 'polls/:id', 'polls', 'view', array('id' => '\d+'));

			// -----------------------------------------------------------------------------------Новости
			$map->connect('news', 'news', 'news', 'index');
			$map->connect('news_page_index', 'news/page', 'news', 'index');
			$map->connect('news_page_num', 'news/page/:page', 'news', 'index', array('page' => '\d+'));

			$map->connect('news_archive', 'news/archive', 'news', 'archive');
			$map->connect('news_archive_page_index', 'news/archive/page', 'news', 'archive');
			$map->connect('news_archive_page_num', 'news/archive/page/:page', 'news', 'archive', array('page' => '\d+'));

			$map->connect('news_archive_year', 'news/archive/:year', 'news', 'archive', array('year' => '\d+'));
			$map->connect('news_archive_year_page_index', 'news/archive/:year/page', 'news', 'archive', array('year' => '\d+'));
			$map->connect('news_archive_year_page_num', 'news/archive/:year/page/:page', 'news', 'archive', array('page' => '\d+', 'year' => '\d+'));

			$map->connect('news_view', 'news/:id', 'news', 'view', array('id' => '\d+'));

			// -----------------------------------------------------------------------------------Поиск
			$map->connect('search', 'search', 'search', 'index');
			$map->connect('search_process', 'search/:slug/:searchword', 'search', 'index', array('slug' => '\S+', 'searchword' => '(.*?)'));
			$map->connect('search_page_index', 'search/:slug/:searchword', 'search', 'index', array('slug' => '\S+', 'searchword' => '(.*?)'));
			$map->connect('search_page_num', 'search/:slug/:searchword/page/:page', 'search', 'index', array('slug' => '\S+', 'searchword' => '(.*?)', 'page' => '\d+'));
			//$map->connect('search_page_num', 'search/:slug/:searchword/:page', 'search', 'index', array('slug' => '\S+', 'searchword' => '\S+', 'page' => '\d+'));
			// -----------------------------------------------------------------------------------Вопрос-ответ
			$map->connect('faq', 'faq', 'faq', 'index');
			$map->connect('faq_page_index', 'faq/page', 'job', 'index');
			$map->connect('faq_page_num', 'faq/page/:page', 'faq', 'index', array('page' => '\d+'));
			$map->connect('faq_send_question', 'faq/send', 'faq', 'send_question');

			$map->connect('faq_archive', 'faq/archive', 'faq', 'archive');
			$map->connect('faq_archive_page_index', 'faq/archive/page', 'faq', 'archive');
			$map->connect('faq_archive_page_num', 'faq/archive/page/:page', 'faq', 'archive', array('page' => '\d+'));

			$map->connect('faq_archive_year', 'faq/archive/:year', 'faq', 'archive', array('year' => '\d+'));
			$map->connect('faq_archive_year_page_index', 'faq/archive/:year/page', 'faq', 'archive', array('year' => '\d+'));
			$map->connect('faq_archive_year_page_num', 'faq/archive/:year/page/:page', 'faq', 'archive', array('page' => '\d+', 'year' => '\d+'));

			// -----------------------------------------------------------------------------------Странички
			$map->connect('pages_view_by_id', 'pages/:id', 'pages', 'view_by_id', array('id' => '\d+'));

			// конкретные страницы, всегда должно быть последним!
			$map->connect('pages_view', ':page_name', 'pages', 'view', array('page_name' => '[a-z]+'));

			$map->connect('category_collections', 'collections', 'content', 'category_collections');

			self::$instance = $map;
		}

		return self::$instance;
	}

	public static function route() {

		$_SERVER['QUERY_STRING'] = rtrim($_SERVER['QUERY_STRING'], '/');
		$routs = self::$instance->dispatch($_SERVER['QUERY_STRING']);

		joosController::$activroute = $routs['route'];
		joosController::$controller = $routs['action'][0] == 'autocontroller' ? $routs['args']['option'] : $routs['action'][0];
		joosController::$task = $routs['action'][1] == 'autotask' ? (isset($routs['args']['task']) ? $routs['args']['task'] : 'index') : $routs['action'][1];
		joosController::$param = $routs['args'];
	}

	public static function href($route_name, array $params = array()) {
		return self::$instance->url_for($route_name, $params);
	}

	public static function redirect($url, $msg = '', $type = 'success') {

		$iFilter = joosInputFilter::instance();
		$url = $iFilter->process($url);

		empty($msg) ? null : joosFlashMessage::add($iFilter->process($msg));

		$url = preg_split("/[\r\n]/", $url);
		$url = $url[0];

		if ($iFilter->badAttributeValue(array('href', $url))) {
			$url = JPATH_SITE;
		}


		if (headers_sent()) {
			echo "<script>document.location.href='$url';</script>\n";
		} else {
			@ob_end_clean(); // clear output buffer
			header('HTTP/1.1 301 Moved Permanently');
			header("Location: " . $url);
		}

		exit();
	}

}
