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

joosHTML::load_jquery_ui();

joosDocument::instance()
        ->add_js_file(JPATH_SITE . '/includes/libraries/elfinder/js/elFinder.js')
        ->add_js_file(JPATH_SITE . '/includes/libraries/elfinder/js/elFinder.view.js')
        ->add_js_file(JPATH_SITE . '/includes/libraries/elfinder/js/elFinder.quickLook.js')
        ->add_js_file(JPATH_SITE . '/includes/libraries/elfinder/js/elFinder.eventsManager.js')
        ->add_js_file(JPATH_SITE . '/includes/libraries/elfinder/js/elFinder.ui.js')
        ->add_js_file(JPATH_SITE . '/includes/libraries/elfinder/js/i18n/elfinder.ru.js')
        ->add_css(JPATH_SITE . '/includes/libraries/elfinder/js/ui-themes/base/ui.all.css')
        ->add_css(JPATH_SITE . '/includes/libraries/elfinder/css/elfinder.css');