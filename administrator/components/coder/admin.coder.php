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

mosMainFrame::addLib('joiadmin');
JoiAdmin::dispatch();

class actionsCoder {

	public static function on_start() {
		mosMainFrame::addLib('form');
		require joosCore::path('coder', 'admin_class');
		Jdocument::getInstance()->addJS(JPATH_SITE . '/' . JADMIN_BASE . '/components/coder/media/js/coder.js');

	}

	public static function index() {

		echo '<div class="tocorner">';

		echo adminHTML::controller_header('Кодер - моделегенератор');

		$tables = database::getInstance()->getUtils()->getTableList();

		$rets = array();
		$rets[] = '<table class="adminlist"><tbody><tr><th>Таблицы</th><th>Код моделей</th></tr></tbody><tr>';
		$rets[] = '<td width="200" valign="top">';
		$rets[] = form::open('#',  array('id'=>'coder_form') );
		foreach ($tables as $key => $value) {
			$el_id = 'table_' . $value;
			$rets[] = form::checkbox('codertable[]', $value, false, 'id="' . $el_id . '" ');
			$rets[] = form::label($el_id, $value);
			$rets[] = '<br />';
		}
		$rets[] = form::close();
		$rets[] = '</td><td valign="top">';
		$rets[] = '<div id="coder_results" /></div>';
		$rets[] = '</td>';
		$rets[] = '</tr></table>';

		echo implode("\n", $rets);

		echo '</div>';
	}

	public static function create($option) {

		$tables = array(
			'#__quickicons',
			'#__news',
			'#__blog_category',
		);

		$classes = array();
		foreach ($tables as $table) {
			$classes[] = get_model($table);
		}

		echo implode("\n", $classes);
	}

}
