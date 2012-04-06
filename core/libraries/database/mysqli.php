<?php

// запрет прямого доступа
defined('_JOOS_CORE') or die();

/**
 * Библиотека работы с базой данных Mysql через Mysqli
 * Системная библиотека
 *
 * @version    1.0
 * @package    Libraries
 * @subpackage Libraries
 * @subpackage joosDatabase
 * @category   Libraries
 * @author     Joostina Team <info@joostina.ru>
 * @copyright  (C) 2007-2012 Joostina Team
 * @license    MIT License http://www.opensource.org/licenses/mit-license.php
 * Информация об авторах и лицензиях стороннего кода в составе Joostina CMS: docs/copyrights
 *
 * */
class joosDatabaseMysqli implements joosInterfaceDatabase{

	/**
	 * Объект активного соединения с базой данных
	 *
	 * @var joosDatabase
	 */
	private static $instance;

	/**
	 * Переменныя хранения активной или готовящейся к выполнению SQL команды
	 *
	 * @var string
	 */
	protected $_sql;

	/**
	 * Код ошибки работы с базой данных
	 *
	 * @var int
	 */
	protected $_error_num = 0;

	/**
	 * Текст ошибки работы с базой данных
	 *
	 * @var string
	 */
	protected $_error_msg;

	/**
	 * Префикс таблиц активного соединения
	 *
	 * @var string
	 */
	protected $_table_prefix = 'jos_';

	/**
	 * Ресурс активного соединения с базой данных
	 *
	 * @var res
	 */
	protected $_resource;

	/**
	 * Результат последнего активного SQL запроса
	 *
	 * @var
	 */
	protected $_cursor;

	/**
	 * Лимит для активного запроса
	 *
	 * @var int
	 */
	protected $_limit;

	/**
	 * Смещение для активного запроса
	 *
	 * @var int
	 */
	protected $_offset;

	/**
	 * Конструктор открывающий соединение с базой данных
	 *
	 * @param string  $host   - хост базы данных, обычно localhost
	 * @param string  $user   - имя пользователя базы данных
	 * @param string  $pass   - пароль соединения с базой данных
	 * @param string  $db     - название базы данных
	 * @param int     $port   - порт сервера MySQL
	 * @param string  $socket - сокет MySQL
	 */
	protected function __construct($host = 'localhost', $user = 'root', $pass = '', $db = '', $port = null, $socket = null) {

		// проверка доступности поддержки работы с базой данных в php
		if (!function_exists('mysqli_connect')) {
            joosPages::error_database('Нет поддержки mysql');
		}

		// попытка соединиться с сервером баз данных
		if (!( $this->_resource = mysqli_connect($host, $user, $pass, $db, $port, $socket) )) {
            joosPages::error_database('Ошибка соединения с БД');
        }

		// при активации отладки выполнение дополнительных запросов профилирования
		if ( JDEBUG ) {
			mysqli_query($this->_resource, 'set profiling=1');
			mysqli_query($this->_resource, sprintf('set profiling_history_size=%s', joosConfig::get2('db', 'profiling_history_size', 100)));
		}

		// устанавливаем кодировку для корректного соединения с сервером базы данных
		mysqli_set_charset($this->_resource, 'utf8');
	}

	/**
	 * Уничтожение объекта
	 * При уничтожении объекта происходит закрытие соединения с базой
	 *
	 */
	public function __destruct() {
		if (is_resource($this->_resource)) {
			// TODO это убрать при постоянных соединениях
			mysqli_close($this->_resource);
		}
	}

	/**
	 * Получение инстанции для работы с базой данных
	 * @return joosDatabaseMysqli - объект базы данных
	 */
	public static function instance() {

		// отметка получения инстенции базы данных
		JDEBUG ? joosDebug::inc('joosDatabaseMysqli::instance()') : null;

		if (self::$instance === NULL) {
			$db_config = joosConfig::get('db');
			$database = new self($db_config['host'], $db_config['user'], $db_config['password'], $db_config['name']);

			if ($database->_error_num ) {
                $error_message = $database->_error_msg;
                joosPages::error_database( $error_message );
			}
			self::$instance = $database;
		}
		return self::$instance;
	}

	/**
	 * Закрытый метод для предотвращения клонирования объекта базы данных
     * 
     * @todo исправить, метод CLONE используется при кешированиии и сериалзации модели
	 */
	public function __clone() {

	}

