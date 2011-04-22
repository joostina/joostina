<?php

/**
 * Job - Компонент вакансий
 * Аякс-контроллер админ-панели
 *
 * @version 1.0
 * @package Joostina.Components
 * @subpackage Job
 * @author Joostina Team <info@joostina.ru>
 * @copyright (C) 2008-2011 Joostina Team
 * @license MIT License http://www.opensource.org/licenses/mit-license.php
 * Информация об авторах и лицензиях стороннего кода в составе Joostina CMS: docs/copyrights
 *
 * */
// запрет прямого доступа
defined('_JOOS_CORE') or die();

joosAutoAdmin::dispatch_ajax();
joosAutoAdmin::autoajax();

class actionsAjaxFaq {

	public static function on_start() {
		require joosCore::path('faq', 'admin_class');
	}

	public static function index() {
		
	}

}