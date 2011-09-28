<?php

// запрет прямого доступа
defined( '_JOOS_CORE' ) or die();

/**
 * autoadminEditWysiwyg - расширение joosAutoadmin для вывода визуального редактора
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
class autoadminEditWysiwyg {

	public static function render( $element_param , $key , $value , $obj_data , $params , $tabs ) {

		$element       = array ();
		$element[]     = $params['label_begin'];
		$element[]     = forms::label( array ( 'for' => $key ) , $element_param['name'] );
		$element[]     = $params['label_end'];
		$element[]     = $params['el_begin'];

		$editor_params = array ( 'editor' => isset( $element_param['html_edit_element_param']['editor'] ) ? $element_param['html_edit_element_param']['editor'] : 'elrte' ,
		                         'rows'   => isset( $element_param['html_edit_element_param']['rows'] ) ? $element_param['html_edit_element_param']['rows'] : null ,
		                         'cols'   => isset( $element_param['html_edit_element_param']['cols'] ) ? $element_param['html_edit_element_param']['cols'] : null ,
		                         'width'  => isset( $element_param['html_edit_element_param']['width'] ) ? $element_param['html_edit_element_param']['width'] : '"100%"' ,
		                         'height' => isset( $element_param['html_edit_element_param']['height'] ) ? $element_param['html_edit_element_param']['height'] : '200px' , );

		$element[]     = joosEditor::display( $key , $value , $editor_params );
		$element[]     = $params['el_end'];

		joosAutoadmin::add_js_onformsubmit( joosEditor::get_content( $key ) );

		return implode( "\n" , $element );
	}

}