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

load_jquery_plugins('jquery.validate');

//_xdump($validator->get_js_validator('rules'));
//die();

$register_js_code = <<<EOD
<script language="javascript">
	$(document).ready(function() {
        var validator = $('#reg_form').validate({
			rules: {$validator->get_js_validator('rules')},
			messages: {$validator->get_js_validator('messages')},
			errorPlacement: function(error, element) {
				error.appendTo(element.parent());
			},
			success: function(label) {
				label.html('&nbsp;').addClass('checked');
			}
        });
	});
</script>
EOD;

joosDocument::$data['footer'][] = $register_js_code;

?>
<div class="page page_registration">
    <h5>Регистрация</h5>

    <div class="menu_inside_submenu">
        <ul class="menu_inside_submenu_ul active_ul by_statuses">
            <li class="menu_inside_submenu_active">
				<span>
					<a title="Регистрация" href="<?php echo joosRoute::href('register') ?>">Регистрация</a>
				</span>
            </li>
            <li>
				<span>
					<a title="Восстановление пароля" href="<?php echo joosRoute::href('lostpassword') ?>">Восстановление
                        пароля</a>
                </span>
            </li>
        </ul>
    </div>

    <form action="<?php echo joosRoute::href('register') ?>" method="post" id="reg_form">

        <div class="errors"><?php echo $user->get_error(); ?></div>
        <dl class="form_registration">
            <dt><label for="username">Имя пользователя:</label></dt>
            <dd><input type="text" name="username" id="username" size="40" value="<?php echo $user->username ?>"
                       class="inputbox" maxlength="10"/></dd>

            <dt><label for="email"><?php echo _REGISTER_EMAIL; ?></label></dt>
            <dd><input type="text" name="email" id="email" size="40" value="<?php echo $user->email ?>" class="inputbox"
                       maxlength="20"/></dd>

            <dt><label for="password"><?php echo _REGISTER_PASSWORD; ?></label></dt>
            <dd>
                <input class="inputbox" type="password" name="password" id="password" size="20" maxlength="15"
                       value=""/>
                <!--<a href="javascript:void(0)" class="show_hide_pass">Показать</a>-->
            </dd>

            <!--<dt><label for="password2_f"><?php echo _REGISTER_VPASS; ?></label></dt>
<dd><input class="inputbox" type="password" name="password2" id="password2_f" size="40" value="" /></dd>-->
        </dl>
        <input type="submit" value="<?php echo _BUTTON_SEND_REG; ?>" class="button"/>
        <input type="hidden" name="<?php echo joosSpoof::get_code() ?>" value="1"/>
    </form>
</div>