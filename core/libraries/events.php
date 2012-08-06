<?php defined('_JOOS_CORE') or exit;

/*
  joosEvents::add_events('system.onstart', function($a, $b) {
  echo sprintf('1. a=%s; $b=%s', $a, $b);
  });

  joosEvents::add_events('system.onstart', function($a, $b) {
  echo sprintf('2. a=%s; $b=%s', $a, $b);
  });

  joosEvents::add_events('system.onstart', 'absd');

  joosEvents::add_events('system.onstart', 'actionsTest::viewtest');

  joosEvents::fire_events('system.onstart', 1, 2);

 */

/**
 * Работа с плагинами, реализация метода Observer
 *
 * @version    1.0
 * @package    Core\Libraries
 * @subpackage Events
 * @category   Libraries
 * @author     Joostina Team <info@joostina.ru>
 * @copyright  (C) 2007-2012 Joostina Team
 * @license    MIT License http://www.opensource.org/licenses/mit-license.php
 * Информация об авторах и лицензиях стороннего кода в составе Joostina CMS: docs/copyrights
 *
 * @todo       добавить возможность указывать файл, который будет непосредственно подключаться при наступлении события, в нём будет проверяться запрашиваемя функция и возможность её выполнения
 * */
class joosEvents
{
    private static $events = array();

    /**
     * Добавление функции в общий список обработки
     *
     * @param string $events_name название обытия
     * @param mixed  $function    задача
     */
    public static function add_events($events_name, $function)
    {
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
    public static function fire_events($events_name)
    {
        // задач на собыьтие не создано
        if (!isset(self::$events[$events_name])) {
            return false;
        }

        // задачи на событие есть - выполняем их поочередно
        foreach (self::$events[$events_name] as $event) {

            if (is_callable($event)) {
                JDEBUG ? joosDebug::add(sprintf('Запускаем обработку события %s', $events_name)) : null;
                call_user_func_array($event, func_get_args());
            }
        }
    }

    /**
     * Проверка наличия созданных задач на событие
     *
     * @param string $events_name название обытия
     *
     * @return bool результат наличия событий
     */
    public static function has_events($event_name)
    {
        joosDebug::log('Проверка наличия событий :event_name', array(':event_name' => $event_name));

        return (isset(self::$events[$event_name]) && count(self::$events[$event_name]) > 0);
    }

}
