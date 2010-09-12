<?php

defined('_VALID_MOS') or die();

/**
 * Class People
 * @package	People
 * @subpackage	Joostina CMS
 * @created	2010-09-02 19:30
 */
class People extends mosDBTable {

	/**
	 * @var int(11)
	 */
	public $id;
	/**
	 * @var varchar(255)
	 */
	public $name;
	/**
	 * @var varchar(255)
	 */
	public $altnames;
	/**
	 * @var int(11)
	 */
	public $gender;
	/**
	 * @var text
	 */
	public $biography;
	/**
	 * @var datetime
	 */
	public $created_at;
	/**
	 * @var datetime
	 */
	public $updated_at;
	/**
	 * @var tinyint(1)
	 */
	public $main;
	/**
	 * @var int(11)
	 */
	public $edited;
	/**
	 * @var varchar(255)
	 */
	public $url_name;
	/**
	 * @var int(11)
	 */
	public $rating;
	/**
	 * @var varchar(255)
	 */
	public $name2;
	/**
	 * @var int(11)
	 */
	public $last_rating;
	/**
	 * @var int(11)
	 */
	public $today_rating;

	/*
	 * Constructor
	 * @param object Database object
	 */
	function __construct() {
		$this->mosDBTable('people', 'id');
	}

	public function check() {
		return true;
	}

	public function after_update() {
		return true;
	}

	public function after_store() {
		return true;
	}

	public function before_store() {
		return true;
	}

	public function before_delete() {
		return true;
	}

	public function get_fieldinfo() {
		return array(
			'id' => array(
				'name' => 'id',
				'editable' => false,
				'sortable' => false,
				'in_admintable' => false,
				'html_table_element' => 'value',
				'html_table_element_param' => array(
					'width' => '20px',
					'align' => 'center'
				),
				'html_edit_element' => 'edit'
			),
			'mainpicture' => array(
				'name' => 'Главная картинка',
				'editable' => true,
				'html_edit_element' => 'extra',
				'html_edit_element_param' => array(
					'call_from' => 'People::get_main_picture',
				)
			),
			'name' => array(
				'name' => 'Исполнитель',
				'editable' => true,
				'sortable' => false,
				'in_admintable' => true,
				'html_table_element' => 'editlink',
				'html_table_element_param' => array(),
				'html_edit_element' => 'edit'
			),
			'biocount' => array(
				'name' => 'Биографий',
				'in_admintable' => true,
				'html_table_element' => 'extra',
				'html_table_element_param' => array(
					'call_from' => 'People::get_bio_count',
				),
				'html_edit_element' => 'edit'
			),
			'albumscount' => array(
				'name' => 'Альбомов',
				'in_admintable' => true,
				'html_table_element' => 'extra',
				'html_table_element_param' => array(
					'call_from' => 'People::get_album_count',
				),
				'html_edit_element' => 'edit'
			),
			'compositionscount' => array(
				'name' => 'Композиций',
				'in_admintable' => true,
				'html_table_element' => 'extra',
				'html_table_element_param' => array(
					'call_from' => 'People::get_sound_count',
				),
				'html_edit_element' => 'edit'
			),
			'picturescount' => array(
				'name' => 'Фотографий',
				'in_admintable' => true,
				'html_table_element' => 'extra',
				'html_table_element_param' => array(
					'call_from' => 'People::get_pictures_count',
				),
				'html_edit_element' => 'edit'
			),
			'videoscount' => array(
				'name' => 'Видео',
				'in_admintable' => true,
				'html_table_element' => 'extra',
				'html_table_element_param' => array(
					'call_from' => 'People::get_videos_count',
				),
				'html_edit_element' => 'edit'
			),
			'aliasescount' => array(
				'name' => 'Алиасы',
				'in_admintable' => true,
				'html_table_element' => 'extra',
				'html_table_element_param' => array(
					'call_from' => 'People::get_aliases_count',
				),
				'html_edit_element' => 'edit'
			),
			'biography' => array(
				'name' => 'Биография',
				'editable' => true,
				'in_admintable' => true,
				'html_table_element' => 'value',
				'html_table_element_param' => array(
					'width' => '20px',
					'align' => 'center'
				),
				'html_edit_element' => 'text_area'
			),
			'main' => array(
				'name' => 'main',
				'editable' => false,
				'sortable' => false,
				'in_admintable' => true,
				'html_table_element' => 'value',
				'html_table_element_param' => array(
					'width' => '20px',
					'align' => 'center'
				),
				'html_edit_element' => 'edit'
			),
			'edited' => array(
				'name' => 'edited',
				'editable' => false,
				'sortable' => false,
				'in_admintable' => true,
				'html_table_element' => 'value',
				'html_table_element_param' => array(
					'width' => '20px',
					'align' => 'center'
				),
				'html_edit_element' => 'edit'
			),
			'url_name' => array(
				'name' => 'Текст для ссылки',
				'editable' => true,
				'sortable' => false,
				'in_admintable' => true,
				'html_table_element' => 'value',
				'html_table_element_param' => array(
					'width' => '20px',
					'align' => 'center'
				),
				'html_edit_element' => 'edit'
			),
			'rating' => array(
				'name' => 'rating',
				'editable' => false,
				'sortable' => false,
				'in_admintable' => true,
				'html_table_element' => 'value',
				'html_table_element_param' => array(
					'width' => '20px',
					'align' => 'center'
				),
				'html_edit_element' => 'edit'
			),
			'name2' => array(
				'name' => 'name2',
				'editable' => true,
				'sortable' => false,
				'in_admintable' => true,
				'html_table_element' => 'value',
				'html_table_element_param' => array(
					'width' => '20px',
					'align' => 'center'
				),
				'html_edit_element' => 'edit'
			),
			'last_rating' => array(
				'name' => 'last_rating',
				'editable' => false,
				'sortable' => false,
				'in_admintable' => true,
				'html_table_element' => 'value',
				'html_table_element_param' => array(
					'width' => '20px',
					'align' => 'center'
				),
				'html_edit_element' => 'edit'
			),
			'today_rating' => array(
				'name' => 'today_rating',
				'editable' => false,
				'sortable' => false,
				'in_admintable' => true,
				'html_table_element' => 'value',
				'html_table_element_param' => array(
					'width' => '20px',
					'align' => 'center'
				),
				'html_edit_element' => 'edit'
			),
			'aliases' => array(
				'name' => 'Алиасы названия',
				'editable' => true,
				'html_edit_element' => 'extra',
				'html_edit_element_param' => array(
					'call_from' => 'People::get_aliases',
				)
			),
		);
	}

