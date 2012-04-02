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
            <a class="brand" href="index2.php">Joostina CMS</a>

            <div class="nav-collapse"><?php joosModuleAdmin::load_by_name('admin_menu'); ?></div>

            <ul class="nav pull-right">
                <li class="dropdown">
                    <a data-toggle="dropdown" class="dropdown-toggle" href="#">Выйти <?php echo sprintf(__('@%s'), joosCore::user()->user_name) ?> <b class="caret"></b></a>
                    <ul class="dropdown-menu">
                        <li><a href="#">Редактировать профиль</a></li>
                        <li class="divider"></li>
                        <li><a href="index2.php?option=logout">Выйти</a></li>
                    </ul>
                </li>
                <li>
                </li>
            </ul>

        </div>
    </div>
</div>

<div class="container">

    <?php joosModuleAdmin::load_by_name('flashmessage'); ?>
    
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
</body>
</html>