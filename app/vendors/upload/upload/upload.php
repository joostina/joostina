<?php

class joosUpload {

	public static function upload_form() {

	}

	/**
	 * Упрощённая процедура загрузки файла
	 * @param string $element_name название элемента массива $_FILES для загрузки
	 * @param string $upload_location каталог размещения загруженного файла
	 * @param array $params массив расширенных парамтеров загрузки
	 * 		string new_name - переименовать файл
	 * 		string new_extension - переименовать расширение файла
	 *
	 */
	public static function easy_upload($element_name, $upload_location, array $params = array()) {

		$file_name = joosFilter::filename($_FILES[$element_name]['name']);

		$upload_location = rtrim($upload_location, '/');

		$file_base_location = $upload_location . DS . $file_name;

		$success = move_uploaded_file($_FILES[$element_name]['tmp_name'], $file_base_location);

		$file_live_location = str_replace(JPATH_BASE, '', $upload_location);
		$file_live_location = str_replace("\\", DS, $file_live_location);

		return array(
			'location' => $file_live_location,
			'base_location' => $file_base_location,
			'name' => $file_name,
			'file' => $file_live_location . '/' . $file_name,
			'success' => $success
		);
	}

}

/**
 * Handle file uploads via regular form post (uses the $_FILES array)
 */
class qqUploadedFileForm {

	/**
	 * Save the file to the specified path
	 * @return boolean TRUE on success
	 */
	function save($path) {
		return (bool) move_uploaded_file($_FILES['qqfile']['tmp_name'], $path);
	}

	function getName() {
		return $_FILES['qqfile']['name'];
	}

	function getSize() {
		return $_FILES['qqfile']['size'];
	}

}

class qqFileUploader {

	private $allowedExtensions = array();
	private $sizeLimit = 10485760;
	private $file;

	function __construct(array $allowedExtensions = array(), $sizeLimit = 10485760) {
		$allowedExtensions = array_map("strtolower", $allowedExtensions);

		$this->allowedExtensions = $allowedExtensions;
		$this->sizeLimit = $sizeLimit;

		$this->checkServerSettings();

		if (isset($_FILES['qqfile'])) {
			$this->file = new qqUploadedFileForm();
		} else {
			$this->file = false;
		}
	}

	private function checkServerSettings() {
		$postSize = $this->toBytes(ini_get('post_max_size'));
		$uploadSize = $this->toBytes(ini_get('upload_max_filesize'));

		if ($postSize < $this->sizeLimit || $uploadSize < $this->sizeLimit) {
			$size = max(1, $this->sizeLimit / 1024 / 1024) . 'M';
			die("{'error':'Нужно увеличить post_max_size и upload_max_filesize до $size'}");
		}
	}

	public static function toBytes($str) {
		$val = trim($str);
		$last = strtolower($str[strlen($str) - 1]);
		switch ($last) {
			case 'g': $val *= 1024;
			case 'm': $val *= 1024;
			case 'k': $val *= 1024;
		}
		return $val;
	}

	/**
	 * Returns array('success'=>true) or array('error'=>'error message')
	 */
	function handleUpload($uploadDirectory, $replaceOldFile = FALSE, $new_file_name = false, $only_check = false) {
		if (!is_writable($uploadDirectory)) {
			return array('error' => "Server error. Upload directory isn't writable.");
		}

		if (!$this->file) {
			return array('error' => 'No files were uploaded.');
		}

		$size = $this->file->getSize();

		if ($size == 0) {
			return array('error' => 'File is empty');
		}

		if ($size > $this->sizeLimit) {
			return array('error' => 'File is too large');
		}

		$pathinfo = pathinfo($this->file->getName());
		$filename = $new_file_name ? $new_file_name : $pathinfo['filename'];
		$ext = $pathinfo['extension'];

		if ($this->allowedExtensions && !in_array(strtolower($ext), $this->allowedExtensions)) {
			$these = implode(', ', $this->allowedExtensions);
			return array('error' => 'File has an invalid extension, it should be one of ' . $these . '.');
		}

		//если только проверка входных данных - уходим отсюда
		if ($only_check)
			return array('success' => true);

		if (!$replaceOldFile) {
			/// don't overwrite previous files that were uploaded
			while (file_exists($uploadDirectory . $filename . '.' . $ext)) {
				$filename .= rand(10, 99);
			}
		}

		if ($this->file->save($uploadDirectory . $filename . '.' . $ext)) {
			return array('success' => true);
		} else {
			return array('error' => 'Could not save uploaded file.' .
				'The upload was cancelled, or server error encountered');
		}
	}

}

class ValumsfileUploader {

	public static function upload($newnameforfile = false, $rootdir = false, $fileid = false, $show_result = true, $allowedExtensions = array(), $sizeLimit = false) {
		joosLoader::lib('files', 'joostina');

		$fn_get = joosRequest::get('qqfile', false);
		$fileName = $fn_get ? $fn_get : $_FILES['qqfile']['name'];

		$fileData = File::ext($fileName);
		$new_file_name = md5(uniqid());
		$new_file_name_full = $new_file_name . '.' . $fileData['extension'];

		$uploader = new qqFileUploader($allowedExtensions, ($sizeLimit ? $sizeLimit : 100 * 1024 * 1024));
		$result = $uploader->handleUpload(JPATH_BASE . '/tmp/', false, $new_file_name);

		if ($result) {

			$fileName = $new_file_name_full;

			$filelocation = JPATH_BASE . '/tmp/' . $fileName;

			if ($newnameforfile) {
				$filedata = File::ext($filelocation);
				$fileName = $newnameforfile . '.' . $filedata['extension'];
			}

			// JPEG это пусть будет JPG
			$fileName = str_replace('.jpeg', '.jpg', $fileName);
			$fileName = strtolower($fileName);

			$file_upload = self::get_filefolder($rootdir, $filelocation, $fileid);
			$file_pach = $file_upload['file'] . DS . $fileName;

			is_dir($file_upload['filelocation']) ? null : mkdir($file_upload['filelocation'], 0755, true);

			$file = new File(0755);
			$file_pach = $file_upload['file'] . DS . $fileName;
			$file->move($filelocation, $file_pach);

			$file_live = str_replace(JPATH_BASE, '', $file_pach);
			$file_live = str_replace('\\', '/', $file_live);
			$file_location = str_replace($fileName, '', $file_live);

			return array('basename' => $file_pach, 'livename' => $file_live, 'location' => $file_location, 'file_id' => $file_upload['file_id'], 'file_name' => $fileName);
		}

		echo json_encode($result);
		die();
	}

	private static function get_filefolder($rootdir = false, $filename = false, $fileid = false) {
		joosLoader::lib('attached');

		$id = $fileid ? $fileid : attached::add($filename)->id;

		$rootdir = $rootdir ? $rootdir : File::mime_content_type($filename);

		return array('file' => JPATH_BASE . DS . 'attachments' . DS . $rootdir . DS . File::makefilename($id), 'filelocation' => JPATH_BASE . DS . 'attachments' . DS . $rootdir . DS . File::makefilename($id), 'file_id' => $id);
	}

}