	public function get_tableinfo() {
		return array(
			'header_list' => 'Исполнители',
			'header_new' => 'Создание исполнителя',
			'header_edit' => 'Редактирование исполнителя'
		);
	}

	public static function get_bio_count($obj) {
		return self::$_db_instance->setQuery('SELECT COUNT(*) FROM  biographies WHERE person_id=' . $obj->id)->loadResult();
	}

	public static function get_album_count($obj) {
		return self::$_db_instance->setQuery('SELECT COUNT(*) FROM  albums_people WHERE person_id=' . $obj->id)->loadResult();
	}

	public static function get_sound_count($obj) {
		return self::$_db_instance->setQuery('SELECT COUNT(*) FROM  compositions_people WHERE person_id=' . $obj->id)->loadResult();
	}

	public static function get_pictures_count($obj) {

	}

	public static function get_videos_count($obj) {
		return self::$_db_instance->setQuery("SELECT COUNT(*) FROM people_videos WHERE person_id=" . $obj->id)->loadResult();
	}

	public static function get_aliases_count($obj) {
		return self::$_db_instance->setQuery("SELECT COUNT(*) FROM aliases WHERE person_id=" . $obj->id)->loadResult();
	}

	public static function get_main_picture($obj) {

		$database = self::$_db_instance;

		$filename = JPATH_SITE . '/images/mp3s/nomp3s.jpg';

		$sql = "SELECT id FROM main_pictures WHERE person_id={$obj->id} ";
		$main_picture_id = $database->setQuery($sql, 0, 1)->loadResult();

		if ($main_picture_id > 0) {
			$sql = "SELECT id FROM pictures WHERE 	attachable_id=$main_picture_id AND attachable_type='MainPicture' ";
			$picture_id = $database->setQuery($sql, 0, 1)->loadResult();

			$p = sprintf('%09d', $picture_id);
			$h = str_split($p, 3);
			$filename = implode('/', $h);
			$filename = sprintf('http://img.zaycev.fm/photos/%s/original/image.jpg', $filename);
		}

		return sprintf('<img src="%s" alt="" title="">', $filename);
	}

