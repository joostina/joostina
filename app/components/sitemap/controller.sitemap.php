<?php

// запрет прямого доступа
defined('_JOOS_CORE') or die();

/**
 * Sitemap - Компонент генерации карты сайта
 * Контроллер сайта
 *
 * @version 1.0
 * @package Joostina.Components.Controllers
 * @subpackage Sitemap    
 * @author Joostina Team <info@joostina.ru>
 * @copyright (C) 2007-2011 Joostina Team
 * @license MIT License http://www.opensource.org/licenses/mit-license.php
 * Информация об авторах и лицензиях стороннего кода в составе Joostina CMS: docs/copyrights
 *
 * */
class actionsSitemap {

	public static function action_before($active_task) {
		//Хлебные крошки
		joosBreadcrumbs::instance()
				->add('Карта сайта');

		//Метаинформация страницы
		joosMetainfo::set_meta('sitemap', '', '', array('title' => 'Карта сайта'));
	}

	public static function index() {

		$map = Sitemap::get_map();

		return array(
			'map' => $map
		);
	}

}