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
				//'html_edit_element' => 'text',
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

}