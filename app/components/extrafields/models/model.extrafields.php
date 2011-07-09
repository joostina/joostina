<?php

// запрет прямого доступа
defined('_JOOS_CORE') or die();

/**
 * Extrafields - Модель дополнительных полей
 * Модель для работы сайта
 *
 * @version 1.0
 * @package Joostina.Models
 * @subpackage Extrafields
 * @author Joostina Team <info@joostina.ru>
 * @copyright (C) 2007-2011 Joostina Team
 * @license MIT License http://www.opensource.org/licenses/mit-license.php
 * Информация об авторах и лицензиях стороннего кода в составе Joostina CMS: docs/copyrights
 *
 * */
class Extrafields extends joosModel {

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
	 * @var int(11)
	 */
	public $object;
	/**
	 * @var varchar(255)
	 */
	public $name;
	/**
	 * @var varchar(255)
	 */
	public $label;
	/**
	 * @var joosText
	 */
	public $rules;
	/**
	 * @var tinyint(1)
	 */
	public $state;

	/*
	 * Constructor
	 */
	function __construct() {
		parent::__construct('#__extrafields', 'id');
	}

	public static function get_fields_with_values($group, $subgroup = '', $object = null, $item_id = null) {

		$ef = new self;

		$ef->group = $group;
		$ef->subgroup = $subgroup;
		$ef->object = $object;

		//Ищем все нужные поля
		$where = "e.group = '$group'";
		$where .= $ef->subgroup ? " AND e.subgroup = '$subgroup'" : "";
		$where .= $ef->object ? " AND e.object = $object" : "";

		$fields = $ef->get_list(array(
					'select' => 'e.id, e.name, ed.value',
					'where' => $where,
					'join' => 'LEFT JOIN #__extrafields_data AS ed ON (e.id = ed.field_id AND ed.obj_id = ' . $item_id . ')',
					'pseudonim' => 'e',
					'key' => ''
				));

		$return = array();
		foreach ($fields as $f) {
			$return[$f->id] = $f->value;
		}

		return $return;
	}

	public static function get_scheme($fields) {


		if ($fields) {

			$_rules = array();

			foreach ($fields as $f) {
				if ($f->rules) {

					$_rules[$f->id] = array(
						'name' => $f->label
					);

					ob_start();
					eval($f->rules);
					$add_ = ob_get_clean();

					$_rules[$f->id] += $rules;
				}
			}


			return $_rules;
		} else {
			return false;
		}
	}

	public static function __get_scheme($item, $params) {

		$ef = new self;
		$ef->group = $params['group'];
		$ef->subgroup = $subgroup = $params['subgroup'];
		$ef->object = isset($item->$subgroup) ? $item->$subgroup : null;

		$where = "`group` = '$ef->group'";
		$where .= $ef->subgroup ? " AND subgroup = '$ef->subgroup'" : "";
		$where .= $ef->object ? " AND object = $ef->object" : "";

		$fields = $ef->get_list(array(
					'select' => '*',
					'where' => $where
				));

		if ($fields) {

			$_rules = array();

			foreach ($fields as $f) {
				if ($f->rules) {

					$_rules[$f->id] = array(
						'name' => $f->label
					);

					ob_start();
					eval($f->rules);
					$add_ = ob_get_clean();

					$_rules[$f->id] += $rules;
				}
			}


			return $_rules;
		} else {
			return false;
		}
	}

}

/**
 * Class ExtrafieldsData
 * @package    Joostina.Components
 * @subpackage    ExtrafieldsData
 * @author JoostinaTeam
 * @copyright (C) 2007-2011 Joostina Team
 * @license MIT License http://www.opensource.org/licenses/mit-license.php
 * @version
 * @created 2011-03-14 21:51:21
 * Информация об авторах и лицензиях стороннего кода в составе Joostina CMS: docs/copyrights
 */
class ExtrafieldsData extends joosModel {

	/**
	 * @var int(11)
	 */
	public $field_id;
	/**
	 * @var int(11)
	 */
	public $obj_id;
	/**
	 * @var tinytext
	 */
	public $value;

	/*
	 * Constructor
	 */
	function __construct() {
		parent::__construct('#__extrafields_data', 'id');
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

	public function get_fieldinfo() {
		return array(
			'field_id' => array(
				'name' => 'field_id',
				'editable' => true,
				'in_admintable' => true,
				'html_table_element' => 'value',
				'html_table_element_param' => array(),
				'html_edit_element' => 'edit',
				'html_edit_element_param' => array(),
			),
			'obj_id' => array(
				'name' => 'obj_id',
				'editable' => true,
				'in_admintable' => true,
				'html_table_element' => 'value',
				'html_table_element_param' => array(),
				'html_edit_element' => 'edit',
				'html_edit_element_param' => array(),
			),
			'value' => array(
				'name' => 'value',
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
			'header_list' => 'ExtrafieldsData',
			'header_new' => 'Создание ExtrafieldsData',
			'header_edit' => 'Редактирование ExtrafieldsData'
		);
	}

	public function get_extrainfo() {
		return array(
			'search' => array(),
			'filter' => array(),
			'extrafilter' => array()
		);
	}

	public function save_data(array $data, $object = '') {
		$this->obj_id = $object;

		foreach ($data as $id => $val) {
			$this->field_id = $id;
			$this->value = $val;
			$this->delete_list(array('where' => 'field_id = ' . $this->field_id . ' AND obj_id = ' . $this->obj_id));
			$this->store();
		}
	}

}
