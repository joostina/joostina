<?php

/**
 * @package Joostina
 * @copyright Авторские права (C) 2008-2010 Joostina team. Все права защищены.
 * @license Лицензия http://www.gnu.org/licenses/gpl-2.0.htm GNU/GPL, или help/license.php
 * Joostina! - свободное программное обеспечение распространяемое по условиям лицензии GNU/GPL
 * Для получения информации о используемых расширениях и замечаний об авторском праве, смотрите файл help/copyright.php.
 */
// запрет прямого доступа
defined('_JOOS_CORE') or die();

joosLoader::lib('joiadmin', 'system');
JoiAdmin::dispatch();

/**
 * Пацанска базадурьюзаполнялка
 * Работает на основе Faker, адаптированного для русского языка и php 5.3 - Joostina Team:
 * Class for generating fake data
 * @package Faker
 * @version 0.2
 * @copyright 2007 Caius Durling
 * @author Caius Durling
 * @author ifunk
 * @author FionaSarah
 */
class actionsFaker {

	public static function index() {

		require_once 'plugins/caius-php-faker/faker.php';

		//self::blogfuck(new Faker);
		//self::blogcatfuck(new Faker);
		//self::commentfuck(new Faker);
	}

	private static function newsfuck($f) {
		joosLoader::model('news');
		joosLoader::lib('text');


		$model = new News;

		for ($index = 0; $index < 555; $index++) {

			$model->title = $f->Company->name;
			$model->slug = Text::str_to_url($model->title);
			$model->introtext = $f->Lorem->paragraph;
			$model->fulltext = implode('<br />', $f->Lorem->paragraphs);
			$model->type_id = rand(1, 2);
			$model->created_at = _CURRENT_SERVER_TIME;

			$model->store();
			$model->reset();
		}
	}

	private static function blogfuck($f) {
		joosLoader::model('blog');
		joosLoader::lib('text');


		$model = new Blog;

		for ($index = 0; $index < 555; $index++) {

			$model->title = $f->Company->name;
			$model->slug = Text::str_to_url($model->title);
			$model->introtext = $f->Lorem->paragraph;
			$model->fulltext = implode('<br />', $f->Lorem->paragraphs);
			$model->state = 1;
			$model->created_at = _CURRENT_SERVER_TIME;
			$model->category_id = rand(1, 84);
			$model->user_id = 1;

			$model->store();
			$model->reset();
		}
	}

	private static function blogcatfuck($f) {
		joosLoader::model('blog');
		joosLoader::lib('text');

		$model = new BlogCategory;

		for ($index = 0; $index < 50; $index++) {

			$model->title = $f->Company->name;
			$model->slug = Text::str_to_url($model->title);
			$model->description = $f->Lorem->paragraph;
			$model->state = 1;
			$model->created_at = _CURRENT_SERVER_TIME;

			$model->store();
			$model->reset();
		}
	}

	private static function commentfuck($f) {
		joosLoader::model('comments');

		$model = new Comments;

		for ($index = 0; $index < 1000; $index++) {

			$model->obj_option = 'Blogs';
			$model->obj_id = rand(1, 505);
			$model->comment_text = $f->Lorem->paragraph;
			$model->user_id = rand(1, 10);
			$model->user_name = $f->Company->name;
			$model->created_at = _CURRENT_SERVER_TIME;
			$model->state = 1;

			$model->store();
			$model->reset();
		}
	}

}