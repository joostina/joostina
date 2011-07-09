<?php

// запрет прямого доступа
defined('_JOOS_CORE') or die();

/**
 * News - Модель новостей
 * Модель для работы сайта
 *
 * @version 1.0
 * @package Joostina.Models
 * @subpackage News
 * @author Joostina Team <info@joostina.ru>
 * @copyright (C) 2007-2011 Joostina Team
 * @license MIT License http://www.opensource.org/licenses/mit-license.php
 * Информация об авторах и лицензиях стороннего кода в составе Joostina CMS: docs/copyrights
 *
 * */
class News extends joosModel {

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
	 * @var joosText
	 */
	public $attachments;


	/*
	 * Constructor
	 * @param object Database object
	 */
	function __construct() {
		parent::__construct('#__news', 'id');
	}

	public static function show_date(stdClass $obj) {
		return joosDateTime::format($obj->created_at, '%d/%m/%Y');
	}

	public static function get_image($item, $type = 'thumb', $image_attr = array()) {

		$file_location = JPATH_SITE_IMAGES . '/' . $item->image . '/' . $type . '.jpg';

		$image_attr += array('src' => $file_location, 'title' => $item->title, 'alt' => $item->title);
		return joosHtml::image($image_attr);
	}

	public static function get_image_default($image_attr = array()) {

		$file_location = JPATH_SITE . '/media/images/noimg.jpg';
		$image_attr += array('src' => $file_location, 'alt' => '');
		return joosHtml::image($image_attr);
	}

}