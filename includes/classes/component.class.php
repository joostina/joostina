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
/**
 * Component database table class
 * @package Joostina
 */
class mosComponent extends JDBmodel {
	public $id;
	public $name;
	public $link;
	public $menuid;
	public $parent;
	public $admin_menu_link;
	public $admin_menu_alt;
	public $option;
	public $ordering;
	public $admin_menu_img;
	public $iscore;
	public $params;
	public $_model;
	public $_controller;
	public $_view;
	public $_mainframe;

	function mosComponent() {
		$this->JDBmodel('#__components','id');
	}
}