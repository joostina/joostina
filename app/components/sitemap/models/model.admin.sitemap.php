<?php

// запрет прямого доступа
defined( '_JOOS_CORE' ) or die();

/**
 * modelAdminSitemap - Модель компонента управления картой сайта
 * Модель панели управления
 *
 * @version    1.0
 * @package    Components\Sitemap
 * @subpackage Models\Admin
 * @author     Joostina Team <info@joostina.ru>
 * @copyright  (C) 2007-2012 Joostina Team
 * @license    MIT License http://www.opensource.org/licenses/mit-license.php
 * Информация об авторах и лицензиях стороннего кода в составе Joostina CMS: docs/copyrights
 *
 * */
class modelAdminSitemap extends midelSitemap {

	public function get_tableinfo() {
		return array ( 'header_main' => 'Карта сайта' );
	}

}