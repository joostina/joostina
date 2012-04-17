<?php

// запрет прямого доступа
defined( '_JOOS_CORE' ) or die();

/**
 * Модуль главного меню панели управления
 * Модуль панели управления
 *
 * @version   1.0
 * @package   Core\Modules
 * @author    Joostina Team <info@joostina.ru>
 * @copyright (C) 2007-2012 Joostina Team
 * @license   MIT License http://www.opensource.org/licenses/mit-license.php
 * Информация об авторах и лицензиях стороннего кода в составе Joostina CMS: docs/copyrights
 *
 * */

//Получение дерева пунктов меню
$menu_items = require_once JPATH_APP_CONFIG.'/admin_menu.php';

// рендер
joosModuleAdmin::render( 'admin_menu' , 
    array ( 'menu_items' => $menu_items ) 
);