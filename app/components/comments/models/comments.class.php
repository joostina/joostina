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

/**
 * Модель Comments
 */

/**
 * Class Comments
 * @package    Comments
 * @subpackage    Joostina CMS
 * @created    2010-11-03 17:49:02
 */
class Comments extends joosDBModel {

	/**
	 * @var int(11) unsigned
	 */
	public $id;
	/**
	 * @var int(11) unsigned
	 */
	public $parent_id;
	/**
	 * @var varchar(255)
	 */
	public $path;
	/**
	 * @var tinyint(1)
	 */
	public $level;
	/**
	 * @var int(11) unsigned
	 */
	public $obj_id;
	/**
	 * @var varchar(30)
	 */
	public $obj_option;
	/**
	 * @var int(11) unsigned
	 */
	public $user_id;
	/**
	 * @var varchar(100)
	 */
	public $user_name;
	/**
	 * @var varchar(50)
	 */
	public $user_email;
	/**
	 * @var varchar(50)
	 */
	public $user_ip;
	/**
	 * @var mediumtext
	 */
	public $comment_text;
	/**
	 * @var timestamp
	 */
	public $created_at;
	/**
	 * @var longtext
	 */
	public $params;
	/**
	 * @var tinyint(1)
	 */
	public $state;

	/*
	 * Constructor
	 */
	function __construct() {
		$this->joosDBModel('#__comments', 'id');
	}

	public function check() {
		$this->filter();
		return true;
	}

	public function before_store() {
		$this->params = array();

		$plugin_file = __DIR__ . '/plugins/' . strtolower($this->obj_option) . '.php';
		if (is_file($plugin_file)) {
			require_once $plugin_file;
			$plugin_class = 'comments' . $this->obj_option;
			$this->params += array('href' => $plugin_class::href($this));
		}

		$this->params = json_encode($this->params);
	}

	/**
	 * После сохранения комментария в БД
	 */
	public function after_insert() {

		$this->update_counters();
	}

	/**
	 * Первая загрузка комментариев
	 * Метод используется при использовании постраничной навигации, комментарии - списком
	 * Загружаем первую страницу с комментариями и инициализируем пагинацию
	 * @var string $obj_option Тип объекта (компонент)
	 * @var integer $obj_id ID комментируемого объекта
	 * @var integer $limit Количество комменариев на страницу
	 * @var integer $visible_pages Количество кнопок с номерами страниц в видимой части пагинатора
	 */
	public function load_comments($obj, $limit = 10, $visible_pages = 10) {

		$this->obj_option = get_class($obj);
		$this->obj_id = $obj->{$obj->_tbl_key}; // настоящая уличная магия
		//
        //Подключаем пагинацию
		joosDocument::instance()
				->add_js_file(JPATH_SITE . '/includes/libraries/ajaxpager/media/js/jquery.paginate.js')
				->add_css(JPATH_SITE . '/includes/libraries/ajaxpager/media/css/ajaxpager.css')
				->add_custom_head_tag(joosHTML::js_code("var _comments_objoption = '$this->obj_option';var _comments_objid = $this->obj_id;var _comments_limit = $limit;var _comments_display = $visible_pages;"))
				->add_js_file(JPATH_SITE . '/components/comments/media/js/comments.js');
	}

	/**
	 * Вывод древовидного представления комментариев
	 * @var $obj Объект комментирования
	 */
	public function load_comments_tree($obj) {

		joosLoader::view('comments');

		$this->obj_option = get_class($obj);
		$this->obj_id = $obj->{$obj->_tbl_key}; // настоящая уличная магия
		//JS объявления, необходимые для загрузки первой страницы комментариев
		$script = joosHTML::js_code("var _comments_objoption = '$this->obj_option';var _comments_objid = $this->obj_id;");

		if (joosRequest::is_ajax()) {
			echo $script;
		} else {
			joosDocument::instance()
					->add_custom_head_tag($script)
					->add_js_file(JPATH_SITE . '/components/comments/media/js/comments_tree.js');
		}

		$comments_list = $this->get_comments();

		//Выводим список комментариев если они есть
		$comments_list ? commentsHTML::lists($comments_list) : commentsHTML::emptylists();

		commentsHTML::addform();
	}

	/**
	 * Получение списка комментариев
	 * @param integer $offset смещение
	 * @param integer $limit лимит для постранички
	 * @return array массив объектов комментариев
	 */
	public function get_comments($offset = 0, $limit = 0) {
		$comment = new Comments;
		return $comment->get_list(array(
			'select' => 'c.*,c.parent_id as parent, u.username as user_name',
			'join' => 'AS c LEFT JOIN `#__users` AS u ON (u.id = c.user_id)',
			'where' => 'c.state=1 AND c.obj_option = \'' . $this->obj_option . '\' AND c.obj_id = \'' . $this->obj_id . '\'',
			'order' => 'c.parent_id, c.created_at ASC',
			'limit' => $limit,
			'offset' => $offset,
				), array('comments', 'com-' . $this->obj_option));
	}

	/**
	 * Изменение счётчиков общего количества комментариев по объекту и по пользователю
	 */
	private function update_counters() {
		$sql = sprintf("INSERT INTO `#__comments_counter` (`obj_id`, `obj_option`, `last_user_id`, `last_comment_id`,`counter`)" .
				" VALUES (%s, '%s', %s, %s,1)" .
				" ON DUPLICATE KEY UPDATE counter=counter+1,last_user_id=%s,last_comment_id=%s ;", $this->obj_id, $this->obj_option, $this->user_id, $this->id, $this->user_id, $this->id);
		return $this->_db->set_query($sql)->query();
	}

	public static function get_counters($obj) {
		$r = new stdClass(); // new self
		$r->count = rand(1, 1000);
		$r->last_user_id = rand(1, 10);
		$r->last_comment_id = rand(1, 1000);

		joosLoader::lib('text');
		$r->count_text = joosText::declension($r->count, array('комментарий', 'комментария', 'комментариев'));

		return $r;
	}

}