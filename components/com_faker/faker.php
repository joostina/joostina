<?php

/**
 * @package Joostina
 * @copyright Авторские права (C) 2008-2010 Joostina team. Все права защищены.
 * @license Лицензия http://www.gnu.org/licenses/gpl-2.0.htm GNU/GPL, или help/license.php
 * Joostina! - свободное программное обеспечение распространяемое по условиям лицензии GNU/GPL
 * Для получения информации о используемых расширениях и замечаний об авторском праве, смотрите файл help/copyright.php.
 */
// запрет прямого доступа
defined('_VALID_MOS') or die();

/**
 * Пацанска базадурьюзаполнялка
 * Работает на основе:
 * 
 * Class for generating fake data
 * @package Faker
 * @version 0.2
 * @copyright 2007 Caius Durling
 * @author Caius Durling
 * @author ifunk
 * @author FionaSarah
 */
class actionsFaker {

	public static function index($option, $id, $page, $task) {

		require_once 'plugins/caius-php-faker/faker.php';

		$f = new Faker;

		self::topicfuck($f);

		// цепляем нужную модельку
	}

	public static function gamefuck($f) {
		require_once ( mosMainFrame::getInstance()->getPath('class', 'games'));

		$model = new Games;

		for ($index = 0; $index < 505; $index++) {

			$model->title = $f->Company->name;
			$model->title_rus = $f->Company->name;
			$model->desc = implode('<br />', $f->Lorem->paragraphs);
			$model->date = sprintf('%s-%s-%s', rand(1900, 2010), rand(1, 12), rand(1, 31));
			$model->date_rus = sprintf('%s-%s-%s', rand(1900, 2010), rand(1, 12), rand(1, 31));
			$model->developer = $f->Company->name;
			$model->publisher = $f->Company->name;
			$model->publisher_rus = $f->Company->name;
			$model->localizer = $f->Company->name;
			$model->publisher_rus = $f->Company->name;
			$model->site = $f->Internet->domain_name;
			$model->site_rus = $f->Internet->domain_name;
			$model->min_req = $f->Lorem->paragraph;
			$model->recom_req = $f->Lorem->paragraph;
			$model->state = 1;
			$model->created_at = _CURRENT_SERVER_TIME;
			$model->image_id = rand(1, 22);

			$_POST['gameplatforms'] = array(rand(1, 12), rand(1, 12), rand(1, 12), rand(1, 12), rand(1, 12), rand(1, 12), rand(1, 12));
			$_POST['gameganres'] = array(rand(1, 7), rand(1, 7), rand(1, 7), rand(1, 7));

			$model->store();
			$model->reset();
		}
	}

	public static function topicfuck($f) {
		require_once ( mosMainFrame::getInstance()->getPath('class', 'topic'));

		$model = new Topic;

		for ($index = 0; $index < 500; $index++) {

			$type_cat_ids = Topic::get_types_cat();
			$type_cat_id = array_rand($type_cat_ids);

			$type_id = call_user_func($type_cat_ids[$type_cat_id][2]);
			$type_id = array_rand($type_id);

			$model->title = $f->Company->name;
			$model->type_id = $type_id;
			$model->type_cat_id = $type_cat_id;
			$model->game_id = rand(1, 35);
			$model->game_name = $f->Company->name;
			$model->anons = $f->Lorem->paragraph;
			$model->cut_text = $f->Name->name;
			$model->fulltext = implode('<br />', $f->Lorem->paragraphs);
			$model->source = $f->Internet->domain_name;
			$model->href = $f->Internet->domain_name;
			$model->user_id = rand(1, 10);
			$model->user_login = $f->Name->name;
			$model->file_id = rand(10, 20);
			$model->file_name = $f->Name->name;
			$model->file_size = rand(10, 99999999);
			$model->state = 1;
			$model->created_at = _CURRENT_SERVER_TIME;

			$model->store();
			$model->reset();

			//_xdump($model);
			//die();
		}
	}

	public static function commentfuck($f) {
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

			//_xdump($model);
			//die();
		}
	}

}