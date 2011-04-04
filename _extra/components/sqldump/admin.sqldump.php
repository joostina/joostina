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

Jacl::isDeny('sqldump') ? joosRoute::redirect('index2.php?', _NOT_AUTH) : null;

joosLoader::lib('joiadmin', 'system');
JoiAdmin::dispatch();

class actionsSqldump {

	public static function index() {

		$tables = '*';

		$db_config = joosConfig::get('db');

		$link = mysql_connect($db_config['host'], $db_config['user'], $db_config['password']);
		mysql_select_db($db_config['name'], $link);

		mysql_query('SET NAMES `UTF8`');

		if ($tables == '*') {
			$tables = array();
			$result = mysql_query('SHOW TABLES');
			while ($row = mysql_fetch_row($result)) {
				$tables[] = $row[0];
			}
		} else {
			$tables = is_array($tables) ? $tables : explode(',', $tables);
		}

		$return = '';

		foreach ($tables as $table) {
			$result = mysql_query('SELECT * FROM ' . $table);
			$num_fields = mysql_num_fields($result);

			$return.= 'DROP TABLE IF EXISTS ' . $table . ';';
			$row2 = mysql_fetch_row(mysql_query('SHOW CREATE TABLE ' . $table));
			$return.= "\n" . $row2[1] . ";\n";

			for ($i = 0; $i < $num_fields; $i++) {
				while ($row = mysql_fetch_row($result)) {
					$return.= 'INSERT INTO ' . $table . ' VALUES(';
					for ($j = 0; $j < $num_fields; $j++) {
						$row[$j] = addslashes($row[$j]);
						$row[$j] = str_replace(array("\n\r", "\n"), '\n', $row[$j]);
						if (isset($row[$j])) {
							$return.= '"' . $row[$j] . '"';
						} else {
							$return.= '""';
						}
						if ($j < ($num_fields - 1)) {
							$return.= ',';
						}
					}
					$return.= ");\n";
				}
			}
			$return.="\n\n";
		}

		$file = 'backups/db-backup-' . date('Y-m-d-H-i-s', time()) . '-' . (md5(implode(',', $tables))) . '.sql';
		$handle = fopen(JPATH_BASE . DS . JADMIN_BASE . DS . $file, 'w+');
		fwrite($handle, $return);
		fclose($handle);

		joosLoader::lib('html');
		echo HTML::anchor(JPATH_SITE . '/' . JADMIN_BASE . '/' . $file, 'Скачать');
	}

}