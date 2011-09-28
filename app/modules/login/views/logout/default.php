<?php
/**
 * Login - модуль авторизации
 * Основной исполняемый файл
 *
 * @version    1.0
 * @package    Joostina CMS
 * @subpackage Modules
 * @author     JoostinaTeam
 * @copyright  (C) 2008-2010 Joostina Team
 * @license    see license.txt
 *
 * */
//Запрет прямого доступа
defined( '_JOOS_CORE' ) or die();
?>
<div class="m-autorization_logout">
	<form action="<?php echo joosRoute::href( 'logout' ) ?>" method="post" id="m-auto_logout">
		<div class="m-auto_logout">
			<div class="m-auto_greeting">
				Привет, <a
				href="<?php echo joosRoute::href( 'user_view' , array ( 'id'       => $user->id ,
				                                                        'username' => $user->username ) ) ?>"><?php echo $user->username; ?></a>
			</div>
			<input type="submit" name="submit" id="logout_button" class="button" value="Выйти"/>
		</div>
		<input type="hidden" name="<?php echo joosCSRF::get_code( 1 ); ?>" value="1"/>
	</form>
</div>