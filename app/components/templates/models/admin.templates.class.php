<?php
/**
 * Templates - управление шаблонами
 * Модель
 *
 * @version 1.0
 * @package ComponentsAdmin
 * @subpackage Templates
 * @author JoostinaTeam
 * @copyright (C) 2008-2010 Joostina Team
 * @license see license.txt
 *
 * */
// запрет прямого доступа
defined('_JOOS_CORE') or die();

/**
 * Class Templates
 * @package	Templates
 * @subpackage	Joostina CMS
 * @created	2010-12-20 19:18:41
 */
class Templates extends joosDBModel {
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
	function __construct(){
		$this->joosDBModel( '#__templates', 'id' );
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
 * @package	TemplatePositions
 * @subpackage	Joostina CMS
 * @created	2010-12-20 19:25:30
 */
class TemplatePositions extends joosDBModel {
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
	function __construct(){
		$this->joosDBModel( '#__template_positions', 'id' );
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
