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

Jacl::isDeny('quickicons') ? mosRedirect('index2.php?', _NOT_AUTH) : null;

require joosCore::path('quickicons', 'admin_class');
require joosCore::path('quickicons', 'admin_html');

/**
 * Содержимое
 */
class actionsQuickicons {

	/**
	 * Название обрабатываемой модели
	 * @var joosDBModel модель
	 */
	public static $model = 'Quickicons';

	/**
	 * Список объектов
	 */
	public static function index($option) {
		$obj = new self::$model;
		$obj_count = $obj->count();

		$pagenav = JoiAdmin::pagenav($obj_count, $option);

		$param = array(
			'offset' => $pagenav->limitstart,
			'limit' => $pagenav->limit,
			'order' => 'ordering ASC'
		);
		$obj_list = $obj->get_list($param);

		// передаём данные в представление
		quickiconsHTML::index($obj, $obj_list, $pagenav);
	}

	/**
	 * Редактирование
	 */
	public static function create($option) {
		self::edit($option, 0);
	}

	/**
	 * Редактирование объекта
	 * @param integer $id - номер редактируемого объекта
	 */
	public static function edit($option, $id) {

		joosDocument::instance()->add_js_file(JPATH_SITE . '/app/components/quickicons/media/js/quickicons.js');

		$obj_data = new self::$model;
		$obj_data->load($id);

		quickiconsHTML::edit($obj_data, $obj_data);
	}

	/**
	 * Сохранение отредактированного или созданного объекта
	 */
	public static function save($option, $id, $page, $task, $create_new = false) {

		joosSpoof::check_code();

		$obj_data = new self::$model;
		$obj_data->save($_POST);

		$create_new ? mosRedirect('index2.php?option=' . $option . '&task=create', $obj_data->title . ', cохранено успешно!, Создаём новое') : mosRedirect('index2.php?option=' . $option, $obj_data->title . ', cохранено успешно!');
	}

	public static function save_and_new($option) {
		self::save($option, null, null, null, true);
	}

	/**
	 * Удаление одного или группы объектов
	 */
	public static function remove($option) {
		joosSpoof::check_code();

		// идентификаторы удаляемых объектов
		$cid = (array) joosRequest::array_param('cid');

		$obj_data = new self::$model;
		$obj_data->delete_array($cid, 'id') ? mosRedirect('index2.php?option=' . $option, 'Удалено успешно!') : mosRedirect('index2.php?option=' . $option, 'Ошибка удаления');
	}

}