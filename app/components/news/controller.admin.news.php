<?php

// запрет прямого доступа
defined('_JOOS_CORE') or die();

/**
 * News  - Компонент управления новостями
 * Контроллер панели управления
 *
 * @version 1.0
 * @package Joostina.Components.Controllers
 * @subpackage News   
 * @author Joostina Team <info@joostina.ru>
 * @copyright (C) 2008-2011 Joostina Team
 * @license MIT License http://www.opensource.org/licenses/mit-license.php
 * Информация об авторах и лицензиях стороннего кода в составе Joostina CMS: docs/copyrights
 *
 * */
class actionsAdminNews {

	/**
	 * Название обрабатываемой модели
	 * @var joosModel модель
	 */
	public static $model = 'adminNews';
	/**
	 * Подменю
	 */
	public static $submenu = array(
		'news' => array(
			'name' => 'Все новости',
			'href' => 'index2.php?option=news',
			'active' => false
		),
		'categories' => array(
			'name' => 'Категории новостей',
			'href' => 'index2.php?option=categories&group=news',
			'active' => false
		),
		'params' => array(
			'name' => 'Настройки',
			'href' => 'index2.php?option=params&group=news',
			'active' => false
		),
		'metainfo' => array(
			'name' => 'Метаданные по-умолчанию',
			'href' => 'index2.php?option=metainfo&group=news',
			'active' => false
		),
	);
	/**
	 * Тулбары
	 */
	public static $toolbars = array();

	/**
	 * Список объектов
	 *
	 * @param string $option
	 */
	public static function index($option) {

		//установка подменю
		self::$submenu['news']['active'] = true;

		$obj = new self::$model;

		//количество записей
		$obj_count = joosAutoAdmin::get_count($obj);

		//инициализируем постраничную навигацию
		$pagenav = joosAutoAdmin::pagenav($obj_count, $option);

		//параметры запроса на получение списка записей
		$param = array(
			'offset' => $pagenav->limitstart,
			'limit' => $pagenav->limit,
			'order' => 'id DESC'
		);

		//получаем массив объектов
		$obj_list = joosAutoAdmin::get_list($obj, $param);

		// массив названий элементов для отображения в таблице списка
		$fields_list = array('date', 'title', 'type_id', 'state');
		// передаём информацию о объекте и настройки полей в формирование представления
		joosAutoAdmin::listing($obj, $obj_list, $pagenav, $fields_list);
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

		//Параметры
		$obj_data->params = joosParams::get_params('news', 'item', $obj_data->id);

		//Мета-информация
		$obj_data->metainfo = joosMetainfo::get_meta('news', 'item', $obj_data->id);

		//Передаём данные в формирование представления
		joosAutoAdmin::edit($obj_data, $obj_data);
	}

	/**
	 * Сохранение информации
	 *
	 * @param string $option
	 * @param integer $redirect
	 */
	private static function save_this($option, $redirect = 0) {

		joosSpoof::check_code();

		$obj_data = new self::$model;

		//сохраняем основные данные
		$result = $obj_data->save($_POST);

		//Сохранение параметров
		if (isset($_POST['params'])) {
			$params = new joosParams;
			$params->save_params($_POST['params'], 'news', 'item', $obj_data->id);
		}

		//Сохранение мета-информации
		joosMetainfo::add_meta($_POST['metainfo'], 'news', 'item', $obj_data->id);

		if ($result == false) {
			echo 'Ошибочка: ' . joosDatabase::instance()->get_error_msg();
			return;
		}

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
		joosSpoof::check_code();

		//идентификаторы удаляемых объектов
		$cid = (array) joosRequest::array_param('cid');

		$obj_data = new self::$model;

		if ($obj_data->delete_array($cid, 'id')) {
			joosRoute::redirect('index2.php?option=' . $option, 'Удалено успешно!');
		} else {
			joosRoute::redirect('index2.php?option=' . $option, 'Ошибка удаления');
		}
	}

}