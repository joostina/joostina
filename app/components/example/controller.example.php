<?php

// запрет прямого доступа
defined('_JOOS_CORE') or die();

/**
 * actionsExample  - Компонент для тестированного собранного функционала системных функций через реальные примеры вызова
 * Контроллер сайта
 *
 * @version 1.0
 * @package Joostina.Components.Controllers
 * @author Joostina Team <info@joostina.ru>
 * @copyright (C) 2008-2011 Joostina Team
 * @license MIT License http://www.opensource.org/licenses/mit-license.php
 * Информация об авторах и лицензиях стороннего кода в составе Joostina CMS: docs/copyrights
 *
 * */
class actionsExample {

	public static function index() {

		$results = array();

// joosCore::path ************************************************************
		// тестирование joosCore::path
		$results['joosCore::path'] = array(
			"joosCore::path('example', 'controller');" => joosCore::path('example', 'controller'),
			"joosCore::path('example', 'admin_controller');" => joosCore::path('example', 'admin_controller'),
			"joosCore::path('example', 'ajax_controller');" => joosCore::path('example', 'ajax_controller'),
			"joosCore::path('example', 'model');" => joosCore::path('example', 'model'),
			"joosCore::path('example', 'admin_model');" => joosCore::path('example', 'admin_model'),
			"joosCore::path('example', 'admin_template_html');" => joosCore::path('example', 'admin_template_html'),
			"joosCore::path('example', 'view','index');" => joosCore::path('example', 'view', 'index'),
			"joosCore::path('example', 'admin_view','index');" => joosCore::path('example', 'admin_view', 'index'),
			"joosCore::path('example', 'module_helper');" => joosCore::path('example', 'module_helper'),
			"joosCore::path('example', 'module_admin_helper');" => joosCore::path('example', 'module_admin_helper'),
			"joosCore::path('example', 'lib');" => joosCore::path('example', 'lib'),
			"joosCore::path('example', 'lib-vendor','vendor_name');" => joosCore::path('example', 'lib-vendor', 'vendor_name'),
		);

		// для красивости закроем абсолютные пути
		foreach ($results['joosCore::path'] as &$_t) {
			$_t = str_replace(JPATH_BASE, '[JPATH_BASE]', $_t);
		}

// joosCore::path //************************************************************

// joosLoader ************************************************************

		$results['joosLoader'] = array(
			"joosLoader::model('example');" => joosLoader::model('example'),
			"joosLoader::admin_model('example');" => joosLoader::admin_model('example'),
			"joosLoader::view('example','example');" => joosLoader::view('example', 'example_view'),
			"joosLoader::admin_view('example');" => joosLoader::admin_view('example', 'example_view'),
			"joosLoader::admin_template_view('example');" => joosLoader::admin_template_view('example'),
			"joosLoader::controller('example');" => joosLoader::controller('example'),
			"joosLoader::admin_controller('example');" => joosLoader::admin_controller('example'),
			"joosLoader::lib('example');" => joosLoader::lib('example'),
			"joosLoader::lib('example','example');" => joosLoader::lib('example', 'example'),
		);

// joosLoader //************************************************************

		return array(
			'results' => $results
		);
	}

}