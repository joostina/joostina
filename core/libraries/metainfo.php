<?php

// запрет прямого доступа
defined('_JOOS_CORE') or die();

/**
 * joosMetainfo - Библиотека рвботы с мета-данными
 * Системная библиотека
 *
 * @version 1.0
 * @package Joostina.Libraries
 * @subpackage Libraries
 * @subpackage Metainfo
 * @category Libraries
 * @author Joostina Team <info@joostina.ru>
 * @copyright (C) 2008-2011 Joostina Team
 * @license MIT License http://www.opensource.org/licenses/mit-license.php
 * Информация об авторах и лицензиях стороннего кода в составе Joostina CMS: docs/copyrights
 *
 * */
class joosMetainfo extends joosModel {

	/**
	 * @var int(11)
	 */
	public $id;
	/**
	 * @var varchar(255)
	 */
	public $group;
	/**
	 * @var varchar(255)
	 */
	public $subgroup;
	/**
	 * @var int(11) unsigned
	 */
	public $obj_id;
	/**
	 * @var varchar(255)
	 */
	public $meta_title;
	/**
	 * @var varchar(500)
	 */
	public $meta_description;
	/**
	 * @var varchar(255)
	 */
	public $meta_keywords;

	/*
	 * Constructor
	 */
	function __construct() {
		parent::__construct('#__metainfo', 'id');
	}

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
		return true;
	}

	public function after_store() {
		return true;
	}

	public function before_delete() {
		return true;
	}

	public static function get_fieldinfo() {
		$scheme = self::get_scheme();

		$add_fields = array(
			'group' => array(
				'name' => 'group',
				'editable' => true,
				'in_admintable' => true,
				'html_table_element' => 'value',
				'html_table_element_param' => array(),
				'html_edit_element' => 'hidden',
				'html_edit_element_param' => array(),
			),
			'subgroup' => array(
				'name' => 'subgroup',
				'editable' => true,
				'in_admintable' => true,
				'html_table_element' => 'value',
				'html_table_element_param' => array(),
				'html_edit_element' => 'hidden',
				'html_edit_element_param' => array(),
			),
		);

		return $scheme + $add_fields;
	}

	public static function get_scheme() {
		return array(
			'id' => array(
				'name' => 'id',
				'editable' => true,
				'in_admintable' => true,
				'html_table_element' => 'value',
				'html_table_element_param' => array(),
				'html_edit_element' => 'hidden',
				'html_edit_element_param' => array(),
			),
			'meta_title' => array(
				'name' => 'MetaTitle',
				'editable' => true,
				'in_admintable' => true,
				'html_table_element' => 'value',
				'html_table_element_param' => array(),
				'html_edit_element' => 'edit',
				'html_edit_element_param' => array(),
			),
			'meta_description' => array(
				'name' => 'MetaDescription',
				'editable' => true,
				'in_admintable' => true,
				'html_table_element' => 'value',
				'html_table_element_param' => array(),
				'html_edit_element' => 'edit',
				'html_edit_element_param' => array(),
			),
			'meta_keywords' => array(
				'name' => 'MetaKeywords',
				'editable' => true,
				'in_admintable' => true,
				'html_table_element' => 'value',
				'html_table_element_param' => array(),
				'html_edit_element' => 'edit',
				'html_edit_element_param' => array(),
			)
		);
	}

	public function get_tableinfo() {
		return array(
			'header_list' => 'Метаданные',
			'header_new' => 'Метаданные',
			'header_edit' => 'Метаданные'
		);
	}

	//Установка метаданных страницы

	/**
	 * joosMetainfo::set_meta()
	 * Устновка метаданных страницы: title, MetaDescription, MetaKeywords
	 *
	 * @param string $group Компонент
	 * @param string $subgroup Метод контроллера
	 * @param mixed $obj_id ID объекта
	 * @param array $defaults Массив дефолтных значение array('title'=>'', 'description'=>'', 'keywords'=>''))
	 */
	public static function set_meta($group, $subgroup, $obj_id = '', $defaults = array()) {

		$meta_title = isset($defaults['title']) ? $defaults['title'] : '';
		$meta_description = isset($defaults['description']) ? $defaults['description'] : '';
		$meta_keywords = isset($defaults['keywords']) ? $defaults['keywords'] : '';

		$meta_array = joosMetainfo::get_meta($group, $subgroup, $obj_id);

		if ($meta_array) {
			$meta_title = $meta_array['meta_title'] ? $meta_array['meta_title'] : $defaults['title'];
			$meta_description = isset($meta_array['meta_description']) ? $meta_array['meta_description'] : $defaults['description'];
			$meta_keywords = isset($meta_array['meta_keywords']) ? $meta_array['meta_keywords'] : $defaults['keywords'];
		}

		//Заголовок страницы
		joosDocument::instance()
				->add_title($meta_title);


		//MetaDescription
		if ($meta_description) {
			joosDocument::instance()
					->add_meta_tag('description', $meta_description);
		}

		//MetaKeywords
		if ($meta_keywords) {
			joosDocument::instance()
					->add_meta_tag('keywords', $meta_keywords);
		}
	}

	/**
	 * Meta::add()
	 * Новая запись с метаинформацией страницы
	 */
	public static function add_meta($source, $group, $subgroup = '', $obj_id = '') {

		$meta = new self;
		$meta->delete($source['id']);

		if ($source['meta_title'] || $source['meta_description'] || $source['meta_keywords']) {
			$source['group'] = $group;
			$source['subgroup'] = $subgroup;
			$source['obj_id'] = $obj_id;

			return $meta->save($source) ? true : false;
		}

		return true;
	}

	/**
	 * Meta::get_meta()
	 * Получение метаинформации страницы
	 */
	public static function get_meta($group, $subgroup = '', $obj_id = null) {

		$meta = new self;

		$meta->group = $group;
		$meta->subgroup = $subgroup;
		$meta->obj_id = $obj_id;

		return ($meta->find() && (($obj_id && $meta->obj_id) || (!$obj_id && !$meta->obj_id))) ? (array) $meta : array();
	}

	/**
	 * Meta::get_all_meta()
	 * Получение всех метаданных
	 */
	public static function get_all_meta() {
		return joosDatabase::getInstance()->setQuery('SELECT * FROM #__metainfo')->loadObjectList('obj_id');
	}

	/**
	 * Meta::get_all_meta_items()
	 * Получение всех метаданных
	 */
	public static function get_all_meta_items($group, $subgroup) {
		return joosDatabase::getInstance()->setQuery("SELECT id as meta_id, obj_id, meta_title, meta_description, meta_keywords FROM #__metainfo WHERE subgroup='" . $subgroup . "' AND obj_id > 0 AND group = '" . $group . "'")->loadObjectList('obj_id');
	}

}