	/**
	 * Экранирование элементов
	 *
	 * @param string  $text  - значение для экранирования
	 * @param boolean $extra - дополнительная обработка элемента
	 *
	 * @return string
	 */
	public function get_escaped($text, $extra = false) {
		$string = mysqli_real_escape_string($this->_resource, $text);
		return $extra ? addcslashes($string, '%_') : $string;
	}

	/**
	 * Квотирование элемента
	 *
	 * @param string  $text    - значение для квотирования
	 * @param boolean $escaped - параметр расширенного квотирования
	 *
	 * @return string обработанный результат
	 */
	public function quote($text, $escaped = true) {
		return '\'' . ( $escaped ? $this->get_escaped($text) : $text ) . '\'';
	}

	/**
	 * Квотирование элементов спецсиволами
	 * Используется для обрамления названий таблиц и полей базы данных в SQL запросах
	 *
	 * @param string $s встрока для квотирования
	 *
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
	 * Установка префикса таблиц базы данных
	 *
	 * @param string $prefix
	 */
	public function set_prefix($prefix) {
		$this->_table_prefix = $prefix;
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
	 *
	 * @param string $sql    текст sql запроса для выполнения
	 * @param int    $offset значения смещения для результато ввыборки
	 * @param int    $limit  ограничение н ачисло выбираемых объектов
	 *
	 * @return joosDatabaseMysqli
	 */
	public function set_query($sql, $offset = 0, $limit = 0) {
		$this->_sql = $this->replace_prefix($sql);
		$this->_limit = (int) $limit;
		$this->_offset = (int) $offset;
		return $this;
	}

	/**
	 * Замена преффикса таблиц базы данных
	 *
	 * @param string $sql текст sql запроса для замены преффикса
	 *
	 * @return string sql с заменённым преффиксом
	 */
	private function replace_prefix($sql) {
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

			throw new joosDatabaseException('Ошибка выполнения SQL #:error_num <br /> :error_message.<br /><br /> Ошибка в команде: :sql',
					array(
						':error_num' => mysqli_errno($this->_resource),
						':error_message' => mysqli_error($this->_resource),
						':sql' => $this->_sql)
			);
            
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
	 * Возвращает один (первый) результат выполненного запроса
	 * @return string строка результата
	 */
	public function load_result() {

		// TODO, логично, но спорно
		$this->_limit = 1;
		$this->_offset = 0;

		$cur = $this->query();


		$ret = ( $row = mysqli_fetch_row($cur) ) ? $row[0] : null;

		$this->free_result();

		return $ret;
	}

	/**
	 * Возвращает результат запроса в виде массива. Массив содержит значения столбца под номером указанным в $numinarray
	 *
	 * @param int $numinarray номер столбца для отобрадения в результуриющем запросе. 0 - первый столбцев, 1 - второй столбец и т.д
	 *
	 * @return array массив результата
	 */
	public function load_result_array($numinarray = 0) {
		
        $cur = $this->query();

		$array = array();
		while ($row = mysqli_fetch_row($cur)) {
			$array[] = $row[$numinarray];
		}

		$this->free_result();

		return $array;
	}

	/**
	 * Возвращаем массив результата запроса. Каждый результирующий столбец хранится как массив массива, начиная со позиции 0.
	 * Может возвращать ассоциативный массив гд еключем выступает значение поля указанное в параметре $key
	 *
	 * @param string $key поле выступающее в качестве ключа для ассоциативного массива результата
	 *
	 * @return array ассоциативнй либо обычный массив массивов результата
	 */
	public function load_assoc_list($key = '') {

        $cur = $this->query();

        $array = array();
		while ($row = mysqli_fetch_assoc($cur)) {
			if ($key) {
				$array[$row[$key]] = $row;
			} else {
				$array[] = $row;
			}
		}

		$this->free_result();

		return $array;
	}

	/**
	 * Возвращает первый результат запроса в виде ассоциативного массива название поля - значение
	 * @return array ассоциативный массив результата
	 */
	public function load_assoc_row() {

        $cur = $this->query();

        $row = mysqli_fetch_assoc($cur);

		$this->free_result();

		return $row;
	}

	/**
	 * Загружает результат запроса в принимаемы в качестве параметра объект
	 *
	 * @param joosModel|stdClass $object объект для загрузки результата
	 *
	 * @return bool результат сбора результата в значения полей принимаемого объекта
	 */
	public function load_object(& $object) {
		if ($object != null) {

            $cur = $this->query();

            if (( $array = (array) mysqli_fetch_assoc($cur))) {
				$this->free_result();
				$this->bind_array_to_object($array, $object, null, null, false);
				return true;
			} else {
				return false;
			}
		} else {
			if (( $cur = $this->query())) {
				if (( $object = mysqli_fetch_object($cur))) {
					$this->free_result();
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
	 *
	 * @param boolean|string $key поле выступающее в качестве ключа для ассоциативного массива результата
	 *
	 * @return array ассоциативный или обычный массив результатов
	 */
	public function load_object_list($key = false) {

        $cur = $this->query();

        $array = array();
		while ($row = mysqli_fetch_object($cur)) {
			if ($key) {
				$array[$row->$key] = $row;
			} else {
				$array[] = $row;
			}
		}

		$this->free_result();

		return $array;
	}

    /**
     * Версия load_object_list работающая с кешем
     *
     * @param boolean|string $key поле выступающее в качестве ключа для ассоциативного массива результата
     * @param int $cache_time Время жизни кэша
     *
     * @return array ассоциативный или обычный массив результатов
     */
    public function load_object_list_cache($key = false,$cache_time = 86400 ) {

        $cache = new joosCache();
        $cache_key = md5($this->_sql);

        if (($value = $cache->get($cache_key)) === NULL) {

            if (!( $cur = $this->query() )) {
                return null;
            }

            $value = array();
            while ($row = mysqli_fetch_object($cur)) {
                if ($key) {
                    $value[$row->$key] = $row;
                } else {
                    $value[] = $row;
                }
            }

            $this->free_result();

            $cache->set($cache_key, $value, $cache_time);
        }
       


        return $value;
    }    
    
	/**
	 * Возвращает массив результата запроса, в котором в качестве значений выступают значения полей первого результата
	 * @return array массив значение полей первого результата
	 */
	public function load_row() {

        $cur = $this->query();

        $ret = ( $row = mysqli_fetch_row($cur) ) ? $row : null;

		$this->free_result();

		return $ret;
	}

	/**
	 * Возвращает ассоциативный массив результата запроса.
	 * В качестве ключей массива результата может быть использовано номер поля указанного в $key
	 *
	 * @param int $key номер поля начиная с 0, значение которого необходимо использовать  вкачестве ключа для ассициативного массива результата
	 *
	 * @return array ассоциативный или обычный массив результатов
	 */
	public function load_row_list($key = false) {

        $cur = $this->query();

        $array = array();

		while ($row = mysqli_fetch_row($cur)) {
			if ($key!==false) {
				$array[$row[$key]] = $row;
			} else {
				$array[] = $row;
			}
		}

		$this->free_result();

		return $array;
	}

	/**
	 * Возвращает ассоциативный массив результата, ключами которого являются значения поля $key, а значениями - значения поля $value
	 *
	 * @param string $key   название поля для ключа результирующего массива
	 * @param string $value названи еполя для значения результирующего массива
	 *
	 * @return array ассоциативнй массив ключ=>значение результата
	 */
	public function load_row_array($key, $value) {

        $cur = $this->query();

        $array = array();
		while ($row = mysqli_fetch_object($cur)) {
			$array[$row->$key] = $row->$value;
		}

		$this->free_result();

		return $array;
	}

	/**
	 * Вставка записи.
	 * Работает с объектами, свойства которых являются названиями поле в базе, а значения свойств - значениями полей
	 * Работает ТОЛЬКО через joosDatabaseMysqli::instance()->insert_object
	 *
	 * @param string   $table   название таблицы, можно с преффиксом #__
	 * @param stdClass $object  объект с заполненными свойствами
	 * @param string   $keyName название ключевого автоинскриментного поля таблицы
	 *
	 * @return int идентификатор вставленной записи, истину или ложь если операция провалилась
	 */
	public function insert_object($table, $object, $keyName = null) {

		$ignore = isset($object->__ignore) ? ' IGNORE ' : '';
		unset($object->__ignore);

		$fmtsql = "INSERT $ignore INTO $table ( %s ) VALUES ( %s ) ";

		$fields = array();
		$values = array();
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

		return ( $id > 0 ) ? $id : true;
	}

	public function insert_array($table, $object, array $values_array) {

		$ignore = isset($object->__ignore) ? ' IGNORE ' : '';
		unset($object->__ignore);

		$fmtsql = "INSERT $ignore INTO $table ( %s ) VALUES %s ";

		$fields = array();
		$n = 0;
		$values = array();
		foreach (get_object_vars($object) as $k => $v) {

			if (is_array($v) or is_object($v)) {
				continue;
			}

			if ($k[0] == '_') {
				continue;
			}

			$fields[] = $this->name_quote($k);
			foreach ($values_array as $key => $value) {
				$values[$key][$n] = ( isset($value[$k]) && $v == null ) ? $this->quote($value[$k]) : ( ( $v != null ) ? $this->quote($v) : 'NULL' );
				++$n;
			}
		}

		array_walk($values, function( &$d ) {
					$d = ' (' . implode(",", $d) . ') ';
				});

		$this->set_query(sprintf($fmtsql, implode(",", $fields), implode(",", $values)));

		return $this->query() ? true : false;
	}

	/**
	 * Обновление записи.
	 * Работает с объектами, свойства которых являются названиями поле в базе, а значения свойств - значениями полей
	 *
	 * @param string   $table       название таблицы, можно с преффиксом #__
	 * @param stdClass $object      объект с заполненными свойствами
	 * @param string   $key_name     название ключевого автоинскриментного поля таблицы
	 * @param bool     $update_nulls флаг обновления неопределённых свойств
	 *
	 * @return bool результат обновления данных записи
	 */
	public function update_object($table, $object, $key_name, $update_nulls = true) {

		$fmtsql = "UPDATE $table SET %s  WHERE %s";
		$tmp = array();
		$where = '';
		foreach (get_object_vars($object) as $k => $v) {
			if (is_array($v) or is_object($v) or $k[0] == '_') { // internal or NA field
				continue;
			}
			if ($k == $key_name) { // PK not to be updated
				$where = $key_name . '=' . $this->quote($v);
				continue;
			}
			if ($v === null && !$update_nulls) {
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
	 * Возвращает ID-номер, сгенерированный для столбца AUTO_INCREMENT предыдущим запросом INSERT
	 * @return int
	 */
	public function insert_id() {
		return mysqli_insert_id($this->_resource);
	}

	/**
	 * Возвращаем объект с утилитарными функциями работы с базой данных
	 * @return joosDatabaseMysqliUtils
	 */
	public function get_utils() {
		return new joosDatabaseMysqliUtils($this);
	}

	/**
	 * Преобразование массива в объект
	 *
	 * @param array  $array        исходный массив ключ=>значение
	 * @param object $obj          объект, свойства которого будут заполнены значениями сообтветсвующих ключей массива
	 * @param string $ignore       свойства объекта которые следует игнорировать, через пробел ('id title slug')
	 * @param string $prefix       префикс полей массива. Например в объекте title, а в массивe blog_title
	 * @param bool   $checkSlashes флаг экранизации значений через addslashes
	 *
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
	 * @tutorial joosDatabaseMysqli::models('modelUsers')->count()
	 * @tutorial joosDatabaseMysqli::model('Blog')->get_list( array('where'=>'sate=1') )
	 * @tutorial joosDatabaseMysqli::model('Blog')->save( $_POST )
	 *
	 * @param string $model_name
	 *
	 * @return joosModel объект выбранной модели
	 */
	public static function models($model_name) {
		return new $model_name;
	}

	/**
	 * Очистка буфера mysqli
	 */
	private function free_result(){;
		 !JDEBUG ? mysqli_free_result( $this->_cursor ) : null;
	}

}

/**
 * Библиотека утилитарных функций работы с базой данных
 *
 * @version    1.0
 * @package    Libraries
 * @subpackage Libraries
 * @subpackage joosDatabase
 * @author     Joostina Team <info@joostina.ru>
 * @copyright  (C) 2007-2012 Joostina Team
 * @license    MIT License http://www.opensource.org/licenses/mit-license.php
 * Информация об авторах и лицензиях стороннего кода в составе Joostina CMS: docs/copyrights
 *
 * */
class joosDatabaseMysqliUtils extends joosDatabaseMysqli implements joosInterfaceDatabaseUtils {

	/**
	 *
	 * @var
	 */
	private $_db;

	/**
	 * Объект работы с базой данных
	 *
	 * @param joosDatabaseMysqli $db
	 */
	public function __construct(joosDatabaseMysqli $db) {
		$this->_db = $db;
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
	 *
	 * @param bool $only_joostina флаг позволяющий оставить в результирующем наборе только таблицы текущего сайта
	 *
	 * @return array массив таблиц текущей базы данных
	 */
	public function get_table_list($only_joostina = true) {
		$only_joostina = $only_joostina ? " LIKE '" . $this->_db->_table_prefix . "%' " : '';
		return $this->_db->set_query('SHOW TABLES ' . $only_joostina)->load_result_array();
	}

	/**
	 * Возвращает ассоциативный массив структур таблиц
	 *
	 * @param array $tables массив таблиц структуру которых необходимо получить
	 *
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
	 *
	 * @param string $tables название таблицы
	 *
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

}