	public static function get_aliases($obj) {
		return 555;
	}

}

/**
 * Class Albums
 * @package	Albums
 * @subpackage	Joostina CMS
 * @created	2010-09-06 00:12:41
 */
class Albums extends mosDBTable {

	/**
	 * @var int(11)
	 */
	public $id;
	/**
	 * @var varchar(90)
	 */
	public $name;
	/**
	 * @var date
	 */
	public $year;
	/**
	 * @var int(11)
	 */
	public $studio_id;
	/**
	 * @var int(11)
	 */
	public $duration;
	/**
	 * @var int(11)
	 */
	public $comp_count;

	/*
	 * Constructor
	 * @param object Database object
	 */
	function __construct() {
		$this->mosDBTable('albums', 'id');
	}

	public function check() {
		$this->filter();
		return true;
	}

	public function after_update() {
		return true;
	}

	public function after_store() {
		return true;
	}

	public function before_store() {
		return true;
	}

	public function before_delete() {
		return true;
	}

	public function get_fieldinfo() {
		return array(
			'id' => array(
				'name' => 'id',
				'editable' => false,
				'in_admintable' => true,
				'html_table_element' => 'value',
				'html_table_element_param' => array(),
				'html_edit_element' => 'edit'
			),
			'name' => array(
				'name' => 'Название альбома',
				'editable' => true,
				'in_admintable' => true,
				'html_table_element' => 'editlink',
				'html_table_element_param' => array(),
				'html_edit_element' => 'edit'
			),
			'year' => array(
				'name' => 'Год выпуска',
				'editable' => true,
				'in_admintable' => true,
				'html_table_element' => 'value',
				'html_table_element_param' => array(),
				'html_edit_element' => 'edit'
			),
			'studio_id' => array(
				'name' => 'Выпущен на студии',
				'editable' => true,
				'in_admintable' => true,
				'html_table_element' => 'value',
				'html_table_element_param' => array(),
				'html_edit_element' => 'edit'
			),
			'duration' => array(
				'name' => 'Продолжительность',
				'editable' => true,
				'in_admintable' => true,
				'html_table_element' => 'value',
				'html_table_element_param' => array(),
				'html_edit_element' => 'edit'
			),
			'comp_count' => array(
				'name' => 'Число композиций',
				'editable' => true,
				'in_admintable' => true,
				'html_table_element' => 'value',
				'html_table_element_param' => array(),
				'html_edit_element' => 'edit'
			),
		);
	}

	public function get_tableinfo() {
		return array(
			'header_list' => 'Альбомы',
			'header_new' => 'Создание альбома',
			'header_edit' => 'Редактирование альбома'
		);
	}

}

/**
 * Class Aliases
 * @package	Aliases
 * @subpackage	Joostina CMS
 * @created	2010-09-06 00:12:41
 */
class Aliases extends mosDBTable {

	/**
	 * @var varchar(255)
	 */
	public $name;
	/**
	 * @var int(11)
	 */
	public $person_id;

	/*
	 * Constructor
	 * @param object Database object
	 */
	function __construct() {
		$this->mosDBTable('aliases', 'name');
	}

	public function check() {
		$this->filter();
		return true;
	}

	public function after_update() {
		return true;
	}

	public function after_store() {
		return true;
	}

	public function before_store() {
		return true;
	}

	public function before_delete() {
		return true;
	}

	public function get_fieldinfo() {
		return array(
			'person_id' => array(
				'name' => 'Оригинальный исполнитель',
				'editable' => true,
				'in_admintable' => true,
				'html_table_element' => 'value',
				'html_table_element_param' => array(),
				'html_edit_element' => 'extra',
				'html_edit_element_param' => array(
					'call_from' => 'Aliases::get_person_name',
				)
			),
			'name' => array(
				'name' => 'Алиас',
				'editable' => true,
				'in_admintable' => true,
				'html_table_element' => 'editlink',
				'html_table_element_param' => array(),
				'html_edit_element' => 'edit'
			),
		);
	}

	public function get_tableinfo() {
		return array(
			'header_list' => 'Алиасы',
			'header_new' => 'Создание алиаса',
			'header_edit' => 'Редактирование алиаса'
		);
	}

