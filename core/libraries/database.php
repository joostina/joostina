<?php

// запрет прямого доступа
defined('_JOOS_CORE') or die();

/**
 * joosDatabase - Библиотека работы с базой данных
 * Системная библиотека
 *
 * @version 1.0
 * @package Joostina.Libraries
 * @subpackage Libraries
 * @subpackage joosDatabase
 * @category Libraries
 * @author Joostina Team <info@joostina.ru>
 * @copyright (C) 2008-2011 Joostina Team
 * @license MIT License http://www.opensource.org/licenses/mit-license.php
 * Информация об авторах и лицензиях стороннего кода в составе Joostina CMS: docs/copyrights
 *
 * */
class joosDatabase {

	/**
	 * Объект активного соединения с базой данных
	 * @var joosDatabase
	 */
	private static $instance;
	/**
	 * Переменныя хранения активной или готовящейся к выполнению SQL команды
	 * @var string
	 */
	protected $_sql;
	/**
	 * Код ошибки работы с базой данных
	 * @var int
	 */
	protected $_error_num = 0;
	/**
	 * Текст ошибки работы с базой данных
	 * @var string
	 */
	protected $_error_msg;
	/**
	 * Префикс таблиц активного соединения
	 * @var string
	 */
	protected $_table_prefix;
	/**
	 * Ресурс активного соединения с базой данных
	 * @var res
	 */
	protected $_resource;
	/**
	 * Результат последнего активного SQL запроса
	 * @var
	 */
	protected $_cursor;
	/**
	 * Параметр включения отладки работы с базой данных
	 * @var boolean
	 */
	protected $_debug;
	/**
	 * Лимит для активного запроса
	 * @var int
	 */
	protected $_limit;
	/**
	 * Смещение для активного запроса
	 * @var int
	 */
	protected $_offset;

	/**
	 * Конструктор открывающий соединение с базой данных
	 * @param string $host - хост базы данных, обычно localhost
	 * @param string $user - имя пользователя базы данных
	 * @param string $pass - пароль соединения с базой данных
	 * @param string $db - название базы данных
	 * @param string $table_prefix - преффикс таблиц базы данных
	 * @param boolean $debug - активность отладки рабюоты с базой данных
	 * @param int $port - порт сервера MySQL
	 * @param string $socket - сокет MySQL
	 */
	protected function __construct($host = 'localhost', $user = 'root', $pass = '', $db = '', $table_prefix = '#__', $debug = 0, $port = null, $socket = null) {
		$this->_debug = $debug;
		$this->_table_prefix = $table_prefix;

		// проверка доступности поддержки работы с базой данных в php
		if (!function_exists('mysqli_connect')) {
			$mosSystemError = 1;
			include JPATH_BASE . '/app/templates/system/offline.php';
			exit();
		}

		// попытка соединиться с сервером баз данных
		if (!($this->_resource = @mysqli_connect($host, $user, $pass, $db, $port, $socket))) {
			$mosSystemError = 2;
			include JPATH_BASE . '/app/templates/system/offline.php';
			exit();
		}

		// при активации отладки выполнение дополнительных запросов профилирования
		if ($this->_debug == 1) {
			mysqli_query($this->_resource, 'set profiling=1');
			mysqli_query($this->_resource, sprintf('set profiling_history_size=%s', joosConfig::get2('db', 'profiling_history_size', 100)));
		}
		;

		// устанавливаем кодировку для корректного соединения с сервером базы данных
		mysqli_set_charset($this->_resource, 'utf8');
	}

	/**
	 * Уничтожение объекта
	 * При уничтожении объекта происходит закрытие соединения с базой
	 */
	public function __destruct() {
		if (is_resource($this->_resource)) {
			// TODO это убрать при постоянных соединениях
			mysqli_close($this->_resource);
		}
	}

	/**
	 * Получение инстанции для работы с базой данных
	 * @return joosDatabase - объект базы данных
	 */
	public static function instance() {

		// отметка получения инстенции базы данных
		JDEBUG ? joosDebug::inc('joosDatabase::instance()') : null;

		if (self::$instance === NULL) {
			$db_config = joosConfig::get('db');
			$database = new joosDatabase($db_config['host'], $db_config['user'], $db_config['password'], $db_config['name'], $db_config['prefix'], $db_config['debug']);

			if ($database->get_error_num()) {
				$mosSystemError = $database->get_error_num();
				include JPATH_BASE . DS . 'templates/system/offline.php';
				exit();
			}
			self::$instance = $database;
		}
		return self::$instance;
	}

	/**
	 * Закрытый метод для предотвращения клонирования объекта базы данных
	 */
	// TODO исправить, метод CLONE используется при кешированиии и сериалзации модели
	public function __clone() {
		
	}

	/**
	 * Установка пааметра отладки базы данных
	 * @param boolean $debug флаг активности отладки
	 */
	public function debug($debug) {
		$this->_debug = (bool) $debug;
	}

	/**
	 * Получение кода ошибки
	 * @return int
	 */
	public function get_error_num() {
		return $this->_error_num;
	}

	/**
	 * Получение сообщения об ошибке в работе с базой данных
	 * @return string
	 */
	public function get_error_msg() {
		return str_replace(array("\n", "'"), array('\n', "\'"), $this->_error_msg);
	}

	/**
	 * Экранирование элементов
	 * @param string $text - значение для экранирования
	 * @param boolean $extra - дополнительная обработка элемента
	 * @return string
	 */
	public function get_escaped($text, $extra = false) {
		$string = mysqli_real_escape_string($this->_resource, $text);
		return $extra ? addcslashes($string, '%_') : $string;
	}

	/**
	 * Квотирование элемента
	 * @param string $text - значение для квотирования
	 * @param boolean $escaped - параметр расширенного квотирования
	 * @return string обработанный результат
	 */
	public function quote($text, $escaped = true) {
		return '\'' . ($escaped ? $this->get_escaped($text) : $text) . '\'';
	}

	/**
	 * Квотирование элементов спецсиволами
	 * Используется для обрамления названий таблиц и полей базы данных в SQL запросах
	 * @param string $s встрока для квотирования
	 * @return string обработанная строка
	 */
	public function name_quote($s) {
		return '`' . $s . '`';
	}

	/**
	 * Получение преффикса таблиц, по умолчанию jos_
	 * Преффиксы используются для размещения в одной базе данных нескольких структур баз разных сайтов
	 * @return string
	 */
	public function get_prefix() {
		return $this->_table_prefix;
	}

	/**
	 * Получение нулевого значения времени для использования по умолчанию в sql запросах
	 * @return string строка определяющая нулевое значение времени для использования в базе
	 */
	public function get_null_date() {
		return '0000-00-00 00:00:00';
	}

