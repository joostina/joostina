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

/**
 * Класс работы с базой данных
 * @subpackage Database
 * @package Joostina
 */
class database {

    /**
     * Объект активного соединения с базой данных
     * @var database
     */
    protected static $_db_instance;
    /**
     * Переменныя хранения активной или готовящейся к выполнению SQL команды
     * @var string
     */
    protected $_sql;
    /**
     * Код ошибки работы с базой данных
     * @var int
     */
    protected $_errorNum = 0;
    /**
     * Текст ошибки работы с базой данных
     *  @var string
     */
    protected $_errorMsg;
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
     * Строка для поля типа даты
     * @var string null/zero
     */
    protected $_nullDate = '0000-00-00 00:00:00';
    /**
     * символ квотирования названия полей таблиц
     * @var string
     */
    protected $_nameQuote = '`';

    /**
     * Конструктор открывающий соединение с базой данных
     * @param string $host - хост базы данных, обычно localhost
     * @param string $user - имя пользователя базы данных
     * @param string $pass - пароль соединения с базой данных
     * @param string $db - название базы данных
     * @param string $table_prefix - преффикс таблиц базы данных
     * @param boolean $goOffline - возможность отображения страницы недоступности сайта при проблемах в работе базы данных
     * @param boolean $debug - активность отладки рабюоты с базой данных
     */
    function __construct($host = 'localhost', $user = 'root', $pass = '', $db = '', $table_prefix = '', $goOffline = true, $debug = 0) {
        $this->_debug = $debug;
        $this->_table_prefix = $table_prefix;

        // проверка доступности поддержки работы с базой данных в php
        if (!function_exists('mysql_connect')) {
            $mosSystemError = 1;
            if ($goOffline) {
                include JPATH_BASE . '/templates/system/offline.php';
                exit();
            }
        }

        // попытка соединиться с сервером баз данных
        if (!($this->_resource = @mysql_connect($host, $user, $pass, true))) {
            $mosSystemError = 2;
            if ($goOffline) {
                include JPATH_BASE . '/templates/system/offline.php';
                exit();
            }
        }

        // попытка выбрать используемую базы данных
        if ($db != '' && !mysql_select_db($db, $this->_resource)) {
            $mosSystemError = 3;
            if ($goOffline) {
                include JPATH_BASE . '/templates/system/offline.php';
                exit();
            }
        }

        // при активации отладки выполнение дополнительных запросов профилирования
        if ($this->_debug == 1) {
            mysql_query('set profiling=1', $this->_resource);
            mysql_query('set profiling_history_size=150', $this->_resource);
        };

        // устанавливаем верное соединение с сервером базы данных
        mysql_set_charset('utf8');
    }

    /**
     * Получение инстанции для работы с базой данных
     * @return database - объект базы данных
     */
    public static function getInstance() {

        // отметка получения инстенции базы данных
        JDEBUG ? jd_inc('database::getInstance()') : null;

        if (self::$_db_instance === NULL) {
            $config = Jconfig::getInstance();

            $database = new database($config->config_host, $config->config_user, $config->config_password, $config->config_db, $config->config_dbprefix, true, JDEBUG);
            if ($database->getErrorNum()) {
                $mosSystemError = $database->getErrorNum();
                include JPATH_BASE . DS . 'templates/system/offline.php';
                exit();
            }
            self::$_db_instance = $database;
        }
        return self::$_db_instance;
    }

    /**
     * Закрытый метод для предотвращения клонирования объекта базы данных
     */
    public function __clone() {

    }

    /**
     * Установка пааметра отладки базы данных
     * @param boolean $level
     */
    public function debug($debug) {
        $this->_debug = intval($debug);
    }

    /**
     * Получение кода ошибки
     * @return int
     */
    public function getErrorNum() {
        return $this->_errorNum;
    }

    /**
     * Получение сообщения об ошибке в работе с базой данных
     * @return string
     */
    public function getErrorMsg() {
        return str_replace(array("\n", "'"), array('\n', "\'"), $this->_errorMsg);
    }

    /**
     * Экранирование элементов
     * @param string $text - значение для экранирования
     * @param boolean $extra - дополнительная обработка элемента
     * @return <type>
     */
    public function getEscaped($text, $extra = false) {
        $string = mysql_real_escape_string($text, $this->_resource);
        return $extra ? addcslashes($string, '%_') : $string;
    }

