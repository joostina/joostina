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

/**
 * Модель Bookmarks - пользовательские закладки
 */
class Bookmarks extends mosDBTable {

	public $id;
	public $user_id;
	public $obj_id;
	public $obj_option;
	public $obj_task;
	public $created_at;

	function __construct() {
		$this->mosDBTable('#__bookmarks', 'id');
	}

	public function check() {
		$this->filter();
	}

	/**
	 * Добавление элемента в закладки
	 * @param string $option - название компонента
	 * @param integer $id - идентификатор элемента компонента
	 * @param string $task - задача компонента
	 * @return boolean or error obj - результат выполнения вставки или объект с данными о ошибке
	 */
	public static function add($option, $id, $task='') {
		$bookmarks = new self;
		$bookmarks->user_id = User::current()->id;
		$bookmarks->obj_id = $id;
		$bookmarks->obj_option = $option;
		$bookmarks->obj_task = $task;

		if ($bookmarks->find()) {
			// удаляем существующую закладку
			$bookmarks->delete();
			// счетчик уменьшаем
			$bookmarks->update_counters(false);
			// обновляем кеш закладок текущего пользователя
			$bookmarks->update_user_cache();

			// считаем сколкьо раз это содержимое внесено в закладки
			$bookmarks_counter = BookmarksCounter::get_count($id, $option, $task);

			// вывод сообщения в зависимости от типа добавляемой закладки
			$message = array(
				'games_love' => 'Игра убрана из Любимых',
				'games_trace' => 'Игра убрана из списка наблюдения',
				'topic' => 'Избраное разибраненно'
			);
			
			$labels = array(
				'games_love' => 'добавить в любимые',
				'games_trace' => 'следить за игрой',
				'topic' => ''
			);

			// информируем
			return json_encode(array('message' => $message[$option], 'task' => 'unactive', 'count' => $bookmarks_counter->counter, 'label' => $labels[$option]));
			//return json_encode(array('error' => sprintf('%s, такая закладка у тебя уже есть', User::current()->username)));
		}

		$bookmarks->created_at = _CURRENT_SERVER_TIME;

		// вывод сообщения в зависимости от типа добавляемой закладки
		$message = array(
			'games_love' => 'Игра добавлена в Любимые',
			'games_trace' => 'Игра добавлена в список наблюдения',
			'topic' => 'Отлично, новая закладка добавлена!',
		);
		$labels = array(
				'games_love' => 'удалить из любимых',
				'games_trace' => 'не следить за игрой',
				'topic' => ''
			);

		$result = $bookmarks->store() ? array('message' => $message[$option], 'option' => $option, 'label' => $labels[$option]) : array('error' => 'Упс, закладка уже есть, или у нас проблемы...');

		$bookmarks_counter = BookmarksCounter::get_count($id, $option, $task);

		// общее число закладок на этот элемент в базе
		$result['count'] = $bookmarks_counter->counter;

		$where = array();
		$where[] = "user_id = " . User::current()->id;
		$where[] = "obj_option = '" . $option . "'";
		$where[] = "obj_task = '" . $task . "'";

		// число закладок текущего типа конкретного пользователя
		$result['current_count'] = $bookmarks->count(' WHERE ' . implode(' AND ', $where));

		// для добавления в любыие и отслеживае игры - выполняем обновление рейтинга
		( $option == 'games_love' || $option == 'games_trace' ) ? self::update_game_rate($id, $option) : null;

		// сделать значек избранного активным
		$result['task'] = 'active';
		return json_encode($result);
	}

	// после каждого добавления в закладки
	public function after_store() {
		// после записи закладки обновим счетчики
		$this->update_counters();
		$this->update_user_cache();
	}

	// обновление кеша закладок текущего пользователя
	private function update_user_cache() {
		mosMainFrame::getInstance()->getPath('class', 'com_users');
		UserExtra::update_bookmarks_cache(User::current());
	}

	public static function addlink($obj) {
		return sprintf('<button class="to_bookmarks" obj_option="%s" obj_id="%s">в закладки!</button>', get_class($obj), $obj->id);
	}

	public static function by_user(User $user) {
		return database::getInstance()->setQuery('SELECT count(id) FROM #__bookmarks WHERE user_id = ' . $user->id)->loadResult();
	}

	// TODO а это зачем?
	public static function by_user_list(User $user) {
		return database::getInstance()->setQuery('SELECT * FROM #__bookmarks WHERE user_id = ' . $user->id . ' AND obj_option!=\'games_trace\'')->loadObjectList();
	}

	public static function get_count(array $params = array()) {

		$u = isset($params['user_id']) ? 'AND user_id=' . $params['user_id'] : '';
		$o_id = isset($params['obj_id']) ? 'AND obj_id=' . $params['obj_id'] : '';
		$o_option = isset($params['obj_option']) ? "AND obj_option='" . $params['obj_option'] . "'" : '';
		$o_task = isset($params['obj_task']) ? "AND obj_task='" . $params['obj_task'] . "'" : '';

		$sql = sprintf('SELECT count(id) FROM #__bookmarks WHERE true %s %s %s %s', $u, $o_id, $o_option, $o_task);

		return database::getInstance()->setQuery($sql)->loadResult();
	}

	private function update_counters($more = true) {
		$sql = sprintf("INSERT INTO `#__bookmarks_counter` (`obj_id`, `obj_option`,`obj_task`,`counter`)
            VALUES (%s, '%s','%s',1)
            ON DUPLICATE KEY UPDATE counter=counter" . ($more ? '+1' : '-1' ),
						$this->obj_id, $this->obj_option, $this->obj_task);
		return $this->_db->setQuery($sql)->query();
	}

	// обновление рейтинга игры
	private static function update_game_rate($game_id, $option) {

		require_once mosMainFrame::getInstance()->getPath('class', 'com_games');

		$game = new Games;
		$game->load($game_id);

		// если игра не существует - рейтинг ей не обновляем
		if (!$game->id) {
			return;
		}

		// расширяем рейтинг игры за счет добавления в закладки и любиые игры
		$option == 'games_love' ? GameRatings::add_in_love($game, 10) : null;
		$option == 'games_trace' ? GameRatings::add_in_trace($game, 5) : null;
	}

}

class BookmarksCounter extends mosDBTable {

	public $id;
	public $obj_id;
	public $obj_option;
	public $obj_task;
	public $counter;

	function __construct() {
		$this->mosDBTable('#__bookmarks_counter', 'id');
	}

	public static function get_count($id, $option, $task) {
		$bookmarks_counter = new self;
		$bookmarks_counter->obj_id = $id;
		$bookmarks_counter->obj_option = $option;
		$bookmarks_counter->obj_task = $task;
		$bookmarks_counter->find();
		return $bookmarks_counter;
	}

}