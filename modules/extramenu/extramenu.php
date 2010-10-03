<?php

/**
 * @package Joostina
 * @copyright Авторские права (C) 2008 Joostina team. Все права защищены.
 * @license Лицензия http://www.gnu.org/licenses/gpl-2.0.htm GNU/GPL, или help/license.php
 * Joostina! - свободное программное обеспечение распространяемое по условиям лицензии GNU/GPL
 * Для получения информации о используемых расширениях и замечаний об авторском праве, смотрите файл help/copyright.php.
 */
// запрет прямого доступа
defined('_JOOS_CORE') or die();

require_once joosCore::path('extramenu', 'module_helper');

$menu = array(
	'Главная' => array(
		'title' => 'Главнее не бывает',
		'href' => '/',
		'image' => '', // на всякие случаИ
	),
	'Супер-аякс' => array(
		'title' => 'Реальне',
		'href' => '/ajaxdemo',
		'image' => '', // на всякие случаИ
	),
	'Блоги' => array(
		'title' => 'Главнее не бывает',
		'href' => '/blog',
		'children' => array(
			'Блоги пользователй' => array(
				'title' => 'Блоги всех',
				'href' => '/blog/users',
			),
			'Блоги сайта' => array(
				'title' => 'Главнее не бывает',
				'href' => '/blog/j',
			),
		)
	),
	'Новости' => array(
		'title' => 'Главнее не бывает',
		'href' => '/news',
		'children' => array(
			'Новости рас' => array(
				'title' => 'Тыц тыц',
				'href' => '/news/one',
			),
			'Новости два' => array(
				'title' => 'Да да',
				'href' => '/news/two',
			),
		)
	),
);

echo extramenuHelper::ul_li_recurse($menu);

echo JHTML::css_file(JPATH_SITE . '/modules/extramenu/media/css/ullidropdown.css');