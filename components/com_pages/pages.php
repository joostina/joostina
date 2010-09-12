<?php

/**
 * @package Joostina
 * @copyright Авторские права (C) 2007-2010 Joostina team. Все права защищены.
 * @license Лицензия http://www.gnu.org/licenses/gpl-2.0.htm GNU/GPL, или help/license.php
 * Joostina! - свободное программное обеспечение распространяемое по условиям лицензии GNU/GPL
 * Для получения информации о используемых расширениях и замечаний об авторском праве, смотрите файл help/copyright.php.
 */
// запрет прямого доступа
defined('_VALID_MOS') or die();

require_once ($mainframe->getPath('front_html'));
require_once ($mainframe->getPath('class'));

class actionsPages extends Jcontroller {

	public static function index($option, $id = 1) {

		//$menu = mosMainFrame::getInstance()->get('menu');
		//$params = new mosParameters($menu->params);

		$page = new Pages();
		//$page->load( $id ? $id : $params->get('page_id',0) );
		$page->load( $id ? $id : 1 );

		Jdocument::getInstance()
				->setPageTitle($page->title_page)
				->addMetaTag('description', $page->meta_description)
				->addMetaTag('keywords', $page->meta_keywords)
				->seotag('yandex-vf1', md5(time())) // формируем тэг для поисковой машины Yandex.ru ( пример )
				->seotag('rating', false); // тэг rating - скрываем

		pagesHTML::index($page);

		// если для текущего действия аквирован счетчик хитов - то обновим его
		mosMainFrame::addLib('jhit');
		Jhit::add('pages', $page->id, 'view');
	}

	public static function blog($option, $id, $page, $task) {
		$obj = new Pages;
		$obj_count = $obj->count('WHERE state=1');

		mosMainFrame::addLib('pager');
		$pager = new Pager(sefRelToAbs('index.php?option=com_pages&task=blog', true), $obj_count, 2, 5, '&larr;', '&rarr;');
		$pager->paginate($page);

		$param = array(
			'select' => 'id,title',
			'where' => 'state=1',
			'offset' => $pager->offset,
			'limit' => $pager->limit,
			'order' => 'id DESC'
		);
		$obj_list = $obj->get_list($param);
		/*
		  echo $pager->output;
		  echo $pager->defaultCss;
		  echo $pager->components['jump_menu'];

		  foreach ($obj_list as $obj) {
		  echo $obj->title.'<br />';
		  }
		 */
	}

	
	public static function view( $option, $id ){
		self::index($option, $id);
	}

}