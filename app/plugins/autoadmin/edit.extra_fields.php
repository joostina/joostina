<?php

// запрет прямого доступа
defined( '_JOOS_CORE' ) or die();

/**
 * Для вывода вывода вложенных элементов joosAutoadmin
 *
 * @version    1.0
 * @package    Plugins
 * @subpackage joosAutoadmin
 * @category   joosAutoadmin
 * @author     Joostina Team <info@joostina.ru>
 * @copyright  (C) 2007-2012 Joostina Team
 * @license    MIT License http://www.opensource.org/licenses/mit-license.php
 * Информация об авторах и лицензиях стороннего кода в составе Joostina CMS: docs/copyrights
 *
 * */
class autoadminEditExtraFields implements joosAutoadminPluginsEdit{

	public static function render( $element_param , $key , $value , $obj_data , $params , $tabs ) {
		$element = array ();

		$data    = ( isset( $element_param['html_edit_element_param']['call_from'] ) && is_callable( $element_param['html_edit_element_param']['call_from'] ) ) ? call_user_func( $element_param['html_edit_element_param']['call_from'] , $obj_data ) : null;

		if ( !$data ) {
			return false;
		}

		$element[] = $params['label_begin'];
		$element[] = forms::label( array ( 'for' => $key ) , ( isset( $element_param['html_edit_element_param']['text'] ) ? $element_param['html_edit_element_param']['text'] : $element_param['name'] ) );

		$element[] = $params['label_end'];
		$element[] = $params['el_begin'];

		$main_key  = $key;
		$values    = isset( $data['values'] ) ? $data['values'] : array ();

		$element[] = '<table class="admin_extrafields">';
		foreach ( $data['rules'] as $key => $field ) {
			if ( isset( $field['editable'] ) && $field['editable'] == true ) {
				$v         = isset( $values[$key] ) ? $values[$key] : '';
				$element[] = joosAutoadmin::get_edit_html_element( $field , $main_key . '[' . $key . ']' , $v , $obj_data , $params , $tabs );
			}
		}
		$element[] = '</table>';
		$element[] = $params['el_end'];

		return implode( "\n" , $element );
	}

}