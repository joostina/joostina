<?php
namespace shozu;
/**
 * Observer
 *
 * Observe / Notify events
 */
final class Observer
{
    private static $events = array(); // events callback

    /**
     * Observe event
     *
     * <code>
     * Observer::observe('system.shutdown', array('Profiler', 'display'));
     * </code>
     *
     * @param string
     * @param mixed
     */
    public static function observe($name, $callback)
    {
        if (!isset(self::$events[$name]))
        {
            self::$events[$name] = array();
        }
        self::$events[$name][] = $callback;
    }

    /**
     * Detach a callback to an event queue.
     */
    public static function clear($name, $callback=false)
    {
        if ( ! $callback)
        {
            self::$events[$name] = array();
        }
        else if (isset(self::$events[$name]))
        {
            foreach (self::$events[$name] as $i => $event_callback)
            {
                if ($callback === $event_callback)
                {
                    unset(self::$events[$name][$i]);
                }
            }
        }
    }

    public static function get($name)
    {
        return empty(self::$events[$name]) ? array(): self::$events[$name];
    }

    /**
     * Notify event
     *
     * <code>
     * Observer::notify('system.execute');
     * </code>
     *
     * @param string
     */
    public static function notify($name)
    {
        // removing event name from the arguments
        $args = func_num_args() > 1 ? array_slice(func_get_args(), 1): array();

        foreach (self::get($name) as $callback)
        {
            //if(is_callable($callback))
            //{
                call_user_func_array($callback, $args);
            //}
        }
    }
}
