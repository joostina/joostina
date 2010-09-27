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

echo '<h1>'.'<a href="'.Jroute::href('blog' ).'">Блоги</a>'.' / '.$blog_category->title.'</h1>';
echo '<p>'.$blog_category->description.'</p>';
echo '<br />';

foreach( $blog_items as $blog_item ){
	$href = Jroute::href('blog_view',  array( 'id'=>$blog_item->id, 'cat_name'=>$blog_item->cat_name ) );
	echo '<h3><a href="'.$href.'">'.$blog_item->title.'</a></h3>';
	echo $blog_item->introtext.'<br /><hr /><br />';
}

echo $pager->output;