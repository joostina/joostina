<?php

// запрет прямого доступа
defined( '_JOOS_CORE' ) or die();

/**
 * Для вывода большой текстовой области textarea
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
class pluginAutoadminEditTextArea implements joosAutoadminPluginsEdit{

	public static function render( $element_param , $key , $value , $obj_data , $params , $tabs ) {
		$element   = array ();

		$element[] = $params['label_begin'];
		$element[] = forms::label( array ( 'for' => $key ) , $element_param['name'] );
		$element[] = $params['label_end'];
		$element[] = $params['el_begin'];
		$element[] = forms::textarea( array ( 'name'  => $key ,
		                                      'class' => 'text_area' ,
		                                      'rows'  => ( isset( $element_param['html_edit_element_param']['rows'] ) ? $element_param['html_edit_element_param']['rows'] : 6 ) ,
		                                      'cols'  => ( isset( $element_param['html_edit_element_param']['cols'] ) ? $element_param['html_edit_element_param']['cols'] : 40 ) ,
		                                      'style' => ( isset( $element_param['html_edit_element_param']['style'] ) ? $element_param['html_edit_element_param']['style'] : 'width:97%' ) , ) , $value );
		$element[] = $params['el_end'];

		return implode( "\n" , $element );
	}

}