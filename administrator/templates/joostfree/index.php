<?php
/**
 * @JoostFREE
 * @package Joostina
 * @copyright Авторские права (C) 2007-2010 Joostina team. Все права защищены.
 * @license Лицензия http://www.gnu.org/licenses/gpl-2.0.htm GNU/GPL, или help/license.php
 * Joostina! - свободное программное обеспечение распространяемое по условиям лицензии GNU/GPL
 * Для получения информации о используемых расширениях и замечаний об авторском праве, смотрите файл help/copyright.php.
 */
// запрет прямого доступа
defined('_VALID_MOS') or die();

echo '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
$cur_file_icons_path = JPATH_SITE . '/' . JADMIN_BASE . '/templates/' . JTEMPLATE . '/images/ico';

$option = mosGetParam($_REQUEST, 'option', '');

mosCommonHTML::loadJquery();

Jdocument::getInstance()
		->addCSS(JPATH_SITE . '/' . JADMIN_BASE . '/templates/joostfree/css/template_css.css')
		->addJS(JPATH_SITE . '/media/js/admin.menu.js')
		->addJS(JPATH_SITE . '/media/js/admin.menu/theme.js')
		->addJS(JPATH_SITE . '/' . JADMIN_BASE . '/includes/js/admin.js');
?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <title><?php echo $mosConfig_sitename; ?> - <?php echo _JOOSTINA_CONTROL_PANEL ?></title>
        <meta http-equiv="Content-Type" content="text/html;  charset=UTF-8" />
		<?php echo Jdocument::head(); ?>
		<?php echo Jdocument::stylesheet(); ?>
		<?php echo Jdocument::javascript(); ?>
		<link rel="shortcut icon" href="<?php echo JPATH_SITE; ?>/images/favicon.ico" />
	</head>
    <body>
        <div class="page">
            <div id="topper">
                <div class="logo">
                    <a href="index2.php" title="<?php echo _GO_TO_MAIN_ADMIN_PAGE ?>"><img border="0" alt="J!" src="templates/joostfree/images/logo_130.png" /></a>
                </div>
                <div id="joo">
                    <a href="index2.php" title="<?php echo _GO_TO_MAIN_ADMIN_PAGE ?>"><?php echo $mosConfig_sitename; ?></a>
                </div>
                <div id="ajax_status"><?php echo _PLEASE_WAIT ?></div>
                <table width="100%" class="menubar" cellpadding="0" cellspacing="0" border="0">
                    <tr class="menubackgr">
                        <td width="85%"><?php mosLoadAdminModule('fullmenu'); ?></td>
                        <td width="5%" align="right" class="header_info"><?php mosLoadAdminModules('header', -2); ?></td>
                        <td width="35" align="center">
                            <input type="image" name="jtoggle_editor" id="jtoggle_editor" title="<?php echo _TOGGLE_WYSIWYG_EDITOR ?>" onclick="jtoggle_editor();" src="<?php echo $cur_file_icons_path; ?>/<?php echo (intval(mosGetParam($_SESSION, 'user_editor_off', ''))) ? 'editor_off.png' : 'editor_on.png' ?>" alt="<?php echo _DISABLE_WYSIWYG_EDITOR ?>" />
                        </td>
                        <td style="padding-left: 12px;" align="right" class="jtd_nowrap">
                            <a href="<?php echo JPATH_SITE; ?>/" target="_blank" class="preview" title="<?php echo _PREVIEW_SITE ?>"><?php echo _PREVIEW_SITE ?></a>
                        </td>
                        <td style="padding-left: 7px;" align="right" class="jtd_nowrap">
                            <a href="index2.php?option=logout" class="logoff"><?PHP echo _BUTTON_LOGOUT ?> <?php echo $my->username; ?></a>&nbsp;
                        </td>
                    </tr>
                </table>
            </div>
			<?php if ($option != '') { ?>
				<div id="top-toolbar"><?php mosLoadAdminModule('toolbar'); ?></div>
			<?php }; ?>
			<?php mosLoadAdminModule('mosmsg'); ?>
			<?php josSecurityCheck('100%'); ?>
            <div id="status-info">&nbsp;</div>
            <div id="main_body"><?php mosMainBody_Admin(); ?></div>
        </div>
        <div id="footer" align="center" class="smallgrey"><?php echo coreVersion::$CMS . ' ' . coreVersion::$CMS_ver . ' :: ' . coreVersion::$RELDATE . ' ' . coreVersion::$RELTIME . '<br />' . coreVersion::$SUPPORT; ?></div>
        <script type="text/javascript">
            var _live_site = '<?php echo JPATH_SITE; ?>';
            var _option = '<?php echo mosGetParam($_REQUEST, 'option', ''); ?>';
            // путь к текущим графическим элементам
            var image_path ='<?php echo $cur_file_icons_path ?>/';
            var _cur_template = '<?php echo JTEMPLATE; ?>';
        </script>
    </body>
</html>