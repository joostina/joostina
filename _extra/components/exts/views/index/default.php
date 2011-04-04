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

$ext_types = Exts::get_types_slug();

foreach ($exts_items as $exts_item) {
	$href = joosRoute::href('extensions_view',  array('type'=>$ext_types[$exts_item->type_id] ,'name'=>$exts_item->slug) );
	echo sprintf( '<a href="%s">%s</a><br />', $href, $exts_item->title );
}