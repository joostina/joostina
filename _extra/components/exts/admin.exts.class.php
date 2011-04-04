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

joosLoader::model('exts');

class adminExts extends Exts {

	public function get_fieldinfo() {
		return array(
			'id' => array(
				'name' => 'id',
				'editable' => false,
				'in_admintable' => false,
				'html_table_element' => 'value',
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
			'title' => array(
				'name' => 'Название',
				'editable' => true,
				'in_admintable' => true,
				'html_table_element' => 'editlink',
				'html_edit_element' => 'edit'
			),
			'slug' => array(
				'name' => 'Текст ссылки',
				'editable' => false,
				'in_admintable' => true,
				'html_table_element' => 'value',
				'html_edit_element' => 'edit'
			),
			'text_desc' => array(
				'name' => 'Полный текст описания (можно HTML)',
				'editable' => true,
				'in_admintable' => true,
				'html_table_element' => 'value',
				'html_edit_element' => 'edit'
			),
			'text_install' => array(
				'name' => 'Установка (можно HTML)',
				'editable' => true,
				'in_admintable' => true,
				'html_table_element' => 'value',
				'html_edit_element' => 'edit'
			),
			'text_hints' => array(
				'name' => 'Советы по работе (можно HTML)',
				'editable' => true,
				'in_admintable' => true,
				'html_table_element' => 'value',
				'html_edit_element' => 'edit'
			),
			'version' => array(
				'name' => 'Номер версии',
				'editable' => true,
				'in_admintable' => true,
				'html_table_element' => 'value',
				'html_table_element_param' => array(
					'align' => 'center',
					'width' => '50px',
				),
				'html_edit_element' => 'edit',
				'html_edit_element_param' => array(),
			),
			'released_at' => array(
				'name' => 'Дата релиза',
				'editable' => true,
				'in_admintable' => true,
				'html_table_element' => 'value',
				'html_edit_element' => 'edit',
				'html_edit_element_param' => array(),
			),
			'type_id' => array(
				'name' => 'Типа расширения',
				'editable' => true,
				'in_admintable' => true,
				'html_table_element' => 'one_from_array',
				'html_table_element_param' => array(
					'call_from' => 'adminExts::get_types_title',
					'width' => '120px',
				),
				'html_edit_element' => 'option',
				'html_edit_element_param' => array(
					'call_from' => 'adminExts::get_types_title'
				),
			),
			'user_id' => array(
				'name' => 'Автор расширения',
				'editable' => true,
				'sortable' => false,
				'in_admintable' => true,
				'html_table_element' => 'one_from_array',
				'html_table_element_param' => array(
					'align' => 'center',
					'call_from' => 'adminExts::get_users_array'
				),
				'html_edit_element' => 'option',
				'html_edit_element_param' => array(
					'align' => 'center',
					'call_from' => 'adminExts::get_users_array'
				),
			),
			'extcats' => array(
				'name' => 'Категории расширения',
				'editable' => true,
				'sortable' => false,
				'in_admintable' => false,
				'html_edit_element' => 'extra',
				'html_edit_element_param' => array(
					'align' => 'center',
					'call_from' => 'adminExts::get_cats_selectors'
				),
			),
			'created_at' => array(
				'name' => 'created_at',
				'editable' => false,
				'in_admintable' => true,
				'html_table_element' => 'value',
				'html_edit_element' => 'edit',
				'html_edit_element_param' => array(),
			),
		);
	}

	public function get_tableinfo() {
		return array(
			'header_list' => 'Расширения',
			'header_new' => 'Создание информации о расширении',
			'header_edit' => 'Редактирование информации о расширении'
		);
	}

	public function get_extrainfo() {
		return array(
			'search' => array(
				'title', 'slug'
			),
		/* 'filter' => array(
		  'category_id' => array(
		  'name' => 'Категория',
		  'call_from' => 'Blog::get_blog_cats'
		  ),
		  ), */
		);
	}

	public static function get_users_array() {
		$obj = new User();
		return $obj->get_selector(array('key' => 'id', 'value' => 'username'), array('select' => 'id, username'));
	}

}

class adminExtsAttr extends ExtsAttr {

	public function check() {
		$this->filter();
		return true;
	}

