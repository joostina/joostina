<?php

// запрет прямого доступа
defined('_JOOS_CORE') or die();

/**
 * joosAttached - Библиотека работы с вложениями, загрузками, аттачами
 * Системная библиотека
 *
 * @version 1.0
 * @package Joostina.Libraries
 * @subpackage Libraries
 * @category Libraries
 * @author Joostina Team <info@joostina.ru>
 * @copyright (C) 2008-2011 Joostina Team
 * @license MIT License http://www.opensource.org/licenses/mit-license.php
 * Информация об авторах и лицензиях стороннего кода в составе Joostina CMS: docs/copyrights
 *
 * */
class joosAttached extends joosModel {

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
		parent::__construct('#__attached', 'id');
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
		$file->user_id = Users::current()->id;
		$file->file_ext = $filedata['ext'];
		$file->file_mime = $filedata['mime'];
		$file->file_name = $filedata['name'];
		$file->file_size = $filedata['size'];

		$file->store();

		return $file;
	}

}