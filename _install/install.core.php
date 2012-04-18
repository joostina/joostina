<?php

class joosInstallRequest {

	/**
	 * Получение параметра
	 *
	 * @param string     $name    название параметра
	 * @param string     $default значение для параметра по умолчани
	 * @param array|bool $vars    массив переменных из которого необходимо получить параметр $name, по умолчанию используется суперглобальный $_REQUEST
	 *
	 * @return mixed результат, массив или строка
	 */
	public static function param($name, $default = null, $vars = false) {
		$vars = $vars ? $vars : $_REQUEST;
		return ( isset($vars[$name]) ) ? $vars[$name] : $default;
	}

}

class joosInstall {

	/**
	 * Проверка доступности базы данных
	 *
	 * @param type $host
	 * @param type $user
	 * @param type $pass
	 * @param type $db_name
	 */
	public static function check_db($host, $user, $pass, $db_name) {

		if (!@mysql_connect($host, $user, $pass)) {
			return array('message' => 'Подключение к серверу базы данных провалено',
				'success' => false);
		}

		if (!mysql_select_db($db_name)) {
			return array('message' => 'Выбор базы данных провален',
				'success' => false);
		}

		return array('message' => 'Всё прекрасно',
			'success' => true);
	}

	/**
	 * Установка SQL структуры
	 * На основе http://rmcreative.ru/blog/post/import-bolshchikh-sql-dampov-cherez-php#c4753
	 */
	public static function install_db($host, $user, $pass, $db_name) {

		mysql_connect($host, $user, $pass);
		mysql_select_db($db_name);

		mysql_query('SET NAMES "utf8"');
		$dump = file_get_contents('sql/core.sql');
		$q = '';
		$state = 0;
		$len = strlen($dump);
		for ($i = 0; $i < $len; $i++) {
			switch ($dump{$i}) {
				case '"':
					if ($state == 0) {
						$state = 1;
					} elseif ($state == 1) {
						$state = 0;
					}
					break;
				case "'":
					if ($state == 0) {
						$state = 2;
					} elseif ($state == 2) {
						$state = 0;
					}
					break;
				case "`":
					if ($state == 0) {
						$state = 3;
					} elseif ($state == 3) {
						$state = 0;
					}
					break;
				case ";":
					if ($state == 0) {
						//echo $q . "\n;\n";
						mysql_query($q);
						$q = '';
						$state = 4;
					}
					break;
				case "\\":
					if (in_array($state, array(1, 2, 3))) {
						$q .= $dump[$i++];
					}
					break;
			}
			if ($state == 4) {
				$state = 0;
			} else {
				$q .= $dump{$i};
			}
		}

		return array('message' => 'Всё прекрасно',
			'success' => true);
	}

}