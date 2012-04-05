<?php

// запрет прямого доступа
defined('_JOOS_CORE') or die();

require_once 'database/interface.php';
require_once 'database/mysqli.php';


/**
 * Библиотека ORM расширения для гибкой работы с информацией в юазе данных
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
class joosModel {

	/**
	 * Название таблицы, используемой текущей моделью
	 *
	 * @var string
	 */
	protected $_tbl;

	/**
	 * Название поля первичного ключа таблицы, чаще всего ID
	 * По данному полю производится идентификация объекта, и по правильному оно должно содержать уникальное значение
	 *
	 * @var string
	 */
	protected $_tbl_key;

	/**
	 * Текст ошибки работы с активной моделью
	 *
	 * @var string
	 */
	protected $_error;

	/**
	 * Объект базы данных
	 *
	 * @var joosDatabase
	 */
	protected $_db;

	/**
	 * "Мягкое" удаление объектов БД
	 * Если в модели переопределить это значение в TRUE - то запись перед удалением будет копироваться в общесистемную корзину
	 *
	 * @var bool
	 */
	protected $_soft_delete = FALSE;

	/**
	 * Название текущего класса модели
	 *
	 * @var string
	 */
	protected $__obj_name;
	protected $_validation_error_messages = array();

	/**
	 * Инициализация модели
	 *
	 * @param string $table название используемой таблицы, можно с преффиксом, например #__news
	 * @param string $key   Название поля первичного ключа таблицы,
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
	 * Заглушка получения информации о полях
	 * @return array
	 */
    public function get_fieldinfo() {
		return array();
	}

	/**
	 * Заглушка получения информации о таблице модели
	 * @return array
	 */
    public function get_tableinfo() {
		return array();
	}

	/**
	 * Заглушка получения информации о вкладках для оформления информации
	 *
	 * @return array
	 */
    public function get_tabsinfo() {
		return array();
	}

	/**
	 * Заглушка получения правил валидации полей модели
	 *
	 * @return array
	 */
    public function get_validate_rules() {
		return array();
	}

	/**
	 * Получение массива ошибок валидации модели
	 *
	 * @return bool|array массив ошибок или
	 */
	public function get_validation_error_messages() {
		return count($this->_validation_error_messages) > 0 ? $this->_validation_error_messages : false;
	}

	/**
	 * Валидация полей модели
	 *
	 * @return boolean
	 */
	public function validate() {

		$rules = $this->get_validate_rules();

		$valid = true;
		foreach ($rules as $rule) {
			$message = joosValidateHelper::valid($this->$rule[0], $rule[1], ( isset($rule['message']) ? $rule['message'] : false));
			if ($message !== TRUE) {
				$this->_validation_error_messages[$rule[0]][] = $message;
				$valid = false;
			};
		}

		return $valid;
	}

	/**
	 * Магический метод восстановления объекта
	 * Используется при прямом кэшировании модели
	 *
	 * @param array $values - массив значений востановленного объекта
	 *
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
	 *
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
	 *
	 * @param string $_property название поля
	 *
	 * @return string значение поля
	 */
	public function get($_property) {
		return isset($this->$_property) ? $this->$_property : null;
	}

	/**
	 * Установка значения конкретного поля модели
	 *
	 * @param string $_property название модели
	 * @param string $_value    значение поля для установки
	 */
	public function set($_property, $_value) {
		$this->$_property = $_value;
	}

	/**
	 * Сброс значения полей активной модели
	 *
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
	 *
	 * @param array  $array  двумерный массив "название поля"=>"значение поля"
	 * @param string $ignore название аттрибута для игнорирования
	 *
	 * @return boolean результат заполнения
	 */
	function bind(array $array, $ignore = '') {
		return $this->_db->bind_array_to_object($array, $this, $ignore);
	}

	/**
	 * Загрузка данных в модель непосредственно из БД по значению ключевого поля
	 * В случае успешного выполнения заполняет поля модели значениями из БД выбранными по ключевому полю
	 *
	 * @param integer $oid значение уникального ключевого поля, по которому необходимо делать выборку в БД
	 *
	 * @return boolean результат заполнения свойств модели
	 */
	function load($oid) {

		// сброс установок для обнуления назначенных ранее свойств объекта ( проблема с isset($obj->id) )
		$this->reset();

		$query = 'SELECT * FROM ' . $this->_tbl . ' WHERE ' . $this->_tbl_key . ' = ' . $this->_db->quote($oid);
		$result = $this->_db->set_query($query)->load_object($this);

		$events_name = 'model.on_load.' . $this->classname();
		joosEvents::has_events($events_name) ? joosEvents::fire_events($events_name, $result, $this) : null;

		return $result;
	}

	/**
	 * Загрузка данных в модель непосредственно из БД по значению произвольного поля
	 * В случае успешного выполнения заполняет поля модели значениями первого результата из БД выбранными по указанному
	 *
	 * @param string $field название произвольного поля модели
	 * @param string $value значение произвольного поля модели
	 *
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
	 *
	 * @param bool $update_nulls флаг обновления неопределённых свойств
	 * @param bool $forced_Insert   флаг принудительной вставки. Необходимо в случаях, когда значение ключевого поля уже задано, но всё-равно необходимо создать новую запись (например, в компоненте категорий: category_id известно, но в таблице `categories_details` нужно создать запись с этим ключом )
	 *
	 * @return boolean результат сохранения модели
	 */
	public function store($update_nulls = false, $forced_Insert = false) {
		$k = $this->_tbl_key;

		$this->before_store();

		if (( isset($this->$k) && $this->$k != 0 ) && !$forced_Insert) {

			// дата последней модификации
			if (property_exists($this, 'modified_at') && $this->modified_at == null) {
				$this->modified_at = JCURRENT_SERVER_TIME;
			}

			$this->before_update();
			$ret = $this->_db->update_object($this->_tbl, $this, $this->_tbl_key, $update_nulls);
			$this->after_update();
		} else {

			// дата создания объекта
			if (property_exists($this, 'created_at') && $this->created_at == null) {
				$this->created_at = JCURRENT_SERVER_TIME;
			}

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
	 *
	 * @return boolean результат проверки
	 */
	public function check() {
		return true;
	}

	/**
	 * Метод, выполняемый до обновления значений модели
	 *
	 * @return boolean
	 */
	protected function before_update() {
		return true;
	}

	/**
	 * Метод, выполняемый после обновления значений модели
	 *
	 * @return boolean
	 */
	protected function after_update() {
		return true;
	}

	/**
	 * Метод выполняемый до добавления значений модели
	 *
	 * @return boolean
	 */
	protected function before_insert() {
		return true;
	}

	/**
	 * Метод выполняемый после вставки значений модели
	 *
	 * @return boolean
	 */
	protected function after_insert() {
		return true;
	}

	/**
	 * Метод выполняемый до сохранения значений модели ( вставка / обновление )
	 *
	 * @return boolean
	 */
	protected function before_store() {
		return true;
	}

	/**
	 * Метод выполняемый после полного сохранения данных модели ( вставка / обновление )
	 *
	 * @return boolean
	 */
	protected function after_store() {
		return true;
	}

	/**
	 * Метод выполняемый до удаления конкретной записи модели
	 *
	 * @return boolean
	 */
	protected function before_delete() {
		return true;
	}

	/**
	 * Метод выполняемый после удаления конкретной записи модели
	 *
	 * @return boolean
	 */
	protected function after_delete() {
		return true;
	}

	/**
	 * Удаление записи в БД по значению ключевого поля
	 * Производит непосредственное удаление записи из БД
	 *
	 * @param mixed $oid значение ключевого поля
	 *
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
	 *
	 * @param array  $oid   массив значений ключевого
	 * @param string $key   название ключевого поля, по умолчанию - название ключевого поля текущей модели
	 * @param string $table название таблицы, в которой необходимо произвести удаление, по умолчанию - таблица текущей модели
	 *
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
	 *
	 * @param array $params массив параметров для формирования условий удаления
	 *
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
	 *
	 * @param array  $oid   массив значений ключевого
	 * @param string $key   название ключевого поля, по умолчанию - название ключевого поля текущей модели
	 * @param string $table название таблицы, в которой необходимо произвести копирование, по умолчанию - таблица текущей модели
	 *
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
	 *
	 * @param array  $source массив свойств название поля=>значение поля для заполнения свойств модели ( см. self::bind )
	 * @param string $ignore название аттрибута для игнорирования
	 *
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
	 *
	 * @param array $cid
	 * @param type  $publish
	 *
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
	 *
	 * @param string $field_name название свойства модели для изменения на противоположное
	 *
	 * @return boolean результат смены значения поля
	 */
	public function change_state($field_name) {
		$key = $this->{$this->_tbl_key};
		return $this->_db->set_query("UPDATE `$this->_tbl` SET `$field_name` = !`$field_name` WHERE $this->_tbl_key = $key", 0, 1)->query();
	}

	/**
	 * Возвращает число записей в таблице БД активной модели
	 *
	 * @param string $where дополнительное условие для подсчета числа записей, например "WHERE state=1"
	 *
	 * @return int число записей
	 */
	public function count($where = '') {
		$sql = "SELECT count(*) FROM $this->_tbl " . $where;
		return $this->_db->set_query($sql)->load_result();
	}

	/**
	 * Возвращает сумму по определенному полю
	 *
	 * @param string $field поле, по которому считаем
	 * @param string $where дополнительное условие
	 *
	 * @return int число записей
	 */
	public function sum($field, $where = '') {
		$sql = "SELECT sum($field) FROM $this->_tbl " . $where;
		return $this->_db->set_query($sql)->load_result();
	}

	/**
	 * Возвращает массив результатов выборки
	 *
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
	 *
	 * @return array ассоциативный или обычный массив результатов
	 */
	public function get_list(array $params = array()) {

		$offset = isset($params['offset']) ? $params['offset'] . "\n" : 0;
		$limit = isset($params['limit']) ? $params['limit'] . "\n" : 0;
		$tbl_key = isset($params['key']) ? $params['key'] : null;

		return $this->_db->set_query($this->get_query_list($params), $offset, $limit)->load_object_list($tbl_key);
	}

	/**
	 * Функция, формирующая SQL-запрос для метода get_list()
	 *
	 * @param array $params Те же параметры, что и для get_list
	 * @return string SQL-запрос
	 */
	private function get_query_list($params) {

		$select = isset($params['select']) ? $params['select'] . "\n" : '*';
		$where = isset($params['where']) ? 'WHERE ' . $params['where'] . "\n" : '';
		$join = isset($params['join']) ? $params['join'] . "\n" : '';
		$group = isset($params['group']) ? 'GROUP BY ' . $params['group'] . "\n" : '';
		$order = isset($params['order']) ? 'ORDER BY ' . $params['order'] . "\n" : '';
		$pseudonim = isset($params['pseudonim']) ? ' AS ' . $params['pseudonim'] . ' ' : '';

		return "SELECT $select FROM $this->_tbl $pseudonim $join " . $where . $group . $order;
	}

	/**
	 * Версия метода get_list с кэшированием
	 *
	 * @param array $params Те же параметры, что и для get_list
	 * @param int $cache_time Время жизни кэша
	 * @return type Закэшированное значение
	 */
	public function get_list_cache(array $params = array(), $cache_time = 86400) {

		$cache = new joosCache();
		$key   = md5($this->get_query_list($params));

		if (($value = $cache->get($key)) === NULL) {

			$value = $this->get_list($params);
			$cache->set($key, $value, $cache_time);
		}

		return $value;
	}

	/**
	 * Возвращает ассоциативный двумерный массив возможных значений модели
	 *
	 * @param array $key_val - массив array( 'key'=>'название поля - ключа','value'=>'название поля - значения' ). По умолчанияю key=>id, value=>title
	 * @param array $params  массив параметров для уточнее области выборки результата
	 *     <pre>
	 *         select - список поле для выборки, по умолчанию key,value (поля указанные в $key_val)
	 *         where - условие WHERE для выборки
	 *         order - название поля и направление сортировки результата, пример - " id DESC ", либо "id DESC, title ASC"
	 *         offset - смещение для выборки результата, по умолчанию - 0
	 *         limit - лимит выборки для результата, по молчанию - 0, т.е. ВСЕ записи
	 *         table - название таблицы, из которой необходимо сделать выборку. По умолчанию - таблица текущей модели
	 *     <pre>
	 *
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
		$where = isset($params['where']) ? 'WHERE ' . $params['where'] : "WHERE t_key.$key_parent = $this->{$this->_tbl_key} ";
		$order = isset($params['order']) ? 'ORDER BY ' . $params['order'] : '';
		$offset = isset($params['offset']) ? intval($params['offset']) : 0;
		$limit = isset($params['limit']) ? intval($params['limit']) : 0;
		$join = isset($params['join']) ? intval($params['join']) : 'LEFT JOIN';

		$sql = "SELECT $select FROM $table_values AS t_val $join $table_keys AS  t_key ON t_val.id=t_key.$key_children $where $order";
		return $this->_db->set_query($sql, $offset, $limit)->load_assoc_list('id');
	}

	// сохранение значение одного ко многим
	public function save_one_to_many($name_table_keys, $key_name, $value_name, $key_value, array $values) {

		if ($key_value == null || $key_value == '') {
			return false;
		}

		//сначала чистим все предыдущие связи
		$this->_db->set_query("DELETE FROM $name_table_keys WHERE $key_name=$key_value ")->query();

		// фомируем массив сохраняемых значений
		$vals = array();
		foreach ($values as $value) {
			$vals[] = " ($key_value, $value  ) ";
		}

		if (count($vals) == 0) {
			return true;
		}

		$values = implode(',', $vals);

		$sql = "INSERT IGNORE INTO $name_table_keys ( $key_name,$value_name ) VALUES $values";
		return $this->_db->set_query($sql)->query();
	}

	// селектор выбора отношений один-ко-многим
	public function get_one_to_many_selectors($name, $table_values, $table_keys, $key_parent, $key_children, array $selected_ids = array(), array $params = array()) {

		$params['select'] = isset($params['select']) ? $params['select'] : 't_val.id, t_val.title';
		$params['select_children'] = isset($params['select_children']) ? $params['select_children'] : array();

		$childrens = $this->get_selector($params['select_children'], array('table' => $table_values));

		$rets = array();
		foreach ($childrens as $key => $value) {
			$el_id = $name . $key;
			$checked = (bool) isset($selected_ids[$key]);
			$rets[] = forms::checkbox($name . '[]', $key, $checked, 'id="' . $el_id . '" ');
			$rets[] = forms::label($el_id, $value);
		}

		return implode("\n\t", $rets);
	}

	/**
	 * Загрузка значение текущей модели через указание произвольных свойств модели
	 *
	 * @param array $params массив параметров для условий выборки
	 *
	 * @return boolean результат поиска и загрузки значений в свойства текущей модели
	 */
	public function find(array $params = array('select' => '*')) {

		return $this->_db->set_query($this->get_find_query_from_params($params))->load_object($this);
	}

	/**
	 * Построение строки запроса из параметров метода find();
	 */
	private function get_find_query_from_params($params) {

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

		return sprintf($fmtsql, implode(' AND ', $tmp));
	}


	/**
	 * Кэширующая обертка над фунцией find
	 *
	 * @param array $params Параметры к методу find
	 * @param int $cache_time Время кэширования
	 * @return boolean Найденный объект
	 *
	 * @todo проверить обоснованность использования $find_result
	 */
	public function find_cache(array $params = array('select' => '*'), $cache_time = 86400) {

		$cache = new joosCache();
		$key   = md5($this->get_find_query_from_params($params));

		if (($value = $cache->get($key)) === NULL) {

			$find_result = $this->find($params);
			//в кэше надо хранить не только значение, но еще и результат поиска
			$cache->set($key, array($find_result, $this->to_cache()), $cache_time);
		}
		else {

			//достаем объект и мэппим его поля на текущий объект
			list($find_result,$obj) = $value;
			foreach($obj as $k => $v) {

				$this->$k = $v;
			}
		}

		return $find_result;
	}

	/**
	 * Поиск записей, удовлетворяющих указаным свойствам объекта
	 *
	 * @param array $params массив параметров для уточнения области поиска записей
	 *     <pre>
	 *         select - список поле для выборки, по умолчанию * (все поля)
	 *         where - условие WHERE для выборки
	 *         order - название поля и направление сортировки результата, пример - " id DESC ", либо "id DESC, title ASC"
	 *         offset - смещение для выборки результата, по умолчанию - 0
	 *         limit - лимит выборки для результата, по молчанию - 0, т.е. ВСЕ записи
	 *         key - название ключевого поля, для использования в качестве ключа ассоциативного массива результатов. По умолчанию использует ключевое поле модели. key=>FALSE если необходимо сделать простой массив ( 0=>array(),1=>array() )
	 *     <pre>
	 *
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
	 *
	 * @param string $name Имя поля
	 *
	 * @return integer максимальное значение
	 */
	function get_max_by_field($name) {
		$query = 'SELECT  ' . $name . ' AS max FROM ' . $this->_tbl . ' ORDER BY  ' . $name . ' DESC';
		return $this->_db->set_query($query)->load_result();
	}

	/**
	 * Вставка массива значений в таблицу текущего объекта
	 *
	 * @example
	 * $values = array(
	 * 	 0 => array(
	 * 		 'counter' => 111,
	 * 		 'name' => 'первая запись',
	 * 	 ),
	 * 	 1 => array(
	 * 		 'name' => ' вторая запись ',
	 * 		 'counter' => 2222
	 * 	 ),
	 * 	 2 => array(
	 * 		 'name' => ' третья запись',
	 * 		 'counter' => 123456
	 * 	 ),
	 * );
	 *
	 * @param array $array_values
	 * @return bool результат вставки массива
	 */
	public function insert_array(array $array_values) {
		return $this->_db->insert_array($this->_tbl, $this, $array_values);
	}

}

class joosDatabaseException extends joosException {

}
