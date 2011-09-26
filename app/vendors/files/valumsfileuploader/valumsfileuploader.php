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
 * @deprecated переписать и упростить функционал, адаптировать в класс joosUpload
 */

/**
 * Handle file uploads via XMLHttpRequest
 */
class qqUploadedFileXhr {

	/**
	 * Save the file to the specified path
	 * @return boolean TRUE on success
	 */
	function save($path) {
		$input = fopen("php://input", "r");
		$temp = tmpfile();
		$realSize = stream_copy_to_stream($input, $temp);
		fclose($input);

		if ($realSize != $this->getSize()) {
			return false;
		}

		$target = fopen($path, "w");
		fseek($temp, 0, SEEK_SET);
		stream_copy_to_stream($temp, $target);
		fclose($target);

		return true;
	}

	function getName() {
		return $_GET['qqfile'];
	}

	function getSize() {
		if (isset($_SERVER["CONTENT_LENGTH"])) {
			return (int) $_SERVER["CONTENT_LENGTH"];
		} else {
			throw new Exception('Getting content length is not supported.');
		}
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
		if (!move_uploaded_file($_FILES['qqfile']['tmp_name'], $path)) {
			return false;
		}
		return true;
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
	private $sizeLimit = 209700;
	private $file;

	function __construct(array $allowedExtensions = array(), $sizeLimit = 10485760) {
		$allowedExtensions = array_map("strtolower", $allowedExtensions);

		$this->allowedExtensions = $allowedExtensions;
		$this->sizeLimit = $sizeLimit;

		$this->checkServerSettings();

		if (isset($_GET['qqfile'])) {
			$this->file = new qqUploadedFileXhr();
		} elseif (isset($_FILES['qqfile'])) {
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
			die("{'error':'increase post_max_size and upload_max_filesize to $size'}");
		}
	}

	private function toBytes($str) {
		$val = trim($str);
		$last = strtolower($str[strlen($str) - 1]);
		switch ($last) {
			case 'g':
				$val *= 1024;
			case 'm':
				$val *= 1024;
			case 'k':
				$val *= 1024;
		}
		return $val;
	}

	public static function _toBytes($str) {
		$val = trim($str);
		$last = strtolower($str[strlen($str) - 1]);
		switch ($last) {
			case 'g':
				$val *= 1024;
			case 'm':
				$val *= 1024;
			case 'k':
				$val *= 1024;
		}
		return $val;
	}

	/**
	 * Returns array('success'=>true) or array('error'=>'error message')
	 */
	function handleUpload($uploadDirectory, $replaceOldFile = FALSE, $new_file_name = false) {
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

	public static function upload_temp($allowedExtensions = array(), $sizeLimit = false) {

		$fn_get = joosRequest::get('qqfile', false);
		$fileName = $fn_get ? $fn_get : $_FILES['qqfile']['name'];

		$fileData = joosFile::file_info($fileName);
		$new_file_name = md5(uniqid());
		$new_file_name_full = $new_file_name . '.' . $fileData['ext'];

		$uploader = new qqFileUploader($allowedExtensions, ($sizeLimit ? $sizeLimit : qqFileUploader::_toBytes(ini_get('upload_max_filesize'))));
		$temp_dir = JPATH_BASE . '/cache/tmp/' . time() . '/';
		mkdir($temp_dir, 0755, true);

		$result = $uploader->handleUpload($temp_dir, true, $new_file_name);

		if ($result) {

			$fileName = $new_file_name_full;


			$basename = $temp_dir . $fileName;

			return array('basename' => $basename, 'dir' => $temp_dir);
		}

		echo json_encode($result);
		die();
	}

	public static function upload($newnameforfile = false, $rootdir = false, $fileid = false, $show_result = true, $allowedExtensions = array(), $sizeLimit = false) {

		$fn_get = joosRequest::get('qqfile', false);
		$fileName = $fn_get ? $fn_get : $_FILES['qqfile']['name'];

		$fileData = pathinfo($fileName);
		$new_file_name = md5(uniqid());
		$new_file_name_full = $new_file_name . '.' . $fileData['extension'];

		$uploader = new qqFileUploader($allowedExtensions, ($sizeLimit ? $sizeLimit : qqFileUploader::_toBytes(ini_get('upload_max_filesize'))));
		$result = $uploader->handleUpload(JPATH_BASE . '/cache/tmp/', false, $new_file_name);

		if ($result) {

			$fileName = $new_file_name_full;

			$filelocation = JPATH_BASE . '/cache/tmp/' . $fileName;

			if ($newnameforfile) {
				$filedata = joosFile::file_info($filelocation);
				$fileName = $newnameforfile . '.' . $filedata['ext'];
			}

			$file_upload = self::get_filefolder($rootdir, $filelocation, $fileid);

			is_dir($file_upload['filelocation']) ? null : mkdir($file_upload['filelocation'], 0755, true);

			$file_pach = $file_upload['file'] . DS . $fileName;
			joosFile::move($filelocation, $file_pach, 0755);

			$file_live = str_replace(JPATH_BASE, '', $file_pach);
			$file_live = str_replace('\\', '/', $file_live);
			$file_location = str_replace($fileName, '', $file_live);

			return array(
				'basename' => $file_pach,
				'livename' => $file_live,
				'location' => $file_location,
				'file_id' => $file_upload['file_id'],
				'file_name' => $fileName
			);
		}

		echo json_encode($result);
		die();
	}

	public static function get_filefolder($rootdir = false, $filename = false, $fileid = false) {

		$id = $fileid ? $fileid : joosAttached::add($filename)->id;

		$rootdir = $rootdir ? $rootdir : joosFile::mime_content_type($filename);

		$location = joosFile::make_file_location($id);
		return array(
			'file' => JPATH_BASE . DS . 'attachments' . DS . $rootdir . DS . $location,
			'filelocation' => JPATH_BASE . DS . 'attachments' . DS . $rootdir . DS . $location,
			'file_id' => $id);
	}

}