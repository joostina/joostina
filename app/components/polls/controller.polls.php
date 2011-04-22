<?php

// запрет прямого доступа
defined('_JOOS_CORE') or die();

/**
 * Polls - Компонент управления голосованиями
 * Контроллер сайта
 *
 * @version 1.0
 * @package Joostina.Components.Controllers
 * @subpackage Polls    
 * @author Joostina Team <info@joostina.ru>
 * @copyright (C) 2008-2011 Joostina Team
 * @license MIT License http://www.opensource.org/licenses/mit-license.php
 * Информация об авторах и лицензиях стороннего кода в составе Joostina CMS: docs/copyrights
 *
 * */
class actionsPolls extends joosController {

	public static function index() {

		if (!isset($_POST['question'])) {
			joosRoute::redirect(JPATH_SITE, 'Нужно ответить на вопросы');
			return array();
		}

		$poll = new Polls;
		if (!$poll->load($_POST['poll_id'])) {
			joosRoute::redirect(JPATH_SITE, 'Нет такого опроса');
			return array();
		}

		$polls_users = new PollsUsers;
		if ($polls_users->already_vote($poll->id)) {
			joosRoute::redirect(JPATH_SITE, 'Вы уже голосовали', 'error');
			return array();
		}

		$poll_results = new PollsResults;

		if ($poll_results->save_results($poll->id, joosRequest::post('question'))) {

			$poll->total_users = $poll->total_users + 1;
			$poll->store();

			joosRoute::redirect('/polls/' . $poll->id, 'Ваш голос учтён!');
			return array();
		} else {
			joosRoute::redirect(JPATH_SITE, 'Что-то пошло не так(');
			return array();
		}


		//_xdump($_POST);

		return array(
			'poll' => '',
		);
	}

	public static function view() {

		// номер просматриваемой записи
		$id = self::$param['id'];

		// формируем и загружаем просматриваемую запись
		$poll = new Polls;
		$poll->load($id) ? null : self::error404();

		$_poll_results = new PollsResults;
		$_poll_results = $_poll_results->get_list(array('where' => 'poll_id = ' . $poll->id, 'key' => ''));
		$poll_results = array();
		foreach ($_poll_results as $res) {
			$poll_results[$res->question_id][$res->variant_id] = $res;
		}

		// одно из вышеобозначенных действий зафиксировало ошибку, прекращаем работу
		if (self::$error) {
			return;
		}

		// устанавливаем заголовок страницы
		joosDocument::instance()
				->set_page_title($poll->title);


		// передаём параметры записи и категории в которой находится запись для оформления
		return array(
			'poll' => $poll,
			'poll_results' => $poll_results
		);
	}

}