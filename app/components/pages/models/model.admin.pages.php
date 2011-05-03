<?php

// запрет прямого доступа
defined('_JOOS_CORE') or die();

/**
 * adminPages - Модель компонента независимыми страницами
 * Модель панели управления
 *
 * @version 1.0
 * @package Joostina.Models
 * @subpackage Pages
 * @author Joostina Team <info@joostina.ru>
 * @copyright (C) 2008-2011 Joostina Team
 * @license MIT License http://www.opensource.org/licenses/mit-license.php
 * Информация об авторах и лицензиях стороннего кода в составе Joostina CMS: docs/copyrights
 *
 * */
class adminPages extends Pages {

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
			'title' => array(
				'name' => 'Название',
				'editable' => true,
				'sortable' => true,
				'in_admintable' => true,
				'html_edit_element' => 'edit',
				'html_table_element' => 'editlink',
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
					'width' => '80px',
				)
			),
			'slug' => array(
				'name' => 'Ссылка',
				'editable' => true,
				'sortable' => true,
				'in_admintable' => true,
				'html_edit_element' => 'edit',
				'html_table_element' => 'value',
				'html_table_element_param' => array(
					'width' => '100px',
				),
			),
			'text' => array(
				'name' => 'Описание',
				'editable' => true,
				'html_edit_element' => 'wysiwyg',
				'html_edit_element_param' => array(),
			),
			//подключение функционала заполнения мета-информации
			'metainfo' => array(
				'name' => 'params',
				'editable' => true,
				'html_edit_element' => 'json',
				'html_edit_element_param' => array(
					'call_from' => 'joosMetainfo::get_scheme'
				),
			),
		//подключение функционала парметров
		/* 'params' => array(
		  'name' => 'Параметры',
		  'editable' => true,
		  'html_edit_element' => 'params',
		  'html_edit_element_param' => array(
		  'call_from' => 'joosParams::get_scheme'
		  ),
		  ), */
		);
	}

	public function get_tableinfo() {
		return array(
			'header_main' => 'Страницы',
			'header_list' => 'Все страницы',
			'header_new' => 'Создание страницы',
			'header_edit' => 'Редактирование страницы'
		);
	}

}