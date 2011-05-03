<?php

// запрет прямого доступа
defined('_JOOS_CORE') or die();

/**
 * adminUsers - Модель компонента управления пользователями
 * Модель панели управления
 *
 * @version 1.0
 * @package Joostina.Models
 * @subpackage Users
 * @author Joostina Team <info@joostina.ru>
 * @copyright (C) 2008-2011 Joostina Team
 * @license MIT License http://www.opensource.org/licenses/mit-license.php
 * Информация об авторах и лицензиях стороннего кода в составе Joostina CMS: docs/copyrights
 *
 * */
class adminUsers extends Users {

	public function get_fieldinfo() {
		return array(
			'id' => array(
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
			'username' => array(
				'name' => 'Логин',
				'editable' => true,
				'sortable' => true,
				'in_admintable' => true,
				'html_edit_element' => 'edit',
				'html_table_element' => 'editlink',
			),
			'state' => array(
				'name' => 'Разрешен',
				'editable' => true,
				'sortable' => true,
				'in_admintable' => true,
				'editlink' => true,
				'html_edit_element' => 'checkbox',
				'html_table_element' => 'state_box',
				'html_edit_element_param' => array(
					'text' => 'Разрешён / Активирован',
				),
				'html_table_element' => 'statuschanger',
				'html_table_element_param' => array(
					'statuses' => array(
						0 => 'Разрешён',
						1 => 'Заблокирован',
					),
					'align' => 'center',
					'class' => 'td-state-joiadmin',
					'width' => '20px',
				)
			),
			'email' => array(
				'name' => 'email адрес',
				'editable' => true,
				'in_admintable' => true,
				'html_edit_element' => 'edit',
				'html_table_element' => 'value',
			),
			'openid' => array(
				'name' => 'Адрес OpenID',
				'editable' => true,
				'in_admintable' => true,
				'html_edit_element' => 'edit',
				'html_table_element' => 'value',
			),
			'password' => array(
				'name' => 'Пароль',
				'editable' => true,
				'in_admintable' => true,
				'html_edit_element' => 'edit',
				'html_table_element' => 'value',
			),
			'gid' => array(
				'name' => 'Группа',
				'editable' => true,
				'sortable' => true,
				'in_admintable' => true,
				'html_edit_element' => 'option',
				'html_edit_element_param' => array(
					'call_from' => 'Users::get_usergroup_title'
				),
				'html_table_element' => 'one_from_array',
				'html_table_element_param' => array(
					'call_from' => 'Users::get_usergroup_title'
				),
			),
			'registerDate' => array(
				'name' => 'Дата регистрации',
				'editable' => true,
				'in_admintable' => true,
				'html_edit_element' => 'edit',
				'html_table_element' => 'value',
			),
			'lastvisitDate' => array(
				'name' => 'Последнее посещение',
				'editable' => true,
				'sortable' => true,
				'in_admintable' => true,
				'html_edit_element' => 'edit',
				'html_table_element' => 'value',
				'html_table_element_param' => array(
					'width' => '200px',
					'align' => 'center'
				)
			),
			'activation' => array(
				'name' => 'Код активации',
				'editable' => false,
				'in_admintable' => false,
				'html_edit_element' => 'edit',
				'html_table_element' => 'value',
			),
		);
	}

	public function get_tableinfo() {
		return array(
			'header_list' => 'Пользователи',
			'header_new' => 'Создание пользователя',
			'header_edit' => 'Редактирование данных пользователя'
		);
	}

	public function get_extrainfo() {
		return array(
			'search' => array(
				'username', 'realname', 'email'
			),
			'filter' => array(
				'gid' => array(
					'name' => 'Группа',
					'call_from' => 'Users::get_usergroup_title'
				)
			)
		);
	}

}

/**
 * Class UsersGroups
 * @package    UsersGroups
 * @subpackage    Joostina CMS
 * @created    2010-10-20 16:48:08
 */
class adminUsersGroups extends UsersGroups {

	public function check() {
		$this->filter();
		return true;
	}

	public function get_fieldinfo() {
		return array(
			'id' => array(
				'name' => 'id',
				'editable' => false,
				'in_admintable' => true,
				'html_table_element' => 'value',
				'html_table_element_param' => array(
					'width' => '20px',
					'align' => 'center'
				),
				'html_edit_element' => 'edit',
				'html_edit_element_param' => array(),
			),
			'parent_id' => array(
				'name' => 'Родительская группа',
				'editable' => true,
				'in_admintable' => false,
				'html_table_element' => 'one_from_array',
				'html_table_element_param' => array(
					'call_from' => 'UsersGroups::get_parent_usergroup'
				),
				'html_edit_element' => 'extra',
				'html_edit_element_param' => array(
					'call_from' => 'UsersGroups::get_parent_usergroup_selector'
				),
			),
			'title' => array(
				'name' => 'Заголовок группы',
				'editable' => true,
				'in_admintable' => true,
				'html_table_element' => 'value',
				'html_table_element_param' => array(),
				'html_edit_element' => 'edit',
				'html_edit_element_param' => array(),
			),
			'group_title' => array(
				'name' => 'Название группы',
				'editable' => true,
				'in_admintable' => true,
				'html_table_element' => 'editlink',
				'html_table_element_param' => array(),
				'html_edit_element' => 'edit',
				'html_edit_element_param' => array(),
			),
		);
	}

	public function get_tableinfo() {
		return array(
			'header_list' => 'Группы пользователей',
			'header_new' => 'Создание новой группы пользователей',
			'header_edit' => 'Редактирование группы пользователей'
		);
	}

	public static function get_parent_usergroup_selector($obj) {
		$groups = new UsersGroups();
		$group_selector = $groups->get_selector(array('key' => 'id', 'value' => 'group_title'), array('select' => 'id, group_title'));
		unset($group_selector[$obj->id]);
		return forms::dropdown(array('name' => 'parent_id', 'options' => $group_selector, 'selected' => $obj->parent_id));
	}

	public static function get_parent_usergroup() {
		$groups = new UsersGroups();
		return $groups->get_selector(array('key' => 'id', 'value' => 'group_title'), array('select' => 'id, group_title'));
	}

}
