<?php

/**
 * Users - пользователи
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

//Подключение вспомогательной библиотеки
require_once joosCore::path('users', 'module_helper');

joosLoader::model('bookmarks');

if (isset($params['type'])) {

    switch ($params['type']) {

        //Блок в профиле пользователя
        case 'profile_user':
        default:
            $user = (isset($params['user'])) ? $params['user'] : false;
            break;

            break;
    }
}

//Подключение шаблона вывода
$params['template'] ? require $params['template'] : null;