<?php

/**
 * News - компонент новостей
 * Модель
 *
 * @version 1.0
 * @package Components
 * @subpackage News
 * @author JoostinaTeam
 * @copyright (C) 2008-2010 Joostina Team
 * @license see license.txt
 *
 * */
// запрет прямого доступа
defined('_JOOS_CORE') or die();

//Поддержка метаданных
joosLoader::lib('metainfo', 'seo');

//Поддержка кастомных параметров
joosLoader::lib('params', 'system');

//Поддержка дополнительных полей
joosLoader::model('extrafields');

//Категории
joosLoader::admin_model('categories');

/**
 * Class News
 * @package	News
 * @subpackage	Joostina CMS
 * @created	2010-10-03 00:41:28
 */
class Content extends joosDBModel {

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
	 * @var text
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
	 * @var text
	 */
	public $attachments;	
	


	/*
	 * Constructor
	 * @param object Database object
	 */
	function __construct() {
		$this->joosDBModel('#__content', 'id');
	}

	public static function get_types() {
		$types = array();
		array_walk(self::$news_array, function($d, $k) use (&$types) {
					$types[$k] = $d['title'];
				});

		return $types;
	}

	public static function get_types_slug() {
		$slugs = array();
		array_walk(self::$news_array, function($d, $k) use (&$slugs) {
					$slugs[$k] = $d['slug'];
				});

		return $slugs;
	}

	public static function get_types_slug_array() {
		return array_flip(self::get_types_slug());
	}

	public static function get_types_slug_by_type_id($type_id) {
		$types = self::get_types_slug();
		return $types[$type_id];
	}

	public static function show_date(stdClass $obj) {
		return joosDate::format($obj->created_at, '%d/%m/%Y');
	}


	public static function get_image($item, $type = 'thumb', $image_attr = array()) {

		$file_location = JPATH_SITE_IMAGES.'/' . $item->image_path . '/' . $type.'.jpg';
	
		$image_attr += array('src' => $file_location, 'title' => $item->title, 'alt' => $item->title);
		return HTML::image($image_attr);
	}



	public static function get_image_default($image_attr = array()) {

		$file_location = JPATH_SITE . '/media/images/noimg.jpg';
		$image_attr += array('src' => $file_location, 'alt' => '');
		return HTML::image($image_attr);
	}

	public static function get_extrafields($item){

		$return = array();
		//$parent_cat = joosRequest::request('parentcat');

		//if(!$item->id && !$parent_cat){
			//return false;
		//}

		//$category_id = $item->id ? $item->category_id : $parent_cat;
		$category_id = 3;

		$fields = Categories::get_extrafields_by_category($category_id, 'content');

		if(!$fields){
			return false;
		}

		//Формируем правила
		$return['rules'] = Extrafields::get_scheme($fields);

		if($item->id){
			//Достаём данные
			$f_ids = array_keys($fields);
			$f_data = new ExtrafieldsData();
			$f_data = $f_data->get_selector(
				array('key'=>'field_id', 'value' => 'value'),
				array(
					'where' => 'field_id IN (' .implode(',' , $f_ids) .') AND obj_id = '.$item->id
				)
			);

			if($f_data){
				foreach($fields as $f){
					$return['values'][$f->id] = $f_data[$f->id];
				}
			}

		}

		return $return;
	}

}
