<?php

// запрет прямого доступа
defined('_JOOS_CORE') or die();

/**
 * Adminquickicons  - Модуль значков быстрого доступа панели управления
 * Модуль панели управления
 *
 * @version 1.0
 * @package Joostina.Modules
 * @author Joostina Team <info@joostina.ru>
 * @copyright (C) 2007-2011 Joostina Team
 * @license MIT License http://www.opensource.org/licenses/mit-license.php
 * Информация об авторах и лицензиях стороннего кода в составе Joostina CMS: docs/copyrights
 *
 * */
//Подклчение вспомагательной библиотеки
//Получение перечня значков
$items = joosDatabase::models('adminQuickicons')->get_list(array('where' => 'state = 1'));

//Подключение шаблона вывода
joosModuleAdmin::render('adminquickicons', array('items' => $items));