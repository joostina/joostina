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

class joosDebug {

	private static $instance;
	/* стек сообщений лога */
	private static $_log = array();
	/* буфер сообщений лога */
	private static $text = null;
	/* счетчики */
	private static $_inc = array();
	/**
	 * Массив внутренней конфигурации отладчика
	 * @var array
	 */
	private static $config = array(
		'sort_inc_log' => true // сортировать лог счетчика по алфавиту
	);

	public static function instance() {
		if (self::$instance === null) {
			self::$instance = new self;
		}
		return self::$instance;
	}

	private function __clone() {

	}

	public static function add($text, $top = 0) {
		$top ? array_unshift(self::$_log, $text) : self::$_log[] = $text;
	}

	public static function add_top($text){
		self::add($text, true);
	}


	public static function inc($key) {
		if (!isset(self::$_inc[$key])) {
			self::$_inc[$key] = 0;
		}
		self::$_inc[$key]++;
	}

	public static function get() {
		echo '<span style="display:none"><![CDATA[<noindex>]]></span><pre>';

		self::$text = '';

		/* счетчики */
		self::$text .= '<ul class="debug_log listreset">';

		// TODO, тут можно отключать если
		self::$config['sort_inc_log'] ? ksort(self::$_inc) : null;

		foreach (self::$_inc as $key => $value) {
			self::$text .= '<li>INC: ' . $key . ': ' . $value . '</small>';
		}
		self::$text .= '</ul>';
		// выведем лог в более приятном отображении
		array_multisort(self::$_log);

		/* лог */
		self::$text .= '<ul class="debug_log listreset">';
		foreach (self::$_log as $key => $value) {
			self::$text .= '<li><small>LOG:</small> ' . $value . '</li>';
		}
		self::$text .= '</ul>';

		self::$text .= self::db_debug();

		/* подключенные файлы */
		$files = get_included_files();
		$f = array();
		$f[] = '<div onclick="$(\'#_debug_file\').toggle();" style="cursor: pointer;border-bottom:1px solid #CCCCCC;border-top:1px solid #CCCCCC;">' . __('Подключено файлов') . ': ' . count($files) . '</div>';
		$f[] = '<table id="_debug_file" style="display:none">';
		foreach ($files as $key => $value) {
			$f[] = '<tr><td>#' . $key . ':</td><td> ' . $value . '</td></tr>';
		}
		$f[] = '</table>';

		self::$text .= implode('', $f);
		unset($f);
		echo '<div id="jDebug">' . self::$text . '</div>';
		echo '</pre><span style="display:none"><![CDATA[</noindex>]]></span>';
	}

	private static function db_debug() {
		$profs = joosDatabase::instance()->set_query('show profiles;')->load_assoc_list();

		$r = array();
		$r[] = '<div onclick="$(\'#_sql_debug_log\').toggle();" style="cursor: pointer;border-bottom:1px solid #CCCCCC;border-top:1px solid #CCCCCC;">SQL: ' . count($profs) . '</div>';
		$r[] = '<table id="_sql_debug_log" style="display:none">';
		if (isset($profs[0])) {
			foreach ($profs as $prof) {
				$r[] = '<tr valign="top"><td>#' . $prof['Query_ID'] . ' </td><td> ' . $prof['Duration'] . ' </td><td> ' . $prof['Query'] . ' </td></tr>';
			}
		}
		$r[] = '</table>';
		return implode('', $r);
	}

}