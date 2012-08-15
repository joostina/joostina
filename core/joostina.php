<?php defined('_JOOS_CORE') or exit;

/**
 * Ядро
 *
 * @package   Core
 * @author    JoostinaTeam
 * @copyright (C) 2007-2012 Joostina Team
 * @license   MIT License http://www.opensource.org/licenses/mit-license.php
 * Иинформация об авторах и лицензиях стороннего кода в составе Joostina CMS: docs/copyrights
 */

// Обработчик ошибок
require JPATH_BASE . DS . 'core' . DS . 'libraries' . DS . 'exception.php';
// Автозагрузчик
require JPATH_BASE . DS . 'core' . DS . 'libraries' . DS . 'autoloader.php';


/**
 * Главное ядро Joostina CMS
 *
 * Описывать класс можно более чем подробно
 * - Первое.
 * - Второе, много разного текста
 *   даже нна несколкьо строк.
 * - И третье.
 *
 * @package    Joostina
 * @subpackage Core
 */
class joosCore
{

	/**
	 * @var joosRoute
	 */
	protected $router;

	/**
	 * @var joosCore
	 */
	protected static  $instance;

	/**
	 * @static
	 * @return joosCore
	 */
	public static function instance(){

		if( self::$instance === NULL ){
			self::$instance = new joosCore();
		}

		return self::$instance;
	}

	public function init()
	{

		// регистрация автолоадера
		joosAutoloader::init();

		// сначала инициализация базовой конфигурации
		joosConfig::init();

		// отправка заголовков
		joosDocument::header();

		return $this;
	}

	public function route(){

		$this->router = new joosRoute();
		$this->router->route();

		return $this;

	}

	/**
	 * @return joosRoute
	 */
	public function get_router(){

		return $this->router;
	}
	
    /**
     * Флаг работы ядра в режиме FALSE - сайт, TRUE - панель управления
     *
     * @var bool
     */
    private static $is_admin = false;

    /**
     * Получение инстанции текущего авторизованного пользователя
     * Функция поддерживает работу и на фронте и в панели управления сайта
     *
     * @tutorial joosCore::user() => Объект пользователя modelUsers
     *
     * @return modelUsers
     */
    public static function user()
    {
        return self::$is_admin ? joosCoreAdmin::user() : modelUsers::instance();
    }

    public static function set_admin_mode()
    {
        self::$is_admin = TRUE;
    }

    public static function is_admin()
    {
        return (bool) self::$is_admin;
    }

}

/**
 * Убрать, заменить везде и использовать как joosDebug::dump($var);
 * @deprecated
 */
function _xdump($var)
{
    joosDebug::dump($var);
}

