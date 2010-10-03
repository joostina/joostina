<?php

/**
 * @package Joostina
 * @copyright Авторские права (C) 2008 Joostina team. Все права защищены.
 * @license Лицензия http://www.gnu.org/licenses/gpl-2.0.htm GNU/GPL, или help/license.php
 * Joostina! - свободное программное обеспечение распространяемое по условиям лицензии GNU/GPL
 * Для получения информации о используемых расширениях и замечаний об авторском праве, смотрите файл help/copyright.php.
 */
// запрет прямого доступа
defined('_JOOS_CORE') or die();

class extramenuHelper {
	private static $counter;

	public static function ul_li_recurse(array $items) {

		if( self::$counter==NULL ){
			$class = 'class="dropdown"';
		}else{
			$class = '';
		}

		$menu = array();
		$menu[] = '<ul '.$class.'>';
		foreach ($items as $item => $datas) {
			$menu[] = '<li>';
			$menu[] = sprintf( '<a href="%s" title="%s">%s</a>', JPATH_SITE . $datas['href'], ( isset($datas['title']) ? $datas['title'] : $item  ), $item );
			if( isset($datas['children']) && is_array($datas['children']) ){
				$menu[] = self::ul_li_recurse($datas['children']);
			}
			$menu[] = '</li>';
		}
		$menu[] = '</ul>';


		return implode("\n", $menu);
	}

}