    /**
     * Квотирование элемента
     * @param string $text - значение для квотирования
     * @param boolean $escaped - параметр расширенного квотирования
     * @return string
     */
    public function Quote($text, $escaped = true) {
        return '\'' . ($escaped ? $this->getEscaped($text) : $text) . '\'';
    }

    /**
     * Квотирование элементов спецсиволами
     * @param string $s
     * @return string
     */
    public function NameQuote($s) {
        $q = $this->_nameQuote;
        return (strlen($q) == 1) ? $q . $s . $q : $q{0} . $s . $q{1};
    }

    /**
     *
     * @return <type>
     */
    public function getPrefix() {
        return $this->_table_prefix;
    }

    /**
     *
     * @return <type>
     */
    public function getNullDate() {
        return $this->_nullDate;
    }

    /**
     *
     * @param <type> $sql
     * @param <type> $offset
     * @param <type> $limit
     * @param <type> $prefix
     * @return <type>
     */
    public function setQuery($sql, $offset = 0, $limit = 0, $prefix = '#__') {
        $this->_sql = $this->replacePrefix(trim($sql), $prefix);
        $this->_limit = intval($limit);
        $this->_offset = intval($offset);
        return $this;
    }

    /**
     *
     * @param <type> $sql
     * @param <type> $prefix
     * @return <type>
     */
    private function replacePrefix($sql, $prefix = '#__') {
        return str_replace('#__', $this->_table_prefix, $sql);
    }

    /**
     *
     * @return <type>
     */
    public function getResource() {
        return $this->_resource;
    }

    /**
     *
     * @return <type>
     */
    public function getQuery() {
        return '<pre>' . htmlspecialchars($this->_sql) . '</pre>';
    }

    /**
     *
     * @return <type>
     */
    public function query() {
        if ($this->_limit > 0 && $this->_offset == 0) {
            $this->_sql .= "\nLIMIT $this->_limit";
        } elseif ($this->_limit > 0 || $this->_offset > 0) {
            $this->_sql .= "\nLIMIT $this->_offset, $this->_limit";
        }

        $this->_errorNum = 0;
        $this->_errorMsg = '';
        $this->_cursor = mysql_query($this->_sql, $this->_resource);
        // для оптимизации расхода памяти можно раскомментировать следующие строки, но некоторые особенно кривые расширения сразу же отвалятся
        //unset($this->_sql);
        //return $this->_cursor;
        // /*
        if (!$this->_cursor) {
            $this->_errorNum = mysql_errno($this->_resource);
            $this->_errorMsg = mysql_error($this->_resource) . " SQL=$this->_sql";
            if ($this->_debug) {
                $this->getUtils()->show_db_error(mysql_error($this->_resource), $this->_sql);
            }
            return false;
        }

        // тут тоже раскомментировать, что бу верхнее условие оказалось в комментариях, или еще лучше его вообще удалить
        //*/
        return $this->_cursor;
    }

    /**
     *
     * @return <type>
     */
    public function getAffectedRows() {
        return mysql_affected_rows($this->_resource);
    }

    /**
     *
     * @param <type> $cur
     * @return <type>
     */
    public function getNumRows($cur = null) {
        return mysql_num_rows($cur ? $cur : $this->_cursor);
    }

    /**
     *
     * @return <type>
     */
    public function loadResult() {
        if (!($cur = $this->query())) {
            return null;
        }

        $ret = ($row = mysql_fetch_row($cur)) ? $row[0] : null;

        mysql_free_result($cur);
        return $ret;
    }

    /**
     *
     * @param <type> $numinarray
     * @return <type>
     */
    public function loadResultArray($numinarray = 0) {
        if (!($cur = $this->query())) {
            return null;
        }
        $array = array();
        while ($row = mysql_fetch_row($cur)) {
            $array[] = $row[$numinarray];
        }
        mysql_free_result($cur);
        return $array;
    }

    /**
     *
     * @param <type> $key
     * @return <type>
     */
    public function loadAssocList($key = '') {
        if (!($cur = $this->query())) {
            return null;
        }
        $array = array();
        while ($row = mysql_fetch_assoc($cur)) {
            if ($key) {
                $array[$row[$key]] = $row;
            } else {
                $array[] = $row;
            }
        }
        mysql_free_result($cur);

        return $array;
    }

