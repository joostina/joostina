<?php

/**
 * @package Joostina
 * @copyright Авторские права (C) 2007-2010 Joostina team. Все права защищены.
 * @license Лицензия http://www.gnu.org/licenses/gpl-2.0.htm GNU/GPL, или help/license.php
 * Joostina! - свободное программное обеспечение распространяемое по условиям лицензии GNU/GPL
 * Для получения информации о используемых расширениях и замечаний об авторском праве, смотрите файл help/copyright.php.
 */
// запрет прямого доступа
defined('_JOOS_CORE') or die();

/*
 * Базируется на классалх кеширования из проекта DooPHP - http://www.doophp.com/
 * @author Leng Sheng Hong <darkredz@gmail.com>
 * @link http://www.doophp.com/
 * @copyright Copyright &copy; 2009 Leng Sheng Hong
 * @license http://www.doophp.com/license
 */

class Doo
{

    protected static $_conf;
    protected static $_cache;

    public static function conf()
    {
        if (self::$_conf === NULL) {
            self::$_conf = new DooConfig;
        }
        return self::$_conf;
    }

    /**
     * @param string $cacheType Cache type: file, php, front, apc, memcache, xcache, eaccelerator. Default is file based cache.
     * @return DooFileCache|DooPhpCache|DooFrontCache|DooApcCache|DooMemCache|DooXCache|DooEAcceleratorCache file/php/apc/memcache/xcache/eaccelerator & frontend caching tool, singleton, auto create if the singleton has not been created yet.
     */
    public static function cache($cacheType = 'file')
    {
        if ($cacheType == 'file') {
            if (isset(self::$_cache['file']))
                return self::$_cache['file'];

            self::loadCore('cache/DooFileCache');
            self::$_cache['file'] = new DooFileCache;
            return self::$_cache['file'];
        }
        else if ($cacheType == 'php') {
            if (isset(self::$_cache['php']))
                return self::$_cache['php'];

            self::loadCore('cache/DooPhpCache');
            self::$_cache['php'] = new DooPhpCache;
            return self::$_cache['php'];
        }
        else if ($cacheType == 'front') {
            if (isset(self::$_cache['front']))
                return self::$_cache['front'];

            self::loadCore('cache/DooFrontCache');
            self::$_cache['front'] = new DooFrontCache;
            return self::$_cache['front'];
        }
        else if ($cacheType == 'apc') {
            if (isset(self::$_cache['apc']))
                return self::$_cache['apc'];

            self::loadCore('cache/DooApcCache');
            self::$_cache['apc'] = new DooApcCache;
            return self::$_cache['apc'];
        }
        else if ($cacheType == 'xcache') {
            if (isset(self::$_cache['xcache']))
                return self::$_cache['xcache'];

            self::loadCore('cache/DooXCache');
            self::$_cache['xcache'] = new DooXCache;
            return self::$_cache['xcache'];
        }
        else if ($cacheType == 'eaccelerator') {
            if (isset(self::$_cache['eaccelerator']))
                return self::$_cache['eaccelerator'];

            self::loadCore('cache/DooEAcceleratorCache');
            self::$_cache['eaccelerator'] = new DooEAcceleratorCache;
            return self::$_cache['eaccelerator'];
        }
        else if ($cacheType == 'memcache') {
            if (isset(self::$_cache['memcache']))
                return self::$_cache['memcache'];

            self::loadCore('cache/DooMemCache');
            self::$_cache['memcache'] = new DooMemCache(Doo::conf()->MEMCACHE);
            return self::$_cache['memcache'];
        }
        else if ($cacheType == 'ssi') {
            if (isset(self::$_cache['ssi']))
                return self::$_cache['ssi'];

            self::loadCore('cache/JooSSICache');
            self::$_cache['ssi'] = new JooSSICache;
            return self::$_cache['ssi'];
        }
    }

    public static function loadCore($name)
    {
        require_once JPATH_BASE . DS . 'includes' . DS . 'libraries' . DS . 'doocache' . DS . $name . '.php';
    }

}

class DooConfig
{

    public $SITE_PATH;
    public $BASE_PATH;
    public $PROTECTED_FOLDER;
    public $SUBFOLDER;
    public $CACHE_PATH;
    /**
     * Settings for Memcache servers, defined in arrays: array(host, port, persistent, weight)
     * <code>
     * // host, port, persistent, weight
     * $config['MEMCACHE'] = array(
     *                       array('192.168.1.31', '11211', true, 40),
     *                       array('192.168.1.23', '11211', true, 80)
     *                     );
     * </code>
     * @var array
     */
    public $MEMCACHE;

    public function __construct()
    {
        $cache_config = joosConfig::get('cache');

        $this->CACHE_PATH = $cache_config['filepath'];
        $this->SITE_PATH = JPATH_SITE;
        $this->BASE_PATH = JPATH_BASE;
        $this->PROTECTED_FOLDER = '';
        $this->MEMCACHE = array(
            array($cache_config['memcache_host'], $cache_config['memcache_port'])
        );
    }

}