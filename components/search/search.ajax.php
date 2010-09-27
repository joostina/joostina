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

require_once ($mainframe->getPath('class'));

class actionsSearch {

    public static function autocomplete($option, $id, $page, $task) {

        $word = strval(mosGetParam($_GET, 'term', ''));

        // если пользователь ввёл меньше 2х символов - не будем выдавать ему подсказку
        if (Jstring::strlen($word) > 2) {
            $result = SearchLog::get_log($word);
        } else {
            $result =false;
        }
        echo json_encode($result);
    }

}