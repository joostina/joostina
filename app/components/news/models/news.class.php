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

//Поддержка параметров
joosLoader::lib('params', 'system');

/**
 * Class News
 * @package	News
 * @subpackage	Joostina CMS
 * @created	2010-10-03 00:41:28
 */
class News extends joosDBModel {

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
	 * @var varchar(250)
	 */
	public $image;
	/**
	 * @var tinyint(1)
	 */
	public $type_id;
	/**
	 * @var timestamp
	 */
	public $created_at;
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
	
	private static $news_array = array(
		1 => array(
			'title' => 'Новости сайта',
			'slug' => 'sitenews',
		),
		2 => array(
			'title' => 'Новости разработки',
			'slug' => 'devnews',
		),
	);

	/*
	 * Constructor
	 * @param object Database object
	 */
	function __construct() {
		$this->joosDBModel('#__news', 'id');
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

		$file_location = JPATH_SITE_IMAGES.'/' . $item->image . '/' . $type.'.jpg';
	
		$image_attr += array('src' => $file_location, 'title' => $item->title, 'alt' => $item->title);
		return HTML::image($image_attr);
	}

	public static function get_image_default($image_attr = array()) {

		$file_location = JPATH_SITE . '/media/images/noimg.jpg';
		$image_attr += array('src' => $file_location, 'alt' => '');
		return HTML::image($image_attr);
	}

}