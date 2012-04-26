<?php

// запрет прямого доступа
defined( '_JOOS_CORE' ) or die();

/**
 * Для вывода элемента checkbox
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
class pluginAutoadminEditCheckbox implements joosAutoadminPluginsEdit{

	public static function render( $element_param , $key , $value , $obj_data , $params , $tabs ) {
		$element   = array ();

		$element[] = $params['label_begin'];
		$element[] = forms::label( array ( 'for' => $key ) , ( isset( $element_param['html_edit_element_param']['text'] ) ? $element_param['html_edit_element_param']['text'] : $element_param['name'] ) );
		$element[] = $params['label_end'];
		$element[] = forms::hidden( $key , 0 );
		$element[] = $params['el_begin'];
		$element[] = forms::checkbox( array ( 'name'  => $key ,
		                                      'class' => 'text_area' , ) , 1 , $value );
		$element[] = $params['el_end'];

		return implode( "\n" , $element );
	}

}