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

joosLoader::model('pages');

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
		Metainfo::set_meta('news', 'item', $page->id, array('title'=>$page->title));

		Jbreadcrumbs::instance()
				->add($page->title);

		// передаём параметры записи и категории в которой находится запись для оформления
		return array(
			'page' => $page
		);
	}

}