	/**
	 * Установка строки SQL запроса для дальнейшего выполнения
	 * Первый и гравный метод для любой работы с базой данных
	 * @param string $sql текст sql запроса для выполнения
	 * @param int $offset значения смещения для результато ввыборки
	 * @param int $limit ограничение н ачисло выбираемых объектов
	 * @return joosDatabase
	 */
	public function set_query($sql, $offset = 0, $limit = 0) {
		$this->_sql = $this->replace_prefix($sql, $this->_table_prefix);
		$this->_limit = (int) $limit;
		$this->_offset = (int) $offset;
		return $this;
	}

	/**
	 * Замена преффикса таблиц базы данных
	 * @param string $sql текст sql запроса для замены преффикса
	 * @param string $prefix строка преффикса
	 * @return string sql с заменённым преффиксом
	 */
	private function replace_prefix($sql, $prefix = '#__') {
		return str_replace('#__', $this->_table_prefix, $sql);
	}

	/**
	 * Получение текста последнего установленного SQL запроса
	 * @return string строка sql запроса
	 */
	public function get_query() {
		return sprintf('<pre code="sql">%s</pre>', htmlspecialchars($this->_sql, ENT_QUOTES, 'utf-8'));
	}

	/**
	 * Выполнение установленного ранее SQL запроса
	 * Непосредственно само действие выполняемое в базе данных
	 * @return mysql cursor ресурс результата выполнения запроса
	 */
	public function query() {

		if ($this->_limit > 0 && $this->_offset == 0) {
			$this->_sql .= "\nLIMIT $this->_limit";
		} elseif ($this->_limit > 0 || $this->_offset > 0) {
			$this->_sql .= "\nLIMIT $this->_offset, $this->_limit";
		}

		$this->_error_num = 0;
		$this->_error_msg = '';
		$this->_cursor = mysqli_query($this->_resource, $this->_sql);

		if (!$this->_cursor) {
			$this->_error_num = mysqli_errno($this->_resource);
			$this->_error_msg = mysqli_error($this->_resource) . " SQL=$this->_sql";
			if ($this->_debug) {
				$this->get_utils()->show_db_error(mysqli_error($this->_resource), $this->_sql);
			}
			return false;
		}

		return $this->_cursor;
	}

	/**
	 * Возвращает количество рядов, задействованных в последнем запросе INSERT, UPDATE или DELETE
	 * @return int число рядок результатов
	 */
	public function get_affected_rows() {
		return mysqli_affected_rows($this->_resource);
	}

	/**
	 * Возвращает количество рядов в результирующем наборе. Эта команда верна только для операторов SELECT.
	 * @param mysql cursor $cur ресурс результата выполнения запроса
	 * @return int количество рядов в результирующем наборе
	 */
	public function get_num_rows($cur = false) {
		return mysqli_num_rows($cur ? $cur : $this->_cursor);
	}

	/**
	 * Возвращает один (первый) результат выполненного запроса
	 * @return string строка результата
	 */
	public function load_result() {

		// TODO, логично, но спорно
		$this->_limit = 1;
		$this->_offset = 0;

		if (!($cur = $this->query())) {
			return null;
		}

		$ret = ($row = mysqli_fetch_row($cur)) ? $row[0] : null;

		mysqli_free_result($cur);

		return $ret;
	}

	/**
	 * Возвращает результат запроса в виде массива. Массив содержит значения столбца под номером указанным в $numinarray
	 * @param int $numinarray номер столбца для отобрадения в результуриющем запросе. 0 - первый столбцев, 1 - второй столбец и т.д
	 * @return array массив результата
	 */
	public function load_result_array($numinarray = 0) {
		if (!($cur = $this->query())) {
			return null;
		}
		$array = array();
		while ($row = mysqli_fetch_row($cur)) {
			$array[] = $row[$numinarray];
		}
		mysqli_free_result($cur);
		return $array;
	}

	/**
	 * Возвращаем массив результата запроса. Каждый результирующий столбец хранится как массив массива, начиная со позиции 0.
	 * Может возвращать ассоциативный массив гд еключем выступает значение поля указанное в параметре $key
	 * @param string $key поле выступающее в качестве ключа для ассоциативного массива результата
	 * @return array ассоциативнй либо обычный массив массивов результата
	 */
	public function load_assoc_list($key = '') {
		if (!($cur = $this->query())) {
			return null;
		}
		$array = array();
		while ($row = mysqli_fetch_assoc($cur)) {
			if ($key) {
				$array[$row[$key]] = $row;
			} else {
				$array[] = $row;
			}
		}
		mysqli_free_result($cur);

		return $array;
	}

	/**
	 * Возвращает первый результат запроса в виде ассоциативного массива название поля - значение
	 * @return array ассоциативный массив результата
	 */
	public function load_assoc_row() {

		if (!($cur = $this->query())) {
			return null;
		}
		$row = mysqli_fetch_assoc($cur);

		mysqli_free_result($cur);

		return $row;
	}

	/**
	 * Загружает результат запроса в принимаемы в качестве параметра объект
	 * @param stdClass $object объект для загрузки результата
	 * @return bool результат сбора результата в значения полей принимаемого объекта
	 */
	public function load_object(& $object) {
		if ($object != null) {
			if (!($cur = $this->query())) {
				return false;
			}
			if (($array = mysqli_fetch_assoc($cur))) {
				mysqli_free_result($cur);
				$this->bind_array_to_object($array, $object, null, null, false);
				return true;
			} else {
				return false;
			}
		} else {
			if (($cur = $this->query())) {
				if (($object = mysqli_fetch_object($cur))) {
					mysqli_free_result($cur);
					return true;
				} else {
					$object = null;
					return false;
				}
			} else {
				return false;
			}
		}
	}

	/**
	 * Возвращает ассоциативный либо простой массив объектов результата запроса.
	 * В качестве ключей массива результата может быть использовано значение поля указанного в $key
	 * @param string $key поле выступающее в качестве ключа для ассоциативного массива результата
	 * @return array ассоциативный или обычный массив результатов
	 */
	public function load_object_list($key = '') {

		if (!($cur = $this->query())) {
			return null;
		}

		$array = array();
		while ($row = mysqli_fetch_object($cur)) {
			if ($key) {
				$array[$row->$key] = $row;
			} else {
				$array[] = $row;
			}
		}
		mysqli_free_result($cur);

		return $array;
	}

	/**
	 * Возвращает массив результата запроса, в котором в качестве значений выступают значения полей первого результата
	 * @return array массив значение полей первого результата
	 */
	public function load_row() {
		if (!($cur = $this->query())) {
			return null;
		}
		$ret = ($row = mysqli_fetch_row($cur)) ? $row : null;
		mysqli_free_result($cur);

		return $ret;
	}

