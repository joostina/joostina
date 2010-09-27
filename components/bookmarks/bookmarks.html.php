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

class bookmarksHTML {

    public static function addlink() {
        return '<button class="to_bookmarks">в закладки!</button>';
    }
    
    // список закладок определенного пользователя
    public static function index( User $user, array $bookmarks_list, paginator3000 $pager, $type_name = '' ) {
        $my = User::current();
        $_is_it_bookmarks = true;
        require_once 'views/bookmarks/default.php';
    }
    
    // список пользователей добавивших наблюдающих за игрой
    public static function gametrace( array $users, paginator3000 $pager, Games $game ) {
        require_once 'views/gametrace/default.php';
    }

    // список пользователей добавивших игру в любиые
    public static function gamelove( array $users, paginator3000 $pager, Games $game ) {
        require_once 'views/gamelove/default.php';
    }
}