	public static function get_person_name($obj) {
		return self::$_db_instance->setQuery('SELECT name FROM people WHERE id=' . $obj->person_id, 0, 1)->loadResult();
	}

}

/**
 * Class Biographies
 * @package	Biographies
 * @subpackage	Joostina CMS
 * @created	2010-09-06 00:12:41
 */
class Biographies extends mosDBTable {

	/**
	 * @var int(11)
	 */
	public $id;
	/**
	 * @var text
	 */
	public $name;
	/**
	 * @var int(11)
	 */
	public $version;
	/**
	 * @var int(11)
	 */
	public $person_id;
	/**
	 * @var datetime
	 */
	public $created_at;
	/**
	 * @var datetime
	 */
	public $updated_at;

	/*
	 * Constructor
	 * @param object Database object
	 */
	function __construct() {
		$this->mosDBTable('biographies', 'id');
	}

	public function check() {
		$this->filter(array('name'));
		return true;
	}

	public function after_update() {
		return true;
	}

	public function after_store() {
		return true;
	}

	public function before_store() {
		return true;
	}

	public function before_delete() {
		return true;
	}

	public function get_fieldinfo() {
		return array(
			'id' => array(
				'name' => 'id',
				'editable' => false,
				'in_admintable' => true,
				'html_table_element' => 'editlink',
				'html_table_element_param' => array(),
				'html_edit_element' => 'edit'
			),
			'person_id' => array(
				'name' => 'Исполнитель',
				'editable' => true,
				'in_admintable' => true,
				'html_table_element' => 'value',
				'html_table_element_param' => array(),
				'html_edit_element' => 'extra',
				'html_edit_element_param' => array(
					'call_from' => 'Biographies::get_person_name',
				)
			),
			'name' => array(
				'name' => 'Текст биографии',
				'editable' => true,
				'in_admintable' => true,
				'html_table_element' => 'value',
				'html_table_element_param' => array(),
				'html_edit_element' => 'text_area_wysiwyg'
			),
			'version' => array(
				'name' => 'version',
				'editable' => false,
				'in_admintable' => true,
				'html_table_element' => 'value',
				'html_table_element_param' => array(),
				'html_edit_element' => 'edit'
			),
			'created_at' => array(
				'name' => 'created_at',
				'editable' => false,
				'in_admintable' => true,
				'html_table_element' => 'value',
				'html_table_element_param' => array(),
				'html_edit_element' => 'edit'
			),
			'updated_at' => array(
				'name' => 'updated_at',
				'editable' => false,
				'in_admintable' => true,
				'html_table_element' => 'value',
				'html_table_element_param' => array(),
				'html_edit_element' => 'edit'
			),
		);
	}

	public function get_tableinfo() {
		return array(
			'header_list' => 'Биография',
			'header_new' => 'Создание биографии',
			'header_edit' => 'Редактирование биографии'
		);
	}

	public static function get_person_name($obj) {
		return self::$_db_instance->setQuery('SELECT name FROM people WHERE id=' . $obj->person_id, 0, 1)->loadResult();
	}

}

/**
 * Class Compositions
 * @package	Compositions
 * @subpackage	Joostina CMS
 * @created	2010-09-06 00:12:41
 */
class Compositions extends mosDBTable {

	/**
	 * @var int(11)
	 */
	public $id;
	/**
	 * @var varchar(255)
	 */
	public $name;
	/**
	 * @var int(11)
	 */
	public $album_id;
	/**
	 * @var text
	 */
	public $words;
	/**
	 * @var varchar(255)
	 */
	public $genre;
	/**
	 * @var int(11)
	 */
	public $duration;

	/*
	 * Constructor
	 * @param object Database object
	 */
	function __construct() {
		$this->mosDBTable('compositions', 'id');
	}

	public function check() {
		$this->filter();
		return true;
	}

	public function after_update() {
		return true;
	}

	public function after_store() {
		return true;
	}

	public function before_store() {
		return true;
	}

	public function before_delete() {
		return true;
	}

