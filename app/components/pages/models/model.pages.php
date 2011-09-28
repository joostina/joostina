<?php

// запрет прямого доступа
defined( '_JOOS_CORE' ) or die();

/**
 * Pages - Модель независимымых страниц
 * Модель для работы сайта
 *
 * @version    1.0
 * @package    Joostina.Models
 * @subpackage Pages
 * @author     Joostina Team <info@joostina.ru>
 * @copyright  (C) 2007-2011 Joostina Team
 * @license    MIT License http://www.opensource.org/licenses/mit-license.php
 * Информация об авторах и лицензиях стороннего кода в составе Joostina CMS: docs/copyrights
 *
 * */
class Pages extends joosModel {

	public $id;
	public $title;
	public $title_page;
	public $slug;
	public $text;
	public $created_at;
	public $meta_keywords;
	public $meta_description;
	public $state;

	function __construct() {
		parent::__construct( '#__pages' , 'id' );
	}

	function check() {
		$this->filter( array ( 'text' ) );
		return true;
	}

}