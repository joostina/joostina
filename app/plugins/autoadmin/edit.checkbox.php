<?php

// запрет прямого доступа
defined( '_JOOS_CORE' ) or die();

/**
 * autoadminEditCheckbox - расширение joosAutoadmin для вывода элемента checkbox
 * Базовый плагин
 *
 * @version    1.0
 * @package    Joostina.Plugins
 * @subpackage Plugins
 * @category   joosAutoadmin
 * @author     Joostina Team <info@joostina.ru>
 * @copyright  (C) 2007-2011 Joostina Team
 * @license    MIT License http://www.opensource.org/licenses/mit-license.php
 * Информация об авторах и лицензиях стороннего кода в составе Joostina CMS: docs/copyrights
 *
 * */
class autoadminEditCheckbox {

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