	public function get_fieldinfo() {
		return array(
			'id' => array(
				'name' => 'id',
				'editable' => false,
			),
			'title' => array(
				'name' => 'Название',
				'editable' => true,
				'in_admintable' => true,
				'html_table_element' => 'editlink',
				'html_edit_element' => 'edit',
				'html_edit_element_param' => array(),
			),
			'slug' => array(
				'name' => 'Текст ссылки',
				'editable' => true,
				'in_admintable' => true,
				'html_table_element' => 'value',
				'html_edit_element' => 'edit',
				'html_edit_element_param' => array(),
			),
			'params' => array(
				'name' => 'Параметры аттрибута',
				'editable' => true,
				'in_admintable' => true,
				'html_table_element' => 'value',
				'html_edit_element' => 'edit',
				'html_edit_element_param' => array(),
			),
			'plugin' => array(
				'name' => 'Используемый плагин',
				'editable' => true,
				'in_admintable' => true,
				'html_table_element' => 'value',
				'html_edit_element' => 'edit',
				'html_edit_element_param' => array(),
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
		);
	}

	public function get_tableinfo() {
		return array(
			'header_list' => 'Аттрибуты расширений',
			'header_new' => 'Создание аттрибута',
			'header_edit' => 'Редактирование аттрибута'
		);
	}

	public function get_extrainfo() {
		return array(
			'search' => array(),
		//'filter' => array(),
		//'extrafilter' => array()
		);
	}

}

class adminExtsAttrValues extends ExtsAttrValues {

	public function check() {
		$this->filter();
		return true;
	}

	public function get_fieldinfo() {
		return array(
			'id' => array(
				'name' => 'id',
				'editable' => false,
			),
			'attr_id' => array(
				'name' => 'Аттрибут',
				'editable' => true,
				'in_admintable' => true,
				'html_table_element' => 'one_from_array',
				'html_table_element_param' => array(
					'align' => 'center',
					'call_from' => 'adminExtsAttrValues::get_attrs_array'
				),
				'html_edit_element' => 'option',
				'html_edit_element_param' => array(
					'align' => 'center',
					'call_from' => 'adminExtsAttrValues::get_attrs_array'
				),
			),
			'title' => array(
				'name' => 'Значение аттрибута',
				'editable' => true,
				'in_admintable' => true,
				'html_table_element' => 'editlink',
				'html_edit_element' => 'edit',
				'html_edit_element_param' => array(),
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
		);
	}

	public function get_tableinfo() {
		return array(
			'header_list' => 'Значения аттрибутов расширений',
			'header_new' => 'Создание значения аттрибута',
			'header_edit' => 'Редактирование значения аттрибута'
		);
	}

	public function get_extrainfo() {
		return array(
			'search' => array('title'),
			'filter' => array(
				'attr_id' => array(
					'name' => 'Аттрибут',
					'call_from' => 'adminExtsAttrValues::get_attrs_array'
				),
			),
		);
	}

	public static function get_attrs_array() {
		$obj = new adminExtsAttr();
		return $obj->get_selector(array('key' => 'id', 'value' => 'title'), array('select' => 'id, title'));
	}

}

class adminExtsCats extends ExtsCats {

	public function check() {
		$this->filter();
		return true;
	}

	public function before_insert() {
		return true;
	}

	public function after_insert() {
		return true;
	}

	public function before_update() {
		return true;
	}

	public function after_update() {
		return true;
	}

	public function before_store() {
		joosLoader::lib('text');
		$this->slug = Text::str_to_url($this->title);
	}

	public function after_store() {
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
				'in_admintable' => true,
				'html_table_element' => 'value',
				'html_edit_element' => 'edit',
				'html_edit_element_param' => array(),
			),
			'title' => array(
				'name' => 'Название',
				'editable' => true,
				'in_admintable' => true,
				'html_table_element' => 'editlink',
				'html_edit_element' => 'edit',
			),
			'slug' => array(
				'name' => 'Текст ссылки',
				'editable' => true,
				'in_admintable' => true,
				'html_table_element' => 'value',
				'html_edit_element' => 'edit',
				'html_edit_element_param' => array(),
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
		);
	}

	public function get_tableinfo() {
		return array(
			'header_list' => 'Категории расширений',
			'header_new' => 'Создание категории расширений',
			'header_edit' => 'Редактирование категории расширений'
		);
	}

	public function get_extrainfo() {
		return array(
			'search' => array('title'),
		//'filter' => array(),
		//'extrafilter' => array()
		);
	}

}