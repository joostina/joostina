<?php defined('_JOOS_CORE') or exit();

/**
 * Библиотека работы с базой данных MySQL через PDO
 * Системная библиотека
 *
 * @version    1.0
 * @package    Core\Libraries
 * @subpackage Database\Drivers
 * @category   Libraries
 * @author     Joostina Team <info@joostina.ru>
 * @copyright  (C) 2007-2012 Joostina Team
 * @license    MIT License http://www.opensource.org/licenses/mit-license.php
 * Информация об авторах и лицензиях стороннего кода в составе Joostina CMS: docs/copyrights
 *
 * */
class joosDatabasePDO implements joosInterfaceDatabase
{
    /**
     * @var joosDatabasePDO Объект работы с базой данных
     */
    private static $instance = NULL;

    /**
     * @var PDO Объект соединения с базой данных
     */
    protected $_connection = NULL;

    /**
     * @var string Префикс таблиц базы данных
     */
    private $_table_prefix = NULL;

    /**
     * @var string Что именно считается префиксом
     */
    private $_prefix_key = '#__';

    /**
     * @var string Строка, хранящая последний установленный запрос
     */
    private $_sql = NULL;

    /**
     * @var PDOStatement Последний использованный объект запроса
     */
    private $_statement = NULL;

    /**
     * @var array Массив параметров для привязки
     */
    private $_params = array();

    /**
     * Закрытый конструктор для соединений с базой данных. В случае отсутствия соединения
     * прекращает работу сайта.
     *
     * @param string $host     Хост базы
     * @param string $user     Имя пользователя
     * @param string $password Пароль
     * @param string $db       Имя базы
     * @param string $charset  Кодировка базы
     * @param string $prefix   Префикс таблиц
     */
    protected function __construct($host, $user, $password, $db, $charset = 'utf8', $prefix = 'jos_')
    {
        //а существует ли расширение вообще
        if (!extension_loaded('PDO') || !extension_loaded('pdo_mysql')) {

            $this->offline();
        }

        //пытаемся соединиться
        try {

            $connection = new PDO('mysql:host=' . $host . ';dbname=' . $db . ';charset=' . $charset, $user, $password);
            $this->_connection = $connection;
            $this->_table_prefix = $prefix;

        } catch (Exception $e) {

            $this->offline();
        }

        //указание charset в строке DSN игнорируется, поэтому указываем так
        $this->set_query('SET NAMES ' . $charset)->query();

        $this->set_profiling();
    }

    /**
     * Любая ошибка соединений с базой это повод выключить сайт.
     */
    private function offline()
    {
        include JPATH_BASE . '/app/templates/system/offline.php';
        exit();
    }

    /**
     * Простой синглетон для единого коннекта к базе данных
     *
     * @return joosDatabasePDO Объект соединений с базой
     */
    public static function instance()
    {
        //объект создается однажды
        if (self::$instance === NULL) {

            $db = joosConfig::get('db');
            joosDatabasePDO::$instance = new joosDatabasePDO($db['host'], $db['user'], $db['password'], $db['name'], $db['charset'], $db['prefix']);
        }

        return joosDatabasePDO::$instance;
    }

    /**
     * При включенной отладке необходимо профилирование запросов
     */
    private function set_profiling()
    {
        if (JDEBUG) {

            $this->set_query('set profiling=1')->query();
            $this->set_query('set profiling_history_size=100')->query();
        }
    }

    /**
     * Пока что метод клонирование закрываем, хз зачем он вообще нужен
     */
    public function __clone()
    {
    }

    /**
     * Метод обрамления служебных названий кавычками
     *
     * @param  string $s Входная строка
     * @return string Заквотированная строка
     */
    public function name_quote($s)
    {
        return '`' . $s . '`';
    }

    /**
     * Получение нулевого значения времени для использования по умолчанию в sql запросах
     *
     * @return string строка определяющая нулевое значение времени для использования в базе
     */
    public function get_null_date()
    {
        return '0000-00-00 00:00:00';
    }

    /**
     * Установка запроса для последующего исполнения
     *
     * @param  string          $sql    SQL-запрос
     * @param  array           $params Массив параметров для замены вида :name => $value
     * @return joosDatabasePDO
     */
    public function set_query($sql, $params = array())
    {
        $this->_sql = str_replace($this->_prefix_key, $this->_table_prefix, $sql);
        $this->_params = $params;

        return $this;
    }

    /**
     * Исполнение запроса с указанными параметрами. Если произошла ошибка - выбрасывается
     * исключение и работа прекращается.
     *
     * @return PDOStatement В случае успеха возвращается объект запроса
     */
    public function query()
    {
        //установка запроса и привязка параметров
        $this->_statement = $this->_connection->prepare($this->_sql);
        $this->bind_params();

        //исполнение и обработка ошибок
        $this->_statement->execute();
        if ('00000' != $this->_statement->errorCode()) {

            //тут хранится информация об ошибке
            $error_info = $this->_statement->errorInfo();

            throw new joosDatabaseException('Ошибка выполнения SQL #:error_num <br /> :error_message.<br /><br /> Ошибка в SQL: :sql', array(':error_num' => $this->_statement->errorCode(), ':error_message' => $error_info[2], ':sql' => $this->_sql));
        }

        return $this->_statement;
    }

