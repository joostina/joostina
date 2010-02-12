<?php

interface Session
{
	/**
	 * Задает имя сессии, идентификатор сессии, определяет session_extantion, пользовательские функции сессии и стартует сессию
	 */
	public function start();

	/**
	 * Записывает данные в сессию
	 * @param string $name - название переменной
	 * @param mixed $value - значение переменной
	 */
	public function set($name, $value);

	/**
	 * Получает здачение переменной, записанной в сессии
	 * @param string $name - название переменной
	 * @return mixed - значение переменной
	 */
	public function get($name);

	/**
	 * Получает все данные, записанные в сессии
	 * @return array
	 */
	public function get_all();

	/**
	 * Проверяет определена ли переменная в данных сессии
	 * @param string $name - название переменной
	 * @return boolean
	 */
	public function _isset($name);

	/**
	 * Удаляет перенную из сессии
	 * @param string $name - название переменной
	 */
	public function _unset($name);

	/**
	 * Удаляет все данныи из сессии в заданном пространстве имен
	 * @param string $namespace - пространство имен, если не задано берется текущее
	*/
	public function namespace_unset($namespace = null);
}