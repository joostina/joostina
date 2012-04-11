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

        // активное правило загрузки для файла
        $rules_name = joosRequest::post('rules_name');

        joosUpload::init($rules_name);

        $upload_result = array();
        
        $upload_result = joosUpload::actions_before($upload_result) + $upload_result;

        $upload_result = joosUpload::easy_upload( joosUpload::get_input_name() , joosUpload::get_upload_location() ) + $upload_result;

        $upload_result = joosUpload::actions_after($upload_result) + $upload_result;
        
        // подчищаем секретные данные
        unset( $upload_result['file_base_location'] );
        
		return $upload_result;
	}

}