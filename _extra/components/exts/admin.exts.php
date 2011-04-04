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

joosLoader::lib('joiadmin', 'system');
JoiAdmin::dispatch();

/**
 * Содержимое
 */
class actionsExts {

	public static function on_start() {
		joosLoader::model('exts');
		joosLoader::admin_model('exts');
	}

	private static $headers_data = array(
		'adminExts' => array('Расширения',
			array('title', 'state')
		),
		'adminExtsAttr' => array('Аттрибуты расширений',
			array('title', 'state')
		),
		'adminExtsAttrValues' => array('Значения аттрибутов расширений',
			array('title', 'attr_id', 'state')
		),
		'adminExtsCats' => array('Категории расширений',
			array('title', 'state')
		),
	);
	/**
	 * Название обрабатываемой модели
	 * @var joosDBModel модель
	 */
	public static $model = 'adminExts';

	public static function index($option) {
		$current_model = joosRequest::request('model', self::$model);

		actionsCurrent::$headers_menu = viewsCurrent::get_hrefs(self::$headers_data);
		actionsCurrent::$model = $current_model;
		actionsCurrent::$model_data = self::$headers_data[$current_model];
		actionsCurrent::index($option);
	}

	public static function create($option) {
		$current_model = joosRequest::request('model', self::$model);

		actionsCurrent::$headers_menu = viewsCurrent::get_hrefs(self::$headers_data);
		actionsCurrent::$model = $current_model;
		actionsCurrent::$model_data = self::$headers_data[$current_model];
		actionsCurrent::create($option, 0);
	}

	public static function edit($option, $id) {
		$current_model = joosRequest::request('model', self::$model);

		actionsCurrent::$headers_menu = viewsCurrent::get_hrefs(self::$headers_data);
		actionsCurrent::$model = $current_model;
		actionsCurrent::$model_data = self::$headers_data[$current_model];
		actionsCurrent::edit($option, $id);
	}

	public static function save($option, $a, $b, $redirect = 0) {
		$current_model = joosRequest::request('model', self::$model);

		actionsCurrent::$headers_menu = viewsCurrent::get_hrefs(self::$headers_data);
		actionsCurrent::$model = $current_model;
		actionsCurrent::$model_data = self::$headers_data[$current_model];
		actionsCurrent::save($option, $redirect);
	}

	public static function apply($option) {
		self::save($option, 0,0,1);
	}

	public static function save_and_new($option) {
		self::save($option, 0,0, 2);
	}

	public static function remove($option) {
		$current_model = joosRequest::request('model', self::$model);

		actionsCurrent::$headers_menu = viewsCurrent::get_hrefs(self::$headers_data);
		actionsCurrent::$model = $current_model;
		actionsCurrent::$model_data = self::$headers_data[$current_model];
		actionsCurrent::remove($option);
	}

}

class actionsCurrent {

	public static $model;
	public static $model_data;
	public static $headers_menu;

	public static function index($option) {

		JoiAdmin::$model = self::$model;
		// верхнее меню текущего компонента
		JoiAdmin::$headers_menu = self::$headers_menu;

		$obj = new self::$model;
		$obj_count = JoiAdmin::get_count($obj);

		$pagenav = JoiAdmin::pagenav($obj_count, $option);

		$param = array(
			'offset' => $pagenav->limitstart,
			'limit' => $pagenav->limit,
			'order' => 'id DESC'
		);
		$obj_list = JoiAdmin::get_list($obj, $param);

		viewsCurrent::index($obj, $obj_list, $pagenav, self::$model_data[1]);
	}

	public static function create($option) {
		self::edit($option, 0);
	}

	public static function edit($option) {

		JoiAdmin::$model = self::$model;

		$obj_data = new self::$model;

		$key = $obj_data->get_key_field();
		$key_value = joosRequest::int($key, false, $_GET);

		$obj_data->load($key_value);

		viewsCurrent::edit($obj_data, $obj_data);
	}

	public static function save($option, $redirect = 0) {
		joosSpoof::check_code();

		JoiAdmin::$model = self::$model;

		$obj_data = new self::$model;
		$result = $obj_data->save($_POST);

		if ($result == false) {
			echo 'Ошибочка: ' . database::instance()->get_error_msg();
			return;
		}

		switch ($redirect) {
			default:
			case 0: // просто сохранение
				return mosRedirect('index2.php?option=' . $option . '&model=' . self::$model, 'Всё ок!');
				break;

			case 1: // применить
				return mosRedirect('index2.php?option=' . $option . '&model=' . self::$model . '&task=edit&id=' . $obj_data->id, 'Всё ок, редактируем дальше');
				break;

			case 2: // сохранить и добавить новое
				return mosRedirect('index2.php?option=' . $option . '&model=' . self::$model . '&task=create', 'Всё ок, создаём новое');
				break;
		}
	}

	public static function apply($option) {
		return self::save($option, 1);
	}

	public static function save_and_new($option) {
		return self::save($option, 2);
	}

	public static function remove($option) {
		joosSpoof::check_code();

		$cid = joosRequest::array_param('cid');

		JoiAdmin::$model = self::$model;

		$obj_data = new self::$model;
		$obj_data->delete_array($cid, $obj_data->get_key_field());
	}

}

class viewsCurrent {

	public static function get_hrefs(array $data) {

		$option = joosRequest::request('option', '');
		$model = joosRequest::request('model', '');

		$base_href = sprintf('index2.php?option=%s&model=', $option);
		$result = array();
		foreach ($data as $name => $table) {
			$result[] = array('href' => $base_href . $name, 'name' => $table[0], 'active' => ( $model == $name ? 1 : 0 ));
		}
		return $result;
	}

	/**
	 * Список объектов
	 * @param joosDBModel $obj - основной объект отображения
	 * @param array $obj_list - список объектов вывода
	 * @param joosPagenator $pagenav - объект постраничной навигации
	 */
	public static function index($obj, array $obj_list, $pagenav, array $fields_list = array()) {
		JoiAdmin::listing($obj, $obj_list, $pagenav, $fields_list);
	}

	/**
	 * Редактирование-создание объекта
	 * @param joosDBModel $articles_obj - объект  редактирования с данными, либо пустой - при создании
	 * @param stdClass $articles_data - свойства объекта
	 */
	public static function edit($articles_obj, $articles_data) {

		// передаём данные в формирование представления
		JoiAdmin::edit($articles_obj, $articles_data);
	}

}