<?php defined('_JOOS_CORE') or die();

/**
 * Компонент ядра системы
 * Контроллер сайта афякс функций
 *
 * @version    1.0
 * @package    Components\Site
 * @subpackage Controllers\Site
 * @author     Joostina Team <info@joostina.ru>
 * @copyright  (C) 2007-2012 Joostina Team
 * @license    MIT License http://www.opensource.org/licenses/mit-license.php
 * Информация об авторах и лицензиях стороннего кода в составе Joostina CMS: docs/copyrights
 *
 * */
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