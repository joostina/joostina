<?php

/**
 * @package Joostina
 * @copyright Авторские права (C) 2007-2010 Joostina team. Все права защищены.
 * @license Лицензия http://www.gnu.org/licenses/gpl-2.0.htm GNU/GPL, или help/license.php
 * Joostina! - свободное программное обеспечение распространяемое по условиям лицензии GNU/GPL
 * Для получения информации о используемых расширениях и замечаний об авторском праве, смотрите файл help/copyright.php.
 */
// запрет прямого доступа
defined('_JOOS_CORE') or die();

/**
 * Class Blog
 * @package	Blog
 * @subpackage	joosDBModel
 * @created	2010-09-24 02:37:11
 */
class Blog extends joosDBModel {

	/**
	 * @var int(11) unsigned
	 */
	public $id;
	/**
	 * @var varchar(200)
	 */
	public $title;
	/**
	 * @var varchar(100)
	 */
	public $slug;
	/**
	 * @var text
	 */
	public $introtext;
	/**
	 * @var longtext
	 */
	public $fulltext;
	/**
	 * @var text
	 */
	public $params;
	/**
	 * @var timestamp
	 */
	public $created_at;
	/**
	 * @var tinyint(1)
	 */
	public $state;
	/**
	 * @var tinyint(2)
	 */
	public $category_id;
	/**
	 * @var int(11)
	 */
	public $user_id;
	/**
	 * @var array	 
	 */
	public $_error_blog;

	/*
	 * Constructor
	 * @param object Database object
	 */
	function __construct() {
		$this->joosDBModel('#__blog', 'id');
	}

	public function check() {
		$this->title = Jstring::trim($this->title);
		$this->fulltext = Jstring::trim($this->fulltext);

		$validator = BlogValidations::add();
		if (!$validator->ValidateForm()) {
			$this->_error_blog['validator'] = $validator->GetErrors();
			return false;
		}

		$this->filter(array('fulltext'));		
		
		//Для визуального реадктора
		joosLoader::lib('jevix', 'text');
		$jevix = new JJevix;
		$this->fulltext = $jevix->Parser($this->fulltext);
				
		return true;
	}

	public function after_update() {
		return true;
	}

	public function before_insert() {
		$this->user_id = $this->user_id ? $this->user_id : User::instance()->id;
		return true;
	}

	// преед сохранением записи блога
	public function before_store() {
		joosLoader::lib('text');
		$this->slug = trim($this->slug) == '' ? Text::str_to_url($this->title) . '--' . rand(1, 10000) : $this->slug;

		joosLoader::lib('jevix', 'text');
		$jevix = new JJevix;
		$this->fulltext = $jevix->Parser($this->fulltext);

		// если запись принадлежит какой-либо программе то запишем эти данные в параметры
		$category_data = explode(':', $this->category_id);
		if (isset($category_data[1])) {
			// в параметры запишем номер связанной программы
			$this->params += array('program_id' => $category_data[1]);
			// а в поле категории впишем оригинальный номер категории
			$this->category_id = $category_data[0];
		}

		$this->params = json_encode($this->params);
		return true;
	}

	public function before_delete() {
		return true;
	}

	public static function get_blog_cats() {
		$obj = new BlogCategory();
		return $obj->get_selector(array('key' => 'id', 'value' => 'title'), array('select' => 'id, title', 'order' => 'title ASC'));
	}

	public static function get_image($blog_item, $size = false, $image_attr = array()) {
		if (trim($blog_item->params) != '') {
			$params = json_decode($blog_item->params);
			if (isset($params->image_id) && $params->image_id != '') {
				joosLoader::lib('html');
				joosLoader::lib('files');
				$location = Files::makefilename($params->image_id);
				$size = $size ? 'image_' . $size . '.png' : 'image.png';
				$file_location = JPATH_SITE . '/attachments/blogs/' . $location . '/' . $size;
				$image_attr += array('src' => $file_location, 'title' => $blog_item->title, 'alt' => $blog_item->title);
				return HTML::image($image_attr);
			}
		}
		return false;
	}

	public static function get_image_default($image_attr = array()) {
		joosLoader::lib('html');
		$file_location = JPATH_SITE . '/media/images/nomp3s.jpg';
		$image_attr += array('src' => $file_location, 'alt' => '');
		return HTML::image($image_attr);
	}

}

/**
 * Class BlogCategory
 * @package	Blog
 * @subpackage	joosDBModel
 * @created	2010-09-24 02:37:11
 */
class BlogCategory extends joosDBModel {

	/**
	 * @var int(10) unsigned
	 */
	public $id;
	/**
	 * @var varchar(200)
	 */
	public $title;
	/**
	 * @var varchar(100)
	 */
	public $slug;
	/**
	 * @var text
	 */
	public $description;
	/**
	 * @var text
	 */
	public $params;
	/**
	 * @var timestamp
	 */
	public $created_at;
	/**
	 * @var tinyint(1)
	 */
	public $state;

	/*
	 * Constructor
	 * @param object Database object
	 */
	function __construct() {
		$this->joosDBModel('#__blog_category', 'id');
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
				'html_edit_element' => 'edit'
			),
			'title' => array(
				'name' => 'title',
				'editable' => true,
				'in_admintable' => true,
				'html_table_element' => 'value',
				'html_table_element_param' => array(),
				'html_edit_element' => 'edit'
			),
			'slug' => array(
				'name' => 'slug',
				'editable' => true,
				'in_admintable' => true,
				'html_table_element' => 'value',
				'html_table_element_param' => array(),
				'html_edit_element' => 'edit'
			),
			'description' => array(
				'name' => 'description',
				'editable' => true,
				'in_admintable' => true,
				'html_table_element' => 'value',
				'html_table_element_param' => array(),
				'html_edit_element' => 'edit'
			),
			'params' => array(
				'name' => 'params',
				'editable' => true,
				'in_admintable' => true,
				'html_table_element' => 'value',
				'html_table_element_param' => array(),
				'html_edit_element' => 'edit'
			),
			'created_at' => array(
				'name' => 'created_at',
				'editable' => true,
				'in_admintable' => true,
				'html_table_element' => 'value',
				'html_table_element_param' => array(),
				'html_edit_element' => 'edit'
			),
			'state' => array(
				'name' => 'state',
				'editable' => true,
				'in_admintable' => true,
				'html_table_element' => 'value',
				'html_table_element_param' => array(),
				'html_edit_element' => 'edit'
			),
		);
	}

	public function get_tableinfo() {
		return array(
			'header_list' => 'BlogCategory',
			'header_new' => 'Создание BlogCategory',
			'header_edit' => 'Редактирование BlogCategory'
		);
	}

}

class BlogValidations {

	public static function add() {
		joosLoader::lib('formvalidator', 'forms');
		$validator = new FormValidator();
		$validator
				->addValidation("title", "req", "Введите заголовок")
				->addValidation("title", "minlen=5", "Минимум  5 символа")
				->addValidation("title", "maxlen=120", "Максимум 120 символов")
				->addValidation("fulltext", "req", "Введите текст");
		return $validator;
	}

}