    /**
     * Хитрая привязка параметров для исполнения запроса
     *
     * @param array $params Параметры
     */
    private function bind_params()
    {
        foreach ($this->_params as $param_name => $param_value) {

            //эта сволочь привязывается к переменной, а в цикле она всегда будет привязана
            //к последнему значению переменной, поэтому такой изврат с новой переменной
            $tmp = 'aaa' . rand(1, 1000) . rand(1, 1000) . rand(1, 1000) . rand(1, 1000) . rand(1, 1000);
            $$tmp = $param_value;

            $this->_statement->bindParam(':' . $param_name, $$tmp);
        }
    }

    /**
     * Возвращает число строк, измененныхп при последнем запросе DELETE, UPDATE или INSERT
     */
    public function get_affected_rows()
    {
        return $this->_statement->rowCount();
    }

    /**
     * Возвращает первый результат запроса
     *
     * @return string Значение поля
     */
    public function load_result()
    {
        //результат исполнения запроса не проверяем, так как если что - оно само упадет
        $this->query();

        //получаем массив, пронумерованный с нуля
        $result = $this->_statement->fetch(PDO::FETCH_NUM);

        $this->free_result();

        //и пытаемся вернуть нулевой элемент
        return isset($result[0]) ? $result[0] : NULL;
    }

    /**
     * Получение одного столбца запроса (по умолчанию нулевого) как обычного
     * массива со значениями полей.
     *
     * @param  int   $column Индекс поля в запросе (с нуля)
     * @return array Массив значений
     */
    public function load_result_array($column = 0)
    {
        //исполняем запрос
        $this->query();

        $result = array();
        while ($row = $this->_statement->fetch(PDO::FETCH_NUM)) {

            $result[] = isset($row[$column]) ? $row[$column] : NULL;
        }

        $this->free_result();

        return $result;
    }

    /**
     * Возвращает массив строк выборки, где каждая строка это ассоциативный
     * массив с данными столбцов
     *
     * @param string $key Ключ выборки
     */
    public function load_assoc_list($key = '')
    {
        $this->query();

        $result = array();
        while ($row = $this->_statement->fetch(PDO::FETCH_ASSOC)) {

            if ($key) {

                $result[$row[$key]] = $row;
            } else {

                $result[] = $row;
            }
        }

        $this->free_result();

        return $result;
    }

    /**
     * Загрузка полей выборки в указанный объект.
     *
     * @param object $object Куда сохраняем данные
     */
    public function load_object(& $object)
    {
        $this->query();

        if ($object !== NULL) {

            $array = $this->_statement->fetch(PDO::FETCH_ASSOC);
            $this->bind_array_to_object($array, $object, null, null, false);
        } else {

            $object = $this->_statement->fetch(PDO::FETCH_OBJ);
        }

        $this->free_result();
    }

    /**
     * Загрузка строк из таблицы как массива объектов stdClass
     *
     * @param  string $key Ключ массива (имя столбца)
     * @return array  Результирующий массив
     */
    public function load_object_list($key = '')
    {
        $this->query();

        $result = array();
        while ($row = $this->_statement->fetch(PDO::FETCH_OBJ)) {

            if ($key) {

                $result[$row->$key] = $row;
            } else {

                $result[] = $row;
            }
        }

        $this->free_result();

        return $result;
    }

    /**
     * Вставка в базу готового ассоциативного массива с данными
     *
     * @param string $table        Имя таблицы
     * @param object $object       Объект модели с полями
     * @param array  $values_array Массив массивов значений
     */
    public function insert_array($table, $object, array $values_array)
    {
        $ignore = isset($object->__ignore) ? ' IGNORE ' : '';
        unset($object->__ignore);

        $fmtsql = "INSERT {$ignore} INTO {$table} ( %s ) VALUES %s ";

        //храним поля и значения
        $fields = array();
        $values = array();
        $values_size = 0;

        //получаем все простые публичные поля модели (не начинающиеся с _)
        foreach (get_object_vars($object) as $field_name => $model_field_value) {

            if (is_array($model_field_value) or is_object($model_field_value)) {
                continue;
            }

            if ($field_name[0] == '_') {
                continue;
            }

            //имена полей базы
            $fields[] = $this->name_quote($field_name);

            //цикл по входному массиву массивов
            foreach ($values_array as $value_index => $one_array) {

                //если указано значение в самой модели - то оно важнее данных в массиве
                if ($model_field_value !== NULL) {

                    $result = $model_field_value;
                } else {

                    //если поле есть во входном массиве - используем его
                    if (isset($one_array[$field_name])) {

                        $result = $one_array[$field_name];
                    }
                    //иначе NULL
                    else {

                        $result = 'NULL';
                    }
                }

                //кладем в массив, который потом развернем в SQL-запрос
                $values[$field_name][] = $result;
            }

            //число элементов в подчиненном массиве
            $values_size++;
        }

        //формируем заполнение полей для столбца VALUE()
        $params = array();
        for ($i = 0; $i < $values_size; $i++) {

            //формируем кортеж одной записи
            $row = array();
            foreach ($values as $field => $one_array) {

                //$row[] = $one_array[$i];
                $param_name = $field . '_' . $i;
                $params[$param_name] = $one_array[$i];

                $row[] = ':' . $param_name;
            }

            $row_strings[] = '(' . implode(', ', $row) . ')';
        }

        //готовим запрос
        $query = sprintf($fmtsql, implode(",", $fields), implode(",", $row_strings));
        //и исполняем
        $this->set_query($query, $params);
        $this->query();

        $this->free_result();

        return true;
    }

