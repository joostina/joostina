<?php

interface Cache_Extention
{
	/**
	 * Записывает данные в кэш
	 * @param string $id - идентификатор
	 * @param mixed $value - значение
	 */
	public function set($id, $value);

	/**
	 * Получает кэш по идентификатору
	 * @param string $id - идентификатор
	 * @return mixed
	 */
	public function get($id);

	/**
	 * Удаляет данные из кеша по идентификатору
	 * @param string $id - идентификатор
	 */
	public function delete($id);

	/**
	 * Удаляет все данные из кэша
	 * @param string $name - название переменной
	 */
	public function delete_all();
}