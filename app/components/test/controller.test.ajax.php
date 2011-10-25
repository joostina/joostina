<?php

// запрет прямого доступа
defined('_JOOS_CORE') or die();

class actionsAjaxTest {

	public static function upload() {

		joosLoader::lib('upload', 'upload');

		$upload_result = joosUpload::easy_upload('qqfile',  JPATH_BASE.'/cache/tmp/' );

		return $upload_result + array(
			'success' => $upload_result['success']
		);
	}

}