	/**
	 * Возвращает ассоциативный массив результата запроса.
	 * В качестве ключей массива результата может быть использовано номер поля указанного в $key
	 * @param int $key номер поля начиная с 0, значение которого необходимо использовать  вкачестве ключа для ассициативного массива результата
	 * @return array ассоциативный или обычный массив результатов
	 */
	public function load_row_list($key = null) {

		if (!($cur = $this->query())) {
			return null;
		}

		$array = array();

		while ($row = mysqli_fetch_row($cur)) {
			if (!is_null($key)) {
				$array[$row[$key]] = $row;
			} else {
				$array[] = $row;
			}
		}
		mysqli_free_result($cur);

		return $array;
	}

	/**
	 * Возвращает ассоциативный масив результата, ключами которого являются значения поля $key, а значениями - значения поля $value
	 * @param string $key название поля для ключа результирующего массива
	 * @param string $value названи еполя для значения результирующего массива
	 * @return array ассоциативнй массив ключ=>значение результата
	 */
	public function load_row_array($key, $value) {

		if (!($cur = $this->query())) {
			return null;
		}

		$array = array();

		while ($row = mysqli_fetch_object($cur)) {
			$array[$row->$key] = $row->$value;
		}
		mysqli_free_result($cur);

		return $array;
	}

	/**
	 * Вставка записи.
	 * Работает с объектами, свойства которых являются названиями поле в базе, а значения свойств - значениями полей
	 * Работает ТОЛЬКО через joosDatabase::instance()->insert_object
	 * @param string $table название таблицы, можно с преффиксом #__
	 * @param stdClass $object объект с заполненными свойствами
	 * @param string $keyName название ключевого автоинскриментного поля таблицы
	 * @return int идентификатор вставленной записи, истину или ложь если операция провалилась
	 */
	public function insert_object($table, $object, $keyName = null) {

		$fmtsql = "INSERT INTO $table ( %s ) VALUES ( %s ) ";

		$fields = array();
		foreach (get_object_vars($object) as $k => $v) {
			if (is_array($v) or is_object($v) or $v === null) {
				continue;
			}
			if ($k[0] == '_') { // внешние поля
				continue;
			}

			$fields[] = $this->name_quote($k);
			$values[] = $this->quote($v);
		}

		$this->set_query(sprintf($fmtsql, implode(",", $fields), implode(",", $values)));

		if (!$this->query()) {
			return false;
		}

		// TODO тут был прямой вызов
		$id = $this->insert_id();
		//$id = mysqli_insert_id($this->_resource);

		if ($keyName && $id) {
			$object->$keyName = $id;
		}

		return ($id > 0) ? $id : true;
	}

	/**
	 * Обновление записи.
	 * Работает с объектами, свойства которых являются названиями поле в базе, а значения свойств - значениями полей
	 * @param string $table название таблицы, можно с преффиксом #__
	 * @param stdClass $object объект с заполненными свойствами
	 * @param string $keyName название ключевого автоинскриментного поля таблицы
	 * @param type $updateNulls флаг обновления неопределённых свойств
	 * @return bool результат обновления данных записи
	 */
	public function update_object($table, $object, $keyName, $updateNulls = true) {

		$fmtsql = "UPDATE $table SET %s  WHERE %s";
		$tmp = array();
		foreach (get_object_vars($object) as $k => $v) {
			if (is_array($v) or is_object($v) or $k[0] == '_') { // internal or NA field
				continue;
			}
			if ($k == $keyName) { // PK not to be updated
				$where = $keyName . '=' . $this->quote($v);
				continue;
			}
			if ($v === null && !$updateNulls) {
				continue;
			}
			if ($v == '') {
				$val = "''";
			} else {
				$val = $this->quote($v);
			}
			$tmp[] = $this->name_quote($k) . '=' . $val;
		}
		$this->set_query(sprintf($fmtsql, implode(",", $tmp), $where));

		return (bool) $this->query();
	}

	/**
	 * Возвращает текст ошибки работы базы данных
	 * @param bool $showSQL флаг отображения в возвращаемом тексте содержимого ошибочного SQL запроса
	 * @return string текст ошибки
	 */
	public function stderr($showSQL = false) {
		JDEBUG ? joosDebug::add($this->_error_msg . "\n\t" . $this->_sql) : null;
		return "Ошибка базы данных $this->_error_num <br /><font color=\"red\">$this->_error_msg</font>" . ($showSQL ? "<br />SQL = <pre>$this->_sql</pre>" : '');
	}

	/**
	 * Возвращает ID-номер, сгенерированный для столбца AUTO_INCREMENT предыдущим запросом INSERT
	 * @return int
	 */
	public function insert_id() {
		return mysqli_insert_id($this->_resource);
	}

	/**
	 * Возвращаем объект с утилитарными функциями работы с базой данных
	 * @return joosDatabaseUtils
	 */
	public function get_utils() {
		return new joosDatabaseUtils($this);
	}

	/**
	 * Преобразование массива в объект
	 * 
	 * @param array $array исходный массив ключ=>значение
	 * @param object $obj объект, свойства которого будут заполнены значениями сообтветсвующих ключей массива
	 * @param string $ignore свойства объекта которые следует игнорировать, через пробел ('id title slug')
	 * @param string $prefix префикс полей массива. Например в объекте title, а в массивe blog_title
	 * @param bool $checkSlashes флаг экранизации значений через addslashes
	 * @return bool результат предразования
	 */
	public function bind_array_to_object(array $array, &$obj, $ignore = '', $prefix = null, $checkSlashes = false) {

		$ignore = ' ' . $ignore . ' ';
		foreach (get_object_vars($obj) as $k => $v) {
			if (substr($k, 0, 1) != '_') { // закрытые свойства пропускаем
				if (strpos($ignore, ' ' . $k . ' ') === false) {
					if ($prefix) {
						$ak = $prefix . $k;
					} else {
						$ak = $k;
					}
					if (isset($array[$ak])) {
						$obj->$k = $checkSlashes ? addslashes($array[$ak]) : $array[$ak];
					}
				}
			}
		}
		return true;
	}

	/**
	 * Быстрое статическое создание модели и доступ к её медотам и свойствам
	 * 
	 * @example joosDatabase::models('Users')->count()
	 * @example joosDatabase::model('Blog')->get_list( array('where'=>'sate=1') )
	 * @example joosDatabase::model('Blog')->save( $_POST )
	 * 
	 * @param string $model_name
	 * @return joosModel объект выбранной модели
	 */
	public static function models($model_name) {
		return new $model_name;
	}

}

/**
 * joosDatabaseUtils - Библиотека утилитарных функций работы с базой данных
 * Системная библиотека
 *
 * @version 1.0
 * @package Joostina.Libraries
 * @subpackage Libraries
 * @subpackage joosDatabase
 * @author Joostina Team <info@joostina.ru>
 * @copyright (C) 2008-2011 Joostina Team
 * @license MIT License http://www.opensource.org/licenses/mit-license.php
 * Информация об авторах и лицензиях стороннего кода в составе Joostina CMS: docs/copyrights
 *
 * */
class joosDatabaseUtils extends joosDatabase {

	/**
	 *
	 * @var
	 */
	private $_db;

