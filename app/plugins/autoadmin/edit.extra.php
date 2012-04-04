<?php

// запрет прямого доступа
defined( '_JOOS_CORE' ) or die();

/**
 * Для вывода данных сформированных произвольной функцией
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
class autoadminEditExtra implements joosAutoadminPluginsEdit{

	public static function render( $element_param , $key , $value , $obj_data , $params , $tabs ) {
		$element = array ();

		// скрываем левую колонку с названием поля
		if ( !isset( $element_param['html_edit_element_param']['hidden_label'] ) ) {
			$element[] = $params['label_begin'];
			$element[] = forms::label( array ( 'for' => $key ) , ( isset( $element_param['html_edit_element_param']['text'] ) ? $element_param['html_edit_element_param']['text'] : $element_param['name'] ) );

			$element[] = $params['label_end'];
		}
		$element[] = $params['el_begin'];
		$element[] = ( isset( $element_param['html_edit_element_param']['call_from'] ) && is_callable( $element_param['html_edit_element_param']['call_from'] ) ) ? call_user_func( $element_param['html_edit_element_param']['call_from'] , $obj_data ) : $datas_for_select;
		$element[] = forms::hidden( 'extrafields[]' , $key );
		$element[] = $params['el_end'];
		return implode( "\n" , $element );
	}

}