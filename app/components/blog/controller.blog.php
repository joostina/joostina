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

class actionsBlog extends joosController {

	public static function on_start($active_task) {
		joosBreadcrumbs::instance()
				->add('Блоги', $active_task == 'index' ? false : joosRoute::href('blog'));
	}

	public static function index() {

		$page = isset(self::$param['page']) ? self::$param['page'] : 0;

		// формируем объект записей блога
		$blogs = new Blog();

		// число записей в блоге
		$count = $blogs->count('WHERE state=1');

		// подключаем библиотеку постраничной навигации
		joosLoader::lib('pager', 'utils');
		$pager = new Pager(joosRoute::href('blog'), $count, 5, 6);
		$pager->paginate($page);

		// опубликованные записи блога
		$blog_items = $blogs->get_list(
						array(
					'select' => "b.*,c.slug AS cat_slug, u.gid, u.id AS userid, u.username, u.realname, comm.counter AS comments, votesresults.votes_count AS votesresults",
					'join' => 'AS b
								INNER JOIN #__blog_category AS c ON ( c.id=b.category_id AND c.state=1 )
								INNER JOIN #__users AS u ON u.id=b.user_id
								LEFT JOIN #__comments_counter AS comm ON (comm.obj_option = "Blog" AND comm.obj_id = b.id)
								LEFT JOIN #__votes_blog_results AS votesresults ON ( votesresults.obj_id=b.id )',
					'where' => 'b.state=1',
					'offset' => $pager->offset,
					'limit' => $pager->limit,
					'order' => 'id DESC', // сначала последние
						), array('controller', 'blog', 'comments', 'rater')
		);

		// устанавливаем заголосов страницы
		joosDocument::instance()
				->set_page_title('Блоги');

		return array(
			'blog_items' => $blog_items,
			'pager' => $pager,
			'core::modules' => array(
				'sidebar' => array(
					'users' => array('type' => 'authors_top', 'template' => 'authors_top'),
					'comments' => array('type' => 'from_blogs')
				)
			)
		);
	}

	public static function category() {
		// текущая страница
		$page = isset(self::$param['page']) ? self::$param['page'] : 0;
		// slug-название категории которой принадлежит текущая запись
		$cat_name = self::$param['cat_slug'];

		// формируем и загружаем категорпию к которой принадлежит просматриваемая запись
		$blog_category = new BlogCategory;
		$blog_category->slug = $cat_name;
		if (!$blog_category->find()) {
			return self::error404();
		}

		// формируем объект записей блога
		$blogs = new Blog();

		// число записей в блоге
		$count = $blogs->count('WHERE state=1 AND category_id=' . $blog_category->id);

		// подключаем библиотеку постраничной навигации
		joosLoader::lib('pager', 'utils');
		$pager = new Pager(joosRoute::href('blog_cat', array('cat_slug' => $blog_category->slug)), $count, 5, 6);
		$pager->paginate($page);

		// опубликованные записи блога
		$blog_items = $blogs->get_list(
						array(
					'select' => "b.*,c.slug AS cat_slug, u.gid, u.id AS userid, u.username, u.realname, comm.counter AS comments, votesresults.votes_count AS votesresults",
					'join' => "AS b INNER JOIN #__blog_category AS c ON ( c.id=b.category_id AND c.state=1 )"
					. "\nINNER JOIN #__users AS u ON u.id=b.user_id"
					. "\nLEFT JOIN #__comments_counter AS comm ON (comm.obj_option = 'Blog' AND comm.obj_id = b.id)"
					. "\nLEFT JOIN #__votes_blog_results AS votesresults ON ( votesresults.obj_id=b.id )",
					'where' => 'b.state=1 AND b.category_id=' . $blog_category->id,
					'offset' => $pager->offset,
					'limit' => $pager->limit,
					'order' => 'id DESC', // сначала последние
						), array('controller', 'blog', 'comments', 'rater')
		);

		joosBreadcrumbs::instance()
				->add($blog_category->title);


		return array(
			'blog_category' => $blog_category,
			'blog_items' => $blog_items,
			'pager' => $pager,
		);
	}