	/**
	 * Объект работы с базой данных
	 * @param joosDatabase $db
	 */
	public function __construct(joosDatabase $db) {
		$this->_db = $db;
	}

	/**
	 * Возвращает объект работы с базой данных
	 * @return joosDatabase
	 */
	private function db() {
		return $this->_db;
	}

	/**
	 * Возвращает строку, представляющую номер версии сервера
	 * @return string строка версии сервера
	 */
	public function get_version() {
		return mysqli_get_server_info($this->_db->_resource);
	}

	/**
	 * Возвращает список таблиц активной базы
	 * @param bool $only_joostina флаг позволяющий оставить в результирующем наборе только таблицы текущего сайта
	 * @return array массив таблиц текущей базы данных
	 */
	public function get_table_list($only_joostina = true) {
		$only_joostina = $only_joostina ? " LIKE '" . $this->db()->_table_prefix . "%' " : '';
		return $this->_db->set_query('SHOW TABLES ' . $only_joostina)->load_result_array();
	}

	/**
	 * Возвращает ассоциативный массив структур таблиц
	 * @param array $tables массив таблиц структуру которых необходимо получить
	 * @return array ассоциативный массив, ключами которогоявляются названия  таблиц, а значениями - самаструктура этихтаблиц
	 */
	public function get_table_create(array $tables) {
		$result = array();

		foreach ($tables as $tblval) {
			$rows = $this->_db->set_query('SHOW CREATE table ' . $this->_db->get_escaped($tblval))->load_row_list();
			foreach ($rows as $row) {
				$result[$tblval] = $row[1];
			}
		}

		return $result;
	}

	/**
	 * Возвращает ассоциативный массив свойств столбцов таблицы
	 * @param string $tables название таблицы
	 * @return array ассоциативный массив, ключами которого являются названия полей, а значения - свойства полей
	 */
	public function get_table_fields($tables) {
		$fields = $this->_db->set_query('SHOW FIELDS FROM ' . $tables)->load_object_list();

		$result = array();
		foreach ($fields as $field) {
			$result[$field->Field] = $field->Type;
		}

		return $result;
	}

	/**
	 * Прямое выполнения множества SQL запросов за один раз.
	 * Запросы должн ыбыть разделены знаком точки с запятой - ;
	 * @param bool $abort_on_error флаг указывающий что при ошибки  водном из запросов дальнейшую работу необходимо прекратить
	 * @param bool $transaction_safe флаг использования транзакций для выполнения запросов
	 * @return bool флаг выполнения работы, истина в случае успешного выполнения всех запросов,
	 */
	public function query_batch($abort_on_error = true, $transaction_safe = false) {
		$this->_error_num = 0;
		$this->_error_msg = '';

		if ($transaction_safe) {
			$this->_sql = 'START TRANSACTION;' . $this->_sql . '; COMMIT;';
		}

		$query_split = preg_split("/[;]+/", $this->_sql);
		$error = 0;

		foreach ($query_split as $command_line) {
			$command_line = trim($command_line);
			if ($command_line != '') {
				$this->_cursor = mysqli_query($this->_resource, $command_line);
				if (!$this->_cursor) {
					$error = 1;
					$this->_error_num .= mysqli_errno($this->_resource) . ' ';
					$this->_error_msg .= mysqli_error($this->_resource) . " SQL=$command_line <br />";
					if ($abort_on_error) {
						return $this->_cursor;
					}
				}
			}
		}

		return (bool) $error;
	}

	/**
	 * Возвращает сформированную HTML таблицу с информации о каждой из использованных в запросе SELECT таблиц
	 * @return string результирующий HTML код
	 */
	public function explain() {
		$temp = $this->_sql;
		$this->_db->_sql = 'EXPLAIN ' . $this->_db->_sql;

		if (!($cur = $this->_db->query())) {
			return null;
		}
		$first = true;

		$buf = '<table cellspacing="1" cellpadding="2" border="0" bgcolor="#000000" align="center">';
		$buf .= $this->_db->get_query();
		while ($row = mysqli_fetch_assoc($cur)) {
			if ($first) {
				$buf .= '<tr>';
				foreach ($row as $k => $v) {
					$buf .= '<th bgcolor="#ffffff">' . $k . '</th>';
				}
				$buf .= '</tr>';
				$first = false;
			}
			$buf .= '<tr>';
			foreach ($row as $k => $v) {
				$buf .= '<td bgcolor="#ffffff">' . $v . '</td>';
			}
			$buf .= '</tr>';
		}
		$buf .= '</table><br />';
		mysqli_free_result($cur);

		$this->_sql = $temp;

		return '<div style="background-color:#FFFFCC" align="left">' . $buf . '</div>';
	}

	/**
	 * Выводит расширенно есообзения о ошибке выполнения запроса
	 * @param string $message текст сообщения - ошибки
	 * @param string $sql sql код запрос вызвавшего ошибку
	 */
	public function show_db_error($message, $sql = null) {
		echo '<div style="display:block;width:100%;"><b>DB::error:</b> ';
		echo $message;
		echo $sql ? '<pre>' . $sql . '</pre><b>UseFiles</b>::' : '';
		if (function_exists('debug_backtrace')) {
			foreach (debug_backtrace() as $back) {
				if (isset($back['file'])) {
					echo '<br />' . $back['file'] . ':' . $back['line'];
				}
			}
		}
		echo '</div>';
	}

}

/**
 * joosModel - Библиотека ORM расширения для гибкой работы с информацией в юазе данных
 * Системная библиотека
 *
 * @version 1.0
 * @package Joostina.Libraries
 * @subpackage Libraries
 * @subpackage joosDatabase
 * @author Joostina Team <info@joostina.ru>
 * @copyright (C) 2008-2011 Joostina Team
 * @license MIT License http://www.opensource.org/licenses/mit-license.php
 * Информация об авторах и лицензиях стороннего кода в составе Joostina CMS: docs/copyrights
 *
 * */
class joosModel {

	/**
	 * Название таблицы, используемой текущей моделью
	 * @var string
	 */
	protected $_tbl;
	/**
	 * Название поля первичного ключа таблицы, чаще всего ID
	 * По данному полю производится идентификация объекта, и по правильному оно должно содержать уникальное значение
	 * @var string
	 */
	protected $_tbl_key;
	/**
	 * Текст ошибки работы с активной моделью
	 * @var string
	 */
	protected $_error;
	/**
	 * Объект базы данных
	 * @var joosDatabase
	 */
	protected $_db;
	/**
	 * "Мягкое" удаление объектов БД
	 * Если в модели переопределить это значение в TRUE - то запись перед удалением будет копироваться в общесистемную корзину
	 * @var bool
	 */
	protected $_soft_delete = FALSE;

	/**
	 * Инициализация модели
	 * @param string $table название используемой таблицы, можно с преффиксом, например #__news
	 * @param string $key Название поля первичного ключа таблицы,
	 */
	public function __construct($table, $key) {
		$this->_tbl = $table;
		$this->_tbl_key = $key;
		$this->_db = joosDatabase::instance();
	}

