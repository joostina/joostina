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

class userHTML {

    public static function index(array $users_list, paginator3000 $pager) {
        $_is_it_all = true;
        require_once 'views/index/default.php';
    }

    /**
     * Форма регистрации пользователя
     */
    public static function register(User $user, $validator) {
        require_once 'views/register/default.php';
    }

    public static function after_register() {
?>Всё прекрасно - хорошо!<?php
    }

    /**
     * Форма восстановления забытого пароля
     */
    public static function lostpassword() {
        require_once 'views/lostpassword/default.php';
    }

    public static function view(User $user, UserExtra $user_extra, array $games_love) {
        $my = User::current();
        $_is_it_view = true;
        require_once 'views/view/default.php';
    }

    public static function edit(User $user, UserExtra $user_extra, $validator) {
        $my = User::current();
        $bday_date = mosFormatDate($user_extra->birthdate, '%d', '0');
        $bday_month = mosFormatDate($user_extra->birthdate, '%m', '0');
        $bday_year = mosFormatDate($user_extra->birthdate, '%Y', '0');
        $_is_it_edit = true;
        require_once 'views/edit/default.php';
    }

    public static function files(User $user, array $files) {
        $my = User::current();
        $_is_it_files = true;
        require_once 'views/files/default.php';
    }

    public static function lovegames(User $user, array $game_list, paginator3000 $pager) {
        $my = User::current();
        $_is_it_lovegames = true;
        require_once 'views/lovegames/default.php';
    }

    public static function watchgames(User $user, array $game_list, paginator3000 $pager) {
        $my = User::current();
        $_is_it_watchgames = true;
        require_once 'views/watchgames/default.php';
    }

    // топ пользователей
    public static function top(array $users_list, paginator3000 $pager) {
        require_once 'views/top/default.php';
    }

}