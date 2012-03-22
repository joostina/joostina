<?php

// запрет прямого доступа
defined( '_JOOS_CORE' ) or die();

/**
 * modelQuickicons - Компонент управления кнопками быстрого доступа панели управления
 * Контроллер панели управления
 *
 * @version    1.0
 * @package    Controllers
 * @subpackage Quickicons
 * @author     Joostina Team <info@joostina.ru>
 * @copyright  (C) 2007-2012 Joostina Team
 * @license    MIT License http://www.opensource.org/licenses/mit-license.php
 * Информация об авторах и лицензиях стороннего кода в составе Joostina CMS: docs/copyrights
 *
 * */
class actionsAdminQuickicons {

	/**
	 * Название обрабатываемой модели
	 *
	 * @var joosModel модель
	 */
	public static $model = 'modelAdminQuickicons';

	/**
	 * Список объектов
	 */
	public static function index( $option ) {
		$obj       = new self::$model;
		$obj_count = $obj->count();

		$pagenav   = joosAutoadmin::pagenav( $obj_count , $option );

		$param     = array ( 'offset' => $pagenav->limitstart ,
		                     'limit'  => $pagenav->limit ,
		                     'order'  => 'ordering ASC' );
		$obj_list  = $obj->get_list( $param );

		// массив названий элементов для отображения в таблице списка
		$fields_list = array ( 'id' , 'title' , 'group_id' , 'state' );
		// передаём информацию о объекте и настройки полей в формирование представления
		joosAutoadmin::listing( $obj , $obj_list , $pagenav , $fields_list );
	}

	/**
	 * Редактирование
	 */
	public static function create( $option ) {
		self::edit( $option , 0 );
	}

	/**
	 * Редактирование объекта
	 *
	 * @param integer $id - номер редактируемого объекта
	 */
	public static function edit( $option , $id ) {

		joosDocument::instance()->add_js_file( JPATH_SITE . '/app/components/quickicons/media/js/quickicons.js' );

		$obj_data = new self::$model;
		$obj_data->load( $id );

		joosAutoadmin::edit( $obj_data , $obj_data );
	}

	/**
	 * Сохранение отредактированного или созданного объекта
	 */
	public static function save( $option , $id , $page , $task , $create_new = false ) {

		joosCSRF::check_code();

		$obj_data = new self::$model;
		$obj_data->save( $_POST );

		$create_new ? joosRoute::redirect( 'index2.php?option=' . $option . '&task=create' , $obj_data->title . ', cохранено успешно!, Создаём новое' ) : joosRoute::redirect( 'index2.php?option=' . $option , $obj_data->title . ', cохранено успешно!' );
	}

	public static function save_and_new( $option ) {
		self::save( $option , null , null , null , true );
	}

	/**
	 * Удаление одного или группы объектов
	 */
	public static function remove( $option ) {
		joosCSRF::check_code();

		// идентификаторы удаляемых объектов
		$cid      = (array) joosRequest::array_param( 'cid' );

		$obj_data = new self::$model;
		$obj_data->delete_array( $cid , 'id' ) ? joosRoute::redirect( 'index2.php?option=' . $option , 'Удалено успешно!' ) : joosRoute::redirect( 'index2.php?option=' . $option , 'Ошибка удаления' );
	}

}