	/**
	 * Возвращает назание текущей модели
	 * @return string
	 */
	public function classname() {
		return get_class($this);
	}

	/**
	 * Загрушка для функции получения данных о расширенных возможностях управления данными
	 * @return array
	 */
	public function get_extrainfo() {
		return array();
	}

	/**
	 * Магический метод восстановления объекта
	 * Используется при прямом кэшировании модели
	 * @param array $values - массив значений востановленного объекта
	 * @return stdClass восстановленный объект модели
	 */
	public static function __set_state(array $values) {
		// формируем объект по сохранённым параметрам
		$obj = new $values['__obj_name']($values['_tbl'], $values['_tbl_key']);
		// заполняем сохранёнными параметрами настоящие поля модели
		$obj->bind($values);

		return $obj;
	}

	/**
	 * Подготовка модели к кэшированию
	 * @return stdClass подготовленный к кэшированию объект
	 */
	public function to_cache() {
		$obj = clone $this;
		// удаляем ненужную ссылку на ресурс базы данных и стек ошибок
		unset($obj->_db, $obj->_error);
		// сохраняем оригинальное название модели
		$obj->__obj_name = get_class($obj);

		return $obj;
	}

	/**
	 * Возвращает название ключевого поя текущей модели
	 * @return string
	 */
	public function get_key_field() {
		return $this->_tbl_key;
	}

	/**
	 * Получение массива публичных свойств - полей текущей модели
	 * @staticvar string $cache статичная переменная для внутреннеего кеширования свойств
	 * @return array
	 */
	public function get_public_properties() {
		static $cache = null;

		if (is_null($cache)) {
			$cache = array();
			foreach (get_class_vars(get_class($this)) as $key => $val) {
				if (substr($key, 0, 1) != '_') {
					$cache[] = $key;
				}
			}
		}

		return $cache;
	}

	/**
	 * Очищает значения публоичных свойств модели от HTML тэгов
	 * Пример $this->filter( array('desc','extra') );
	 * @param array $ignoreList массив названий полей модели, которые НЕ требуется очистить от HTML кода
	 */
	public function filter(array $ignoreList = null) {
		$ignore = is_array($ignoreList);

		$iFilter = joosInputFilter::instance();
		foreach ($this->get_public_properties() as $k) {
			if ($ignore && in_array($k, $ignoreList)) {
				continue;
			}
			$this->$k = $iFilter->process($this->$k);
		}
	}

	/**
	 * Получение текста ошибки при работе с текущей моделью
	 * @return string
	 */
	public function get_error() {
		return $this->_error;
	}

	/**
	 * Получение значения поля
	 * @param string $_property название поля
	 * @return string значение поля
	 */
	public function get($_property) {
		return isset($this->$_property) ? $this->$_property : null;
	}

	/**
	 * Установка значения конкретного поля модели
	 * @param string $_property название модели
	 * @param string $_value значение поля для установки
	 */
	public function set($_property, $_value) {
		$this->$_property = $_value;
	}

	/**
	 * Сброс значения полей активной модели
	 * @param string $value значение, устанавливаемое во все поля активной модели
	 */
	public function reset($value = null) {
		$keys = $this->get_public_properties();
		foreach ($keys as $k) {
			$this->$k = $value;
		}
	}

	/**
	 * Заполнение значения полей модели значениями ассоциативного массива
	 * @param array $array двумерный массив "название поля"=>"значение поля"
	 * @param string $ignore название аттрибута для игнорирования
	 * @return boolean результат заполнения
	 */
	function bind(array $array, $ignore = '') {
		return $this->_db->bind_array_to_object($array, $this, $ignore);
	}

	/**
	 * Загрузка данных в модель непосредственно из БД по значению ключевого поля
	 * В случае успешного выполнения заполняет поля модели значениями из БД выбранными по ключевому полю
	 * @param mix $oid значение уникального ключевого поля, по которому необходимо делать выборку в БД
	 * @return boolean результат заполнения свойств модели
	 */
	function load($oid) {

		// сброс установок для обнуления назначенных ранее свойств объекта ( проблема с isset($obj->id) )
		$this->reset();

		$query = 'SELECT * FROM ' . $this->_tbl . ' WHERE ' . $this->_tbl_key . ' = ' . $this->_db->quote($oid);
		$result = $this->_db->set_query($query)->load_object($this);
		
		$events_name = 'model.on_load.' .  $this->classname();
		joosEvents::has_events($events_name) ? joosEvents::fire_events( $events_name ,$result, $this ) : null;

		return $result;
	}

	/**
	 * Загрузка данных в модель непосредственно из БД по значению произвольного поля
	 * В случае успешного выполнения заполняет поля модели значениями первого результата из БД выбранными по указанному
	 * @param string $field название произвольного поля модели
	 * @param string $value значение произвольного поля модели
	 * @return boolean результат заполнения свойств модели
	 */
	function load_by_field($field, $value) {

		$this->reset();

		$query = 'SELECT * FROM ' . $this->_db->name_quote($this->_tbl) . ' WHERE ' . $this->_db->name_quote($field) . ' = ' . $this->_db->quote($value);
		return $this->_db->set_query($query, 0, 1)->load_object($this);
	}

	/**
	 * Сохранение свойств модели в БД
	 * Производит непосредственно запись в БД значений заполненных полей модели. При этом сами свойства должны быть указаны ранее, методом bind, либо set, либо прямого присвоения $news->title='Новость 1'
	 * @param bool $updateNulls флаг обновления неопределённых свойств
	 * @param bool $forcedIns флаг принудительной вставки. Необходимо в случаях, когда значение ключевого поля уже задано, но всё-равно необходимо создать новую запись (например, в компоненте категорий: category_id известно, но в таблице `categories_details` нужно создать запись с этим ключом )
	 * @return boolean результат сохранения модели
	 */
	public function store($updateNulls = false, $forcedIns = false) {
		$k = $this->_tbl_key;

		$this->before_store();

		if ((isset($this->$k) && $this->$k != 0) && !$forcedIns) {
			$this->before_update();
			$ret = $this->_db->update_object($this->_tbl, $this, $this->_tbl_key, $updateNulls);
			$this->after_update();
		} else {
			$this->before_insert();
			$ret = $this->_db->insert_object($this->_tbl, $this, $this->_tbl_key);
			$this->after_insert();
		}

		if (!$ret) {
			$this->_error = $this->classname() . "::store ошибка выполнения" . $this->_db->get_error_msg();
			return false;
		} else {
			$this->after_store();
			return true;
		}
	}

	/**
	 * Переопределяемая функция проверки правильности заполнения полей модели
	 * @return boolean результат проверки
	 */
	public function check() {
		return true;
	}

	/**
	 * Метод, выполняемый до обновления значений модели
	 */
	public function before_update() {
		return true;
	}

