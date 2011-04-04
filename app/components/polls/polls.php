<?php

/**
 * @package Joostina
 * @copyright Авторские права (C) 2007-2010 Joostina team. Все права защищены.
 * @license Лицензия http://www.gnu.org/licenses/gpl-2.0.htm GNU/GPL, или help/license.php
 * Joostina! - свободное программное обеспечение распространяемое по условиям лицензии GNU/GPL
 * Для получения информации о используемых расширениях и замечаний об авторском праве, смотрите файл help/copyright.php.
 */
// запрет прямого доступа
defined('_JOOS_CORE') or die();

joosLoader::model('polls');

class actionsPolls extends joosController {

	public static function on_start($active_task) {

	}

	public static function index() {
		
		if(!isset($_POST['question'])){
			mosRedirect(JPATH_SITE, 'Нужно ответить на вопросы');
			return array();
		}
		
		$poll = new Polls;
		if(!$poll->load($_POST['poll_id'])){
			mosRedirect(JPATH_SITE, 'Нет такого опроса');
			return array();
		}
		
		$polls_users = new PollsUsers;
		if($polls_users->already_vote($poll->id)){
			mosRedirect(JPATH_SITE, 'Вы уже голосовали', 'error');
			return array();
		}
		
		$poll_results = new PollsResults;
		
		if(	$poll_results->save_results($poll->id, joosRequest::post('question'))){

			$poll->total_users = $poll->total_users + 1;
			$poll->store();
			
			mosRedirect('/polls/'.$poll->id, 'Ваш голос учтён!');
			return array();
			
		}
		else{
			mosRedirect(JPATH_SITE, 'Что-то пошло не так(');
			return array();
		}

		
		//_xdump($_POST);

		return array(
			'poll' => '',
		);
	}
	
	public static function view(){
		
		// номер просматриваемой записи
		$id = self::$param['id'];

		// формируем и загружаем просматриваемую запись
		$poll = new Polls;
		$poll->load($id) ? null : self::error404();
		
		$_poll_results = new PollsResults;
		$_poll_results = $_poll_results->get_list(array('where'=>'poll_id = '.$poll->id, 'key'=>''));
		$poll_results = array();
		foreach($_poll_results as $res){
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