<?php defined('_JOOS_CORE') or exit();

/**
 * Работа с сессиями
 *
 * @version    1.0
 * @package    Core\Libraries
 * @subpackage Session
 * @category   Libraries
 * @author     Joostina Team <info@joostina.ru>
 * @copyright  (C) 2007-2012 Joostina Team
 * @license    MIT License http://www.opensource.org/licenses/mit-license.php
 * Информация об авторах и лицензиях стороннего кода в составе Joostina CMS: docs/copyrights
 *
 * */
class joosSession
{
    private static $_userstate = null;

    private static function start()
    {
        if (!isset($_SESSION)) {
            $session_name = self::get_session_name();

            session_name($session_name);
            session_start();
        }
    }

    /**
     * Получение уникального названия сессии для пользователя
     *
     * @return string
     */
    public static function get_session_name()
    {
        $user_ip = joosRequest::user_ip();
        $user_browser = joosRequest::server('HTTP_USER_AGENT');

        return joosCSRF::hash($user_ip . $user_browser);
    }

    public static function get($key, $default = null)
    {
        self::start();

        return joosRequest::session($key, $default);
    }

    public static function set($key, $value)
    {
        self::start();

        return $_SESSION[$key] = $value;
    }

    public static function session_cookie_name()
    {
        $syte = str_replace(array('http://', 'https://'), '', JPATH_SITE);

        return md5('site' . $syte);
    }

    /**
     * Получение уникального ключа для значения пользовательской сессии
     *
     * @static
     * @param  null   $hash_key
     * @return string
     *
     * @todo добавить возможность работы через прокси, когда у пользователя меняется конечный IP, но единый IP прокси
     */
    public static function session_cookie_value($hash_key = null)
    {
        $user_ip = joosRequest::user_ip();
        $user_browser = joosRequest::server('HTTP_USER_AGENT', 'none');

        $type = joosConfig::get2('session', 'type', 2);
        switch ($type) {
            case 1:
                $value = md5($hash_key . $user_ip);
                break;

            default:
                $value = joosCSRF::hash($hash_key . $user_ip . $user_browser);
                break;
        }

        return $value;
    }

    public static function init_user_state()
    {
        if (self::$_userstate === null && isset($_SESSION['session_userstate'])) {
            self::$_userstate = &$_SESSION['session_userstate'];
        } else {
            self::$_userstate = null;
        }
    }

    public static function get_user_state_from_request($var_name, $req_name, $var_default = null)
    {
        if (is_array(self::$_userstate)) {
            if (isset($_REQUEST[$req_name])) {
                self::set_user_state($var_name, $_REQUEST[$req_name]);
            } elseif (!isset(self::$_userstate[$var_name])) {
                self::set_user_state($var_name, $var_default);
            }

            self::$_userstate[$var_name] = joosInputFilter::instance()->process(self::$_userstate[$var_name]);

            return self::$_userstate[$var_name];
        } else {
            return null;
        }
    }

    private static function set_user_state($var_name, $var_value)
    {
        // TODO сюда надо isset
        if (is_array(self::$_userstate)) {
            self::$_userstate[$var_name] = $var_value;
        }
    }

}
