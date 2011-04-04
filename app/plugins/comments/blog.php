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

class commentsBlog extends Comments {

	public static function href(Comments $comment) {

		joosLoader::model('blog');

		$sql = sprintf('SELECT b.id, c.slug AS cat_slug FROM #__blog as b INNER JOIN #__blog_category AS c ON ( c.id=b.category_id AND c.state=1 ) WHERE b.id=%s', $comment->obj_id);
		$blog = database::instance()->set_query($sql, 0, 1)->load_assoc_row();

		return str_replace(JPATH_SITE, '', joosRoute::href('blog_view', array('cat_slug' => $blog['cat_slug'], 'id' => $blog['id'])));
	}

}