<?php

// запрет прямого доступа
defined('_JOOS_CORE') or die();

/**
 * adminBlog - Модель блогов
 * Модель панели управления
 *
 * @version 1.0
 * @package Joostina.Models
 * @subpackage Blog
 * @author Joostina Team <info@joostina.ru>
 * @copyright (C) 2008-2011 Joostina Team
 * @license MIT License http://www.opensource.org/licenses/mit-license.php
 * Информация об авторах и лицензиях стороннего кода в составе Joostina CMS: docs/copyrights
 *
 * */
class adminBlog extends Blog {

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
					'call_from' => 'Blog::get_blog_cats',
					'width' => '120px',
				),
				'html_edit_element' => 'option',
				'html_edit_element_param' => array(
					'call_from' => 'Blog::get_blog_cats'
				),
			),
			'user_id' => array(
				'name' => 'Автор',
				'editable' => true,
				'in_admintable' => true,
				'html_table_element' => 'value',
				'html_table_element_param' => array(),
				'html_edit_element' => 'edit'
			),
			'introtext' => array(
				'name' => 'Текст вступления (без HTML)',
				'editable' => true,
				'in_admintable' => true,
				'html_table_element' => 'value',
				'html_table_element_param' => array(),
				'html_edit_element' => 'wysiwyg',
				'html_edit_element_param' => array(
					'editor' => 'none',
				)
			),
			'fulltext' => array(
				'name' => 'Полный текст записи (можно HTML)',
				'editable' => true,
				'in_admintable' => true,
				'html_table_element' => 'value',
				'html_table_element_param' => array(),
				'html_edit_element' => 'wysiwyg'
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
				'html_edit_element' => 'checkbox',
				'html_table_element' => 'state_box',
				'html_edit_element_param' => array(
					'text' => 'Опубликовано',
				),
				'html_table_element' => 'statuschanger',
				'html_table_element_param' => array(
					'align' => 'center',
					'class' => 'td-state-joiadmin',
					'width' => '20px',
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

	/*
	  public function get_extrainfo() {
	  return array(
	  'search' => array(
	  'title', 'slug'
	  ),
	  'filter' => array(
	  'category_id' => array(
	  'name' => 'Категория',
	  'call_from' => 'Blog::get_blog_cats'
	  ),
	  ),
	  );
	  }
	 */
}

/**
 * adminBlog_Category - Модель блогов
 * Модель панели управления
 *
 * @version 1.0
 * @package Joostina.Models
 * @subpackage Blog
 * @author Joostina Team <info@joostina.ru>
 * @copyright (C) 2008-2011 Joostina Team
 * @license MIT License http://www.opensource.org/licenses/mit-license.php
 * Информация об авторах и лицензиях стороннего кода в составе Joostina CMS: docs/copyrights
 *
 * */
class adminBlog_Category extends Blog_Category {

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
			'header_list' => 'Blog_Category',
			'header_new' => 'Создание Blog_Category',
			'header_edit' => 'Редактирование Blog_Category'
		);
	}

}