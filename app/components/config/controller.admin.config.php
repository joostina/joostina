<?php

// запрет прямого доступа
defined('_JOOS_CORE') or die();

/**
 * Config  - Компонент управления параметрами
 * Контроллер панели управления
 *
 * @version 1.0
 * @package Joostina.Components.Controllers
 * @subpackage Config 
 * @author Joostina Team <info@joostina.ru>
 * @copyright (C) 2007-2011 Joostina Team
 * @license MIT License http://www.opensource.org/licenses/mit-license.php
 * Информация об авторах и лицензиях стороннего кода в составе Joostina CMS: docs/copyrights
 *
 * */
class actionsAdminConfig {

	/**
	 * Название обрабатываемой модели
	 * @var string
	 */
	public static $model = 'joosParams';
	/**
	 * Массив с пунктами подменю
	 * @var array
	 */
	public static $submenu = array();
	/**
	 * Тулбар
	 * @var array
	 */
	public static $toolbars = array();


	public static function action_before() {

		ob_start();
		mosMenuBar::startTable();
		mosMenuBar::save();
		mosMenuBar::endTable();
		$index_tools = ob_get_clean();

		self::$toolbars['index'] = $index_tools;
	}

	/**
	 * Отображение страницы настроек
	 */
	public static function index($option) {

		$params = new self::$model;
		$params->group = joosRequest::request('group');
		$params->subgroup = 'default';
		$params->find();

		$params->data = json_decode($params->data, true);

		//вытягиваем подменю, если оно есть
		$controller = 'actions' . ucfirst($params->group);
		joosLoader::admin_controller($params->group);
		if (isset($controller::$submenu)) {
			self::$submenu = $controller::$submenu;
			self::$submenu['params']['active'] = true;
		}

		joosAutoadmin::edit($params, $params);
	}

	/**
	 * Сохранение отредактированного или созданного объекта
	 */
	public static function save($option, $redirect = 0) {

		joosCSRF::check_code();

		$obj_data = new self::$model;
		$_POST['data'] = json_encode($_POST['data']);

		$result = $obj_data->save($_POST);


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
		}
	}

	public static function apply($option) {
		return self::save($option, 1);
	}

}