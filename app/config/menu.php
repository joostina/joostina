<?php
defined('_JOOS_CORE') or exit;
/*
 * Конфиг главного меню сайта
 */
return array(
    'Главная' => array(
           'title' => '',
           'href' => joosRoute::href('default')
       ),

    'Новости' => array(
           'title' => '',
           'href' => joosRoute::href('news')
       ),

    'Блоги' => array(
           'title' => '',
           'href' => joosRoute::href('blog')
       ),

    'Вёрстка здесь' => array(
        'title' => '',
        'href' => false,
        'children' => array(
            'Блог' => array(
                'title' => '',
                'href' => joosRoute::href('layouts', array('tpl' => 'blog_index')),
            ),
            'Блог. Пост' => array(
                'title' => '',
                'href' => joosRoute::href('layouts', array('tpl' => 'blog_post')),
            ),
            'Пользователи. Список' => array(
                'title' => '',
                'href' => joosRoute::href('layouts', array('tpl' => 'users_index')),
            ),
        )
    ),

);
