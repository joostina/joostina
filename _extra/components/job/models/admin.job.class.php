<?php
/**
 * Job - Компонент вакансий
 * Модель админ-панели
 *
 * @version 1.0
 * @package Joostina.Components
 * @subpackage Job
 * @author Joostina Team <info@joostina.ru>
 * @copyright (C) 2008-2011 Joostina Team
 * @license MIT License http://www.opensource.org/licenses/mit-license.php
 * Информация об авторах и лицензиях стороннего кода в составе Joostina CMS: docs/copyrights
 *
 **/
 
// запрет прямого доступа
defined('_JOOS_CORE') or die();

//Модель компонента
joosLoader::model('job');


/**
 * Class Job
 * @package	Joostina.Components
 * @subpackage	Job
 * @author JoostinaTeam
 * @copyright (C) 2008-2011 Joostina Team
 * @license MIT License http://www.opensource.org/licenses/mit-license.php
 * @version
 * @created 2011-03-26 20:15:39
 * Информация об авторах и лицензиях стороннего кода в составе Joostina CMS: docs/copyrights
 */
class adminJob extends Job {

	/*
	 * Constructor
	 */
	function __construct(){
		$this->joosDBModel( '#__job', 'id' );
	}

	public function check() {
		//$this->filter();
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
			'id' => array(
				'name' => 'id',
				'editable' => true,
				'in_admintable' => true,
				'html_table_element' => 'value',
				'html_table_element_param' => array(),
				'html_edit_element' => 'value',
				'html_edit_element_param' => array(),
			),
			'title' => array(
				'name' => 'Заголовок',
				'editable' => true,
				'in_admintable' => true,
				'html_table_element' => 'editlink',
				'html_table_element_param' => array(),
				'html_edit_element' => 'edit',
				'html_edit_element_param' => array(),
			),
			'fulltext' => array(
				'name' => 'Полный текст (с HTML)',
				'editable' => true,
				'in_admintable' => true,
				'html_table_element' => 'value',
				'html_table_element_param' => array(),
				'html_edit_element' => 'text_area_wysiwyg',
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
					'statuses' => array(
						0 => 'Скрыто',
						1 => 'Опубликовано'
					),
					'images' => array(
						0 => 'publish_x.png',
						1 => 'publish_g.png',
					),
					'align' => 'center',
					'class' => 'td-state-joiadmin',
					'width' => '20px',
				)
			),
		);
	}


	public function get_tableinfo() {
			return array(
				'header_main' => 'Вакансии',
				'header_list' => 'Вакансии',
				'header_new' => 'Создание вакансии',
				'header_edit' => 'Редактирование вакансии'
			);
	}


	public function get_extrainfo() {
			return array(
				'search' => array(),
				'filter' => array()
			);
	}

}

class adminJobResponses extends JobResponses {

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
			'id' => array(
				'name' => 'id',
				'editable' => true,
				'in_admintable' => true,
				'html_table_element' => 'value',
				'html_table_element_param' => array(),
				'html_edit_element' => 'edit',
				'html_edit_element_param' => array(),
			),
			'job_id' => array(
				'name' => 'Вакансия',
				'editable' => true,
				'in_admintable' => true,
				'html_table_element' => 'one_from_array',
				'html_table_element_param' => array('call_from' => 'adminJobResponses::get_job_title'),
				'html_edit_element' => 'edit',
				'html_edit_element_param' => array(),
			),
			'username' => array(
				'name' => 'Кандидат',
				'editable' => true,
				'in_admintable' => true,
				'html_table_element' => 'extra',
				'html_table_element_param' => array('call_from' => 'adminJobResponses::get_u_info'),
				'html_edit_element' => 'edit',
				'html_edit_element_param' => array(),
			),
			'useremail' => array(
				'name' => 'useremail',
				'editable' => true,
				'in_admintable' => true,
				'html_table_element' => 'value',
				'html_table_element_param' => array(),
				'html_edit_element' => 'edit',
				'html_edit_element_param' => array(),
			),
			'message' => array(
				'name' => 'Сообщение',
				'editable' => true,
				'in_admintable' => true,
				'html_table_element' => 'value',
				'html_table_element_param' => array(),
				'html_edit_element' => 'edit',
				'html_edit_element_param' => array(),
			),
			'resume' => array(
				'name' => 'Резюме',
				'editable' => true,
				'in_admintable' => true,
				'html_table_element' => 'extra',
				'html_table_element_param' => array('call_from' => 'adminJobResponses::get_resume'),
				'html_edit_element' => 'edit',
				'html_edit_element_param' => array(),
			),
		);
	}


	public function get_tableinfo() {
			return array(
				'header_list' => 'Ответы на вакансии',
				'header_new' => 'Создание JobResponses',
				'header_edit' => 'Редактирование JobResponses'
			);
	}


	public function get_extrainfo() {
			return array(
				'search' => array(),
				'filter' => array(),
				'extrafilter' => array()
			);
	}

	public static function get_u_info($item){
		return $item->username . '<br/>' . $item->useremail;
	}

	public static function get_job_title(){
		$job = new Job;
		return $job->get_selector(array('key'=>'id', 'value'=>'title'), array('where'=>'state=1'));
	}

	public static function get_resume($item){
		return '<a target="_blank" href="'.$item->resume.'">Скачать</a>';
	}

}
