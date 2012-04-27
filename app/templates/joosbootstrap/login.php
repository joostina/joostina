<?php
/**
 * @JoostFREE
 * @package   Joostina
 * @copyright Авторские права (C) 2007-2010 Joostina team. Все права защищены.
 * @license   Лицензия http://www.gnu.org/licenses/gpl-2.0.htm GNU/GPL, или help/license.php
 * Joostina! - свободное программное обеспечение распространяемое по условиям лицензии GNU/GPL
 * Для получения информации о используемых расширениях и замечаний об авторском праве, смотрите файл help/copyright.php.
 */
// запрет прямого доступа
defined("_JOOS_CORE") or die();

joosDocument::instance()
    ->add_css(JPATH_SITE . '/app/templates/' . JTEMPLATE_ADMIN . '/media/css/app.css?ver=1')
    ->add_js_file(JPATH_SITE . "/media/js/jquery.js", array("first" => true)) // jquery всегда первое!
    ->add_js_file(JPATH_SITE . "/media/js/administrator.login.js");

?><!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title><?php echo "Панель управления Joostina CMS" ?></title>
    <meta http-equiv="Content-Type" content="text/html;  charset=UTF-8"/>
    <link rel="shortcut icon" href="<?php echo JPATH_SITE; ?>/media/favicon.ico"/>
    <?php echo joosDocument::stylesheet(); ?>
</head>
<body>
    <div class="container">
        <div class="row">
            <div class="span16">
                <?php joosModuleAdmin::load_by_name('flash_message') ?>
                <h6>Joostina CMS v2.x</h6>
                <form action="<?php echo JPATH_SITE_ADMIN ?>/index.php" method="post" name="login_form" id="login_form" class="well">
                    <input name="user_name" id="user_name" type="text" class="input-medium" placeholder="Логин">
                    <input name="password" type="password" type="password" class="input-medium" placeholder="Пароль">
                    <button type="submit" class="btn btn-primary">Войти</button>
                    <br />
                    <a href="#">Восстановить пароль</a> | <a href="<?php echo JPATH_SITE ?>">Перейти на сайт</a>
                    <input type="hidden" value="1" name="<?php echo joosCSRF::get_code('admin_login') ?>"/>
                </form>

            </div>
        </div>
    </div>
<?php echo joosDocument::javascript(); ?>
</body>
</html>