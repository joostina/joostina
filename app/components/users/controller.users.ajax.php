<?php defined('_JOOS_CORE') or exit();

/**
 * Компонент управления пользователями
 * Контроллер сайта ajax
 *
 * @version    1.0
 * @package    Components\Users
 * @subpackage Controllers\Site
 * @author     Joostina Team <info@joostina.ru>
 * @copyright  (C) 2007-2012 Joostina Team
 * @license    MIT License http://www.opensource.org/licenses/mit-license.php
 * Информация об авторах и лицензиях стороннего кода в составе Joostina CMS: docs/copyrights
 *
 * */
class actionsAjaxUsers extends joosControllerAjax {

	public static function register() {

		$validator = UserValidations::registration();

		$user      = new modelUsers;
		$user->bind( $_POST );

		if ( !$user->check( $validator ) ) {
			$error_hash = $validator->GetErrors();
			$errors     = '';
			foreach ( $error_hash as $inp_err ) {
				$errors .= '' . $inp_err . ' |  ';
			}
			echo json_encode( array ( 'error' => 'Ошибки: ' . $errors ) );
			return false;
		}

		if ( $user->save( $_POST ) ) {
			$password = joosRequest::post( 'password' );
			$response = json_decode( modelUsers::login( $user->user_name , $password , array ( 'return' => 1 ) ) , true );
			if ( isset( $response['error'] ) ) {
				echo json_encode( array ( 'error' => $response['error'] ) );
				return false;
			} else {
				echo json_encode( array ( 'success' => 'всё пучком' ) );
				return true;
			}
		} else {
			//userHtml::register($user, $validator);
			echo json_encode( array ( 'error' => 'Что-то не так с данными для регистрации' ) );
			return false;
		}
	}

}
