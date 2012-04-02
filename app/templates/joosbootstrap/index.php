<?php
/**
 * @JoostIN
 * @package Joostina
 */
// запрет прямого доступа
defined('_JOOS_CORE') or die();

echo '<?xml version="1.0" encoding="UTF-8"?>' . "\n";

$cur_file_icons_path = joosConfig::get('admin_icons_path');

joosDocument::instance()
    ->add_css(JPATH_SITE . '/app/templates/' . JTEMPLATE_ADMIN . '/media/css/app.css?ver=1')
    ->add_js_file(JPATH_SITE . '/media/js/jquery.js', array('first' => true)) // jquery всегда первое!
    ->add_js_file(JPATH_SITE . '/app/templates/' . JTEMPLATE_ADMIN . '/media/js/bootstrap.min.js')
    //->add_js_file(JPATH_SITE . '/media/js/jquery.ui/jquery-ui-1.8.7.custom.min.js')
    ->add_js_file(JPATH_SITE . '/media/js/jquery.plugins/jquery.hotkeys.js')
    ->add_js_file(JPATH_SITE . '/media/js/jquery.plugins/jquery.tiptip.js')
    ->add_js_file(JPATH_SITE . '/media/js/jquery.plugins/jquery.jeditable.js')
    ->add_js_file(JPATH_SITE . '/media/js/jquery.plugins/jquery.tablednd.js')
    ->add_js_file(JPATH_SITE . '/media/js/administrator.js');

// для панели управления расширенные тэги индексации не нужны
joosDocument::$config['seotag'] = FALSE;
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <?php echo joosDocument::head(); ?>
    <?php echo joosDocument::stylesheet(); ?>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <!-- Le HTML5 shim, for IE6-8 support of HTML5 elements -->
    <!--[if lt IE 9]>
    <script src="//html5shim.googlecode.com/svn/trunk/html5.js"></script>
    <![endif]-->

</head>
<body>

<div class="navbar navbar-fixed-top">
    <div class="navbar-inner">
        <div class="container">
            <a class="brand" href="#">Project name</a>

            <div class="nav-collapse">
                <ul class="nav">

                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown">Контент<b class="caret"></b></a>
                        <ul class="dropdown-menu">
                            <li class="nav-header">Независимые страницы</li>
                            <li><a href="#">Все страницы</a></li>
                            <li><a href="#">Добавить страницу</a></li>

                            <li class="divider"></li>

                            <li class="nav-header">Структурированный контент</li>
                            <li><a href="#">Все материалы</a></li>
                            <li><a href="#">Добавить материал</a></li>

                            <li class="divider"></li>

                            <li class="nav-header">Категории</li>
                            <li><a href="#">Все категории</a></li>
                            <li><a href="#">Добавить категорию</a></li>
                        </ul>
                    </li>

                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown">Пользователи<b class="caret"></b></a>
                        <ul class="dropdown-menu">
                            <li><a href="components-users-all_users.html">Все пользователи</a></li>
                            <li><a href="#">Добавить пользователя</a></li>
                            <li class="divider"></li>
                            <li><a href="#">Группы</a></li>
                            <li><a href="#">Права доступа</a></li>
                        </ul>
                    </li>

                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown">Компоненты<b class="caret"></b></a>
                        <ul class="dropdown-menu">
                            <li><a href="#">Новости</a></li>
                            <li><a href="#">Блоги</a></li>
                            <li><a href="#">Контакты</a></li>
                        </ul>
                    </li>

                    <li><a href="#about">Модули</a></li>


                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown">Инструменты<b class="caret"></b></a>
                        <ul class="dropdown-menu">
                            <li><a href="#">Кодер</a></li>
                            <li><a href="#">Медиа-менеджер</a></li>
                        </ul>
                    </li>

                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown">Информация<b class="caret"></b></a>
                        <ul class="dropdown-menu">
                            <li><a href="#">Информация о системе</a></li>
                            <li><a href="#">Joostina API</a></li>
                            <li><a href="#">Joostina GitHub</a></li>
                        </ul>
                    </li>
                </ul>
            </div>

            <ul class="nav pull-right">
                <li class="dropdown">
                    <a data-toggle="dropdown" class="dropdown-toggle" href="#">NickName <b class="caret"></b></a>
                    <ul class="dropdown-menu">
                        <li><a data-toggle="modal" href="#modal-login_form">Войти</a></li>
                        <li><a href="#">Забыли пароль?</a></li>
                        <li class="divider"></li>
                        <li><a href="#">Регистрация</a></li>
                    </ul>
                </li>
            </ul>

        </div>
    </div>
</div>

<div class="container">

    <h1>Dashboard</h1>
    <p>Здесь какие-то сводные данные</p>

    <div id="component"><?php echo joosDocument::get_body(); ?></div>

</div>
<script type="text/javascript">
    var _live_site = '<?php echo JPATH_SITE; ?>';
    var _option = '<?php echo joosRequest::param('option'); ?>';
    var image_path = '<?php echo $cur_file_icons_path ?>';
    var _cur_template = '<?php echo JTEMPLATE_ADMIN; ?>';
</script>
<?php echo joosDocument::javascript(); ?>
<?php echo joosDocument::footer_data(); ?>
    <script type="text/javascript" src="js/lib/bootstrap.min.js"></script>

</body>
</html>