	/**
	 * Метод, выполняемый после обновления значений модели
	 */
	public function after_update() {
		return true;
	}

	/**
	 * Метод выполняемый до добавления значений модели
	 */
	public function before_insert() {
		return true;
	}

	/**
	 * Метод выполняемый после вставки значений модели
	 */
	public function after_insert() {
		return true;
	}

	/**
	 * Метод выполняемый до сохранения значений модели ( вставка / обновление )
	 */
	public function before_store() {
		return true;
	}

	/**
	 * Метод выполняемый после полного сохранения данных модели ( вставка / обновление )
	 */
	public function after_store() {
		return true;
	}

	/**
	 * Метод выполняемый до удаления конкретной записи модели
	 */
	public function before_delete() {
		return true;
	}

	/**
	 * Метод выполняемый после удаления конкретной записи модели
	 */
	public function after_delete() {
		return true;
	}

	/**
	 * Удаление записи в БД по значению ключевого поля
	 * Производит непосредственное удаление записи из БД
	 * @param mix $oid значение ключевого поля
	 * @return boolean результат удаления
	 */
	public function delete($oid) {
		$k = $this->_tbl_key;

		if ($oid) {
			$this->$k = (int) $oid;
		}

		$this->before_delete();

		// активируем "мягкое удаление", т.е. сохраняем копию в корзине
		if ($this->_soft_delete) {
			joosTrash::add($this);
		}

		$query = "DELETE FROM $this->_tbl WHERE $this->_tbl_key = " . $this->_db->quote($this->$k);
		$this->_db->set_query($query);

		if ($this->_db->query()) {
			$this->after_delete();
			return true;
		} else {
			$this->_error = $this->_db->get_error_msg();
			return false;
		}
	}

	/**
	 * Удаление неограниченного числа записей в БД через указание массива значений ключевого, либо произвольного поля
	 * Производит непосредственное удаление записей из БД принимая массив значений вида array(1,15,16,22)
	 * @param array $oid массив значений ключевого
	 * @param string $key название ключевого поля, по умолчанию - название ключевого поля текущей модели
	 * @param string $table название таблицы, в которой необходимо произвести удаление, по умолчанию - таблица текущей модели
	 * @return boolean результат удаления записей
	 */
	public function delete_array(array $oid = array(), $key = false, $table = false) {
		$key = $key ? $key : $this->_tbl_key;
		$table = $table ? $table : $this->_tbl;

		$table = $this->_db->name_quote($table);

		// "мягкое" удаление объектов
		if ($this->_soft_delete) {

			$obj = clone $this;
			foreach ($oid as $cur_id) {
				$obj->load($cur_id);
				joosTrash::add($obj);
				$obj->reset();
			}
			unset($obj);
		}

		$obj = clone $this;
		foreach ($oid as &$cur_id) {
			$obj->{$key} = $cur_id;
			$obj->before_delete();
			$cur_id = $this->_db->quote($cur_id);
		}

		$query = "DELETE FROM $table WHERE $key IN (" . implode(',', $oid) . ')';

		if ($this->_db->set_query($query)->query()) {
			return true;
		} else {
			$this->_error = $this->_db->get_error_msg();
			return false;
		}
	}

	/**
	 * Удаление элементов в БД через указание произвольных условий
	 * @param array $params массив параметров для формирования условий удаления
	 * @return boolean результат удаления
	 */
	public function delete_list(array $params = array()) {

		$where = isset($params['where']) ? 'WHERE ' . $params['where'] . "\n" : '';

		$this->_db->set_query("DELETE FROM $this->_tbl " . $where);

		if ($this->_db->query()) {
			return true;
		} else {
			$this->_error = $this->_db->get_error_msg();
			return false;
		}
	}

	/**
	 * Копирование неограниченного числа записей в БД через указание массива значений ключевого, либо произвольного поля
	 * @param array $oid массив значений ключевого
	 * @param string $key название ключевого поля, по умолчанию - название ключевого поля текущей модели
	 * @param string $table название таблицы, в которой необходимо произвести копирование, по умолчанию - таблица текущей модели
	 * @return boolean результат копирования записей
	 */
	public function copy_array(array $oid = array(), $key = false, $table = false) {

		$key = $key ? $key : $this->_tbl_key;
		$table = $table ? $table : $this->_tbl;

		$table = $this->_db->name_quote($table);

		$query = "SELECT * FROM $table WHERE $key IN (" . implode(',', $oid) . ')';
		$rows = $this->_db->set_query($query)->load_object_list();

		foreach ($rows as $row) {
			$row->$key = null;
			$this->_db->insert_object($this->_tbl, $row, $this->_tbl_key);
		}

		return true;
	}

	/**
	 * Сохранение свойств модели в БД
	 * @param array $source массив свойств название поля=>значение поля для заполнения свойств модели ( см. self::bind )
	 * @param string $ignore название аттрибута для игнорирования
	 * @return boolean результат сохранения
	 */
	public function save(array $source, $ignore = '') {
		if ($source && !$this->bind($source, $ignore)) {
			return false;
		}
		if (!$this->check()) {
			return false;
		}
		if (!$this->store()) {
			return false;
		}

		$this->_error = '';
		return true;
	}

	/**
	 * @todo прередалать на set_state
	 * @param array $cid
	 * @param type $publish
	 * @return type 
	 */
	function publish(array $cid = null, $publish = 1) {

		joosCore::array_to_ints($cid, array());

		if (count($cid) < 1) {
			$this->_error = __('Ничего не было выбрано');
			return false;
		}

		$cids = $this->_tbl_key . '=' . implode(' OR ' . $this->_tbl_key . '=', $cid);

		$query = "UPDATE $this->_tbl SET published = " . (int) $publish . " WHERE ($cids)";

		if (!$this->_db->set_query($query)->query()) {
			$this->_error = $this->_db->get_error_msg();
			return false;
		}

		$this->_error = '';
		return true;
	}

	/**
	 * Булево изменение содержимого указанного столбца. Используется для смены статуса элемента
	 * Меняет значение указанного поля на противопложное
	 * @param string $fieldname название свойства модели для изменения на противоположное
	 * @return boolean результат смены значения поля
	 */
	public function change_state($fieldname) {
		$key = $this->{$this->_tbl_key};
		return $this->_db->set_query("UPDATE `$this->_tbl` SET `$fieldname` = !`$fieldname` WHERE $this->_tbl_key = $key", 0, 1)->query();
	}

