<?php

// запрет прямого доступа
defined('_JOOS_CORE') or die();

/**
 * Faq - Модель структуры вопрос-ответ
 * Модель для работы сайта
 *
 * @version 1.0
 * @package Joostina.Models
 * @subpackage Faq
 * @author Joostina Team <info@joostina.ru>
 * @copyright (C) 2008-2011 Joostina Team
 * @license MIT License http://www.opensource.org/licenses/mit-license.php
 * Информация об авторах и лицензиях стороннего кода в составе Joostina CMS: docs/copyrights
 *
 * */
class Faq extends joosModel {

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
		parent::__construct('#__faq', 'id');
	}

}
