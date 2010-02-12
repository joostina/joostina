<?php

/*
 * Класс прямой работы с базой данных
 *
*/
class db {

	/**
	 * Подключение к базе
	 * @param array $array_config - массив конфигурационных параметров
	 * @return boolean true - если соединение прошло успешно
	 */
	public function connect( $array_config ) {
	}

	/**
	 * Отключение от базы
	 * @return boolean true - если отключение прошло успешно
	 */
	public function disconnect() {
	}

	/**
	 * Вставка записи
	 * @param string $table - названи еиспользуемой таблицы
	 * @param array $array_atributes - массив вставляемых значение
	 * @return integer - возвращает ID вставленной записи
	 * @example db::insert( 'posts', array( 'title'=>'Название', 'body'=>'Описание' )  );
	 */
	public static function insert( $table, $array_atributes) {
	}

	/**
	 * Обновление существующей записи
	 * @param string $table - названи еиспользуемой таблицы
	 * @param array $array_atributes - массив обновляемых значение
	 * @param array $where_atributes - массив условий
	 * @return boolean true - если обновлени епрошло успешно
	 * @example  db::update( 'posts', array( 'id'=>array('=',1), 'state'=>array('>',0), 'limit'=>array(0,10) ) ,array( 'title'=>'Название', 'body'=>'Описание' )  );
	 */
	public static function update( $table, $array_atributes, $where_atributes) {
	}

	/**
	 * Удаление записи или группы записей от база
	 * @param string $table - название таблицы
	 * @param array $where_atributes - массив условий
	 * @param array $limit - массив с указанием лимита и смещения для удаляемых элементов
	 * @return boolean true
	 */
	public static function delete( $table, $where_atributes, $limit ) {
	}

	/**
	 * Выборка записий
	 * @param string $table - название используемой таблицы
	 * @param array $select_params - массив парамтеров запроса
	 * @return boolean true - возвращает если запрос сформировался корректно
	 * @example db::select( 'posts', array( 'where'=>'state=1 and id>10','limit'=>array(0,1) ) )
	 * @tutorial Функция только формирует запрос, выполнением и формированием результата занимаются другие функции
	 */
	public static function select( $table, $select_params ) {
	}

	/**
	 * Прямое выполнение запроса
	 * @param string $sql - строка SQL запроса
	 * @param array $attributes - массив атрибутов участвующих в запросе
	 * @param array $params - массив атрибутов условий
	 * @return boolean true - возвращает если запрос сформировался корректно
	 * @example (db)$db->query( 'SELECT * FROM `posts` WHERE state=%i and title=%s  ', array( 1,'синие педали', array( 'limit'=>10,'asc'=>'title' )  ) )
	 * @tutorial Функция только формирует запрос, выполнением и формированием результата занимаются другие функции
	 */
	public function query( $sql, $attributes, $params ) {
	}

	/**
	 * Получение идентификатора последней вставленной записи
	 * @return integer - идетификатор последдней вставленной записи
	 * @tutorial Функция выполняется только после INSERT запросов
	 */
	public function last_insert_id() {
	}

	/**
	 * Получение числа записей
	 * @param string $table - название используемой таблицы
	 * @param array $params - массив атрибутов условий
	 * @return integer - число записей удовлетворяющих указанному условия
	 * @example db::get_count( 'posts', array( 'where'=>'id>10' )  )
	 */
	public static function get_count( $table, $params ) {
	}

	/**
	 * Получение объекта запрашиваемой записи
	 * @return obj - объект выбранной записи
	 * @tutorial Функция выполняется только после SELECT запроса
	 */
	public function get_one() {
	}

	/**
	 * Получение массива запрашиваемых записей
	 * @return array - массив объектов выбранных записей
	 * @tutorial Функция выполняется только после SELECT запроса
	 */
	public function get_all() {
	}

	/**
	 * Получение ассоциативного по клучю массива запрашиваемых записей
	 * @param string $key - названи еключевого поля
	 * @return array - массив объектов выбранных записей
	 * @tutorial Функция выполняется только после SELECT запроса
	 */
	public function get_assoc( $key ) {
	}


	

	// выборка из нескольких таблиц //

	// запрос по таблицам один-к-одному
	public function related_one(){
	}

	// запрос по таблицам один ко многим
	public function related_one_to_many(){
	}

	// запрос по таблицам многие ко многим
	public function related_many_to_many(){
	}

}