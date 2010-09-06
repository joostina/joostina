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


class CustomQuickIcons extends mosDBTable {
	public $id;
	public $text;
	public $target;
	public $icon;
	public $ordering;
	public $new_window;
	public $published;
	public $title;
	public $display;
	public $access;
	public $gid;

	function CustomQuickIcons() {
		$this->mosDBTable('#__quickicons','id');
	}

	function check() {
		$returnVar = true;

		if(empty($this->icon) && $this->display != '1') {
			$this->_error = _PLEASE_ENTER_NUTTON_LINK;
			$returnVar = false;
		}
		if(empty($this->target)) {
			$this->_error = _PLEASE_ENTER_NUTTON_LINK;
			$returnVar = false;
		}
		if(empty($this->text)) {
			$this->_error = _PLEASE_ENTER_BUTTON_TEXT;
			$returnVar = false;
		}

		return $returnVar;
	}
}