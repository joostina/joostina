<?php

/**
 * @package   Joostina
 * @copyright Авторские права (C) 2008-2010 Joostina team. Все права защищены.
 * @license   Лицензия http://www.gnu.org/licenses/gpl-2.0.htm GNU/GPL, или help/license.php
 * Joostina! - свободное программное обеспечение распространяемое по условиям лицензии GNU/GPL
 * Для получения информации о используемых расширениях и замечаний об авторском праве, смотрите файл help/copyright.php.
 */
// запрет прямого доступа
defined('_JOOS_CORE') or die();

return array(
	'default' => array(
		'href' => '',
		'defaults' => array(
			'controller' => 'pages',
			'action' => 'index'
		)
	),
	'login' => array(
		'href' => 'login',
		'defaults' => array(
			'controller' => 'users',
			'action' => 'login'
		)
	),
	'logout' => array(
		'href' => 'logout',
		'defaults' => array(
			'controller' => 'users',
			'action' => 'logout'
		)
	),
	'lostpassword' => array(
		'href' => 'lostpassword',
		'defaults' => array(
			'controller' => 'users',
			'action' => 'lostpassword'
		)
	),
	'register' => array(
		'href' => 'register',
		'defaults' => array(
			'controller' => 'users',
			'action' => 'register'
		)
	),
	'user_view' => array(
		'href' => 'user/view-<id>/<user_name>',
		'params_rules' => array(
			'id' => ':digit',
			'user_name' => '\w+'
		),
		'defaults' => array(
			'controller' => 'users',
			'action' => 'view'
		)
	),
	'test' => array(
		'href' => 'test',
		'defaults' => array(
			'controller' => 'test',
			'action' => 'index')
	),
	'test-upload' => array(
		'href' => 'upload',
		'defaults' => array(
			'controller' => 'test',
			'action' => 'upload')
	),
	// сборник тестиования системных функций
	'unittest' => array(
		'href' => 'unittest',
		'defaults' => array(
			'controller' => 'example',
			'action' => 'index'
		)
	),
	/* Компонент блогов */
	'blog' => array(
		'href' => 'blogs',
		'defaults' => array(
			'controller' => 'blog',
			'action' => 'index'
		)
	),
	'blog_view' => array(
		'href' => 'blogs/<cat_slug>/<id>',
		'params_rules' => array(
			'id' => ':digit',
			'cat_slug' => '[a-z]+'
		),
		'defaults' => array(
			'controller' => 'blog',
			'action' => 'view'
		)
	),
	'blog_edit' => array(
		'href' => 'blogs/edit/<id>',
		'params_rules' => array(
			'id' => ':digit'
		),
		'defaults' => array(
			'controller' => 'blog',
			'action' => 'view'
		)
	),
	'blog_cat' => array(
		'href' => 'blogs/<cat_slug>',
		'params_rules' => array(
			'cat_slug' => '[a-z]+'),
		'defaults' => array(
			'controller' => 'blog',
			'action' => 'category'
		)
	),
	'blog_pages' => array(
		'href' => 'blogs/page/<page>',
		'params_rules' => array(
			'page' => ':digit'
			),
		'defaults' => array(
			'controller' => 'blog',
			'action' => 'index'
		)
	),
	/* Компонент страниц */
	'pages' => array(
		'href' => 'pages',
		'defaults' => array(
			'controller' => 'pages',
			'action' => 'index'
		)
	),
	'pages_view' => array(
		'href' => '<page_name>',
		'params_rules' => array(
			'page_name' => '[a-z0-9\_\-\.]+'),
		'defaults' => array(
			'controller' => 'pages',
			'action' => 'view'
		)
	),
	'test_db' => array(
		'href' => 'test/db',
		'defaults' => array(
			'controller' => 'test',
			'action' => 'db'
		)
	),
	'test_m' => array(
		'href' => 'user/<user_name>',
		'params_rules' => array(
			'user_name' => ':any'
		),
		'defaults' => array(
			'controller' => 'users',
			'action' => 'view'
		)
	),
);
