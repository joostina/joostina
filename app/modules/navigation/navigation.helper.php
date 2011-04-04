<?php

/**
 * Navigation - модуль меню
 * Вспомагательный класс
 *
 * @version 1.0
 * @package Joostina CMS
 * @subpackage Modules
 * @author JoostinaTeam
 * @copyright (C) 2008-2010 Joostina Team
 * @license see license.txt
 *
 * */
//Запрет прямого доступа
defined('_JOOS_CORE') or die();

class navigationHelper {

	//Отдаем пункты меню
	public static function get_items() {
		return array(
			'Главная' => array(
				'title' => '',
				'href' => '/',
			),
			'О нас' => array(
				'title' => '',
				'href' => '/about',
				'children' => array(
					'О нас' => array(
						'title' => '',
						'href' => '/about',
					),
					'Обратная связь' => array(
						'title' => '',
						'href' => joosRoute::href('contacts'),
					),
				)
			),
			'Новости' => array(
				'title' => '',
				'href' => '/news',
			),
			'Контакты' => array(
				'title' => '',
				'href' => '/contacts',
			)
		);
	}
	


}