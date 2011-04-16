<?php

/**
 * @package Joostina
 * @copyright Авторские права (C) 2007-2010 Joostina team. Все права защищены.
 * @license Лицензия http://www.gnu.org/licenses/gpl-2.0.htm GNU/GPL, или help/license.php
 * Joostina! - свободное программное обеспечение распространяемое по условиям лицензии GNU/GPL
 * Для получения информации о используемых расширениях и замечаний об авторском праве, смотрите файл help/copyright.php.
 */
// запрет прямого доступа
defined('_JOOS_CORE') or die();

/**
 * Работа с прикрепляемыми файлами
 * Суть работы заключается в расспределении файлов по файловой в системе с вложенностью каталогов, созданной по номеру файла в БД
 * Например /attachments/123/456/789/file.txt
 */
class joosAttached extends joosDBModel {

	/**
	 * @var int(11) unsigned
	 */
	public $id;
	/**
	 * @var timestamp
	 */
	public $created_at;
	/**
	 * @var int(11) unsigned
	 */
	public $user_id;
	/**
	 * @var varchar(200)
	 */
	public $file_name;
	/**
	 * @var varchar(25)
	 */
	public $file_ext;
	/**
	 * @var varchar(50)
	 */
	public $file_mime;
	/**
	 * @var int(11) unsigned
	 */
	public $file_size;

	/*
	 * Constructor
	 */
	function __construct() {
		$this->joosDBModel('#__attached', 'id');
	}

	/**
	 * Загрузка данных по номеру файла
	 * @param int $id - номер файла
	 * @return joosAttached
	 */
	public static function file($id) {
		$file = new self;
		$file->load($id);

		return $file;
	}

	/**
	 * Добавление информации о файле в базу данных
	 * @param string $filename полный путь к файлу
	 * @return self
	 */
	public static function add($filename) {
		joosLoader::lib('files');

		$filedata = Files::filedata($filename);

		$file = new self;
		$file->created_at = _CURRENT_SERVER_TIME;
		$file->user_id = User::current()->id;
		$file->file_ext = $filedata['ext'];
		$file->file_mime = $filedata['mime'];
		$file->file_name = $filedata['name'];
		$file->file_size = $filedata['size'];

		$file->store();

		return $file;
	}

}