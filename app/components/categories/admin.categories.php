<?php

/**
 * Categories - компонент управления категориями
 * Контроллер админ-панели
 *
 * @version 1.0
 * @package ComponentsAdmin
 * @subpackage Categories
 * @author JoostinaTeam
 * @copyright (C) 2008-2010 Joostina Team
 * @license see license.txt
 *
 * */
// запрет прямого доступа
defined('_JOOS_CORE') or die();

class actionsCategories {

	/**
	 * Название обрабатываемой модели
	 * @var joosDBModel модель
	 */
	public static $model = 'Categories';
	/**
	 * Массив с пунктами подменю
	 * @var array
	 */
	public static $submenu = array();
	/**
	 * Название компонента, с которым работаем
	 * @var string
	 */
	public static $component_title = '';
	/**
	 * Тулбар
	 * @var array
	 */
	public static $toolbars = array();


	public static function on_start() {

		joosDocument::instance()
				->add_css(JPATH_SITE . '/app/components/categories/media/css/categories.admin.css')
				->add_js_file(JPATH_SITE . '/core/libraries/system/joiadmin/media/js/joiadmin.js')
				->add_js_file(JPATH_SITE . '/app/components/categories/media/js/categories.admin.js');

		joosLoader::admin_model('categories');
		joosLoader::admin_view('categories');

		$group = joosRequest::request('group', '');
		if ($group) {
			//Определяем заголовок компонента, с которым работаем
			JoiAdmin::$component_title = JoiAdmin::get_component_title($group);

			//вытягиваем подменю, если оно есть
			$controller = 'actions' . ucfirst($group);
			joosLoader::admin_controller($group);
			if (isset($controller::$submenu)) {
				self::$submenu = $controller::$submenu;
				self::$submenu['categories']['active'] = true;
			}
		}
	}

	public static function index() {
		ob_start();
		mosMenuBar::startTable();
		mosMenuBar::addNew('create');
		mosMenuBar::endTable();
		$index_tools = ob_get_contents();
		ob_end_clean();

		self::$toolbars['index'] = $index_tools;

		echo JoiAdmin::header(self::$component_title, 'Категории', array(), 'listing');

		$cats = new Categories;
		$rootExists = $cats->check_root_node();

		$tree = $cats->get_full_tree_extended();

		require_once ('views/categories/default.php');

		echo JoiAdmin::footer();
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

		$cat_details = new CategoriesDetails;
		$cat_details->load($id);

		foreach (get_object_vars($cat_details) as $key => $val) {
			$obj_data->$key = $val;
		}

		$obj_data->id = $id;
		$obj_data->cat_id = $id;

		//Параметры
		$obj_data->params_group = $obj_data->group;
		$obj_data->params = Params::get_params($obj_data->group, 'category', $obj_data->id);

		//Мета-информация
		$obj_data->metainfo = array();
		if ($id) {
			$obj_data->metainfo = Metainfo::get_meta('category', 'item', $id);
		}


		thisHTML::edit($obj_data, $obj_data);
	}

	/**
	 * Сохранение информации
	 * 
	 * @param string $option
	 * @param integer $redirect
	 */
	public static function save_this($option, $redirect = 0) {

		joosSpoof::check_code();

		$obj = new self::$model;

		if ($obj->save($_POST) === false) {
			echo 'Ошибочка: ' . database::instance()->get_error_msg();
			return;
		}

		$cat_details = new CategoriesDetails;

		if (joosRequest::post('id', 0)) {
			$_POST['cat_id'] = $obj->id;
			$cat_details->load($obj->id);
			$cat_details->save($_POST);
		} else {
			$cat_details->bind($_POST);
			$cat_details->cat_id = $obj->id;
			database::instance()->insert_object('#__categories_details', $cat_details);
		}

		//Сохранение параметров
		if (isset($_POST['params'])) {
			$params = new Params;
			$params->save_params($_POST['params'], $obj->group, 'category', $obj->id);
		}


		//Сохранение мета-информации
		Metainfo::add_meta($_POST['metainfo'], 'category', 'item', $obj->id);

		switch ($redirect) {
			default:
			case 0: // просто сохранение

				return joosRoute::redirect('index2.php?option=' . $option . '&model=' . self::$model . $obj->get_link_suff(), 'Всё ок!');
				break;

			case 1: // применить
				return joosRoute::redirect('index2.php?option=' . $option . '&model=' . self::$model . '&task=edit&id=' . $obj->id . $obj->get_link_suff(), 'Всё ок, редактируем дальше');
				break;

			case 2: // сохранить и добавить новое
				return joosRoute::redirect('index2.php?option=' . $option . '&model=' . self::$model . '&task=create' . $obj->get_link_suff(), 'Всё ок, создаём новое');
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

	public static function root_add() {

		$cats = new Categories;
		$action = $cats->insert_root_node(joosRequest::post('name'));

		$redirect = 'index2.php?option=categories' . $cats->get_link_suff();
		$action === true ? joosRoute::redirect($redirect, 'Корень успешно создан!') : joosRoute::redirect($redirect, implode(' ', $cats->get_error()));
	}

	public static function node_add() {

		$cats = new Categories;
		$action = $cats->insertChildNode(joosRequest::post('name'), joosRequest::post('id'));

		$redirect = 'index2.php?option=categories' . $cats->get_link_suff();
		$action === true ? joosRoute::redirect($redirect, 'Категория успешно добавлена') : joosRoute::redirect($redirect, implode(' ', $cats->get_error()));
	}

	public static function node_del() {

		$cats = new Categories;


		if (joosRequest::request('del_childs', 'no') == 'yes') {
			$action = $cats->delete_branch(joosRequest::request('id'));
		} else {
			$action = $cats->delete_node(joosRequest::request('id'));
		}


		$redirect = 'index2.php?option=categories' . $cats->get_link_suff();
		$action === true ? joosRoute::redirect($redirect, 'Категория удалена') : joosRoute::redirect($redirect, implode(' ', $cats->get_error()));
	}

	public static function node_move_up() {

		$cats = new Categories;

		$action = $cats->move_up(joosRequest::get('id'));



		$redirect = 'index2.php?option=categories' . $cats->get_link_suff();
		$action === true ? joosRoute::redirect($redirect, 'Категория перемещена') : joosRoute::redirect($redirect, implode(' ', $cats->get_error()));
	}

	public static function node_move_down() {

		$cats = new Categories;

		$action = $cats->move_down(joosRequest::get('id'));

		$redirect = 'index2.php?option=categories' . $cats->get_link_suff();
		$action === true ? joosRoute::redirect($redirect, 'Категория перемещена') : joosRoute::redirect($redirect, implode(' ', $cats->get_error()));
	}

	public static function node_move_lft() {

		$cats = new Categories;

		$action = $cats->move_lft(joosRequest::get('id'));


		$redirect = 'index2.php?option=categories' . $cats->get_link_suff();
		$action === true ? joosRoute::redirect($redirect, 'Категория перемещена') : joosRoute::redirect($redirect, implode(' ', $cats->get_error()));
	}

	public static function node_move_rgt() {

		$cats = new Categories;

		$action = $cats->move_rgt(joosRequest::get('id'));

		$redirect = 'index2.php?option=categories' . $cats->get_link_suff();
		$action === true ? joosRoute::redirect($redirect, 'Категория перемещена') : joosRoute::redirect($redirect, implode(' ', $cats->get_error()));
	}

}