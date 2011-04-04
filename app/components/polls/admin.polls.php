<?php
/**
 * @package Joostina
 * @copyright Авторские права (C) 2007-2010 Joostina team. Все права защищены.
 * @license Лицензия http://www.gnu.org/licenses/gpl-2.0.htm GNU/GPL, или help/license.php
 * Joostina! - свободное программное обеспечение распространяемое по условиям лицензии GNU/GPL
 * Для получения информации о используемых расширениях и замечаний об авторском праве, смотрите файл help/copyright.php.
 */
// запрет прямого доступа
defined('_JOOS_CORE') or die();

/**
 * Содержимое
 */
class actionsPolls {

	/**
	 * Название обрабатываемой модели
	 * @var joosDBModel модель
	 */
	public static $model = 'adminPolls';
	
	/**
	 * Тулбары
	 */	
	public static  $toolbars = array();
	
	
	public static function on_start() {		
		joosLoader::admin_model('polls');

	}	
			

	/**
	 * Список объектов
	 */
	public static function index($option) {
		
		$obj = new self::$model;
		$obj_count = JoiAdmin::get_count($obj);

		$pagenav = JoiAdmin::pagenav($obj_count, $option);

		$param = array(
			'offset' => $pagenav->limitstart,
			'limit' => $pagenav->limit,
			'order' => 'id DESC'
		);
		$obj_list = JoiAdmin::get_list($obj, $param);

        // массив названий элементов для отображения в таблице списка
        $fields_list = array( 'id', 'title',  'state');
        // передаём информацию о объекте и настройки полей в формирование представления
        JoiAdmin::listing( $obj, $obj_list, $pagenav, $fields_list );
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
		
		$obj_data->questions =  implode( "\n",  json_decode($obj_data->questions, true) );
		$obj_data->variants =  implode( "\n",  json_decode($obj_data->variants, true) );

        // передаём данные в формирование представления
        JoiAdmin::edit($obj_data, $obj_data);
	}

	/**
	 * Сохранение отредактированного или созданного объекта
	 */
	public static function save($option, $redirect = 0) {

		joosSpoof::check_code();

		$obj_data = new self::$model;
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

			case 2: // сохранить и добавить новое
				return mosRedirect('index2.php?option=' . $option . '&model=' . self::$model . '&task=create', 'Всё ок, создаём новое');
				break;
		}
	}

	public static function apply($option) {
		return self::save($option, 1);
	}

	public static function save_and_new($option) {
		return self::save($option, 2);
	}

	/**
	 * Удаление одного или группы объектов
	 */
	public static function remove($option) {
		joosSpoof::check_code();

		// идентификаторы удаляемых объектов
		$cid = (array) joosRequest::array_param('cid');

		$obj_data = new self::$model;
		$obj_data->delete_array($cid, 'id') ? mosRedirect('index2.php?option=' . $option, 'Удалено успешно!') : mosRedirect('index2.php?option=' . $option, 'Ошибка удаления');
	}

}