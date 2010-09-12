<?php

/**
 * @package Joostina
 * @copyright Авторские права (C) 2007-2010 Joostina team. Все права защищены.
 * @license Лицензия http://www.gnu.org/licenses/gpl-2.0.htm GNU/GPL, или help/license.php
 * Joostina! - свободное программное обеспечение распространяемое по условиям лицензии GNU/GPL
 * Для получения информации о используемых расширениях и замечаний об авторском праве, смотрите файл help/copyright.php.
 */
// запрет прямого доступа
defined('_VALID_MOS') or die();

class actionsTest {

	public static function index() {
		for ($index = 0; $index < 25; $index++) {
			echo sprintf("<h1>%s</h1>", md5(time() . rand(0, 500)));
		}
	}

	public static function images() {

		mosMainFrame::addLib('images');
		$rotte = dirname(__FILE__) . '/media/files/';
		Thumbnail::output($rotte . 'original.jpg', $rotte . '/image_350x350.png', array('width' => 350, 'height' => 350));
		Thumbnail::output($rotte . 'image_350x350.png', $rotte . '/image_200x200.png', array('width' => 200, 'height' => 200, 'method' => THUMBNAIL_METHOD_CROP));
	}

	public static function memcacheimp() {
		echo time() . '<br />';

		mosMainFrame::addLib('memcacheimp');

		$cache = new Memcacheimp();

		$value = $cache->get('data-1');

		if ($value == NULL) {
			$cache->set('data-1', 'post content - 1 - ' . date('H:m:s'), array('posts', 'time', 'data'), 60 * 10);
		}

		$value = $cache->get('data-2');

		if ($value == NULL) {
			$cache->set('data-2', 'post content - 2 - ' . date('H:m:s'), array('posts', 'data'), 60 * 10);
		}


		echo $value = 'PS1: ' . $cache->get('data-1');
		echo $value = 'PS2: ' . $cache->get('data-2');


		$cache->delete_tag('time');
	}

	public static function cache() {

		mosMainFrame::addLib('doocache');

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
			$obj_cachind = $obj_final->tocache();
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

	public static function browser() {
		mosMainFrame::addLib('browser');
		$browser = new Browser();
		echo $browser->getBrowser();
	}

	public static function location() {
		mosMainFrame::addLib('russiastate');
		echo Russiastate::selector('mystate');
	}

	public static function torrents() {
		mosMainFrame::addLib('bittorrent');
		$bt = new BitTorrent();
		$info = $bt->decodeSource(file_get_contents(JPATH_BASE . '/media/torrents/pirate.torrent'));

		_xdump($info);
	}

	public static function viewtest(){

		//вьюшка должна возвращать массив параметров, в котором можно указывать и название шаблона вьюшки ( в пределах определённого каталог ) и тип вывода HTML/JSON
		return array(
			'aaa'=>'bbbb',
			'bb'=>array(
				1,2,3
			),
			'template'=>'new', // переопределили шаблон вьюшки используемый по умолчанию
			//'as_json'=>true - можно выводить результат как JSON объект
		);
	}

}
