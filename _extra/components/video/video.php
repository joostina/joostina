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

joosLoader::model('video');

class actionsVideo extends joosController {

	public static function index() {

		$items = null;
		$pager = null;

		Jbreadcrumbs::instance()
				->add('Музыкальные клипы');

		joosDocument::instance()
				->set_page_title('Видео');

		return array(
			'items' => $items,
			'pager' => $pager,
			'core::modules' => array(
				'sidebar' => array(
					'search_music' => array(),
					'hits' => array()
				)
			)
		);
	}

}