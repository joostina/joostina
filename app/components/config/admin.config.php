<?php

/**

 */
// запрет прямого доступа
defined('_JOOS_CORE') or die();

class actionsConfig {

	/**
	 * Название обрабатываемой модели
	 * @var string
	 */
	public static $model = 'Params';
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


	public static function on_start() {

		ob_start();
		mosMenuBar::startTable();
		mosMenuBar::save();
		mosMenuBar::endTable();
		$index_tools = ob_get_contents();
		ob_end_clean();

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

		JoiAdmin::edit($params, $params);
	}

	/**
	 * Сохранение отредактированного или созданного объекта
	 */
	public static function save($option, $redirect = 0) {

		joosSpoof::check_code();

		$obj_data = new self::$model;
		$_POST['data'] = json_encode($_POST['data']);

		$result = $obj_data->save($_POST);


		if ($result == false) {
			echo 'Ошибочка: ' . database::instance()->get_error_msg();
			return;
		}

		switch ($redirect) {
			default:
			case 0: // просто сохранение
				return mosRedirect('index2.php?option=' . $option . '&model=' . self::$model, 'Всё ок!');
				break;

			case 1: // применить
				return mosRedirect('index2.php?option=' . $option . '&model=' . self::$model . '&task=edit&id=' . $obj_data->id, 'Всё ок, редактируем дальше');
				break;
		}
	}

	public static function apply($option) {
		return self::save($option, 1);
	}

}