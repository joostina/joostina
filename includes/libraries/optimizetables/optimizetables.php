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

class optimizetables {
	// время, через какое необходимо выполнять оптимизацию таблиц. в секундах
	private static $optimizetime = 86400;

    /**
     * Оптимизация таблиц базы данных
     * Основано на мамботе OptimizeTables - smart (C) 2006, Joomlaportal.ru. All rights reserved
     */
    public static function optimizetables() {
        // 1 раз из 50 вызовем проверку и оптимизацию таблиц
        if (mt_rand(1, 50) == 1) {
            register_shutdown_function('optimizetables::job');
        }
    }

    // Непосредственно оптимизация таблиц базы данных
    public static function job() {

        $config = Jconfig::getInstance();
        $flag = $config->config_cachepath . '/optimizetables.flag';

        $filetime = @filemtime($flag);
        $currenttime = time();
        if ($filetime + self::$optimizetime > $currenttime) {
            return;
        }
        $f = fopen($flag, 'w+');
        @fwrite($f, time());
        fclose($f);
        @chmod($flag, 0777);

        $database = database::getInstance();
        $database->setQuery("OPTIMIZE TABLE `" . implode('`,`', $database->getTableList()) . "`;")->query();
    }
}