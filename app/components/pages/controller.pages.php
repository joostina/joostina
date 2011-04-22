<?php

// запрет прямого доступа
defined('_JOOS_CORE') or die();

/**
 * Pages - Компонент управления независимыми страницами
 * Контроллер сайта
 *
 * @version 1.0
 * @package Joostina.Components.Controllers
 * @subpackage Pages    
 * @author Joostina Team <info@joostina.ru>
 * @copyright (C) 2008-2011 Joostina Team
 * @license MIT License http://www.opensource.org/licenses/mit-license.php
 * Информация об авторах и лицензиях стороннего кода в составе Joostina CMS: docs/copyrights
 *
 * */
class actionsPages extends joosController {

	public static function index() {

		$page = new Pages();
		$page->load(1);

		joosDocument::instance()
				->set_page_title($page->title_page ? $page->title_page : $page->title)
				->add_meta_tag('description', $page->meta_description)
				->add_meta_tag('keywords', $page->meta_keywords)
				->seo_tag('yandex-vf1', md5(time())) // формируем тэг для поисковой машины Yandex.ru ( пример )
				->seo_tag('rating', false); // тэг rating - скрываем
		//
        // если для текущего действия аквирован счетчик хитов - то обновим его
		joosLoader::lib('jooshit', 'utils');
		joosHit::add('pages', $page->id, 'view');

		return array(
			'task' => 'view',
			'page' => $page
		);
	}

	public static function view() {

		$slug = self::$param['page_name'];

		$page = new Pages;
		$page->slug = $slug;
		$page->find() ? null : self::error404();

		// одно из вышеобозначенных действий зафиксировало ошибку, прекращаем работу
		if (self::$error) {
			return;
		}

		//Метаинформация страницы
		joosMetainfo::set_meta('news', 'item', $page->id, array('title' => $page->title));

		joosBreadcrumbs::instance()
				->add($page->title);

		// передаём параметры записи и категории в которой находится запись для оформления
		return array(
			'page' => $page
		);
	}

}