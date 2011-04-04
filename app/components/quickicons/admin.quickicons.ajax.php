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

Jacl::isDeny('quickicons','edit') ? ajax_acl_error() : null;

require joosCore::path('quickicons', 'admin_class');

// подключаем библиотеку JoiAdmin
joosLoader::lib('joiadmin', 'system');
// передаём управление полётом в автоматический Ajax - обработчик
echo JoiAdmin::autoajax();