<?php

/**
 * @package Joostina
 * @copyright Авторские права (C) 2008-2010 Joostina team. Все права защищены.
 * @license Лицензия http://www.gnu.org/licenses/gpl-2.0.htm GNU/GPL, или help/license.php
 * Joostina! - свободное программное обеспечение распространяемое по условиям лицензии GNU/GPL
 * Для получения информации о используемых расширениях и замечаний об авторском праве, смотрите файл help/copyright.php.
 */
// запрет прямого доступа
defined('_JOOS_CORE') or die();

return array(
	//'default' => array('href' => '', 'action' => 'mainpage', 'task' => 'index'),
	//'content' => array('href' => 'content', 'action' => 'content', 'task' => 'index'),
	'default' => array(
		'href' => '',
		'defaults' => array('controller' => 'mainpage', 'action' => 'index')
	),
	'login' => array(
		'href' => 'login',
		'defaults' => array('controller' => 'users', 'action' => 'login')
	),
	'lostpassword' => array(
		'href' => 'lostpassword',
		'defaults' => array('controller' => 'users', 'action' => 'lostpassword')
	),
	'register' => array(
		'href' => 'register',
		'defaults' => array('controller' => 'users', 'action' => 'register')
	),
	'user_view_by_login' => array(
		'href' => 'user/view-<id>/<username>',
		'params_rules' => array('id' => '\d+', 'username' => '\w+'),
		'defaults' => array('controller' => 'users', 'action' => 'view')
	),
	'contacts' => array(
		'href' => 'feedback',
		'defaults' => array('controller' => 'contacts', 'action' => 'index')
	),
	'test' => array(
		'href' => 'test',
		'defaults' => array('controller' => 'test', 'action' => 'index')
	),
);
