<?php

// запрет прямого доступа
defined('_JOOS_CORE') or die();

/**
 * Test  - Компонент для тестирования нового функционала
 * Контроллер панели управления
 *
 * @joostina_admin_menu Тестовая функция. Показывает вариант использования функции joosText::declension
 * @joostina_admin_menu_acl 8,9,10,11. доступно для группа с номерами 8,9,10,11
 * 
 * @version 1.0
 * @package Joostina.Components.Controllers
 * @subpackage Test     
 * @author Joostina Team <info@joostina.ru>
 * @copyright (C) 2008-2011 Joostina Team
 * @license MIT License http://www.opensource.org/licenses/mit-license.php
 * Информация об авторах и лицензиях стороннего кода в составе Joostina CMS: docs/copyrights
 *
 * */
class actionsAdminTest {

	/**
	 * 
	 * 
	 * @joostina_admin_menu Начало как бэ. Отсюда запускается контроллер
	 */
	public static function index() {

		$class = '';
		if (strpos($class, '_') > 0) {
			$class_names = explode('_', $class);
			echo $class = $class_names[1];
			die();
		}
		
		$reflection = new ReflectionClass(__CLASS__);

		echo '<pre>';
		echo 'Файл: ' . $reflection->getFileName();
		echo '<br />';
		echo 'Начинается со строки: ' . $reflection->getStartLine();
		echo '<br />';
		echo 'Заканчивается на строке: ' . $reflection->getEndLine();
		echo '<br />';
		echo 'Описание класса: ' . $reflection->getDocComment();
		echo '<br />';
		echo 'Содержит методы: ' . implode(', ', $reflection->getMethods());
		echo '<br />';
		echo 'Содержит интерфейсы: ' . $reflection->getParentClass();
	}

	/**
	 * Вариант использования joosText::declension
	 * 
	 * @example joosText::declension( 5, array( 'товар', 'товара', 'товаров') );
	 * @example joosText::declension( 123, array( 'человек', 'человека', 'людей') );
	 * 
	 * @example_for joosText::declension
	 * @example_class joosText
	 * @example_function declension
	 * @param string $baseURL Base URL to be appended to the page number
	 * @param int $totalItems Total items to be paginate
	 * @param int $itemPerPage Items to be shown in one page.
	 * @param int $maxlength Number of links for the pager navigation
	 * @param string $prevText joosText for the Previous button link
	 * @param string $nextText joosText for the Next button link
	 * 
	 * @joostina_admin_menu Тестовая функция. Показывает вариант использования функции joosText::declension
	 * @joostina_admin_menu_acl 8,9,10. доступно для группа с номерами 8,9,10
	 */
	public static function test_function() {
		$users = joosDatabase::instance()
				->set_query('select count(id) from #__users')
				->load_result();

		$users_count_string = joosText::declension($users, array('пользователь', 'пользователя', 'пользователей'));
		echo sprintf('На сайте зарегестрированно %s %s', $users, $users_count_string);
	}

	// так можно расширять меню компонента панели управления
	public static function get_menu() {

		$documentation_array = array();

		$reflection = new ReflectionClass(__CLASS__);

		foreach ($reflection->getMethods() as $method) {

			echo $method->getName();

			$matches = array();
			preg_match_all('/@joostina_admin_menu+(.*)[.]+(.*)$/Um', $method, $matches);

			if (!empty($matches[0])) {
				foreach ($matches[0] as $i => $row) {
					$documentation_array['joostina_admin_menu'][$method->getName()] = array('title' => $matches[1][$i], 'description' => $matches[2][$i]);
				}
			}
		}
	}

}