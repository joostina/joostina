<?php
namespace shozu;
/**
 * Flash service
 *
 * Purpose of this service is to make some data available across pages. Flash
 * data is available on the next page but deleted when execution reach its end.
 *
 * Usual use of Flash is to make it possible for the current page to pass some data
 * to the next one (for instance success or error message before HTTP redirect).
 *
 * <code>
 * Flash::set('errors', 'Blog not found!');
 * Flass::set('success', 'Blog has been saved with success!');
 * Flash::get('success');
 * </code>
 *
 * @package MVC
 */
final class Flash
{
    const SESSION_KEY = 'shozu_flash';
    /**
     * Data that previous page left in the Flash
     */
    private static $_previous = array();

    /**
     * Return specific variable from the flash. If value is not found NULL is
     * returned
     *
     * @param string $var Variable name
     * @return mixed
     */
    public static function get($var)
    {
        return isset(self::$_previous[$var]) ? self::$_previous[$var] : null;
    }

    /**
     * Add specific variable to the flash. This variable will be available on the
     * next page unless removed with the removeVariable() or clear() method
     *
     * @param string $var Variable name
     * @param mixed $value Variable value
     * @return void
     */
    public static function set($var, $value)
    {
        $key = self::SESSION_KEY;
        $session = \shozu\Session::getInstance();
        $tab = $session->$key;
        $tab[$var] = $value;
        $session->$key = $tab;
    }

    /**
     * Call this function to clear flash. Note that data that previous page
     * stored will not be deleted - just the data that this page saved for
     * the next page
     *
     * @param none
     * @return void
     */
    public static function clear()
    {
        $key = self::SESSION_KEY;
        \shozu\Session::getInstance()->$key = array();
    }

    /**
     * This function will read flash data from the $_SESSION variable
     * and load it into $this->previous array
     *
     * @param none
     * @return void
     */
    public static function init()
    {
        $key = self::SESSION_KEY;
        $session = \shozu\Session::getInstance();
        $tab = $session->$key;
        // Get flash data...
        if (!empty($tab) && is_array($tab))
        {
            self::$_previous = $tab;
        }
        self::clear();
    }
}