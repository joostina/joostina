<?php

/**

 *
 * */
// запрет прямого доступа
defined('_JOOS_CORE') or die();

class adminPolls extends Polls {

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

		$questions_values = explode("\n", $this->questions);
		$questions_keys = range(1, count($questions_values));
		$c = array_combine($questions_keys, $questions_values);
		$this->questions = json_encode($c);

		$variants_values = explode("\n", $this->variants);
		$variants_keys = range(1, count($variants_values));
		$c = array_combine($variants_keys, $variants_values);
		$this->variants = json_encode($c);

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
			'description' => array(
				'name' => 'Описание',
				'editable' => true,
				'in_admintable' => true,
				'html_table_element' => 'value',
				'html_table_element_param' => array(),
				'html_edit_element' => 'text_area',
				'html_edit_element_param' => array(),
			),
			'questions' => array(
				'name' => 'Вопросы',
				'editable' => true,
				'in_admintable' => true,
				'html_table_element' => 'value',
				'html_table_element_param' => array(),
				'html_edit_element' => 'text_area',
				'html_edit_element_param' => array(),
			),
			'variants' => array(
				'name' => 'Варианты ответов',
				'editable' => true,
				'in_admintable' => true,
				'html_table_element' => 'value',
				'html_table_element_param' => array(),
				'html_edit_element' => 'text_area',
				'html_edit_element_param' => array(),
			),
			'total_users' => array(
				'name' => 'Всего проголосовало',
				'editable' => true,
				'in_admintable' => true,
				'html_table_element' => 'value',
				'html_table_element_param' => array(),
				'html_edit_element' => 'value',
				'html_edit_element_param' => array(),
			),
		);
	}

	public function get_tableinfo() {
		return array(
			'header_list' => 'Опросы',
			'header_new' => 'Создание опроса',
			'header_edit' => 'Редактирование опроса'
		);
	}

	public function get_extrainfo() {
		return array(
			'search' => array(),
			'filter' => array(),
			'extrafilter' => array()
		);
	}

}