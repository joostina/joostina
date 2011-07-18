<?php
/**
 * Login - модуль авторизации
 * Шаблон
 *
 * @version 1.0
 * @package Joostina CMS
 * @subpackage Modules
 * @author JoostinaTeam
 * @copyright (C) 2008-2010 Joostina Team
 * @license see license.txt
 *
 * */
//Запрет прямого доступа
defined('_JOOS_CORE') or die();

$validator = UserValidations::registration();
$register_js_code = <<<EOD
var validator = $('#m-auto_reg').validate({
	rules: {$validator->get_js_validator('rules')},
	messages:  {$validator->get_js_validator('messages')},
	errorElement: "div",
	errorClass: "error",
	errorPlacement: function(error, element) {
		error.append('<em></em>');
		element.parent().parent().append(error);
	},
	submitHandler: function(form) {
		ajax_registration();
	},
	success: function(label) {
		label.html('&nbsp;').addClass('checked');
	}
});
$.validator.addMethod("usernameRegex",function(value,element){
	return this.optional(element) || /^[a-zA-Z0-9._-]{3,16}$/i.test(value);
},"Username are 3-15 characters");
EOD;
joosDocument::instance()->add_js_code($register_js_code);

?>
<div class="m-autorization_login">

    <div class="m-auto_enter">
        <span class="m-auto_login" rel="m-auto_form_login">Вход</span>/
        <span class="m-auto_reg" rel="m-auto_form_reg">Регистрация</span>
    </div>

    <div class="m-auto_formwrap" id="m-auto_form_login">
        <span class="close">x</span>
        <span class="h3">Вход в аакаунт</span>
        <span class="g-italic g-smaller">Нет аккаунта? Давайте <a href="#">зарегистрируемся</a></span>

        <form id="m-auto_login" action="<?php echo joosRoute::href('login') ?>" method="post">
            <dl>
                <dt><label for="username_login">Логин:</label></dt>
                <dd><input type="text" name="username" id="username_login" size="32" maxlength="20"/></dd>
            </dl>
            <dl>
                <dt><label for="password_login">Пароль:</label></dt>
                <dd><input type="password" name="password" id="password_login" size="32" maxlength="32"/></dd>
            </dl>

            <a class="g-italic g-smaller" href="<?php echo joosRoute::href('lostpassword') ?>"
               id="lost_pass"><?php echo __('Забыл пароль?') ?></a>

            <button type="submit" id="submit_login">Войти</button>

            <input type="hidden" name="remember" value="1"/>

            <input type="hidden" name="lang" value="russian"/>
            <input type="hidden" name="return" value="<?php echo JPATH_SITE ?>"/>
            <input type="hidden" name="message" value="aaaa"/>
            <input type="hidden" name="force_session" value="1"/>
            <input type="hidden" name="<?php echo joosCSRF::get_code(1); ?>" value="1"/>
        </form>
    </div>

    <div class="m-auto_formwrap" id="m-auto_form_reg">
        <span class="close">x</span>
        <span class="h3">Регистрация</span>

        <form id="m-auto_reg" action="<?php echo joosRoute::href('register') ?>" method="post">
            <dl>
                <dt><label for="email">Email:</label></dt>
                <dd>
                    <input type="text" name="email" id="email" size="32" maxlength="128"/>
                </dd>
            </dl>
            <dl>
                <dt><label for="username_reg">Логин:</label></dt>
                <dd><input type="text" name="username" id="username_reg" size="32" maxlength="20"/></dd>
            </dl>
            <dl>
                <dt><label for="password_reg">Пароль:</label></dt>
                <dd><input type="password" name="password" id="password_reg" size="32" maxlength="32"/></dd>
            </dl>
            <fieldset class="action">
                <button type="submit" id="submit_reg">Зарегистрироваться</button>
            </fieldset>
        </form>
    </div>
</div>