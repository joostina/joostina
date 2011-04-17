<?php

/**
 * Blogs - модуль вывода записей из блога
 * Вспомагательный класс
 *
 * @version 1.0
 * @package Joostina CMS
 * @subpackage Modules
 * @author JoostinaTeam
 * @copyright (C) 2008-2010 Joostina Team
 * @license see license.txt
 *
 * */
//Запрет прямого доступа
defined('_JOOS_CORE') or die();

class blogsHelper {

	//Получение последних записей из всех категорий
	public static function get_latest($limit = 10) {

		// формируем объект записей блога
		$blogs = new Blog();

		// опубликованные записи блога
		return $blogs->get_list(array(
			'select' => "b.id, b.title, u.id as userid, b.created_at, u.username,c.slug as cat_slug",
			'join' =>
			'as b INNER JOIN #__blog_category AS c ON ( c.id=b.category_id AND c.state=1 )' .
			' LEFT JOIN #__users AS u ON u.id=b.user_id',
			'where' => 'b.state=1',
			//'group' => 'u.id',
			'limit' => $limit,
			'order' => 'b.id DESC', // сначала последние
				), array('blog', 'modules', 'users'), 600
		);
	}

	//Получение последних записей из определенной категории
	public static function get_latest_by_cat_id($category_id, $limit = 10) {

		// формируем объект записей блога
		$blogs = new Blog();

		// опубликованные записи блога
		return $blogs->get_list(array(
			'select' => "b.id, b.title, u.id as userid, b.created_at, u.username,c.slug as cat_slug",
			'join' =>
			'as b INNER JOIN #__blog_category AS c ON ( c.id=b.category_id AND c.state=1 )' .
			' INNER JOIN #__users AS u ON u.id=b.user_id',
			'where' => 'b.state=1 AND b.category_id=' . $category_id,
			'group' => 'u.id',
			'limit' => $limit,
			'order' => 'b.id DESC', // сначала последние
				), array('blog', 'modules', 'users'), 600
		);
	}

	//По автору
	public static function get_by_user($params) {

		$limit = isset($params['limit']) ? $params['limit'] : 5;

		$user = $params['user'];

		$cat_id = $user->gid == 9 ? 1 : 3;

		$exceptid = isset($params['exceptid']) ? ' AND b.id!=' . $params['exceptid'] . ' ' : '';

		// формируем объект записей блога
		$blogs = new Blog();

		// опубликованные записи блога
		return $blogs->get_list(
				array(
			'select' => "b.id, b.title, u.id as userid, b.created_at, u.username,c.slug as cat_slug",
			'join' =>
			'as b INNER JOIN #__blog_category AS c ON ( c.id=b.category_id AND c.state=1 )' .
			' INNER JOIN #__users AS u ON u.id=b.user_id',
			'where' => 'b.state=1 AND b.user_id = ' . $user->id . '  AND b.category_id=' . $cat_id . $exceptid,
			'limit' => $limit,
			'order' => 'b.id DESC', // сначала последние
				), array(
			'blog', 'modules', 'users'
				)
		);
	}

}