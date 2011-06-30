<?php

// запрет прямого доступа
defined('_JOOS_CORE') or die();

joosEvents::add_events('system.onstart', function($a, $b) {
			echo sprintf('1. a=%s; $b=%s', $a, $b);
		});

joosEvents::add_events('system.onstart', function($a, $b) {
			echo sprintf('2. a=%s; $b=%s', $a, $b);
		});

joosEvents::add_events('system.onstart', 'absd');

joosEvents::add_events('system.onstart', 'actionsTest::viewtest');

joosEvents::fire_events('system.onstart', 1, 2);

/**
 * joosEvents - Библиотека работы с плагинами, реализация метода Observer
 * Системная библиотека
 *
 * @version 1.0
 * @package Joostina.Libraries
 * @subpackage Libraries
 * @subpackage joosEvents
 * @category Libraries
 * @author Joostina Team <info@joostina.ru>
 * @copyright (C) 2008-2011 Joostina Team
 * @license MIT License http://www.opensource.org/licenses/mit-license.php
 * Информация об авторах и лицензиях стороннего кода в составе Joostina CMS: docs/copyrights
 *
 * */
class joosEvents {

	private static $events = array();

	/**
	 * Добавление функции в общий список обработки
	 * 
	 * @param string $events_name название обытия
	 * @param mixed $function задача
	 */
	public static function add_events($events_name, $function) {
		// если массива для списка задач на событие не создано - создаём его
		if (!isset(self::$events[$events_name])) {
			self::$events[$events_name] = array();
		}
		self::$events[$events_name][] = $function;
	}

	/**
	 * Вызов задач повешанных на событие
	 * 
	 * @param string $events_name название обытия
	 */
	public static function fire_events($events_name) {

		// задач на собыьтие не создано
		if (!isset(self::$events[$events_name])) {
			return false;
		}

		// задачи на событие есть - выполняем их поочередно
		foreach (self::$events[$events_name] as $event) {

			if (is_callable($event)) {
				call_user_func_array($event, func_get_args());
			}
		}
	}

}

function absd() {
	echo 555;
}

