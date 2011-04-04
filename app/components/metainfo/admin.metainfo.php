<?php

/**
 * Компонент используется в качестве интерфейса для отображения 
 * дефолтной мета-информации. Эти же данные используются для главной страницы компонента 
 */
// запрет прямого доступа
defined('_JOOS_CORE') or die();

joosLoader::lib('metainfo', 'seo');

/**
 * Содержимое
 */
class actionsMetainfo {

	/**
	 * Название обрабатываемой модели
	 * @var joosDBModel модель
	 */
	public static $model = 'Metainfo';
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


	public static function on_start() {

		ob_start();
		mosMenuBar::startTable();
		mosMenuBar::save();
		mosMenuBar::cancel();
		mosMenuBar::endTable();
		$index_tools = ob_get_contents();
		ob_end_clean();

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
		JoiAdmin::$component_title = JoiAdmin::get_component_title($metainfo->group);

		//вытягиваем подменю, если оно есть
		$controller = 'actions' . ucfirst($metainfo->group);
		joosLoader::admin_controller($metainfo->group);
		if (isset($controller::$submenu)) {
			self::$submenu = $controller::$submenu;
			self::$submenu['metainfo']['active'] = true;
		}

		JoiAdmin::edit($metainfo, $metainfo);
	}

	/**
	 * Сохранение отредактированного или созданного объекта
	 */
	public static function save($option, $redirect = 0) {

		joosSpoof::check_code();

		$obj_data = new self::$model;

		$result = $obj_data->save($_POST);

		$group = joosRequest::request('group');


		if ($result == false) {
			echo 'Ошибочка: ' . database::instance()->get_error_msg();
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