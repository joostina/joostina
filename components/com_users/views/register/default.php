<?php
/**
 * @package Joostina
 * @copyright Авторские права (C) 2007-2010 Joostina team. Все права защищены.
 * @license Лицензия http://www.gnu.org/licenses/gpl-2.0.htm GNU/GPL, или help/license.php
 * Joostina! - свободное программное обеспечение распространяемое по условиям лицензии GNU/GPL
 * Для получения информации о используемых расширениях и замечаний об авторском праве, смотрите файл help/copyright.php.
 */
// запрет прямого доступа
defined('_VALID_MOS') or die();

JHTML::loadJqueryPlugins('jquery.validate');
?>

<script language="javascript">
$(document).ready(function() {
        var validator = $('#reg_form').validate({
                rules: <?php echo $validator->get_js_validator('rules');?>,
                messages: <?php echo $validator->get_js_validator('messages');?>,
                errorPlacement: function(error, element) {
                    error.appendTo(element.parent());
                },
                success: function(label) {
                    label.html('&nbsp;').addClass('checked');
                }
        });
});
</script>
                
<div class="page page_registration">

    <h5>Регистрация</h5>
    
    <div class="menu_inside_submenu">
        <ul class="menu_inside_submenu_ul active_ul by_statuses">
            <li class="menu_inside_submenu_active"><span>
                <a title="Регистрация" href="<?php echo sefRelToAbs('index.php?option=com_users&task=register',true) ?>">Регистрация</a>
            </span></li>
            <li><span>
                <a title="Восстановление пароля" href="<?php echo sefRelToAbs('index.php?option=com_users&task=lostpassword',true) ?>">Восстановление пароля</a>
                </span></li>
        </ul>       
    </div> 
    
    <form action="<?php echo sefRelToAbs('index.php?option=com_users&task=register',true) ?>" method="post" id="reg_form">
       
        <div class="errors"><?php echo $user->getError(); ?></div>
        
        <dl class="form_registration">
            <dt><label for="username">Имя пользователя:</label></dt>
            <dd><input type="text" name="username" id="username" size="40" value="<?php echo $user->username ?>" class="inputbox" maxlength="10" /></dd>
    
            <dt><label for="email"><?php echo _REGISTER_EMAIL; ?></label></dt>
            <dd><input type="text" name="email" id="email" size="40" value="<?php echo $user->email ?>" class="inputbox" maxlength="20" /></dd>
            
            <dt><label for="password"><?php echo _REGISTER_PASSWORD; ?></label></dt>
            <dd>
                <input class="inputbox" type="password"  name="password"  id="password" size="20" maxlength="15" value="" />
                <!--<a href="javascript:void(0)" class="show_hide_pass">Показать</a>--> 
            </dd>
            
<!--<dt><label for="password2_f"><?php echo _REGISTER_VPASS; ?></label></dt>
<dd><input class="inputbox" type="password" name="password2" id="password2_f" size="40" value="" /></dd>-->  
        </dl>
        
        <span class="button"><input type="submit" value="<?php echo _BUTTON_SEND_REG; ?>" class="button" /></span>
        <input type="hidden" name="<?php echo josSpoofValue() ?>" value="1" />
      
    </form>

</div>