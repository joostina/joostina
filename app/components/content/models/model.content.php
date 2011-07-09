<?php

// запрет прямого доступа
defined('_JOOS_CORE') or die();

/**
 * Content - Модель контента
 * Модель для работы сайта
 *
 * @version 1.0
 * @package Joostina.Models
 * @subpackage Content
 * @author Joostina Team <info@joostina.ru>
 * @copyright (C) 2007-2011 Joostina Team
 * @license MIT License http://www.opensource.org/licenses/mit-license.php
 * Информация об авторах и лицензиях стороннего кода в составе Joostina CMS: docs/copyrights
 *
 * */
class Content extends joosModel {

	/**
	 * @var int(11)
	 */
	public $id;
	/**
	 * @var varchar(200)
	 */
	public $title;
	/**
	 * @var varchar(200)
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
	 * @var varchar(255)
	 */
	public $image;
	/**
	 * @var int(11)
	 */
	public $category_id;
	/**
	 * @var timestamp
	 */
	public $created_at;
	/**
	 * @var int(11)
	 */
	public $ordering;
	/**
	 * @var tinyint(1)
	 */
	public $state;
	/**
	 * @var tinyint(1)
	 */
	public $special;
	/**
	 * @var joosText
	 */
	public $attachments;
	/**
	 * @var int(11)
	 */
	public $user_id;



	/*
	 * Constructor
	 * @param object Database object
	 */
	function __construct() {
		parent::__construct('#__content', 'id');
	}

	public static function show_date(stdClass $obj) {
		return joosDateTime::format($obj->created_at, '%d/%m/%Y');
	}

	public static function get_image($item, $type = 'thumb', $image_attr = array()) {

		$file_location = JPATH_SITE_IMAGES . '/' . $item->image_path . '/' . $type . '.jpg';

		$image_attr += array('src' => $file_location, 'title' => $item->title, 'alt' => $item->title);
		return joosHtml::image($image_attr);
	}

	public static function get_image_default($image_attr = array()) {

		$file_location = JPATH_SITE . '/media/images/noimg.jpg';
		$image_attr += array('src' => $file_location, 'alt' => '');
		return joosHtml::image($image_attr);
	}

	public static function get_extrafields($item) {

		$return = array();
		//$parent_cat = joosRequest::request('parentcat');
		//if(!$item->id && !$parent_cat){
		//return false;
		//}
		//$category_id = $item->id ? $item->category_id : $parent_cat;
		$category_id = 3;

		$fields = Categories::get_extrafields_by_category($category_id, 'content');

		if (!$fields) {
			return false;
		}

		//Формируем правила
		$return['rules'] = Extrafields::get_scheme($fields);

		if ($item->id) {
			//Достаём данные
			$f_ids = array_keys($fields);
			$f_data = new ExtrafieldsData();
			$f_data = $f_data->get_selector(array('key' => 'field_id', 'value' => 'value'), array(
						'where' => 'field_id IN (' . implode(',', $f_ids) . ') AND obj_id = ' . $item->id
							)
			);

			if ($f_data) {
				foreach ($fields as $f) {
					$return['values'][$f->id] = $f_data[$f->id];
				}
			}
		}

		return $return;
	}

}
