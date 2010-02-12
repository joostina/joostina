<?php

interface Session_Extention
{
	/**
	 * Открытие сессии
	 * @param string $save_path
	 * @param string $session_name
	 * @return boolean true
	 */
	public function open($save_path, $session_name);

	/**
	 * Закрытие сессии - освобождение ресурсов
	 */
	public function close();

	/**
	 * Чтение сессии
	 * @param string $id - идентификатор сессии
	 * @return $sess_data - серилизованные данные сессии;
	 */
	public function read($id);

	/**
	 * Запись данных сессии
	 * @param string $id - идентификатор сессии
	 * @param string $sess_data - серилизованные данные сессии
	 * @return boolean;
	 */
	public function write($id, $sess_data);

	/**
	 * Чистка мусора - удаляет сессии старше $maxlifetime
	 * @param integer $maxlifetime - время хранения сессии в секундах
	 * @return boolean true
	 */
	public function gc($maxlifetime);

	/**
	 * Удаление сессии
	 * @param string $id - идентификатор сессии
	 */
	public function destroy($id);

}