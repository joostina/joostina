<?php

// запрет прямого доступа
defined('_JOOS_CORE') or die();

/**
 * News  - Компонент управления новостями
 * Контроллер сайта
 *
 * @version 1.0
 * @package Joostina.Components.Controllers
 * @subpackage News  
 * @author Joostina Team <info@joostina.ru>
 * @copyright (C) 2007-2011 Joostina Team
 * @license MIT License http://www.opensource.org/licenses/mit-license.php
 * Информация об авторах и лицензиях стороннего кода в составе Joostina CMS: docs/copyrights
 *
 * */
class actionsNews extends joosController {

	public static function action_before($active_task) {

		//Хлебные крошки
		joosBreadcrumbs::instance()
				->add('Новости', $active_task == 'index' ? false : joosRoute::href('news'));

		//Метаинформация страницы
		joosMetainfo::set_meta('news', '', '', array('title' => 'Новости'));
	}

	public static function index() {

		$page = isset(self::$param['page']) ? self::$param['page'] : 0;

		// формируем объект записей блога
		$news = new News();

		// число записей в блоге
		$count = $news->count('WHERE state = 1');

		$pager = new joosPager(joosRoute::href('news'), $count, 3, 5);
		$pager->paginate($page);

		// опубликованные записи блога
		$news_items = $news->get_list(
						array(
							'select' => '*',
							'offset' => $pager->offset,
							'limit' => $pager->limit,
							'where' => 'state = 1',
							'order' => 'id DESC', // сначала последние
						)
		);

		return array(
			'news_items' => $news_items,
			'pager' => $pager
		);
	}

	public static function archive() {

		$page = isset(self::$param['page']) ? self::$param['page'] : 0;
		$year = isset(self::$param['year']) ? self::$param['year'] : date('Y');

		// формируем объект записей блога
		$news = new News();

		$params = new joosParams;
		$params->group = 'news';
		$params->subgroup = 'default';
		$params->find();
		$params = json_decode($params->data, true);
		$years = explode(',', $params['archive_eyars']);

		$where = 'YEAR(created_at)=' . $year;

		// число записей в блоге
		$count = $news->count('WHERE state = 1 AND ' . $where);

		$pager = new joosPager(joosRoute::href('news_archive_year', array('year' => $year)), $count, 3, 5);
		$pager->paginate($page);

		// опубликованные записи блога
		$news_items = $news->get_list(
						array(
							'select' => '*',
							'where' => 'state = 1 AND ' . $where,
							'offset' => $pager->offset,
							'limit' => $pager->limit,
							'order' => 'id DESC', // сначала последние
						)
		);

		//Хлебные крошки
		joosBreadcrumbs::instance()
				->add('Архив новостей');
		JoosDocument::instance()
				->add_title('Архив новостей');

		return array(
			'news_items' => $news_items,
			'years' => $years,
			'year' => $year,
			'pager' => $pager
		);
	}

	public static function view() {

		// номер просматриваемой записи
		$id = self::$param['id'];

		// формируем и загружаем просматриваемую запись
		$item = new News;
		// новость доступна и опубликована
		($item->load($id) && $item->state == 1) ? null : self::error404();

		// хлебные крошки
		joosBreadcrumbs::instance()
				->add($item->title);

		//Метаинформация страницы
		joosMetainfo::set_meta('news', 'item', $item->id, array('title' => $item->title));

		// передаём параметры записи и категории в которой находится запись для оформления
		return array(
			'item' => $item
		);
	}

}