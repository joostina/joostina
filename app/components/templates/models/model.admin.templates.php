<?php

// запрет прямого доступа
defined('_JOOS_CORE') or die();

/**
 * adminTemplates - Модель компонента управления шаблонами оформления сайта
 * Модель панели управления
 *
 * @version 1.0
 * @package Joostina.Models
 * @subpackage Templates
 * @author Joostina Team <info@joostina.ru>
 * @copyright (C) 2008-2011 Joostina Team
 * @license MIT License http://www.opensource.org/licenses/mit-license.php
 * Информация об авторах и лицензиях стороннего кода в составе Joostina CMS: docs/copyrights
 *
 * */
class adminTemplates extends joosModel {

	/**
	 * @var int(11)
	 */
	public $id;
	/**
	 * @var varchar(255)
	 */
	public $name;
	/**
	 * @var varchar(255)
	 */
	public $title;

	/*
	 * Constructor
	 */
	function __construct() {
		$this->joosDBModel('#__templates', 'id');
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
				'html_edit_element' => 'edit',
				'html_edit_element_param' => array(),
			),
			'name' => array(
				'name' => 'name',
				'editable' => true,
				'in_admintable' => true,
				'html_table_element' => 'value',
				'html_table_element_param' => array(),
				'html_edit_element' => 'edit',
				'html_edit_element_param' => array(),
			),
			'title' => array(
				'name' => 'title',
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
			'header_list' => 'Шаблоны',
			'header_new' => 'Создание шаблона',
			'header_edit' => 'Редактирование шаблона'
		);
	}

}

/**
 * Class TemplatePositions
 * @package    TemplatePositions
 * @subpackage    Joostina CMS
 * @created    2010-12-20 19:25:30
 */
class TemplatePositions extends joosModel {

	/**
	 * @var int(11)
	 */
	public $id;
	/**
	 * @var varchar(10)
	 */
	public $position;
	/**
	 * @var varchar(255)
	 */
	public $description;

	/*
	 * Constructor
	 */
	function __construct() {
		$this->joosDBModel('#__template_positions', 'id');
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
				'html_edit_element' => 'edit',
				'html_edit_element_param' => array(),
			),
			'position' => array(
				'name' => 'position',
				'editable' => true,
				'in_admintable' => true,
				'html_table_element' => 'value',
				'html_table_element_param' => array(),
				'html_edit_element' => 'edit',
				'html_edit_element_param' => array(),
			),
			'description' => array(
				'name' => 'description',
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
			'header_list' => 'TemplatePositions',
			'header_new' => 'Создание TemplatePositions',
			'header_edit' => 'Редактирование TemplatePositions'
		);
	}

}