	// TODO понять что куда и главное - зачем
	function move($dirn, $where = '') {
		$k = $this->_tbl_key;

		$sql = "SELECT $this->_tbl_key, ordering FROM $this->_tbl";

		if ($dirn < 0) {
			$sql .= "\n WHERE ordering < " . (int) $this->ordering;
			$sql .= ( $where ? ' AND ' . $where : '');
			$sql .= "\n ORDER BY ordering DESC";
			$sql .= "\n LIMIT 1";
		} else
		if ($dirn > 0) {
			$sql .= "\n WHERE ordering > " . (int) $this->ordering;
			$sql .= ( $where ? "\n AND $where" : '');
			$sql .= "\n ORDER BY ordering";
			$sql .= "\n LIMIT 1";
		} else {
			$sql .= "\nWHERE ordering = " . (int) $this->ordering;
			$sql .= ( $where ? "\n AND $where" : '');
			$sql .= "\n ORDER BY ordering";
			$sql .= "\n LIMIT 1";
		}

		$this->_db->set_query($sql);

		$row = null;
		if ($this->_db->load_object($row)) {
			$query = "UPDATE $this->_tbl SET ordering = " . (int) $row->ordering . " WHERE $this->_tbl_key = " . $this->_db->quote($this->$k);
			$this->_db->set_query($query);

			if (!$this->_db->query()) {
				$err = $this->_db->get_error_msg();
				die($err);
			}

			$query = "UPDATE $this->_tbl SET ordering = " . (int) $this->ordering . " WHERE $this->_tbl_key = " . $this->_db->quote($row->$k);
			$this->_db->set_query($query);

			if (!$this->_db->query()) {
				$err = $this->_db->get_error_msg();
				die($err);
			}

			$this->ordering = $row->ordering;
		} else {
			$query = "UPDATE $this->_tbl SET ordering = " . (int) $this->ordering . " WHERE $this->_tbl_key = " . $this->_db->quote($this->$k);
			$this->_db->set_query($query);
			if (!$this->_db->query()) {
				$err = $this->_db->get_error_msg();
				die($err);
			}
		}
	}

	/**
	 * @todo понять что это, как работает и описать как пользоваться
	 * @param type $where
	 * @return type 
	 */
	function update_order($where = '') {
		$k = $this->_tbl_key;

		if (!array_key_exists('ordering', get_class_vars(strtolower(get_class($this))))) {
			$this->_error = __("ВНИМАНИЕ: :class_name не поддерживает сортировку.", array(':class_name' => $this->classname()));
			return false;
		}

		$query = "SELECT $this->_tbl_key, ordering" . "\n FROM $this->_tbl" . ($where ? "\n WHERE $where" : '') . "\n ORDER BY ordering";
		$this->_db->set_query($query);
		if (!($orders = $this->_db->load_object_list())) {
			$this->_error = $this->_db->get_error_msg();
			return false;
		}

		for ($i = 0, $n = count($orders); $i < $n; $i++) {
			if ($orders[$i]->ordering >= 0) {
				$orders[$i]->ordering = $i + 1;
			}
		}

		$shift = 0;
		$n = count($orders);
		for ($i = 0; $i < $n; $i++) {
			if ($orders[$i]->$k == $this->$k) {
				$orders[$i]->ordering = min($this->ordering, $n);
				$shift = 1;
			} else
			if ($orders[$i]->ordering >= $this->ordering && $this->ordering > 0) {
				$orders[$i]->ordering++;
			}
		}

		for ($i = 0, $n = count($orders); $i < $n; $i++) {
			if ($orders[$i]->ordering >= 0) {
				$orders[$i]->ordering = $i + 1;
				$query = "UPDATE $this->_tbl" . "\n SET ordering = " . (int) $orders[$i]->ordering . "\n WHERE $k = " . $this->_db->quote($orders[$i]->$k);
				$this->_db->set_query($query);
			}
		}

		if ($shift == 0) {
			$order = $n + 1;
			$query = "UPDATE $this->_tbl" . "\n SET ordering = " . (int) $order . "\n WHERE $k = " . $this->_db->quote($this->$k);
			$this->_db->set_query($query);
		}
		return true;
	}

	/**
	 * Возвращает число записей в таблице БД активной модели
	 * @param string $where дополнительное условие для подсчета числа записей, например "WHERE state=1"
	 * @return int число записей
	 */
	public function count($where = '') {
		$sql = "SELECT count(*) FROM $this->_tbl " . $where;
		return $this->_db->set_query($sql)->load_result();
	}

	/**
	 * Возвращает массив результатов выборки
	 * @param array $params массив параметров для уточнее области выборки результата
	 *     <pre>
	 *         select - список поле для выборки, по умолчанию * (все поля)
	 *         where - условие WHERE для выборки
	 *         join - данные о объединённой выборке с использованием сторонних таблиц
	 *         group - название поля для группировки результата, пример - " user_id "
	 *         order - название поля и направление сортировки результата, пример - " id DESC ", либо "id DESC, title ASC"
	 *         offset - смещение для выборки результата, по умолчанию - 0
	 *         limit - лимит выборки для результата, по молчанию - 0, т.е. ВСЕ записи
	 *         key - название ключевого поля, для использования в качестве ключа ассоциативного массива результатов. По умолчанию использует ключевое поле модели. key=>FALSE если необходимо сделать простой массив ( 0=>array(),1=>array() )
	 *     <pre>
	 * @return array ассоциативный или обычный массив результатов
	 */
	public function get_list(array $params = array()) {

		$select = isset($params['select']) ? $params['select'] . "\n" : '*';
		$where = isset($params['where']) ? 'WHERE ' . $params['where'] . "\n" : '';
		$join = isset($params['join']) ? $params['join'] . "\n" : '';
		$group = isset($params['group']) ? 'GROUP BY ' . $params['group'] . "\n" : '';
		$order = isset($params['order']) ? 'ORDER BY ' . $params['order'] . "\n" : '';
		$offset = isset($params['offset']) ? $params['offset'] . "\n" : 0;
		$limit = isset($params['limit']) ? $params['limit'] . "\n" : 0;

		$tbl_key = isset($params['key']) ? $params['key'] : $this->_tbl_key;

		$pseudonim = isset($params['pseudonim']) ? ' AS ' . $params['pseudonim'] . ' ' : '';

		return $this->_db->set_query("SELECT $select FROM $this->_tbl $pseudonim $join " . $where . $group . $order, $offset, $limit)->load_object_list($tbl_key);
	}

