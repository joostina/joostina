<?php

// запрет прямого доступа
defined('_JOOS_CORE') or die();

/**
 * adminComments - Модель компонента управления комментариями
 * Модель панели управления
 *
 * @version 1.0
 * @package Joostina.Models
 * @subpackage Comments
 * @author Joostina Team <info@joostina.ru>
 * @copyright (C) 2008-2011 Joostina Team
 * @license MIT License http://www.opensource.org/licenses/mit-license.php
 * Информация об авторах и лицензиях стороннего кода в составе Joostina CMS: docs/copyrights
 *
 * */
class adminComments extends Comments {

	public function get_fieldinfo() {
		return array(
			'sid' => array(
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
			'obj_id' => array(
				'name' => 'ID объекта',
				'editable' => false,
				'sortable' => false,
				'in_admintable' => true,
				'html_table_element' => 'value',
				'html_table_element_param' => array(
					'width' => '20px',
					'align' => 'center'
				)
			),
			'obj_option' => array(
				'name' => 'Тип объекта',
				'editable' => false,
				'sortable' => false,
				'in_admintable' => true,
				'html_table_element' => 'value',
				'html_table_element_param' => array(
					'width' => '50px',
					'align' => 'center'
				)
			),
			'user_id' => array(
				'name' => 'Логин / ID пользователя',
				'editable' => true,
				'sortable' => false,
				'in_admintable' => true,
				'html_table_element' => 'one_from_array',
				'html_table_element_param' => array(
					'align' => 'center',
					'call_from' => 'adminComments::get_users_array'
				),
				'html_edit_element' => 'option',
				'html_edit_element_param' => array(
					'align' => 'center',
					'call_from' => 'adminComments::get_users_array'
				),
			),
			'user_name' => array(
				'name' => 'Имя пользователя (для неавторизованных)',
				'editable' => false,
				'sortable' => false,
				'in_admintable' => true,
				'html_table_element' => 'value',
				'html_table_element_param' => array(
					'align' => 'center'
				)
			),
			'user_email' => array(
				'name' => 'Email пользователя (для неавторизованных)',
				'editable' => false,
				'sortable' => false,
				'in_admintable' => true,
				'html_table_element' => 'value',
				'html_table_element_param' => array(
					'width' => '20px',
					'align' => 'center'
				)
			),
			'user_ip' => array(
				'name' => 'IP адрес пользователя',
				'editable' => false,
				'sortable' => false,
				'in_admintable' => true,
				'html_table_element' => 'value',
				'html_table_element_param' => array(
					'width' => '20px',
					'align' => 'center'
				)
			),
			'comment_text' => array(
				'name' => 'Текст комментария',
				'editable' => true,
				'sortable' => true,
				'in_admintable' => true,
				'editlink' => true,
				'html_edit_element' => 'textarea',
				'html_edit_element_param' => array(
					'height' => 100,
				),
				'html_table_element' => 'editlink',
				'html_table_element_param' => array(
					'text_limit' => 50,
				)
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
					'align' => 'center',
					'class' => 'td-state-joiadmin',
				)
			),
		);
	}

	/**
	 * Информация для страниц вывода данных о комментариях
	 * @return array массив информации дял построителя интерфейса
	 */
	public function get_tableinfo() {
		return array(
			'header_list' => 'Комментарии',
			'header_new' => 'Создание комментария',
			'header_edit' => 'Редактирование комментария'
		);
	}

	public static function get_users_array() {
		$obj = new Users();
		return $obj->get_selector(array('key' => 'id', 'value' => 'username'), array('select' => 'id, username'));
	}

}