	public function get_fieldinfo() {
		return array(
			'id' => array(
				'name' => 'id',
				'editable' => true,
				'in_admintable' => true,
				'html_table_element' => 'value',
				'html_table_element_param' => array(),
				'html_edit_element' => 'edit'
			),
			'name' => array(
				'name' => 'name',
				'editable' => true,
				'in_admintable' => true,
				'html_table_element' => 'value',
				'html_table_element_param' => array(),
				'html_edit_element' => 'edit'
			),
			'album_id' => array(
				'name' => 'album_id',
				'editable' => true,
				'in_admintable' => true,
				'html_table_element' => 'value',
				'html_table_element_param' => array(),
				'html_edit_element' => 'edit'
			),
			'words' => array(
				'name' => 'words',
				'editable' => true,
				'in_admintable' => true,
				'html_table_element' => 'value',
				'html_table_element_param' => array(),
				'html_edit_element' => 'edit'
			),
			'genre' => array(
				'name' => 'genre',
				'editable' => true,
				'in_admintable' => true,
				'html_table_element' => 'value',
				'html_table_element_param' => array(),
				'html_edit_element' => 'edit'
			),
			'duration' => array(
				'name' => 'duration',
				'editable' => true,
				'in_admintable' => true,
				'html_table_element' => 'value',
				'html_table_element_param' => array(),
				'html_edit_element' => 'edit'
			),
		);
	}

	public function get_tableinfo() {
		return array(
			'header_list' => 'Compositions',
			'header_new' => 'Создание Compositions',
			'header_edit' => 'Редактирование Compositions'
		);
	}

}

/**
 * Class Main_pictures
 * @package	Main_pictures
 * @subpackage	Joostina CMS
 * @created	2010-09-06 00:12:41
 */
class Main_pictures extends mosDBTable {

	/**
	 * @var int(11)
	 */
	public $id;
	/**
	 * @var int(11)
	 */
	public $person_id;

	/*
	 * Constructor
	 * @param object Database object
	 */
	function __construct() {
		$this->mosDBTable('main_pictures', 'id');
	}

	public function check() {
		$this->filter();
		return true;
	}

	public function after_update() {
		return true;
	}

	public function after_store() {
		return true;
	}

	public function before_store() {
		return true;
	}

	public function before_delete() {
		return true;
	}

	public function get_fieldinfo() {
		return array(
			'id' => array(
				'name' => 'id',
				'editable' => true,
				'in_admintable' => true,
				'html_table_element' => 'value',
				'html_table_element_param' => array(),
				'html_edit_element' => 'edit'
			),
			'person_id' => array(
				'name' => 'person_id',
				'editable' => true,
				'in_admintable' => true,
				'html_table_element' => 'value',
				'html_table_element_param' => array(),
				'html_edit_element' => 'edit'
			),
		);
	}

	public function get_tableinfo() {
		return array(
			'header_list' => 'Main_pictures',
			'header_new' => 'Создание Main_pictures',
			'header_edit' => 'Редактирование Main_pictures'
		);
	}

}

/**
 * Class Missings
 * @package	Missings
 * @subpackage	Joostina CMS
 * @created	2010-09-06 00:12:41
 */
class Missings extends mosDBTable {

	/**
	 * @var int(11)
	 */
	public $id;
	/**
	 * @var varchar(255)
	 */
	public $name;
	/**
	 * @var int(11)
	 */
	public $trial;
	/**
	 * @var varchar(10)
	 */
	public $get_from;
	/**
	 * @var datetime
	 */
	public $created_at;
	/**
	 * @var datetime
	 */
	public $updated_at;
	/**
	 * @var varchar(255)
	 */
	public $state;

	/*
	 * Constructor
	 * @param object Database object
	 */
	function __construct() {
		$this->mosDBTable('missings', 'id');
	}

	public function check() {
		$this->filter();
		return true;
	}

	public function after_update() {
		return true;
	}

	public function after_store() {
		return true;
	}

	public function before_store() {
		return true;
	}

	public function before_delete() {
		return true;
	}