	public static function view() {

		// номер просматриваемой записки
		$id = self::$param['id'];
		// slug-название категории которой принадлежит текущая запись
		$cat_name = self::$param['cat_slug'];

		// формируем и загружаем просматриваемую запись
		$blog = new Blog;
		$blog->load($id) ? null : self::error404();

		// формируем и загружаем категорпию к которой принадлежит просматриваемая запись
		$blog_category = new BlogCategory;
		$blog_category->load($blog->category_id) ? null : self::error404();

		// проверяем что в ссылки на текущаю запись название slug-категории соответствует реальным данным этой категории
		$blog_category->slug == $cat_name ? null : self::error404();

		$user = new User;
		$user->load($blog->user_id) ? null : self::error404();

		// одно из вышеобозначенных действий зафиксировало ошибку, прекращаем работу
		if (self::$error) {
			return;
		}

		// устанавливаем заголосов страницы
		joosDocument::instance()
				->set_page_title($blog->title);

		$tags = new Tags;
		$comments = new Comments;

		// оценка
		joosLoader::lib('voter', 'joostina');
		$vr = new VotesResult('blog');
		$blog->votesresults = round($vr->load_counter($id), 1);


		//Определяем модули для вывода
		$first_module = array();

		// передаём параметры записи и категории в которой находится запись для оформления
		return array(
			'blog' => $blog,
			'blog_category' => $blog_category,
			'user' => $user,
			'comments' => $comments,
			'tags' => $tags,
		);
	}

	public static function user_blog() {

		$page = isset(self::$param['page']) ? self::$param['page'] : 0;

		isset(self::$param['username']) ? null : self::error404();

		$user = new User;
		$user->load_by_field('username', self::$param['username']) ? null : self::error404();

		// формируем объект записей блога
		$blogs = new Blog();

		// число записей в блоге
		$count = $blogs->count('WHERE state=1 AND user_id = ' . $user->id);

		$state = (User::current()->id == $user->id || User::current()->gid == 8) ? 'b.state>=0' : 'b.state=1';

		// подключаем библиотеку постраничной навигации
		joosLoader::lib('pager', 'utils');
		$pager = new Pager(joosRoute::href('blog_user', array('username' => $user->username)), $count, 5, 6);
		$pager->paginate($page);

		// опубликованные записи блога
		$blog_items = $blogs->get_list(
						array(
					'select' => "b.*,c.slug AS cat_slug, u.id AS userid, u.username AS username, comm.counter AS comments, votesresults.votes_count AS votesresults",
					'join' => 'AS b INNER JOIN #__blog_category AS c ON ( c.id=b.category_id AND c.state=1 ) INNER JOIN #__users AS u ON u.id=b.user_id LEFT JOIN #__comments_counter AS comm ON (comm.obj_option = "Blog" AND comm.obj_id = b.id) LEFT JOIN #__votes_blog_results AS votesresults ON ( votesresults.obj_id=b.id )',
					'where' => $state . ' AND b.user_id = ' . $user->id,
					'offset' => $pager->offset,
					'limit' => $pager->limit,
					'order' => 'id DESC', // сначала последние
						), array('controller', 'blog', 'user-' . $user->id, 'comments', 'rater')
		);

		// устанавливаем заголосов страницы
		joosDocument::instance()
				->set_page_title('Блоги');

		joosBreadcrumbs::instance()
				->add($user->username, joosRoute::href('user_view', array('username' => $user->username)))
				->add('Блог');

		return array(
			'blog_items' => $blog_items,
			'pager' => $pager,
			'task' => 'index',
			'core::modules' => array(
				'sidebar' => array(
					'users' => array('type' => 'profile_user', 'template' => 'profile_user', 'user' => $user)
				)
			)
		);
	}

	public static function add() {

		if (!User::current()->id) {
			joosRoute::redirect(JPATH_SITE);
		}

		$blog = new Blog;

		if ($_POST) {
			self::save_blog_item($blog);
		}

		return array(
			'item' => $blog,
			'validator' => BlogValidations::add(),
			'core::modules' => array(
				'sidebar' => array(
					'users' => array('type' => 'profile_user', 'template' => 'profile_user', 'user' => User::current())
				)
			)
		);
	}

	public static function edit() {

		if (!User::current()->id) {
			joosRoute::redirect(JPATH_SITE);
		}

		$blog = new Blog;

		(isset(self::$param['id']) && $blog->load(self::$param['id'])) ? null : self::error404();

		if ($_POST) {
			self::save_blog_item($blog);
		}

		$user = new User;
		$user->load($blog->user_id);

		return array(
			'item' => $blog,
			'validator' => BlogValidations::add(),
			'task' => 'add',
			'core::modules' => array(
				'sidebar' => array(
					'users' => array('type' => 'profile_user', 'template' => 'profile_user', 'user' => $user)
				)
			)
		);
	}

	public static function save_blog_item($blog) {

		//Выбирать категорию могут только админы
		// Для обычных пользователей - ID категории = 3
		$blog->category_id = (User::current()->gid == 8 || User::current()->gid == 9) ? joosRequest::post('category_id', 1) : 3;
		$blog->save($_POST);

		if ($blog->id) {
			$blog_category = new BlogCategory;
			$blog_category->load($blog->category_id);
			joosRoute::redirect(joosRoute::href('blog_view', array('id' => $blog->id, 'cat_slug' => $blog_category->slug)));
		}
		;
	}

}