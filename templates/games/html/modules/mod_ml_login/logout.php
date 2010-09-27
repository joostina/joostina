<?php
/**
* @package Joostina
* @copyright Авторские права (C) 2008-2009 Joostina team. Все права защищены.
* @license Лицензия http://www.gnu.org/licenses/gpl-2.0.htm GNU/GPL, или help/license.php
* Joostina! - свободное программное обеспечение распространяемое по условиям лицензии GNU/GPL
* Для получения информации о используемых расширениях и замечаний об авторском праве, смотрите файл help/copyright.php.
*/

// запрет прямого доступа
defined( '_JOOS_CORE' ) or die();
$module->helper->prepare_logout_form($params); ?>





	<form action="<?php echo $params->_action; ?>" method="post" name="logout">
    
        <div class="logout">
            
            <input type="submit" name="Submit" id="logout_button" class="button" value="<?php echo _BUTTON_LOGOUT; ?>" />
            <span>Привет,</span>  <?php echo $params->_user_name; ?>
            
            <div>
                <a class="avatar" href="#"><img class="avatar" src="<?php echo JPATH_SITE;?>/<?php echo User::get_avatar($my);?>" alt="<?php echo $params->_raw_user_name;?>" /></a>
                <a class="mail" href="/.">1 личное сообщение</a>
                <br />
                <a class="fav" href="/.">Избранное</a> (14)       
            </div>
        </div>

		<input type="hidden" name="option" value="logout" />
		<input type="hidden" name="op2" value="logout" />
		<input type="hidden" name="lang" value="<?php echo $mainframe->getCfg('lang'); ?>" />
		<input type="hidden" name="return" value="<?php echo sefRelToAbs($params->get('logout',$params->_returnUrl)); ?>" />
		<input type="hidden" name="message" value="<?php echo $params->get('logout_message',''); ?>" />
	</form>