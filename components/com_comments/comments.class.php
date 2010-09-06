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
 * Модель Comments
 */
class Comments extends mosDBTable {

	public $id;
	public $parent_id;
	public $path;
	public $level;
	public $obj_id;
	public $obj_option;
	public $user_id;
	public $user_name;
	public $user_email;
	public $user_ip;
	public $comment_text;
	public $created_at;
	public $state;

	function __construct() {
		$this->mosDBTable('#__comments', 'id');
	}

	public function get_fieldinfo() {
		return array(
			'sid' => array(
				'name' => 'ID',
				'editable' => false,
				'sortable' => false,
				'in_admintable' => true,
				'html_table_element' => 'value',
				'html_table_element_param' => array(
					'width' => '20px',
					'align' => 'center'
				)
			),
			'obj_id' => array(
				'name' => 'ID объекта',
				'editable' => false,
				'sortable' => false,
				'in_admintable' => true,
				'html_table_element' => 'value',
				'html_table_element_param' => array(
					'width' => '20px',
					'align' => 'center'
				)
			),
			'obj_option' => array(
				'name' => 'Тип объекта',
				'editable' => false,
				'sortable' => false,
				'in_admintable' => true,
				'html_table_element' => 'value',
				'html_table_element_param' => array(
					'width' => '50px',
					'align' => 'center'
				)
			),
			'user_id' => array(
				'name' => 'Логин / ID пользователя',
				'editable' => false,
				'sortable' => false,
				'in_admintable' => true,
				'html_table_element' => 'one_from_array',
				'html_table_element_param' => array(
					'align' => 'center',
					'call_from' => 'Comments::get_users_array'
				),
			),
			'user_name' => array(
				'name' => 'Имя пользователя (для неавторизованных)',
				'editable' => false,
				'sortable' => false,
				'in_admintable' => true,
				'html_table_element' => 'value',
				'html_table_element_param' => array(
					'align' => 'center'
				)
			),
			'user_email' => array(
				'name' => 'Email пользователя (для неавторизованных)',
				'editable' => false,
				'sortable' => false,
				'in_admintable' => true,
				'html_table_element' => 'value',
				'html_table_element_param' => array(
					'width' => '20px',
					'align' => 'center'
				)
			),
			'user_ip' => array(
				'name' => 'IP адрес пользователя',
				'editable' => false,
				'sortable' => false,
				'in_admintable' => true,
				'html_table_element' => 'value',
				'html_table_element_param' => array(
					'width' => '20px',
					'align' => 'center'
				)
			),
			'comment_text' => array(
				'name' => 'Текст комментария',
				'editable' => true,
				'sortable' => true,
				'in_admintable' => true,
				'editlink' => true,
				'html_edit_element' => 'text_area',
				'html_edit_element_param' => array(
					'height' => 100,
				),
				'html_table_element' => 'editlink',
				'html_table_element_param' => array(
					'text_limit' => 50,
				)
			),
			'state' => array(
				'name' => 'Состояние',
				'editable' => true,
				'sortable' => true,
				'in_admintable' => true,
				'editlink' => true,
				'html_edit_element' => 'checkbox',
				'html_table_element' => 'state_box',
				'html_edit_element_param' => array(
					'text' => 'Опубликовано',
				),
				'html_table_element' => 'statuschanger',
				'html_table_element_param' => array(
					'statuses' => array(
						0 => 'Скрыто',
						1 => 'Опубликовано'
					),
					'images' => array(
						0 => 'publish_x.png',
						1 => 'publish_g.png',
					),
					'align' => 'center',
					'class' => 'td-state-joiadmin',
				)
			),
		);
	}

	public function check() {
		$this->filter();
		return true;
	}

	/**
	 * Информация для страниц вывода данных о комментариях
	 * @return array массив информации дял построителя интерфейса
	 */
	public function get_tableinfo() {
		return array(
			'header_list' => 'Комментарии',
			'header_new' => 'Создание комментария',
			'header_edit' => 'Редактирование комментария'
		);
	}