    /**
     *
     * @return <type>
     */
    public function loadAssocRow() {
        if (!($cur = $this->query())) {
            return null;
        }
        $row = mysql_fetch_assoc($cur);
        mysql_free_result($cur);

        return $row;
    }

    /**
     *
     * @param <type> $object
     * @return <type>
     */
    public function loadObject(& $object) {
        if ($object != null) {
            if (!($cur = $this->query())) {
                return false;
            }
            if ($array = mysql_fetch_assoc($cur)) {
                mysql_free_result($cur);
                mosBindArrayToObject($array, $object, null, null, false);
                return true;
            } else {
                return false;
            }
        } else {
            if ($cur = $this->query()) {
                if ($object = mysql_fetch_object($cur)) {
                    mysql_free_result($cur);
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
     *
     * @param <type> $key
     * @return <type>
     */
    public function loadObjectList($key = '') {
        if (!($cur = $this->query())) {
            return null;
        }
        $array = array();
        while ($row = mysql_fetch_object($cur)) {
            if ($key) {
                $array[$row->$key] = $row;
            } else {
                $array[] = $row;
            }
        }
        mysql_free_result($cur);

        return $array;
    }

    /**
     *
     * @return <type>
     */
    public function loadRow() {
        if (!($cur = $this->query())) {
            return null;
        }
        $ret = ($row = mysql_fetch_row($cur)) ? $row : null;
        mysql_free_result($cur);

        return $ret;
    }

    /**
     *
     * @param <type> $key
     * @return <type>
     */
    public function loadRowList($key = null) {
        if (!($cur = $this->query())) {
            return null;
        }
        $array = array();
        while ($row = mysql_fetch_row($cur)) {
            if (!is_null($key)) {
                $array[$row[$key]] = $row;
            } else {
                $array[] = $row;
            }
        }
        mysql_free_result($cur);

        return $array;
    }

    /**
     *
     * @param <type> $table
     * @param <type> $object
     * @param <type> $keyName
     * @param <type> $verbose
     * @return <type>
     */
    public function insertObject($table, $object, $keyName = null, $verbose = false) {

        $fmtsql = "INSERT INTO $table ( %s ) VALUES ( %s ) ";

        $fields = array();
        foreach (get_object_vars($object) as $k => $v) {
            if (is_array($v) or is_object($v) or $v === null) {
                continue;
            }
            if ($k[0] == '_') { // внешние поля
                continue;
            }
            $fields[] = $this->NameQuote($k);
            $values[] = $this->Quote($v);
        }
        $this->setQuery(sprintf($fmtsql, implode(",", $fields), implode(",", $values)));
        ($verbose) && print "$fmtsql<br />\n";
        if (!$this->query()) {
            return false;
        }
        $id = mysql_insert_id($this->_resource);
        ($verbose) && print "id=[$id]<br />\n";
        if ($keyName && $id) {
            $object->$keyName = $id;
        }

        return ($id > 0) ? $id : true;
    }

    /**
     *
     * @param <type> $table
     * @param <type> $object
     * @param <type> $keyName
     * @param <type> $updateNulls
     * @return <type>
     */
    public function updateObject($table, $object, $keyName, $updateNulls = true) {

        $fmtsql = "UPDATE $table SET %s  WHERE %s";
        $tmp = array();
        foreach (get_object_vars($object) as $k => $v) {
            if (is_array($v) or is_object($v) or $k[0] == '_') { // internal or NA field
                continue;
            }
            if ($k == $keyName) { // PK not to be updated
                $where = $keyName . '=' . $this->Quote($v);
                continue;
            }
            if ($v === null && !$updateNulls) {
                continue;
            }
            if ($v == '') {
                $val = "''";
            } else {
                $val = $this->Quote($v);
            }
            $tmp[] = $this->NameQuote($k) . '=' . $val;
        }
        $this->setQuery(sprintf($fmtsql, implode(",", $tmp), $where));

        return (bool) $this->query();
    }

    public function stderr($showSQL = false) {
        JDEBUG ? jd_log($this->_errorMsg . "\n\t" . $this->_sql) : null;
        return "DB function failed with error number $this->_errorNum <br /><font color=\"red\">$this->_errorMsg</font>" . ($showSQL ? "<br />SQL = <pre>$this->_sql</pre>" : '');
    }

    public function insertid() {
        return mysql_insert_id($this->_resource);
    }

    public function getCursor() {
        return $this->_cursor;
    }

    /**
     *
     * @return UtulsDB
     */
    public function getUtils() {
        return new UtulsDB($this);
    }

}

/**
 * Утилиты для работы с базой данных
 */
class UtulsDB extends database {

    /**
     *
     * @var <type>
     */
    private $_db;

    /**
     *
     * @param <type> $db
     */
    public function __construct($db) {
        $this->_db = $db;
    }

    /**
     *
     * @return <type>
     */
    private function db() {
        return $this->_db;
    }

    /**
     *
     * @return <type>
     */
    public function getVersion() {
        return mysql_get_server_info($this->db()->_resource);
    }

    /**
     *
     * @param <type> $only_joostina
     * @return <type>
     */
    public function getTableList($only_joostina = true) {
        $only_joostina = $only_joostina ? " LIKE '" . $this->db()->_table_prefix . "%' " : '';
        return $this->db()->setQuery('SHOW TABLES ' . $only_joostina)->loadResultArray();
    }

    /**
     *
     * @param <type> $tables
     * @return <type>
     */
    public function getTableCreate($tables) {
        $result = array();

        foreach ($tables as $tblval) {
            $rows = $this->db()->setQuery('SHOW CREATE table ' . $this->getEscaped($tblval))->loadRowList();
            foreach ($rows as $row) {
                $result[$tblval] = $row[1];
            }
        }

        return $result;
    }

    /**
     *
     * @param <type> $tables
     * @return <type>
     */
    public function getTableFields($tables) {
        $result = array();

		$fields = $this->db()->setQuery('SHOW FIELDS FROM ' . $tables)->loadObjectList();
        foreach ($fields as $field) {
			$result[$field->Field] = $field->Type;
       }

        return $result;
    }

    /**
     *
     * @param <type> $abort_on_error
     * @param <type> $p_transaction_safe
     * @return <type>
     */
    public function query_batch($abort_on_error = true, $p_transaction_safe = false) {
        $this->_errorNum = 0;
        $this->_errorMsg = '';
        if ($p_transaction_safe) {
            $si = mysql_get_server_info($this->_resource);
            preg_match_all("/(\d+)\.(\d+)\.(\d+)/i", $si, $m);
            if ($m[1] >= 4) {
                $this->_sql = 'START TRANSACTION;' . $this->_sql . '; COMMIT;';
            } else
            if ($m[2] >= 23 && $m[3] >= 19) {
                $this->_sql = 'BEGIN WORK;' . $this->_sql . '; COMMIT;';
            } else
            if ($m[2] >= 23 && $m[3] >= 17) {
                $this->_sql = 'BEGIN;' . $this->_sql . '; COMMIT;';
            }
        }
        $query_split = preg_split("/[;]+/", $this->_sql);
        $error = 0;
        foreach ($query_split as $command_line) {
            $command_line = trim($command_line);
            if ($command_line != '') {
                $this->_cursor = mysql_query($command_line, $this->_resource);
                if (!$this->_cursor) {
                    $error = 1;
                    $this->_errorNum .= mysql_errno($this->_resource) . ' ';
                    $this->_errorMsg .= mysql_error($this->_resource) . " SQL=$command_line <br />";
                    if ($abort_on_error) {
                        return $this->_cursor;
                    }
                }
            }
        }
        return (bool) $error;
    }

    /**
     *
     * @return <type>
     */
    public function explain() {
        $temp = $this->_sql;
        $this->_sql = 'EXPLAIN ' . $this->_sql;
        $this->query();

        if (!($cur = $this->query())) {
            return null;
        }
        $first = true;

        $buf = '<table cellspacing="1" cellpadding="2" border="0" bgcolor="#000000" align="center">';
        $buf .= $this->getQuery();
        while ($row = mysql_fetch_assoc($cur)) {
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
        mysql_free_result($cur);

        $this->_sql = $temp;

        return '<div style="background-color:#FFFFCC" align="left">' . $buf . '</div>';
    }

    /**
     *
     * @param <type> $message
     * @param <type> $sql
     */
    public function show_db_error($message, $sql = null) {
        echo '<div style="display:block;width:100%;"><b>DB::error:</b> ';
        echo $message;
        echo $sql ? '<pre>' . $sql . '</pre><b>UseFiles</b>::' : '';
        if (function_exists('debug_backtrace')) {
            foreach (debug_backtrace () as $back) {
                if (@$back['file']) {
                    echo '<br />' . $back['file'] . ':' . $back['line'];
                }
            }
        }
        echo '</div>';
    }

}

class mosDBTable extends database {

    public $_tbl;
    protected $_tbl_key;
    protected $_error;
    /**
     * Объект базы данных
     * @var database
     */
    public $_db;

    public function mosDBTable($table, $key, $db = null) {
        $this->_tbl = $table;
        $this->_tbl_key = $key;
        $this->_db = $db ? $db : parent::$_db_instance;
    }

    public function classname() {
        return get_class($this);
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
    public function tocache() {
        $obj = clone $this;
        // удаляем ненужную ссылку на ресурс базы данных и стек ошибок
        unset($obj->_db, $obj->_error);
        // сохраняем оригинальное название модели
        $obj->__obj_name = get_class($obj);

        return $obj;
    }

	
	public function getKeyField(){
		return $this->_tbl_key;
	}


	public function getPublicProperties() {
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

    public function filter($ignoreList = null) {
        $ignore = is_array($ignoreList);

        $iFilter = InputFilter::getInstance();
        foreach ($this->getPublicProperties() as $k) {
            if ($ignore && in_array($k, $ignoreList)) {
                continue;
            }
            $this->$k = $iFilter->process($this->$k);
        }
    }

    public function getError() {
        return $this->_error;
    }

    public function get($_property) {
        return isset($this->$_property) ? $this->$_property : null;
    }

    public function set($_property, $_value) {
        $this->$_property = $_value;
    }

    public function reset($value = null) {
        $keys = $this->getPublicProperties();
        foreach ($keys as $k) {
            $this->$k = $value;
        }
    }

    function bind($array, $ignore = '') {
        if (!is_array($array)) {
            $this->_error = strtolower(get_class($this)) . '::bind - error';
            return false;
        } else {
            return mosBindArrayToObject($array, $this, $ignore);
        }
    }

    function load($oid = null) {
        $k = $this->_tbl_key;

        if ($oid !== null) {
            $this->$k = $oid;
        }

        $oid = $this->$k;

        if ($oid === null) {
            return false;
        }
        /*
         * // TODO это зачем?
          $class_vars = get_class_vars(get_class($this));
          foreach ($class_vars as $name => $value) {
          if (($name != $k) and ($name != '_db') and ($name != '_tbl') and ($name != '_tbl_key')) {
          $this->$name = $value;
          }
          }
          $this->reset();
         */
        // сброс установок для обнуления назначенных ранее свойств объекта ( проблема с isset($obj->id) )
        $this->reset();
        $query = 'SELECT * FROM ' . $this->_tbl . ' WHERE ' . $this->_tbl_key . ' = ' . $this->_db->Quote($oid);
        return $this->_db->setQuery($query)->loadObject($this);
    }

    public function store($updateNulls = false) {
        $k = $this->_tbl_key;

        $this->before_store();

        if ( isset($this->$k) && $this->$k != 0) {
            // TODO сюда можно добавить "версионность", т.е. сохранять текущие версии объектов перед внесением правок
            $ret = $this->_db->updateObject($this->_tbl, $this, $this->_tbl_key, $updateNulls);
            $this->after_update();
        } else {
            $ret = $this->_db->insertObject($this->_tbl, $this, $this->_tbl_key);
            $this->after_insert();
        }

        if (!$ret) {
            $this->_error = strtolower(get_class($this)) . "::ошибка выполнения store<br />" . $this->_db->getErrorMsg();
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
     * Метод выполняемый после обновления значений модели
     */
    public function after_update() {

    }

    /**
     * Метод выполняемый после вставки значений модели
     */
    public function after_insert() {

    }

    /**
     * Метод выполняемый после полного сохранения данных модели ( вставка / обновление )
     */
    public function after_store() {

    }

    /**
     * Метод выполняемый до сохранения значений модели ( вставка / обновление )
     */
    public function before_store() {

    }

    /**
     * Метод выполняемый после удаления конкретной записи модели
     */
    public function before_delete() {

    }

    public function delete($oid = null) {
        $k = $this->_tbl_key;

        if ($oid) {
            $this->$k = intval($oid);
        }

        $this->before_delete();

        // активируем "мягкое удаление", т.е. сохраняем копию в корзине
        _DB_SOFTDELETE ? Jtrash::add($this) : null;

        $query = "DELETE FROM $this->_tbl WHERE $this->_tbl_key = " . $this->_db->Quote($this->$k);
        $this->_db->setQuery($query);

        if ($this->_db->query()) {
            return true;
        } else {
            $this->_error = $this->_db->getErrorMsg();
            return false;
        }
    }

    // TODO добавить "мягкое удаление"
    public function delete_array($oid = array(), $key = false, $table = false) {
        $key = $key ? $key : $this->_tbl_key;
        $table = $table ? $table : $this->_tbl;

        if (_DB_SOFTDELETE) {
            $obj = clone $this;
            foreach ($oid as $cur_id) {
                $obj->load($cur_id);
                Jtrash::add($obj);
                $obj->reset();
            }
            unset($obj);
        }

        $query = "DELETE FROM $table WHERE $key IN (" . implode(',', $oid) . ')';

        if ($this->_db->setQuery($query)->query()) {
            return true;
        } else {
            $this->_error = $this->_db->getErrorMsg();
            return false;
        }
    }

    public function save($source = false, $ignore_filter = '') {
        if ($source && !$this->bind($source, $ignore_filter)) {
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

    function publish_array($cid = null, $publish = 1, $user_id = 0) {
        $this->publish($cid, $publish, $user_id);
    }

    function publish($cid = null, $publish = 1) {
        mosArrayToInts($cid, array());

        $publish = (int) $publish;
        if (count($cid) < 1) {
            $this->_error = "No items selected.";
            return false;
        }

        $cids = $this->_tbl_key . '=' . implode(' OR ' . $this->_tbl_key . '=', $cid);

        $query = "UPDATE $this->_tbl SET published = " . (int) $publish . " WHERE ($cids)";

        if (!$this->_db->setQuery($query)->query()) {
            $this->_error = $this->_db->getErrorMsg();
            return false;
        }

        $this->_error = '';
        return true;
    }

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

        $this->_db->setQuery($sql);

        $row = null;
        if ($this->_db->loadObject($row)) {
            $query = "UPDATE $this->_tbl SET ordering = " . (int) $row->ordering . " WHERE $this->_tbl_key = " . $this->_db->Quote($this->$k);
            $this->_db->setQuery($query);

            if (!$this->_db->query()) {
                $err = $this->_db->getErrorMsg();
                die($err);
            }

            $query = "UPDATE $this->_tbl SET ordering = " . (int) $this->ordering . " WHERE $this->_tbl_key = " . $this->_db->Quote($row->$k);
            $this->_db->setQuery($query);

            if (!$this->_db->query()) {
                $err = $this->_db->getErrorMsg();
                die($err);
            }

            $this->ordering = $row->ordering;
        } else {
            $query = "UPDATE $this->_tbl SET ordering = " . (int) $this->ordering . " WHERE $this->_tbl_key = " . $this->_db->Quote($this->$k);
            $this->_db->setQuery($query);
            if (!$this->_db->query()) {
                $err = $this->_db->getErrorMsg();
                die($err);
            }
        }
    }

    function updateOrder($where = '') {
        $k = $this->_tbl_key;

        if (!array_key_exists('ordering', get_class_vars(strtolower(get_class($this))))) {
            $this->_error = "ВНИМАНИЕ: " . strtolower(get_class($this)) . " не поддерживает сортировку.";
            return false;
        }

        $query = "SELECT $this->_tbl_key, ordering" . "\n FROM $this->_tbl" . ($where ? "\n WHERE $where" : '') . "\n ORDER BY ordering";
        $this->_db->setQuery($query);
        if (!($orders = $this->_db->loadObjectList())) {
            $this->_error = $this->_db->getErrorMsg();
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
                $query = "UPDATE $this->_tbl" . "\n SET ordering = " . (int) $orders[$i]->ordering . "\n WHERE $k = " . $this->_db->Quote($orders[$i]->$k);
                $this->_db->setQuery($query);
            }
        }

        if ($shift == 0) {
            $order = $n + 1;
            $query = "UPDATE $this->_tbl" . "\n SET ordering = " . (int) $order . "\n WHERE $k = " . $this->_db->Quote($this->$k);
            $this->_db->setQuery($query);
        }
        return true;
    }

//  число записей в таблице по условию
    public function count($where = '') {
        $sql = "SELECT count(*) FROM $this->_tbl " . $where;
        return $this->_db->setQuery($sql)->loadResult();
    }

// получение списка значений
    public function get_list(array $params = array()) {

        $select = isset($params['select']) ? $params['select'] : '*';
        $where = isset($params['where']) ? ' WHERE ' . $params['where'] : '';
        $order = isset($params['order']) ? ' ORDER BY ' . $params['order'] : '';
        $offset = isset($params['offset']) ? intval($params['offset']) : 0;
        $limit = isset($params['limit']) ? intval($params['limit']) : 0;
        $join = isset($params['join']) ? $params['join'] : '';

        $tbl_key = isset( $params['key'] ) ? $params['key'] : $this->_tbl_key;

        return $this->_db->setQuery("SELECT $select FROM $this->_tbl $join " . $where . $order, $offset, $limit)->loadObjectList( $tbl_key );
    }

// получение списка значений для селектора
    public function get_selector(array $key_val, array $params = array()) {

        $key = isset($key_val['key']) ? $key_val['key'] : 'id';
        $value = isset($key_val['value']) ? $key_val['value'] : 'title';

        $select = $key . ',' . $value;
        $where = isset($params['where']) ? 'WHERE ' . $params['where'] : '';
        $order = isset($params['order']) ? ' ORDER BY ' . $params['order'] : '';
        $offset = isset($params['offset']) ? intval($params['offset']) : 0;
        $limit = isset($params['limit']) ? intval($params['limit']) : 0;
        $tablename = isset($params['table']) ? $params['table'] : $this->_tbl;

        $opts = $this->_db->setQuery("SELECT $select FROM $tablename " . $where. $order, $offset, $limit)->loadAssocList();

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
        return $this->_db->setQuery($sql, $offset, $limit)->loadAssocList('id');
    }

// сохранение значение одного ко многим
    public function save_one_to_many($name_table_keys, $key_name, $value_name, $key_value, $values) {

        //сначала чистим все предыдущие связи
        $this->_db->setQuery("DELETE FROM $name_table_keys WHERE $key_name=$key_value ")->query();

        // фомируем массив сохраняемых значений
        $vals = array();
        foreach ($values as $value) {
            $vals[] = " ($key_value, $value  ) ";
        }

        $values = implode(',', $vals);

        $sql = "INSERT IGNORE INTO $name_table_keys ( $key_name,$value_name ) VALUES $values";
        return $this->_db->setQuery($sql)->query();
    }

// булево изменение содержимого указанного столбца. Используется для смены статуса элемента
    public function changeState($fieldname) {
        return $this->_db->setQuery("UPDATE $this->_tbl SET `$fieldname` = !`$fieldname` WHERE $this->_tbl_key = $this->id ", 0, 1)->query();
    }

// селектор выбора отношений один-ко-многим
    public function get_one_to_many_selectors($name, $table_values, $table_keys, $key_parent, $key_children, array $selected_ids, array $params = array()) {
        mosMainFrame::addLib('form');

        $params['select'] = isset($params['select']) ? $params['select'] : 't_val.id, t_val.title';
        
        $params['wrap_start'] = isset($params['wrap_start']) ? $params['wrap_start'] : '';
        $params['wrap_end'] = isset($params['wrap_end']) ? $params['wrap_end'] : '';
        
        $childrens = $this->get_selector(array(), array('table' => $table_values));

        $rets = array();
        foreach ($childrens as $key => $value) {
            $el_id = $name . $key;
            $checked = (bool) isset($selected_ids[$key]);            
            $rets[] = $params['wrap_start'].form::checkbox($name . '[]', $key, $checked, 'id="' . $el_id . '" ');
            $rets[] = form::label($el_id, $value).$params['wrap_end'];
        }

        return implode("\n\t", $rets);
    }

    // поиск записи через указание группы свойств объекта
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
                $val = $this->_db->Quote($v);
            }
            $tmp[] = $this->NameQuote($k) . '=' . $val;
        }
        return $this->_db->setQuery(sprintf($fmtsql, implode(' AND ', $tmp)))->loadObject($this);
    }

}