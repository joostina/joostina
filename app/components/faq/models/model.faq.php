<?php

/**
 * Class Faq
 * @package    Joostina.Components
 * @subpackage    Faq
 * @author JoostinaTeam
 * @copyright (C) 2008-2011 Joostina Team
 * @license MIT License http://www.opensource.org/licenses/mit-license.php
 * @version
 * @created 2011-03-27 19:56:48
 * Информация об авторах и лицензиях стороннего кода в составе Joostina CMS: docs/copyrights
 */
// запрет прямого доступа
defined('_JOOS_CORE') or die();

class Faq extends joosDBModel {

	/**
	 * @var int(11)
	 */
	public $id;
	/**
	 * @var datetime
	 */
	public $created_at;
	/**
	 * @var joosText
	 */
	public $question;
	/**
	 * @var joosText
	 */
	public $answer;
	/**
	 * @var varchar(255)
	 */
	public $username;
	/**
	 * @var varchar(255)
	 */
	public $useremail;
	/**
	 * @var tinyint(1)
	 */
	public $state;


	/*
	 * Constructor
	 */
	function __construct() {
		$this->joosDBModel('#__faq', 'id');
	}

}
