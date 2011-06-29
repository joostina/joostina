<?php

// запрет прямого доступа
defined('_JOOS_CORE') or die();

/**
 * Test  - Компонент для тестирования нового функционала
 * Контроллер сайта
 *
 * @version 1.0
 * @package Joostina.Components.Controllers
 * @subpackage Tags    
 * @author Joostina Team <info@joostina.ru>
 * @copyright (C) 2008-2011 Joostina Team
 * @license MIT License http://www.opensource.org/licenses/mit-license.php
 * Информация об авторах и лицензиях стороннего кода в составе Joostina CMS: docs/copyrights
 *
 * */
class actionsTest {

	public static function index() {


		joosEvents::add_events('system.onstart', function($a, $b) {
					echo sprintf('1. a=%s; $b=%s', $a, $b);
				});

		joosEvents::add_events('system.onstart', function($a, $b) {
					echo sprintf('2. a=%s; $b=%s', $a, $b);
				});

		joosEvents::add_events('system.onstart', 'absd');

		joosEvents::add_events('system.onstart', 'actionsTest::viewtest');
		
		joosEvents::fire_events('system.onstart', 1, 2);


		die();

		return array('asd' => crc32('Alanis Morissette - Crazy'));
	}

	public static function redirect() {
		joosRoute::redirect(joosRoute::href('user_view', array('id' => 72, 'username' => 'ZaiSL')), 'Раз-раз');

		return array(
			'task' => 'viewtest'
		);
	}

	public static function images() {

		joosLoader::lib('images');
		$rotte = dirname(__FILE__) . '/media/files/';
		Thumbnail::output($rotte . 'original.jpg', $rotte . '/image_350x350.png', array('width' => 350, 'height' => 350));
		Thumbnail::output($rotte . 'image_350x350.png', $rotte . '/image_200x200.png', array('width' => 200, 'height' => 200, 'method' => THUMBNAIL_METHOD_CROP));
	}

	public static function cache() {

		joosLoader::lib('doocache');

		// кеширование php блока
		if (!Doo::cache('front')->getPart('cache', 5)):
			Doo::cache('front')->start('cache');

			echo time(); // тут любые операции echo и прямой вывод HTML кода

			Doo::cache('front')->end();
		endif;


		// прямое кеширование модели как реального объекта
		$cache = Doo::cache('php');
		if (!($obj_final = $cache->get('555'))) {
			$obj_final = new Pages;
			$obj_final->load(8);
			$obj_cachind = $obj_final->to_cache();
			$cache->set('555', $obj_cachind, 300);
		}

		_xdump($obj_final);

		// кеширование переменной
		$cache = Doo::cache('file');

		$m = new stdClass();
		$m->s = array(1, 2, 3);
		$m->ttttttttttt = 'sdfsdfsdfsd';
		$s = array(562 => array('one' => 'two', 5 => $m));

		$cache->setIn('system', 321, $s, 50); // кеш с группой
		$cache->set('123', $m, 300); // прямой кеш

		$r = $cache->get('123'); // получение кеша
	}

	public static function viewtest() {

		echo time();
		
		//вьюшка должна возвращать массив параметров, в котором можно указывать и название шаблона вьюшки ( в пределах определённого каталог ) и тип вывода HTML/JSON
		return array(
			'aaa' => 'bbbb',
			'bb' => array(
				1, 2, 3
			),
			'template' => 'new', // переопределили шаблон вьюшки используемый по умолчанию
		//'as_json'=>true - можно выводить результат как JSON объект
		);
	}

}