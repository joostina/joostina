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

require_once joosCore::path('pages', 'class');

class actionsPages extends Jcontroller {

	public static function index() {

		//$menu = mosMainFrame::getInstance()->get('menu');
		//$params = new mosParameters($menu->params);

		$page = new Pages();
		//$page->load( $id ? $id : $params->get('page_id',0) );
		$page->load(1);

		Jdocument::getInstance()
				->setPageTitle($page->title_page)
				->addMetaTag('description', $page->meta_description)
				->addMetaTag('keywords', $page->meta_keywords)
				->seotag('yandex-vf1', md5(time())) // формируем тэг для поисковой машины Yandex.ru ( пример )
				->seotag('rating', false); // тэг rating - скрываем
		// если для текущего действия аквирован счетчик хитов - то обновим его
		mosMainFrame::addLib('jhit');
		Jhit::add('pages', $page->id, 'view');

		return array(
			'task' => 'view',
			'page' => $page
		);
	}

	public static function view_by_id() {
		$id = self::$param['id'];

		$page = new Pages;
		$page->load($id) ? null : self::error404();

		// одно из вышеобозначенных действий зафиксировало ошибку, прекращаем работу
		if (self::$error) {
			return;
		}

		Jdocument::getInstance()
				->setPageTitle($page->title_page)
				->addMetaTag('description', $page->meta_description)
				->addMetaTag('keywords', $page->meta_keywords);

		return array(
			'task' => 'view',
			'page' => $page
		);
	}

	public static function view_by_slug() {
		$slug = self::$param['page_name'];

		$page = new Pages;
		$page->slug = $slug;
		$page->find() ? null : self::error404();

		// одно из вышеобозначенных действий зафиксировало ошибку, прекращаем работу
		if (self::$error) {
			return;
		}

		Jdocument::getInstance()
				->setPageTitle($page->title_page)
				->addMetaTag('description', $page->meta_description)
				->addMetaTag('keywords', $page->meta_keywords);

		return array(
			'task' => 'view',
			'page' => $page
		);
	}

}