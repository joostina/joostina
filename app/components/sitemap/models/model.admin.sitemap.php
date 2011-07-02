<?php

// запрет прямого доступа
defined('_JOOS_CORE') or die();

/**
 * adminSitemap - Модель компонента управления картой сайта
 * Модель панели управления
 *
 * @version 1.0
 * @package Joostina.Models
 * @subpackage Sitemap
 * @author Joostina Team <info@joostina.ru>
 * @copyright (C) 2008-2011 Joostina Team
 * @license MIT License http://www.opensource.org/licenses/mit-license.php
 * Информация об авторах и лицензиях стороннего кода в составе Joostina CMS: docs/copyrights
 *
 * */
class adminSitemap extends Sitemap {

	public function get_tableinfo() {
		return array(
			'header_main' => 'Карта сайта'
		);
	}

}