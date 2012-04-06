<?php

// запрет прямого доступа
defined( '_JOOS_CORE' ) or die();

/**
 * Компонент управления категориями
 * Контроллер панели управления
 *
 * @version    1.0
 * @package    Controllers
 * @subpackage Categories
 * @author     Joostina Team <info@joostina.ru>
 * @copyright  (C) 2007-2012 Joostina Team
 * @license    MIT License http://www.opensource.org/licenses/mit-license.php
 * Информация об авторах и лицензиях стороннего кода в составе Joostina CMS: docs/copyrights
 *
 * */
class actionsAdminCategories  extends joosAdminController{

    protected static $active_menu = 'categories';

    public static $submenu = array(
        'categories' => array(
            'name' => 'Все страницы',
            'model'=>'modelCategories',
            'href' => 'index2.php?option=pages',
            'active' => false
        ),
    );

    public static function action_before() {

        parent::action_before();
        
		joosDocument::instance()
            ->add_css( JPATH_SITE . '/app/components/categories/media/css/categories.admin.css' )
            ->add_js_file( JPATH_SITE . '/app/components/categories/media/js/categories.admin.js' );

	}

	/**
	 * Редактирование объекта
	 *
	 * @param integer $id - номер редактируемого объекта
	 */
	public static function edit( $option , $id ) {

		$obj_data = new self::$model;
		$id > 0 ? $obj_data->load( $id ) : null;

		$cat_details = new CategoriesDetails;
		$cat_details->load( $id );

		foreach ( get_object_vars( $cat_details ) as $key => $val ) {
			$obj_data->$key = $val;
		}

		$obj_data->id     = $id;
		$obj_data->cat_id = $id;

		//Параметры
		$obj_data->params_group = $obj_data->group;
		$obj_data->params       = joosParams::get_params( $obj_data->group , 'category' , $obj_data->id );

		joosAutoadmin::edit( $obj_data , $obj_data );
	}

	/**
	 * Сохранение информации
	 *
	 * @param string  $option
	 * @param integer $redirect
	 */
	public static function save_this( $option , $redirect = 0 ) {

		joosCSRF::check_code();

		$obj = new self::$model;
		$obj->save( $_POST );


		//Сохранение дополинтельной информации о категории
		$cat_details = new CategoriesDetails;

		//существующая категория
		if ( joosRequest::post( 'id' , 0 ) ) {
			$_POST['cat_id'] = $obj->id;
			$cat_details->load( $obj->id );
			$cat_details->save( $_POST );
		} //новая категория
		else {
			$cat_details->bind( $_POST );
			$cat_details->cat_id = $obj->id;
			$cat_details->store( false , true );
		}

		//Сохранение параметров
		if ( isset( $_POST['params'] ) ) {
			$params = new joosParams;
			$params->save_params( $_POST['params'] , $obj->group , 'category' , $obj->id );
		}

		switch ( $redirect ) {
			default:
			case 0: // просто сохранение

				joosRoute::redirect( 'index2.php?option=' . $option . '&model=' . self::$model . $obj->get_link_suff() , 'Всё ок!' );
				break;

			case 1: // применить
				joosRoute::redirect( 'index2.php?option=' . $option . '&model=' . self::$model . '&task=edit&id=' . $obj->id . $obj->get_link_suff() , 'Всё ок, редактируем дальше' );
				break;

			case 2: // сохранить и добавить новое
				joosRoute::redirect( 'index2.php?option=' . $option . '&model=' . self::$model . '&task=create' . $obj->get_link_suff() , 'Всё ок, создаём новое' );
				break;
		}
	}

	public static function root_add() {

		$cats     = new modelCategories;
		$action   = $cats->insert_root_node( joosRequest::post( 'name' ) );

		$redirect = 'index2.php?option=categories' . $cats->get_link_suff();
		$action === true ? joosRoute::redirect( $redirect , 'Корень успешно создан!' ) : joosRoute::redirect( $redirect , implode( ' ' , $cats->get_error() ) );
	}

	public static function node_add() {

		$cats     = new modelCategories;
		$action   = $cats->insertChildNode( joosRequest::post( 'name' ) , joosRequest::post( 'id' ) );

		$redirect = 'index2.php?option=categories' . $cats->get_link_suff();
		$action === true ? joosRoute::redirect( $redirect , 'Категория успешно добавлена' ) : joosRoute::redirect( $redirect , implode( ' ' , $cats->get_error() ) );
	}

	public static function node_del() {

		$cats = new modelCategories;

		if ( joosRequest::request( 'del_childs' , 'no' ) == 'yes' ) {
			$action = $cats->delete_branch( joosRequest::request( 'id' ) );
		} else {
			$action = $cats->delete_node( joosRequest::request( 'id' ) );
		}

		$redirect = 'index2.php?option=categories' . $cats->get_link_suff();
		$action === true ? joosRoute::redirect( $redirect , 'Категория удалена' ) : joosRoute::redirect( $redirect , implode( ' ' , $cats->get_error() ) );
	}

	public static function node_move_up() {

		$cats     = new modelCategories;

		$action   = $cats->move_up( joosRequest::get( 'id' ) );


		$redirect = 'index2.php?option=categories' . $cats->get_link_suff();
		$action === true ? joosRoute::redirect( $redirect , 'Категория перемещена' ) : joosRoute::redirect( $redirect , implode( ' ' , $cats->get_error() ) );
	}

	public static function node_move_down() {

		$cats     = new modelCategories;

		$action   = $cats->move_down( joosRequest::get( 'id' ) );

		$redirect = 'index2.php?option=categories' . $cats->get_link_suff();
		$action === true ? joosRoute::redirect( $redirect , 'Категория перемещена' ) : joosRoute::redirect( $redirect , implode( ' ' , $cats->get_error() ) );
	}

	public static function node_move_lft() {

		$cats     = new modelCategories;

		$action   = $cats->move_lft( joosRequest::get( 'id' ) );


		$redirect = 'index2.php?option=categories' . $cats->get_link_suff();
		$action === true ? joosRoute::redirect( $redirect , 'Категория перемещена' ) : joosRoute::redirect( $redirect , implode( ' ' , $cats->get_error() ) );
	}

	public static function node_move_rgt() {

		$cats     = new modelCategories;

		$action   = $cats->move_rgt( joosRequest::get( 'id' ) );

		$redirect = 'index2.php?option=categories' . $cats->get_link_suff();
		$action === true ? joosRoute::redirect( $redirect , 'Категория перемещена' ) : joosRoute::redirect( $redirect , implode( ' ' , $cats->get_error() ) );
	}

}