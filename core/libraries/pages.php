<?php defined('_JOOS_CORE') or die();

/**
 * Библиотека работы со страницами системных сообщений
 *
 * @version    1.0
 * @package    Core\Libraries
 * @subpackage Pages
 * @category   Libraries
 * @author     Joostina Team <info@joostina.ru>
 * @copyright  (C) 2007-2012 Joostina Team
 * @license    MIT License http://www.opensource.org/licenses/mit-license.php
 * Информация об авторах и лицензиях стороннего кода в составе Joostina CMS: docs/copyrights
 *
 * */
class joosPages {

    /**
     * Инициализация задачи
     *
     * @static
     * @param $code
     */
    private static function init( $code ) {
        joosRequest::send_headers_by_code( $code );
        if ( ob_get_level() ) {
            ob_end_clean();
        }
    }

    /**
     * Подключение шаблона страницы и завершение работы системы
     *
     * @static
     * @param $message
     * @param $page
     */
    public static function render($message, $page){
        include JPATH_BASE . '/app/templates/system/' . $page . '.php';
        die();
    }

    /**
     * 404 страница - не найдено
     *
     * @static
     * @param string $message тест сообщения
     */
    public static function page404( $message = 'Не найдено' ) {

        self::init( 404 );
        self::render($message,'page_404');
    }

    /**
     * 403 страница - в доступе отказано
     *
     * @static
     * @param string $message тест сообщения
     */
    public static function page403( $message = 'В доступе отказано' ) {

        self::init( 403 );
        self::render($message,'page_403');
    }

    /**
     * 502 страница - ошибка работы сервера / приложения
     *
     * @static
     * @param string $message тест сообщения
     */
    public static function page502( $message = 'Ошибка системы' ) {

        self::init( 502 );
        self::render($message,'page_502');
    }

    /**
     * Ошибка работы с базой данных
     *
     * @static
     * @param string $message тест сообщения
     */
    public static function error_database( $message = 'Ошибка базы данных' ) {

        self::init( 503 );
        self::render($message,'error_database');
    }

    /**
     * Страница ошибки доступная пользователю
     *
     * @static
     * @param string $message тест сообщения
     */
    public static function error_user( $message = 'Ошибка' ) {

        self::init( 503 );
        self::render($message,'error_user');
    }

    /**
     * Ошибка внутри php кода
     *
     * @static
     * @param string $message тест сообщения
     */
    public static function error_code( $message = 'Ой, у нас ошибочка' ) {

        self::init( 503 );
        self::render($message,'error_code');
    }

    /**
     * Технические работы на сайте / сервере
     *
     * @static
     * @param string $message тест сообщения
     */
    public static function maintenance( $message = 'Технические работы' ) {

        self::init( 503 );
        self::render($message,'maintenance');
    }

    /**
     * Сайт выключен
     *
     * @static
     * @param string $message тест сообщения
     */
    public static function offline( $message = 'Сайт отключен' ) {

        self::init( 503 );
        self::render($message,'offline');
    }


}
