<?php
/**
 * @package Joostina
 * @copyright Авторские права (C) 2007-2010 Joostina team. Все права защищены.
 * @license Лицензия http://www.gnu.org/licenses/gpl-2.0.htm GNU/GPL, или help/license.php
 * Joostina! - свободное программное обеспечение распространяемое по условиям лицензии GNU/GPL
 * Для получения информации о используемых расширениях и замечаний об авторском праве, смотрите файл help/copyright.php.
 */

// запрет прямого доступа
defined('_VALID_MOS') or die();

/**
 * @package Joostina
 * @subpackage Templates
 */
class mosTemplatePosition extends mosDBTable {
	var $id = null;
	var $position = null;
	var $description = null;

	function mosTemplatePosition() {
		global $database;
		$this->mosDBTable('#__template_positions','id',$database);
	}
}

/**
 * Template Table Class
 *
 * Provides access to the jos_templates table
 * @package Joostina
 */
class mosTemplate extends mosDBTable {
	/**
	 @var int*/
	var $id = null;
	/**
	 @var string*/
	var $cur_template = null;
	/**
	 @var int*/
	var $col_main = null;

	/**
	 * @param database A database connector object
	 */
	function mosTemplate(&$database) {
		$this->mosDBTable('#__templates','id',$database);
	}
}
