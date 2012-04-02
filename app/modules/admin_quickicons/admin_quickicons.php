<?php

// запрет прямого доступа
defined( '_JOOS_CORE' ) or die();

/**
 * Adminquickicons  - Модуль значков быстрого доступа панели управления
 * Модуль панели управления
 *
 * @version   1.0
 * @package   Modules
 * @author    Joostina Team <info@joostina.ru>
 * @copyright (C) 2007-2012 Joostina Team
 * @license   MIT License http://www.opensource.org/licenses/mit-license.php
 * Информация об авторах и лицензиях стороннего кода в составе Joostina CMS: docs/copyrights
 *
 * */
//Подклчение вспомагательной библиотеки
//Получение перечня значков
$items = joosDatabase::models( 'modelAdminQuickicons' )
		->get_list( array ( 'where' => 'state = 1' ) );

//Подключение шаблона вывода
joosModuleAdmin::render( 'admin_quickicons' , array ( 'items' => $items ) );