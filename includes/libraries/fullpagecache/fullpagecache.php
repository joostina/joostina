<?php

/**
 * @package Joostina
 * @copyright Авторские права (C) 2008-2010 Joostina team. Все права защищены.
 * @license Лицензия http://www.gnu.org/licenses/gpl-2.0.htm GNU/GPL, или help/license.php
 * Joostina! - свободное программное обеспечение распространяемое по условиям лицензии GNU/GPL
 * Для получения информации о используемых расширениях и замечаний об авторском праве, смотрите файл help/copyright.php.
 */
// запрет прямого доступа
defined('_VALID_MOS') or die();

/**
 * Полностраничное кеширование
 */
class Fullpagecache {

    // полное имя файла для кеша
    private static $filename;
    // содержимое страницы для кеширования
    private static $data;

    /**
     * Начало буферизации содержимого страницы для кеширования
     */
    public static function start() {
        JDEBUG ? jd_log('Fullpagecache::start') : null;
        ob_start();
    }

    /**
     * Окончание буферизации кеширования для страницы
     */
    public static function stop() {
        JDEBUG ? jd_log('Fullpagecache::stop') : null;
        self::$data = ob_get_contents();
        ob_end_clean();
    }

    /**
     * Кеширование страницы
     * @param string $data - содержимое страницы
     * @return boolean - в случае отображения страницы с имеющимися POST данными
     */
    public static function cache($data = false) {
        JDEBUG ? jd_log('Fullpagecache::cache') : null;

        if ($_POST) {
            JDEBUG ? jd_log('Fullpagecache::NON-cache::POST') : null;
            return;
        }

        $filename = self::$filename;
        self::$data = $data ? $data : self::$data;

        self::optimize();

        mosMainFrame::addLib('files');

        $cache = new File(0777);
        $cache->create(JPATH_BASE . DS . 'cache' . DS . 'fullpagecache' . $filename, self::$data);
    }

    /**
     * Получение адреса текущей страницы
     * @return string - относительный адрес текущей страницы
     */
    public static function get_page_url() {
        JDEBUG ? jd_log('Fullpagecache::get_page_url') : null;

        if (isset($_SERVER['HTTPS']) && !empty($_SERVER['HTTPS']) && (strtolower($_SERVER['HTTPS']) != 'off')) {
            $https = 's://';
        } else {
            $https = '://';
        }
        if (!empty($_SERVER['PHP_SELF']) && !empty($_SERVER['REQUEST_URI'])) {
            $theURI = 'http' . $https . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
        } else {
            $theURI = 'http' . $https . $_SERVER['HTTP_HOST'] . $_SERVER['SCRIPT_NAME'];
            if (isset($_SERVER['QUERY_STRING']) && !empty($_SERVER['QUERY_STRING'])) {
                $theURI .= '?' . $_SERVER['QUERY_STRING'];
            }
        }
        $theURI = urldecode($theURI);
        $theURI = str_replace('www.', '', $theURI);

        $ps = str_replace('www.', '', JPATH_SITE);
        $filename = str_replace($ps, '', $theURI);
        $filename = ( $filename[strlen($filename) - 1] == '/' ) ? $filename . 'index.html' : $filename;

        return self::$filename = $filename;
    }

    /**
     * Оптимизация кода кешироуемой HTML страницы
     */
    private static function optimize() {
        mosMainFrame::addLib('html_optimize');
        self::$data = html_optimize::optimize(self::$data);
    }

}