	/**
	 * Возвращает ассоциативный двумерный массив возможных значений модели
	 * @param array $key_val - массив array( 'key'=>'название поля - ключа','value'=>'название поля - значения' ). По умолчанияю key=>id, value=>title
	 * @param array $params массив параметров для уточнее области выборки результата
	 *     <pre>
	 *         select - список поле для выборки, по умолчанию key,value (поля указанные в $key_val)
	 *         where - условие WHERE для выборки
	 *         order - название поля и направление сортировки результата, пример - " id DESC ", либо "id DESC, title ASC"
	 *         offset - смещение для выборки результата, по умолчанию - 0
	 *         limit - лимит выборки для результата, по молчанию - 0, т.е. ВСЕ записи
	 *         table - название таблицы, из которой необходимо сделать выборку. По умолчанию - таблица текущей модели
	 *     <pre>
	 * @return array - ассоциативный массив результата
	 */
	public function get_selector(array $key_val = array(), array $params = array()) {

		$key = isset($key_val['key']) ? $key_val['key'] : 'id';
		$value = isset($key_val['value']) ? $key_val['value'] : 'title';

		$select = isset($params['select']) ? $params['select'] : $key . ',' . $value;
		$where = isset($params['where']) ? 'WHERE ' . $params['where'] : '';
		$order = isset($params['order']) ? ' ORDER BY ' . $params['order'] : '';
		$offset = isset($params['offset']) ? (int) $params['offset'] : 0;
		$limit = isset($params['limit']) ? (int) $params['limit'] : 0;
		$tablename = isset($params['table']) ? $params['table'] : $this->_tbl;

		$opts = $this->_db->set_query("SELECT $select FROM $tablename " . $where . $order, $offset, $limit)->load_assoc_list();

		$return = array();
		foreach ($opts as $opt) {
			$return[$opt[$key]] = $opt[$value];
		}

		return $return;
	}

// отношение один-ко-многим, список выбранных значений из многих
	public function get_select_one_to_many($table_values, $table_keys, $key_parent, $key_children, array $params = array()) {

		$select = isset($params['select']) ? $params['select'] : 't_val.*';
		$where = isset($params['where']) ? 'WHERE ' . $params['where'] : "WHERE t_key.$key_parent = $this->id ";
		$order = isset($params['order']) ? 'ORDER BY ' . $params['order'] : '';
		$offset = isset($params['offset']) ? intval($params['offset']) : 0;
		$limit = isset($params['limit']) ? intval($params['limit']) : 0;
		$join = isset($params['join']) ? intval($params['join']) : 'LEFT JOIN';

		$sql = "SELECT $select FROM $table_values AS t_val $join $table_keys AS  t_key ON t_val.id=t_key.$key_children $where ";
		return $this->_db->set_query($sql, $offset, $limit)->load_assoc_list('id');
	}

// сохранение значение одного ко многим
	public function save_one_to_many($name_table_keys, $key_name, $value_name, $key_value, $values) {

		//сначала чистим все предыдущие связи
		$this->_db->set_query("DELETE FROM $name_table_keys WHERE $key_name=$key_value ")->query();

		// фомируем массив сохраняемых значений
		$vals = array();
		foreach ($values as $value) {
			$vals[] = " ($key_value, $value  ) ";
		}

		$values = implode(',', $vals);

		$sql = "INSERT IGNORE INTO $name_table_keys ( $key_name,$value_name ) VALUES $values";
		return $this->_db->set_query($sql)->query();
	}

// селектор выбора отношений один-ко-многим
	public function get_one_to_many_selectors($name, $table_values, $table_keys, $key_parent, $key_children, array $selected_ids, array $params = array()) {

		$params['select'] = isset($params['select']) ? $params['select'] : 't_val.id, t_val.title';

		$params['wrap_start'] = isset($params['wrap_start']) ? $params['wrap_start'] : '';
		$params['wrap_end'] = isset($params['wrap_end']) ? $params['wrap_end'] : '';

		$childrens = $this->get_selector(array(), array('table' => $table_values));

		$rets = array();
		foreach ($childrens as $key => $value) {
			$el_id = $name . $key;
			$checked = (bool) isset($selected_ids[$key]);
			$rets[] = $params['wrap_start'] . forms::checkbox($name . '[]', $key, $checked, 'id="' . $el_id . '" ');
			$rets[] = forms::label($el_id, $value) . $params['wrap_end'];
		}

		return implode("\n\t", $rets);
	}

	/**
	 * Загрузка значение текущей модели через указание произвольных свойств модели
	 * @param array $params массив параметров для условий выборки
	 * @return boolean результат поиска и загрузки значений в свойства текущей модели
	 */
	public function find(array $params = array('select' => '*')) {
		$fmtsql = "SELECT {$params['select']} FROM $this->_tbl WHERE %s";
		$tmp = array();
		foreach (get_object_vars($this) as $k => $v) {

			if (is_array($v) or is_object($v) or $k[0] == '_' or empty($v)) {
				continue;
			}
			if ($v == '') {
				$val = "''";
			} else {
				$val = $this->_db->quote($v);
			}
			$tmp[] = $this->_db->name_quote($k) . '=' . $val;
		}
		return $this->_db->set_query(sprintf($fmtsql, implode(' AND ', $tmp)))->load_object($this);
	}

	/**
	 * Поиск записей, удовлетворяющих указаным свойствам объекта
	 * @param array $params массив параметров для уточнения области поиска записей
	 *     <pre>
	 *         select - список поле для выборки, по умолчанию * (все поля)
	 *         where - условие WHERE для выборки
	 *         order - название поля и направление сортировки результата, пример - " id DESC ", либо "id DESC, title ASC"
	 *         offset - смещение для выборки результата, по умолчанию - 0
	 *         limit - лимит выборки для результата, по молчанию - 0, т.е. ВСЕ записи
	 *         key - название ключевого поля, для использования в качестве ключа ассоциативного массива результатов. По умолчанию использует ключевое поле модели. key=>FALSE если необходимо сделать простой массив ( 0=>array(),1=>array() )
	 *     <pre>
	 * @return array ассоциативный массив результата поиска
	 */
	public function find_all(array $params = array()) {
		$def_param = array('select' => '*');
		$params += $def_param;
		$fmtsql = "SELECT {$params['select']} FROM $this->_tbl WHERE %s";
		$fmtsql .= isset($params['order']) ? ' ORDER BY ' . $params['order'] : '';

		$tmp = array();

		if (isset($params['where'])) {
			$tmp[] = $params['where'];
		}

		foreach (get_object_vars($this) as $k => $v) {

			if (is_array($v) or is_object($v) or $k[0] == '_' or empty($v)) {
				continue;
			}
			if ($v == '') {
				$val = "''";
			} else {
				$val = $this->_db->quote($v);
			}
			$tmp[] = $this->_db->name_quote($k) . '=' . $val;
		}
		$tmp = count($tmp) > 0 ? $tmp : array('true');

		$offset = isset($params['offset']) ? intval($params['offset']) : 0;
		$limit = isset($params['limit']) ? intval($params['limit']) : 0;

		$tbl_key = isset($params['key']) ? $params['key'] : $this->_tbl_key;

		return $this->_db->set_query(sprintf($fmtsql, implode(' AND ', $tmp)), $offset, $limit)->load_object_list($tbl_key);
	}

	/**
	 * Возвращает максимальное значение по заданному полю
	 * @param string $name Иимя поля
	 * @return integer максимальное значение
	 */
	function get_max_by_field($name) {
		$query = 'SELECT  ' . $name . ' AS max FROM ' . $this->_tbl . ' ORDER BY  ' . $name . ' DESC';
		return $this->_db->set_query($query)->load_result();
	}

}