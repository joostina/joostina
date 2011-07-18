<?php

// запрет прямого доступа
defined('_JOOS_CORE') or die();

/**
 * Blog - Модель компонента блогов
 * Модель для работы сайта
 *
 * @version 1.0
 * @package Joostina.Models
 * @subpackage Blog
 * @author Joostina Team <info@joostina.ru>
 * @copyright (C) 2007-2011 Joostina Team
 * @license MIT License http://www.opensource.org/licenses/mit-license.php
 * Информация об авторах и лицензиях стороннего кода в составе Joostina CMS: docs/copyrights
 *
 * */
class Blog extends joosModel {

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
	 * @var joosText
	 */
	public $introtext;
	/**
	 * @var longtext
	 */
	public $fulltext;
	/**
	 * @var joosText
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

	/**
	 * Constructor
	 * @param object Database object
	 */
	function __construct() {
		parent::__construct('#__blog', 'id');
	}

	public function check() {
		$this->title = joosString::trim($this->title);
		$this->fulltext = joosString::trim($this->fulltext);

		$validator = BlogValidations::add();
		if (!$validator->ValidateForm()) {
			$this->_error_blog['validator'] = $validator->GetErrors();
			return false;
		}

		$this->filter(array('fulltext'));

		//Для визуального реадктора
		$jevix = new JJevix;
		$this->fulltext = $jevix->Parser($this->fulltext);

		return true;
	}

	public function after_update() {
		return true;
	}

	public function before_insert() {
		$this->user_id = $this->user_id ? $this->user_id : Users::instance()->id;
		return true;
	}

	// преед сохранением записи блога
	public function before_store() {

		$this->slug = trim($this->slug) == '' ? joosText::str_to_url($this->title) . '--' . rand(1, 10000) : $this->slug;

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

	// после сохранения записи
	public function after_store() {

		/*
		//Сохраняем тэги
		$tags = new Tags;
		$tags->save_tags($this);
		*/
	}

	public function before_delete() {
		return true;
	}

	public static function get_blog_cats() {
		$obj = new Blog_Category();
		return $obj->get_selector(array('key' => 'id', 'value' => 'title'), array('select' => 'id, title', 'order' => 'title ASC'));
	}

	public static function get_image($blog_item, $size = false, $image_attr = array()) {
		if (trim($blog_item->params) != '') {
			$params = json_decode($blog_item->params);
			if (isset($params->image_id) && $params->image_id != '') {
				$location = joosFile::make_file_location($params->image_id);
				$size = $size ? 'image_' . $size . '.png' : 'image.png';
				$file_location = JPATH_SITE . '/attachments/blogs/' . $location . '/' . $size;
				$image_attr += array('src' => $file_location, 'title' => $blog_item->title, 'alt' => $blog_item->title);
				return joosHtml::image($image_attr);
			}
		}
		return false;
	}

	public static function get_image_default($image_attr = array()) {
		$file_location = JPATH_SITE . '/media/images/noimg.jpg';
		$image_attr += array('src' => $file_location, 'alt' => '');
		return joosHtml::image($image_attr);
	}

}

/**
 * Class Blog_Category
 * @package    Blog
 * @subpackage    joosModel
 * @created    2010-09-24 02:37:11
 */
class Blog_Category extends joosModel {

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
	 * @var joosText
	 */
	public $description;
	/**
	 * @var joosText
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
		parent::__construct('#__blog_category', 'id');
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