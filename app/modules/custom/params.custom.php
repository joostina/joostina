<?php

/**
 * Test - тестовый модуль
 * */
//Запрет прямого доступа
defined( '_JOOS_CORE' ) or die();

$extension_install = array ( 'type'      => 'module' ,
                             'module'    => 'test' ,
                             'client_id' => '0' ,
                             'position'  => 'left' ,
                             'title'     => 'Тестовый модуль' , );

$extension_access  = array ( 'view'        => array ( 'title'  => 'Отображать' ,
                                                      'groups' => array ( 8 , 9 ) ) ,
                             'smth_action' => array ( 'title'  => 'Какое-то действие' ,
                                                      'groups' => array ( 9 ) ) );

$extension_info    = array ( 'author'       => 'Joostina Team' ,
                             'creationDate' => '2010' ,
                             'copyright'    => '(C) 2008-2010 Joostina team.' ,
                             'license'      => 'http://www.gnu.org/licenses/gpl-2.0.htm GNU/GPL' ,
                             'authorEmail'  => 'joostinacms@gmail.com' ,
                             'authorUrl'    => 'www.joostina.ru' ,
                             'version'      => '1.0' ,
                             'description'  => 'Модуль для демонстрации всяческих возможностей' );

$extension_params  = array ( 'param1' => array ( 'name'                    => 'Параметр #1' ,
                                                 'editable'                => true ,
                                                 'html_edit_element'       => 'edit' ,
                                                 'html_edit_element_param' => array () ) ,
                             'param2' => array ( 'name'                    => 'Параметр #2' ,
                                                 'editable'                => true ,
                                                 'html_edit_element'       => 'edit' ,
                                                 'html_edit_element_param' => array () ) );