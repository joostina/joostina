<?php

// запрет прямого доступа
defined('_JOOS_CORE') or die();

/**
 * Компонент управления независимыми страницами
 * Контроллер панели управления
 *
 * @version    1.0
 * @package    Controllers
 * @subpackage Pages
 * @author     Joostina Team <info@joostina.ru>
 * @copyright  (C) 2007-2012 Joostina Team
 * @license    MIT License http://www.opensource.org/licenses/mit-license.php
 * Информация об авторах и лицензиях стороннего кода в составе Joostina CMS: docs/copyrights
 *
 * */
class actionsAdminPages  extends joosAdminController{

	/**
	 * Название обрабатываемой модели
	 *
	 * @var joosModel модель
	 */
	public static $model = 'modelAdminPages';

	/**
	 * Подменю
	 */
	public static $submenu = array(
		'pages' => array(
			'name' => 'Все страницы',
			'href' => 'index2.php?option=pages',
			'active' => false
		),
	);

	/**
	 * Тулбары
	 */
	public static $toolbars = array();

	public static function action_before() {

		joosDocument::instance()
				->add_js_file(JPATH_SITE . '/app/components/pages/media/js/pages.admin.js');
	}

	/**
	 * Список объектов
	 *
	 * @param string $option
	 */
	public static function index($option) {

		//установка подменю
		self::$submenu['pages']['active'] = true;

		$obj = new self::$model;

		//количество записей
		$obj_count = joosAutoadmin::get_count($obj);

		//инициализируем постраничную навигацию
		$pagenav = joosAutoadmin::pagenav($obj_count, $option);

		//параметры запроса на получение списка записей
		$param = array('offset' => $pagenav->limitstart,
			'limit' => $pagenav->limit,
			'order' => 'id DESC');

		//получаем массив объектов
		$obj_list = joosAutoadmin::get_list($obj, $param);

		// массив названий элементов для отображения в таблице списка
		$fields_list = array('id', 'title', 'slug', 'state');
		// передаём информацию о объекте и настройки полей в формирование представления
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
	 * @param string  $option
	 * @param integer $id - номер редактируемого объекта
	 */
	public static function edit($option, $id) {

		$obj_data = new self::$model;

		$id > 0 ? $obj_data->load($id) : null;

		// передаём данные в формирование представления
		joosAutoadmin::edit($obj_data, $obj_data);
	}

	/**
	 * Сохранение информации
	 *
	 * @param string  $option
	 * @param integer $redirect
	 */
	private static function save_this($option, $redirect = 0) {

		joosCSRF::check_code();

		$obj_data = new self::$model;

		//сохраняем основные данные
		$result = $obj_data->save($_POST);

		//Сохранение параметров
		if (isset($_POST['params'])) {
			$params = new joosParams;
			$params->save_params($_POST['params'], 'pages', 'item', $obj_data->id);
		}

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

			case 2: // сохранить и добавит