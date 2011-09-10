<?php

// запрет прямого доступа
defined('_JOOS_CORE') or die();

/**
 * joosFlashMessage - Библиотека работы с системными уведомлениями
 * Системная библиотека
 *
 * @version 1.0
 * @package Joostina.Libraries
 * @subpackage Libraries
 * @subpackage joosSession
 * @category Libraries
 * @author Joostina Team <info@joostina.ru>
 * @copyright (C) 2007-2011 Joostina Team
 * @license MIT License http://www.opensource.org/licenses/mit-license.php
 * Информация об авторах и лицензиях стороннего кода в составе Joostina CMS: docs/copyrights
 *
 * */
class joosFlashMessage {

	/**
	 * Установка системного сообщения
	 * @static
	 * @param  $msg  текст сообщения
	 * @return void
	 */
	public static function add($msg) {
		$msg = joosString::trim($msg);

		if ($msg != '') {
			if ( joosCore::is_admin()) {
				$_s = session_id();
				if (empty($_s)) {
					session_name(md5(JPATH_SITE));
					session_start();
				}
			} else {
				session_name(joosSession::sessionCookieName());
				session_start();
			}

			$_SESSION['joostina.mosmsg'] = $msg;
		}
	}

	/**
	 * Получение системного сообщения
	 * @return string - текст сообщения
	 */
	public static function get() {

		$_s = session_id();

		if (!joosCore::is_admin() && empty($_s)) {
			session_name(joosSession::sessionCookieName());
			session_start();
		}

		$mosmsg = joosRequest::session('joostina.mosmsg', false);

		if ($mosmsg != '' && joosString::strlen($mosmsg) > 300) { // выводим сообщения не длинее 300 символов
			$mosmsg = joosString::substr($mosmsg, 0, 300);
		}

		/**
		  @var $_SESSION array */
		unset($_SESSION['joostina.mosmsg']);
		return $mosmsg ? '<div class="b-system_message">' . $mosmsg . '</div>' : '';
	}

}


/**
 * Flash service
 *
 * Purpose of this service is to make some data available across pages. Flash
 * data is available on the next page but deleted when execution reach its end.
 *
 * Usual use of Flash is to make it possible for the current page to pass some data
 * to the next one (for instance success or error message before HTTP redirect).
 *
 * Flash::set('errors', 'Blog not found!');
 * Flass::set('success', 'Blog has been saved with success!');
 * Flash::get('success');
 *
 * Flash service as a concept is taken from Rails. This thing is really useful!
 * @todo адаптировать
 */
final class Flash
{
    const SESSION_KEY = 'framework_flash';
    
    private static $_previous = array(); // Data that prevous page left in the Flash

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
        $_SESSION[self::SESSION_KEY][$var] = $value;
    } // set

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
        $_SESSION[self::SESSION_KEY] = array();
    } // clear

    /**
     * This function will read flash data from the $_SESSION variable
     * and load it into $this->previous array
     *
     * @param none
     * @return void
     */
    public static function init()
    {
        // Get flash data...
        if ( ! empty($_SESSION[self::SESSION_KEY]) && is_array($_SESSION[self::SESSION_KEY])) {
            self::$_previous = $_SESSION[self::SESSION_KEY];
        }
        $_SESSION[self::SESSION_KEY] = array();
    }

} // end Flash class