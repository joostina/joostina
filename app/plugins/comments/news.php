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

class commentsNews {

	public static function href(Comments $comment) {
		
		joosLoader::model('news');

		$sql = sprintf('SELECT id,type_id FROM #__news AS n  WHERE n.id=%s', $comment->obj_id);
		$new = database::instance()->set_query($sql, 0, 1)->load_assoc_row();

		$new['slug'] = News::get_types_slug_by_type_id($new['type_id']);
		
		return str_replace( JPATH_SITE , '', joosRoute::href('news_view', array('type' => $new['slug'], 'id' => $new['id'])));
	}

}