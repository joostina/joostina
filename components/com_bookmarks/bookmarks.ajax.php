<?php
/**
 * @package Joostina
 * @copyright Авторские права (C) 2007-2010 Joostina team. Все права защищены.
 * @license Лицензия http://www.gnu.org/licenses/gpl-2.0.htm GNU/GPL, или help/license.php
 * Joostina! - свободное программное обеспечение распространяемое по условиям лицензии GNU/GPL
 * Для получения информации о используемых расширениях и замечаний об авторском праве, смотрите файл help/copyright.php.
 */

// запрет прямого доступа
defined('_VALID_MOS') or die();

require_once ($mainframe->getPath('class'));


class actionsBookmarks {

    // добавление закладки
    public static function add() {

        // закладки могут добавлять только авторизованные пользователи
        User::current()->id ? null : die( json_encode( array('error'=>'Необходимо авторизоваться') ) );

        $option = mosGetParam($_POST, 'obj_option','all');
        $id = (int)mosGetParam($_POST, 'obj_id',0);
        $task = mosGetParam($_POST, 'obj_task','all');

        echo Bookmarks::add($option, $id, $task);
    }

}