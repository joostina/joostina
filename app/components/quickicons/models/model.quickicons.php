<?php

// запрет прямого доступа
defined('_JOOS_CORE') or die();

/**
 * Quickicons - Модель компонента управления кнопками быстрого доступа панели управления
 * Модель панели управления
 *
 * @version 1.0
 * @package Joostina.Models
 * @subpackage Quickicons
 * @author Joostina Team <info@joostina.ru>
 * @copyright (C) 2008-2011 Joostina Team
 * @license MIT License http://www.opensource.org/licenses/mit-license.php
 * Информация об авторах и лицензиях стороннего кода в составе Joostina CMS: docs/copyrights
 *
 * */
class Quickicons extends joosModel {

	/**
	 * @var int(11)
	 */
	public $id;
	/**
	 * @var varchar(64)
	 */
	public $title;
	/**
	 * @var varchar(255)
	 */
	public $alt_text;
	/**
	 * @var varchar(255)
	 */
	public $href;
	/**
	 * @var varchar(100)
	 */
	public $icon;
	/**
	 * @var int(10) unsigned
	 */
	public $ordering;
	/**
	 * @var tinyint(1) unsigned
	 */
	public $state;
	/**
	 * @var int(3)
	 */
	public $gid;

	/*
	 * Constructor
	 * @param object Database object
	 */
	function __construct() {
		parent::__construct('#__quickicons', 'id');
	}

}