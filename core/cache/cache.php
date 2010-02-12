<?php

interface Cache
{
	/**
	 * Конструктор класса - подключает бэкенд, определяется время жизни кэша из конфигурации
	 */
	public function __construct();

	/**
	 * Записывает данные в кэш
	 * @param string $id - идентификатор
	 * @param mixed $value - значение
	 * @param mixed (array or string) $tags - теги
	 */
	public function set($id, $value,$tags);

	/**
	 * Получает кэш по идентификатору
	 * @param string $id - идентификатор
	 * @return mixed
	 */
	public function get($id);

	/**
	 * Получает кэш по идентификаторам
	 * @param array $ids - идентификаторы
	 * @return array
	 */
	public function get_ids($ids);

	/**
	 * Получает кэш по тегам
	 * @param mixed (array or string) $tags - теги
	 * @return array
	 */
	public function get_tags($tags);

	/**
	 * Удаляет данные из кеша по идентификатору
	 * @param string $id - идентификатор
	 */
	public function delete($id);

	/**
	 * Удаляет данные из кеша по тегам
	 * @param mixed (array or string) $tags - теги
	 */
	public function delete_tags($tags);

	/**
	 * Удаляет все данные из кэша
	 * @param string $name - название переменной
	 */
	public function delete_all();
	
	/**
	 * Удаляет просроченные данные из кеша
	 * @param mixed (array or string) $tags - теги
	 */
	public function flush();

	/**
	 * Получает идентификаторы сохраненных данные по тегу
	 * @param string $tag - тег
	 * @return array
	 */
	public function get_ids_from_tag($tag);
}