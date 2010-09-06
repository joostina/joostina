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

mosCommonHTML::loadJquery();
mosCommonHTML::loadJqueryUI( true );

$mainframe = mosMainFrame::getInstance();
$mainframe->addJS( JPATH_SITE . '/includes/libraries/elfinder/js/elFinder.js');
$mainframe->addJS( JPATH_SITE . '/includes/libraries/elfinder/js/elFinder.view.js');
$mainframe->addJS( JPATH_SITE . '/includes/libraries/elfinder/js/elFinder.quickLook.js');
$mainframe->addJS( JPATH_SITE . '/includes/libraries/elfinder/js/elFinder.eventsManager.js');
$mainframe->addJS( JPATH_SITE . '/includes/libraries/elfinder/js/elFinder.ui.js');
$mainframe->addJS( JPATH_SITE . '/includes/libraries/elfinder/js/i18n/elfinder.ru.js');
$mainframe->addCSS( JPATH_SITE . '/includes/libraries/elfinder/js/ui-themes/base/ui.all.css' );
$mainframe->addCSS( JPATH_SITE . '/includes/libraries/elfinder/css/elfinder.css' );