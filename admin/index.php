<?php

/**
 * @package   Core
 * @copyright Авторские права (C) 2007-2010 Joostina team. Все права защищены.
 * @license   Лицензия http://www.gnu.org/licenses/gpl-2.0.htm GNU/GPL, или help/license.php
 * Joostina! - свободное программное обеспечение распространяемое по условиям лицензии GNU/GPL
 * Для получения информации о используемых расширениях и замечаний об авторском праве, смотрите файл help/copyright.php.
 */
// Установка флага родительского файла
define('_JOOS_CORE', 1);

// корень файлов панели управления
define('JPATH_BASE_ADMIN', __DIR__);

require_once ( dirname(JPATH_BASE_ADMIN) . DIRECTORY_SEPARATOR . 'core' . DIRECTORY_SEPARATOR . 'joostina.php' );

require_once ( JPATH_BASE . DS . 'core' . DS . 'admin.root.php' );
require_once ( JPATH_BASE . DS . 'app' . DS . 'bootstrap.php' );

joosDocument::header();
joosCoreAdmin::start();

$user = new stdClass;
$user->id = (int) joosRequest::session('session_user_id');
$user->user_name = joosRequest::session('session_user_name');

$session_id = joosRequest::session('session_id');
$logintime = joosRequest::session('session_logintime');

/**
 * @todo добавить проверку существования этой сессии в БД
 */
if ($session_id == md5($user->id . $user->user_name . $logintime)) {

    // проверка прав доступа в панель управления
    if ( helperAcl::check_access_for_user_id('admin_panel::use',$user->id)  ) {

        joosRoute::redirect('index2.php');
    }

    session_destroy();
}

if (joosRequest::is_post()) {

    joosCSRF::check_code('admin_login');

    $user_name = joosRequest::post('user_name');
    $password = joosRequest::post('password');

    if ($password == null) {
        joosRoute::redirect(JPATH_SITE_ADMIN, 'Необходимо ввести пароль');
        exit();
    }

    $database = joosDatabase::instance();

    $user = new modelUsers;
    $user->user_name = $user_name;
    $user->state = 1;
    $user->find();

    if ($user->id) {

        // проверка числа неудачных попуток авторизации
        if ($user->bad_auth_count >= 5) {

            $user->state = 0;
            $user->update();

            joosRoute::redirect(JPATH_SITE_ADMIN, 'Ваш аккаунт был заблокирован. Обратитесь к администратору сайта: ' . joosConfig::get2('mail', 'from'));
        }

        // готовим введённый пароль для проверки
        list( $hash, $salt ) = explode(':', $user->password);
        $cryptpass = md5($password . $salt);

        // проверка правильности пароля
        if ($hash !== $cryptpass) {

            $query = 'UPDATE #__users SET bad_auth_count = bad_auth_count + 1 WHERE id = ' . (int) $user->id;
            $database->set_query($query)->query();

            joosRoute::redirect(JPATH_SITE_ADMIN, 'Неправильный логин или пароль');
        }

        // проверка прав доступа в панель управления
        if ( helperAcl::check_access_for_user_id('admin_panel::init',$user->id) !==true ) {

            joosRoute::redirect(JPATH_SITE_ADMIN, 'В доступе отказано');
        }

        // construct Session ID
        $logintime = time();
        $session_id = md5($user->id . $user->user_name . $logintime);

        // чистим старые сессии
        session_destroy();
        session_unset();
        session_write_close();

        // запускаем новую сессию с нужным идентификатором и именем
        session_name(JADMIN_SESSION_NAME);
        session_id($session_id);
        session_start();

        // add Session ID entry to DB
        $query = "INSERT INTO #__users_session SET time = " . $database->get_quoted($logintime) . ", session_id = " . $database->get_quoted($session_id) . ", user_id = " . (int) $user->id  . ", user_name = " . $database->get_quoted($user->user_name) . ", guest=0, is_admin=1";
        $database->set_query($query)->query();

        $query = "DELETE FROM #__users_session WHERE  is_admin=1 AND session_id != " . $database->get_quoted($session_id) . " AND user_id = " . (int) $user->id;
        joosDatabase::instance()->set_query($query)->query();

        $_SESSION['session_id'] = $session_id;
        $_SESSION['session_user_id'] = $user->id;
        $_SESSION['session_user_name'] = $user->user_name;
        $_SESSION['session_logintime'] = $logintime;
        $_SESSION['session_bad_auth_count'] = $user->bad_auth_count;
        $_SESSION['session_userstate'] = array();

        session_write_close();

        $expired = JPATH_SITE_ADMIN . '/index2.php';

        if ($user->bad_auth_count!==0) {
            // скидываем счетчик неудачных авторзаций в админке
            $user->bad_auth_count = 0;
            $user->update();
        }

        joosRoute::redirect(JPATH_SITE_ADMIN.'/index2.php',  sprintf('С возвращением, %s',$user->user_name) );
    } else {

        joosRoute::redirect(JPATH_SITE_ADMIN, 'Такой пользователь не существует или ваш аккаунт был заблокирован. Обратитесь к администратору сайта: ' . joosConfig::get2('mail', 'from'));
    }
} else {
    $path = JPATH_BASE . DS . 'app' . DS . 'templates' . DS . JTEMPLATE_ADMIN . DS . 'login.php';
    require_once ( $path );
}
