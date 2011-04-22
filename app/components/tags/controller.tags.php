<?php

// запрет прямого доступа
defined('_JOOS_CORE') or die();

/**
 * Tags - Компонент управления тэгами
 * Контроллер сайта
 *
 * @version 1.0
 * @package Joostina.Components.Controllers
 * @subpackage Tags    
 * @author Joostina Team <info@joostina.ru>
 * @copyright (C) 2008-2011 Joostina Team
 * @license MIT License http://www.opensource.org/licenses/mit-license.php
 * Информация об авторах и лицензиях стороннего кода в составе Joostina CMS: docs/copyrights
 *
 * */
joosLoader::view('tags');

class actionsTags extends joosController {

	/**
	 * Вывод страниц тэгов
	 * @param <type> $option
	 * @param <type> $page
	 * @param <type> $task
	 * @param <type> $tag
	 */
	public static function index() {
		$tag = urldecode(self::$param['tag']);
		$page = isset(self::$param['page']) ? self::$param['page'] : 0;


		joosDocument::instance()->set_page_title($tag);

		$tags = new Tags;
		$search_results_nodes_count = $tags->search_count($tag);

		// постраничная навигация
		joosLoader::lib('paginator3000');
		$pager = new paginator3000(sefRelToAbs('index.php?option=com_tags&tag=' . $tag), $search_results_nodes_count, 10, 15);
		$pager->paginate($page);

		$tags_results = $tags->search($tag, '#__pages', $pager->offset, $pager->limit);

		tagsHTML::tag_search($tag, $tags_results, $pager);

		// считаем обращения к тэгу, но только на первой странице
		joosLoader::lib('jooshit', 'utils');
		($page == 0 OR $page == 1) ? joosHit::add('tags', 0, $tag) : null;
	}

	/**
	 * Формирование облака тэгов
	 */
	public static function cloud() {

		$tags = new Tags;
		$tag_arr = $tags->load_all();

		$tags_cloud = new tagsCloud($tag_arr);
		$tags_cloud = $tags_cloud->get_cloud('', 400);

		tagsHTML::full_cloud($tags_cloud);
	}

}