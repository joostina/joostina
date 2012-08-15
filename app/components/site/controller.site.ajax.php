<?php defined('_JOOS_CORE') or exit;

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
class actionsAjaxSite extends joosController
{
    public function upload()
    {
        // активное правило загрузки для файла
        $rules_name = joosRequest::post('rules_name');

        joosUpload::init($rules_name);

        $upload_result = array();

        $check = joosUpload::check();
        if ($check === true) {

            $upload_result = joosUpload::actions_before() + $upload_result;
            $upload_result = joosUpload::easy_upload( joosUpload::get_input_name() , joosUpload::get_upload_location() ) + $upload_result;
            $upload_result = joosUpload::actions_after($upload_result) + $upload_result;

            // удаляем физически файл если проверки не прошли в пользователю выдаём ошибку
            if ($upload_result['success']!==true) {
                joosFile::delete( $upload_result['file_base_location'] );
            }

        } else {

            $upload_result = $check;
        }

        // подчищаем секретные данные
        unset( $upload_result['file_base_location'] );

        return $upload_result;
    }

    // добавление товара в корзину
    public function add_to_basket()
    {
        $obj_name = joosRequest::post('obj');
        $obj_id = joosRequest::post('obj_id');

        return joosBasket::add($obj_name,$obj_id);
    }

}
