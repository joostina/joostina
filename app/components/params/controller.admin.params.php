<?php

// запрет прямого доступа
defined('_JOOS_CORE') or die();

/**
 * Params  - Компонент используется в качестве интерфейса для отображения дефолтного конфига любого компонента
 * Контроллер панели управления
 *
 * @version 1.0
 * @package Joostina.Components.Controllers
 * @subpackage Params   
 * @author Joostina Team <info@joostina.ru>
 * @copyright (C) 2008-2011 Joostina Team
 * @license MIT License http://www.opensource.org/licenses/mit-license.php
 * Информация об авторах и лицензиях стороннего кода в составе Joostina CMS: docs/copyrights
 *
 * */
class actionsAdminParams {

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
	/**
	 * Название компонента, с которым работаем
	 * @var string
	 */
	public static $component_title = '';


	public static function action_before() {

		ob_start();
		mosMenuBar::startTable();
		mosMenuBar::save();
		mosMenuBar::apply();
		mosMenuBar::cancel();
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


		//Определяем заголовок компонента, с которым работаем
		joosAutoAdmin::$component_title = joosAutoAdmin::get_component_title($params->group);

		//вытягиваем подменю, если оно есть
		$component_menu = joosAutoAdmin::get_component_submenu($params->group);
		if ($component_menu) {
			self::$submenu = $component_menu;
			self::$submenu['params']['active'] = true;
		}

		joosAutoAdmin::edit($params, $params);
	}

	/**
	 * Сохранение отредактированного или созданного объекта
	 */
	public static function save_this($option, $redirect = 0) {

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
				return joosRoute::redirect('index2.php?option=' . $obj_data->group, 'Всё ок!');
				break;

			case 1: // применить
				return joosRoute::redirect('index2.php?option=' . $option . '&group=' . $obj_data->group, 'Всё ок, редактируем дальше');
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

	public static function cancel($option) {
		$group = joosRequest::request('group');
		return joosRoute::redirect('index2.php?option=' . $group);
	}

}