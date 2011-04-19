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

class actionsBookmarks extends joosController {

	//Обычные закладки
	public static function index() {
		$username = self::$param['username'];

		$user = new Users;
		$user->load_by_field('username', $username);

		$user->id ? null : joosRoute::redirect(JPATH_SITE, 'Такого пользователя у нас совсем нет. Ну, то есть, вообще (');

		$user_bookmarks = null;

		//Все закладки
		$bookmarks = $user->extra()->cache_bookmarks;


		//Новости
		$news = isset($bookmarks['News']['all']) ? $bookmarks['News']['all'] : null;
		if ($news) {
			$news_ids = array_keys($news);

			$news = new News;
			$user_bookmarks['news'] = $news->get_list(
							array(
								'select' => "id, title, type_id",
								'where' => 'state=1 AND id IN(' . implode(',', $news_ids) . ')',
								'order' => 'id DESC', // сначала последние
							)
			);
		}


		//Блоги
		$blogs = isset($bookmarks['Blogs']['all']) ? $bookmarks['Blogs']['all'] : null;
		if ($blogs) {
			$blogs_ids = array_keys($blogs);

			$blogs = new Blog();
			$user_bookmarks['blogs'] = $blogs->get_list(
							array(
								'select' => "b.id, b.title, u.id as userid, b.created_at, u.username,c.slug as cat_slug",
								'join' =>
								'as b INNER JOIN #__blog_category AS c ON ( c.id=b.category_id AND c.state=1 )' .
								' INNER JOIN #__users AS u ON u.id=b.user_id',
								'where' => 'b.state=1 AND b.id IN(' . implode(',', $blogs_ids) . ')',
								'order' => 'b.id DESC',
							)
			);
		}


		joosBreadcrumbs::instance()
				->add($username, joosRoute::href('user_view', array('username' => $username)))
				->add('Закладки');

		return array(
			'bookmarks' => $user_bookmarks,
			'user' => $user,
			'task' => 'index',
			'core::modules' => array(
				'sidebar' => array(
					'users' => array('type' => 'profile_user', 'template' => 'profile_user', 'user' => $user),
				)
			)
		);
	}

}