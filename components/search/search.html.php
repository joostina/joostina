<?php

/**
 * @package Joostina
 * @copyright Авторские права (C) 2008-2010 Joostina team. Все права защищены.
 * @license Лицензия http://www.gnu.org/licenses/gpl-2.0.htm GNU/GPL, или help/license.php
 * Joostina! - свободное программное обеспечение распространяемое по условиям лицензии GNU/GPL
 * Для получения информации о используемых расширениях и замечаний об авторском праве, смотрите файл help/copyright.php.
 */
// запрет прямого доступа
defined('_JOOS_CORE') or die();

class searchHTML {
    
    public static function index() {
        require_once 'views/index/default.php';
    }

    public static function form($q = '') {
        require_once 'views/form/default.php';
    }

    public static function results($lists, paginator3000 $pager, $limit, $q) {
        require_once 'views/results/default.php';
    }

}