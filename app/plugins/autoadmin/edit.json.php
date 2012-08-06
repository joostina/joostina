<?php

// запрет прямого доступа
defined( '_JOOS_CORE' ) or exit;

/**
 * Для вывода вывода вложенных элементов joosAutoadmin сохранённых в JSON формате
 *
 * @version    1.0
 * @package    Plugins
 * @subpackage Autoadmin
 * @author     Joostina Team <info@joostina.ru>
 * @copyright  (C) 2007-2012 Joostina Team
 * @license    MIT License http://www.opensource.org/licenses/mit-license.php
 * Информация об авторах и лицензиях стороннего кода в составе Joostina CMS: docs/copyrights
 *
 * */
class pluginAutoadminEditJson implements joosAutoadminPluginsEdit
{
    public static function render( $element_param , $key , $value , $obj_data , $params )
    {
        $element   = array ();

        $_add_data = isset( $element_param['html_edit_element_param']['call_params'] ) ? $element_param['html_edit_element_param']['call_params'] : null;
        $data      = ( isset( $element_param['html_edit_element_param']['call_from'] ) && is_callable( $element_param['html_edit_element_param']['call_from'] ) ) ? call_user_func( $element_param['html_edit_element_param']['call_from'] , $obj_data , $_add_data ) : null;

        if (!$data) {
            return false;
        }

        $main_key = $key;
        $values   = $obj_data->$main_key;

        foreach ($data as $key => $field) {
            if ( isset( $field['editable'] ) && $field['editable'] == true ) {
                $v         = isset( $values[$key] ) ? $values[$key] : '';
                $element[] = joosAutoadmin::get_edit_html_element( $field , $main_key . '[' . $key . ']' , $v , $obj_data , $params );
            }
        }

        return implode( "\n" , $element );
    }

}
