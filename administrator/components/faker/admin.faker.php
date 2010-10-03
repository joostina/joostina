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

mosMainFrame::addLib('joiadmin');
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
		self::newsfuck(new Faker);
	}

	private static function newsfuck($f) {
		require joosCore::path('news', 'class');

		$model = new News;

		for ($index = 0; $index < 555; $index++) {

			$model->title = $f->Company->name;
			$model->slug = Jstring::strtolower(urlencode($f->Company->name));
			$model->introtext = $f->Lorem->paragraph;
			$model->fulltext = implode('<br />', $f->Lorem->paragraphs);
			$model->type_id = rand(1, 2);
			$model->created_at = _CURRENT_SERVER_TIME;

			$model->store();
			$model->reset();
		}
	}

	private static function blogfuck($f) {
		require_once ( mosMainFrame::getInstance()->getPath('class', 'blog'));

		$model = new Blog;

		for ($index = 0; $index < 555; $index++) {

			$model->title = $f->Company->name;
			$model->slug = Jstring::strtolower(urlencode($f->Company->name));
			$model->introtext = $f->Lorem->paragraph;
			$model->fulltext = implode('<br />', $f->Lorem->paragraphs);
			$model->state = 1;
			$model->created_at = _CURRENT_SERVER_TIME;
			$model->category_id = rand(1, 80);

			$model->store();
			$model->reset();
		}
	}

	private static function blogcatfuck($f) {
		require_once ( mosMainFrame::getInstance()->getPath('class', 'blog'));

		$model = new BlogCategory;

		for ($index = 0; $index < 100; $index++) {

			$model->title = $f->Company->name;
			$model->slug = Jstring::strtolower(urlencode($f->Address->street_suffix));
			$model->description = $f->Lorem->paragraph;
			$model->state = 1;
			$model->created_at = _CURRENT_SERVER_TIME;

			$model->store();
			$model->reset();
		}
	}

	private static function commentfuck($f) {
		require_once ( mosMainFrame::getInstance()->getPath('class', 'comments'));

		$model = new Comments;

		for ($index = 0; $index < 1000; $index++) {

			$model->obj_option = 'Topic';
			$model->obj_id = rand(1, 505);
			$model->comment_text = $f->Lorem->paragraph;
			$model->user_id = rand(1, 10);
			$model->user_name = $f->Name->name;
			$model->created_at = _CURRENT_SERVER_TIME;
			$model->state = 1;

			$model->store();
			$model->reset();
		}
	}

}