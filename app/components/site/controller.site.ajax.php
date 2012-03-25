<?php

// запрет прямого доступа
defined('_JOOS_CORE') or die();

class actionsAjaxSite extends joosControllerAjax {

	public static function upload() {

		joosLoader::lib('upload', 'upload');

		$upload_result = joosUpload::easy_upload('file',  JPATH_BASE.'/cache/tmp/' );

		joosDebug::dump($upload_result);

		require_once 'upload.class.php';

		$upload_handler = new UploadHandler();
		$upload_handler->post();



		return array(
			'success' => true
		);
	}

}