    /**
     * Возвращает значение ID, сгенерированное для столбца AUTO_INCREMENT в
     * предыдущем запросе INSERT
     *
     * @return int Значение последнего ID
     */
    public function insert_id()
    {
        return $this->_connection->lastInsertId();
    }

    /**
     * Возвращаем объект с утилитарными функциями работы с базой данных
     * @return joosDatabaseUtilsPDO Объект функций
     */
    public function get_utils()
    {
        return new joosDatabaseUtilsPDO($this);
    }

    /**
     * Преобразование массива в объект (взял без изменения из старого класса)
     *
     * @param array  $array        исходный массив ключ=>значение
     * @param object $obj          объект, свойства которого будут заполнены значениями сообтветсвующих ключей массива
     * @param string $ignore       свойства объекта которые следует игнорировать, через пробел ('id title slug')
     * @param string $prefix       префикс полей массива. Например в объекте title, а в массивe blog_title
     * @param bool   $checkSlashes флаг экранизации значений через addslashes
     *
     * @return bool результат предразования
     */
    public function bind_array_to_object(array $array, &$obj, $ignore = '', $prefix = null, $checkSlashes = false)
    {
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
     * Освобождаем ресурсы соединения
     */
    private function free_result()
    {
        if (!JDEBUG) {

            $this->_statement->closeCursor();
            $this->_statement = NULL;
        }
    }

    /**
     * Быстрое статическое создание модели и доступ к её медотам и свойствам
     *
     * @tutorial joosDatabase::models('modelUsers')->count()
     * @tutorial joosDatabase::models('Blog')->get_list( array('where'=>'sate=1') )
     * @tutorial joosDatabase::models('Blog')->save( $_POST )
     *
     * @param string $model_name
     *
     * @return joosModel объект выбранной модели
     */
    public static function models($model_name)
    {
        return new $model_name;
    }
}

/**
 * Библиотека утилитарных функций работы с базой данных через расширение PDO
 *
 * @version    1.0
 * @package    Core\Libraries
 * @subpackage Libraries
 * @subpackage joosDatabase
 * @author     Joostina Team <info@joostina.ru>
 * @copyright  (C) 2007-2012 Joostina Team
 * @license    MIT License http://www.opensource.org/licenses/mit-license.php
 * Информация об авторах и лицензиях стороннего кода в составе Joostina CMS: docs/copyrights
 *
 * */
class joosDatabaseUtilsPDO extends joosDatabasePDO implements joosInterfaceDatabaseUtils
{
    /**
     * @var joosDatabasePDO Объект базы данных
     */
    private $_db;

    /**
     * @param joosDatabase $db Уже существующий объект работы с базой данных
     */
    public function __construct(joosDatabasePDO $db)
    {
        $this->_db = $db;
    }

    /**
     * Возвращает список таблиц активной базы
     *
     * @param  bool  $only_joostina флаг позволяющий оставить в результирующем наборе только таблицы текущего сайта
     * @return array массив таблиц текущей базы данных
     */
    public function get_table_list($only_joostina = true)
    {
        $only_joostina = $only_joostina ? " LIKE '" . $this->_db->_table_prefix . "%' " : '';

        return $this->_db->set_query('SHOW TABLES ' . $only_joostina)->load_result_array();
    }

    /**
     * Возвращает ассоциативный массив свойств столбцов таблицы
     *
     * @param  string $tables название таблицы
     * @return array  ассоциативный массив, ключами которого являются названия полей, а значения - свойства полей
     */
    public function get_table_fields($tables)
    {
        $fields = $this->_db->set_query('SHOW FIELDS FROM ' . $tables)->load_object_list();

        $result = array();
        foreach ($fields as $field) {
            $result[$field->Field] = $field->Type;
        }

        return $result;
    }
}
