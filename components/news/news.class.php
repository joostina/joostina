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
 * Class News
 * @package	News
 * @subpackage	Joostina CMS
 * @created	2010-10-03 00:41:28
 */
class News extends JDBmodel {

	/**
	 * @var int(11)
	 */
	public $id;
	/**
	 * @var varchar(200)
	 */
	public $title;
	/**
	 * @var varchar(200)
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
	 * @var int(11)
	 */
	public $image_id;
	/**
	 * @var tinyint(1)
	 */
	public $type_id;
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
		$this->JDBmodel('#__news', 'id');
	}

	public function check() {
		$this->filter(array('fulltext'));
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
				'html_edit_element' => 'edit',
				'html_edit_element_param' => array(),
			),
			'date'=> array(
				'name' => 'Создано',
				'editable' => false,
				'in_admintable' => true,
				'html_table_element' => 'extra',
				'html_table_element_param' => array(
					'call_from' => 'News::show_date',
					'width'=>'80px',
					'align'=>'center',
				),
				'html_edit_element' => 'edit',
				'html_edit_element_param' => array(),
			),
			'title' => array(
				'name' => 'Заголовок',
				'editable' => true,
				'in_admintable' => true,
				'html_table_element' => 'editlink',
				'html_table_element_param' => array(),
				'html_edit_element' => 'edit',
				'html_edit_element_param' => array(),
			),
			'state' => array(
				'name' => 'Опубликовано',
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
			'type_id' => array(
				'name' => 'Категория',
				'editable' => true,
				'in_admintable' => true,
				'html_table_element' => 'one_from_array',
				'html_table_element_param' => array(
					'call_from' => 'News::get_types'
				),
				'html_edit_element' => 'option',
				'html_edit_element_param' => array(
					'call_from' => 'News::get_types'
				),
			),
			'slug' => array(
				'name' => 'slug',
				'editable' => false,
				'in_admintable' => true,
				'html_table_element' => 'value',
				'html_table_element_param' => array(),
				'html_edit_element' => 'edit',
				'html_edit_element_param' => array(),
			),
			'introtext' => array(
				'name' => 'Анонс ( без HTML )',
				'editable' => true,
				'in_admintable' => true,
				'html_table_element' => 'value',
				'html_table_element_param' => array(),
				'html_edit_element' => 'edit',
				'html_edit_element_param' => array(),
			),
			'fulltext' => array(
				'name' => 'Полный текст ( с HTML )',
				'editable' => true,
				'in_admintable' => true,
				'html_table_element' => 'value',
				'html_table_element_param' => array(),
				'html_edit_element' => 'text_area_wysiwyg',
				'html_edit_element_param' => array(),
			),
			'image_id' => array(
				'name' => 'Изображение',
				'editable' => true,
				'in_admintable' => true,
				'html_table_element' => 'value',
				'html_table_element_param' => array(),
				'html_edit_element' => 'edit',
				'html_edit_element_param' => array(),
			),
			'created_at' => array(
				'name' => 'Создано',
				'editable' => true,
				'in_admintable' => true,
				'html_table_element' => 'value',
				'html_table_element_param' => array(),
				'html_edit_element' => 'edit',
				'html_edit_element_param' => array(),
			),
		);
	}

	public function get_tableinfo() {
		return array(
			'header_list' => 'Новости',
			'header_new' => 'Создание новости',
			'header_edit' => 'Редактирование новости'
		);
	}

	public static function get_types() {
		return array(
			1 => 'Новости рас',
			2 => 'Новости два'
		);
	}

	public static function get_types_slug() {
		return array(
			1 => 'one',
			2 => 'two'
		);
	}

	public static function get_types_slug_by_type_id( $type_id ) {
		$types = self::get_types_slug();
		return $types[$type_id];
	}

	public static function show_date( stdClass $obj ){
		return mosFormatDate($obj->created_at, '%d/%m/%Y' );
	}


}
