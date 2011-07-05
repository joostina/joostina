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

/*
 * Класс формирования представлений
 */

class htmlAdminTemplates {

	/**
	 * Список позиций
	 * @param joosModel $obj - основной объект отображения
	 * @param array $obj_list - список объектов вывода
	 * @param joosAdminPagenator $pagenav - объект постраничной навигации
	 */
	public static function index_positions($obj, $obj_list, $pagenav) {
		// массив названий элементов для отображения в таблице списка
		$fields_list = array('position', 'description');
		// передаём информацию о объекте и настройки полей в формирование представления
		joosAutoadmin::listing($obj, $obj_list, $pagenav, $fields_list);
	}

}
