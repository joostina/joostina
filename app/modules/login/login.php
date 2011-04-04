<?php

/**
 * Login - модуль авторизации
 * Основной исполняемый файл
 *
 * @version 1.0
 * @package Joostina CMS
 * @subpackage Modules
 * @author JoostinaTeam
 * @copyright (C) 2008-2010 Joostina Team
 * @license see license.txt
 *
 * */
//Запрет прямого доступа
defined('_JOOS_CORE') or die();

echo joosHTML::js_code('_current_uid=' . (User::instance()->id ? User::instance()->id : 'false'));

$user = isset($params['user']) ? $params['user'] : User::current();

echo '<div id="login_area">';
require_once $user->id ? 'views/logout/default.php' : 'views/login/default.php';
echo '</div>';
