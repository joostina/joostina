<?php defined('_JOOS_CORE') or exit();

/**
 * Работа с загрузкой файлов
 *
 * @version    1.0
 * @package    Core\Libraries
 * @subpackage Trash
 * @category   Libraries
 * @author     Joostina Team <info@joostina.ru>
 * @copyright  (C) 2007-2012 Joostina Team
 * @license    MIT License http://www.opensource.org/licenses/mit-license.php
 * Информация об авторах и лицензиях стороннего кода в составе Joostina CMS: docs/copyrights
 *
 * */
class joosUpload {

	private static $upload_rules;
	private static $active_rules_name;

	public static function init($active_rules_name) {

		if (self::$upload_rules === null) {
			self::$upload_rules = require JPATH_APP_CONFIG . '/uploads.php';
		}

		self::$active_rules_name = $active_rules_name;
	}

	public static function get_active_rules_name() {

		return self::$active_rules_name;
	}

	public static function get_input_name() {

		return self::$active_rules_name;
	}

	public static function get_class() {

		$active_rules = self::$upload_rules[self::$active_rules_name];
		return (isset($active_rules['style']) && isset($active_rules['style']['class'])) ? $active_rules['style']['class'] : '';
	}

	public static function get_upload_url() {

		return JPATH_SITE . '/ajax.index.php?option=site&task=upload';
	}

	public static function get_upload_url_admin() {

		return JPATH_SITE_ADMIN . '/ajax.index.php?option=site&task=upload';
	}

	public static function get_upload_location() {

		$active_rules = self::$upload_rules[self::$active_rules_name];
		if (!isset($active_rules['upload_location'])) {
			throw new joosUploadLibrariesException('Не указан каталог загрузки файлов');
		}

		return $active_rules['upload_location'];
	}

	public static function get_accept_file_types() {

		$active_rules = self::$upload_rules[self::$active_rules_name];
		if (!isset($active_rules['accept_file_types'])) {
			throw new joosUploadLibrariesException('Не указаны типы разрешённых файлов');
		}

		return $active_rules['accept_file_types'];
	}

	public static function get_accept_mime_content_types() {

		$active_rules = self::$upload_rules[self::$active_rules_name];
		return (isset($active_rules['accept_mime_content_types']) && count($active_rules['accept_mime_content_types']) > 0 ) ? $active_rules['accept_mime_content_types'] : false;
	}

	public static function actions_before() {

		$rules_name = self::$upload_rules[self::$active_rules_name];

		$result = array();
		if (isset($rules_name['actions_before']) && is_callable($rules_name['actions_before'])) {
			$result = call_user_func($rules_name['actions_before']);
		}

		return $result;
	}

	public static function actions_after($upload_result) {

		$rules_name = self::$upload_rules[self::$active_rules_name];

		$result = array();
		if (isset($rules_name['actions_after']) && is_callable($rules_name['actions_after'])) {
			$result = call_user_func($rules_name['actions_after'], $upload_result);
		}

		return $result;
	}

	public static function check() {

		// проверка MIME - типа
		$accept_mime_content_types = self::get_accept_mime_content_types();
		if ($accept_mime_content_types !== false) {

			$tmp_file = $_FILES[self::$active_rules_name]['tmp_name'];

			$content_type = joosFile::get_mime_content_type($tmp_file);

			if (!in_array($content_type, $accept_mime_content_types)) {

				// убираем все элементы из массива, даные о файле нельзя дальше передавать
				$upload_result = array();
				$upload_result['success'] = false;
				$upload_result['message'] = 'Файл не загружен, такой тип не разрешён';

				return $upload_result;
			}
		}

		return true;
	}

	/**
	 * Упрощённая процедура загрузки файла
	 * @param string $element_name название элемента массива $_FILES для загрузки
	 * @param string $upload_location каталог размещения загруженного файла
	 * @param array $params массив расширенных парамтеров загрузки
	 * 		string new_name - новое имя для файла
	 * 		string new_extension - переименовать расширение файла
	 *
	 */
	public static function easy_upload($element_name, $upload_location, array $params = array()) {

		$file_name = $_FILES[$element_name]['name'];

		//Если нужно сменить имя файла
		if (isset($params['new_name'])) {

			$file_name = $params['new_name'] . '.' . substr($file_name, strrpos($file_name, '.') + 1);
		} else {

			//иначе - очищаем исходное имя файла от мусора
			$file_name = joosFile::make_safe_name($file_name);
		}

		//директория загрузки
		$upload_location = rtrim($upload_location, '/');

		if (!joosFolder::is_writable($upload_location)) {
			throw new joosUploadLibrariesException('Каталог :upload_location недоступен для создания подкаталогов и записи', array(':upload_location' => $upload_location));
		}

		//если её нет, создаём
		is_dir($upload_location) ? null : mkdir($upload_location, 0755, true);

		//перемещаем файл в директорию назначения
		$file_base_location = $upload_location . DS . $file_name;
		$success = move_uploaded_file($_FILES[$element_name]['tmp_name'], $file_base_location);

		//получаем путь файла для http
		$file_live_location = str_replace(JPATH_BASE, '', $upload_location);
		$file_live_location = str_replace("\\", DS, $file_live_location);

		return array(
			'success' => $success,
			'file_location' => $file_live_location,
			'file_name' => $file_name,
			'file_live_location' => sprintf('%s%s/%s', JPATH_SITE, $file_live_location, $file_name),
			'file_base_location' => sprintf('%s%s/%s', JPATH_BASE, $file_live_location, $file_name),
		);
	}

	private static function get_filefolder($rootdir = false, $filename = false, $fileid = false) {
		joosLoader::lib('attached');

		$id = $fileid ? $fileid : joosAttached::add($filename)->id;

		$rootdir = $rootdir ? $rootdir : File::mime_content_type($filename);

		return array('file' => JPATH_BASE . DS . 'attachments' . DS . $rootdir . DS . File::makefilename($id), 'filelocation' => JPATH_BASE . DS . 'attachments' . DS . $rootdir . DS . File::makefilename($id), 'file_id' => $id);
	}

	private function checkServerSettings() {
		$postSize = $this->toBytes(ini_get('post_max_size'));
		$uploadSize = $this->toBytes(ini_get('upload_max_filesize'));

		if ($postSize < $this->sizeLimit || $uploadSize < $this->sizeLimit) {
			$size = max(1, $this->sizeLimit / 1024 / 1024) . 'M';
			die("{'error':'Нужно увеличить post_max_size и upload_max_filesize до $size'}");
		}
	}

}

/**
 * Обработчик ошибок для библиотеки joosUpload
 */
class joosUploadLibrariesException extends joosException {

}
