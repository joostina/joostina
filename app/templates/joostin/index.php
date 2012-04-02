<?php
/**
 * @JoostIN
 * @package Joostina
 */
// запрет прямого доступа
defined('_JOOS_CORE') or die();

echo '<?xml version="1.0" encoding="UTF-8"?>' . "\n";

$cur_file_icons_path = joosConfig::get('admin_icons_path');

joosDocument::instance()->add_css(JPATH_SITE . '/app/templates/' . JTEMPLATE_ADMIN . '/media/css/template.css')->add_css(JPATH_SITE . '/app/templates/' . JTEMPLATE_ADMIN . '/media/css/dropdown.css')//->addCSS(JPATH_SITE . '/media/js/jquery.ui/themes/ui-lightness/jquery-ui.css')
		->add_js_file(JPATH_SITE . '/media/js/jquery.js', array('first' => true)) // jquery всегда первое!
		->add_js_file(JPATH_SITE . '/media/js/jquery.ui/jquery-ui-1.8.7.custom.min.js')->add_js_file(JPATH_SITE . '/media/js/jquery.plugins/jquery.hotkeys.js')->add_js_file(JPATH_SITE . '/media/js/jquery.plugins/jquery.tiptip.js')->add_js_file(JPATH_SITE . '/media/js/jquery.plugins/jquery.jeditable.js')->add_js_file(JPATH_SITE . '/media/js/jquery.plugins/jquery.tablednd.js')->add_js_file(JPATH_SITE . '/media/js/administrator.js');

// для панели управления расширенные тэги индексации не нужны
joosDocument::$config['seotag'] = FALSE;
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

	<head>
		<meta http-equiv="Content-Type" content="text/html;  charset=UTF-8"/>
        <?php echo joosDocument::head(); ?>
		<?php echo joosDocument::stylesheet(); ?>
	</head>

	<body>

		<div id="sidebar">
            <?php joosModuleAdmin::load_by_name('adminquickicons'); ?>
		</div>

		<div id="wrapper">

			<div id="header">
				<a href="index2.php?option=logout"
				   class="logout_link"><?PHP echo sprintf(__('Выйти %s'), joosCore::user()->user_name) ?></a>

				<div id="admin_menu"><?php joosModuleAdmin::load_by_name('adminmenu'); ?></div>
			</div>
			<!-- #header-->
			<div id="content">
				<div id="component"><?php echo joosDocument::get_body(); ?></div>
			</div>
			<!-- #content-->
		</div>
		<!-- #wrapper -->

		<div id="footer">
			<div class="copyrights">
				<p><a href="http://www.joostina.ru" target="_blank">Joostina CMS</a> &copy; 2007-2012</p>

				<p>ver. 2.0 a2 (<?php echo joosVersion::$BUILD ?>)</p>
			</div>
			<div class="pathsite"><a target="_blank" href="<?php echo JPATH_SITE ?>"><?php echo JPATH_SITE ?></a></div>
		</div>
		<!-- #footer -->

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