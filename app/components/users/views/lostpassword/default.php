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
?>
<div class="page page_lostpassword">
    <h5>Восстановление пароля</h5>

    <div class="menu_inside_submenu">
        <ul class="menu_inside_submenu_ul active_ul by_statuses">
            <li>
				<span>
					<a title="Регистрация" href="<?php echo joosRoute::href('register') ?>">Регистрация</a>
				</span>
            </li>
            <li class="menu_inside_submenu_active">
				<span>
					<a title="Восстановление пароля" href="<?php echo joosRoute::href('lostpassword') ?>">Восстановление
                        пароля</a>
                </span>
            </li>
        </ul>
    </div>

    <form action="<?php echo sefRelToAbs('index.php?option=com_users&task=lostpassword', true) ?>" method="post">
        <dl class="form_lostpassword">
            <dt><label for="username_f">Введите имя пользователя</label></dt>
            <dd><input type="text" name="username" id="username_f" size="40" value="" class="inputbox" maxlength="20"/>
            </dd>
            <dt><label for="email_f">или <?php echo _REGISTER_EMAIL; ?></label></dt>
            <dd><input type="text" name="email" id="email_f" size="40" value="" class="inputbox" maxlength="20"/></dd>
        </dl>

        <span class="button"><input type="submit" value="Восстановить пароль" class="button"/></span>
        <input type="hidden" name="<?php echo joosCSRF::get_code() ?>" value="1"/>
    </form>
</div>