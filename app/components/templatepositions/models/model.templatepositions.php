<?php

// запрет прямого доступа
defined('_JOOS_CORE') or die();

/**
 * TemplatePositions - Модель позиций шаблона оформления сайта
 * Модель
 *
 * @version 1.0
 * @package Joostina.Models
 * @subpackage Templates
 * @subpackage Modules
 *  * @author Joostina Team <info@joostina.ru>
 * @copyright (C) 2007-2011 Joostina Team
 * @license MIT License http://www.opensource.org/licenses/mit-license.php
 * Информация об авторах и лицензиях стороннего кода в составе Joostina CMS: docs/copyrights
 *
 * */
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
		parent::__construct('#__template_positions', 'id');
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
