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

Jacl::isDeny('filemanager') ? mosRedirect('index2.php?', _NOT_AUTH) : null;

require_once($mainframe->getPath('admin_html'));

mosMainFrame::addLib('elfinder');

// конфигурация редактора, тут можно сдлеать настраиваемость для разных групп пользователям
$elfinder_config = array(
        'url' => JPATH_SITE . '/ajax.index.php?option=com_finder',
        'lang' => _LANGUAGE,
        'height' => '600px',
        'wrap' => 14,
        'places'=>'',
        'placesFirst' => FALSE,
        'view' => 'icons',
        'width' => '100%',
        'height' => '600px',
        'disableShortcuts' => false,
        'dialog' => null,
        'docked' => false
);

// исправляем ошибку с кворированием URL сайта
$elfinder_config = str_replace('\/', '/', json_encode($elfinder_config));

elFinder::index($elfinder_config);