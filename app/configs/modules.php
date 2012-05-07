<?php
/*
 * Файл конфигурации модулей сайта
 *
 * Примеры настроек:
 *     'test' => array( //имя модуля
            array(
                'route' => 'default', //выводить на главной странице
                'position' => 'right', //в позиции 'right'
                'params' => array('template' => '1') //с шаблоном views/1.php
            ),
            array(
                'route' => '__all', //выводить на всех страницах сайта
                'position' => 'right', //в позиции 'right'
            ),
            array(
                'route' => 'news', //выводить в разделе "Новости"
                'position' => 'right', //в позиции 'right'
                'action' => 'test_action', //метод модуля
            ),
            array(
                'route' => '__exept', //на всех страницах, кроме роутов, которые заданы в '__exept_routes'
                '__exept_routes' => array('default'),
                'position' => 'right'
            ),
     ),

 * */
return array(

    //Модуль "Test"
    'test' => array(
        array(
            'route' => 'default', //выводим на главной
            'position' => 'right', //в позиции 'right'
            'params' => array('template' => '1') //с шаблоном views/1.php
        ),
        array(
            'route' => '__all', //на всех страницах сайта
            'position' => 'right' //в позиции 'right'
        ),
        array(
            'route' => 'news', //выводить в разделе "Новости"
            'position' => 'right', //в позиции 'right'
            'action' => 'test_action', //метод модуля
        ),
    ),

    //Модуль "Новости"
    'news' => array(
        array(
            'route' => 'default', //выводим только на главной
            'position' => 'top' //в позиции 'top'
        )
    ),

);