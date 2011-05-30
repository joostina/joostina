<?php

class Event extends ArrayObject {

	public function __invoke() {
		foreach ($this as $callback)
			call_user_func_array($callback, func_get_args());
	}

}

class Eventable {

	public function __call($name, $args) {
		if (method_exists($this, $name))
			call_user_func_array(array(&$this, $name), $args);
		else
		if (isset($this->{$name}) && is_callable($this->{$name}))
			call_user_func_array($this->{$name}, $args);
	}

}

class joosPlugins {

	private static $events = array();

	/**
	 * Добавление функции в общий список обработки
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
	 * @param string $events_name название обытия
	 */
	public static function fire_events($events_name) {

		// задач на собыьтие не создано
		if (!isset(self::$events[$events_name])) {
			return false;
		}

		// задачи на событие есть - выполняем их поочередно
		foreach (self::$events[$events_name] as $event) {
			
		}
	}

}