	public function get_fieldinfo() {
		return array(
			'id' => array(
				'name' => 'id',
				'editable' => true,
				'in_admintable' => true,
				'html_table_element' => 'value',
				'html_table_element_param' => array(),
				'html_edit_element' => 'edit'
			),
			'name' => array(
				'name' => 'name',
				'editable' => true,
				'in_admintable' => true,
				'html_table_element' => 'value',
				'html_table_element_param' => array(),
				'html_edit_element' => 'edit'
			),
			'trial' => array(
				'name' => 'trial',
				'editable' => true,
				'in_admintable' => true,
				'html_table_element' => 'value',
				'html_table_element_param' => array(),
				'html_edit_element' => 'edit'
			),
			'get_from' => array(
				'name' => 'get_from',
				'editable' => true,
				'in_admintable' => true,
				'html_table_element' => 'value',
				'html_table_element_param' => array(),
				'html_edit_element' => 'edit'
			),
			'created_at' => array(
				'name' => 'created_at',
				'editable' => true,
				'in_admintable' => true,
				'html_table_element' => 'value',
				'html_table_element_param' => array(),
				'html_edit_element' => 'edit'
			),
			'updated_at' => array(
				'name' => 'updated_at',
				'editable' => true,
				'in_admintable' => true,
				'html_table_element' => 'value',
				'html_table_element_param' => array(),
				'html_edit_element' => 'edit'
			),
			'state' => array(
				'name' => 'state',
				'editable' => true,
				'in_admintable' => true,
				'html_table_element' => 'value',
				'html_table_element_param' => array(),
				'html_edit_element' => 'edit'
			),
		);
	}

	public function get_tableinfo() {
		return array(
			'header_list' => 'Missings',
			'header_new' => 'Создание Missings',
			'header_edit' => 'Редактирование Missings'
		);
	}

}

/**
 * Class Pictures
 * @package	Pictures
 * @subpackage	Joostina CMS
 * @created	2010-09-06 00:12:41
 */
class Pictures extends mosDBTable {

	/**
	 * @var int(11)
	 */
	public $id;
	/**
	 * @var int(11)
	 */
	public $attachable_id;
	/**
	 * @var varchar(255)
	 */
	public $attachable_type;
	/**
	 * @var varchar(255)
	 */
	public $photo_file_name;
	/**
	 * @var varchar(255)
	 */
	public $photo_content_type;
	/**
	 * @var int(11)
	 */
	public $photo_file_size;

	/*
	 * Constructor
	 * @param object Database object
	 */
	function __construct() {
		$this->mosDBTable('pictures', 'id');
	}

	public function check() {
		$this->filter();
		return true;
	}

	public function after_update() {
		return true;
	}

	public function after_store() {
		return true;
	}

	public function before_store() {
		return true;
	}

	public function before_delete() {
		return true;
	}

	public function get_fieldinfo() {
		return array(
			'id' => array(
				'name' => 'id',
				'editable' => true,
				'in_admintable' => true,
				'html_table_element' => 'value',
				'html_table_element_param' => array(),
				'html_edit_element' => 'edit'
			),
			'attachable_id' => array(
				'name' => 'attachable_id',
				'editable' => true,
				'in_admintable' => true,
				'html_table_element' => 'value',
				'html_table_element_param' => array(),
				'html_edit_element' => 'edit'
			),
			'attachable_type' => array(
				'name' => 'attachable_type',
				'editable' => true,
				'in_admintable' => true,
				'html_table_element' => 'value',
				'html_table_element_param' => array(),
				'html_edit_element' => 'edit'
			),
			'photo_file_name' => array(
				'name' => 'photo_file_name',
				'editable' => true,
				'in_admintable' => true,
				'html_table_element' => 'value',
				'html_table_element_param' => array(),
				'html_edit_element' => 'edit'
			),
			'photo_content_type' => array(
				'name' => 'photo_content_type',
				'editable' => true,
				'in_admintable' => true,
				'html_table_element' => 'value',
				'html_table_element_param' => array(),
				'html_edit_element' => 'edit'
			),
			'photo_file_size' => array(
				'name' => 'photo_file_size',
				'editable' => true,
				'in_admintable' => true,
				'html_table_element' => 'value',
				'html_table_element_param' => array(),
				'html_edit_element' => 'edit'
			),
		);
	}

	public function get_tableinfo() {
		return array(
			'header_list' => 'Pictures',
			'header_new' => 'Создание Pictures',
			'header_edit' => 'Редактирование Pictures'
		);
	}

}

/**
 * Class Studios
 * @package	Studios
 * @subpackage	Joostina CMS
 * @created	2010-09-06 00:12:41
 */
class Studios extends mosDBTable {

	/**
	 * @var int(11)
	 */
	public $id;
	/**
	 * @var varchar(50)
	 */
	public $name;

	/*
	 * Constructor
	 * @param object Database object
	 */
	function __construct() {
		$this->mosDBTable('studios', 'id');
	}

	public function check() {
		$this->filter();
		return true;
	}

