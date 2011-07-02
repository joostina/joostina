<?php

// запрет прямого доступа
defined('_JOOS_CORE') or die();

/**
 * Blog - Компонент ведения личного и общего блога
 * Контроллер панели управления
 *
 * @version 1.0
 * @package Joostina.Components.Controllers
 * @subpackage Blog
 * @author Joostina Team <info@joostina.ru>
 * @copyright (C) 2008-2011 Joostina Team
 * @license MIT License http://www.opensource.org/licenses/mit-license.php
 * Информация об авторах и лицензиях стороннего кода в составе Joostina CMS: docs/copyrights
 *
 * */
class actionsAdminBlog {

	/**
	 * Название обрабатываемой модели
	 * @var joosModel модель
	 */
	public static $model = 'adminBlog';

	/**
	 * Список объектов
	 */
	public static function index($option) {
		$obj = new self::$model;
		$obj_count = joosAutoAdmin::get_count($obj);

		$pagenav = joosAutoAdmin::pagenav($obj_count, $option);

		$param = array(
			'offset' => $pagenav->limitstart,
			'limit' => $pagenav->limit,
			'order' => 'id DESC'
		);
		$obj_list = joosAutoAdmin::get_list($obj, $param);

		// массив названий элементов для отображения в таблице списка
		$fields_list = array('id', 'title', 'type_id', 'category_id', 'state');
		// передаём информацию о объекте и настройки полей в формирование представления
		joosAutoAdmin::listing($obj, $obj_list, $pagenav, $fields_list);
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
		$obj_data = new self::$model;
		$id > 0 ? $obj_data->load($id) : null;

		joosAutoAdmin::edit($obj_data, $obj_data);
	}

	/**
	 * Сохранение отредактированного или созданного объекта
	 */
	public static function save($option, $id, $page, $task, $redirect = 0) {

		joosCSRF::check_code();

		$obj_data = new self::$model;
		$result = $obj_data->save($_POST);

		switch ($redirect) {
			default:
			case 0: // просто сохранение
				return joosRoute::redirect('index2.php?option=' . $option . '&model=' . self::$model, 'Всё ок!');
				break;

			case 1: // применить
				return joosRoute::redirect('index2.php?option=' . $option . '&model=' . self::$model . '&task=edit&id=' . $obj_data->id, 'Всё ок, редактируем дальше');
				break;

			case 2: // сохранить и добавить новое
				return joosRoute::redirect('index2.php?option=' . $option . '&model=' . self::$model . '&task=create', 'Всё ок, создаём новое');
				break;
		}
	}

	public static function apply($option) {
		return self::save($option, null, null, null, 1);
	}

	public static function save_and_new($option) {
		return self::save($option, null, null, null, 2);
	}

	/**
	 * Удаление одного или группы объектов
	 */
	public static function remove($option) {
		joosCSRF::check_code();

		// идентификаторы удаляемых объектов
		$cid = (array) joosRequest::array_param('cid');

		$obj_data = new self::$model;
		$obj_data->delete_array($cid, 'id') ? joosRoute::redirect('index2.php?option=' . $option, 'Удалено успешно!') : joosRoute::redirect('index2.php?option=' . $option, 'Ошибка удаления');
	}

}