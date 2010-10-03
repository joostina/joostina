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

require joosCore::path('news', 'class');

class actionsNews extends Jcontroller {

	public static function index() {

		$page = isset(self::$param['page']) ? self::$param['page'] : 0;

		// формируем объект записей блога
		$news = new News();

		// число записей в блоге
		$count = $news->count();

		// подключаем библиотеку постраничной навигации
		mosMainFrame::addLib('pager');
		$pager = new Pager(Jroute::href('news'), $count, 5, 6);
		$pager->paginate($page);

		// опубликованные записи блога
		$news_items = $news->get_list(
						array(
							'select' => '*',
							'offset' => $pager->offset,
							'limit' => $pager->limit,
							'order' => 'id DESC', // сначала последние
						)
		);

		return array(
			'news_items' => $news_items,
			'pager' => $pager
		);
	}

	private static function list_by_type_id($type_id, $route_name) {
		$page = isset(self::$param['page']) ? self::$param['page'] : 0;

		// формируем объект записей блога
		$news = new News();

		// число записей в блоге
		$count = $news->count('WHERE type_id=' . $type_id);

		// подключаем библиотеку постраничной навигации
		mosMainFrame::addLib('pager');
		$pager = new Pager(Jroute::href($route_name), $count, 5, 6);
		$pager->paginate($page);

		// опубликованные записи блога
		$news_items = $news->get_list(
						array(
							'select' => '*',
							'where' => 'type_id=' . $type_id,
							'offset' => $pager->offset,
							'limit' => $pager->limit,
							'order' => 'id DESC', // сначала последние
						)
		);

		return array(
			'task' => 'index',
			'news_items' => $news_items,
			'pager' => $pager
		);
	}

	// новости1
	public static function one() {
		return self::list_by_type_id(1, 'news_one');
	}

	// новости2
	public static function two() {
		return self::list_by_type_id(2, 'news_two');
	}

	public static function view() {

		// номер просматриваемой записки
		$id = self::$param['id'];

		// формируем и загружаем просматриваемую запись
		$new = new News;
		$new->load($id) ? null : self::error404();

		// одно из вышеобозначенных действий зафиксировало ошибку, прекращаем работу
		if (self::$error) {
			return;
		}

		// устанавливаем заголосов страницы
		Jdocument::getInstance()
				->setPageTitle($new->title);

		// передаём параметры записи и категории в которой находится запись для оформления
		return array(
			'new' => $new,
		);
	}

}