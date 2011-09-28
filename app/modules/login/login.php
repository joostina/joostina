<?php

/**
 * Login - модуль авторизации
 * Основной исполняемый файл
 *
 * @version    1.0
 * @package    Joostina CMS
 * @subpackage Modules
 * @author     JoostinaTeam
 * @copyright  (C) 2008-2010 Joostina Team
 * @license    see license.txt
 *
 * */
//Запрет прямого доступа
defined( '_JOOS_CORE' ) or die();

$user = isset( $params['user'] ) ? $params['user'] : joosCore::user();


echo '<div id="login_area">';
$params['template'] = $user->id ? JPATH_BASE . '/app/modules/login/views/logout/default.php' : JPATH_BASE . '/app/modules/login/views/login/default.php';
echo '</div>';
