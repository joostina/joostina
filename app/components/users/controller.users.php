<?php

// запрет прямого доступа
defined( '_JOOS_CORE' ) or die();

/**
 * modelUsers - Компонент управления пользователями
 * Контроллер сайта
 *
 * @version    1.0
 * @package    Joostina.Components.Controllers
 * @subpackage modelUsers
 * @author     Joostina Team <info@joostina.ru>
 * @copyright  (C) 2007-2011 Joostina Team
 * @license    MIT License http://www.opensource.org/licenses/mit-license.php
 * Информация об авторах и лицензиях стороннего кода в составе Joostina CMS: docs/copyrights
 *
 * */
class actionsUsers extends joosController {

	public static function action_before() {
		joosBreadcrumbs::instance()->add( 'Пользователи' );
	}

	//Список пользователей сайта
	public static function index() {

		$page        = isset( self::$param['page'] ) ? self::$param['page'] : 0;

		$users       = new modelUsers;
		$users_count = $users->count( 'WHERE state=1' );

		joosDocument::instance()->set_page_title( 'Список пользователей' )->add_meta_tag( 'description' , 'Список пользователей сайта' );


		return array ( 'users_count' => $users_count ,
		               'page'        => $page );
	}

	//профиль пользователя
	public static function view() {

		$username = self::$param['username'];

		$user     = new modelUsers;
		$user->load_by_field( 'username' , $username );

		$user->id ? null : joosRoute::redirect( JPATH_SITE , 'Пользователь не найден' );

		joosDocument::instance()->set_page_title( $user->username );

		joosBreadcrumbs::instance()->add( $user->username );

		return array ( 'user' => $user , );
	}

	//редактирование
	public static function edituser() {

		$username = self::$param['username'];

		$user     = new modelUsers;
		$user->load_by_field( 'username' , $username );

		if ( joosCore::user()->id != $user->id ) {
			joosRoute::redirect( JPATH_SITE , 'Ай, ай!' );
		}

		$validator = UserValidations::edit();

		if ( $_POST ) {

			$user = new modelUsers;
			$user->load( $_POST['id'] );
			$user->id ? null : joosRoute::redirect( JPATH_SITE , 'Пользователь не найден' );

			//смена пароля
			$old_password = joosRequest::post( 'password_old' );
			$new_password = joosRequest::post( 'password_new' );

			if ( $old_password && $new_password ) {
				if ( modelUsers::check_password( $old_password , $user->password ) ) {
					$_POST['password'] = modelUsers::prepare_password( $new_password );
				} else {
					joosRoute::redirect( joosRoute::href( 'user_view' , array ( 'id'       => $user->id ,
					                                                            'username' => $user->username ) ) , 'Неправильно введён пароль' );
				}
			}

			$user->save( $_POST );

			joosRoute::redirect( joosRoute::href( 'user_view' , array ( 'id'       => $user->id ,
			                                                            'username' => $user->username ) ) , 'Данные успешно сохранены' );

			return array ( 'user'      => $user ,
			               'validator' => $validator
			);
		} else {
			$user->id ? null : joosRoute::redirect( JPATH_SITE , 'Пользователь не найден' );

			joosDocument::instance()->set_page_title( $username );

			joosBreadcrumbs::instance()->add( $username );

			joosHtml::make_safe( $user );

			return array ( 'user'      => $user ,
			               'validator' => $validator
			);
		}
	}

	/**
	 * Авторизация пользователя
	 */
	public static function login() {

		joosCSRF::check_code(null, 1);

		$username = joosRequest::post( 'username' );
		$password = joosRequest::post( 'password' );

		modelUsers::login( $username , $password );
	}

	/**
	 * Разлогинивание епользователя
	 */
	public static function logout() {

		joosCSRF::check_code( 1 );

		modelUsers::logout();

		$return = joosRequest::param( 'return' );
		if ( $return && !( strpos( $return , 'registration' ) || strpos( $return , 'login' ) ) ) {
			joosRoute::redirect( $return );
		} elseif ( isset( $_SERVER['HTTP_REFERER'] ) ) {
			joosRoute::redirect( $_SERVER['HTTP_REFERER'] );
		} else {
			joosRoute::redirect( JPATH_SITE );
		}
	}

	public static function register() {

		joosDocument::instance()->set_page_title( 'Регистрация' );

		joosDocument::$config['seotag'] = false;

		$validator                      = UserValidations::registration();

		if ( $_POST ) {
			self::save_register( $validator );
		} else {
			return array ( 'user'      => new modelUsers ,
			               'validator' => $validator
			);
		}
	}

	public static function check() {

		$param = explode( '?' , $_SERVER['REQUEST_URI'] );
		parse_str( $param[1] , $datas );

		if ( isset( $datas['username'] ) && joosString::trim( $datas['username'] ) != '' ) {
			$user           = new modelUsers;
			$user->username = $datas['username'];
			$ret            = $user->find() ? 0 : 1;

			$ret            = preg_match( _USERNAME_REGEX , $datas['username'] ) ? $ret : false;


			echo $ret ? 'true' : 'false';
			exit();
		}

		if ( isset( $datas['email'] ) && joosString::trim( $datas['email'] ) != '' ) {
			$user        = new modelUsers;
			$user->email = $datas['email'];
			echo $user->find() ? 'false' : 'true';
			exit();
		}
	}

	private static function save_register( $validator ) {

		$user = new modelUsers;
		$user->bind( $_POST );

		if ( $user->check( $validator ) && $user->save( $_POST ) ) {
			modelUsers::login( $user->username , $_POST['password'] );
		} else {
			joosRoute::redirect( JPATH_SITE );
			//userHtml::register($user, $validator);
		}
	}

	/**
	 * Форма восстановления пароля
	 */
	public static function lostpassword() {
		$_POST ? self::send_new_pass() : self::lostpassword();
	}

	/**
	 * Процесс восстановления пароля
	 */
	// TODO обновить до актуального
	public static function send_new_pass() {

	}

}