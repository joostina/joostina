<?php

/**
 * @package Joostina
 * @copyright Авторские права (C) 2008-2010 Joostina team. Все права защищены.
 * @license Лицензия http://www.gnu.org/licenses/gpl-2.0.htm GNU/GPL, или help/license.php
 * Joostina! - свободное программное обеспечение распространяемое по условиям лицензии GNU/GPL
 * Для получения информации о используемых расширениях и замечаний об авторском праве, смотрите файл help/copyright.php.
 */
// запрет прямого доступа
defined('_JOOS_CORE') or die();

class Search {
	
}

class SearchLog extends JDBmodel {

	public $word;
	public $hit;

	public function __construct() {
		$this->JDBmodel('#__searched', 'word');
	}

	public static function add($word) {
		$sql = sprintf("INSERT INTO `#__searched` (`word`, `hit`) VALUES ('%s',1) ON DUPLICATE KEY UPDATE hit=hit+1;", $word);
		return database::getInstance()->setQuery($sql)->query();
	}

        public static function get_log( $q ){
            return database::getInstance()->setQuery( "SELECT hit as id,  word as label, word as value FROM #__searched WHERE LOWER(word) LIKE LOWER('%{$q}%') ORDER BY hit DESC" , 0, 15)->loadAssocList();
        }


}