<?php

// запрет прямого доступа
defined('_JOOS_CORE') or die();

/**
 * modelPages - Модель независимымых страниц
 * Модель для работы сайта
 *
 * @version    1.0
 * @package    Models
 * @subpackage Pages
 * @author     Joostina Team <info@joostina.ru>
 * @copyright  (C) 2007-2012 Joostina Team
 * @license    MIT License http://www.opensource.org/licenses/mit-license.php
 * Информация об авторах и лицензиях стороннего кода в составе Joostina CMS: docs/copyrights
 *
 * */
class modelPages extends joosModel {

	/**
	 * @field int(11) unsigned
	 * @type int
	 */
	public $id;

	/**
	 * @field varchar(200)
	 * @type string
	 */
	public $title;

	/**
	 * @field varchar(200)
	 * @type string
	 */
	public $slug;

	/**
	 * @field text
	 * @type string
	 */
	public $text;

	/**
	 * @field text
	 * @type string
	 */
	public $meta_keywords;

	/**
	 * @field text
	 * @type string
	 */
	public $meta_description;

	/**
	 * @field datetime
	 * @type datetime
	 */
	public $created_at;

	/**
	 * @field tinyint(1) unsigned
	 * @type int
	 */
	public $state;

	/*
	 * Constructor
	 */

	function __construct() {
		parent::__construct('#__pages', 'id');
	}

	public function check() {
		$this->filter(array('text'));
		return true;
	}

}
