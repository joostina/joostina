<?php

/**
 * @package   Joostina
 * @copyright Авторские права (C) 2007-2012 Joostina team. Все права защищены.
 * @license   Лицензия http://www.gnu.org/licenses/gpl-2.0.htm GNU/GPL, или help/license.php
 * Joostina! - свободное программное обеспечение распространяемое по условиям лицензии GNU/GPL
 * Для получения информации о используемых расширениях и замечаний об авторском праве, смотрите файл help/copyright.php.
 */
// запрет прямого доступа
defined('_JOOS_CORE') or exit();

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
    'clear_pages'=>true,
    'Статичные страницы' => array(
        'Все страницы' => array(
            'href' => 'index2.php?option=pages',
            'title' => '',
            'ico' => 'text-editor',
        ),
        'Добавить страницу' => array(
            'href' => 'index2.php?option=pages&task=create',
            'title' => '',
            'ico' => 'stock_copy',
        ),

    ),
    'clear_news'=>true,
    'Новости' => array(
        'Все новости' => array(
            'href' => 'index2.php?option=news',
            'title' => '',
            'ico' => 'edit-select-all',
        ),
        'Добавить новость' => array(
            'href' => 'index2.php?option=news&task=create',
            'title' => '',
            'ico' => 'stock_copy',
        ),

    ),
    'clear_blogs'=>true,
    'Блоги' => array(
        'Блогозаписи' => array(
            'href' => 'index2.php?option=blogs',
            'title' => '',
            'ico' => 'folder-documents',
        ),
        'Добавить блогозапись' => array(
            'href' => 'index2.php?option=blogs&task=create',
            'title' => '',
            'ico' => 'stock_edit',
        ),
        'Категории' => array(
            'href' => 'index2.php?option=blogs&menu=blogs_category',
            'title' => '',
            'ico' => 'fileopen',
        ),
        'Добавить категорию' => array(
            'href' => 'index2.php?option=blogs&menu=blogs_category&task=create',
            'title' => '',
            'ico' => 'folder-new',
        ),

    ),
    'clear_coder'=>true,
    'Инструменты' => array(
        'Генератор моделей' => array(
            'href' => 'index2.php?option=coder',
            'title' => '',
            'ico' => 'application-default-icon',
        ),
        'Генератор контроллеров' => array(
            'href' => 'index2.php?option=coder&task=code_generator',
            'title' => '',
            'ico' => 'application-default-icon',
        ),
        'Генератор автозагрузки' => array(
            'href' => 'index2.php?option=coder&task=autoload_generator',
            'title' => '',
            'ico' => 'application-default-icon',
        ),
    ),
);