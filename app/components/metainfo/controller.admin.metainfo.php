<?php

// запрет прямого доступа
defined('_JOOS_CORE') or die();

/**
 * Metainfo - Компонент используется в качестве интерфейса для отображения дефолтной мета-информации
 * Контроллер панели управления
 *
 * @version 1.0
 * @package Joostina.Components.Controllers
 * @subpackage Metainfo  
 * @author Joostina Team <info@joostina.ru>
 * @copyright (C) 2008-2011 Joostina Team
 * @license MIT License http://www.opensource.org/licenses/mit-license.php
 * Информация об авторах и лицензиях стороннего кода в составе Joostina CMS: docs/copyrights
 *
 * */
class actionsAdminMetainfo {

	/**
	 * Название обрабатываемой модели
	 * @var joosModel модель
	 */
	public static $model = 'joosMetainfo';
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
			mosMenuBar::cancel();
			mosMenuBar::endTable();
			$index_tools = ob_get_clean();


		self::$toolbars['index'] = $index_tools;
	}

	/**
	 * Отображение страницы настроек метаданных
	 */
	public static function index($option) {

		$metainfo = new self::$model;
		$metainfo->group = joosRequest::request('group');
		$metainfo->find();

		//Определяем заголовок компонента, с которым работаем
		joosAutoAdmin::$component_title = joosAutoAdmin::get_component_title($metainfo->group);

		//вытягиваем подменю, если оно есть
		$component_menu = joosAutoAdmin::get_component_submenu($metainfo->group);
		if ($component_menu) {
			self::$submenu = $component_menu;
			self::$submenu['metainfo']['active'] = true;
		}

		joosAutoAdmin::edit($metainfo, $metainfo);
	}

	/**
	 * Сохранение отредактированного или созданного объекта
	 */
	public static function save($option, $redirect = 0) {

		joosCSRF::check_code();

		$obj_data = new self::$model;

		$result = $obj_data->save($_POST);

		$group = joosRequest::request('group');


		if ($result == false) {
			echo 'Ошибочка: ' . joosDatabase::instance()->get_error_msg();
			return;
		}

		switch ($redirect) {
			default:
			case 0: // просто сохранение
				return joosRoute::redirect('index2.php?option=' . $option . '&model=' . self::$model . '&group=' . $group, 'Всё ок!');
				break;

			case 1: // применить
				return joosRoute::redirect('index2.php?option=' . $option . '&model=' . self::$model . '&task=edit&id=' . $obj_data->id . '&group=' . $group, 'Всё ок, редактируем дальше');
				break;
		}
	}

	public static function apply($option) {
		return self::save($option, 1);
	}

	public static function cancel($option) {
		$group = joosRequest::request('group');
		return joosRoute::redirect('index2.php?option=' . $group);
	}

}