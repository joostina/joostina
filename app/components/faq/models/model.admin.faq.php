<?php

// запрет прямого доступа
defined('_JOOS_CORE') or die();

/**
 * adminFaq - Модель компонента управления структурой вопрос-ответ
 * Модель панели управления
 *
 * @version 1.0
 * @package Joostina.Models
 * @subpackage Faq
 * @author Joostina Team <info@joostina.ru>
 * @copyright (C) 2008-2011 Joostina Team
 * @license MIT License http://www.opensource.org/licenses/mit-license.php
 * Информация об авторах и лицензиях стороннего кода в составе Joostina CMS: docs/copyrights
 *
 * */
class adminFaq extends Faq {

	public function get_fieldinfo() {
		return array(
			'id' => array(
				'name' => 'id',
				'editable' => true,
				'in_admintable' => true,
				'html_table_element' => 'value',
				'html_table_element_param' => array(),
				'html_edit_element' => 'value',
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
			'question' => array(
				'name' => 'Вопрос',
				'editable' => true,
				'in_admintable' => true,
				'html_table_element' => 'editlink',
				'html_table_element_param' => array(),
				'html_edit_element' => 'textarea',
				'html_edit_element_param' => array(),
			),
			'answer' => array(
				'name' => 'Ответ',
				'editable' => true,
				'in_admintable' => true,
				'html_table_element' => 'value',
				'html_table_element_param' => array(),
				'html_edit_element' => 'wysiwyg',
				'html_edit_element_param' => array(),
			),
			'username' => array(
				'name' => 'Имя',
				'editable' => true,
				'in_admintable' => true,
				'html_table_element' => 'extra',
				'html_table_element_param' => array('call_from' => 'adminFaq::get_u_info'),
				'html_edit_element' => 'edit',
				'html_edit_element_param' => array(),
			),
			'useremail' => array(
				'name' => 'Email',
				'editable' => true,
				'in_admintable' => true,
				'html_table_element' => 'value',
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
					'align' => 'center',
					'class' => 'td-state-joiadmin',
					'width' => '20px',
				)
			),
		);
	}

	public function get_tableinfo() {
		return array(
			'header_main' => 'Вопрос-ответ',
			'header_list' => 'Все вопросы',
			'header_new' => 'Создание вопроса',
			'header_edit' => 'Редактирование вопроса'
		);
	}

	public function check() {
		$this->filter();
		return true;
	}

	public function before_insert() {
		return true;
	}

	public function after_insert() {
		$this->created_at = _CURRENT_SERVER_TIME;
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

	public function get_extrainfo() {
		return array(
			'search' => array(),
			'filter' => array()
		);
	}

	public static function get_u_info($item) {
		return $item->username . '<br/>' . $item->useremail;
	}

}

