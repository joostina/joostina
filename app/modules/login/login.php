<?php

/**
 * Login - модуль авторизации
 * Основной исполняемый файл
 *
 * @version    1.0
 * @package    Joostina CMS
 * @package   Core\Modules
 * @author     JoostinaTeam
 * @copyright  (C) 2007-2012 Joostina Team
 * @license    see license.txt
 *
 * */
//Запрет прямого доступа
defined( '_JOOS_CORE' ) or exit();

$user = isset( $params['user'] ) ? $params['user'] : joosCore::user();



$params['template_file'] = $user->id ? JPATH_BASE . '/app/modules/login/views/logout/default.php' : JPATH_BASE . '/app/modules/login/views/login/default.php';

$params['template_file'] ? require_once $params['template_file'] : null;