	public function after_update() {
		return true;
	}

	public function after_store() {
		return true;
	}

	public function before_store() {
		return true;
	}

	public function before_delete() {
		return true;
	}

	public function get_fieldinfo() {
		return array(
			'id' => array(
				'name' => 'id',
				'editable' => true,
				'in_admintable' => true,
				'html_table_element' => 'value',
				'html_table_element_param' => array(),
				'html_edit_element' => 'edit'
			),
			'name' => array(
				'name' => 'name',
				'editable' => true,
				'in_admintable' => true,
				'html_table_element' => 'value',
				'html_table_element_param' => array(),
				'html_edit_element' => 'edit'
			),
		);
	}

	public function get_tableinfo() {
		return array(
			'header_list' => 'Studios',
			'header_new' => 'Создание Studios',
			'header_edit' => 'Редактирование Studios'
		);
	}

}

/**
 * Class Themes
 * @package	Themes
 * @subpackage	Joostina CMS
 * @created	2010-09-06 00:12:41
 */
class Themes extends mosDBTable {

	/**
	 * @var int(11)
	 */
	public $id;
	/**
	 * @var int(11)
	 */
	public $person_id;
	/**
	 * @var int(11)
	 */
	public $padding;
	/**
	 * @var varchar(10)
	 */
	public $color;
	/**
	 * @var varchar(255)
	 */
	public $img_file_name;
	/**
	 * @var varchar(255)
	 */
	public $img_content_type;
	/**
	 * @var int(11)
	 */
	public $img_file_size;

	/*
	 * Constructor
	 * @param object Database object
	 */
	function __construct() {
		$this->mosDBTable('themes', 'id');
	}

	public function check() {
		$this->filter();
		return true;
	}

	public function after_update() {
		return true;
	}

	public function after_store() {
		return true;
	}

	public function before_store() {
		return true;
	}

	public function before_delete() {
		return true;
	}

	public function get_fieldinfo() {
		return array(
			'id' => array(
				'name' => 'id',
				'editable' => true,
				'in_admintable' => true,
				'html_table_element' => 'value',
				'html_table_element_param' => array(),
				'html_edit_element' => 'edit'
			),
			'person_id' => array(
				'name' => 'person_id',
				'editable' => true,
				'in_admintable' => true,
				'html_table_element' => 'value',
				'html_table_element_param' => array(),
				'html_edit_element' => 'edit'
			),
			'padding' => array(
				'name' => 'padding',
				'editable' => true,
				'in_admintable' => true,
				'html_table_element' => 'value',
				'html_table_element_param' => array(),
				'html_edit_element' => 'edit'
			),
			'color' => array(
				'name' => 'color',
				'editable' => true,
				'in_admintable' => true,
				'html_table_element' => 'value',
				'html_table_element_param' => array(),
				'html_edit_element' => 'edit'
			),
			'img_file_name' => array(
				'name' => 'img_file_name',
				'editable' => true,
				'in_admintable' => true,
				'html_table_element' => 'value',
				'html_table_element_param' => array(),
				'html_edit_element' => 'edit'
			),
			'img_content_type' => array(
				'name' => 'img_content_type',
				'editable' => true,
				'in_admintable' => true,
				'html_table_element' => 'value',
				'html_table_element_param' => array(),
				'html_edit_element' => 'edit'
			),
			'img_file_size' => array(
				'name' => 'img_file_size',
				'editable' => true,
				'in_admintable' => true,
				'html_table_element' => 'value',
				'html_table_element_param' => array(),
				'html_edit_element' => 'edit'
			),
		);
	}

	public function get_tableinfo() {
		return array(
			'header_list' => 'Themes',
			'header_new' => 'Создание Themes',
			'header_edit' => 'Редактирование Themes'
		);
	}

}

/**
 * Class Videos
 * @package	Videos
 * @subpackage	Joostina CMS
 * @created	2010-09-06 00:12:41
 */
class Videos extends mosDBTable {

