<?php

// запрет прямого доступа
defined( '_JOOS_CORE' ) or die();

/**
 * adminExtrafields - Модель компонента управления дополнительными полями
 * Модель панели управления
 *
 * @version    1.0
 * @package    Joostina.Models
 * @subpackage Extrafields
 * @author     Joostina Team <info@joostina.ru>
 * @copyright  (C) 2007-2011 Joostina Team
 * @license    MIT License http://www.opensource.org/licenses/mit-license.php
 * Информация об авторах и лицензиях стороннего кода в составе Joostina CMS: docs/copyrights
 *
 * */
class adminExtrafields extends Extrafields {

	public function check() {
		$this->filter();
		return true;
	}

	public function get_fieldinfo() {
		return array ( 'id'       => array ( 'name'                     => 'id' ,
		                                     'editable'                 => false ,
		                                     'in_admintable'            => true ,
		                                     'html_table_element'       => 'value' ,
		                                     'html_table_element_param' => array () ,
		                                     'html_edit_element'        => 'edit' ,
		                                     'html_edit_element_param'  => array () , ) ,
		               'group'    => array ( 'name'                     => 'Группа (компонент)' ,
		                                     'editable'                 => true ,
		                                     'in_admintable'            => true ,
		                                     'html_table_element'       => 'value' ,
		                                     'html_table_element_param' => array () ,
		                                     'html_edit_element'        => 'edit' ,
		                                     'html_edit_element_param'  => array () , ) ,
		               'subgroup' => array ( 'name'                     => 'Подгруппа' ,
		                                     'editable'                 => true ,
		                                     'in_admintable'            => true ,
		                                     'html_table_element'       => 'value' ,
		                                     'html_table_element_param' => array () ,
		                                     'html_edit_element'        => 'edit' ,
		                                     'html_edit_element_param'  => array () , ) ,
		               'object'   => array ( 'name'                     => 'ID объекта' ,
		                                     'editable'                 => true ,
		                                     'in_admintable'            => true ,
		                                     'html_table_element'       => 'value' ,
		                                     'html_table_element_param' => array () ,
		                                     'html_edit_element'        => 'edit' ,
		                                     'html_edit_element_param'  => array () , ) ,
		               'name'     => array ( 'name'                     => 'Имя поля' ,
		                                     'editable'                 => true ,
		                                     'in_admintable'            => true ,
		                                     'html_table_element'       => 'value' ,
		                                     'html_table_element_param' => array () ,
		                                     'html_edit_element'        => 'edit' ,
		                                     'html_edit_element_param'  => array () , ) ,
		               'label'    => array ( 'name'                     => 'Подпись поля' ,
		                                     'editable'                 => true ,
		                                     'in_admintable'            => true ,
		                                     'html_table_element'       => 'editlink' ,
		                                     'html_table_element_param' => array () ,
		                                     'html_edit_element'        => 'edit' ,
		                                     'html_edit_element_param'  => array () , ) ,
		               'rules'    => array ( 'name'                     => 'Правила (joosAutoadmin-описание поля)' ,
		                                     'editable'                 => true ,
		                                     'in_admintable'            => true ,
		                                     'html_table_element'       => 'value' ,
		                                     'html_table_element_param' => array () ,
		                                     'html_edit_element'        => 'textarea' ,
		                                     'html_edit_element_param'  => array () , ) ,
		               'state'    => array ( 'name'                     => 'Опубликовано' ,
		                                     'editable'                 => true ,
		                                     'sortable'                 => true ,
		                                     'in_admintable'            => true ,
		                                     'editlink'                 => true ,
		                                     'html_edit_element'        => 'checkbox' ,
		                                     'html_table_element'       => 'state_box' ,
		                                     'html_edit_element_param'  => array ( 'text' => 'Опубликовано' , ) ,
		                                     'html_table_element'       => 'statuschanger' ,
		                                     'html_table_element_param' => array ( 'align' => 'center' ,
		                                                                           'class' => 'td-state-joiadmin' ,
		                                                                           'width' => '20px' , ) ) , );
	}

	public function get_tableinfo() {
		return array ( 'header_list' => 'Дополнительные поля' ,
		               'header_new'  => 'Создание дополнительного поля' ,
		               'header_edit' => 'Редактирование дополнительного поля' );
	}

	public function get_extrainfo() {
		return array ( 'search' => array () ,
		               'filter' => array () );
	}

}