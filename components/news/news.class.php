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
 * @created	2010-09-25 22:22:47
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
	 * @var tinyint(1)
	 */
	public $created_at;

	/*
	 * Constructor
	 * @param object Database object
	 */
	function __construct(){
		$this->JDBmodel( '#__news', 'id' );
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
				'html_edit_element' => 'text_area'
			),
			'fulltext' => array(
				'name' => 'Полный текст новости (можно HTML)',
				'editable' => true,
				'in_admintable' => true,
				'html_table_element' => 'value',
				'html_table_element_param' => array(),
				'html_edit_element' => 'text_area_wysiwyg'
			),
			'created_at' => array(
				'name' => 'Дата создания',
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
				'header_list' => 'Новости',
				'header_new' => 'Создание новости',
				'header_edit' => 'Редактирование новости'
			);
	}

}