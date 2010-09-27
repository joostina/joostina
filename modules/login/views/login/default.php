<?php
/**
 * @package Joostina
 * @copyright Авторские права (C) 2007-2010 Joostina team. Все права защищены.
 * @license Лицензия http://www.gnu.org/licenses/gpl-2.0.htm GNU/GPL, или help/license.php
 * Joostina! - свободное программное обеспечение распространяемое по условиям лицензии GNU/GPL
 * Для получения информации о используемых расширениях и замечаний об авторском праве, смотрите файл help/copyright.php.
 */

// запрет прямого доступа
defined( '_JOOS_CORE' ) or die();

?>
<div class="mod_ml_login login vertical">
    <form action="<?php echo Jroute::href('login')?>" method="post" name="login">
		<div class="login_form">
			<input type="text" name="username" id="mod_login_USER" class="inputbox" alt="username" value="" />
			<input type="password" id="mod_login_password" name="passwd" class="inputbox" alt="password" value="" />
			<input type="checkbox" name="remember" id="mod_login_remember"  value="yes" alt="Remember Me" />
			<label for="mod_login_remember"><?php echo _REMEMBER_ME?></label>
			<input type="submit" name="Submit" class="button" id="login_button" value="<?php echo _BUTTON_LOGIN?>" />
			<a href="<?php echo Jroute::href('lostpassword') ?>"><?php echo _LOST_PASSWORDWORD?></a>
			<a href="<?php echo Jroute::href('register')?>"><?php echo _CREATE_ACCOUNT?></a>
		</div>
		<input type="hidden" name="option" value="login" />
		<input type="hidden" name="op2" value="login" />
		<input type="hidden" name="lang" value="russian" />
		<input type="hidden" name="return" value="<?php echo JPATH_SITE ?>" />
		<input type="hidden" name="message" value="aaaa" />
		<input type="hidden" name="force_session" value="1" />
		<input type="hidden" name="<?php echo josSpoofValue(1); ?>" value="1" />
	</form>
</div>