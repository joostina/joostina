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

foreach( $news_items as $new_item ){
	$href = Jroute::href('news_view',  array( 'id'=>$new_item->id, 'type'=>  News::get_types_slug_by_type_id( $new_item->type_id )  ) );
	echo '<h3><a href="'.$href.'">'.$new_item->title.'</a></h3>';
	echo $new_item->introtext.'<br /><hr /><br />';
}

echo $pager->output;