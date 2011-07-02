<?php

// запрет прямого доступа
defined('_JOOS_CORE') or die();

/**
 * Search   - Компонент поиска
 * Контроллер сайта
 *
 * @version 1.0
 * @package Joostina.Components.Controllers
 * @subpackage Search    
 * @author Joostina Team <info@joostina.ru>
 * @copyright (C) 2008-2011 Joostina Team
 * @license MIT License http://www.opensource.org/licenses/mit-license.php
 * Информация об авторах и лицензиях стороннего кода в составе Joostina CMS: docs/copyrights
 *
 * */
class actionsSearch extends joosController {

	/**
	 * Cтартовый метод, запускается до вызова основного метода контроллера
	 */
	public static function action_before($active_task) {

		//Хлебные крошки
		joosBreadcrumbs::instance()
				->add('Поиск', $active_task == 'index' ? false : joosRoute::href('search'));

		//Метаинформация страницы
		joosMetainfo::set_meta('search', '', '', array('title' => 'Поиск'));
	}

	/**
	 * Главная страница компонента
	 */
	public static function index() {

		$type = self::$param['slug'];

		$searchword = self::$param['searchword'];
		$searchword = self::name_prepare($searchword);

		if ($type == 'catalog') {
			$results = self::_search_catalog($searchword);
		} else {
			$results = self::_search_news($searchword);
		}

		return array(
			'template' => $type,
			'type' => $type,
			'searchword' => $searchword,
			'total' => $results['total'],
			'results' => $results['results'],
			'pager' => $results['pager']
		);
	}

	private static function _search_catalog($searchword) {

		$page = isset(self::$param['page']) ? self::$param['page'] : 0;

		$content = new Content;


		//Ищем по основному содержимому
		$_results = $content->get_list(array(
					'select' => 'c.*, ef.*, ef.value AS sku',
					'join' => 'LEFT JOIN #__extrafields_data AS ef ON ( (ef.obj_id = c.id AND ef.field_id = 2))',
					'where' => "LOWER(c.title) LIKE LOWER('%{$searchword}%')
						OR LOWER(c.fulltext) LIKE LOWER('%{$searchword}%')
						OR LOWER(ef.value) LIKE LOWER('%{$searchword}%') ",
					'group' => 'c.id',
					'pseudonim' => 'c'
				));
		$count = count($_results);

		// подключаем библиотеку постраничной навигации
		joosLoader::lib('pager', 'utils');
		$pager = new joosPager(joosRoute::href('search_process', array('slug' => 'catalog', 'searchword' => $searchword)), $count, 3, 5);
		$pager->paginate($page);

		$results = $content->get_list(array(
					'select' => 'c.*, ef.*, ef.value AS sku',
					'join' => 'LEFT JOIN #__extrafields_data AS ef ON ( (ef.obj_id = c.id AND ef.field_id = 2))',
					'where' => "LOWER(c.title) LIKE LOWER('%{$searchword}%')
						OR LOWER(c.fulltext) LIKE LOWER('%{$searchword}%')
						OR LOWER(ef.value) LIKE LOWER('%{$searchword}%') ",
					'offset' => $pager->offset,
					'limit' => $pager->limit,
					'group' => 'c.id',
					'pseudonim' => 'c'
				));

		return array('results' => $results, 'pager' => $pager, 'total' => $count);
	}

	private static function _search_news($searchword) {

		$page = isset(self::$param['page']) ? self::$param['page'] : 0;

		$content = new News;

		$where = "state = 1 AND LOWER(`title`) LIKE LOWER('%{$searchword}%')
						OR LOWER(`introtext`) LIKE LOWER('%{$searchword}%')
						OR LOWER(`fulltext`) LIKE LOWER('%{$searchword}%') ";

		$count = $content->count('WHERE ' . $where);

		// подключаем библиотеку постраничной навигации
		joosLoader::lib('pager', 'utils');
		$pager = new joosPager(joosRoute::href('search_process', array('slug' => 'news', 'searchword' => $searchword)), $count, 3, 5);
		$pager->paginate($page);

		//Ищем по основному содержимому
		$results = $content->get_list(array(
					'select' => '*',
					'where' => $where,
					'offset' => $pager->offset,
					'limit' => $pager->limit,
					'order' => 'id DESC'
				));

		return array('results' => $results, 'pager' => $pager, 'total' => $count);
	}

	private static function name_prepare($name) {

		$searchword = urldecode($name);

		//$searchword = str_ireplace(array('_', '&amp;', '&', ' feat ', ' and ', ' и ', '.', ','), ' ', $searchword);
		//$searchword = str_ireplace(array('_', '&amp;', '&', ' feat ', ' and ', ' и ', '.', ','), ' ', $searchword);
		// МЕГАМАГИЯ
		$searchword_new = mb_convert_encoding($searchword, 'utf-8', 'utf-8');
		$searchword = joosString::is_ascii($searchword_new) ? mb_convert_encoding($searchword, 'utf-8', 'cp1251') : $searchword_new;

		$searchword = str_replace(array('"', "'", '\\', '/'), '', $searchword);
		$searchword = joosString::trim(stripslashes($searchword));

		$searchword = htmlspecialchars($searchword, ENT_QUOTES, 'UTF-8');

		// удаляем дубли пробелов
		return preg_replace("#[\r\n\t\s]+#isu", " ", $searchword);
	}

}