	/**
	 * @var int(11)
	 */
	public $id;
	/**
	 * @var varchar(255)
	 */
	public $name;
	/**
	 * @var text
	 */
	public $resource;
	/**
	 * @var datetime
	 */
	public $created_at;
	/**
	 * @var datetime
	 */
	public $updated_at;
	/**
	 * @var varchar(255)
	 */
	public $state_24;
	/**
	 * @var text
	 */
	public $player;
	/**
	 * @var int(11)
	 */
	public $id_24;
	/**
	 * @var int(11)
	 */
	public $duration;
	/**
	 * @var float
	 */
	public $size_byte;
	/**
	 * @var tinyint(1)
	 */
	public $by_user;
	/**
	 * @var tinyint(1)
	 */
	public $edited;
	/**
	 * @var tinyint(1)
	 */
	public $youtube;
	/**
	 * @var text
	 */
	public $src;

	/*
	 * Constructor
	 * @param object Database object
	 */
	function __construct() {
		$this->mosDBTable('videos', 'id');
	}

	public function check() {
		$this->filter(array('player', 'src'));
		return true;
	}

	public function after_update() {
		return true;
	}

	public function after_store() {
		return true;
	}

	public function before_store() {
		return true;
	}

	public function before_delete() {
		return true;
	}

	public function get_fieldinfo() {
		return array(
			'id' => array(
				'name' => 'id',
				'editable' => false,
				'in_admintable' => true,
				'html_table_element' => 'value',
				'html_table_element_param' => array(),
				'html_edit_element' => 'edit'
			),
			'name' => array(
				'name' => 'Название',
				'editable' => true,
				'in_admintable' => true,
				'html_table_element' => 'editlink',
				'html_table_element_param' => array(),
				'html_edit_element' => 'edit'
			),
			'resource' => array(
				'name' => 'resource',
				'editable' => true,
				'in_admintable' => true,
				'html_table_element' => 'value',
				'html_table_element_param' => array(),
				'html_edit_element' => 'edit'
			),
			'created_at' => array(
				'name' => 'created_at',
				'editable' => true,
				'in_admintable' => true,
				'html_table_element' => 'value',
				'html_table_element_param' => array(),
				'html_edit_element' => 'edit'
			),
			'updated_at' => array(
				'name' => 'updated_at',
				'editable' => true,
				'in_admintable' => true,
				'html_table_element' => 'value',
				'html_table_element_param' => array(),
				'html_edit_element' => 'edit'
			),
			'state_24' => array(
				'name' => 'state_24',
				'editable' => true,
				'in_admintable' => true,
				'html_table_element' => 'value',
				'html_table_element_param' => array(),
				'html_edit_element' => 'edit'
			),
			'player' => array(
				'name' => 'player',
				'editable' => true,
				'in_admintable' => true,
				'html_table_element' => 'value',
				'html_table_element_param' => array(),
				'html_edit_element' => 'edit'
			),
			'id_24' => array(
				'name' => 'id_24',
				'editable' => true,
				'in_admintable' => true,
				'html_table_element' => 'value',
				'html_table_element_param' => array(),
				'html_edit_element' => 'edit'
			),
			'duration' => array(
				'name' => 'duration',
				'editable' => true,
				'in_admintable' => true,
				'html_table_element' => 'value',
				'html_table_element_param' => array(),
				'html_edit_element' => 'edit'
			),
			'size_byte' => array(
				'name' => 'size_byte',
				'editable' => true,
				'in_admintable' => true,
				'html_table_element' => 'value',
				'html_table_element_param' => array(),
				'html_edit_element' => 'edit'
			),
			'by_user' => array(
				'name' => 'by_user',
				'editable' => true,
				'in_admintable' => true,
				'html_table_element' => 'value',
				'html_table_element_param' => array(),
				'html_edit_element' => 'edit'
			),
			'edited' => array(
				'name' => 'edited',
				'editable' => true,
				'in_admintable' => true,
				'html_table_element' => 'value',
				'html_table_element_param' => array(),
				'html_edit_element' => 'edit'
			),
			'youtube' => array(
				'name' => 'youtube',
				'editable' => true,
				'in_admintable' => true,
				'html_table_element' => 'value',
				'html_table_element_param' => array(),
				'html_edit_element' => 'edit'
			),
			'src' => array(
				'name' => 'src',
				'editable' => true,
				'in_admintable' => true,
				'html_table_element' => 'value',
				'html_table_element_param' => array(),
				'html_edit_element' => 'value'
			),
		);
	}

	public function get_tableinfo() {
		return array(
			'header_list' => 'Видео',
			'header_new' => 'Создание видео',
			'header_edit' => 'Редактирование видео'
		);
	}

}
