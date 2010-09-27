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
 * Class Blog
 * @package	Blog
 * @subpackage	Joostina CMS
 * @created	2010-09-24 02:37:11
 */
class Blog extends JDBmodel {

	/**
	 * @var int(11) unsigned
	 */
	public $id;
	/**
	 * @var varchar(200)
	 */
	public $title;
	/**
	 * @var varchar(100)
	 */
	public $slug;
	/**
	 * @var text
	 */
	public $introtext;
	/**
	 * @var longtext
	 */
	public $fulltext;
	/**
	 * @var text
	 */
	public $params;
	/**
	 * @var timestamp
	 */
	public $created_at;
	/**
	 * @var tinyint(1)
	 */
	public $state;
	/**
	 * @var tinyint(2)
	 */
	public $category_id;

	/*
	 * Constructor
	 * @param object Database object
	 */
	function __construct() {
		$this->JDBmodel('#__blog', 'id');
	}

	public function check() {
		$this->filter( array('fulltext') );
		return true;
	}

	public function after_update() {
		return true;
	}

	public function after_store() {
		return true;
	}

	public function before_store() {
		return true;
	}

	public function before_delete() {
		return true;
	}

	public function get_fieldinfo() {
		return array(
			'id' => array(
				'name' => 'id',
				'editable' => false,
				'in_admintable' => false,
				'html_table_element' => 'value',
				'html_table_element_param' => array(),
				'html_edit_element' => 'edit'
			),
			'title' => array(
				'name' => 'Заголовок',
				'editable' => true,
				'in_admintable' => true,
				'html_table_element' => 'editlink',
				'html_table_element_param' => array(),
				'html_edit_element' => 'edit'
			),
			'slug' => array(
				'name' => 'slug',
				'editable' => false,
				'in_admintable' => true,
				'html_table_element' => 'value',
				'html_table_element_param' => array(),
				'html_edit_element' => 'edit'
			),
			'category_id' => array(
				'name' => 'Категория',
				'editable' => true,
				'in_admintable' => true,
				'html_table_element' => 'one_from_array',
				'html_table_element_param' => array(
					'call_from' => 'Blog::get_blog_cats'
				),
				'html_edit_element' => 'option',
				'html_edit_element_param' => array(
					'call_from' => 'Blog::get_blog_cats'
				),
			),
			'introtext' => array(
				'name' => 'Текст вступления (без HTML) ',
				'editable' => true,
				'in_admintable' => true,
				'html_table_element' => 'value',
				'html_table_element_param' => array(),
				'html_edit_element' => 'text'
			),
			'fulltext' => array(
				'name' => 'Полный текст записи (можно HTML)',
				'editable' => true,
				'in_admintable' => true,
				'html_table_element' => 'value',
				'html_table_element_param' => array(),
				'html_edit_element' => 'text_area_wysiwyg'
			),
			'params' => array(
				'name' => 'Параметры',
				'editable' => false,
				'in_admintable' => true,
				'html_table_element' => 'value',
				'html_table_element_param' => array(),
				'html_edit_element' => 'edit'
			),
			'created_at' => array(
				'name' => 'Дата создания',
				'editable' => true,
				'in_admintable' => true,
				'html_table_element' => 'value',
				'html_table_element_param' => array(),
				'html_edit_element' => 'edit'
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
			'tags' => array(
				'name' => 'Тэги',
				'editable' => true,
				'html_edit_element' => 'tags',
			),
		);
	}

	public function get_tableinfo() {
		return array(
			'header_list' => 'Блоги',
			'header_new' => 'Добавление записи в блог',
			'header_edit' => 'Редактирование блогозаписи'
		);
	}

	public static function get_blog_cats() {
		$obj = new BlogCategory();
		return $obj->get_selector(array('key' => 'id', 'value' => 'title'), array('select' => 'id, title','order'=>'title ASC'));
	}

}

/**
 * Class BlogCategory
 * @package	BlogCategory
 * @subpackage	Joostina CMS
 * @created	2010-09-24 02:37:11
 */
class BlogCategory extends JDBmodel {

	/**
	 * @var int(10) unsigned
	 */
	public $id;
	/**
	 * @var varchar(200)
	 */
	public $title;
	/**
	 * @var varchar(100)
	 */
	public $slug;
	/**
	 * @var text
	 */
	public $description;
	/**
	 * @var text
	 */
	public $params;
	/**
	 * @var timestamp
	 */
	public $created_at;
	/**
	 * @var tinyint(1)
	 */
	public $state;

	/*
	 * Constructor
	 * @param object Database object
	 */
	function __construct() {
		$this->JDBmodel('#__blog_category', 'id');
	}

	public function check() {
		$this->filter();
		return true;
	}

	public function after_update() {
		return true;
	}

	public function after_store() {
		return true;
	}

	public function before_store() {
		return true;
	}

	public function before_delete() {
		return true;
	}

	public function get_fieldinfo() {
		return array(
			'id' => array(
				'name' => 'id',
				'editable' => true,
				'in_admintable' => true,
				'html_table_element' => 'value',
				'html_table_element_param' => array(),
				'html_edit_element' => 'edit'
			),
			'title' => array(
				'name' => 'title',
				'editable' => true,
				'in_admintable' => true,
				'html_table_element' => 'value',
				'html_table_element_param' => array(),
				'html_edit_element' => 'edit'
			),
			'slug' => array(
				'name' => 'slug',
				'editable' => true,
				'in_admintable' => true,
				'html_table_element' => 'value',
				'html_table_element_param' => array(),
				'html_edit_element' => 'edit'
			),
			'description' => array(
				'name' => 'description',
				'editable' => true,
				'in_admintable' => true,
				'html_table_element' => 'value',
				'html_table_element_param' => array(),
				'html_edit_element' => 'edit'
			),
			'params' => array(
				'name' => 'params',
				'editable' => true,
				'in_admintable' => true,
				'html_table_element' => 'value',
				'html_table_element_param' => array(),
				'html_edit_element' => 'edit'
			),
			'created_at' => array(
				'name' => 'created_at',
				'editable' => true,
				'in_admintable' => true,
				'html_table_element' => 'value',
				'html_table_element_param' => array(),
				'html_edit_element' => 'edit'
			),
			'state' => array(
				'name' => 'state',
				'editable' => true,
				'in_admintable' => true,
				'html_table_element' => 'value',
				'html_table_element_param' => array(),
				'html_edit_element' => 'edit'
			),
		);
	}

	public function get_tableinfo() {
		return array(
			'header_list' => 'BlogCategory',
			'header_new' => 'Создание BlogCategory',
			'header_edit' => 'Редактирование BlogCategory'
		);
	}

}

