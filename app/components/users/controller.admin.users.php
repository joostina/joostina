<?php

// запрет прямого доступа
defined('_JOOS_CORE') or die();

/**
 * modelUsers - Компонент управления пользователями и группами
 * Контроллер панели управления
 *
 * @version    1.0
 * @package    Joostina.Components.Controllers
 * @subpackage modelUsers
 * @author     Joostina Team <info@joostina.ru>
 * @copyright  (C) 2007-2012 Joostina Team
 * @license    MIT License http://www.opensource.org/licenses/mit-license.php
 * Информация об авторах и лицензиях стороннего кода в составе Joostina CMS: docs/copyrights
 *
 * */
class actionsAdminUsers {

	/**
	 * Название обрабатываемой модели
	 *
	 * @var joosModel модель
	 */
	public static $model = 'modelAdminUsers';

	/**
	 * Активный по умолчанию пункт меню
	 *
	 * @var string
	 */
	private static $active_menu = 'users';
	private static $fields_list;

	/**
	 * Подменю
	 */
	public static $submenu = array(
		'users' => array(
			'name' => 'Пользователи',
			'href' => 'index2.php?option=users',
			'model' => 'modelAdminUsers',
			'fields' => array('id', 'user_name', 'group_id', 'lastvisit_date', 'state'),
			'active' => false
		),
		'usersgroups' => array(
			'name' => 'Группы пользователей',
			'href' => 'index2.php?option=users&menu=usersgroups',
			'model' => 'modelAdminUsersGroups',
			'fields' => array('group_title', 'title', 'parent_id'),
			'active' => false
		)
	);

	public static function action_before() {

		$menu = joosRequest::request('menu', false);

		if ($menu && isset(self::$submenu[$menu])) {

			self::$active_menu = $menu;

			$active = self::$submenu[$menu];

			self::$submenu[$menu]['active'] = true;
			self::$model = $active['model'];

			joosAutoadmin::$submenu = $menu;
		} else {
			$menu = self::$active_menu;
		}


		self::$fields_list = isset(self::$submenu[$menu]['fields']) ? self::$submenu[$menu]['fields'] : array('id', 'title', 'type_id', 'category_id', 'state');

		joosAutoadmin::$model = self::$model;
	}

	/**
	 * Список объектов
	 */
	public static function index($option) {
		$obj = new self::$model;
		$obj_count = joosAutoadmin::get_count($obj);

		$pagenav = joosAutoadmin::pagenav($obj_count, $option);

		$param = array(
			'offset' => $pagenav->limitstart,
			'limit' => $pagenav->limit,
			'order' => 'id DESC'
		);
		$obj_list = joosAutoadmin::get_list($obj, $param);

		// передаём информацию о объекте и настройки полей в формирование представления
		joosAutoadmin::listing($obj, $obj_list, $pagenav, self::$fields_list);
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

		joosAutoadmin::edit($obj_data, $obj_data);
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
				return joosRoute::redirect('index2.php?option=' . $option . '&menu=' . self::$active_menu, 'Всё ок!');
				break;

			case 1: // применить
				return joosRoute::redirect('index2.php?option=' . $option . '&menu=' . self::$active_menu . '&task=edit&id=' . $obj_data->id, 'Всё ок, редактируем дальше');
				break;

			case 2: // сохранить и добавить новое
				return joosRoute::redirect('index2.php?option=' . $option . '&menu=' . self::$active_menu . '&task=create', 'Всё ок, создаём новое');
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
		$obj_data->delete_array($cid, 'id') ? joosRoute::redirect('index2.php?option=' . $option . '&menu=' . self::$active_menu, 'Удалено успешно!') : joosRoute::redirect('index2.php?option=' . $option . '&menu=' . self::$active_menu, 'Ошибка удаления');
	}

}