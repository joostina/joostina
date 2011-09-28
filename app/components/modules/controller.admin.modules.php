<?php

// запрет прямого доступа
defined( '_JOOS_CORE' ) or die();

/**
 * Modules - Компонент управления модулями
 * Контроллер панели управления
 *
 * @version    1.0
 * @package    Joostina.Components.Controllers
 * @subpackage Modules
 * @author     Joostina Team <info@joostina.ru>
 * @copyright  (C) 2007-2011 Joostina Team
 * @license    MIT License http://www.opensource.org/licenses/mit-license.php
 * Информация об авторах и лицензиях стороннего кода в составе Joostina CMS: docs/copyrights
 *
 * */
class actionsAdminModules {

	/**
	 * Название обрабатываемой модели
	 *
	 * @var joosModel модель
	 */
	public static $model = 'adminModules';

	public static function action_before( $actiove_task ) {
		joosDocument::instance()->add_js_file( JPATH_SITE . '/app/components/modules/media/js/modules.js' );
	}

	/**
	 * Список объектов
	 */
	public static function index( $option ) {
		$obj       = new self::$model;

		$obj_count = $obj->count();

		$pagenav   = joosAutoadmin::pagenav( $obj_count , $option );

		$param     = array ( 'offset' => $pagenav->limitstart ,
		                     'limit'  => $pagenav->limit ,
		                     'order'  => 'position, ordering' );
		$obj_list  = joosAutoadmin::get_list( $obj , $param );

		// массив названий элементов для отображения в таблице списка
		$fields_list = array ( 'id' , 'title' , 'position' , 'ordering' , 'module' , 'state' );
		// передаём информацию о объекте и настройки полей в формирование представления
		joosAutoadmin::listing( $obj , $obj_list , $pagenav , $fields_list , 'position' );
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
		$obj_data = new self::$model;
		$id > 0 ? $obj_data->load( $id ) : null;

		//Прицепляем дополнительные параметры конкретного модуля
		$obj_data->params = json_decode( $obj_data->params , true );

		joosAutoadmin::edit( $obj_data , $obj_data );
	}

	/**
	 * Сохранение отредактированного или созданного объекта
	 */
	public static function save( $option , $redirect = 0 ) {

		joosCSRF::check_code();

		$obj_data        = new self::$model;

		$_POST['params'] = json_encode( joosRequest::param( 'params' , '' ) );

		$result          = $obj_data->save( $_POST );


		//Настройка прав
		if ( isset( $_POST['access'] ) ) {

			$access = new Access;

			foreach ( $_POST['access'] as $action => $new_access ) {
				$access->section    = 'Module';
				$access->subsection = $obj_data->id;
				$access->action     = $action;

				$access->find();

				$access->accsess = json_encode( $new_access );
				$access->store();
			}
		}


		//Привязка модуля к страницам сайта
		$pages = new ModulesPages;
		if ( $_POST['pages'] ) {
			$_pages = $_POST['pages'];

			//Удалим старые записи
			$pages->delete_list( array ( 'where' => 'moduleid = ' . $obj_data->id ) );

			foreach ( $_pages as $page ) {

				if ( $page['controller'] ) {
					$pages->moduleid   = $obj_data->id;
					$pages->controller = $page['controller'];
					$pages->method     = $page['method'];
					$pages->rule       = $page['rule'];

					//сохраняем
					$pages->store();
					//сбрасываем
					//$pages->reset();
				}
			}
			//endforeach
		}

		if ( $result == false ) {
			echo 'Ошибочка: ' . joosDatabase::instance()->get_error_msg();
			return false;
		}

		switch ( $redirect ) {
			default:
			case 0: // просто сохранение
				return joosRoute::redirect( 'index2.php?option=' . $option . '&model=' . self::$model , 'Всё ок!' );
				break;

			case 1: // применить
				return joosRoute::redirect( 'index2.php?option=' . $option . '&model=' . self::$model . '&task=edit&id=' . $obj_data->id , 'Всё ок, редактируем дальше' );
				break;

			case 2: // сохранить и добавить новое
				return joosRoute::redirect( 'index2.php?option=' . $option . '&model=' . self::$model . '&task=create' , 'Всё ок, создаём новое' );
				break;
		}
	}

	public static function apply( $option ) {
		return self::save( $option , 1 );
	}

	public static function save_and_new( $option ) {
		return self::save( $option , 2 );
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