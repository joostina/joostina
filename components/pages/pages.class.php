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

// странички
class Pages extends JDBmodel {

	public $id;
	public $title;
	public $title_page;
	public $slug;
	public $text;
	public $created_at;
	public $meta_keywords;
	public $meta_description;
	public $state;

	function __construct() {
		$this->JDBmodel('#__pages', 'id');
	}

	public function get_fieldinfo() {
		return array(
			'id' => array(
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
			'title' => array(
				'name' => 'Название',
				'editable' => true,
				'sortable' => true,
				'in_admintable' => true,
				'html_edit_element' => 'edit',
				'html_table_element' => 'editlink',
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
			'title_page' => array(
				'name' => 'Title страницы',
				'editable' => true,
				'in_admintable' => true,
				'html_edit_element' => 'edit',
				'html_table_element' => 'value',
			),
			'slug' => array(
				'name' => 'Ссылка страницы: <b>' . JPATH_SITE . '/</b>',
				'editable' => true,
				'sortable' => true,
				'in_admintable' => true,
				'html_edit_element' => 'edit',
				'html_table_element' => 'value',
			),
			'text' => array(
				'name' => 'Описание',
				'editable' => true,
				'html_edit_element' => 'text_area_wysiwyg',
				'html_edit_element_param' => array(
					'height' => 100,
				)
			),
			'tags' => array(
				'name' => 'Тэги',
				'editable' => true,
				'html_edit_element' => 'tags',
			),
			'meta_description' => array(
				'name' => 'META::description',
				'editable' => true,
				'sortable' => true,
				'in_admintable' => true,
				'editlink' => true,
				'html_edit_element' => 'text',
				'html_edit_element_param' => array(
					'rows' => 2,
					'cols' => 100
				)
			),
			'meta_keywords' => array(
				'name' => 'META::keywords',
				'editable' => true,
				'sortable' => true,
				'in_admintable' => true,
				'editlink' => true,
				'html_edit_element' => 'text',
				'html_edit_element_param' => array(
					'rows' => 2,
					'cols' => 100
				)
			),
		);
	}

	public function get_tableinfo() {
		return array(
			'header_list' => 'Страницы',
			'header_new' => 'Создание страницы',
			'header_edit' => 'Редактирование страницы'
		);
	}

	public function check() {
		$this->filter(array('text'));
		return true;
	}

}