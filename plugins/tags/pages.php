<?php
/**
 * @package Joostina
 * @copyright Авторские права (C) 2008-2009 Joostina team. Все права защищены.
 * @license Лицензия http://www.gnu.org/licenses/gpl-2.0.htm GNU/GPL, или help/license.php
 * Joostina! - свободное программное обеспечение распространяемое по условиям лицензии GNU/GPL
 * Для получения информации о используемых расширениях и замечаний об авторском праве, смотрите файл help/copyright.php.
 */

// запрет прямого доступа
defined('_VALID_MOS') or die(); 

function Tags_pages_object_tags($row, $tags) {
	if($_tags = $tags->load_by_obj($row->id)) {
		return $tags->print_tags($row->id);
	}
	else {
		return 'Тэги не указаны';
	}


}

function Tags_pages_object_tags_edit($row, $tags) {
	$_tags = $tags->load_by_obj($row->id);
	return $tags->print_tags_edit($row->id);

}

function Tags_pages_group_tags($rows, $tags) {
	$tags->load_by_group($rows, 'id');
}