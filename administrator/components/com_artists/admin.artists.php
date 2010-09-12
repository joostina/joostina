<?php

defined('_VALID_MOS') or die();

//ini_set ('display_errors', 1);

require_once 'artists.class.php';
require_once ($mainframe->getPath('admin_html'));

//define('JPATH_BASE', dirname(dirname(dirname(dirname(__FILE__)))));
//define('JPATH_BASE_ADMIN', dirname(dirname(dirname(__FILE__))));


mosMainFrame::addLib('joiadmin');
mosMainFrame::addLib('html');

JoiAdmin::dispatch();

class actionsArtists {

	private static $mp3tables = array(
		'people' => array('Исполнители', array('id', 'name', 'biocount', 'albumscount', 'compositionscount', 'picturescount', 'videoscount', 'aliasescount')),
		'videos' => array('Видео', array('name','player')),
		'albums' => array('Альбомы', array('name', 'year','comp_count')),
		'aliases' => array('Алиасы', array('name')),
		'biographies' => array('Биографии', array('id', 'name')),
		'compositions' => array('Композиции', array('id', 'name')),
		'pictures' => array('Изображения', array('id', 'name')),
		'studios' => array('Студии', array('id', 'name')),
	);

	public static function index($option) {

		$current_model = mosGetParam($_REQUEST, 'model', 'people');

		echo viewsArtists::top_hrefs(self::$mp3tables);
		echo '<br />';

		actionsCurrent::$model = $current_model;
		actionsCurrent::$model_data = self::$mp3tables[$current_model];
		actionsCurrent::index($option);
	}

	public static function edit($option, $id) {

		$current_model = mosGetParam($_REQUEST, 'model', 'people');

		echo viewsArtists::top_hrefs(self::$mp3tables);
		echo '<br />';

		actionsCurrent::$model = $current_model;
		actionsCurrent::$model_data = self::$mp3tables[$current_model];
		actionsCurrent::edit($option, $id);
	}

	public static function save($option) {
		$current_model = mosGetParam($_REQUEST, 'model', 'people');

		echo viewsArtists::top_hrefs(self::$mp3tables);
		echo '<br />';

		actionsCurrent::$model = $current_model;
		actionsCurrent::$model_data = self::$mp3tables[$current_model];
		actionsCurrent::save($option);
	}

	public static function save_and_new($option) {
		self::save($option, null, null, null, true);
	}

	public static function remove($option) {
		$current_model = mosGetParam($_REQUEST, 'model', 'people');

		echo viewsArtists::top_hrefs(self::$mp3tables);
		echo '<br />';

		actionsCurrent::$model = $current_model;
		actionsCurrent::$model_data = self::$mp3tables[$current_model];
		actionsCurrent::remove($option);
	}

}

class actionsCurrent {

	public static $model = 'People';
	public static $model_data = 'People';

	public static function index($option) {

		JoiAdmin::$model = self::$model;

		$obj = new self::$model;
		$obj_count = $obj->count();

		$pagenav = JoiAdmin::pagenav($obj_count, $option);

		$param = array(
			'offset' => $pagenav->limitstart,
			'limit' => $pagenav->limit,
			'order' => self::$model_data[1][0] . ' DESC'
		);
		$obj_list = $obj->get_list($param);

		viewsArtists::index($obj, $obj_list, $pagenav, self::$model_data[1]);
	}

	public static function create($option) {
		self::edit($option, 0);
	}

	public static function edit($option) {

		JoiAdmin::$model = self::$model;

		$obj_data = new self::$model;

		$key = $obj_data->getKeyField();
		$key_value = mosGetParam($_GET, $key, false);

		$obj_data->load($key_value);

		viewsArtists::edit($obj_data, $obj_data);
	}

	public static function save($option) {
		josSpoofCheck();

		JoiAdmin::$model = self::$model;

		_xdump($_POST);
		die();

		$obj_data = new self::$model;
		$obj_data->save($_POST);

		$create_new ? mosRedirect('index2.php?option=' . $option.'&model='.self::$model. '&task=new', 'Сохранено') : mosRedirect('index2.php?option=' . $option.'&model='.self::$model, 'Всё ок!');
	}

	public static function save_and_new($option) {
		self::save($option, null, null, null, true);
	}

	public static function remove($option) {
		josSpoofCheck();

		$cid = (array) josGetArrayInts('cid');

		JoiAdmin::$model = self::$model;

		$obj_data = new self::$model;
		$obj_data->delete_array($cid, $obj_data->getKeyField() ) ? mosRedirect('index2.php?option=' . $option.'&model='.self::$model, 'Удалено отлично!') : mosRedirect('index2.php?option=' . $option, 'РћС€РёР±РєР° СѓРґР°Р»РµРЅРёСЏ');
	}

}

class viewsArtists {

	public static function top_hrefs($hrefs) {
		$base_href = 'index2.php?option=com_artists&model=';
		$result = array();
		foreach ($hrefs as $name => $table) {
			$result[] = HTML::anchor($base_href . $name, $table[0]);
		}

		return implode(' | ', $result);
	}

	/**
	 * Список объектов
	 * @param mosDBTable $obj - основной объект отображения
	 * @param array $obj_list - список объектов вывода
	 * @param mosPageNav $pagenav - объект постраничной навигации
	 */
	public static function index($obj, array $obj_list, $pagenav, array $fields_list = array()) {
		// массив названий элементов для отображения в таблице списка
		//$fields_list = array( 'id', 'title', 'state');
		// передаём информацию о объекте и настройки полей в формирование представления
		//_xdump($fields_list);

		JoiAdmin::listing($obj, $obj_list, $pagenav, $fields_list);
	}

	/**
	 * Редактирование-создание объекта
	 * @param mosDBTable $articles_obj - объект  редактирования с данными, либо пустой - при создании
	 * @param stdClass $articles_data - свойства объекта
	 */
	public static function edit($articles_obj, $articles_data) {
		// передаём данные в формирование представления
		JoiAdmin::edit($articles_obj, $articles_data);
	}

}