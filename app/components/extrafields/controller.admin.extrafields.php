<?php

// запрет прямого доступа
defined('_JOOS_CORE') or die();

/**
 * Extrafields    - Компонент управления дополнительными полями
 * Контроллер панели управления
 *
 * @version 1.0
 * @package Joostina.Components.Controllers
 * @subpackage Extrafields  
 * @author Joostina Team <info@joostina.ru>
 * @copyright (C) 2008-2011 Joostina Team
 * @license MIT License http://www.opensource.org/licenses/mit-license.php
 * Информация об авторах и лицензиях стороннего кода в составе Joostina CMS: docs/copyrights
 *
 * */
class actionsAdminExtrafields {

	/**
	 * Название обрабатываемой модели
	 */
	public static $model = 'adminExtrafields';
	/**
	 * Подменю
	 */
	public static $submenu = array();
	/**
	 * Тулбары
	 */
	public static $toolbars = array();

	/**
	 * Выполняется сразу после запуска контроллера
	 */
	public static function action_before() {

		$group = joosRequest::request('group', '');
		if ($group) {
			//Определяем заголовок компонента, с которым работаем
			joosAutoadmin::$component_title = joosAutoadmin::get_component_title($group);

			//вытягиваем подменю, если оно есть
			$controller = 'actions' . ucfirst($group);
			joosLoader::admin_controller($group);
			if (isset($controller::$submenu)) {
				self::$submenu = $controller::$submenu;
				self::$submenu['extrafields']['active'] = true;
			}
		}
	}

	/**
	 * Список объектов
	 *
	 * @param string $option
	 */
	public static function index($option) {


		$obj = new self::$model;

		//количество записей
		$obj_count = joosAutoadmin::get_count($obj);

		//инициализируем постраничную навигацию
		$pagenav = joosAutoadmin::pagenav($obj_count, $option);

		//параметры запроса на получение списка записей
		$param = array(
			'offset' => $pagenav->limitstart,
			'limit' => $pagenav->limit,
			'order' => 'id DESC'
		);

		//получаем массив объектов
		$obj_list = joosAutoadmin::get_list($obj, $param);

		//Массив названий элементов для отображения в таблице списка
		$fields_list = array('id', 'label', 'name');
		//Передаём информацию о объекте и настройки полей в формирование представления
		joosAutoadmin::listing($obj, $obj_list, $pagenav, $fields_list);
	}

	/**
	 * Создание объекта
	 *
	 * @param string $option
	 */
	public static function create($option) {
		self::edit($option, 0);
	}

	/**
	 * Редактирование объекта
	 *
	 * @param string $option
	 * @param integer $id - номер редактируемого объекта
	 */
	public static function edit($option, $id) {

		$obj_data = new self::$model;

		$id > 0 ? $obj_data->load($id) : null;

		//Передаём данные в формирование представления
		joosAutoadmin::edit($obj_data, $obj_data);
	}

	/**
	 * Сохранение информации
	 *
	 * @param string $option
	 * @param integer $redirect
	 */
	private static function save_this($option, $redirect = 0) {

		joosCSRF::check_code();

		$obj_data = new self::$model;

		//сохраняем основные данные
		$result = $obj_data->save($_POST);

		if ($result == false) {
			echo 'Ошибочка: ' . joosDatabase::instance()->get_error_msg();
			return;
		}

		switch ($redirect) {
			default:
			case 0: // просто сохранение
				return joosRoute::redirect('index2.php?option=' . $option . '&group=' . $obj_data->group, 'Всё ок!');
				break;

			case 1: // применить
				return joosRoute::redirect('index2.php?option=' . $option . '&task=edit&group=' . $obj_data->group . '&id=' . $obj_data->id, 'Всё ок, редактируем дальше');
				break;

			case 2: // сохранить и добавить новое
				return joosRoute::redirect('index2.php?option=' . $option . '&group=' . $obj_data->group . '&task=create', 'Всё ок, создаём новое');
				break;
		}
	}

	/**
	 * Сохранение отредактированного или созданного объекта
	 * и перенаправление на главную страницу компонента
	 *
	 * @param string $option
	 */
	public static function save($option) {
		self::save_this($option);
	}

	/**
	 * Сохраняем и возвращаем на форму редактирования
	 *
	 * @param string $option
	 */
	public static function apply($option) {
		return self::save_this($option, 1);
	}

	/**
	 * Сохраняем и направляем на форму создания нового объекта
	 *
	 * @param mixed $option
	 */
	public static function save_and_new($option) {
		return self::save_this($option, 2);
	}

	/**
	 * Удаление объекта или группы объектов, возврат на главную
	 *
	 * @param string $option
	 * @return
	 */
	public static function remove($option) {
		joosCSRF::check_code();

		//идентификаторы удаляемых объектов
		$cid = (array) joosRequest::array_param('cid');

		$obj_data = new self::$model;

		$ef_data = new ExtrafieldsData();

		if ($obj_data->delete_array($cid, 'id')) {
			$ef_data->delete_array($cid, 'field_id');

			joosRoute::redirect('index2.php?option=' . $option, 'Удалено успешно!');
		} else {
			joosRoute::redirect('index2.php?option=' . $option, 'Ошибка удаления');
		}
	}

}