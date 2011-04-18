<?php

/**
 *
 * */
// запрет прямого доступа
defined('_JOOS_CORE') or die();

class adminSitemap extends Sitemap {

	public function get_tableinfo() {
		return array(
			'header_main' => 'Карта сайта'
		);
	}

	public function get_extrainfo() {
		return array(
		);
	}

}