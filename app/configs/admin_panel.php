<?php

/**
 * @package   Joostina
 * @copyright Авторские права (C) 2007-2012 Joostina team. Все права защищены.
 * @license   Лицензия http://www.gnu.org/licenses/gpl-2.0.htm GNU/GPL, или help/license.php
 * Joostina! - свободное программное обеспечение распространяемое по условиям лицензии GNU/GPL
 * Для получения информации о используемых расширениях и замечаний об авторском праве, смотрите файл help/copyright.php.
 */
// запрет прямого доступа
defined('_JOOS_CORE') or die();

return array(
    'Пользователи' => array(
        'Все пользователи' => array(
            'href' => 'index2.php?option=users',
            'title' => 'Человечки',
            'ico' => 'user-info',
        ),
        'Добавить пользователя' => array(
            'href' => 'index2.php?option=users&task=create',
            'title' => '',
            'ico' => 'contact-new',
        ),
        'Права доступа' => array(
            'href' => 'index2.php?option=acls',
            'title' => '',
            'ico' => 'folder-publicshare',
        )
    ),
    'Статичные страницы' => array(
        'Все страницы' => array(
            'href' => 'index2.php?option=pages',
            'title' => '',
            'ico' => 'text-editor',
        ),
        'Добавить новую' => array(
            'href' => 'index2.php?option=pages&task=create',
            'title' => '',
            'ico' => 'stock_copy',
        ),

    ),
    'clear'=>true,
    'Инструменты' => array(
        'Кодогенератор' => array(
            'href' => 'index2.php?option=coder',
            'title' => '',
            'ico' => 'application-default-icon',
        ),
    ),
);