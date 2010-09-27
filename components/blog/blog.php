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

require joosCore::path('blog', 'class');

class actionsBlog extends Jcontroller {

	public static function index() {

		$page = isset(self::$param['page']) ? self::$param['page'] : 0;

		// формируем объект записей блога
		$blogs = new Blog();

		// число записей в блоге
		$count = $blogs->count('WHERE state=1');

		// подключаем библиотеку постраничной навигации
		mosMainFrame::addLib('pager');
		$pager = new Pager(Jroute::href('blog'), $count, 5, 6);
		$pager->paginate($page);

		// опубликованные записи блога
		$blog_items = $blogs->get_list(
						array(
							'select' => "b.*,c.slug as cat_name",
							'join' => 'as b INNER JOIN #__blog_category AS c ON ( c.id=b.category_id AND c.state=1 )',
							'where' => 'b.state=1',
							'offset' => $pager->offset,
							'limit' => $pager->limit,
							'order' => 'id DESC', // сначала последние
						)
		);

		return array(
			'blog_items' => $blog_items,
			'pager' => $pager
		);
	}

	public static function category() {
		// текущая страница
		$page = isset(self::$param['page']) ? self::$param['page'] : 0;
		// slug-название категории которой принадлежит текущая запись
		$cat_name = self::$param['cat_name'];

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
		mosMainFrame::addLib('pager');
		$pager = new Pager(Jroute::href('blog_cat', array('cat_name' => $blog_category->slug)), $count, 5, 6);
		$pager->paginate($page);

		// опубликованные записи блога
		$blog_items = $blogs->get_list(
						array(
							'select' => "b.*,c.slug as cat_name",
							'join' => 'as b INNER JOIN #__blog_category AS c ON ( c.id=b.category_id AND c.state=1 )',
							'where' => 'b.state=1 AND b.category_id=' . $blog_category->id,
							'offset' => $pager->offset,
							'limit' => $pager->limit,
							'order' => 'id DESC', // сначала последние
						)
		);

		return array(
			'blog_category' => $blog_category,
			'blog_items' => $blog_items,
			'pager' => $pager
		);
	}

	public static function view() {

		// номер просматриваемой записки
		$id = self::$param['id'];
		// slug-название категории которой принадлежит текущая запись
		$cat_name = self::$param['cat_name'];

		// формируем и загружаем просматриваемую запись
		$blog = new Blog;
		$blog->load($id) ? null : self::error404();

		// формируем и загружаем категорпию к которой принадлежит просматриваемая запись
		$blog_category = new BlogCategory;
		$blog_category->load($blog->category_id) ? null : self::error404();

		// проверяем что в ссылки на текущаю запись название slug-категории соответствует реальным данным этой категории
		$blog_category->slug == $cat_name ? null : self::error404();

		// одно из вышеобозначенных действий зафиксировало ошибку, прекращаем работу
		if (self::$error) {
			return;
		}

		// устанавливаем заголосов страницы
		Jdocument::getInstance()
				->setPageTitle($blog->title);

		require_once joosCore::path('tags', 'class');
		$tags = new Tags;

		require_once joosCore::path('comments', 'class');
		$comments = new Comments;

		// передаём параметры записи и категории в которой находится запись для оформления
		return array(
			'blog' => $blog,
			'blog_category' => $blog_category,
			'comments' => $comments,
			'tags' => $tags,
		);
	}

	public static function users_blog() {
		_xdump(self::$param);
		echo 'Просомтр записей блога по всем пользователям';
	}

	public static function user_blog() {
		_xdump(self::$param);
		echo 'Просомтр записей блога по конкретному пользователю';
	}

}