<?php

/**

 *
 * */
//Запрет прямого доступа
defined('_JOOS_CORE') or die();

joosLoader::model('job');

$job = new Job;
$job = $job->get_selector(array('key'=>'id', 'value'=>'title'), array('where'=>'state=1'));

//Подключение шаблона модуля	
require $module->template_path;	