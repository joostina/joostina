<?php
/**
 *
 */
defined( '_JOOS_CORE' ) or die();

$news = new modelNews();
$news = $news->get_list(array(
    'where' => 'state = 1',
    'order' => 'id DESC',
    'limit' => 3
));

//Подключение шаблона вывода
$params['template_file'] ? require_once $params['template_file'] : null;