	/**
	 * После сохранения комментария в БД
	 */
	public function after_insert() {

	}

	public static function get_users_array() {
		$obj = new mosUser();
		return $obj->get_selector(array('key' => 'id', 'value' => 'username'), array('select' => 'id, title'));
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

		$mf = mosMainFrame::getInstance();

		$this->obj_option = get_class($obj);
		$this->obj_id = $obj->{$obj->_tbl_key}; // настоящая уличная магия
		//
		//Подключаем пагинацию
		Jdocument::getInstance()
				->addJS(JPATH_SITE . '/includes/libraries/ajaxpager/media/js/jquery.paginate.js')
				->addCSS(JPATH_SITE . '/includes/libraries/ajaxpager/media/css/ajaxpager.css')
				->addCustomHeadTag(JHTML::js_code("var _comments_objoption = '$this->obj_option';var _comments_objid = $this->obj_id;var _comments_limit = $limit;var _comments_display = $visible_pages;"))
				->addJS(JPATH_SITE . '/components/com_comments/media/js/comments.js');
	}

	/**
	 * Вывод древовидного представления комментариев
	 * @var $obj Объект комментирования
	 */
	public function load_comments_tree($obj) {

		$mf = mosMainFrame::getInstance();
		require_once ($mf->getPath('front_html', 'com_comments'));

		$this->obj_option = get_class($obj);
		$this->obj_id = $obj->{$obj->_tbl_key}; // настоящая уличная магия
		//
		//JS объявления, необходимые для загрузки первой страницы комментариев
		$script = JHTML::js_code("var _comments_objoption = '$this->obj_option';var _comments_objid = $this->obj_id;");
		Jdocument::getInstance()
				->addCustomHeadTag($script)
				->addJS(JPATH_SITE . '/components/com_comments/media/js/comments_tree.js');

		$comments_list = $this->get_comments();

		if ($comments_list) {
			//Выводим список комментариев
			CommentsHTML::lists($comments_list);
		}

		CommentsHTML::addform();
	}

	/**
	 * Получение списка комментариев
	 * @param integer $offset смещение
	 * @param integer $limit лимит для постранички
	 * @return array массив объектов комментариев
	 */
	public function get_comments($offset = 0, $limit = 0) {
		$sql = 'SELECT c.*,c.parent_id as parent, u.username as user_name, 123 AS votesresults
            FROM #__comments AS c
            LEFT JOIN `#__users` AS u ON (u.id = c.user_id)
            WHERE  c.state=1 AND c.obj_option = \'' . $this->obj_option . '\' AND c.obj_id = \'' . $this->obj_id . '\'            
            GROUP BY c.id
            ORDER BY c.parent_id, c.created_at ASC';
		return $this->_db->setQuery($sql, $offset, $limit)->loadObjectList();
	}

	/**
	 * Изменение счётчиков общего количества комментариев по объекту и по пользователю
	 */
	private function update_counters() {
		//По объекту
		$sql = sprintf("INSERT INTO `#__comments_counter` (`obj_id`, `obj_option`, `last_user_id`, `last_comment_id`,`counter`)
		VALUES (%s, '%s', %s, %s,1)
		ON DUPLICATE KEY UPDATE counter=counter+1;",
						$this->obj_id, $this->obj_option, $this->user_id, $this->id);
		return $this->_db->setQuery($sql)->query();
	}

	public static function get_counters($obj) {
		$r = new stdClass(); // new self
		$r->count = rand(1, 1000);
		$r->last_user_id = rand(1, 10);
		$r->last_comment_id = rand(1, 1000);

		mosMainFrame::addLib('text');
		$r->count_text = Text::declension($r->count, array('комментарий', 'комментария', 'комментариев'));

		return $r;
	}

}