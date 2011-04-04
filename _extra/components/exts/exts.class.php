<?php

/**
 * @package Joostina
 * @copyright Авторские права (C) 2007-2010 Joostina team. Все права защищены.
 * @license Лицензия http://www.gnu.org/licenses/gpl-2.0.htm GNU/GPL, или help/license.php
 * Joostina! - свободное программное обеспечение распространяемое по условиям лицензии GNU/GPL
 * Для получения информации о используемых расширениях и замечаний об авторском праве, смотрите файл help/copyright.php.
 */
// запрет прямого доступа
defined('_JOOS_CORE') or die();

/**
 * Class Exts
 * Модель общей информации о рассширении
 * @package	Exts
 * @subpackage	Joostina CMS
 * @created	2011-01-02 20:32:55
 */
class Exts extends joosDBModel {

	/**
	 * @var int(11) unsigned
	 */
	public $id;
	/**
	 * @var varchar(200)
	 */
	public $title;
	/**
	 * @var varchar(255)
	 */
	public $slug;
	/**
	 * @var text
	 */
	public $text_desc;
	/**
	 * @var text
	 */
	public $text_install;
	/**
	 * @var text
	 */
	public $text_hints;
	/**
	 * @var float
	 */
	public $version;
	/**
	 * @var date
	 */
	public $released_at;
	/**
	 * @var date
	 */
	public $last_update_at;
	/**
	 * @var tinyint(1)
	 */
	public $type_id;
	/**
	 * @var int(11) unsigned
	 */
	public $user_id;
	/**
	 * @var datetime
	 */
	public $created_at;
	/**
	 * @var datetime
	 */
	public $modified_at;
	/**
	 * @var tinyint(1)
	 */
	public $state;

	/*
	 * Constructor
	 */
	function __construct() {
		$this->joosDBModel('#__exts', 'id');
	}

	// типы рассширений, их не много и нет смысла выносить в отдульную таблицу
	private static $types = array(
		1 => array(
			'title' => 'Компоненты',
			'slug' => 'components',
		),
		2 => array(
			'title' => 'Модули',
			'slug' => 'modules',
		),
		3 => array(
			'title' => 'Плагины',
			'slug' => 'plugins',
		),
		4 => array(
			'title' => 'Шаблоны',
			'slug' => 'templates',
		),
	);

	public static function get_types(){
		return self::$types;
	}


	public static function get_types_title() {
		$types = array();
		array_walk(self::$types, function($d, $k) use (&$types) {
					$types[$k] = $d['title'];
				});

		return $types;
	}

	public static function get_types_slug() {
		$slugs = array();
		array_walk(self::$types, function($d, $k) use (&$slugs) {
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

	public function check() {
		$this->filter(array('desc'));
		return true;
	}

	public function after_update() {
		
	}

	public function after_store() {

		// сохранение связи расширениЕ-категоИИ
		isset($_POST['extcats']) ? $this->save_cats(joosRequest::array_param('extcats', array())) : null;
	}

	public function before_store() {
		$this->version = str_replace(',', '.', $this->version);
		$this->version = (float) trim($this->version);

		joosLoader::lib('text');
		$this->slug = Text::str_to_url($this->title);

		$this->modified_at = _CURRENT_SERVER_TIME;
		return true;
	}

	public function before_insert() {
		$this->created_at = _CURRENT_SERVER_TIME;
		return true;
	}

	public function before_delete() {
		return true;
	}

	// получение выбранных категорий
	private static function get_selected_cats($current_obj) {

		$params = array(
			'table' => '#__exts_cats_xref',
			'where' => 'ext_id=' . $current_obj->id
		);

		$g = new self;
		return $g->get_selector(
				array('key' => 'cat_id', 'value' => 'cat_id'
				), $params
		);
	}

	// получение списка категорий
	public static function get_cats_selectors(self $current_obj, $params = array()) {
		$childrens = $current_obj->id ? self::get_selected_cats($current_obj) : array();
		return $current_obj->get_one_to_many_selectors('extcats', '#__exts_cats', '#__exts_cats_xref', 'ext_id', 'cat_id', $childrens, $params);
	}

	private function save_cats($values) {
		$this->save_one_to_many('#__exts_cats_xref', 'ext_id', 'cat_id', $this->id, $values);
	}

}

/**
 * Class ExtsAttr
 * @package	ExtsAttr
 * @subpackage	Joostina CMS
 * @created	2011-01-02 23:07:39
 */
class ExtsAttr extends joosDBModel {

	/**
	 * @var int(11) unsigned
	 */
	public $id;
	/**
	 * @var varchar(200)
	 */
	public $title;
	/**
	 * @var varchar(250)
	 */
	public $slug;
	/**
	 * @var text
	 */
	public $params;
	/**
	 * @var varchar(100)
	 */
	public $plugin;
	/**
	 * @var tinyint(1)
	 */
	public $state;

	/*
	 * Constructor
	 */
	function __construct() {
		$this->joosDBModel('#__exts_attr', 'id');
	}

}

/**
 * Class ExtsAttrValues
 * @package	ExtsAttrValues
 * @subpackage	Joostina CMS
 * @created	2011-01-02 23:46:47
 */
class ExtsAttrValues extends joosDBModel {

	/**
	 * @var int(11) unsigned
	 */
	public $id;
	/**
	 * @var int(11) unsigned
	 */
	public $attr_id;
	/**
	 * @var varchar(255)
	 */
	public $title;
	/**
	 * @var tinyint(1)
	 */
	public $state;

	/*
	 * Constructor
	 */
	function __construct() {
		$this->joosDBModel('#__exts_attr_values', 'id');
	}

}

/**
 * Class ExtsCats
 * @package	ExtsCats
 * @subpackage	Joostina CMS
 * @created	2011-01-03 20:40:23
 */
class ExtsCats extends joosDBModel {

	/**
	 * @var int(11) unsigned
	 */
	public $id;
	/**
	 * @var varchar(200)
	 */
	public $title;
	/**
	 * @var varchar(255)
	 */
	public $slug;
	/**
	 * @var tinyint(1) unsigned
	 */
	public $state;

	/*
	 * Constructor
	 */
	function __construct() {
		$this->joosDBModel('#__exts_cats', 'id');
	}

}