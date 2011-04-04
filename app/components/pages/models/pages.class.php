<?php
/**
 * Pages - компонент независимых страниц
 * Модель
 *
 * @version 1.0
 * @package Components
 * @subpackage Pages
 * @author JoostinaTeam
 * @copyright (C) 2008-2010 Joostina Team
 * @license MIT License http://www.opensource.org/licenses/mit-license.php
 *
 */
// запрет прямого доступа
defined('_JOOS_CORE') or die();

//Поддержка кастомных параметров
joosLoader::lib('params', 'system');

//Поддержка метаданных
joosLoader::lib('metainfo', 'seo');

// странички
class Pages extends joosDBModel {

	public $id;
	public $title;
	public $slug;
	public $text;
	public $created_at;
	public $meta_keywords;
	public $meta_description;
	public $state;

	function __construct() {
		$this->joosDBModel('#__pages', 'id');
	}

    
    function check() {
		//$this->filter(array('text'));
		return true;
	}

}